<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'new_user_mails.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'username.inc');
include_once($relPath.'email_address.inc');

theme('Edit mail-address for non-activated user', 'header');

if (!user_is_a_sitemanager()) {
    echo _('You are not authorized to invoke this script.');
    theme('', 'footer');
    exit;
}

$action   = get_enumerated_param($_GET, 'action', 'default', array('list_all', 'get_user', 'set_email', 'default'));

if ($action == 'default') {
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
else if ($action == 'list_all') {
    $order_by = get_enumerated_param($_GET, 'order_by', 'date_created DESC', array('username', 'real_name', 'email', 'date_created DESC'));
    $result = mysql_query("
        SELECT
            username,
            real_name,
            email,
            FROM_UNIXTIME(date_created, '%M %e, %Y, %H:%i') AS date
        FROM non_activated_users
        ORDER BY $order_by
    ");
    if (mysql_num_rows($result) == 0)
        echo "<p>No user accounts are awaiting activation.</p>";
    else {
        echo "<p>The following accounts are awaiting activation.
            (Click on a column header to sort by that column.)</p>\n";
        echo "<table border='1'>\n";
        {
            echo "<tr>\n";
            echo "<th><a href='?action=list_all&order_by=username'>username</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=real_name'>real name</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=email'>email address</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=date_created+DESC'>date registered</a></th>\n";
            echo "</tr>\n";
        }
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>\n";
            echo "<td><a href='?action=get_user&username=".urlencode($row['username'])."'>{$row['username']}</a></td>\n";
            echo "<td>{$row['real_name']}</td>\n";
            echo "<td>{$row['email']}</td>\n";
            echo "<td>{$row['date']}</td>\n";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
}
else if ($action == 'get_user') {
    $username = @$_GET['username'];
    if (check_username($username) != '') die("Invalid parameter username.");
    $result = mysql_query("
        SELECT email
        FROM non_activated_users
        WHERE username='$username'
    ");

    if (mysql_num_rows($result) == 0) {
        ?>
        No user '<?php echo htmlspecialchars(stripslashes($username)); ?>' was found in the list of non-validated users.
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
        <input type='hidden' name='username' value='<?php echo htmlspecialchars($username, ENT_QUOTES); ?>' />
        Username: <?php echo htmlspecialchars($username); ?>
        <br />
        E-mail: <input type='text' name='email' value='<?php echo htmlspecialchars($email, ENT_QUOTES); ?>' />
        <br />
        <input type='submit' value='Update address and resend activation mail' />
        </form>
        <?php
    }
}
else if ($action == 'set_email') {
    $username = @$_GET['username'];
    $email    = @$_GET['email'];
    if (check_username($username) != '') die("Invalid parameter username.");
    if (check_email_address($email) != '') die("Invalid parameter email.");
    
    mysql_query("
        UPDATE non_activated_users
        SET email='$email'
        WHERE username='$username'
    ");
    $result = mysql_query("
        SELECT id, real_name, u_intlang
        FROM non_activated_users
        WHERE username='$username'
    ");
    $row = mysql_fetch_assoc($result);

    maybe_activate_mail($email, $row['real_name'], $row['id'], stripslashes($username), $row['u_intlang']);

}
else
    echo 'Unknown action.';

theme('', 'footer');

// vim: sw=4 ts=4 expandtab
?>
