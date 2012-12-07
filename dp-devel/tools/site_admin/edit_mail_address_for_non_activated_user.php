<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'new_user_mails.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'username.inc');
include_once($relPath.'email_address.inc');

require_login();

theme(_('Edit mail-address for non-activated user'), 'header');

if (!user_is_a_sitemanager()) {
    echo _('You are not authorized to invoke this script.');
    theme('', 'footer');
    exit;
}

$action   = get_enumerated_param($_GET, 'action', 'default', array('list_all', 'get_user', 'set_email', 'default'));

if ($action == 'default') {
    echo _("This form should be used when a mail has been received from a user who has not received
    his or her welcome email due to entering a bad email address when registering.");
    printf(_("To change the address, please enter the name of the user below or
    <a href='%s'>list all user accounts awaiting activation</a>."), "?action=list_all");
    ?>
    <br />
    <form method='get'><input type='hidden' name='action' value='get_user' />
    <?php echo _("Username:"); ?> <input type='text' name='username' />
    <input type='submit' value='<?php echo attr_safe(_("Continue")); ?>' />
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
        echo "<p>", _("No user accounts are awaiting activation."), "</p>";
    else {
        echo "<p>", _("The following accounts are awaiting activation."), "
            ", _("(Click on a column header to sort by that column.)"), "</p>\n";
        echo "<table border='1'>\n";
        {
            echo "<tr>\n";
            echo "<th><a href='?action=list_all&order_by=username'>", _("username"), "</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=real_name'>", _("real name"), "</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=email'>", _("email address"), "</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=date_created+DESC'>", _("date registered"), "</a></th>\n";
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
        printf(_("No user '%s' was was found in the list of non-validated users."),
            htmlspecialchars(stripslashes($username)));
        echo "<p>", 
            sprintf(_("Note that you can also <a href='%s'>list all user accounts awaiting activation</a>"), "?action=list_all"), 
            "</p>";
    }
    else {
        $username = stripslashes($username);
        $email = mysql_result($result, 0, 'email');
        echo _("Enter the correct email-address below. When you submit the form, the activation mail will be resent.");
        ?>
        <br />
        <form method='get'>
        <input type='hidden' name='action' value='set_email' />
        <input type='hidden' name='username' value='<?php echo htmlspecialchars($username, ENT_QUOTES); ?>' />
        <?php echo _("Username:"); ?> <?php echo htmlspecialchars($username); ?>
        <br />
        <?php echo _("E-mail:"); ?> <input type='text' name='email' value='<?php echo htmlspecialchars($email, ENT_QUOTES); ?>' />
        <br />
        <input type='submit' value='<?php echo attr_safe(_("Update address and resend activation mail")); ?>' />
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
