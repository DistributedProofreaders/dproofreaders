<?
$relPath="./../pinc/";
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$password = isset($_POST['password'])? $_POST['password']: '';
if ($password=="proofer") {
    $real_name = $_POST['real_name'];
    $username = $_POST['userNM'];
    $userpass = $_POST['userPW'];
    $email = $_POST['email'];
    $email_updates = $_POST['email_updates'];

    $ID = uniqid("userID");

    $todaysdate = time();

    $result = mysql_query ("INSERT INTO users (id, real_name, username, email, manager, date_created, emailupdates, u_plist)
                VALUES ('$ID', '$real_name', '$username', '$email', 'no', '$todaysdate', '$email_updates', '1')");

    if (!$result) {

        echo "That user name already exists, please try another.<br>";
        echo "<center>";
        echo "<a href=\"addproofer.php\">Back to account creation page.</a>";
        echo "</center>";
        exit;
    } else {

        //code from php forums bb_register.php 
        $passwd = md5($userpass);
        $sql = "SELECT max(user_id) AS total FROM phpbb_users";
        if(!$r = mysql_query($sql))
            die("Error connecting to the database.");
        list($total) = mysql_fetch_array($r);
        $currtime = time();

        $total += 1;
        $sql = "INSERT INTO phpbb_users (user_id, username, user_regdate, user_timezone, user_email, user_password, 
user_viewemail) 
                            VALUES ('$total', '$username', " . $currtime . ", '-8.00', '$email', '$passwd', '0')";

        $result = mysql_query($sql);

        mail($email, "Welcome to the Distributed Proofreader's Site!",
             "Hello $real_name,\n\n".
"I want to first thank you for registering on our site. That is the first step in helping us proofread books for Project Gutenberg (http://www.gutenberg.net/).\n\n".

"As a new user, I recommend you read over our main page <http://texts01.archive.org/dp/> for an overview of the site, a selection of the works that we are working on, along with the books that have been completed through the site. The Frequently Asked Questions <http://texts01.archive.org/dp/faq/ProoferFAQ.html> lists most user's initial questions, so be sure to read it over too.\n\n".

"Once you understand the work being done through this site, the best thing to do is get started! Here's a step-by-step process once you login:\n\n".

"- Select a book that you would like to read a little bit on. Each book goes through two rounds of proofreading, you will be starting in the first round so that someone can check your work and e-mail you about any major mistakes. Second round will be available to you later on.\n\n".

"- Click on the title you want to work on.\n\n".

"- It will show you a listing of items to note when working on a project, these will stay fairly consistent on all projects. They will be available later, so do not worry about remembering them all.\n\n".

"- Click on \"Interface Preferences\” and for now just hit the \“Save Preferences\” button unless there is something specific you understand and want to change.\n\n".

"- Click on \“Start Proofreading\”.".

"- Compare the text in the text box to what is in the image, making corrections for differences between the two and any additional items described in the comments. You can pull the comments back up by clicking on the link below the text box.\n\n".

"- Once you are finished with this page, click on either \"Save and Quit\" or \"Save and Do Another\"\n\n".

"That is all there is to completing your first page. If you have specific questions on a book, you can post a message in the forum. Remember every page you do gets these books done quicker. I hope that you enjoy the proofreading available and that you will continue to use our site.\n\n".

"Thanks,\n
The Distributed Proofreaders Team\n\nPS - Your user name, in case you forget is $username.
If your password doesn't work, go to <http://texts01.archive.org/dp/phpBB2/profile.php?mode=sendpassword> to have it reset.",
"From: charlz@lvcablemodem.com\r\nReply-To: charlz@lvcablemodem.com\r\n");

        print "User <b>$username</b> added successfully. Please verify your account by following the link provided in the e-mail being sent to you.";
        echo "<center>";
        echo "<br><font size=+1>Click here to <a href=\"signin.php\">Sign In</a></font> and start proofing!!";
        echo "<br><a href = \"../default.php\">Back to the Main Page</a>";
        echo "</center>";
    }
} else {
?>
<html>
<head><title>User Admin Page: Create a proofreader account</title></head>
<body bgcolor=#ffffff>
<table bgcolor=#000000 valign=top align=center border=0><tr><td bgcolor=#000000>
<table cellpadding=4 bgcolor=#ffffff cellspacing=2 border=0>
<Tr><th>Create a proofreader account</th></tr><tr><td>
<FORM METHOD="post" ACTION="addproofer.php">
<Input Type=hidden Name="password" value = "proofer">
<table border=0>
<td width=20>Real Name:</td><td><INPUT TYPE=text MAXLENGTH=70 NAME="real_name" SIZE=20><Br></td><tr>
<td>Username:</td><td> <INPUT TYPE=text MAXLENGTH=70 NAME="userNM" SIZE=20></td><tr>
<td>Password:</td><td> <Input Type=password Maxlength=70 Name="userPW" Size=10></td><tr>
<td>E-mail address:</td><td> <Input Type=text Maxlength=70 Name="email" Size=20></td><tr>
<td>E-mail updates:</td><td> <input type=radio name=email_updates value=1 checked>Yes&nbsp;&nbsp;<input type=radio name=email_updates value=0>No</td>
</table>
<center><INPUT TYPE=submit VALUE="Add">  <INPUT type=reset VALUE="Reset Form"><br>
<br>The information that you enter here will only be made<br>
available to the project manager(s) for whom you have proofread.
<br>This will allow the project manager to provide feedback to<br>
each proofer such as thanks, pointers on how to proof better, etc.<br>
It will also send you an e-mail with a link to activate your account.
<br><br><a href = "../default.php">Back</a> to the main page.</center>
</form>
</tr></td></table></tr></td></table>
</body>
</html>
<?
}
?>

