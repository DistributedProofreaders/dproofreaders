<?
$relPath='../pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

//Declare all variables
$db_schema = "db_schema.sql";

//Create a string out of the database schema file
$db_schema = file($db_schema);
$sql_create_tables = "";
while ($lines = array_shift($db_schema)){ 
if (substr($lines,0,1) == "#" || substr($lines,0,1) == "\n") {
} else { 
$sql_create_tables = $sql_create_tables.$lines." ";
} }

//Remove all line breaks
$sql_create_tables = str_replace("\r\n","",$sql_create_tables);

//Explode the string into sub-strings for each table
$array = explode(';',$sql_create_tables);

//Loop through the array/substrings and add them to the database
while ($lines = array_shift($array)) {
$result = mysql_query("$lines");
echo mysql_error() . "\n";
}

echo "Tables have been created.";
?> 
