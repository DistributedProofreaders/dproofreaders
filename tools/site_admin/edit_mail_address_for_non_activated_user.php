<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'new_user_mails.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'User.inc');
include_once($relPath.'email_address.inc');

require_login();

output_header(_('Edit mail-address for non-activated user'));

if (!user_is_a_sitemanager()) {
    echo _('You are not authorized to invoke this script.');
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
    <?php echo _("Username"); ?>: <input type='text' name='username' />
    <input type='submit' value='<?php echo attr_safe(_("Continue")); ?>' />
    </form>
    <br />
    <?php
}
else if ($action == 'list_all') {
    $order_by = get_enumerated_param($_GET, 'order_by', 'date_created DESC', array('username', 'real_name', 'email', 'date_created DESC'));
    $result = mysqli_query(DPDatabase::get_connection(), "
        SELECT
            username,
            real_name,
            email,
            FROM_UNIXTIME(date_created, '%M %e, %Y, %H:%i') AS date
        FROM non_activated_users
        ORDER BY $order_by
    ");
    if (mysqli_num_rows($result) == 0)
        echo "<p>", _("No user accounts are awaiting activation."), "</p>";
    else {
        echo "<p>", _("The following accounts are awaiting activation."), "
            ", _("(Click on a column header to sort by that column.)"), "</p>\n";
        echo "<table border='1'>\n";
        {
            echo "<tr>\n";
            echo "<th><a href='?action=list_all&order_by=username'>", _("Username"), "</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=real_name'>", _("Real Name"), "</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=email'>", _("E-mail"), "</a></th>\n";
            echo "<th><a href='?action=list_all&order_by=date_created+DESC'>", _("Date registered"), "</a></th>\n";
            echo "</tr>\n";
        }
        while ($row = mysqli_fetch_assoc($result)) {
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
    $result = mysqli_query(DPDatabase::get_connection(), sprintf("
        SELECT email
        FROM non_activated_users
        WHERE username='%s'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $username)));

    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        printf(_("No user '%s' was was found in the list of non-validated users."),
            html_safe($username));
        echo "<p>", 
            sprintf(_("Note that you can also <a href='%s'>list all user accounts awaiting activation</a>"), "?action=list_all"), 
            "</p>";
    }
    else {
        $email = $row["email"];
        echo _("Enter the correct email-address below. When you submit the form, the activation mail will be resent.");
        ?>
        <br />
        <form method='get'>
        <input type='hidden' name='action' value='set_email' />
        <input type='hidden' name='username' value='<?php echo attr_safe($username); ?>' />
        <?php echo _("Username"); ?>: <?php echo html_safe($username); ?>
        <br />
        <?php echo _("E-mail"); ?>: <input type='text' name='email' value='<?php echo attr_safe($email); ?>' />
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
    
    mysqli_query(DPDatabase::get_connection(), sprintf("
        UPDATE non_activated_users
        SET email='%s'
        WHERE username='%s'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $email), mysqli_real_escape_string(DPDatabase::get_connection(), $username)));
    $result = mysqli_query(DPDatabase::get_connection(), sprintf("
        SELECT id, real_name, u_intlang
        FROM non_activated_users
        WHERE username='%s'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $username)));
    $row = mysqli_fetch_assoc($result);

    maybe_activate_mail($email, $row['real_name'], $row['id'], $username, $row['u_intlang']);

}
else
    echo 'Unknown action.';

// vim: sw=4 ts=4 expandtab
