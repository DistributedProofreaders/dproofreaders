<?

function dpsql_dump_table( $table_name )
{
	dpsql_dump_query( "SELECT * FROM $table_name" );
}

function dpsql_dump_query( $query )
{
	$result = dpsql_query( $query );
	dpsql_dump_query_result( $result );
}

function dpsql_dump_ranked_query( $query )
{
	$result = dpsql_query( $query );
	dpsql_dump_query_result_with_rank( $result, 1);
}


function dpsql_query( $query )
{
	$result = mysql_query( $query );
	if (!$result)
	{
		print "The following mysql query:<br>\n";
		print $query . "<br>\n";
		print "raised the following error:<br>\n";
		print mysql_error(); "<br>\n";
		print "<br>\n";
	}
	return $result;
}

function dpsql_dump_query_result( $result )
{
	$n_cols = mysql_num_fields($result);

	print "<table border='1'>\n";

	{
		print "<tr>\n";
		for ($c = 0; $c < $n_cols; $c++ )
		{
			print "<th>";
			print mysql_field_name($result, $c);
			print "</th>\n";
		}
		print "</tr>\n";
	}

	while ( $row = mysql_fetch_row($result) )
	{
		print "<tr>\n";
		for ($c = 0; $c < $n_cols; $c++ )
		{
			print "<td>";
			print $row[$c];
			print "</td>\n";
		}
		print "</tr>\n";
	}

	print "</table>\n";
}

function dpsql_dump_query_result_with_rank( $result, $start_at )
{

	$n_cols = mysql_num_fields($result);
        $n_curr_row = $start_at;

	print "<table border='1'>\n";

	print "<tr>\n";
	print "<th>";
	print "Rank";
	print "</th>\n";

	for ($c = 0; $c < ($n_cols); $c++ )
	{
		print "<th>";
		print mysql_field_name($result, $c);
		print "</th>\n";
	}
	print "</tr>\n";

	while ( $row = mysql_fetch_row($result) )
	{
		print "<tr>\n";
		print "<td>";
                print $n_curr_row;
		print "</td>\n";

		for ($c = 0; $c < ($n_cols); $c++ )
		{
			print "<td>";
			print $row[$c];
			print "</td>\n";
		}
		print "</tr>\n";
                $n_curr_row++;
	}

	print "</table>\n";
}


?>
