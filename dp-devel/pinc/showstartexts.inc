<?
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'list_projects.inc');

function showstartexts($etext_limit,$type) {
global $code_url;

if ($type == "bronze") {
$state = SQL_CONDITION_BRONZE;
$text = " " . _("Now Proofreading.") . "</font>  " . _("These books are currently being processed through our site; sign in and start helping!!!!") . " <br>";
} elseif ($type == "silver") {
$state = SQL_CONDITION_SILVER;
$text = " " . _("In Progress.") . "</font>  " . _("These books have been processed through our site but have not yet posted to the Project Gutenberg archive (currently going through their final proofreading/assembly).") . " <br>";
} elseif ($type == "gold") {
$state = SQL_CONDITION_GOLD;
$text = " " . _("Complete.") . "</font>  " . _("These books have been processed through our site and posted to the Project Gutenberg archive.");
$text .= "
	<a href='$code_url/feeds/backend.php?content=posted'>
		<img src='$code_url/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle' alt='[XML]'></a>
	<a href='$code_url/feeds/backend.php?content=posted&amp;type=rss'>
		<img src='$code_url/graphics/rss.gif' border='0' width='36' height='14' style='vertical-align:middle' alt='[RSS]'></a>
	<br>";
}

$total = mysql_num_rows(mysql_query("SELECT projectid FROM projects WHERE $state"));

echo "<img src='$code_url/graphics/{$type}_star.jpg' border='0' height='38' width='40' alt='$type star'> = <font face='Verdana' size='4'>".number_format($total)." $text";

list_projects( $state, "ORDER BY modifieddate DESC", "LIMIT $etext_limit" );

if ($total > $etext_limit) {
echo "<font face='Verdana' size='1'>--<a href='list_etexts.php?x=".substr($type,0,1)."'>"._("See more...")."</a></font><br><br>";
}

}
?>
