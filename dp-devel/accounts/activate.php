<?php
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'new_user_mails.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();
$db_link=$db_Connection->db_lk;
include($relPath.'theme.inc');

// A newly registered user has clicked the link in the welcoming e-mail and has thus
// proved that the e-mail is working. It is time to 'activate' the account, i.e.
// create a record in the users table, create a profile, stats data, etc.
// and send a welcome mail.

theme(_('Activate account'), 'header');

$ID = $_GET['id'];

$result = mysql_query("SELECT * FROM non_activated_users WHERE id='$ID'");

if (mysql_num_rows($result) == 0) {
  echo sprintf(
         _("There is no account with the id '%s' waiting to be activated."),
         stripslashes($ID)
       );
  theme('', 'footer');
  exit;
}

$user = mysql_fetch_assoc($result);
$real_name = addslashes($user['real_name']);
$username = addslashes($user['username']);
$email = addslashes($user['email']);
$date_created = addslashes($user['date_created']);
$email_updates = addslashes($user['email_updates']);
$u_intlang = $user['u_intlang'];
$passwd = $user['user_password'];

// Delete record in non_activated_users.
mysql_query("DELETE FROM non_activated_users WHERE id='$ID'");

// Insert into 'real' table -- users
$result = mysql_query ("INSERT INTO users (id, real_name, username, email, manager, date_created, email_updates, u_plist, u_top10, u_neigh, u_intlang)
            VALUES ('$ID', '$real_name', '$username', '$email', 'no', '$date_created', '$email_updates', '3', '1', '10', '$u_intlang')");

// create profile
$profileString="INSERT INTO user_profiles SET u_ref='".mysql_insert_id($db_link)."'";
$makeProfile=mysql_query($profileString);

// add ref to profile
$refString="UPDATE users SET u_profile='".mysql_insert_id($db_link)."' WHERE id='$ID' AND username='$username'";
$makeRef=mysql_query($refString);

//code from php forums bb_register.php
$sql = "SELECT max(user_id) AS total FROM phpbb_users";
if(!$r = mysql_query($sql))
    die("Error connecting to the database.");
list($total) = mysql_fetch_array($r);
$currtime = time();

$total += 1;
$sql = "INSERT INTO phpbb_users (user_id, username, user_regdate, user_timezone, user_email, user_password, user_viewemail)
    VALUES ('$total', '$username', " . $currtime . ", '-8.00', '$email', '$passwd', '0')";
$result = mysql_query($sql);

// Initialize the user's page-tally history at registrationt time
// to eliminate zero-day php/mysql errors on stats page (Task 472).
// First we have to dig out the auto-incremented users.u_id that was just created.
$sql = "SELECT u_id FROM users WHERE id='$ID'";
$result = mysql_query($sql);
if (!$result) {
  die("Error connecting to the database.");
}
$this_uid = mysql_result($result, 0);

// Send them an introduction e-mail
maybe_welcome_mail($email, $real_name, $username);

echo sprintf(
       _("User %s activated successfully. Please check the e-mail being sent to you for further information about Distributed Proofreading."),
       $username);
echo "<center>";
echo "<br><font size=+1>"._("Enter your password below to sign in and start proofreading!!");
echo "<form action='login.php' method='post'><input type='hidden' name='userNM' value='".$username."'><input type='password' name='userPW'><input type='submit' value='"._("Sign In")."'></form>";

theme("", "footer");

?>
