<?php
if(isset($userP['i_theme']) && $userP['i_theme'] != '' ) {
                $theme_name = $userP['i_theme'];
        } else {
                $theme_name = "project_gutenberg";
}
include_once($relPath."templates/$theme_name/theme.tpl");
$theme = ${$theme_name};

define("DPSQL_NO_RANK",   False);
define("DPSQL_SHOW_RANK", True);

define("DPSQL_NO_RIGHT_ALIGN_INTS", False);
define("DPSQL_RIGHT_ALIGN_INTS",    True);

function dpsql_dump_table( $table_name )
{
	dpsql_dump_query( "SELECT * FROM $table_name" );
}

function dpsql_dump_query($query, $start_at = 0, $show_rank = DPSQL_NO_RANK)
{
	$result = dpsql_query( $query );
	dpsql_dump_query_result($result, $show_rank, $start_at);
}

function dpsql_dump_themed_query($query, $start_at = 0, $show_rank = DPSQL_NO_RANK, $ralign_ints = DPSQL_RIGHT_ALIGN_INTS)
{
	var_dump($ralign_ints);
	$result = dpsql_query( $query );
	dpsql_dump_themed_query_result($result, $start_at, $show_rank, $ralign_ints);
}

function dpsql_query( $query )
{
	$result = mysql_query( $query );
	if (!$result)
	{
		print "The following mysql query:<br>\n";
		print $query . "<br>\n";
		print "raised the following error:<br>\n";
		print mysql_error() . "<br>\n";
		print "<br>\n";
	}
	return $result;
}

function dpsql_dump_query_result($result, $start_at, $show_rank)
{

	$n_cols = mysql_num_fields($result);
	$n_curr_row = $start_at;

	print "<table border='1'>\n";

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
		print mysql_field_name($result, $c);
		print "</th>\n";
	}
	print "</tr>\n";

	while ( $row = mysql_fetch_row($result) )
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

function dpsql_dump_themed_query_result($result, $start_at, $show_rank, $ralign_ints)
{
	$theme = $GLOBALS['theme'];
	var_dump($ralign_ints);
	$n_query_cols = mysql_num_fields($result);
	$n_curr_row = $start_at;

	print "<table border='1' cellspacing='0' cellpadding='0' style='border: 1px solid #111; border-collapse: collapse;' width='99%'>\n";
	
	print "<tr><td>\n";
	print "<table border='0' cellspacing='0' cellpadding='2' width='100%' align='center' class='stats'>";

	$n_cols = $show_rank ? $n_query_cols + 1 : $n_query_cols;
	$perc = 100 / $n_cols;
	for ($c = 0; $c < $n_cols; $c++ )
	{
		print "\n<col width='{$perc}%'>";
	}

	print "<tr>\n";
	if ($show_rank)
	{
		print "<th bgcolor='" . $theme['color_headerbar_bg'] . "'>\n";
		print "<font color='".$theme['color_headerbar_font'] . "'>";
		print _("Rank");
		print "</font></th>\n";
	}

	$type = Array();
	for ($c = 0; $c < $n_query_cols; $c++ )
	{
		$type[$c] = mysql_field_type($result, $c);
		$align = ($type[$c] == 'int' && $ralign_ints) ? " align='right'" : '';
		print "<th bgcolor='" . $theme['color_headerbar_bg'] . "'$align>\n";
		print "<font color='".$theme['color_headerbar_font']."'>";
		print mysql_field_name($result, $c);
		$type[$c] = mysql_field_type($result, $c);
		print "</font></th>\n";
	}
	print "</tr>\n";

	$rownum = 0;
	$listing_bgcolors = array($theme['color_listing_bg_1'], $theme['color_listing_bg_2']);
	while ( $row = mysql_fetch_row($result) )
	{
		print "<tr bgcolor='" . $listing_bgcolors[$rownum % 2] . "'>\n";
		if ($show_rank)
		{
			print "<td>";
			print number_format($n_curr_row);
			print "</td>\n";
		}

		for ($c = 0; $c < $n_query_cols; $c++)
		{
			print '<td' . (($type[$c] == 'int' && $ralign_ints) ? ' align="right"' : '') . '>';
			print ($type[$c] == 'int') ? number_format($row[$c]) : $row[$c];
			print "</td>\n";
		}
		print "</tr>\n";
		$n_curr_row++;
		$rownum++;
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

// -----------------------------------------------------------------------------

function dpsql_fetch_columns( $res )
// Return an array of arrays, one for each column of the result-set.
{
    $columns = array();
    $num_cols = mysql_num_fields($res);
    for ( $c = 0; $c < $num_cols; $c++ )
    {
        $columns[$c] = array();
    }
    while( $row = mysql_fetch_row($res) )
    {
        for ( $c = 0; $c < $num_cols; $c++ )
        {
            $columns[$c][] = $row[$c];
        }
    }
    return $columns;
}

?>
