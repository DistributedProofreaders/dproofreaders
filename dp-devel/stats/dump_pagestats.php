<?
$relPath='../pinc/';
include($relPath.'connect.inc');
$db_Connection = new dbConnect();

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

function dpsql_dump_table( $table_name )
{
	$result = dpsql_query( "SELECT * FROM $table_name" );
	dpsql_dump_query_result( $result );
}

function dpsql_dump_query_result( $result )
{
	$n_cols = mysql_num_fields($result);

	print "<table>\n";

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

dpsql_dump_table( "pagestats" );

?>
