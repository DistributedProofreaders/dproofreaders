<?php
$relPath="../../pinc/";
include($relPath.'dp_main.inc');
include($relPath.'page_states.inc');
include($relPath.'f_project_states.inc');

$projectid  = $_GET['project'];
$page_image = $_GET['page_image'];
$which      = $_GET['which'];

$column = $which . '_text';

$query = "SELECT $column FROM $projectid WHERE image='$page_image'";
$rows = mysql_query($query) or die(mysql_error());

if ( mysql_numrows($rows) == 0 )
{
    echo "no such image $page_image<br>\n";
}
else
{
    $row = mysql_fetch_array( $rows );
    echo "<pre>\n";
    echo $row[$column];
    echo "\n";
    echo "</pre>\n";
}

?>
