<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'new_user_mails.inc');

theme('Edit mail-address for non-activated user', 'header');

if (!user_is_a_sitemanager()) {
  echo _('You are not authorized to invoke this script.');
  theme('', 'footer');
  exit;
}

if (!isset($_GET['action'])) {
  ?>
  This form should be used when a mail has been received from a user who has not received
  his or her welcome email due to entering a bad email address when registering.
  To change the address, please enter the name of the user below or
  <a href="?action=list_all">list all user accounts awaiting activation</a>.
  <br />
  <form method='get'><input type='hidden' name='action' value='get_user' />
  Username: <input type='text' name='username' />
  <input type='submit' value='Continue' />
  </form>
  <br />
  <?php
}
else if ($_GET['action'] == 'get_user') {
  $result = mysql_query("SELECT email FROM non_activated_users WHERE username='$username'");

  if (mysql_num_rows($result) == 0) {
    ?>
    No user '<?=stripslashes($username)?>' was found in the list of non-validated users.
    <p>Note that you can also <a href="?action=list_all">list all user accounts
    awaiting activation</a>.</p>
    <?php
  }
  else {
    $username = stripslashes($username);
    $email = mysql_result($result, 0, 'email');
    ?>
    Enter the correct email-address below. When you submit the form, the activation mail will be resent.
    <br />
    <form method='get'>
    <input type='hidden' name='action' value='set_email' />
    <input type='hidden' name='username' value='<?=htmlspecialchars($username)?>' />
    Username: <?=htmlspecialchars($username)?>
    <br />
    E-mail: <input type='text' name='email' value='<?=$email?>' />
    <br />
    <input type='submit' value='Update address and resend activation mail' />
    </form>
    <?php
  }
}
else if ($_GET['action'] == 'list_all') {
  if (isset($_GET['order_by']))
    $order_by = $_GET['order_by'];
  else
    $order_by = 'date_created DESC';
  $result = mysql_query("SELECT username, FROM_UNIXTIME(date_created, '%M %e, %Y, %H:%i') AS date FROM non_activated_users ORDER BY $order_by");
  if (mysql_num_rows($result) == 0)
    echo "<p>No user accounts are awaiting activation.</p>";
  else {
    echo "<p>Sort by <a href='?action=list_all&order_by=date_created+DESC'>registration date (latest first)</a> or
                   <a href='?action=list_all&order_by=username'>username (ascending)</a>.</p>";
    echo "<p>The following accounts are awaiting activation:\n<ul>\n";
    while ($row = mysql_fetch_assoc($result)) {
      echo "<li><a href='?action=get_user&username=".urlencode($row['username'])."'>{$row['username']}</a> ({$row['date']})</li>\n";
    }
    echo "</ul></p>";
  }
}
else if ($_GET['action'] == 'set_email') {
  mysql_query("UPDATE non_activated_users SET email='$email' WHERE username='$username'");
  $result = mysql_query("SELECT id, real_name, u_intlang FROM non_activated_users WHERE username='$username'");
  $row = mysql_fetch_assoc($result);

  maybe_activate_mail($email, $row['real_name'], $row['id'], stripslashes($username), $row['u_intlang']);

}
else
  echo 'Unknown action.';

theme('', 'footer');

?>
