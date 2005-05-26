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
include_once($relPath.'quizzes.inc');

$Stage_for_id_ = array();

$CRITERIA = array(
    'days since reg' => _('days since registration'),
    'quiz'           => sprintf(_("<a href='%s'>formatting quiz</a> pass"),"$code_url/faq/quiz/quiz_status.php"),
);
if ( TRUE )
{
    // kludge
    $CRITERIA['R*+P1'] = sprintf( _("'%s' pages completed"), 'R1+R2+P1' );
}
// extended with round-ids as they are declared

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

        global $CRITERIA;
        if (is_a($this,'Round'))
        {
            $CRITERIA[$this->id] =
                sprintf( _("'%s' pages completed"), $this->id );
        }
    }

    function user_access( $username, $n_pages_completed=null )
    // Return an object with the following properties:
    // -- can_access:
    //         a boolean: TRUE iff the user can access this stage.
    // -- minima_table:
    //         an array of arrays (4-tuples):
    //         ( $criterion_str, $minimum, $user_score, $satisfied )
    // -- all_minima_satisfied:
    //         boolean
    // -- request_status:
    //         enumerated type
    // 
    // UNIMPLEMENTED:
    // If $n_pages_completed is non-null, use it as the number of pages
    // that the user has completed. Otherwise, consult the database.
    {
        $user_scores = get_user_scores($username);

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
        global $CRITERIA;
        {
            $uao->minima_table = array();
            $uao->all_minima_satisfied = TRUE;
            foreach ( $this->access_minima as $criterion_code => $minimum )
            {
                $criterion_str = $CRITERIA[$criterion_code];
                $user_score = $user_scores[$criterion_code];

                $satisfied = ( $user_score >= $minimum );
                $uao->minima_table[$criterion_code] = array( $criterion_str, $minimum, $user_score, $satisfied );
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

                case 'NOREQ':
                    // They don't request access (or at least, we don't supply a link
                    // by which to request access). Instead, they just wait until
                    // they are approved.
                    if ( $recorded_access == 'yes' )
                    {
                        $uao->request_status = 'sat-granted';
                        $uao->can_access = TRUE;
                    }
                    else
                    {
                        $uao->request_status = 'sat-wait';
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

        return $uao;
    }

}

// --------------------------------------------------------------------------

function get_user_scores($username)
{
    global $CRITERIA, $FQ;

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

    $user_scores = array();
    foreach ( $CRITERIA as $criterion_code => $criterion_descr )
    {
        if ( $criterion_code == 'days since reg' )
        {
            $user_score = round( ( time() - $user_obj->date_created ) / 86400, 1 );
        }
        else if ( $criterion_code == 'quiz' )
        {
            $user_score = $FQ->user_has_passed($username) ? 1 : 0;
        }
        else if ( $criterion_code == 'R*+P1' )
        {
            // kludge
            $tallyboard1 = new TallyBoard( 'R*', 'U' );
            $tallyboard2 = new TallyBoard( 'P1', 'U' );
            $user_score =
                $tallyboard1->get_current_tally( $user_obj->u_id ) +
                $tallyboard2->get_current_tally( $user_obj->u_id );
        }
        else
        {
            $tally_name = $criterion_code;
            $tallyboard = new TallyBoard( $tally_name, 'U' );
            $user_score = $tallyboard->get_current_tally( $user_obj->u_id );
        }
        $user_scores[$criterion_code] = $user_score;
    }
    return $user_scores;
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
            list($criterion_str, $minimum, $user_score, $satisfied) = $row;
            $bgcolor = ( $satisfied ? '#ccffcc' : '#ffcccc' );
            echo "<tr>";
            echo "<td>$criterion_str</td>";
            echo "<td>$minimum</td>";
            echo "<td bgcolor='$bgcolor'>$user_score</td>";
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
            echo _('You have received permission to work in this stage.');
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

        case 'sat-wait':
            echo _('However, you must wait for approval to work in this stage.');
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
    
    echo "<br>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_user_access_chart( $username )
{
    global $CRITERIA, $code_url;

    list($allow_grant,$allow_revoke) = user_can_modify_access();

    if ( $allow_grant || $allow_revoke )
    {
        echo "<form method='post' action='$code_url/tools/modify_access.php'>\n";
        echo "<input type='hidden' name='subject_username' value='$username'>\n";
    }

    echo "<table border='1'>";

    // top header line
    {
        $n_criteria = count($CRITERIA);
        echo "<tr>";
        echo "<th></th>";
        echo "<th colspan='$n_criteria'>Criteria</th>";
        echo "<th rowspan='3'>all sat?</th>";
        echo "<th rowspan='3'>request</th>";
        echo "<th rowspan='3'>ACCESS?</th>";
        if ( $allow_grant )
        {
            echo "<th rowspan='3'>grant?</th>";
        }
        if ( $allow_revoke )
        {
            echo "<th rowspan='3'>revoke?</th>";
        }
        echo "</tr>\n";
    }

    // bottom header line
    {
        echo "<tr>";
        echo "<th></th>";
        foreach ( $CRITERIA as $criterion_code => $criterion_descr )
        {
            echo "<th>$criterion_code</th>";
        }
        echo "</tr>\n";
    }

    // user scores line
    {
        $user_scores = get_user_scores($username);

        echo "<tr>";
        echo "<th>user score:</th>";
        foreach ( $CRITERIA as $criterion_code => $criterion_descr )
        {
            $user_score = $user_scores[$criterion_code];
            echo "<td align='right'>$user_score</td>";
        }
        echo "</tr>\n";
    }

    // stage lines
	global $Stage_for_id_;
	foreach ( $Stage_for_id_ as $stage )
    {
		$uao = $stage->user_access($username);

        echo "<tr>";
        echo "<th>$stage->id</th>";
        foreach ( $CRITERIA as $criterion_code => $criterion_descr )
        {
            $row = @$uao->minima_table[$criterion_code];
            if (is_null($row))
            {
                echo "<td></td>";
            }
            else
            {
                list($criterion_str, $minimum, $user_score, $satisfied) = $row;
                echo td_w_bgcolor( $minimum, $satisfied );
            }
        }

        echo td_w_bgcolor(
            ($uao->all_minima_satisfied ? 'yes' : 'no'),
            $uao->all_minima_satisfied );

        $could_grant = FALSE;
        $could_revoke = FALSE;
        global $code_url;
        echo "<td>";
        switch( $uao->request_status )
        {
            case 'sat-unneeded':
                echo 'unnec';
                break;

            case 'sat-granted':
            case 'unsat-granted':
                echo 'granted';
                $could_revoke = TRUE;
                break;

            case 'sat-requested':
                echo 'requested';
                $could_grant = TRUE;
                break;

            case 'sat-wait':
                echo 'waiting';
                $could_grant = TRUE;
                break;

            case 'sat-available':
                echo 'not yet';
                $could_grant = TRUE;
                break;

            case 'unsat-ungranted':
                $could_grant = TRUE;
                break;

            default:
                die( "bad request_status '$uao->request_status'" );
        }
        echo "</td>";

        echo td_w_bgcolor( ($uao->can_access?'YES':'NO'), $uao->can_access );

        if ( $allow_grant )
        {
            echo "<td>";
            if ( $could_grant )
            {
                echo "<input type='checkbox' name='{$stage->id}|grant'>";
            }
            echo "</td>";
        }

        if ( $allow_revoke )
        {
            echo "<td>";
            if ( $could_revoke )
            {
                echo "<input type='checkbox' name='{$stage->id}|revoke'>";
            }
            echo "</td>";
        }

        echo "</tr>\n";
    }

    echo "</table>\n";

    if ( $allow_grant || $allow_revoke )
    {
        echo "<input type='submit' value='Submit Changes'>\n";
        echo "</form>\n";
    }
}

function td_w_bgcolor( $text, $bool )
{
	$bgcolor = ( $bool ? '#ccffcc' : '#ffcccc' );
	return "<td align='right' bgcolor='$bgcolor'>$text</td>";
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
