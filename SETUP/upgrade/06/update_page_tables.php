<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'stages.inc');

// Change format of page-state values.
// And add columns for extra rounds.

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
	$res = mysqli_query(DPDatabase::get_connection(), "
		DESCRIBE $table_name
	");
	if (!$res)
	{
		// table doesn't exist (has been archived).
		// echo "$table_name doesn't exist\n";
		return;
	}
	$existing_column_names = dpsql_fetch_all_keyed($res);

	echo "$table_name: ";
	mysqli_query(DPDatabase::get_connection(), "
		UPDATE $table_name
		SET state=$case
	") or die(mysqli_error(DPDatabase::get_connection()));
	echo "State field changed on " . mysqli_affected_rows(DPDatabase::get_connection()), " rows. ";

	$n_columns_to_add = 0;
	$adds_sql = "";
	for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
	{
		$round = get_Round_for_round_number($rn);
		if ( !array_key_exists( $round->time_column_name, $existing_column_names ) )
		{
			$n_columns_to_add += 3;
			if ($adds_sql) $adds_sql .= ',';
			$adds_sql .= "
				ADD COLUMN {$round->time_column_name} int(20)     NOT NULL default '0',
				ADD COLUMN {$round->user_column_name} varchar(25) NOT NULL default '',
				ADD COLUMN {$round->text_column_name} longtext    NOT NULL
			";
		}
	}
	if ( $n_columns_to_add > 0 )
	{
		// echo $adds_sql, "\n";
		mysqli_query(DPDatabase::get_connection(), "
			ALTER TABLE $table_name
			$adds_sql
		") or die(mysqli_error(DPDatabase::get_connection()));
	}
	echo "Added $n_columns_to_add columns.";

	echo "\n";
}

// --------------------------------------------

$project_res = mysqli_query(DPDatabase::get_connection(), "
	SELECT projectid
	FROM projects
") or die(mysqli_error(DPDatabase::get_connection()));

while ( list($projectid) = mysqli_fetch_row($project_res) )
{
	update_table( $projectid );
}

// project_pages too
update_table( 'project_pages' );

echo "\nDone!\n";
?>
