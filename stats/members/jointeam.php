<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.inc');

require_login();

$otid = get_integer_param( $_GET, 'otid', 0, 0, null );
$tid  = get_integer_param( $_GET, 'tid', null, 0, null );

$user = User::load_current();
$user_teams = $user->load_teams();

if(in_array($tid, $user_teams))
{
    $title = _("Unable to Join the Team");
    $desc = _("You are already a member of this team....");

    metarefresh(4,"../teams/tdetail.php?tid=$tid", $title, $desc);
}

// If we have an $otid we need to remove them from the team
if($otid)
{
    $user->remove_team($otid);
    $user_teams = $user->load_teams();
}

if(count($user_teams) >= MAX_USER_TEAM_MEMBERSHIP)
{
    $title = _("Team Membership Limit Reached");
    output_header($title);
    echo "<h1>" . html_safe($title) . "</h1>\n";
    echo "<p>" . sprintf(_("You have already joined the maximum of %d teams. Which team would you like to replace?"), MAX_USER_TEAM_MEMBERSHIP) . "</p>";
    echo "<ul>";
    $sql = sprintf("
        SELECT id, teamname
        FROM user_teams
        WHERE id IN (%s)
        ORDER BY teamname
    ", implode(",", $user_teams));
    $result = DPDatabase::query($sql);
    while(list($old_team_id, $teamname) = mysqli_fetch_row($result))
    {
        echo "<li><a href='jointeam.php?tid=$tid&otid=$old_team_id'>" . html_safe($teamname) . "</a></li>";
    }
    echo "</ul>";

    echo "<p><a href='../teams/tdetail.php?tid=$tid'>" . _("Do Not Join Team"). "</a></p>";
    exit;
}

// They aren't a member of the team or at their max, so add them
$user->add_team($tid);
$title = _("Join the Team");
$desc = _("Joining the team....");
metarefresh(0,"../teams/tdetail.php?tid=$tid", $title, $desc);

// vim: sw=4 ts=4 expandtab
