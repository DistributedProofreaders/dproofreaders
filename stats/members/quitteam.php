<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // get_integer_param()
include_once('../includes/team.inc');

require_login();

$tid = get_integer_param($_GET, 'tid', null, 0, null);

if ($userP['team_1'] == $tid || $userP['team_2'] == $tid || $userP['team_3'] == $tid) {
    $quitQuery = "UPDATE users SET ";
    if ($userP['team_1'] == $tid) { $quitQuery .= "team_1 = '0'"; }
    if ($userP['team_2'] == $tid) { $quitQuery .= "team_2 = '0'"; }
    if ($userP['team_3'] == $tid) { $quitQuery .= "team_3 = '0'"; }
    $quitQuery.=" WHERE username='$pguser' AND u_id='".$userP['u_id']."'";
    $teamResult=mysql_query($quitQuery);
    mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id='".$tid."'");
    dpsession_set_preferences_from_db();
    $title = _("Quit the Team");
    $desc = _("Quitting the team....");
    metarefresh(0,"../teams/tdetail.php?tid=".$tid."",$title,$desc);
}  else {
    $title = _("Not a member");
    $desc = _("Unable to quit team....");
    metarefresh(3,"../teams/tdetail.php?tid=".$tid."",$title,$desc);
}

// vim: sw=4 ts=4 expandtab
