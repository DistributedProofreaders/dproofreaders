<?php

$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath . 'TableDocumentation.inc');

// First is the script name, the second is the operation name
$number_of_arguments = $argc - 2;

if ($argc < 2) {
    echo "No operation was chosen.\n";
    echo "Supported operations are: generate, verify\n";

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
        run_operation_for_all_tables($directory_path, 'generate_file_for_table');
    }
    else {
        generate_file_for_table($table_name, "$directory_path/$table_name.md", $table_name);
    }
}
// verify <directory_path> <table_name or all>
elseif ($argv[1] === 'verify') {
    if ($argc !== 4) {
        echo "Operation generate requires 2 arguments, $number_of_arguments were given.\n";
        echo "Supported syntax for verify command is 'verify <directory path> <table name or all>'.\n";

        exit(1);
    }

    $directory_path = $argv[2];
    $table_name = $argv[3];

    if (!is_dir($directory_path)) {
        echo "File path '$directory_path' does not exist or is not a directory.\n";

        exit(1);
    }

    if ($table_name === 'all') {
        run_operation_for_all_tables($directory_path, 'verify_file_for_table');
    }
    else {
        verify_file_for_table($table_name, "$directory_path/$table_name.md", $table_name);
    }
}
else {
    echo "Invalid operation '{$argv[1]}'\n";
    echo "Supported operations are: generate, verify\n";

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

/**
 * Verifies the markdown documentation file is up to date. Verification has three steps:
 *
 * 1. The file must exist.
 * 2. The table in the file must be equal to the table generated from the database information.
 * 3. The file must not contain column definitions for columns which do not exist in the table any more,
 *     and it must contain a column definition for every column in the table.
 *
 * If any step fails, the function calls exit(1) to terminate the program.
 *
 * @param string $table_name the name of the table in the database, used for looking up columns
 * @param string $file_path which file to verify
 * @param string $display_name the title of the markdown file -- is not used
 */
function verify_file_for_table(string $table_name, string $file_path, string $display_name) {
    echo " - verifying documentation for table '$table_name' in '$file_path' is up to date\n";

    if (!is_file($file_path)) {
        echo "  - documentation file '$file_path' does not exist\n";
        exit(1);
    }

    $columns = query_columns_for_table($table_name);

    $table_documentation = new TableDocumentation($display_name, $columns);

    $documentation_text = file_get_contents($file_path);
    $lines = explode("\n", $documentation_text);

    // Check field table
    $table_in_file = implode("\n", TableDocumentation::detect_first_table_in_text($lines));
    $generated_table = $table_documentation->generate_documentation_table();

    if ($table_in_file !== $generated_table) {
        echo "  - generated table is not equal to the table in the documentation file\n";
        echo "  - generated table: \n";
        echo "'$generated_table'\n";
        echo "  - table from the documentation file: \n";
        echo "'$table_in_file'\n";
        exit(1);
    }

    // Check field definitions
    $column_definitions_in_file = TableDocumentation::detect_columns_in_text($lines);
    $column_definitions_from_table = TableDocumentation::detect_columns_in_text(explode("\n", (string) $table_documentation));

    $added_columns = array_diff($column_definitions_from_table, $column_definitions_in_file);
    $deleted_columns = array_diff($column_definitions_in_file, $column_definitions_from_table);

    if (count($added_columns) !== 0) {
        foreach ($added_columns as $index => $column) {
            echo "  - missing column definition for column '$column'\n";
        }

        exit(1);
    }
    if (count($deleted_columns) !== 0) {
        foreach ($deleted_columns as $index => $column) {
            echo "  - redundant column definition for column '$column' which does not exist in table\n";
        }

        exit(1);
    }
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