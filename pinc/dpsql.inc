<?php
define("DPSQL_NO_RANK", false);
define("DPSQL_SHOW_RANK", true);

define("DPSQL_NO_RIGHT_ALIGN_INTS", false);
define("DPSQL_RIGHT_ALIGN_INTS", true);

function dpsql_dump_table($table_name)
{
    dpsql_dump_query("SELECT * FROM $table_name");
}

function dpsql_dump_query($query, $start_at = 0, $show_rank = DPSQL_NO_RANK)
{
    $result = dpsql_query($query);
    dpsql_dump_query_result($result, $start_at, $show_rank);
}

function dpsql_dump_themed_query($query, $start_at = 0, $show_rank = DPSQL_NO_RANK, $ralign_ints = DPSQL_RIGHT_ALIGN_INTS)
{
    $result = dpsql_query($query);
    dpsql_dump_themed_query_result($result, $start_at, $show_rank, $ralign_ints);
}

function dpsql_query($query)
{
    $result = mysqli_query(DPDatabase::get_connection(), $query);
    if (!$result) {
        echo DPDatabase::log_error();
    }
    return $result;
}

function dpsql_dump_query_result($result, $start_at = 0, $show_rank = DPSQL_NO_RANK)
{
    $n_cols = mysqli_num_fields($result);
    $n_curr_row = $start_at;

    echo "<table class='basic striped'>\n";

    echo "<tr>\n";
    if ($show_rank) {
        echo "<th>";
        echo _("Rank");
        echo "</th>\n";
    }

    for ($c = 0; $c < ($n_cols); $c++) {
        echo "<th>";
        $field_data = mysqli_fetch_field_direct($result, $c);
        echo $field_data->name;
        echo "</th>\n";
    }
    echo "</tr>\n";

    while ($row = mysqli_fetch_row($result)) {
        echo "<tr>\n";
        if ($show_rank) {
            echo "<td>$n_curr_row</td>\n";
        }

        for ($c = 0; $c < ($n_cols); $c++) {
            $val = is_null($row[$c]) ? 'NULL' : $row[$c];
            echo "<td>$val</td>\n";
        }
        echo "</tr>\n";
        $n_curr_row++;
    }

    echo "</table>\n";
}

function dpsql_dump_themed_query_result($result, $start_at = 0, $show_rank = DPSQL_NO_RANK, $ralign_ints = DPSQL_RIGHT_ALIGN_INTS)
{
    $n_query_cols = mysqli_num_fields($result);
    $n_curr_row = $start_at;

    echo "<table class='themed theme_striped stats'>\n";

    $n_cols = $show_rank ? $n_query_cols + 1 : $n_query_cols;
    $perc = 100 / $n_cols;
    for ($c = 0; $c < $n_cols; $c++) {
        echo "\n<col style='width: {$perc}%;'>";
    }

    echo "<tr>\n";
    if ($show_rank) {
        echo "<th>";
        echo _("Rank");
        echo "</th>\n";
    }

    $type = [];
    for ($c = 0; $c < $n_query_cols; $c++) {
        $field_data = mysqli_fetch_field_direct($result, $c);
        $type[$c] = $field_data->type;
        $align = ($type[$c] == 'int' && $ralign_ints) ? " style='text-align: right;'" : '';
        echo "<th$align>\n";
        echo $field_data->name;
        echo "</th>\n";
    }
    echo "</tr>\n";

    while ($row = mysqli_fetch_row($result)) {
        echo "<tr>";
        if ($show_rank) {
            echo "<td>";
            echo number_format($n_curr_row);
            echo "</td>\n";
        }

        for ($c = 0; $c < $n_query_cols; $c++) {
            echo '<td' . (($type[$c] == 'int' && $ralign_ints) ? " style='text-align: right;'" : '') . '>';
            echo ($type[$c] == 'int') ? number_format($row[$c]) : $row[$c];
            echo "</td>\n";
        }
        echo "</tr>\n";
        $n_curr_row++;
    }

    echo "</table>\n";
}
// -----------------------------------------------------------------------------

function dpsql_fetch_all_keyed($res)
// Constructs an array with a ($key => $value) pair for each row in $res:
// $key is the first element of the row, and $value is the rest of the row.
//
// (Typically, you'd use this when the first column in the result set satisfies
// a uniqueness constraint. If that's not the case, and two rows have the same
// value for the first element, the later one will overwrite the earlier one.
// However, this too can be a useful behaviour.)
{
    $arr = [];
    while ($row = mysqli_fetch_row($res)) {
        $key = $row[0];
        $value = array_slice($row, 1);
        $arr[$key] = $value;
    }
    return $arr;
}

// -----------------------------------------------------------------------------

function dpsql_fetch_columns($res)
// Return an array of arrays, one for each column of the result-set.
{
    $columns = [];
    $num_cols = mysqli_num_fields($res);
    for ($c = 0; $c < $num_cols; $c++) {
        $columns[$c] = [];
    }
    while ($row = mysqli_fetch_row($res)) {
        for ($c = 0; $c < $num_cols; $c++) {
            $columns[$c][] = $row[$c];
        }
    }
    return $columns;
}
