<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once('../includes/team.php');
$db_Connection=new dbConnect();

if (empty($_GET['order'])) {
	$order = "id";
	$direction = "asc";
} else {
	$order = $_GET['order'];
	$direction = $_GET['direction'];
}

if (!empty($_GET['tstart'])) { $tstart = $_GET['tstart']; } else { $tstart = 0; }

if (!empty($_REQUEST['tname'])) {
	$tResult = select_from_teams("teamname LIKE '%{$_REQUEST['tname']}%'", "ORDER BY $order $direction LIMIT $tstart,20");
	$tRows = mysql_num_rows($tResult);
	if ($tRows == 1) { metarefresh(0,"tdetail.php?tid=".mysql_result($tResult,0,"id")."",'',''); exit; }
	$tname = "tname=".$_REQUEST['uname']."&";
} else {
	$tResult=select_from_teams("", "ORDER BY $order $direction LIMIT $tstart,20");
	$tRows=mysql_num_rows($tResult);
	$tname = "";
}

$name = _("Team List");

theme($name, "header");
echo "<center><br>";

//Display of user teams
echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='4' style='border-collapse: collapse' width='95%'>\n";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><font color='".$theme['color_headerbar_font']."'>";
echo _("User Teams");
echo "</font></b></td></tr>\n";
echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
	echo "<td align='center'><b>"._("Icon")."</b></td>";
	if ($order == "id" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='tlist.php?".$tname."tstart=$tstart&order=id&direction=$newdirection'>"._("ID")."</a></b></td>";
	if ($order == "teamname" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='tlist.php?".$tname."tstart=$tstart&order=teamname&direction=$newdirection'>"._("Team Name")."</a></b></td>";
	if ($order == "member_count" && $direction == "desc") { $newdirection = "asc"; } else { $newdirection = "desc"; }
		echo "<td align='center'><b><a href='tlist.php?".$tname."tstart=$tstart&order=member_count&direction=$newdirection'>"._("Total Members")."</a></b></td>";
	if ($order == "page_count" && $direction == "desc") { $newdirection = "asc"; } else { $newdirection = "desc"; }
		echo "<td align='center'><b><a href='tlist.php?".$tname."tstart=$tstart&order=page_count&direction=$newdirection'>"._("Page Count")."</a></b></td>";
	echo "<td align='center'><b>"._("Options")."</b></td>";
echo "</tr>\n";
if (!empty($tRows)) {
	$i = 0;
	while ($row = mysql_fetch_assoc($tResult)) {
        	if (($i % 2) == 0) { echo "<tr bgcolor='".$theme['color_mainbody_bg']."'>"; } else { echo "<tr bgcolor='".$theme['color_navbar_bg']."'>"; }
		echo "<td align='center'><a href='tdetail.php?tid=".$row['id']."'><img src='$team_icons_url/".$row['icon']."' width='25' height='25' alt='".strip_tags($row['teamname'])."' border='0'></a></td>";
		echo "<td align='center'><b>".$row['id']."</b></td>";
		echo "<td>".$row['teamname']."</td>";
		echo "<td align='center'>".$row['member_count']."</td>";
		echo "<td align='center'>".$row['page_count']."</td>";
		echo "<td align='center'><b><a href='tdetail.php?tid=".$row['id']."'>"._("View")."</a>&nbsp;";
		if ($userP['team_1'] != $row['id'] && $userP['team_2'] != $row['id'] && $userP['team_3'] != $row['id']) {
			echo "<a href='../members/jointeam.php?tid=".$row['id']."'>"._("Join")."</a></b></td>";
		} else {
			echo "<a href='../members/quitteam.php?tid=".$row['id']."'>"._("Quit")."</a></b></td>";
		}
		echo "</tr>\n";
		$i++;
	}
} else {
	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='6' align='center'><b>"._("No more teams available.")."</b></td></tr>\n";
}

echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='3' align='left'>";
if (!empty($tstart)) {
	echo "<b><a href='tlist.php?".$tname."order=$order&direction=$direction&tstart=".($tstart-20)."'>"._("Previous")."</a></b>";
}
echo "&nbsp;</td><td colspan='3' align='right'>&nbsp;";
if ($tRows == 20) {
	echo "<b><a href='tlist.php?".$tname."order=$order&direction=$direction&tstart=".($tstart+20)."'>"._("Next")."</a></b>";
}
echo "</td></tr>\n";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><a href='new_team.php'><font color='".$theme['color_headerbar_font']."'>"._("Create a New Team")."</font></a></b></td></tr>\n";
echo "</table><p>";
theme("", "footer");
?>
