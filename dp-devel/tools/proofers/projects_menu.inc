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
	echo "<a href='proof_per.php' target='_top'>";
} else {
	echo "<a href='#' onclick=\"top.window.opener.location='proof_per.php';top.window.close()\">";
}

echo _("Personal Page")."</a></td>";

// -------

echo "<td bgcolor='CCCCCC' align='center'><a href='list_avail.php' target='_top'>"; 
echo _("View Available Projects");
echo "</a></td>";

// -------

$setT = "<td bgcolor='#CCCCCC' align='center'>";
$setU = "project=$project&amp;proofstate=$proofstate";
$setP = "<a href='../../userprefs.php?$setU'>"._("Interface Preferences")."</a>";

echo $setT.$setP."\r\n</td>";

// -------

echo "<td bgcolor='CCCCCC' align='center'><a href='$forums_url/index.php' target='forumwin'>"._("Forums")."</a></td>";

// -------

echo "<td bgcolor='#CCCCCC' align='center'>";

if (empty($userP['i_newwin'])) {
	echo "<a href='../logout.php'>";
} else {
	echo "<a href='#' onclick=\"top.window.opener.location='../logout.php';top.window.close();\">";
}

echo _("Logout")."</a></td>";

// -------

echo "</tr>";
echo "</table>";
?>
