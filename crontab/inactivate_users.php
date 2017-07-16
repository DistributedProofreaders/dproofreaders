<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

//this module sets users inactive who have not been active on the site in 6 months

$old_date = time() - 15768000; // 6 months ago.

$result = mysqli_query(DPDatabase::get_connection(), "UPDATE `users` SET active = 'no' WHERE t_last_activity < $old_date AND active ='yes'");
$numrows = mysqli_affected_rows(DPDatabase::get_connection());

if($numrows)
{
    echo "inactivate_users.php set $numrows users who have not been active for 6 months as inactive";
}

// vim: sw=4 ts=4 expandtab
