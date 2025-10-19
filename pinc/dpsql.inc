<?php
define("DPSQL_NO_RANK", false);
define("DPSQL_SHOW_RANK", true);

function dpsql_dump_query($query, $start_at = 0, $show_rank = DPSQL_NO_RANK)
{
    $result = DPDatabase::query($query);
    dpsql_dump_query_result($result, $start_at, $show_rank);
}

function dpsql_dump_themed_query($query, $start_at = 0, $show_rank = DPSQL_NO_RANK)
{
    $result = DPDatabase::query($query);
    dpsql_dump_themed_query_result($result, $start_at, $show_rank);
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
        if (!$field_data) {
            throw new ServerError(DPDatabase::log_error());
        }
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

function dpsql_dump_themed_query_result($result, $start_at = 0, $show_rank = DPSQL_NO_RANK)
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
        if (!$field_data) {
            throw new ServerError(DPDatabase::log_error());
        }
        $type[$c] = $field_data->type;
        echo "<th>\n";
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
            echo '<td>';
            echo is_mysqli_field_type_int($type[$c]) ? number_format($row[$c]) : $row[$c];
            echo "</td>\n";
        }
        echo "</tr>\n";
        $n_curr_row++;
    }

    echo "</table>\n";
}

// -----------------------------------------------------------------------------

/**
 * Return an array of arrays, one for each column of the result-set.
 */
function dpsql_fetch_columns($res)
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

function is_mysqli_field_type_int(int $type): bool
{
    return $type == MYSQLI_TYPE_TINY ||
        $type == MYSQLI_TYPE_SHORT ||
        $type == MYSQLI_TYPE_LONG ||
        $type == MYSQLI_TYPE_LONGLONG ||
        $type == MYSQLI_TYPE_INT24;
}
