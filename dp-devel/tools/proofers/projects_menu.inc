<?
echo "<table border='1' width='630'><tr>";
echo "<td width='126' bgcolor='CCCCCC' align='center'>";

if (empty($userP['i_newwin'])) {
	echo "<a href='proof_per.php' target='_top'>";
} else {
	echo "<a href='#' onclick=\"top.window.opener.location='proof_per.php';top.window.close()\">";
}

echo _("Personal Page")."</a></td>";
echo "<td width='126' bgcolor='CCCCCC' align='center'><a href='$forums_url/index.php' target='forumwin'>"._("Forums")."</a></td>";

$setT = "<td width='252' bgcolor='#CCCCCC' align='center'>";
$setU = "project=$project&amp;proofstate=$proofstate";
$setP = "<a href='../../userprefs.php?$setU'>"._("Interface Preferences")."</a>";

if ($projectinfo->availablepages > 0) {
	echo "$setT<b><a href='proof_frame.php?$setU' target='proofframe'>"._("Start Proofing")."</a></b></td>";
} else {
	echo "$setT"._("Round Complete")."</td>";
}

echo $setT.$setP."\r\n</td>";
echo "<td width='126' bgcolor='#CCCCCC' align='center'>";

if (empty($userP['i_newwin'])) {
	echo "<a href='../logout.php'>";
} else {
	echo "<a href='#' onclick=\"top.window.opener.location='../logout.php';top.window.close();\">";
}

echo _("Logout")."</a></td>";

echo "</tr><tr><td colspan='5' align='center'><a href='list_avail.php'>"._("View Available Projects")."</a></td></table>";
?>