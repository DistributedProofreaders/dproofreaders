<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

// Change format of page-state values.

$case = "
	CASE state
		WHEN 'avail_first'  THEN 'P1.page_avail'
		WHEN 'bad_first'    THEN 'P1.page_bad'
		WHEN 'out_first'    THEN 'P1.page_out'
		WHEN 'save_first'   THEN 'P1.page_saved'
		WHEN 'temp_first'   THEN 'P1.page_temp'
		WHEN 'avail_second' THEN 'P2.page_avail'
		WHEN 'bad_second'   THEN 'P2.page_bad'
		WHEN 'out_second'   THEN 'P2.page_out'
		WHEN 'save_second'  THEN 'P2.page_saved'
		WHEN 'temp_second'  THEN 'P2.page_temp'
		ELSE state
	END
";

echo "<pre>\n";

$project_res = mysql_query("
	SELECT projectid
	FROM projects
") or die(mysql_error());

while ( list($projectid) = mysql_fetch_row($project_res) )
{
	// First check whether the page-table exists.
	$res = mysql_query("
		DESCRIBE $projectid
	");
	if (!$res)
	{
		// page-table doesn't exist (has been archived).
		// echo "$projectid doesn't exist\n";
		continue;
	}

	echo "$projectid: ";
	mysql_query("
		UPDATE $projectid
		SET state=$case
	") or die(mysql_error());
	echo mysql_affected_rows(), " rows affected\n";
}

// project_pages too
echo "and project_pages: ";
mysql_query("
	UPDATE project_pages
	SET state=$case
") or die(mysql_error());
echo mysql_affected_rows(), " rows affected\n";

echo "done.\n";
echo "</pre>\n";
?>
