#!/usr/bin/env php
<?php

$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'TableDocumentation.inc');
include_once($relPath.'DPage.inc'); // project_allow_pages()

$OPERATIONS = [
    'generate' => 'generate_file_for_table',
    'verify' => 'verify_file_for_table',
    'update' => 'update_file_for_table'
];

$DEFAULT_DIRECTORY_PATH = realpath("$relPath/../SETUP/dbdocs/");

$PROJECT_TEMPLATE_TABLE = 'projectIDxxxxxxxxxxxxx';

$ADDITIONAL_TABLE_NAMES = [ $PROJECT_TEMPLATE_TABLE ];

list($operation_name, $table_name, $directory_path) =
    get_arguments(array_keys($OPERATIONS), $DEFAULT_DIRECTORY_PATH);

prepare_project_template_table();

if ($table_name === 'all') {
    run_operation_for_all_tables($directory_path, $OPERATIONS[$operation_name]);
}
else {
    $OPERATIONS[$operation_name]($table_name, "$directory_path/$table_name.md", $table_name);
}


/**
 * Return a validated set of command line arguments
 *
 * @param array $operations the set of valid operations
 * @param string $default_dir_path the default directory path
 */
function get_arguments(array $operations, string $default_dir_path): array {
    global $argv;

    try {
        $program_name = array_shift($argv);
        if(!$argv)
            throw new InvalidArgumentException("No operation specified.");

        $operation = array_shift($argv);
        if(!in_array($operation, $operations))
            throw new InvalidArgumentException("Invalid operation.");

        if(!$argv)
            throw new InvalidArgumentException("No table name, or 'all', specified.");
        $table = array_shift($argv);

        if(!$argv)
            $directory = $default_dir_path;
        else
            $directory = array_shift($argv);
        if(!is_dir($directory))
            throw new InvalidArgumentException("$directory does not exist or is not a directory.");

        if($argv)
            throw new InvalidArgumentException("Found unexpected arguments.");
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo "Usage: $program_name operation table [directory]\n";
        echo "    operation - operation to perform, one of: " . implode(", ", $operations) . "\n";
        echo "    table     - table to operate against, or 'all' for all tables\n";
        echo "    directory - directory to use; default: $default_dir_path\n";
        echo "\n";
        exit(1);
    }

    return [ $operation, $table, $directory ];
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
    $table_names = get_table_names_to_document();

    foreach ($table_names as $table_name) {
        $operation($table_name, "$directory_path/$table_name.md", $table_name);
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
    $column_definitions_from_table = $table_documentation->get_column_names();

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

/**
 * Updates the markdown documentation file. Updating has two steps:
 *
 * 1. The documentation table is regenerated from the database information.
 * 2. Missing column definitions are added, redundant column definitions for deleted columns are deleted.
 *
 * After update the file should pass verification.
 *
 * @param string $table_name the name of the table in the database, used for looking up columns
 * @param string $file_path which file to verify
 * @param string $display_name the title of the markdown file -- is not used
 */
function update_file_for_table(string $table_name, string $file_path, string $display_name) {
    echo " - updating documentation for table '$table_name' in '$file_path'\n";

    $columns = query_columns_for_table($table_name);

    $table_documentation = new TableDocumentation($display_name, $columns);

    $documentation_text = file_get_contents($file_path);
    $lines = explode("\n", $documentation_text);

    // Regenerate field table
    $lines = TableDocumentation::replace_first_table($lines, [$table_documentation->generate_documentation_table()]);

    // Add/remove field definitions
    $column_definitions_from_table = $table_documentation->get_column_names();

    $lines = TableDocumentation::update_column_definitions($lines, $column_definitions_from_table);

    file_put_contents($file_path, implode("\n", $lines));
}

// ---------- Database functions ----------

/**
 * Runs a "DESCRIBE $table_name" query and returns the result.
 */
function query_columns_for_table(string $table_name): array {
    $result = mysqli_query(DPDatabase::get_connection(), "DESCRIBE $table_name");

    if (!$result) {
        echo "Failed to fetch column information for table '$table_name'.\n";
        exit(1);
    }

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $rows;
}

/**
 * Returns the names of the tables that should be documented.
 */
function get_table_names_to_document(): array {
    global $ADDITIONAL_TABLE_NAMES;

    $table_names = [];

    foreach (explode("\n", file_get_contents("./db_schema.sql")) as $line)
    {
        if (preg_match('/create table `(\w+)`/i', $line, $matches))
        {
            $table_names[] = $matches[1];
        }
    }

    return array_merge($table_names, $ADDITIONAL_TABLE_NAMES);
}

/**
 * Prepares the projectIDxxxxxxxxxxxxx table
 */
function prepare_project_template_table() {
    global $PROJECT_TEMPLATE_TABLE;

    // attempt to delete an existing table but continue of it doesn't exist
    $sql = "DROP TABLE $PROJECT_TEMPLATE_TABLE";
    $result = mysqli_query(DPDatabase::get_connection(), $sql);

    // now create a new one
    project_allow_pages($PROJECT_TEMPLATE_TABLE);
}
