<?
include_once($relPath.'page_states.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');

$pageCountArray = "";

if ((user_is_a_sitemanager()) || (substr($_SERVER['PHP_SELF'], -14) == "automodify.php")) {
	$result = mysql_query("SELECT COUNT(projectid) AS isEmpty FROM page_counts");
	$isEmpty = mysql_result($result, 0, "isEmpty");
	if (empty($isEmpty)) { updatePageCountsTable(); }
}

function checkRowExistence($projectid) {
	$result = mysql_query("SELECT COUNT(projectid) AS rowExists FROM page_counts WHERE projectid = '$projectid'");
	$rowExists = mysql_result($result, 0, "rowExists");
	if (empty($rowExists)) {
		$result = mysql_query("INSERT INTO page_counts (projectid, total_pages, avail_pages) VALUES ('$projectid', 0, 0)");
	}
}

function update_total_pages($projectid, $adjustAvail) {
	$result = mysql_query("SELECT COUNT(fileid) AS total_pages FROM $projectid");
	$total_pages = mysql_result($result, 0, "total_pages");
	if (!empty($adjustAvail)) { $avail_pages = $total_pages; } else { $avail_pages = 0; }
	checkRowExistence($projectid);
	$result = mysql_query("UPDATE page_counts SET total_pages = $total_pages, avail_pages = $avail_pages WHERE projectid = '$projectid'");
}

function update_avail_pages($projectid, $state) {
	if (empty($state)) { $state = " = '".AVAIL_FIRST."' || state = '".AVAIL_SECOND."'"; }
	$result = mysql_query("SELECT COUNT(fileid) AS avail_pages FROM $projectid WHERE state$state") or die(mysql_error());
	$avail_pages = mysql_result($result, 0, "avail_pages");
	checkRowExistence($projectid);
	$result = mysql_query("UPDATE page_counts SET avail_pages = $avail_pages WHERE projectid = '$projectid'");
}

function deletePageCounts($projectid) {
	$result = mysql_query("DELETE FROM page_counts WHERE projectid = '$projectid'");
}

function total_pages() {
	global $pageCountArray;
	$result = mysql_query("SELECT projectid, total_pages FROM page_counts");
	while ($row = mysql_fetch_assoc($result)) {
		$pageCountArray[$row['projectid']]['total_pages'] = $row['total_pages'];
	}
}

function avail_pages() {
	global $pageCountArray;
	$result = mysql_query("SELECT projectid, avail_pages FROM page_counts");
	while ($row = mysql_fetch_assoc($result)) {
		$pageCountArray[$row['projectid']]['avail_pages'] = $row['avail_pages'];
	}
}

function updatePageCountsTable() {
	$state = SQL_CONDITION_BRONZE." OR ".SQL_CONDITION_SILVER;
	$availableProjects = mysql_query("SELECT projectid FROM projects WHERE $state");
	while ($row = mysql_fetch_assoc($availableProjects)) {
		update_total_pages($row['projectid'], 0);
		update_avail_pages($row['projectid'], "");
	}
}

function bookpages($project,$state) {
  $dbQ = mysql_query("SELECT count(fileid) AS totalpages FROM $project");
  if ($dbQ != "") { $book['total']=mysql_result($dbQ,0,"totalpages"); } else $book['total'] = -1;
  $dbQ=mysql_query("SELECT count(fileid) AS availpages FROM $project WHERE state$state");
  if ($dbQ != "") { $book['available']=mysql_result($dbQ,0,"availpages"); } else $book['available'] = -1;
  return $book;
}
?>