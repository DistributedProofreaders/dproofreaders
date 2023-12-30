<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'js_newpophelp.inc');
include_once($relPath.'metarefresh.inc');
include_once('../includes/team.inc');

require_login();

$theme_extra_args = ["js_data" => get_newHelpWin_javascript("$code_url/pophelp.php?category=teams&name=edit_")];

// Either the parameter is $_POST['tsid'] when coming from the edit form,
// or it is $_GET['tid'] when using the link on top of the team summary.
$tid = get_integer_param($_POST, 'tsid', null, 1, null, true);
if (!isset($tid)) {
    $tid = get_integer_param($_GET, 'tid', null, 1, null);
}
$teamname = stripAllString(trim(array_get($_POST, "teamname", "")));
$text_data = stripAllString(array_get($_POST, "text_data", ""));
$teamwebpage = stripAllString(array_get($_POST, "teamwebpage", ""));
$tavatar = array_get($_POST, "tavatar", "");
$ticon = array_get($_POST, "ticon", "");

$result = select_from_teams("id = $tid");
$curTeam = mysqli_fetch_assoc($result);

$user = User::load_current();

// Allow team owner and site administrators to edit the team
if (($user->u_id != $curTeam['owner']) && (!user_is_a_sitemanager())) {
    $title = _("Authorization Failed");
    $desc = _("You are not authorized to edit this team....");
    metarefresh(4, "tdetail.php?tid=$tid", $title, $desc);
    exit;
}

if (isset($_GET['tid'])) {
    $edit = _("Edit");
    output_header($edit . " " . $curTeam['teamname'], SHOW_STATSBAR, $theme_extra_args);
    echo "<div class='center-align'><br>";
    showEdit($curTeam['teamname'], $curTeam['team_info'], $curTeam['webpage'], 0, $tid);
    echo "</div>";
} elseif (isset($_POST['edQuit'])) {
    $title = _("Quit Without Saving");
    $desc = _("Quitting without saving...");
    metarefresh(0, "tdetail.php?tid=$tid", $title, $desc);
    exit;
} elseif (isset($_POST['edPreview'])) {
    $preview = _("Preview");
    output_header($preview . " " . $teamname, SHOW_STATSBAR, $theme_extra_args);
    $teamimages = uploadImages(1, $tid, "both");
    $curTeam['teamname'] = $teamname;
    $curTeam['team_info'] = $text_data;
    $curTeam['webpage'] = $teamwebpage;
    $curTeam['avatar'] = $teamimages['avatar'];
    echo "<div class='center-align'><br>";
    showEdit($teamname, $text_data, $teamwebpage, 0, $tid);
    echo "<br>";
    showTeamProfile($curTeam, /*$preview = */ true);
    echo "</div><br>";
} elseif (isset($_POST['edMake'])) {
    $sql = sprintf(
        "
        SELECT id
        FROM user_teams
        WHERE id != %d
            AND teamname = '%s'
        ",
        $tid,
        DPDatabase::escape($teamname)
    );
    $result = DPDatabase::query($sql);
    if (mysqli_num_rows($result) > 0 || $teamname == '') {
        $preview = _("Preview");
        output_header($preview, SHOW_STATSBAR, $theme_extra_args);
        $teamimages = uploadImages(1, $tid, "both");
        $curTeam['avatar'] = $teamimages['avatar'];
        if ($teamname == "") {
            echo "<div class='center-align'><br>" . _("The team name must not be empty.") . "<br>";
        } else {
            echo "<div class='center-align'><br>" . _("The team name must be unique. Please make any changes and resubmit.") . "<br>";
        }

        showEdit($teamname, $text_data, $teamwebpage, 0, $tid);
        echo "<br></div><br>";
    } else {
        if (!empty($tavatar)) {
            $sql = sprintf(
                "
                UPDATE user_teams
                SET avatar='%s'
                WHERE id = %d
                ",
                $tid,
                DPDatabase::escape($tavatar)
            );
            DPDatabase::query($sql);
        } elseif (!empty($_FILES['teamavatar'])) {
            uploadImages(0, $tid, "avatar");
        }
        if (!empty($ticon)) {
            $sql = sprintf(
                "
                UPDATE user_teams
                SET icon='%s'
                WHERE id = %d
                ",
                $tid,
                DPDatabase::escape($ticon)
            );
            DPDatabase::query($sql);
        } elseif (!empty($_FILES['teamicon'])) {
            uploadImages(0, $tid, "icon");
        }

        $sql = sprintf(
            "
            UPDATE user_teams
            SET
                teamname = LEFT('%s', 50),
                team_info = '%s',
                webpage = LEFT('%s', 255)
            WHERE id = %d
            ",
            DPDatabase::escape($teamname),
            DPDatabase::escape($text_data),
            DPDatabase::escape($teamwebpage),
            $tid
        );
        DPDatabase::query($sql);

        $title = _("Saving Team Update");
        $desc = _("Updating team....");
        metarefresh(0, "tdetail.php?tid=$tid", $title, $desc);
    }
}
