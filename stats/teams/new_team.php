<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'js_newpophelp.inc');
include_once($relPath.'metarefresh.inc');
include_once('../includes/team.inc');

require_login();

$theme_extra_args = array("js_data" => get_newHelpWin_javascript("$code_url/faq/pophelp/teams/edit_"));

if (isset($_POST['mkPreview']))
{
    $title = sprintf(_("Preview %s"), $_POST['teamname']);  // *Ouch*, data not validated.
    output_header($title, SHOW_STATSBAR, $theme_extra_args);
    $teamimages = uploadImages(1,"","both");
    $curTeam['teamname'] = stripAllString($_POST['teamname']);
    $curTeam['team_info'] = stripAllString($_POST['text_data']);
    $curTeam['webpage'] = stripAllString($_POST['teamwebpage']);
    $curTeam['createdby'] = $pguser;
    $curTeam['created'] = time();
    $curTeam['page_count'] = 0;
    if (!empty($_FILES['teamavatar']['tmp_name']))
    {
        $curTeam['avatar'] = $teamimages['avatar'];
        $tavatar = 1;
    }
    if (!empty($_FILES['teamicon']['tmp_name']))
    {
        $ticon = 1;
    }
    echo "<center><br>";
    showEdit(htmlentities($_POST['teamname']), $_POST['text_data'], $_POST['teamwebpage'], 1, 0, $tavatar, $ticon);
    echo "<br>";
    showTeamProfile($curTeam);
    echo "</center><br>";
}
else if (isset($_POST['mkMake']))
{
    $result = mysql_query(sprintf("
        SELECT id
        FROM user_teams
        WHERE teamname = '%s'
    ", mysql_real_escape_string(stripAllString(trim($_POST['teamname'])))));
    if (mysql_num_rows($result) > 0)
    {
        $name = _("Create Team");
        output_header($name);
        $teamimages = uploadImages(1,"","both");
        
        if (!empty($_FILES['teamavatar']['tmp_name']))
        {
            $curTeam['avatar'] = $teamimages['avatar'];
            $tavatar = 1;
        }
        if (!empty($_FILES['teamicon']['tmp_name']))
        {
            $ticon = 1;
        }
        echo "<center><br>" . _("The team name must be unique. Please make any changes and resubmit.") . "<br>";
        showEdit(htmlentities($_POST['teamname']), $_POST['text_data'], $_POST['teamwebpage'], 1, 0, $tavatar, $ticon);
        echo "<br></center><br>";
    }
    else
    {
        mysql_query(sprintf("
            INSERT INTO user_teams
                (teamname, team_info, webpage, createdby, owner, created)
            VALUES('%s', '%s', '%s', '%s', %s, %s)
        ", mysql_real_escape_string(stripAllString(trim($_POST['teamname']))),
            mysql_real_escape_string(stripAllString($_POST['text_data'])),
            mysql_real_escape_string(stripAllString($_POST['teamwebpage'])),
            $pguser, $userP['u_id'], time()));
        $tid = mysql_insert_id($db_Connection->db_lk);
        if (!empty($_POST['tavatar']))
        {
            mysql_query("UPDATE user_teams SET avatar='".$_POST['tavatar']."' WHERE id = $tid");
        }
        elseif (!empty($_FILES['teamavatar']))
        {
            uploadImages(0, $tid, "avatar");
        }
        if (!empty($_POST['ticon']))
        {
            mysql_query("UPDATE user_teams SET icon='".$_POST['ticon']."' WHERE id = $tid");
        }
        elseif (!empty($_FILES['teamicon']))
        {
            uploadImages(0, $tid, "icon");
        }

        //figure out which team to overwrite
        $otid=0;
        if (!isset($_POST['teamall']))
        {
            if ($userP['team_1'] == $_POST['tteams'])
            {
                $otid=1;
            }
            elseif ($userP['team_2'] == $_POST['tteams'])
            {
                $otid=2;
            }
            else if ($userP['team_3'] == $_POST['tteams'])
            {
                $otid=3;
            }
        }
        dpsession_set_preferences_from_db();
    
        $title = _("Join the Team");
        $desc = _("Creating the team....");
        metarefresh(0,"../members/jointeam.php?tid=$tid&otid=$otid",$title, $desc);
    }
}
else
{
    $name = _("Create a New Team");
    output_header($name, SHOW_STATSBAR, $theme_extra_args);
    echo "<center><br>";
    showEdit("","","",1,0,0,0);
    echo "</center>";
}

// vim: sw=4 ts=4 expandtab
