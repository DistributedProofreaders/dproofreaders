<?php

// If this is not a PHPUnit test, include the dependencies, otherwise the bootstrap file will handle them
if (!defined('PHPUNIT')) {
    $relPath='../pinc/';
    include_once($relPath.'base.inc');
    include_once($relPath.'misc.inc');

    // Do not define the functions if we are in a PHPUnit test.
    // The test will define a fake function suitable for testing.

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
     * Saves the contents to the specified file path.
     *
     * Wrapper around file_put_contents which allows for easier mocking and unit testing.
     */
    function write_to_file(string $file_path, string $contents) {
        file_put_contents($file_path, $contents);
    }
}

// ---------- All tables operation functions ----------

/**
 * Generic function which runs the operation function for each table in the database.
 *
 * Tables with 'projectID' prefix are handled in a special way to prevent spam.
 * Instead of running the operation for each of them, the operation is run for only
 * the first one and the $file_name and $display_name are set to '$projectid'.
 *
 * @param string $directory_path the directory in which to place the generated files
 * @param callable $operation the operation to perform for each table
 */
function run_operation_for_all_tables(string $directory_path, callable $operation) {
    $table_names = query_table_names_in_current_database();
    $was_projectid_table_generated = false;

    foreach ($table_names as $row) {
        $table_name = $row[0];

        if (startswith($table_name, 'projectID')) {
            if ($was_projectid_table_generated) continue;

            $was_projectid_table_generated = true;

            $operation($table_name, $directory_path . '/$projectid.md', '$projectid');
        }
        else {
            $operation($table_name, $directory_path . '/' . $table_name . '.md', $table_name);
        }
    }
}

/**
 * Generates a markdown file documenting the table for each table in the database.
 *
 * @param string $directory_path the directory in which to place the generated files
 */
function generate_files_for_all_tables(string $directory_path) {
    run_operation_for_all_tables($directory_path, 'generate_file_for_table');
}

// ---------- Single table operation functions ----------

/**
 * Generates a markdown file documenting the table.
 *
 * @param string $table_name the name of the table in the database, used for looking up columns
 * @param string $file_path where to generate the file
 * @param string $display_name the title of the markdown file
 */
function generate_file_for_table(string $table_name, string $file_path, string $display_name) {
    $columns = query_columns_for_table($table_name);

    // Add hyper links to field names
    foreach ($columns as $index => $column_information) {
        $field = $column_information['Field'];

        $columns[$index]['Name'] = $field; // Save field name before adding the hyper link for column definition
        $columns[$index]['Field'] = "[$field](#$field)";
    }

    $lines = ['# ' . $display_name, ''];
    $lines = array_merge($lines, create_markdown_table($columns, [ 'Field', 'Type', 'Null', 'Key', 'Default', 'Extra' ]));

    // Add column definitions
    foreach ($columns as $index => $column_information) {
        $lines[] = '';
        $lines[] = '## ' . $column_information['Name'];
    }

    $lines[] = '';

    write_to_file($file_path, implode("\n", $lines));
}

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