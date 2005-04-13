<?
include_once($relPath.'project_states.inc');
include_once($relPath.'RoundDescriptor.inc');
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

function project_update_page_counts($projectid) {

	$is_avail = "0";
	for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
	{
		$round = get_Round_for_round_number($rn);
		$is_avail .= " || state='$round->page_avail_state'";
	}
	$result = mysql_query("
		SELECT
			COUNT(*) AS total_pages,
			SUM( $is_avail ) as avail_pages
		FROM $projectid
	");
	// Note that when MySQL evaluates
	//     state='...' || state='...' || ...
	// for each page, the result is either 0 (false) or 1 (true).
	// Summing this over all pages gives the number of pages in some available state.

	if (!$result)
	{
		echo mysql_error(), "\n";
		return;
	}

	$total_pages = mysql_result($result, 0, "total_pages");
	$avail_pages = mysql_result($result, 0, "avail_pages");

	checkRowExistence($projectid);
	$result = mysql_query("
		UPDATE page_counts
		SET total_pages = $total_pages, avail_pages = $avail_pages
		WHERE projectid = '$projectid'
	");
}

function deletePageCounts($projectid) {
	$result = mysql_query("DELETE FROM page_counts WHERE projectid = '$projectid'");
}

function update_pageCountArray() {
	global $pageCountArray;

	$result = mysql_query("SELECT projectid, total_pages, avail_pages FROM page_counts");
	while ($row = mysql_fetch_assoc($result)) {
		$pageCountArray[$row['projectid']]['total_pages'] = $row['total_pages'];
		$pageCountArray[$row['projectid']]['avail_pages'] = $row['avail_pages'];
	}
}

function updatePageCountsTable() {
	$state = SQL_CONDITION_BRONZE." OR ".SQL_CONDITION_SILVER;
	$availableProjects = mysql_query("SELECT projectid FROM projects WHERE $state");
	while ($row = mysql_fetch_assoc($availableProjects)) {
		project_update_page_counts($row['projectid']);
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
