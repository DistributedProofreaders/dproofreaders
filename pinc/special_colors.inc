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

    static $specials_array = [];
    if(!$specials_array)
    {
        $sql = "
            SELECT spec_code, color
            FROM special_days
        ";
        $specs_result = DPDatabase::query($sql);

        while ($row = mysqli_fetch_assoc($specs_result)) {
            $specials_array[$row['spec_code']] = $row['color'];
        }
    }

    // if we recognise the special code, use the associated colour
    if(isset($specials_array[$special_code]))
    {
        $bgcolor = "#".$specials_array[$special_code];
    }

    return $bgcolor;
}


// outputs HTML showing the name of all SPECIAL DAYS
// backed by their characteristic colour,
// that have any projects within the set of projects
// specified by $projects_where_clause (WHERE keyword NOT needed)
// for use as a legend.

function echo_special_legend( $projects_where_clause)
{
    global $code_url;

    $legend_text = _("Legend for Special Day Colors");

    $currspecs_result = mysqli_query(DPDatabase::get_connection(), "
            SELECT distinct special_code as spec FROM projects
            WHERE $projects_where_clause
        ");

    $curr_specs_array = array();

    while ($cs_row = mysqli_fetch_assoc($currspecs_result)) {
        $curr_specs_array[] = $cs_row['spec'];
    }

    $specs_result = mysqli_query(DPDatabase::get_connection(), "
            SELECT spec_code, display_name, color FROM special_days
            ORDER BY display_name ASC
        ");

    $common_array = array();

    // Strip results down to just the ones in use
    while ($sr_row = mysqli_fetch_assoc($specs_result)) {
        if (in_array($sr_row['spec_code'], $curr_specs_array)) {
            $common_array[] = $sr_row;
        }
    }
    $common_array[] = array('color' => "CCFFFF", 'display_name' => "Authors with recent birthdays");
    $common_array[] = array('color' => "33CCFF", 'display_name' => "Authors with birthdays today");
    $common_array[] = array('color' => "FFFF66", 'display_name' => "Other Special");

    $specs_count = count($common_array);
    echo "<h3><a href='$code_url/tools/project_manager/show_specials.php'>$legend_text</a></h3>";
    echo "<table class='basic'>\n";
    $day_index = 0;
    $table_columns = min($specs_count, 4);
    while($day_index < $specs_count)
    {
        echo "<tr>\n";
        $column_index = 0;
        while($column_index++ < $table_columns)
        {
            if($day_index < $specs_count)
            {
                $this_day = $common_array[$day_index++];
                echo "<td style='background-color:#{$this_day['color']}'>{$this_day['display_name']}</td>\n";
            }
            else
                echo "<td></td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
}

?>
