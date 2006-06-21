<?
include($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'Project.inc'); // project_get_auto_PPer

    function sendtopost($projectid, $username) {
        // Prepare a project for post-processing.
        //
        // $projectid: the unique id of the project.
        // $username: the DP username of the owner(?) of the project.

        $auto_PPer = project_get_auto_PPer($projectid);
        if ( is_null($auto_PPer) )
        {
            $new_state = PROJ_POST_FIRST_AVAILABLE;
        }
        else
        {
            $new_state = PROJ_POST_FIRST_CHECKED_OUT;
        }

        $error_msg = project_transition( $projectid, $new_state, PT_AUTO );
        if ( $error_msg )
        {
            echo "$error_msg<br>\n";
            return;
        }
    }
?>
