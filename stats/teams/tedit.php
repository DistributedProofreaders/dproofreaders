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
$curTeam = mysqli_fetch_assoc($result);

$user = User::load_current();

// Allow team owner and site administrators to edit the team
if (($user->u_id != $curTeam['owner']) && (!user_is_a_sitemanager()))
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
    echo "<div class='center-align'><br>";
    showEdit($curTeam['teamname'], $curTeam['team_info'], $curTeam['webpage'], 0, $tid);
    echo "</div>";
}
elseif (isset($_POST['edQuit']))
{
    $title = _("Quit Without Saving");
    $desc = _("Quitting without saving...");
    metarefresh(0,"tdetail.php?tid=$tid",$title,$desc);
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
    echo "<div class='center-align'><br>";
    showEdit($_POST['teamname'], $_POST['text_data'], $_POST['teamwebpage'], 0, $tid);
    echo "<br>";
    showTeamProfile($curTeam, TRUE /*$preview*/);
    echo "</div><br>";
}
elseif (isset($_POST['edMake']))
{
    $result = mysqli_query(DPDatabase::get_connection(), sprintf("
        SELECT id
        FROM user_teams
        WHERE id != %s
            AND teamname = '%s'
    ", $tid,
        mysqli_real_escape_string(DPDatabase::get_connection(), stripAllString(trim($_POST['teamname'])))));
    if (mysqli_num_rows($result) > 0 || trim($_POST['teamname']) == '')
    {
        $preview = _("Preview");
        output_header($preview, SHOW_STATSBAR, $theme_extra_args);
        $teamimages = uploadImages(1,$tid,"both");
        $curTeam['avatar'] = $teamimages['avatar'];
        if(trim($_POST['teamname']) == "")
            echo "<div class='center-align'><br>" . _("The team name must not be empty.") . "<br>";
        else
            echo "<div class='center-align'><br>" . _("The team name must be unique. Please make any changes and resubmit.") . "<br>";

        showEdit($_POST['teamname'], $_POST['text_data'], $_POST['teamwebpage'], 0, $tid);
        echo "<br></div><br>";
    }
    else
    {
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
            uploadImages(0,$tid,"avatar");
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
            uploadImages(0,$tid,"icon");
        }

        mysqli_query(DPDatabase::get_connection(), sprintf("
            UPDATE user_teams
            SET
                teamname = LEFT('%s', 50),
                team_info = '%s',
                webpage = LEFT('%s', 255),
            WHERE id='%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), stripAllString(trim($_POST['teamname']))),
            mysqli_real_escape_string(DPDatabase::get_connection(), stripAllString($_POST['text_data'])),
            mysqli_real_escape_string(DPDatabase::get_connection(), stripAllString($_POST['teamwebpage'])),
            $tid));

        $title = _("Saving Team Update");
        $desc = _("Updating team....");
        metarefresh(0,"tdetail.php?tid=$tid",$title, $desc);
    }
}

// vim: sw=4 ts=4 expandtab
