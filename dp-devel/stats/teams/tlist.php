<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.php');
$db_Connection=new dbConnect();

theme("Team List", "header");
echo "<center><br>";

if (empty($_GET['order'])) {
	$order = "id";
	$direction = "asc";
} else {
	$order = $_GET['order'];
	$direction = $_GET['direction'];
}

if (!empty($_GET['tstart'])) { $tstart = $_GET['tstart']; } else { $tstart = 0; }

$tResult=mysql_query("SELECT teamname, id, icon, member_count, page_count FROM user_teams ORDER BY $order $direction LIMIT $tstart,20");
$tRows=mysql_num_rows($tResult);

//Display of user teams
echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='4' style='border-collapse: collapse' width='95%'>";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><font color='".$theme['color_headerbar_font']."'>User Teams</font></b></td></tr>";
echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
	echo "<td align='center'><b>Icon</b></td>";
	if ($order == "id" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='tlist.php?tstart=$tstart&order=id&direction=$newdirection'>ID</a></b></td>";
	if ($order == "teamname" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='tlist.php?tstart=$tstart&order=teamname&direction=$newdirection'>Team Name</a></b></td>";
	if ($order == "member_count" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='tlist.php?tstart=$tstart&order=member_count&direction=$newdirection'>Total Members</a></b></td>";
	if ($order == "page_count" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='tlist.php?tstart=$tstart&order=page_count&direction=$newdirection'>Page Count</a></b></td>";
	echo "<td align='center'><b>Options</b></td>";
echo "</tr>";
if (!empty($tRows)) {
	$i = 0;
	while ($row = mysql_fetch_assoc($tResult)) {
        	if (($i % 2) == 0) { echo "<tr bgcolor='".$theme['color_mainbody_bg']."'>"; } else { echo "<tr bgcolor='".$theme['color_navbar_bg']."'>"; }
		echo "<td align='center'><a href='tdetail.php?tid=".$row['id']."'><img src='".$GLOBALS['code_url']."/users/teams/icon/".$row['icon']."' width='25' height='25' alt='".strip_tags($row['teamname'])."' border='0'></a></td>";
		echo "<td align='center'><b>".$row['id']."</b></td>";
		echo "<td>".$row['teamname']."</td>";
		echo "<td align='center'>".$row['member_count']."</td>";
		echo "<td align='center'>".$row['page_count']."</td>";
		echo "<td align='center'><b><a href='tdetail.php?tid=".$row['id']."'>View</a>&nbsp;";
		if ($row['id'] != 1 && $userP['team_1'] != $row['id'] && $userP['team_2'] != $row['id'] && $userP['team_3'] != $row['id']) {
			echo "<a href='../members/jointeam.php?tid=".$row['id']."'>Join</a></b></td>";
		} elseif ($row['id'] != 1) {
			echo "<a href='../members/quitteam.php?tid=".$row['id']."'>Quit</a></b></td>";
		}
		echo "</tr>";
		$i++;
	}
} else {
	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='6' align='center'><b>No more teams available.</b></td></tr>";
}

echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='3' align='left'>";
if (!empty($tstart)) {
	echo "<b><a href='tlist.php?order=$order&direction=$direction&tstart=".($tstart-20)."'>Previous</a></b>";
}
echo "&nbsp;</td><td colspan='3' align='right'>&nbsp;";
if ($tRows == 20) {
	echo "<b><a href='tlist.php?order=$order&direction=$direction&tstart=".($tstart+20)."'>Next</a></b>";
}
echo "</td></tr>";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><a href='new_team.php'><font color='".$theme['color_headerbar_font']."'>Create a New Team</font></a></b></td></tr>";
echo "</table><p>";
theme("", "footer");
?>