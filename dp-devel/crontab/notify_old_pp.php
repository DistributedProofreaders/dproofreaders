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
			
		    echo $PPinQuestion . "\n" . $projectslist ."<br><br>\n\n";

		    if ($numprojs == 1) {
			$message = "This is an automated message.\n\n
Our database indicates that you have had a PP project checked out for more than 90 days:\n\n
$projectslist\n\n 
If you wish to continue working on this project and/or need help please 
forward this email with a brief description of the status to dphelp@pgdp.net.\n\n
If you no longer wish to have this text assigned to you please visit the 
Distributed Proofreaders website Post Processing section and select Return 
to Available for this book, or forward this email to dphelp@pgdp.net and 
state that you would no longer like to have the book in question assigned to you so 
that we may return it to the available pool for someone else to work on.\n\n 
Thanks!\nThe Distributed Proofreaders Team\n(http://www.pgdp.net)";
		    } else {
			$message = "This is an automated message.\n\n
Our database indicates that you have had several PP projects checked out for more than 90 days:\n\n 
$projectslist\n\n 
If you wish to continue working on some or all of these projects and/or need help please 
forward this email with a brief description of the status to dphelp@pgdp.net.\n\n
If you no longer wish to have some or all of these texts assigned to you please visit the 
Distributed Proofreaders website Post Processing section and select Return 
to Available for the books in question or forward this email to dphelp@pgdp.net and 
state that you would no longer like to have the books in question assigned to you so 
that we may return them to the available pool for someone else to work on.\n\n 
Thanks!\nThe Distributed Proofreaders Team\n(http://www.pgdp.net)";
		    }

//	            maybe_mail("$email", "Subject","$message", "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");

// test
  maybe_mail("big_bill_boy2@yahoo.com.au", "Subject","$message", "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");		


		    $projectslist = "";
		    $numprojs = 0;
 	    }
	    $PPinQuestion = $checkedoutby;
	}

	$numprojs++;
	
	$projectslist .= "$nameofwork by $authorsname ($projectid), out since $nicedate\n";
        if (numprojs == 1) {
		$subject = "Distributed Proofreaders: $nameofwork checked out over 90 days";
	} else {
		$subject = "Distributed Proofreaders: $numprojs projects checked out over 90 days";
	}	

        $rownum++;
}

?>
