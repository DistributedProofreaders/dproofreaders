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

echo "$setT";

if (($top_menu) && ($my_last_page_date < $comments_last_modified)) {
	echo "(See Below for Start Link)";
} else if ($projectinfo->availablepages > 0) {
	echo "<b><a href='proof_frame.php?$setU' target='proofframe'>"._("Start Proofreading")."</a></b>";
} else {
	echo _("Round Complete");
}

echo "</td>";
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