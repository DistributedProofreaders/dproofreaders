<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'misc.inc');
include_once($relPath.'privacy.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'forum_interface.inc');
include_once('../includes/team.inc');
include_once('../includes/member.inc');
$db_Connection=new dbConnect();

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
theme($title, "header");
echo "<center><br>";

//Display of user teams
echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='4' style='border-collapse: collapse' width='95%'>";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><font color='".$theme['color_headerbar_font']."'>"
    // TRANSLATORS: %s is the site name
    . sprintf(_("%s Members"),$site_name) . "</font></b></td></tr>";
echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
    if ($order == "u_id" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
        echo "<td width='5%' align='center'><b><a href='mbr_list.php?".$uname."mstart=$mstart&order=u_id&direction=$newdirection'>"._("ID")."</a></b></td>";
    if ($order == "username" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
        echo "<td width='23%' align='center'><b><a href='mbr_list.php?".$uname."mstart=$mstart&order=username&direction=$newdirection'>"._("Username")."</a></b></td>";
    if ($order == "date_created" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
        echo "<td width='23%' align='center'><b><a href='mbr_list.php?".$uname."mstart=$mstart&order=date_created&direction=$newdirection'>".sprintf(_("Date Joined %s"),$site_abbreviation)."</a></b></td>";
    echo "<td width='23%' align='center'><b>"._("Options")."</b></td>";
echo "</tr>";
if (!empty($mRows)) {
    $i = 0;
    while ($row = mysql_fetch_assoc($mResult)) {
            if (($i % 2) == 0) { echo "<tr bgcolor='".$theme['color_mainbody_bg']."'>"; } else { echo "<tr bgcolor='".$theme['color_navbar_bg']."'>"; }

        if ( can_reveal_details_about($row['username'], $row['u_privacy']) ) {

            echo "<td width='5%' align='center'><b>".$row['u_id']."</b></td>";
            echo "<td width='25%'>".$row['username']."</td>";
            echo "<td width='22%' align='center'>".date("m/d/Y", $row['date_created'])."</td>";
            $contact_url = get_url_to_compose_message_to_user($row['username']);
            echo "<td width='23%' align='center'><b><a href='mdetail.php?id=".$row['u_id']."'>"._("Statistics")."</a>&nbsp;|&nbsp;<a href='$contact_url'>"._("PM")."</a></b></td>";

        } else {
            // Print Anonymous Info

            echo "<td width='5%' align='center'><b>---</b></td>";
            echo "<td width='25%'>" . _("Anonymous") . "</td>";
            echo "<td width='22%' align='center'>---</td>";
            echo "<td width='23%' align='center'>" . _("None") . "</td>";

        }


        echo "</tr>";
        $i++;
    }
} else {
    echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='6' align='center'><b>"._("No more members available.")."</b></td></tr>";
}

echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='3' align='left'>";
if (!empty($mstart)) {
    echo "<b><a href='mbr_list.php?".$uname."order=$order&direction=$direction&mstart=".($mstart-20)."'>"._("Previous")."</a></b>";
}
echo "&nbsp;</td><td colspan='3' align='right'>&nbsp;";
if ($mRows == 20) {
    echo "<b><a href='mbr_list.php?".$uname."order=$order&direction=$direction&mstart=".($mstart+20)."'>"._("Next")."</a></b>";
}
echo "</td></tr>";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><a href='$code_url/accounts/addproofer.php'><font color='".$theme['color_headerbar_font']."'>"._("Create a New Account")."</font></a></b></td></tr>";
echo "</table><p>";
theme("", "footer");
?>
