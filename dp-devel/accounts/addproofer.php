<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'username.inc');
include($relPath.'maybe_mail.inc');
include($relPath.'connect.inc');
include($relPath.'html_main.inc');
$db_Connection=new dbConnect();
$db_link=$db_Connection->db_lk;
include($relPath.'theme.inc');
theme("Create An Account", "header");
echo "<br>";

function abort_registration( $error )
{
    global $htmlC;
    $htmlC->startBody(0,1,0,0);
    echo "$error<br>\n";
    echo "<a href=\"addproofer.php\">Back to account creation page.</a>";
    $htmlC->closeBody(0);
    exit;
}

$password = isset($_POST['password'])? $_POST['password']: '';
if ($password=="proofer") {
    $real_name = $_POST['real_name'];
    $username = $_POST['userNM'];
    $userpass = $_POST['userPW'];
    $email = $_POST['email'];
    $email_updates = $_POST['email_updates'];

    // Do some validity-checks.

    $err = check_username( $username );
    if ( $err != '' )
    {
        abort_registration( $err );
    }

    if ($userpass == '')
    {
        abort_registration( "You did not supply a Password" );
    }

    $ID = uniqid("userID");

    $todaysdate = time();

    $result = mysql_query ("INSERT INTO users (id, real_name, username, email, manager, date_created, emailupdates, u_plist)
                VALUES ('$ID', '$real_name', '$username', '$email', 'no', '$todaysdate', '$email_updates', '3')");

    if (!$result) {
        abort_registration(
            "That user name already exists, please try another." );

    } else {
        // create profile
        $profileString="INSERT INTO user_profiles SET u_ref='".mysql_insert_id($db_link)."'";
        $makeProfile=mysql_query($profileString);
        // add ref to profile
        $refString="UPDATE users SET u_profile='".mysql_insert_id($db_link)."' WHERE id='$ID' AND username='$username'";
        $makeRef=mysql_query($refString);
        // join the all users team
        mysql_query("UPDATE user_teams SET member_count=member_count+1 WHERE id='1'");
        //code from php forums bb_register.php 
        $passwd = md5($userpass);
        $sql = "SELECT max(user_id) AS total FROM phpbb_users";
        if(!$r = mysql_query($sql))
            die("Error connecting to the database.");
        list($total) = mysql_fetch_array($r);
        $currtime = time();

        $total += 1;
        $sql = "INSERT INTO phpbb_users (user_id, username, user_regdate, user_timezone, user_email, user_password, user_viewemail) 
            VALUES ('$total', '$username', " . $currtime . ", '-8.00', '$email', '$passwd', '0')";
        $result = mysql_query($sql);

        maybe_mail($email, "Welcome to the Distributed Proofreader's Site!", "
Hello $real_name,

I want to first thank you for registering on our site. That is the
first step in helping us proofread books for Project Gutenberg
<http://www.gutenberg.net/>.

As a new user, I recommend you read over our main page
<$code_url/> for an overview of the site,
a selection of the works that we are working on, along
with the books that have been completed through the site.
The Proofing Guidelines <$code_url/faq/document.php>
covers most formatting questions, so be sure to read it over too.

Once you understand the work being done through this site,
the best thing to do is get started! Here's a step-by-step
process once you login:

- Select a book that you would like to read a little bit on.
  Each book goes through two rounds of proofreading. You will
  be starting in the first round so that someone can check your
  work and e-mail you about any major mistakes. Second round
  will be available to you later on.

- Click on the title you want to work on.

- It will show you a listing of items to note when working on
  a project. These will stay fairly consistent on all projects.
  They will be available later, so do not worry about remembering
  them all.

- Click on \"Start Proofreading\".

- Compare the text in the text box to what is in the image, making
  corrections for differences between the two and any additional
  items described in the comments. You can pull the comments back
  up by clicking on the link below the text box.

- Once you are finished with this page, click on either
  \"Save and Quit\" or \"Save and Do Another\"

That is all there is to completing your first page. If you have
specific questions on a book, you can post a message in the forum.
Remember, every page you do gets these books done quicker. I hope
that you enjoy the proofreading available and that you will continue
to use our site.

Thanks,

The Distributed Proofreaders Team

PS - Your user name, in case you forget, is $username.
If your password doesn't work, go to
<$reset_password_url>
to have it reset.
            ",
            "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");

        $htmlC->startHeader("User $username Added Successfully");
        $htmlC->startBody(0, 1, 0, 0);
        print "User <b>$username</b> added successfully. Please check the e-mail being sent to you for further information about Distributed Proofreading.";
        echo "<center>";
        echo "<br><font size=+1>Click here to <a href=\"signin.php\">Sign In</a></font> and start proofing!!";
        echo "<br><a href = \"../default.php\">Back to the Main Page</a>";
	$htmlC->closeBody(0);
    }
} else {

    $tb=$htmlC->startTable(0,400,0,1);
    $tr=$htmlC->startTR(0,0,1);

$td1=$htmlC->startTD(2,0,2,0,"center",0,0,1);
$td2=$htmlC->startTD(1,0,0,0,"center",0,0,1);
$td3=$htmlC->startTD(0,0,0,0,"center",0,0,1);
$td4=$htmlC->startTD(1,0,2,0,"center",0,0,1);
$td5=$htmlC->startTD(0,0,2,0,"left",0,0,1);
$tde=$htmlC->closeTD(1);
$tre=$htmlC->closeTD(1).$htmlC->closeTR(1);

?>
<center>
<form method="post" action="addproofer.php">
<input type=hidden name="password" value="proofer">
<?php
    echo $tb.$tr.$td1;
    echo "<b>Create A Distributed Proofreader Account</b>";
    echo $tre.$tr.$td2."<b>Real Name:</b>";
    echo $td3.'<input type="text" maxlength=70 name="real_name" size=20>';
    echo $tre.$tr.$td2."<b>User Name:</b>";
    echo $td3.'<input type="text" maxlength=70 name="userNM" size=20>';
    echo $tre.$tr.$td2."<b>Password:</b>";
    echo $td3.'<input type="password" maxlength=70 name="userPW" size=20>';
    echo $tre.$tr.$td2."<b>E-mail Address:</b>";
    echo $td3.'<input type="text" maxlength=70 name="email" size=20>';
    echo $tre.$tr.$td2."<b>E-mail Updates:</b>";
    echo $tde.$td3.'<input type="radio" name="email_updates" value="1" checked>Yes&nbsp;&nbsp;<input type="radio" name="email_updates" value="0">No';
    echo $tre.$tr.$td1.'<input type="submit" value="Create Account">&nbsp;&nbsp;<input type="reset" value="Reset Form">';
    echo $tre.$tr.$td5;
?>
<center><font size="+2">Privacy Statement:</font></center>
<p><font size="+1">Usage of information:</font></p>
<p>This information will allow the project managers to provide feedback to you directly or have users send to you a message via the phpBB forum. An introductory e-mail will also be sent to you.</p>
<p><font size="+1">Removal of information:</font>
<p>Requests to remove the information beforehand can be sent to the web site manager or will be removed based on it's age.</p>
<p><font size="+1">Access of information:</font></p>
<p>Only the web site manager will have full access to the information in this form. Project managers will have access to e-mail you directly if needed. Any public information you fill out in the phpBB forum or profile will be accessible to other users.</p>
<p><font size="+1">Tracking information:</font></p>
<p>The only tracking information we collect at this time is the number of pages you have completed, the date your account was created, and your last login date. Only the pages completed out of the three will be available to end users, the rest is for statistical purposes and removal of old accounts by the web site managers.</p>
<?php
     echo $tre.$htmlC->closeTable(1)."</form>";

     echo "</center>";
}
theme("", "footer");
?>
