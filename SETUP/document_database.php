<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

// ---------- All tables operation functions ----------

// ---------- Operation functions ----------

function generate_file_for_table(string $table_name, string $file_path, string $display_name) {
    $columns = query_columns_for_table($table_name);

    // Add hyper links to field names
    foreach ($columns as $index => $column_information) {
        $field = $column_information['Field'];

        $column_information['Name'] = $field; // Save field name before adding the hyper link for column definition
        $column_information['Field'] = "[$field](#$field)";
    }

    $lines = ['# ' . $display_name, ''];
    $lines = array_merge($lines, create_markdown_table($columns, [ 'Field', 'Type', 'Null', 'Key', 'Default', 'Extra' ]));

    // Add column definitions
    foreach ($columns as $index => $column_information) {
        $lines[] = '';
        $lines[] = '## ' . $column_information['Name'];
    }

    file_put_contents($file_path, implode("\n", $lines));
}

// ---------- Database functions ----------

/**
 * Runs a "DESCRIBE $table_name" query and returns the result.
 */
function query_columns_for_table(string $table_name): array {
    $result = mysqli_query(DPDatabase::get_connection(), "DESCRIBE $table_name");

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $rows;
}

/**
 * Runs a "SHOW TABLES" query and returns the result.
 */
function query_table_names_in_current_database(): array {
    $result = mysqli_query(DPDatabase::get_connection(), "SHOW TABLES");

    $rows = mysqli_fetch_all($result);
    mysqli_free_result($result);

    return $rows;
}

// ---------- Other helper functions ----------

/**
 * Creates a markdown table of the data with the only the specified columns shown and in the specified order.
 * Every cell is padded right with spaces to ensure every column is as wide as the widest cell in that column.
 *
 * @param array $contents the data that is shown within the table
 * @param array $table_columns the columns to display
 * @return array array of strings -- the generate markdown table
 */
function create_markdown_table(array $contents, array $table_columns): array {
    // For each column find the length of the longest value (used for padding in the table)
    $column_lengths = [];

    foreach ($contents as $row) {
        foreach ($row as $column => $value) {
            $column_lengths[$column] = max(strlen($value), array_get($column_lengths, $column, strlen($column)));
        }
    }

    $output_table = [];

    // Add headers
    $header = '|';
    $line_under_header = '|';

    foreach ($table_columns as $column) {
        $header .= str_pad($column, $column_lengths[$column]) . '|';
        $line_under_header .= str_repeat('-', $column_lengths[$column]) . '|';
    }

    $output_table[] = $header;
    $output_table[] = $line_under_header;

    // Add data
    foreach ($contents as $row) {
        $output_row = '|';

        foreach ($table_columns as $column) {
            $output_row .= str_pad(array_get($row, $column, ''), $column_lengths[$column]) . '|';
        }

        $output_table[] = $output_row;
    }

    return $output_table;
}

/**
 * Detects columns in the text by finding column descriptions. A column description contains the name of the column
 * prefixed by "## " (without quotes).
 *
 * For example, for a column named something_useful, its column description would look like this:
 *
 * ## something_useful
 *
 * @param array $lines an array of strings -- the lines of the text
 * @return array an array of strings containing the column names detected in text
 */
function detect_columns_in_text(array $lines): array {
    // array_values is used to reindex the array
    return array_values(preg_filter('/^## ([a-zA-Z0-9_]+)/', '$1', $lines));
}