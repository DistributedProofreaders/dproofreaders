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

		    $message = "This is an automated message.\n\n".
"Our database indicates that you have had ". ($numprojs == 1) ? "a project" : "several projects" . " checked out for more than 90 days:\n\n" 
. $projectslist . "\n\n 
If you wish to continue working on ". ($numprojs == 1) ? "this project" : "some or all of these projects" . " and/or need help please 
forward this email with a brief description of the status to dphelp@pgdp.net.\n\n
If you no longer wish to have ". ($numprojs == 1) ? "this text" : "some or all of these texts" . " assigned to you please visit the 
Distributed Proofreaders website Post Processing section and select Return 
to Available for the ". ($numprojs == 1) ? "book" : "books" . " in question or forward this email to dphelp@pgdp.net and 
state that you would no longer like to have the ". ($numprojs == 1) ? "book" : "books" . " in question assigned to you so 
that we may return ".($numprojs == 1) ? "it" : "them" ." to available.\n\n 
Thanks!\nThe Distributed Proofreaders Team\n(http://www.pgdp.net)";
		
//	            maybe_mail("$email", "Subject","$message", "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");

		
		    $PPinQuestion = $checkedoutby;
		    $projectslist = "";
		    $numprojs = 0;
 	    }
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
