<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'js_newpophelp.inc');
include_once('../includes/team.inc');

$theme_extra_args = array("js_data" => get_newHelpWin_javascript("$code_url/faq/pophelp/teams/edit_"));

//Do we need this anymore?
$tid = get_integer_param($_POST, 'tsid', null, 1, null, true);
if (!isset($tid)) {
    $tid = get_integer_param($_POST, 'tid', null, 1, null);
}

$result = select_from_teams("id = $tid");
$curTeam = mysql_fetch_assoc($result);

if ($userP['u_id'] != $curTeam['owner'])
{
    $title = _("Authorization Failed");
    $desc = _("You are not authorized to edit this team....");
    metarefresh(4,"tdetail.php?tid=$tid",$title,$desc);
    exit;
}

if (isset($_GET['tid']))
{
    $edit = _("Edit");
    theme($edit." ".$curTeam['teamname'], "header", $theme_extra_args);
    echo "<center><br>";
    showEdit(unstripAllString($curTeam['teamname'],0),unstripAllString($curTeam['team_info'],1),unstripAllString($curTeam['webpage'],1),0,$tid,0,0);
    echo "</center>";
    theme("", "footer");
}
elseif (isset($_POST['edPreview']))
{
    $preview = _("Preview");
    theme($preview." ".$_POST['teamname'], "header", $theme_extra_args);
    $teamimages = uploadImages(1,$tid,"both");
    $curTeam['teamname'] = stripAllString($_POST['teamname']);
    $curTeam['team_info'] = stripAllString($_POST['text_data']);
    $curTeam['webpage'] = stripAllString($_POST['teamwebpage']);
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
    showEdit(htmlentities(stripslashes($_POST['teamname'])),stripslashes($_POST['text_data']),stripslashes($_POST['teamwebpage']),0,$tid,$tavatar,$ticon);
    echo "<br>";
    showTeamProfile($curTeam);
    echo "</center><br>";
    theme("", "footer");
}
elseif (isset($_POST['edMake']))
{
    $result = mysql_query("SELECT id FROM user_teams WHERE id != ".$tid." AND teamname = '".addslashes(stripAllString(trim($_POST['teamname'])))."'");
    if (mysql_num_rows($result) > 0)
    {
        $preview = _("Preview");
        theme($preview, "header", $theme_extra_args);
        $teamimages = uploadImages(1,$tid,"both");
        if (!empty($_FILES['teamavatar']['tmp_name']))
        {
            $curTeam['avatar'] = $teamimages['avatar'];
            $tavatar = 1;
        }
        if (!empty($_FILES['teamicon']['tmp_name']))
        {
            $ticon = 1;
        }
        echo "<center><br>The team name must be unique.  Please make any changes and resubmit.<br>";
        showEdit(htmlentities(stripslashes($_POST['teamname'])),stripslashes($_POST['text_data']),stripslashes($_POST['teamwebpage']),0,$tid,$tavatar,$ticon);
        echo "<br></center><br>";
        theme("", "footer");
    }
    else
    {
        if (!empty($_POST['tavatar']))
        {
            mysql_query("UPDATE user_teams SET avatar='".$_POST['tavatar']."' WHERE id = $tid");
        }
        elseif (!empty($_FILES['teamavatar']))
        {
            uploadImages(0,$tid,"avatar");
        }
        if (!empty($_POST['ticon']))
        {
            mysql_query("UPDATE user_teams SET icon='".$_POST['ticon']."' WHERE id = $tid");
        }
        elseif (!empty($_FILES['teamicon']))
        {
            uploadImages(0,$tid,"icon");
        }
    
        mysql_query("UPDATE user_teams SET teamname='".addslashes(stripAllString(trim($_POST['teamname'])))."', team_info='".addslashes(stripAllString($_POST['text_data']))."', webpage='".addslashes(stripAllString($_POST['teamwebpage']))."' WHERE id='$tid'");
    
        $title = _("Saving Team Update");
        $desc = _("Updating team....");
        metarefresh(0,"tdetail.php?tid=$tid",$title, $desc);
    }
}

// vim: sw=4 ts=4 expandtab
?>
