<?
// This file defines the class 'Stage',
// which represents a stage in the life of a project,
// or the set of all projects currently in that stage,
// or an area that houses that set of projects
// (depending on your point of view).
//
// It's a generalization of the existing ideas of 'round' and 'pool'.

include_once($relPath.'gettext_setup.inc');
include_once($relPath.'TallyBoard.inc');

$Stage_for_id_ = array();

class Stage
{
    function Stage(
        $id,
            // A very short mnemonic identifier for the stage.
            // (Should probably conform to the rules for a PHP variable name.)
        $name,
            // A gettext-translated name for the stage.
        $access_minima,
            // An array of minimum requirements that a user must satisfy
            // in order to be allowed to participate in this stage
            // (barring special permission).
            // If empty array, no minima: everyone can participate.
            // If NULL, users cannot qualify for this stage by satisfying minima.
        $after_satisfying_minima,
            // After satisfying the above minima, does the user have to do
            // anything else to work in this stage?
            //     'IMMEDIATE'  Nope, they get immediate access.
            //     'REQ-AUTO'   They must ask for access, but it is auto-granted.
            //     'REQ-HUMAN'  They must ask for access, and it must be human-granted.
        $description,
            // A sentence or two explaining what happens in this stage.
        $listing_bgcolors,
            // An array of (two) HTML colors that will be used as the bgcolor
            // in alternating rows in the listing of projects in this round.

        $relative_url
            // The "home" location (relative to $code_url/) of the stage.
    )
    {
        $this->id            = $id;
        $this->name          = $name;
        $this->access_minima = $access_minima;
        $this->after_satisfying_minima = $after_satisfying_minima;
        $this->description   = $description;
        $this->relative_url  = $relative_url;
        $this->listing_bgcolors = $listing_bgcolors;

        global $testing;
        if ($testing && !is_null($this->access_minima))
        {
            // Relax minima.
            foreach ( $this->access_minima as $criterion_code => $minimum )
            {
                if ( $criterion_code == 'quiz' )
                {
                    // skip
                }
                else if ( $criterion_code == 'days since reg' )
                {
                    $this->access_minima[$criterion_code] = intval($minimum / 7);
                }
                else
                {
                    $this->access_minima[$criterion_code] = intval($minimum / 30);
                }
            }
        }

        global $Stage_for_id_;
        $Stage_for_id_[$id] =& $this;
    }

