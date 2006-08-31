<?PHP
$relPath = '../../../pinc/';
include_once($relPath.'dpsql.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'connect.inc');
new dbConnect();

// Add columns for one more round,
// and shift current content over to make room.

// We're introducing a round before the current round #2.
// The new round will become round #2, the current #2 will become #3, etc.
$insertion_rn = 2;

// Note that, because this script includes the new version of the code,
// MAX_NUM_PAGE_EDITING_ROUNDS has its new value (higher by 1 than before).

$shifts_sql = "";
for ($dst_rn = MAX_NUM_PAGE_EDITING_ROUNDS; $dst_rn >= $insertion_rn; $dst_rn-- )
{
	$dst_round = get_Round_for_round_number($dst_rn);

	if ( $dst_rn > $insertion_rn )
	{
		$src_rn = $dst_rn - 1;
		$src_round = get_Round_for_round_number($src_rn);
		$shifts_sql .= "
			{$dst_round->time_column_name}={$src_round->time_column_name},
			{$dst_round->user_column_name}={$src_round->user_column_name},
			{$dst_round->text_column_name}={$src_round->text_column_name},
		";
	}
	else
	{
		$shifts_sql .= "
			{$dst_round->time_column_name}='',
			{$dst_round->user_column_name}='',
			{$dst_round->text_column_name}=''
		";

	}
}

function update_table( $table_name )
{
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
	$existing_column_names = dpsql_fetch_all_keyed($res);

	echo "$table_name: ";

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
		echo $adds_sql, "\n";
		mysql_query("
			ALTER TABLE $table_name
			$adds_sql
		") or die(mysql_error());
	}
	echo "Added $n_columns_to_add columns.";

	if ( $n_columns_to_add > 0 )
	{
		global $shifts_sql;
		mysql_query("
			UPDATE $table_name
			SET
				$shifts_sql
		") or die(mysql_error());
		echo " Shifted columns over.";
	}

	echo "\n";
}

// --------------------------------------------

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

echo "\nDone!\n";
?>
