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

if (!user_is_a_sitemanager())
    die(_('You are not authorized to invoke this script.'));

$title = _("Resend Account Activation Email");
output_header($title);

echo "<h1>$title</h1>";

$username = array_get($_GET, 'username', '');
$email    = array_get($_GET, 'email', '');
$action   = get_enumerated_param($_GET, 'action', 'default', array('list_all', 'get_user', 'set_email', 'default'));
$order_by = get_enumerated_param($_GET, 'order_by', 'date_created DESC', array('username', 'real_name', 'email', 'date_created DESC'));

if ($action == 'default') {
    echo "<p>";
    echo _("This form should be used when a mail has been received from a user who has not received
    his or her welcome email due to entering a bad email address when registering.");
    echo "</p>";
    echo "<p>";
    printf(_("To change the address, please enter the name of the user below or
    <a href='%s'>list all user accounts awaiting activation</a>."), "?action=list_all");
    echo "</p>";
    ?>
    <br>
    <form method='get'><input type='hidden' name='action' value='get_user'>
    <?php echo _("Username"); ?>: <input type='text' name='username' required>
    <input type='submit' value='<?php echo attr_safe(_("Continue")); ?>'>
    </form>
    <br>
    <?php
}
else if ($action == 'list_all') {
    $result = mysqli_query(DPDatabase::get_connection(), "
        SELECT *
        FROM non_activated_users
        ORDER BY $order_by
    ");
    if (mysqli_num_rows($result) == 0)
        echo "<p>", _("No user accounts are awaiting activation."), "</p>";
    else {
        echo "<p>", _("The following accounts are awaiting activation."), "
            ", _("(Click on a column header to sort by that column.)"), "</p>\n";
        echo "<table class='basic striped'>\n";
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
            echo "<td>" . strftime("%B %e, %Y, %H:%M", $row['date_created']) . "</td>\n";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
}
else if ($action == 'get_user') {
    if (check_username($username) != '') die("Invalid parameter username.");
    try
    {
        $user = new NonactivatedUser($username);
    }
    catch(NonexistentNonactivatedUserException $exception)
    {
        printf(_("No user '%s' was was found in the list of non-validated users."),
            html_safe($username));
        echo "<p>", 
            sprintf(_("Note that you can also <a href='%s'>list all user accounts awaiting activation</a>"), "?action=list_all"), 
            "</p>";
        exit;
    }

    echo "<p>";
    echo _("Enter the correct email-address below. When you submit the form, the activation mail will be resent.");
    echo "</p>";
    ?>
    <br>
    <form method='get'>
    <input type='hidden' name='action' value='set_email'>
    <input type='hidden' name='username' value='<?php echo attr_safe($username); ?>'>
    <?php echo _("Username"); ?>: <?php echo html_safe($username); ?>
    <br>
    <?php echo _("E-mail"); ?>: <input type='text' name='email' size='50' value='<?php echo attr_safe($user->email); ?>' required>
    <br>
    <input type='submit' value='<?php echo attr_safe(_("Update address and resend activation mail")); ?>'>
    </form>
    <?php
}
else if ($action == 'set_email') {
    if (check_username($username) != '') die("Invalid parameter username.");
    if (check_email_address($email) != '') die("Invalid parameter email.");
    
    $user = new NonactivatedUser($username);
    $user->email = $email;
    $user->save();

    maybe_activate_mail($email, $user->real_name, $user->id, $username, $user->u_intlang);
}
else
    echo 'Unknown action.';

// vim: sw=4 ts=4 expandtab
