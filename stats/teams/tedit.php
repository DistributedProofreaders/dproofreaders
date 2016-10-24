<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'js_newpophelp.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // get_integer_param()
include_once('../includes/team.inc');

require_login();

$theme_extra_args = array("js_data" => get_newHelpWin_javascript("$code_url/pophelp.php?category=teams&name=edit_"));

// Either the parameter is $_POST['tsid'] when coming from the edit form,
// or it is $_GET['tid'] when using the link on top of the team summary.
$tid = get_integer_param($_POST, 'tsid', null, 1, null, true);
if (!isset($tid)) {
    $tid = get_integer_param($_GET, 'tid', null, 1, null);
}

$result = select_from_teams("id = $tid");
$curTeam = mysql_fetch_assoc($result);

// Allow team owner and site administrators to edit the team
if (($userP['u_id'] != $curTeam['owner']) && (!user_is_a_sitemanager()))
{
    $title = _("Authorization Failed");
    $desc = _("You are not authorized to edit this team....");
    metarefresh(4,"tdetail.php?tid=$tid",$title,$desc);
    exit;
}

if (isset($_GET['tid']))
{
    $edit = _("Edit");
    output_header($edit." ".$curTeam['teamname'], SHOW_STATSBAR, $theme_extra_args);
    echo "<center><br>";
    showEdit(unstripAllString($curTeam['teamname'],0),unstripAllString($curTeam['team_info'],1),unstripAllString($curTeam['webpage'],1),0,$tid);
    echo "</center>";
}
elseif (isset($_POST['edQuit']))
{
    $title = _("Quit Without Saving");
    $desc = _("Quitting without saving...");
    metarefresh(4,"tdetail.php?tid=$tid",$title,$desc);
    exit;
}
elseif (isset($_POST['edPreview']))
{
    $preview = _("Preview");
    output_header($preview." ".$_POST['teamname'], SHOW_STATSBAR, $theme_extra_args);
    $teamimages = uploadImages(1,$tid,"both");
    $curTeam['teamname'] = stripAllString($_POST['teamname']);
    $curTeam['team_info'] = stripAllString($_POST['text_data']);
    $curTeam['webpage'] = stripAllString($_POST['teamwebpage']);
    $curTeam['avatar'] = $teamimages['avatar'];
    echo "<center><br>";
    showEdit($_POST['teamname'], $_POST['text_data'], $_POST['teamwebpage'], 0, $tid);
    echo "<br>";
    showTeamProfile($curTeam, TRUE /*$preview*/);
    echo "</center><br>";
}
elseif (isset($_POST['edMake']))
{
    $result = mysql_query(sprintf("
        SELECT id
        FROM user_teams
        WHERE id != %s
            AND teamname = '%s'
    ", $tid,
        mysql_real_escape_string(stripAllString(trim($_POST['teamname'])))));
    if (mysql_num_rows($result) > 0 || trim($_POST['teamname']) == '')
    {
        $preview = _("Preview");
        output_header($preview, SHOW_STATSBAR, $theme_extra_args);
        $teamimages = uploadImages(1,$tid,"both");
        $curTeam['avatar'] = $teamimages['avatar'];
        if(trim($_POST['teamname']) == "")
            echo "<center><br>" . _("The team name must not be empty.") . "<br>";
        else
            echo "<center><br>" . _("The team name must be unique. Please make any changes and resubmit.") . "<br>";

        showEdit($_POST['teamname'], $_POST['text_data'], $_POST['teamwebpage'], 0, $tid);
        echo "<br></center><br>";
    }
    else
    {
        if (!empty($_POST['tavatar']))
        {
            $sql = sprintf("
                UPDATE user_teams
                SET avatar='%s'
                WHERE id = $tid
            ", mysql_real_escape_string($_POST['tavatar']));
            mysql_query($sql);
        }
        elseif (!empty($_FILES['teamavatar']))
        {
            uploadImages(0,$tid,"avatar");
        }
        if (!empty($_POST['ticon']))
        {
            $sql = sprintf("
                UPDATE user_teams
                SET icon='%s'
                WHERE id = $tid
            ", mysql_real_escape_string($_POST['ticon']));
            mysql_query($sql);
        }
        elseif (!empty($_FILES['teamicon']))
        {
            uploadImages(0,$tid,"icon");
        }

        mysql_query(sprintf("
            UPDATE user_teams
            SET
                teamname='%s',
                team_info='%s',
                webpage='%s'
            WHERE id='%s'
        ", mysql_real_escape_string(stripAllString(trim($_POST['teamname']))),
            mysql_real_escape_string(stripAllString($_POST['text_data'])),
            mysql_real_escape_string(stripAllString($_POST['teamwebpage'])),
            $tid));

        $title = _("Saving Team Update");
        $desc = _("Updating team....");
        metarefresh(0,"tdetail.php?tid=$tid",$title, $desc);
    }
}

// vim: sw=4 ts=4 expandtab
