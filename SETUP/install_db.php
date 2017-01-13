<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
// connect.inc (via base.inc) include()s udb_user.php but only in a local scope, so we
// need to include it again to place $db_name in this scope.
include($relPath.'udb_user.php'); // $db_name

mysqli_query(DPDatabase::get_connection(), "
    CREATE DATABASE IF NOT EXISTS $db_name
") or die(mysqli_error(DPDatabase::get_connection()));
mysqli_query(DPDatabase::get_connection(), "
    USE $db_name
") or die(mysqli_error(DPDatabase::get_connection()));

// Declare all variables
$db_schema = "db_schema.sql";

// Create a string out of the database schema file
$db_schema = file($db_schema);
$sql_create_tables = "";
while ($lines = array_shift($db_schema)) { 
    if (substr($lines,0,1) == "#" || substr($lines,0,1) == "\n") {
        // skip comment and blank lines
    } else { 
        $sql_create_tables = $sql_create_tables.$lines." ";
    }
}

// Remove all line breaks
$sql_create_tables = str_replace("\r\n","",$sql_create_tables);

// Some older versions of MySQL (sometime before 5.0) don't recognize
// the 'DEFAULT CHARSET' syntax. If you set $strip_default_charset to
// TRUE, we'll strip it out when creating the tables.
$strip_default_charset = FALSE;
if ($strip_default_charset)
{
    $sql_create_tables = str_replace("DEFAULT CHARSET=latin1", "", $sql_create_tables);
}

// Explode the string into sub-strings for each table
$array = explode(';',$sql_create_tables);

// Loop through the array/substrings and add them to the database
while ($lines = array_shift($array)) {
    $result = mysqli_query(DPDatabase::get_connection(), "$lines");
    echo mysqli_error(DPDatabase::get_connection()) . "\n";
}

echo "Tables have been created.";
?> 
