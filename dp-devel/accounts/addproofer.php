<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'pg.inc');
include($relPath.'username.inc');
include($relPath.'email_address.inc');
include($relPath.'new_user_mails.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();
$db_link=$db_Connection->db_lk;
include($relPath.'theme.inc');

// This function takes any error message given to it, displays it & terminates the page

function abort_registration( $error )
{
    $header = _("Registration Incomplete");
    theme($header, "header");
    echo "$error<br>\n";
    $please = _("Please hit 'Back' and try again.");
    echo "$please<br>\n";
    $back = _("Back");
    echo "<a href=\"addproofer.php\">".$back."</a>";
    theme("", "footer");
    exit;
}

$password = isset($_POST['password'])? $_POST['password']: '';
if ($password=="proofer") {

    // From the form filled out at the end of this file

    $real_name = $_POST['real_name'];
    $username = $_POST['userNM'];
    $userpass = $_POST['userPW'];
    $email = $_POST['email'];
    $email_updates = $_POST['email_updates'];

    // Do some validity-checks on inputted username, password, e-mail and real name

    $err = check_username( $username );
    if ( $err != '' )
    {
        abort_registration( $err );
    }

    $err = check_email_address( $email );
    if ( $err != '' )
    {
        abort_registration( $err );
    }

    if (empty($userpass) || empty($real_name))
    {
        $error = _("You did not completely fill out the form.");
        abort_registration($error);
    }

    $todaysdate = time();

    $ID = uniqid("userID");

    // Make sure that the username is not taken by a non-registered user.
    $result = mysql_query ("SELECT username FROM users WHERE username='$username'");
    if (mysql_num_rows($result) > 0) {
        $error = _("That user name already exists, please try another.");
        abort_registration($error);
    }

    $passwd = md5($userpass);

    $result = mysql_query ("INSERT INTO non_activated_users (id, real_name, username, email, date_created, email_updates, u_intlang, user_password)
                VALUES ('$ID', '$real_name', '$username', '$email', '$todaysdate', '$email_updates', '$intlang', '$passwd')");

    if (!$result) {
        $error = _("That user name already exists, please try another.");
        abort_registration($error);

    } else {
        // Send them an activation e-mail
        maybe_activate_mail($email, $real_name, $ID, stripslashes($username), $intlang);

        // Page shown when account is successfully created

        $header = sprintf(_("User %s Added Successfully"), $username);
	theme($header, "header");

        echo sprintf(
               _("User %s added successfully. Please check the e-mail being sent to you for further information about activating your account.".
                 "This extra step is taken so that no-one can register you to the site without your knowledge."),
               $username);

	theme("", "footer");
    }
} else {

// This is the portion that shows up when no parameters are given to the file
//
// When users fill the form out below, it will submit the information back
// to this file & run the above commands.

    $header = _("Create An Account");
    theme($header, "header");

    echo "<center><form method='post' action='addproofer.php'><input type=hidden name='password' value='proofer'>";
    echo "<br><table bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse:collapse' width='400'>";
    echo "<tr><td bgcolor='#e0e8dd' align='center' colspan='2'><b>Note: Please choose your User Name carefully. Your User Name will be visible to other DP users and cannot be changed. <br>
    Please make sure your email address is correct. You will be emailed a confirmation link which you will need to follow in order for your DP account to be activated.</b></td></tr>";
    echo "<tr><td bgcolor='#e0e8dd' align='center'><b>"._("Real Name").":</b>";
    echo "<td bgcolor='#ffffff' align='center'><input type='text' maxlength=70 name='real_name' size=20>";
    echo "</td></tr><tr><td bgcolor='#e0e8dd' align='center'><b>"._("User Name").":</b>";
    echo "<td bgcolor='#ffffff' align='center'><input type='text' maxlength=70 name='userNM' size=20>";
    echo "</td></tr><tr><td bgcolor='#e0e8dd' align='center'><b>"._("Password").":</b>";
    echo "<td bgcolor='#ffffff' align='center'><input type='password' maxlength=70 name='userPW' size=20>";
    echo "</td></tr><tr><td bgcolor='#e0e8dd' align='center'><b>"._("E-mail Address").":</b>";
    echo "<td bgcolor='#ffffff' align='center'><input type='text' maxlength=70 name='email' size=20>";
    echo "</td></tr><tr><td bgcolor='#e0e8dd' align='center'><b>"._("E-mail Updates").":</b>";
    echo "</td><td bgcolor='#ffffff' align='center'><input type='radio' name='email_updates' value='1' checked>"._("Yes")."&nbsp;&nbsp;<input type='radio' name='email_updates' value='0'>"._("No");
    echo "</td></tr><tr><td bgcolor='#336633' colspan='2' align='center'><input type='submit' value='"._("Create Account")."'>&nbsp;&nbsp;<input type='reset'>";
    echo "</td></tr><tr><td bgcolor='#ffffff' colspan='2' align='left'>";

    if(file_exists($code_dir.'/faq/'.lang_dir().'privacy.php')) {
        include($code_dir.'/faq/'.lang_dir().'privacy.php');
    } else {
        include($code_dir.'/faq/privacy.php');
    }

    echo "</td></tr></table></form>";
    echo "</center>";
}
theme("", "footer");
?>


