<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'project_states.inc');
include($relPath.'maybe_mail.inc');
$db_Connection=new dbConnect();

    $old_date = time() - 7776000; // 90 days ago.

    //get projects that have been checked out longer than old_date
    $result = mysql_query("SELECT nameofwork, checkedoutby, modifieddate, projectid, authorsname, 
				DATE_FORMAT(FROM_UNIXTIME(modifieddate), '%e %M  %Y') as Nicedate
                     FROM projects
                     WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."' AND modifieddate <= $old_date ORDER BY checkedoutby, modifieddate");

    $numrows = mysql_num_rows($result);
    $rownum = 0;

    $PPinQuestion = "";
    $lastwork = "";	
    $projectslist = "";
    $displayprojectslist = "";
    $numprojs = 0;

   while ($rownum < $numrows) {

        $nameofwork = mysql_result($result, $rownum, "nameofwork");
        $authorsname = mysql_result($result, $rownum, "authorsname");
        $checkedoutby = mysql_result($result, $rownum, "checkedoutby");
        $modifieddate = mysql_result($result, $rownum, "modifieddate");
	$projectid = mysql_result($result, $rownum, "projectid");
	$nicedate = mysql_result($result, $rownum, "nicedate");

        if ($PPinQuestion != $checkedoutby) {
	    if ($rownum > 0) {

		    $userresult = mysql_query ("SELECT email FROM users WHERE username = '$PPinQuestion'");
		    $email = mysql_result($userresult, 0, "email");
			
		    echo $PPinQuestion . "<br>\n" . $displayprojectslist ."<br><br>\n\n";

		    if ($numprojs == 1) {
			$message = "Hello $PPinQuestion,\n\nThis is an automated message.\n\n
Our database indicates that you have had a PP project checked out for more than 90 days:\n\n
$projectslist\n\n 
If you haven't yet finished and wish to continue working on this book and/or need help please forward a copy of this email (quoting the information on the book, above) with a brief description of the status to dphelp@pgdp.net.\n\n
If you have completed your work on the book, please log in to www.pgdp.net and visit the Post Processing page at $code_url/tools/post_proofers/post_proofers.php and, under 'Books I have checked out for Post-Processing' select the 'Upload for verification' option and follow the prompts. You will be able to leave a message for the verifier during this process, if you have any special information or comments to pass on.\n\n
If you are waiting on missing images or page scans, please add the details to the Missing Page Wiki at: $forums_url/viewtopic.php?t=7584\n\n
If you no longer wish to have this text assigned to you please visit the Distributed Proofreaders website Post Processing section and select Return to Available for this book, or forward this email to dphelp@pgdp.net and state that you would no longer like to have the book in question assigned to you so that we may return it to the available pool for someone else to work on.\n\n 
Thanks!\nThe Distributed Proofreaders Team\n(http://www.pgdp.net)";
		    } else {
			$message = "Hello $PPinQuestion,\n\nThis is an automated message.\n\n
Our database indicates that you have had $numprojs PP projects checked out for more than 90 days:\n\n 
$projectslist\n\n 
If you wish to continue working on some or all of these books and/or need help please forward this email, quoting the list of books, with a brief description of the status for each of the various books listed above to dphelp@pgdp.net.\n\n
If you have completed your work on any of these books, please log in to www.pgdp.net and visit the Post Processing page at $code_url/tools/post_proofers/post_proofers.php and, under 'Books I have checked out for Post-Processing' select the 'Upload for verification' option and follow the prompts. You will be able to leave a message for the verifier during this process, if you have any special information or comments to pass on.\n\n
If you are waiting on missing images or page scans, please add the details to the Missing Page Wiki at: $forums_url/viewtopic.php?t=7584\n\n
If you no longer wish to have some or all of these books assigned to you please visit the Distributed Proofreaders website Post Processing section and select Return to Available for the books in question or forward this email to dphelp@pgdp.net and state that you would no longer like to have the books in question assigned to you so that we may return them to the available pool for someone else to work on.\n\n 
Thanks!\nThe Distributed Proofreaders Team\n(http://www.pgdp.net)";
		    }

	            maybe_mail("$email", "$subject","$message", "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");

		    $projectslist = "";
		    $displayprojectslist = "";
		    $numprojs = 0;
 	    }
	    $PPinQuestion = $checkedoutby;
	}

	$numprojs++;
	
	$projectslist .= "$nameofwork by $authorsname ($projectid), out since $nicedate\n\n";
        if ($numprojs == 1) {
		$subject = "DP: Status update needed for 1 project checked out for PPing over 90 days";
	} else {
		$subject = "DP: Status updates needed for $numprojs projects checked out for PPing over 90 days";
	}	

	$displayprojectslist .= "$nameofwork by $authorsname ($projectid), out since $nicedate\n". "<br>";

        $rownum++;
}

?>
