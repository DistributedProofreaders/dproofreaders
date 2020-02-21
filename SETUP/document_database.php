<?php

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
 * Detects columns in the text by finding column descriptions. A column description
 * contains the name of the column prefixed by "## " (without quotes).
 *
 * For example, for a column named something_useful, its column description would
 * look like this:
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