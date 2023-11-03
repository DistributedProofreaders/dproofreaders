<?php
include_once($relPath.'misc.inc');

/**
 * Return a set of hard-coded special days keyed by spec_code
 */
function load_common_special_days()
{
    return [
        "_BIRTHDAY_RECENT" => [
            'color' => "#CCFFFF",
            'display_name' => "Authors with recent birthdays",
            'symbol' => '🍰',
        ],
        "_BIRTHDAY_TODAY" => [
            'color' => "#33CCFF",
            'display_name' => "Authors with birthdays today",
            'symbol' => '🎂',
        ],
        "_OTHER_SPECIAL" => [
            'color' => "#FFFF66",
            'display_name' => "Other Special",
            'symbol' => '🎉',
        ],
    ];
}

function load_special_days($load_common = false)
{
    if ($load_common) {
        $specials_array = load_common_special_days();
    } else {
        $specials_array = [];
    }

    $sql = "
        SELECT *
        FROM special_days
        ORDER BY display_name
    ";
    $specs_result = DPDatabase::query($sql);

    while ($row = mysqli_fetch_assoc($specs_result)) {
        $specials_array[$row['spec_code']] = array_merge(
            // start with the row from the query
            $row,
            [
                // override color to make it a valid HTML value
                "color" => '#' . $row['color'],

                // provide a localized option label
                "option_label" => sprintf(
                    "%s (%s)",
                    $row["display_name"],
                    date_format(
                        date_create(
                            sprintf("2000-%02d-%02d", $row["open_month"], $row["open_day"])
                        ),
                        "j M"
                    ),
                ),
            ]
        );
    }

    return $specials_array;
}

function sort_special_days(&$special_days, $sort_by = "display_name")
{
    $display_name = array_column($special_days, "display_name");
    $display_name = array_map('strtolower', $display_name);

    if ($sort_by == "display_name") {
        array_multisort(
            $display_name,
            SORT_NATURAL,
            SORT_ASC,
            $special_days
        );
    } elseif ($sort_by == "open_month,open_day") {
        $open_month = array_column($special_days, "open_month");
        $open_day = array_column($special_days, "open_day");
        array_multisort(
            $open_month,
            SORT_NUMERIC,
            SORT_ASC,
            $open_day,
            SORT_NUMERIC,
            SORT_ASC,
            $display_name,
            SORT_NATURAL,
            SORT_ASC,
            $special_days
        );
    } else {
        throw Exception("Invalid value for \$sort_by");
    }
}

/**
 * Returns the special day array associated with this
 * project, or null if no such special day is specified.
 */
function get_special_day($special_code)
{
    static $specials_array = [];
    if (!$specials_array) {
        $specials_array = load_special_days(true);
    }

    $special_day = null;

    // default Special colour (will be over-ridden by any specific matches below)
    if (!is_null($special_code) and strlen($special_code)) {
        $special_day = $specials_array['_OTHER_SPECIAL'];
    }

    // very light blue for birthday books still available after the day
    if (startswith($special_code, 'Birthday')) {
        $special_day = $specials_array['_BIRTHDAY_RECENT'];
    }

    // slightly richer blue for birthday books when today IS the day
    $bday = date('md');
    if (startswith($special_code, "Birthday $bday")) {
        $special_day = $specials_array['_BIRTHDAY_TODAY'];
    }

    // if we recognise the special code, use the associated colour
    if (isset($specials_array[$special_code])) {
        $special_day = $specials_array[$special_code];
    }

    if ($special_day) {
        return $special_day;
    } else {
        return null;
    }
}


/**
 * Output HTML showing the name of all SPECIAL DAYS
 * backed by their characteristic colour,
 * that have any projects within the set of projects
 * specified by $projects_where_clause (WHERE keyword NOT needed)
 * for use as a legend.
 */
function echo_special_legend($projects_where_clause)
{
    global $code_url;

    $legend_text = _("Legend for Special Day Colors");

    $currspecs_result = DPDatabase::query("
        SELECT distinct special_code as spec
        FROM projects
        WHERE $projects_where_clause
    ");

    $specials_array = load_special_days(true);
    $day_array = [];

    while ($cs_row = mysqli_fetch_assoc($currspecs_result)) {
        if (isset($specials_array[$cs_row['spec']])) {
            $day_array[] = $specials_array[$cs_row['spec']];
        }
    }
    // sort day_array by display_name
    array_multisort(array_column($day_array, 'display_name'), SORT_ASC, $day_array);
    // now append the hard-coded special day names
    $day_array = array_merge($day_array, array_values(load_common_special_days()));

    $specs_count = count($day_array);
    echo "<h3>" . html_safe($legend_text) . "</h3>";
    echo "<p><a href='$code_url/tools/project_manager/show_specials.php'>" . _("View all Special Days") . "</a></p>";

    $num_columns = 4;
    $items_in_columns = round(count($day_array) / $num_columns);
    $column_index = 0;
    $columns = [];
    foreach ($day_array as $day_entry) {
        $columns[$column_index][] = $day_entry;
        if (count($columns[$column_index]) % $items_in_columns == 0) {
            $column_index += 1;
        }
    }
    echo "<table>\n";
    echo "<tr>";
    for (; $column_index > 0; $column_index--) {
        echo "<td class='top-align'>";
        echo "<table class='basic'>\n";
        $column = array_shift($columns);
        foreach ($column as $day_entry) {
            [$style, $contents] = get_special_day_cell_parts($day_entry, false);
            echo "<tr>";
            echo "<td style='$style'>$contents</td>";
            echo "<td>{$day_entry['display_name']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</td>";
    }
    echo "</tr>";
    echo "</table>";
}

/**
 * Given a special day, return building blocks to construct a cell for a row
 */
function get_special_day_cell_parts($special_day, $include_title = true)
{
    $background_color = str_replace("#", "", $special_day['color']);
    $text_color = pick_text_color_given_bgcolor($background_color, "white", "black");
    $style = "color: $text_color; background-color: #$background_color; " .
        "text-align: center; width: 1em;";

    // If the symbol is empty, return an em-space so that the browser
    // will show a tooltip for the cell.
    $contents = $special_day['symbol'] == '' ? '&emsp;' : $special_day['symbol'];
    if ($include_title) {
        $title = attr_safe($special_day['display_name']);
        $contents = "<span title='$title'>$contents</span>";
    }
    return [$style, $contents];
}

/**
 * Select a reasonable text color given a specific background color.
 *
 * From https://stackoverflow.com/questions/3942878/how-to-decide-font-color-in-white-or-black-depending-on-background-color
 */
function pick_text_color_given_bgcolor($bg_color, $light_color, $dark_color)
{
    $color = str_replace("#", "", $bg_color);

    $r = hexdec(substr($color, 0, 2)); // hexToR
    $g = hexdec(substr($color, 2, 4)); // hexToG
    $b = hexdec(substr($color, 4, 6)); // hexToB
    return ((($r * 0.299) + ($g * 0.587) + ($b * 0.114)) > 186) ? $dark_color : $light_color;
}