    function user_access( $username, $n_pages_completed=null )
    // Return an object with the following properties:
    // -- can_access:
    //         a boolean: TRUE iff the user can access this stage.
    // -- minima_table:
    //         an array of arrays (4-tuples):
    //         ( $criterion_str, $minimum, $user_value, $satisfied )
    // -- all_minima_satisfied:
    //         boolean
    // -- request_status:
    //         enumerated type
    // -- sitemanager_trumps:
    //         boolean
    // 
    // UNIMPLEMENTED:
    // If $n_pages_completed is non-null, use it as the number of pages
    // that the user has completed. Otherwise, consult the database.
    {
        $res = mysql_query("
            SELECT *
            FROM users
            WHERE username='$username'
        ") or die(mysql_error());

        if ( mysql_num_rows($res) == 0 )
        {
            die( "User '$username' does not exist." );
        }

        $user_obj = mysql_fetch_object($res);

        // -----------------------------------

        $res = mysql_query("
            SELECT value
            FROM usersettings
            WHERE username='$username' AND setting='{$this->id}.access'
        ") or die(mysql_error());
        if ( mysql_num_rows($res) == 0 )
        {
            $recorded_access = 'no';
        }
        elseif ( mysql_num_rows($res) == 1 )
        {
            $recorded_access = mysql_result($res,0);
        }
        else
        {
            // Not sure what to do.
            $recorded_access = mysql_result($res,0);
        }

        // -----------------------------------

        $uao = new StdClass; // user access object

        $uao->stage_id = $this->id;

        // Considering the minima...
        {
            $uao->minima_table = array();
            $uao->all_minima_satisfied = TRUE;
            foreach ( $this->access_minima as $criterion_code => $minimum )
            {
                if ( $criterion_code == 'days since reg' )
                {
                    $criterion_str = _('days since registration');
                    $user_value = sprintf( '%.1f', ( time() - $user_obj->date_created ) / 86400 );
                }
                else if ( $criterion_code == 'quiz' )
                {
                    assert( $minimum == 1 );
                    $criterion_str = _('proofreading quiz pass');
                    // UNIMPLEMENTED
                    // For now, say they have passed.
                    $user_value = 1;
                }
                else
                {
                    $tally_name = $criterion_code;
                    $criterion_str = sprintf( _("'%s' pages completed"), $tally_name );
                    $tallyboard = new TallyBoard( $tally_name, 'U' );
                    $user_value = $tallyboard->get_current_tally( $user_obj->u_id );
                }
                $satisfied = ( $user_value >= $minimum );
                $uao->minima_table[$criterion_code] = array( $criterion_str, $minimum, $user_value, $satisfied );
                if ( !$satisfied ) $uao->all_minima_satisfied = FALSE;
            }

        }

        if ($uao->all_minima_satisfied)
        {
            switch ($this->after_satisfying_minima)
            {
                case 'IMMEDIATE':
                    // They get immediate access.
                    $uao->request_status = 'sat-unneeded';
                    $uao->can_access = TRUE;
                    break;

                case 'REQ-AUTO':
                    // They must ask for access, but it is auto-granted.
                    if ( $recorded_access == 'yes' )
                    {
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    else
                    {
                        $uao->request_status = 'sat-available';
                        $uao->can_access = FALSE;
                    }
                    break;

                case 'REQ-HUMAN':
                    // They must ask for access, and it must be human-granted.
                    if ( $recorded_access == 'yes' )
                    {
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    elseif ( $recorded_access == 'requested' )
                    {
                        $uao->request_status = 'sat-requested';
                        $uao->can_access = FALSE;
                    }
                    else
                    {
                        $uao->request_status = 'sat-available';
                        $uao->can_access = FALSE;
                    }
                    break;

                default:
                    die( "bad after_satisfying_minima value: '$this->after_satisfying_minima'" );
            }
        }
        else
        {
            if ( $recorded_access == 'yes' )
            {
                $uao->request_status = 'unsat-granted';
                $uao->can_access = TRUE;
            }
            else
            {
                $uao->request_status = 'unsat-ungranted';
                $uao->can_access = FALSE;
            }
        }

        // Site managers can go anywhere.
        $uao->sitemanager_trumps = FALSE;
        if ( !$uao->can_access )
        {
            if ($user_obj->sitemanager == 'yes')
            {
                $uao->can_access = TRUE;
                $uao->sitemanager_trumps = TRUE;
            }
        }

        return $uao;
    }

}

// --------------------------------------------------------------------------

function show_user_access_object( $uao )
{
    global $code_url;

    if ( count($uao->minima_table) == 0 )
    {
        echo _("There are no minimum requirements associated with this stage.");
    }
    else
    {
        echo _('Entrance Requirements') . ":\n";
        echo "<table border='1'>\n";

        echo "<tr>";
        echo "<th>" . _('Criterion') . "</th>";
        echo "<th>" . _('Minimum')  . "</th>";
        echo "<th>" . _('You')      . "</th>";
        echo "</tr>\n";

        foreach ( $uao->minima_table as $row )
        {
            list($criterion_str, $minimum, $user_value, $satisfied) = $row;
            $bgcolor = ( $satisfied ? '#ccffcc' : '#ffcccc' );
            echo "<tr>";
            echo "<td>$criterion_str</td>";
            echo "<td>$minimum</td>";
            echo "<td bgcolor='$bgcolor'>$user_value</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";

        if ( $uao->all_minima_satisfied )
        {
            echo _('You satisfy the requirements.');
        }
        else
        {
            echo _('You do not satisfy the requirements.');
        }
    }
    echo "\n";

    switch( $uao->request_status )
    {
        case 'sat-unneeded':
            echo _('So you are allowed to work in this stage.');
            break;

        case 'sat-granted':
            echo _('You have requested and received permission.');
            break;

        case 'sat-requested':
            echo _('You have requested permission, but it has not been granted yet.');
            break;

        case 'sat-available':
            echo sprintf(
                _('If you would like to work in this stage, <a href="%s">click here</a> to submit a request.'),
                "$code_url/tools/request_access.php?stage_id={$uao->stage_id}"
            );
            break;

        case 'unsat-granted':
            echo _("However, you have been granted access to this stage.");
            break;

        case 'unsat-ungranted':
            // Don't mention possibility of special permission.
            // echo _("However, you can be granted access to this stage. See XXX for details.");
            break;

        default:
            die( "bad request_status '$uao->request_status'" );
    }
    echo "\n";
    
    if ( $uao->sitemanager_trumps )
    {
        echo _("But ultimately, you're a site manager, so you have access to everywhere.");
    }
    echo "\n";

    echo "<br>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function & get_Stage_for_id( $id )
{
    global $Stage_for_id_;
    if ( array_key_exists( $id, $Stage_for_id_ ) )
    {
        return $Stage_for_id_[$id];
    }
    else
    {
        die( "There is no stage with id='$id'." );
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function user_can_work_in_stage( $username, $stage_id )
{
    $stage = get_Stage_for_id($stage_id);
    $uao = $stage->user_access($username);
    return $uao->can_access;
}

// vim: sw=4 ts=4 expandtab
?>
