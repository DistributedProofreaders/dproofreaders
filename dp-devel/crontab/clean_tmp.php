<?
$relPath="./../pinc/";
include_once($relPath.'misc.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

exec('/usr/sbin/tmpwatch -fav 3 /tmp/sp_check/');
?>
