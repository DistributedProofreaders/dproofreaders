<?
//Declare all variables
$db_schema = "db_schema.sql";
$db_host = "localhost";
$db_user = "";
$db_pass = "";
$db_name = "dproofreaders";

//Connect to the sql database
$db = mysql_connect($db_host,$db_user,$db_pass);

//Create the new database
$createdb = mysql_query("CREATE DATABASE $db_name CHARACTER SET UTF8");

//Select the newly created database
mysql_select_db($db_name,$db) or die ("Unable to select database.");

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

//Let the user know the db was created
echo $db_name." has been created with the default structure and data.";
?> 
