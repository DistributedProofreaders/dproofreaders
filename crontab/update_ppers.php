<?php
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

$result = mysqli_query(DPDatabase::get_connection(), "SELECT DISTINCT(postproofer) FROM projects WHERE postproofer is not null");
while (list($postproofer) = mysqli_fetch_row($result))
{
    mysqli_query(DPDatabase::get_connection(), "UPDATE users SET postprocessor = 'yes' WHERE username = '$postproofer'");
    if ( mysqli_affected_rows(DPDatabase::get_connection()) > 0 )
    {
        echo "Added: $postproofer<br>";
    }
}

// vim: sw=4 ts=4 expandtab
