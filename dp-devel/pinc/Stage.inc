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
        $description,
            // A sentence or two explaining what happens in this stage.
        $relative_url
            // The "home" location (relative to $code_url/) of the stage.
    )
    {
        $this->id            = $id;
        $this->name          = $name;
        $this->access_minima = $access_minima;
        $this->description   = $description;
        $this->relative_url  = $relative_url;

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
    // Return an array containing:
    // -- a boolean: TRUE iff the user can access this stage.
    // -- NULL or an array of arrays (4-tuples):
    //         ( $criterion_str, $minimum, $user_value, $satisfied )
    // -- a non-empty array of strings: sentences (addressed to "you")
    //         saying why you or don't have access.
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


        $minima_table = null;
        $sentences = array();

        // Considering the minima...

        if ( is_null($this->access_minima) )
        {
            $sentences[] = _("Users cannot qualify for this stage by satisfying minima.");
            $can = FALSE;
        }
        else if ( empty($this->access_minima) )
        {
            $minima_table = null;
            $sentences[] = _("All users can work in this stage.");
            $can = TRUE;
        }
        else
        {
            $minima_table = array();
            $all_minima_satisfied = TRUE;
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
                $minima_table[] = array( $criterion_str, $minimum, $user_value, $satisfied );
                if ( !$satisfied ) $all_minima_satisfied = FALSE;
            }

            if ( $all_minima_satisfied )
            {
                $sentences[] = _('You satisfy the minima, so you are allowed in the stage.');
                $can = TRUE;
            }
            else
            {
                $sentences[] = _('You do not satisfy the minima.');
                $can = FALSE;
            }
        }

        // Considering special grant of access:
        // A user can be given access to this stage (regardless
        // of how they stand w.r.t access minima) via a notation in the
        // database (specific to that user and this stage).
        if (!$can)
        {
            if ( $this->user_has_been_granted_access($username) )
            {
                $can = TRUE;
                $sentences[] = _("However, you have been granted access to this stage.");
            }
            else
            {
                $can = FALSE;
                // Don't mention possibility of special permission.
                // $sentences[] = _("However, you can be granted access to this stage. See XXX for details.");
            }
        }

        // Site managers can go anywhere.
        if ( !$can )
        {
            if ($user_obj->sitemanager == 'yes')
            {
                $can = TRUE;
                $sentences[] = _("But ultimately, you're a site manager, so you have access to everywhere.");
            }
        }

        return array( $can, $minima_table, $sentences );
    }

    function user_has_been_granted_access( $username )
    {
        $res = mysql_query("
            SELECT value
            FROM usersettings
            WHERE username='$username' AND setting='{$this->id}.access'
        ") or die(mysql_error());
        return (mysql_num_rows($res) >= 1 and mysql_result($res,0) == 'yes');
    }

}

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

// vim: sw=4 ts=4 expandtab
?>
