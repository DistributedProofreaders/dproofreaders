<?
include($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');

function project_get_auto_PPer( $projectid )
// Return the username of the user to whom the project will be
// automatically checked out for PPing when it reaches the PP stage,
// or NULL if the project will merely go into the available-for-PP state.
{
    $res = mysql_query("
        SELECT checkedoutby, username
        FROM projects
        WHERE projectid = '$projectid'
    ");
    list($checkedoutby, $username) = mysql_fetch_row($res);

    if ( $checkedoutby != '' )
    {
        // The project is reserved for a PPer, so it will be checked out to him/her.
        return $checkedoutby;
    }
    else
    {
        // The project does not have a reserved PPer.

        $res = mysql_query("
            SELECT value
            FROM usersettings
            WHERE username = '$username' AND setting = 'send_to_post' AND value = 'yes'
        ");
        if ( mysql_num_rows($res) > 0 )
        {
            // The PM has send_to_post=yes, so his/her projects go straight to PP.avail.
            return NULL;
        }
        else
        {
            // Otherwise, his/her projects are auto-checked-out to him/her.
            return $username;
        }
    }
}

    function sendtopost($projectid, $username) {
        // Prepare a project for post-processing.
        //
        // $projectid: the unique id of the project.
        // $username: the DP username of the owner(?) of the project.

        $auto_PPer = project_get_auto_PPer($projectid);
        if ( is_null($auto_PPer) )
        {
            $new_state = PROJ_POST_FIRST_AVAILABLE;
            $extras = array();
        }
        else
        {
            $new_state = PROJ_POST_FIRST_CHECKED_OUT;
            $extras = array( 'checkedoutby' => $auto_PPer );
        }

        $error_msg = project_transition( $projectid, $new_state, PT_AUTO, $extras );
        if ( $error_msg )
        {
            echo "$error_msg<br>\n";
            return;
        }
    }
?>
