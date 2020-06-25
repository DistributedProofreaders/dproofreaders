<?php
define("DPSQL_NO_RANK",   False);
define("DPSQL_SHOW_RANK", True);

define("DPSQL_NO_RIGHT_ALIGN_INTS", False);
define("DPSQL_RIGHT_ALIGN_INTS",    True);

/********* Compatibility wrappers for noncvs scripts *********/
function dpsql_dump_ranked_query( $query )
{
    dpsql_dump_query($query, 0, DPSQL_SHOW_RANK);
}

function dpsql_dump_themed_ranked_query( $query )
{
    dpsql_dump_themed_query($query, 0, DPSQL_SHOW_RANK);
}

function dpsql_dump_query_result_with_rank( $result, $start_at )
{
    dpsql_dump_query_result($result, $start_at, DPSQL_SHOW_RANK);
}

function dpsql_dump_query_themed_result_with_rank( $result, $start_at )
{
    dpsql_dump_themed_query_result($result, $start_at, DPSQL_SHOW_RANK);
}
/********* Compatibility wrappers for noncvs scripts *********/

function dpsql_dump_table( $table_name )
{
	dpsql_dump_query( "SELECT * FROM $table_name" );
}

function dpsql_dump_query($query, $start_at = 0, $show_rank = DPSQL_NO_RANK)
{
	$result = dpsql_query( $query );
	dpsql_dump_query_result($result, $start_at, $show_rank);
}

function dpsql_dump_themed_query($query, $start_at = 0, $show_rank = DPSQL_NO_RANK, $ralign_ints = DPSQL_RIGHT_ALIGN_INTS)
{
	$result = dpsql_query( $query );
	dpsql_dump_themed_query_result($result, $start_at, $show_rank, $ralign_ints);
}

function dpsql_query( $query )
{
	$result = mysqli_query(DPDatabase::get_connection(),  $query );
	if (!$result)
	{
		print DPDatabase::log_error();
	}
	return $result;
}

function dpsql_dump_query_result($result, $start_at = 0, $show_rank = DPSQL_NO_RANK)
{

	$n_cols = mysqli_num_fields($result);
	$n_curr_row = $start_at;

	print "<table class='basic striped'>\n";

	print "<tr>\n";
	if ($show_rank)
	{
		print "<th>";
		print _("Rank");
		print "</th>\n";
	}

	for ($c = 0; $c < ($n_cols); $c++ )
	{
		print "<th>";
		$field_data = mysqli_fetch_field_direct($result, $c);
		print $field_data->name;
		print "</th>\n";
	}
	print "</tr>\n";

	while ( $row = mysqli_fetch_row($result) )
	{
		print "<tr>\n";
		if ($show_rank)
		{
			print "<td>$n_curr_row</td>\n";
		}

		for ($c = 0; $c < ($n_cols); $c++ )
		{
			$val = is_null($row[$c]) ? 'NULL' : $row[$c];
			print "<td>$val</td>\n";
		}
		print "</tr>\n";
		$n_curr_row++;
	}

	print "</table>\n";
}

function dpsql_dump_themed_query_result($result, $start_at = 0, $show_rank = DPSQL_NO_RANK, $ralign_ints = DPSQL_RIGHT_ALIGN_INTS)
{
	$n_query_cols = mysqli_num_fields($result);
	$n_curr_row = $start_at;

	print "<table class='themed theme_striped stats'>\n";
	
	$n_cols = $show_rank ? $n_query_cols + 1 : $n_query_cols;
	$perc = 100 / $n_cols;
	for ($c = 0; $c < $n_cols; $c++ )
	{
		print "\n<col style='width: {$perc}%;'>";
	}

	print "<tr>\n";
	if ($show_rank)
	{
		print "<th>";
		print _("Rank");
		print "</th>\n";
	}

	$type = Array();
	for ($c = 0; $c < $n_query_cols; $c++ )
	{
		$field_data = mysqli_fetch_field_direct($result, $c);
		$type[$c] = $field_data->type;
		$align = ($type[$c] == 'int' && $ralign_ints) ? " style='text-align: right;'" : '';
		print "<th$align>\n";
		print $field_data->name;
		print "</th>\n";
	}
	print "</tr>\n";

	while ( $row = mysqli_fetch_row($result) )
	{
		print "<tr>";
		if ($show_rank)
		{
			print "<td>";
			print number_format($n_curr_row);
			print "</td>\n";
		}

		for ($c = 0; $c < $n_query_cols; $c++)
		{
			print '<td' . (($type[$c] == 'int' && $ralign_ints) ? " style='text-align: right;'" : '') . '>';
			print ($type[$c] == 'int') ? number_format($row[$c]) : $row[$c];
			print "</td>\n";
		}
		print "</tr>\n";
		$n_curr_row++;
	}

	print "</table>\n";
}
// -----------------------------------------------------------------------------

function dpsql_fetch_all_keyed( $res )
// Constructs an array with a ($key => $value) pair for each row in $res:
// $key is the first element of the row, and $value is the rest of the row.
//
// (Typically, you'd use this when the first column in the result set satisfies
// a uniqueness constraint. If that's not the case, and two rows have the same
// value for the first element, the later one will overwrite the earlier one.
// However, this too can be a useful behaviour.)
{
    $arr = array();
    while( $row = mysqli_fetch_row($res) )
    {
        $key = $row[0];
        $value = array_slice( $row, 1 );
        $arr[$key] = $value;
    }
    return $arr;
}

// -----------------------------------------------------------------------------

function dpsql_fetch_columns( $res )
// Return an array of arrays, one for each column of the result-set.
{
    $columns = array();
    $num_cols = mysqli_num_fields($res);
    for ( $c = 0; $c < $num_cols; $c++ )
    {
        $columns[$c] = array();
    }
    while( $row = mysqli_fetch_row($res) )
    {
        for ( $c = 0; $c < $num_cols; $c++ )
        {
            $columns[$c][] = $row[$c];
        }
    }
    return $columns;
}

?>
