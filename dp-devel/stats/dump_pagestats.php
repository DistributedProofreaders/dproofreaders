<?
$relPath='../pinc/';
include($relPath.'connect.inc');
$db_Connection = new dbConnect();

function dump_table( $table_name )
{
	$result = mysql_query( "SELECT * FROM $table_name" );
	dump_query_result( $result );
}

function dump_query_result( $result )
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

dump_table( "pagestats" );

?>
