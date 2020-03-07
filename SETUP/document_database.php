<?php

$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath . 'TableDocumentation.inc');

// First is the script name, the second is the operation name
$number_of_arguments = $argc - 2;

if ($argc < 2) {
    echo "No operation was chosen.\n";
    echo "Supported operations are: generate\n";

    exit(1);
}

// generate <directory_path> <table_name or all>
if ($argv[1] === 'generate') {
    if ($argc !== 4) {
        echo "Operation generate requires 2 arguments, $number_of_arguments were given.\n";
        echo "Supported syntax for generate command is 'generate <directory path> <table name or all>'.\n";

        exit(1);
    }

    $directory_path = $argv[2];
    $table_name = $argv[3];

    if (!is_dir($directory_path)) {
        echo "File path '$directory_path' does not exist or is not a directory.\n";

        exit(1);
    }

    if ($table_name === 'all') {
        generate_files_for_all_tables($directory_path);
    }
    else {
        generate_file_for_table($table_name, "$directory_path/$table_name.md", $table_name);
    }
}
else {
    echo "Invalid operation '{$argv[1]}'\n";
    echo "Supported operations are: generate\n";

    exit(1);
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

            $operation($table_name, "$directory_path/projectIDxxxxxxxxxxxxx.md", 'projectIDxxxxxxxxxxxxx');
        }
        else {
            $operation($table_name, "$directory_path/$table_name.md", $table_name);
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

    $table_documentation = new TableDocumentation($display_name, $columns);

    echo " - generating documentation for table '$table_name' to '$file_path'\n";

    file_put_contents($file_path, (string) $table_documentation);
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