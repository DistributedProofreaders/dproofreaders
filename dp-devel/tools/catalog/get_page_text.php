<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc');

require_login();

$projectid  = validate_projectID('project', $_GET['project']);
$page_image = validate_page_image_filename('page_image', @$_GET['page_image']);
$which      = get_enumerated_param($_GET, 'which', null,
    array('round1', 'round2', 'round3', 'round4', 'round5'));

$column = $which . '_text';

$query = "SELECT $column FROM $projectid WHERE image='$page_image'";
$rows = mysql_query($query) or die(mysql_error());

if ( mysql_numrows($rows) == 0 )
{
    echo sprintf(_("No such image %s"), htmlspecialchars($page_image)) . "<br>\n";
}
else
{
    $row = mysql_fetch_array( $rows );
    header("Content-type: text/plain; charset=$charset");
    // SENDING PAGE-TEXT TO USER
    // but it's a text/plain document, so we don't need to encode anything.
    echo $row[$column];
}

// vim: sw=4 ts=4 expandtab
