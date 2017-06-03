<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

$old_date = time() - 7776000;
    
$result = mysql_query ("SELECT * FROM user_teams WHERE active_members <= 1");

while ($row = mysql_fetch_assoc($result)) {
    if ($row['created'] <= $old_date && $row['active_members'] == 0)
    {
        $del = mysql_query("DELETE FROM user_teams WHERE id = ".$row['id']."");
    }
    elseif ($row['created'] <= $old_date && $row['active_members'] == 1)
    {
        $mbrCheck = mysql_query("SELECT * FROM users WHERE {$row['id']} IN (team_1, team_2, team_3)");
        if (mysql_num_rows($mbrCheck) == 0)
        {
            $del = mysql_query("DELETE FROM user_teams WHERE id = ".$row['id']."");
        }
    }
}
