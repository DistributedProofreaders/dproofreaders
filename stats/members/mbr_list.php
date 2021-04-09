<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'privacy.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'forum_interface.inc');
include_once('../includes/team.inc');
include_once('../includes/member.inc');

require_login();

$order = get_enumerated_param(
    $_GET, 'order', 'u_id', array('u_id', 'username', 'date_created') );
$direction = get_enumerated_param(
    $_GET, 'direction', 'asc', array('asc', 'desc') );
$mstart = get_integer_param( $_GET, 'mstart', 0, 0, null );
$uname = normalize_whitespace(array_get($_GET, 'uname', ''));
$uexact = array_get($_GET, 'uexact', '') == 'yes';

if ($uname) {
    if ($uexact)
    {
        $where_clause = sprintf("WHERE username = '%s'", DPDatabase::escape($uname));
    }
    else
    {
        $where_clause = sprintf("WHERE username LIKE '%%%s%%'",
            addcslashes(DPDatabase::escape($uname), "%_"));
    }

    // Not using sprintf() here because of the wildcard where_clause above.
    // We're relying on get_integer_param() to enforce this as an integer.
    $sql = "
        SELECT u_id, username, date_created, u_privacy
        FROM users
        $where_clause
        ORDER BY $order $direction
        LIMIT $mstart,20
    ";
    $mResult = DPDatabase::query($sql);
    $mRows = mysqli_num_rows($mResult);
    if ($mstart == 0 && $mRows == 1)
    {
        $row = mysqli_fetch_assoc($mResult);
        metarefresh(0, "mdetail.php?id=".$row["u_id"]);
    }
} else {
    $sql = sprintf("
        SELECT u_id, username, date_created, u_privacy
        FROM users
        ORDER BY $order $direction
        LIMIT %d,20
    ", $mstart);
    $mResult = DPDatabase::query($sql);
    $mRows = mysqli_num_rows($mResult);
}

$title = _("Member List");
output_header(_("Member List"));
echo "<h1>" . html_safe($title) . "</h1>\n";

echo "<form method='get'>";
echo "<input type='hidden' name='mstart' value='0'>";
echo "<input type='text' name='uname' size='20' value='" . attr_safe($uname) . "'> ";
echo "<input type='submit' value='" . attr_safe(_("Search")) . "'>";
echo "<br>";
$uexact_checked = $uexact ? "checked" : "";
echo "<input type='checkbox' name='uexact' value='yes' $uexact_checked> " . _("Exact match");
echo "</form>";
echo "<br>";

//Display members
echo "<table class='themed theme_striped'>";
echo "<tr>";
if ($order == "u_id" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
echo "<th style='width: 5%; text-align: center;'><a href='mbr_list.php?uname=" . attr_safe($uname) . "&amp;mstart=$mstart&amp;order=u_id&amp;direction=$newdirection'>"._("ID")."</a></th>";
if ($order == "username" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
echo "<th><a href='mbr_list.php?uname=" . attr_safe($uname) . "&amp;mstart=$mstart&amp;order=username&amp;direction=$newdirection'>"._("Username")."</a></th>";
if ($order == "date_created" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
echo "<th style='text-align: center;'><a href='mbr_list.php?uname=" . attr_safe($uname) . "&amp;mstart=$mstart&amp;order=date_created&amp;direction=$newdirection'>".sprintf(_("Date Joined %s"),$site_abbreviation)."</a></th>";
echo "<th style='text-align: center;'>"._("Options")."</th>";
echo "</tr>";

if (!empty($mRows)) {
    while ($row = mysqli_fetch_assoc($mResult)) {
        echo "<tr>";

        if ( can_reveal_details_about($row['username'], $row['u_privacy']) ) {

            echo "<td style='text-align: center;'><b>".$row['u_id']."</b></td>";
            echo "<td>".$row['username']."</td>";
            echo "<td style='text-align: center;'>".date("m/d/Y", $row['date_created'])."</td>";
            $contact_url = attr_safe(get_url_to_compose_message_to_user($row['username']));
            echo "<td style='text-align: center'><b><a href='mdetail.php?id=".$row['u_id']."'>"._("Statistics")."</a>&nbsp;|&nbsp;<a href='$contact_url'>" . pgettext("private message", "PM") . "</a></b></td>\n";

        } else {
            // Print Anonymous Info

            echo "<td style='text-align: center;'><b>---</b></td>";
            echo "<td>" . _("Anonymous") . "</td>";
            echo "<td style='text-align: center;'>---</td>";
            echo "<td style='text-align: center;'>---</td>";

        }


        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' style='text-align: center;'><b>"._("No more members available.")."</b></td></tr>";
}

echo "<tr><td colspan='2'>";
if (!empty($mstart)) {
    echo "<b><a href='mbr_list.php?uname=" . attr_safe($uname) . "&amp;order=$order&amp;direction=$direction&amp;mstart=".($mstart-20)."'>"._("Previous")."</a></b>";
}
echo "&nbsp;</td><td colspan='2' style='text-align: right;'>&nbsp;";
if ($mRows == 20) {
    echo "<b><a href='mbr_list.php?uname=" . attr_safe($uname) . "&amp;order=$order&amp;direction=$direction&amp;mstart=".($mstart+20)."'>"._("Next")."</a></b>";
}
echo "</td></tr>";
echo "</table>";

