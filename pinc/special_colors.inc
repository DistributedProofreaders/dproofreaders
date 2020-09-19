<?php
include_once($relPath.'misc.inc');

// return a set of hard-coded special days keyed by spec_code
function load_common_special_days()
{
    return [
        "_BIRTHDAY_RECENT" => [
            'color' => "CCFFFF",
            'display_name' => "Authors with recent birthdays",
        ],
        "_BIRTHDAY_TODAY" => [
            'color' => "33CCFF",
            'display_name' => "Authors with birthdays today",
        ],
        "_OTHER_SPECIAL" => [
            'color' => "FFFF66",
            'display_name' => "Other Special",
        ],
    ];
}

function load_special_days()
{
    $specials_array = load_common_special_days();

    $sql = "
        SELECT spec_code, color, display_name
        FROM special_days
        ORDER BY display_name
    ";
    $specs_result = DPDatabase::query($sql);

    while ($row = mysqli_fetch_assoc($specs_result)) {
        $specials_array[$row['spec_code']] = [
            "color" => $row['color'],
            "display_name" => $row['display_name'],
        ];
    }

    return $specials_array;
}

// Returns the special color associated with this
// project, or null if no such color is specified.
//
// $book is supposed to be an associative array
// representing a  record from the
// projects table. At the moment it is 'enough'
// that the key 'special_code' exists.
function get_special_color_for_project($book)
{
    $special_code = $book['special_code'];

    static $specials_array = [];
    if(!$specials_array)
    {
        $specials_array = load_special_days();
    }

    $special_day = NULL;

    // default Special colour (will be over-ridden by any specific matches below)
    if (!is_null($special_code) and strlen($special_code))
    {
        $special_day = $specials_array['_OTHER_SPECIAL'];
    }

    // very light blue for birthday books still available after the day
    if ( startswith( $special_code, 'Birthday' ) )
    {
        $special_day = $specials_array['_BIRTHDAY_RECENT'];
    }

    // slightly richer blue for birthday books when today IS the day
    $bday = date('md');
    if ( startswith( $special_code, "Birthday $bday" ) )
    {
        $special_day = $specials_array['_BIRTHDAY_TODAY'];
    }

    // if we recognise the special code, use the associated colour
    if(isset($specials_array[$special_code]))
    {
        $special_day = $specials_array[$special_code];
    }

    return $bgcolor = "#".$special_day["color"];
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

    $specials_array = load_special_days();
    $day_array = [];

    while ($cs_row = mysqli_fetch_assoc($currspecs_result)) {
        if(isset($specials_array[$cs_row['spec']]))
            $day_array[] = $specials_array[$cs_row['spec']];
    }
    // sort day_array by display_name
    array_multisort(array_column($day_array, 'display_name'), SORT_ASC, $day_array);
    // now append the hard-coded special day names
    $day_array = array_merge($day_array, array_values(load_common_special_days()));

    $specs_count = count($day_array);
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
                $this_day = $day_array[$day_index++];
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
