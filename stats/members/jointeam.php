<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'metarefresh.inc');
include_once('../includes/team.inc');

require_login();

$otid = get_integer_param( $_GET, 'otid', 0, 0, 3 );
$tid  = get_integer_param( $_GET, 'tid', null, 0, null );

$user = User::load_current();

if ($user->team_1 != $tid && $user->team_2 != $tid && $user->team_3 != $tid) {
    if ($user->team_1 == 0 || $otid == 1) {
        $teamResult = mysqli_query(DPDatabase::get_connection(), "UPDATE users SET team_1 = $tid WHERE u_id = $user->u_id");
        mysqli_query(DPDatabase::get_connection(), "UPDATE user_teams SET latestUser = $user->u_id, member_count = member_count+1, active_members = active_members+1 WHERE id = $tid");
        if ($otid != 0) { mysqli_query(DPDatabase::get_connection(), "UPDATE user_teams SET active_members = active_members-1 WHERE id = $user->team_1"); }
        $redirect_team = 1;
    } elseif ($user->team_2 == 0 || $otid == 2) {
        $teamResult = mysqli_query(DPDatabase::get_connection(), "UPDATE users SET team_2 = $tid WHERE u_id = $user->u_id");
        mysqli_query(DPDatabase::get_connection(), "UPDATE user_teams SET latestUser = $user->u_id, member_count = member_count+1, active_members = active_members+1 WHERE id = $tid");
        if ($otid != 0) { mysqli_query(DPDatabase::get_connection(), "UPDATE user_teams SET active_members = active_members-1 WHERE id = $user->team_2"); }
        $redirect_team = 1;
    } elseif ($user->team_3 == 0 || $otid == 3) {
        $teamResult = mysqli_query(DPDatabase::get_connection(), "UPDATE users SET team_3 = $tid WHERE u_id = $user->u_id");
        mysqli_query(DPDatabase::get_connection(), "UPDATE user_teams SET latestUser = $user->u_id, member_count = member_count+1, active_members = active_members+1 WHERE id = $tid");
        if ($otid != 0) { mysqli_query(DPDatabase::get_connection(), "UPDATE user_teams SET active_members = active_members-1 WHERE id = $user->team_3"); }
        $redirect_team = 1;
    } else {
        include_once($relPath.'theme.inc');
        $title = _("Three Team Maximum");
        output_header($title);
        echo "<h1>$title</h1>\n";
        echo "<p>" . _("You have already joined three teams.<br>Which team would you like to replace?") . "</p>";
        echo "<ul>";
        $teamR=mysqli_query(DPDatabase::get_connection(), "SELECT teamname FROM user_teams WHERE id = $user->team_1");
        $row = mysqli_fetch_assoc($teamR);
        $teamname = $row["teamname"];
        echo "<li><a href='jointeam.php?tid=$tid&otid=1'>$teamname</a></li>";
        $teamR=mysqli_query(DPDatabase::get_connection(), "SELECT teamname FROM user_teams WHERE id = $user->team_2");
        $row = mysqli_fetch_assoc($teamR);
        $teamname = $row["teamname"];
        echo "<li><a href='jointeam.php?tid=$tid&otid=2'>$teamname</a></li>";
        $teamR=mysqli_query(DPDatabase::get_connection(), "SELECT teamname FROM user_teams WHERE id = $user->team_3");
        $row = mysqli_fetch_assoc($teamR);
        $teamname = $row["teamname"];
        echo "<li><a href='jointeam.php?tid=$tid&otid=3'>$teamname</a></li>";
        echo "</ul>";

        echo "<p><a href='../teams/tdetail.php?tid=$tid'>" . _("Do Not Join Team"). "</a></p>";
        $redirect_team = 0;
    }
} else {
    $title = _("Unable to Join the Team");
    $desc = _("You are already a member of this team....");

    metarefresh(4,"../teams/tdetail.php?tid=$tid", $title, $desc);
    $redirect_team = 0;
}

if ($redirect_team == 1) {
    $title = _("Join the Team");
    $desc = _("Joining the team....");
    metarefresh(0,"../teams/tdetail.php?tid=$tid",$title, $desc);
}

// vim: sw=4 ts=4 expandtab
