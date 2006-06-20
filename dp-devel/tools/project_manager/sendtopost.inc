<?
include($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'maybe_mail.inc');

    function sendtopost($project, $username) {
        // Prepare a project for post-processing.
        //
        // $project: the unique id of the project.
        // $username: the DP username of the owner(?) of the project.

        // if reserved for a PPer, check it out to them
        $result = mysql_query("SELECT checkedoutby,nameofwork FROM projects WHERE projectid = '$project'");
        if (mysql_num_rows($result) > 0 && mysql_result($result,0) != '') {
            $new_state = PROJ_POST_FIRST_CHECKED_OUT;
            $PPer = mysql_result($result,0,"checkedoutby");
            $extras = array( 'checkedoutby' => $PPer );
        } else {

	        // Decide whether to send to post-processing or have PM work on it.

	        $result = mysql_query("SELECT value FROM usersettings WHERE setting = 'send_to_post' AND username='$username'");
        	if (mysql_num_rows($result) > 0 && mysql_result($result, 0) == 'yes') {
	            $new_state = PROJ_POST_FIRST_AVAILABLE;
        	    $extras = array();
	        } else {
	            $new_state = PROJ_POST_FIRST_CHECKED_OUT;
	            $extras = array( 'checkedoutby' => $username );
	        }
	}

        $error_msg = project_transition( $project, $new_state, PT_AUTO, $extras );
        if ( $error_msg )
        {
            echo "$error_msg<br>\n";
            return;
        }
    }
?>
