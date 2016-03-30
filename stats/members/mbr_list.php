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

$order = get_enumerated_param(
    $_GET, 'order', 'u_id', array('u_id', 'username', 'date_created') );
$direction = get_enumerated_param(
    $_GET, 'direction', 'asc', array('asc', 'desc') );
$mstart = get_integer_param( $_GET, 'mstart', 0, 0, null );
$uname = @$_REQUEST['uname'];
$uexact = @$_REQUEST['uexact'];

if (!empty($uname)) {
    if ($uexact == 'yes')
        $where_clause = "WHERE username='" . $uname . "'";
    else
        $where_clause = "WHERE username LIKE '%" . addcslashes($uname, "%_") . "%'";

    $mResult = mysql_query("
        SELECT u_id, username, date_created, u_privacy
        FROM users
        $where_clause
        ORDER BY $order $direction
        LIMIT $mstart,20
    ");
    $mRows = mysql_num_rows($mResult);
    if ($mRows == 1) { metarefresh(0,"mdetail.php?id=".mysql_result($mResult,0,"u_id")."",'',''); exit; }
    $uname = "uname=".$uname."&";
} else {
    $mResult=mysql_query("
        SELECT u_id, username, date_created, u_privacy
        FROM users
        ORDER BY $order $direction
        LIMIT $mstart,20
    ");
    $mRows = mysql_num_rows($mResult);
    $uname = "";
}

$title = _("Member List");
output_header(_("Member List"));
echo "<h1>$title</h1>\n";

//Display of user teams
echo "<table class='themed striped'>";
echo "<tr>";
    if ($order == "u_id" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
        echo "<th width='5%' align='center'><a href='mbr_list.php?".$uname."mstart=$mstart&order=u_id&direction=$newdirection'>"._("ID")."</a></th>";
    if ($order == "username" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
        echo "<th width='23%' align='center'><a href='mbr_list.php?".$uname."mstart=$mstart&order=username&direction=$newdirection'>"._("Username")."</a></th>";
    if ($order == "date_created" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
        echo "<th width='23%' align='center'><a href='mbr_list.php?".$uname."mstart=$mstart&order=date_created&direction=$newdirection'>".sprintf(_("Date Joined %s"),$site_abbreviation)."</a></th>";
    echo "<th width='23%' align='center'>"._("Options")."</th>";
echo "</tr>";
if (!empty($mRows)) {
    while ($row = mysql_fetch_assoc($mResult)) {
        echo "<tr>";

        if ( can_reveal_details_about($row['username'], $row['u_privacy']) ) {

            echo "<td width='5%' align='center'><b>".$row['u_id']."</b></td>";
            echo "<td width='25%'>".$row['username']."</td>";
            echo "<td width='22%' align='center'>".date("m/d/Y", $row['date_created'])."</td>";
            $contact_url = get_url_to_compose_message_to_user($row['username']);
            echo "<td width='23%' align='center'><b><a href='mdetail.php?id=".$row['u_id']."'>"._("Statistics")."</a>&nbsp;|&nbsp;<a href='$contact_url'>"._("PM")."</a></b></td>\n";

        } else {
            // Print Anonymous Info

            echo "<td width='5%' align='center'><b>---</b></td>";
            echo "<td width='25%'>" . _("Anonymous") . "</td>";
            echo "<td width='22%' align='center'>---</td>";
            echo "<td width='23%' align='center'>" . _("None") . "</td>";

        }


        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' align='center'><b>"._("No more members available.")."</b></td></tr>";
}

echo "<tr><td colspan='2' align='left'>";
if (!empty($mstart)) {
    echo "<b><a href='mbr_list.php?".$uname."order=$order&direction=$direction&mstart=".($mstart-20)."'>"._("Previous")."</a></b>";
}
echo "&nbsp;</td><td colspan='2' align='right'>&nbsp;";
if ($mRows == 20) {
    echo "<b><a href='mbr_list.php?".$uname."order=$order&direction=$direction&mstart=".($mstart+20)."'>"._("Next")."</a></b>";
}
echo "</td></tr>";
echo "</table><p></center>";

// vim: sw=4 ts=4 expandtab
