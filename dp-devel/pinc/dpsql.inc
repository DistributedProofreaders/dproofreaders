<?
if(isset($userP['i_theme']) && $userP['i_theme'] != '' ) {
                $theme_name = $userP['i_theme'];
        } else {
                $theme_name = "project_gutenberg";
}
include_once($relPath."templates/$theme_name/theme.tpl");
$theme = ${$theme_name};


function dpsql_dump_table( $table_name )
{
	dpsql_dump_query( "SELECT * FROM $table_name" );
}

function dpsql_dump_query( $query )
{
	$result = dpsql_query( $query );
	dpsql_dump_query_result( $result );
}

function dpsql_dump_themed_query( $query )
{
	$result = dpsql_query( $query );
	dpsql_dump_themed_query_result( $result );
}

function dpsql_dump_ranked_query( $query )
{
	$result = dpsql_query( $query );
	dpsql_dump_query_result_with_rank( $result, 1);
}

function dpsql_dump_themed_ranked_query( $query )
{
	$result = dpsql_query( $query );
	dpsql_dump_query_themed_result_with_rank( $result, 1);
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
			$val = $row[$c];
			if (is_null($val)) { $val = 'NULL'; }
			print "<td>";
			print $val;
			print "</td>\n";
		}
		print "</tr>\n";
	}

	print "</table>\n";
}

function dpsql_dump_themed_query_result( $result )
{
        $theme_name = $GLOBALS['theme_name'];
        $theme = $GLOBALS['theme'];

	$n_cols = mysql_num_fields($result);

	print "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'>\n";
	print "<tr><td>\n";
	print "<table border='0' cellspacing='0' cellpadding='2' width='100%' align='center'>";
	{
		print "<tr>\n";
		for ($c = 0; $c < $n_cols; $c++ )
		{
			print "<td bgcolor='" . $theme['color_headerbar_bg'] . "'>";
			print "<font color='".$theme['color_headerbar_font']."'>";
			print mysql_field_name($result, $c);
			print "</font></td>\n";
		}
		print "</tr>\n";
	}

	while ( $row = mysql_fetch_row($result) )
	{
		print "<tr bgcolor='" . $theme['color_navbar_bg'] . "'>\n";
		for ($c = 0; $c < $n_cols; $c++ )
		{
			print "<td>";
			print $row[$c];
			print "</td>\n";
		}
		print "</tr>\n";
	}

	print "</table>\n";
	print "</td></tr></table>\n";
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

function dpsql_dump_query_themed_result_with_rank( $result, $start_at )
{
        $theme_name = $GLOBALS['theme_name'];
        $theme = $GLOBALS['theme'];

	$n_cols = mysql_num_fields($result);
        $n_curr_row = $start_at;

	print "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'>\n";
	print "<tr><td>\n";
	print "<table border='0' cellspacing='0' cellpadding='2' width='100%' align='center'>";

	print "<tr>\n";
	print "<td bgcolor='" . $theme['color_headerbar_bg'] . "'>\n";
	print "<font color='".$theme['color_headerbar_font']."'>";
	print "Rank";
	print "</font></td>\n";

	for ($c = 0; $c < ($n_cols); $c++ )
	{
		print "<td bgcolor='" . $theme['color_headerbar_bg'] . "'>\n";
		print "<font color='".$theme['color_headerbar_font']."'>";
		print mysql_field_name($result, $c);
		print "</font></td>\n";
	}
	print "</tr>\n";

	while ( $row = mysql_fetch_row($result) )
	{
		print "<tr bgcolor='" . $theme['color_navbar_bg'] . "'>\n";
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
	print "</td></tr></table>\n";
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
    while( $row = mysql_fetch_row($res) )
    {
        $key = $row[0];
        $value = array_slice( $row, 1 );
        $arr[$key] = $value;
    }
    return $arr;
}

?>
