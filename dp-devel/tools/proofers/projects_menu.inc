<?
// The file that includes this one (i.e. projects.php)
// must appropriately define the following variables,
// either directly or indirectly (via other included files):
//     $userP
//     $forums_url
//     $project
//     $proofstate

echo "<table border='1' width='630'><tr>";

// -------

echo "<td bgcolor='CCCCCC' align='center'>";

if (empty($userP['i_newwin'])) {
	echo "<a href='$code_url/activity_hub.php' target='_top'>";
} else {
	echo "<a href='$code_url/activity_hub.php' onclick=\"top.window.opener.location='$code_url/activity_hub.php';top.window.close()\">";
}

echo _("Activity Hub")."</a></td>";

// -------

$round = get_Round_for_project_state($proofstate);
echo "<td bgcolor='CCCCCC' align='center'><a href='round.php?round_id={$round->round_id}' target='_top'>"; 
echo $round->round_name;
echo "</a></td>";

// -------

$setT = "<td bgcolor='#CCCCCC' align='center'>";
$setU = "project=$project&amp;proofstate=$proofstate";
$setP = "<a href='../../userprefs.php?$setU&tab=1'>"._("Interface Preferences")."</a>";

echo $setT.$setP."\r\n</td>";

// -------

echo "<td bgcolor='CCCCCC' align='center'><a href='$forums_url/index.php' target='forumwin'>"._("Forums")."</a></td>";

// -------

echo "<td bgcolor='#CCCCCC' align='center'>";

if (empty($userP['i_newwin'])) {
	echo "<a href='../logout.php'>";
} else {
	echo "<a href='../logout.php' onclick=\"top.window.opener.location='../logout.php';top.window.close();\">";
}

echo _("Logout")."</a></td>";

// -------

echo "</tr>";
echo "</table>";
?>
