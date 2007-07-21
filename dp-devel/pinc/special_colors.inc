<?php
include_once($relPath.'misc.inc');


// Returns the special color associated with this
// project, or null if no such color is specified.
//
// $book is supposed to be an associative array
// representing a  record from the
// projects table. At the moment it is 'enough'
// that the keys 'comments' and 'username' are
// existant.
function get_special_color_for_project($book) {

    $bgcolor = null;

    $special_code = $book['special_code'];

    // first three are (for now) special cases, the rest will be data-driven
    // from the SPECIAL_DAYS table

    // default Special colour (will be over-ridden by any specific matches below)
    if (!is_null($special_code) and strlen($special_code))
    {
        $bgcolor = '#FFFF66'; // fadedyellow
    }

    // very light blue for birthday books still available after the day
    if ( startswith( $special_code, 'Birthday' ) )
    {
        $bgcolor = '#CCFFFF';
    }

    // slightly richer blue for birthday books when today IS the day
    $bday = date('md');
    if ( startswith( $special_code, "Birthday $bday" ) )
    {
        $bgcolor = '#33CCFF';
    }

    $specs_result = mysql_query("
            SELECT spec_code, color FROM special_days
        ");

    // it'd be nice to make this static, or something, so it only was loaded once
    $specials_array = array();

    while ($s_row = mysql_fetch_assoc($specs_result)) {
        $col = $s_row['color'];
        $code = $s_row['spec_code'];
        $specials_array["$code"] = $col;
    }

    // if we recognise the special code, use the associated colour
    $book_special = $special_code;
    if (array_key_exists("$book_special", $specials_array)) {
        $bgcolor = "#".$specials_array["$book_special"];
    }

    return $bgcolor;
}


// outputs HTML showing the name of all SPECIAL DAYS
// backed by their characteristic colour,
// that have any projects within the set of projects
// specified by $projects_where_clause (WHERE keyword NOT needed)
// for use as a legend.

function echo_special_legend( $projects_where_clause) {

    $legend_text = _("Legend for Special Day Colors:");

    $currspecs_result = mysql_query("
            SELECT distinct special_code as spec FROM projects
            WHERE $projects_where_clause
        ");

    $curr_specs_array = array();

    while ($cs_row = mysql_fetch_assoc($currspecs_result)) {
        $curr_specs_array[] = $cs_row['spec'];
    }

    $specs_result = mysql_query("
            SELECT spec_code, display_name, color FROM special_days
            ORDER BY display_name ASC
        ");

    $common_array = array();

    // Strip results down to just the ones in use
    while ($sr_row = mysql_fetch_assoc($specs_result)) {
        if (in_array($sr_row['spec_code'], $curr_specs_array)) {
            $common_array[] = $sr_row;
        }
    }

    // Find out how many should be in each table column
    $specs_count = count($common_array);
    $max_rows = floor( $specs_count / 3 );
    $div_prefix = "<div style='border-style: none none solid none; border-color: black; border-width: 0px 0px 1px 0px; width: 100%; padding: 0px 0px 2px 1px; background-color: #";
    $div_midfix = "'>";
    $div_suffix = "</div>\n";
    $columns = array_fill(1,3,"<td valign='top'>" );
    $current_column = 1;

    $running_length = 0;
    foreach ($common_array as $this_day) {
        $columns[$current_column] = $columns[$current_column].$div_prefix.$this_day['color'].$div_midfix.$this_day['display_name'].$div_suffix ;
        $running_length += 1;
        if ($running_length > $max_rows ) {
           $current_column += 1;
           $running_length = 0;
        }
    }
    $columns[1] = $columns[1].$div_prefix."CCFFFF".$div_midfix._("Authors with recent birthdays").$div_suffix."</td>";
    $columns[2] = $columns[2].$div_prefix."33CCFF".$div_midfix._("Authors with birthdays today").$div_suffix."</td>";
    $columns[3] = $columns[3].$div_prefix."FFFF66".$div_midfix._("Other Special").$div_suffix."</td>";

    echo "<h3> $legend_text </h3>";
    echo "<table border='1' cellpadding='1' cellspacing='1' width='100%' summary='$legend_text'>";
    echo "<tr>";
    echo "$columns[1]$columns[2]$columns[3]";
    echo "</tr></table>";
}

?>
