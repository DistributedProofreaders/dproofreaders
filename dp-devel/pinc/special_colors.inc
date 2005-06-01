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
            WHERE 1 = 1
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

    echo _("Legend for Special Books:");
    echo "<br><br><b>";

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
            WHERE 1 = 1
            ORDER BY open_month desc, open_day desc
        ");

    $running_length = 0;
    $span_prefix = "<span style=\"background-color: #";
    $span_midfix = "\">";
    $span_suffix = "</span>&nbsp;";

    while ($sr_row = mysql_fetch_assoc($specs_result)) {
        if (in_array($sr_row['spec_code'], $curr_specs_array)) {
            echo $span_prefix.$sr_row['color'].$span_midfix.$sr_row['display_name'].$span_suffix ;
            $running_length = $running_length + strlen($sr_row['display_name']) + 1;
            if ($running_length > 60) {
               echo "<br>";
               $running_length = 0;
            }
        }
    }

    if ($running_length > 0) {
        echo "<br>";
    }

    echo $span_prefix."CCFFFF".$span_midfix._("Authors with recent birthdays").$span_suffix;
    echo $span_prefix."33CCFF".$span_midfix._("Authors with birthdays today").$span_suffix;
    echo "<br>";
    echo $span_prefix."FFFF66".$span_midfix._("Other Special").$span_suffix;
    echo "<br>";
    echo "</b>";

}

?>
