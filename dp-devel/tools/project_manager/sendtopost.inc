<?
include($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'maybe_mail.inc');
include_once('./post_files.inc');

    function sendtopost($project, $username) {
        // Prepare a project for post-processing.
        //
        // $project: the unique id of the project.
        // $username: the DP username of the owner(?) of the project.

	global $auto_email_addr;

        generate_post_files( $project, TRUE );


        // if reserved for a PPer, check it out to them
        $reserved = 0;
        $result = mysql_query("SELECT checkedoutby,nameofwork FROM projects WHERE projectid = '$project'");
        if (mysql_num_rows($result) > 0 && mysql_result($result,0) != '') {
            $new_state = PROJ_POST_FIRST_CHECKED_OUT;
            $PPer = mysql_result($result,0,"checkedoutby");
            $title = mysql_result($result,0,"nameofwork");
            $reserved = 1;
            $extras = array( 'checkedoutby' => $PPer );
            $body_blurb =
                "This project has completed second round proofreading\n".
                "and has been checked out to the PPer who had it reserved,\n".
                "$PPer. You will be notified once it has completed post-processing.";
        } else {

	        // Decide whether to send to post-processing or have PM work on it.

	        $result = mysql_query("SELECT value FROM usersettings WHERE setting = 'send_to_post' AND username='$username'");
        	if (mysql_num_rows($result) > 0 && mysql_result($result, 0) == 'yes') {
	            $new_state = PROJ_POST_FIRST_AVAILABLE;
        	    $extras = array();
	            $body_blurb =
	                "This project has completed second round proofreading\n".
	                "and has been made available for someone else to do the post-processing.\n".
	                "You will be notified once it has completed post-processing.";
	        } else {
	            $new_state = PROJ_POST_FIRST_CHECKED_OUT;
	            $extras = array( 'checkedoutby' => $username );
	            $body_blurb =
	                "This project has completed second round proofreading\n".
	                "and is ready for you to do the post-processing.\n".
	                "If you do not want to work on it,\n".
	                "set it to Available for Post-Processing.";
	        }
	}

        $error_msg = project_transition( $project, $new_state, $extras );
        if ( $error_msg )
        {
            echo "$error_msg<br>\n";
            return;
        }
        maybe_mail_project_manager( $project, $body_blurb, "DP Post-Processing Started");

        if ($reserved == 1) {
                $email_addr = mysql_result(mysql_query("
                        SELECT email FROM users WHERE username = '$PPer'
                "),0);
                $body_blurb = "Hello $PPer,\n\n".
                              "The project \"$title\", which is reserved for you to Post-Process,\n".
                              "is now ready for you to work on, and should shortly appear in your list\n".
                              "of checked out projects.\n".
                              "\nThanks!\nDistributed Proofreaders\n";

                maybe_mail( $email_addr, "DP: Reserved book available for Post-Processing", $body_blurb,
                        "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
        }


    }
?>
