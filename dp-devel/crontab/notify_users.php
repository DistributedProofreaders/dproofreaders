<?
$relPath="./../pinc/";
include($relPath.'dp_main.inc');

    $old_date = time() - 13176000; // 30 days less than 1/2 a year.

    $result = mysql_query ("SELECT * FROM `users` WHERE last_login < $old_date");
    $numrows = mysql_num_rows($result);
    $rownum = 0;

    while ($rownum < $numrows) {
        $username = mysql_result($result, $rownum, "username");
        $real_name = mysql_result($result, $rownum, "real_name");
        $email = mysql_result($result, $rownum, "email");
        $pagescompleted = mysql_result($result, $rownum, "pagescompleted");
        $email_updates = mysql_result($result, $rownum, "email_updates");

        if ($email_updates) {
            mail("$email", "Distributed Proofreaders: Inactive Account $username",
                 "Hello $real_name,\n\n".
"This is an automated message and your only e-mail reminder that your account on the
 Distributed Proofreaders site (http://texts01.archive.org/dp/) has been inactive
for over 5 months now. In order to show a valid number of active members for our
site, we will be removing this account from our site a month from today if it
remains inactive.\n\n
If you wish to receive no more mailings from us, you need to do nothing else and 
this account will be removed from our system. If you have forgotten your password,
visit (http://texts01.archive.org/dp/phpBB2/profile.php?mode=sendpassword) to have
it reset. We hope you care to join us, much has changed since you last saw us.\n\n
Thanks!\n
The Distributed Proofreaders Team", 
"From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");
        }

        $rownum++;
    }

    echo "$numrows";

?>
