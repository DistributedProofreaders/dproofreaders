<?php
$relPath="../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');

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
    header("Content-type: text/plain; charset=$charset");
    // SENDING PAGE-TEXT TO USER
    // but it's a text/plain document, so we don't need to encode anything.
    echo $row[$column];
}

?>
