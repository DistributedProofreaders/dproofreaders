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

function update_table( $table_name )
{
	global $case;

	// First check whether the table exists.
	$res = mysql_query("
		DESCRIBE $table_name
	");
	if (!$res)
	{
		// table doesn't exist (has been archived).
		// echo "$table_name doesn't exist\n";
		return;
	}

	echo "$table_name: ";
	mysql_query("
		UPDATE $table_name
		SET state=$case
	") or die(mysql_error());
	echo mysql_affected_rows(), " rows affected\n";
}

// --------------------------------------------

echo "<pre>\n";

$project_res = mysql_query("
	SELECT projectid
	FROM projects
") or die(mysql_error());

while ( list($projectid) = mysql_fetch_row($project_res) )
{
	update_table( $projectid );
}

// project_pages too
update_table( 'project_pages' );

echo "done.\n";
echo "</pre>\n";
?>
