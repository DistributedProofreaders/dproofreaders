<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'js_newpophelp.inc');
include_once($relPath.'metarefresh.inc');
include_once('../includes/team.inc'); // showEdit()

require_login();

$user = User::load_current();

$theme_extra_args = array("js_data" => get_newHelpWin_javascript("$code_url/pophelp.php?category=teams&name=edit_"));

if (isset($_POST['mkPreview']))
{
    $title = sprintf(_("Preview %s"), $_POST['teamname']);  // *Ouch*, data not validated.
    output_header($title, SHOW_STATSBAR, $theme_extra_args);
    $teamimages = uploadImages(1,"","both");
    $curTeam['id'] = 0;
    $curTeam['topic_id'] = 0;
    $curTeam['teamname'] = stripAllString($_POST['teamname']);
    $curTeam['team_info'] = stripAllString($_POST['text_data']);
    $curTeam['webpage'] = stripAllString($_POST['teamwebpage']);
    $curTeam['createdby'] = $user->username;
    $curTeam['owner'] = $user->u_id;
    $curTeam['created'] = time();
    $curTeam['member_count'] = 0;
    $curTeam['active_members'] = 0;
    $curTeam['avatar'] = $teamimages['avatar'];
    echo "<div class='center-align'><br>";
    showEdit($_POST['teamname'], $_POST['text_data'], $_POST['teamwebpage'], 1, 0);
    echo "<br>";
    showTeamProfile($curTeam, TRUE /* $preview */);
    echo "</div><br>";
}
else if (isset($_POST['mkMake']))
{
    $result = mysqli_query(DPDatabase::get_connection(), sprintf("
        SELECT id
        FROM user_teams
        WHERE teamname = '%s'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), stripAllString(trim($_POST['teamname'])))));
    if (mysqli_num_rows($result) > 0 || trim($_POST['teamname']) == "")
    {
        $name = _("Create Team");
        output_header($name);
        $teamimages = uploadImages(1,"","both");
        $curTeam['avatar'] = $teamimages['avatar'];
        if(trim($_POST['teamname']) == "")
            echo "<div class='center-align'><br>" . _("The team name must not be empty.") . "<br>";
        else
            echo "<div class='center-align'><br>" . _("The team name must be unique. Please make any changes and resubmit.") . "<br>";

        showEdit($_POST['teamname'], $_POST['text_data'], $_POST['teamwebpage'], 1, 0);
        echo "<br></div><br>";
    }
    else
    {
        mysqli_query(DPDatabase::get_connection(), sprintf("
            INSERT INTO user_teams
                (teamname, team_info, webpage, createdby, owner, created)
            VALUES('%s', '%s', '%s', '%s', %s, %s)
        ", mysqli_real_escape_string(DPDatabase::get_connection(), stripAllString(trim($_POST['teamname']))),
            mysqli_real_escape_string(DPDatabase::get_connection(), stripAllString($_POST['text_data'])),
            mysqli_real_escape_string(DPDatabase::get_connection(), stripAllString($_POST['teamwebpage'])),
            $user->username, $user->u_id, time()));
        $tid = mysqli_insert_id(DPDatabase::get_connection());
        if (!empty($_POST['tavatar']))
        {
            $sql = sprintf("
                UPDATE user_teams
                SET avatar='%s'
                WHERE id = $tid
            ", mysqli_real_escape_string(DPDatabase::get_connection(), $_POST['tavatar']));
            mysqli_query(DPDatabase::get_connection(), $sql);
        }
        elseif (!empty($_FILES['teamavatar']))
        {
            uploadImages(0, $tid, "avatar");
        }
        if (!empty($_POST['ticon']))
        {
            $sql = sprintf("
                UPDATE user_teams
                SET icon='%s'
                WHERE id = $tid
            ", mysqli_real_escape_string(DPDatabase::get_connection(), $_POST['ticon']));
            mysqli_query(DPDatabase::get_connection(), $sql);
        }
        elseif (!empty($_FILES['teamicon']))
        {
            uploadImages(0, $tid, "icon");
        }

        //figure out which team to overwrite
        $otid=0;
        if (!isset($_POST['teamall']))
        {
            if ($user->team_1 == $_POST['tteams'])
            {
                $otid=1;
            }
            elseif ($user->team_2 == $_POST['tteams'])
            {
                $otid=2;
            }
            else if ($user->team_3 == $_POST['tteams'])
            {
                $otid=3;
            }
        }
    
        $title = _("Join the Team");
        $desc = _("Creating the team....");
        metarefresh(0,"../members/jointeam.php?tid=$tid&otid=$otid",$title, $desc);
    }
}
elseif (isset($_POST['mkQuit']))
{
    $title = _("Quit Without Saving");
    $desc = _("Quitting without saving...");
    metarefresh(4,"$code_url/activity_hub.php",$title,$desc);
    exit;
}
else
{
    $name = _("Create a New Team");
    output_header($name, SHOW_STATSBAR, $theme_extra_args);
    echo "<div class='center-align'><br>";
    showEdit("","","",1,0);
    echo "</div>";
}

// vim: sw=4 ts=4 expandtab
