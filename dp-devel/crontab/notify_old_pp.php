<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'maybe_mail.inc');

    $old_date = time() - 7776000; // 90 days ago.

    //get projects that have been checked out longer than old_date
    $result = mysql_query("SELECT nameofwork, checkedoutby, modifieddate
                     FROM projects
                     WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."' AND modifieddate <= $old_date");

    $numrows = mysql_num_rows($result);
    $rownum = 0;

   while ($rownum < $numrows) {
        $nameofwork = mysql_result($result, $rownum, "nameofwork");
        $checkedoutby = mysql_result($result, $rownum, "checkedoutby");
        $modifieddate = mysql_result($result, $rownum, "modifieddate");

echo "$nameofwork, $checkedoutby<br>";
$userresult = mysql_query ("SELECT email FROM users WHERE username = '$checkedoutby'");
$email = mysql_result($userresult, 0, "email");


            maybe_mail("$email", "Distributed Proofreaders: checked out book: $nameofwork ",
                 "This is an automated message.\n\n".
"We show that you have had\n\n $nameofwork\n\n checked out for more than 90 days. 
If you wish to continue working on this project and/or need help please 
forward this email with a brief description of the status to dphelp@pgdp.net.\n\n
If you no longer wish to have this text assigned to you please visit the 
Distributed Proofreaders website Post Processing section and select Return 
to Available for this book or forward this email to dphelp@pgdp.net and 
state that you would no longer like to have the book assigned to you so 
that we may return it to available.\n\n 
Thanks!\nThe Distributed Proofreaders Team\n(http://www.pgdp.net)", 
"From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");

        $rownum++;
}

?>
