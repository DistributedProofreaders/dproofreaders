<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once('../includes/team.php');
include_once('../includes/member.php');
$db_Connection=new dbConnect();

if (empty($_GET['order'])) {
	$order = "u_id";
	$direction = "asc";
} else {
	$order = $_GET['order'];
	$direction = $_GET['direction'];
}

if (!empty($_GET['mstart'])) { $mstart = $_GET['mstart']; } else { $mstart = 0; }

if (!empty($_REQUEST['uname'])) {
	$mResult = mysql_query("SELECT u_id, username, date_created, pagescompleted FROM users WHERE username LIKE '%".$_REQUEST['uname']."%' ORDER BY $order $direction LIMIT $mstart,20");
	$mRows = mysql_num_rows($mResult);
	if ($mRows == 1) { metarefresh(0,"mdetail.php?id=".mysql_result($mResult,0,"u_id")."",'',''); exit; }
	$uname = "uname=".$_REQUEST['uname']."&";
} else {
	$mResult=mysql_query("SELECT u_id, username, date_created, pagescompleted FROM users ORDER BY $order $direction LIMIT $mstart,20");
	$mRows = mysql_num_rows($mResult);
	$uname = "";
}

$title = _("Member List");
theme($title, "header");
echo "<center><br>";

//Display of user teams
echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='4' style='border-collapse: collapse' width='95%'>";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><font color='".$theme['color_headerbar_font']."'>"._("Distributed Proofreader Members")."</font></b></td></tr>";
echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
	if ($order == "u_id" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='5%' align='center'><b><a href='mbr_list.php?".$uname."mstart=$mstart&order=u_id&direction=$newdirection'>"._("ID")."</a></b></td>";
	if ($order == "username" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='23%' align='center'><b><a href='mbr_list.php?".$uname."mstart=$mstart&order=username&direction=$newdirection'>"._("Username")."</a></b></td>";
	if ($order == "date_created" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='23%' align='center'><b><a href='mbr_list.php?".$uname."mstart=$mstart&order=date_created&direction=$newdirection'>"._("Date Joined DP")."</a></b></td>";
	if ($order == "pagescompleted" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='25%' align='center'><b><a href='mbr_list.php?".$uname."mstart=$mstart&order=pagescompleted&direction=$newdirection'>"._("Total Pages Completed")."</a></b></td>";
	echo "<td width='23%' align='center'><b>"._("Options")."</b></td>";
echo "</tr>";
if (!empty($mRows)) {
	$i = 0;
	while ($row = mysql_fetch_assoc($mResult)) {
        	$phpbbID = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '".$row['username']."' LIMIT 1");
        	if (($i % 2) == 0) { echo "<tr bgcolor='".$theme['color_mainbody_bg']."'>"; } else { echo "<tr bgcolor='".$theme['color_navbar_bg']."'>"; }
		echo "<td width='5%' align='center'><b>".$row['u_id']."</b></td>";
		echo "<td width='25%'>".$row['username']."</td>";
		echo "<td width='22%' align='center'>".date("m/d/Y", $row['date_created'])."</td>";
		echo "<td width='25%' align='center'>".number_format($row['pagescompleted'])."</td>";
		echo "<td width='23%' align='center'><b><a href='mdetail.php?id=".$row['u_id']."'>"._("Statistics")."</a>&nbsp;|&nbsp;<a href='$forums_url/privmsg.php?mode=post&u=".mysql_result($phpbbID, 0, "user_id")."'>"._("PM")."</a></td>";
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