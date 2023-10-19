<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'js_newpophelp.inc');
include_once($relPath.'metarefresh.inc');
include_once('../includes/team.inc'); // showEdit()

require_login();

$user = User::load_current();

$theme_extra_args = ["js_data" => get_newHelpWin_javascript("$code_url/pophelp.php?category=teams&name=edit_")];

$teamname = trim(array_get($_POST, "teamname", ""));
$text_data = stripAllString(array_get($_POST, "text_data", ""));
$teamwebpage = array_get($_POST, "teamwebpage", "");
$tavatar = array_get($_POST, "tavatar", "");
$ticon = array_get($_POST, "ticon", "");


if (isset($_POST['mkPreview'])) {
    $title = sprintf(_("Preview %s"), $teamname);
    output_header($title, SHOW_STATSBAR, $theme_extra_args);
    $teamimages = uploadImages(1, "", "both");
    $curTeam['id'] = 0;
    $curTeam['topic_id'] = 0;
    $curTeam['teamname'] = $teamname;
    $curTeam['team_info'] = $text_data;
    $curTeam['webpage'] = $teamwebpage;
    $curTeam['createdby'] = $user->username;
    $curTeam['owner'] = $user->u_id;
    $curTeam['created'] = time();
    $curTeam['member_count'] = 0;
    $curTeam['avatar'] = $teamimages['avatar'];
    echo "<div class='center-align'><br>";
    showEdit($teamname, $text_data, $teamwebpage, 1, 0);
    echo "<br>";
    showTeamProfile($curTeam, /* $preview= */ true);
    echo "</div><br>";
} elseif (isset($_POST['mkMake'])) {
    $sql = sprintf(
        "
        SELECT id
        FROM user_teams
        WHERE teamname = '%s'",
        DPDatabase::escape($teamname)
    );
    $result = DPDatabase::query($sql);
    if (mysqli_num_rows($result) > 0 || $teamname == "") {
        $name = _("Create Team");
        output_header($name);
        $teamimages = uploadImages(1, "", "both");
        $curTeam['avatar'] = $teamimages['avatar'];
        if ($teamname == "") {
            echo "<p class='center-align error'>" . _("The team name must not be empty.") . "</p>";
        } else {
            echo "<p class='center-align error'>" . _("The team name must be unique. Please make any changes and resubmit.") . "</p>";
        }

        showEdit($teamname, $text_data, $teamwebpage, 1, 0);
        echo "<br></div><br>";
    } else {
        $sql = sprintf(
            "
            INSERT INTO user_teams
                (teamname, team_info, webpage, createdby, owner, created)
            VALUES(LEFT('%s', 50), '%s', LEFT('%s', 255), '%s', %d, %d)
            ",
            DPDatabase::escape($teamname),
            DPDatabase::escape($text_data),
            DPDatabase::escape($teamwebpage),
            DPDatabase::escape($user->username),
            $user->u_id,
            time()
        );
        DPDatabase::query($sql);
        $tid = mysqli_insert_id(DPDatabase::get_connection());
        if (!empty($tavatar)) {
            $sql = sprintf(
                "
                UPDATE user_teams
                SET avatar='%s'
                WHERE id = %d",
                DPDatabase::escape($tavatar),
                $tid
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
                WHERE id = %d",
                DPDatabase::escape($ticon),
                $tid
            );
            DPDatabase::query($sql);
        } elseif (!empty($_FILES['teamicon'])) {
            uploadImages(0, $tid, "icon");
        }

        //figure out which team to overwrite
        $otid = get_integer_param($_POST, 'otid', 0, 0, null);

        $title = _("Join the Team");
        $desc = _("Creating the team....");
        metarefresh(0, "../members/jointeam.php?tid=$tid&otid=$otid", $title, $desc);
    }
} elseif (isset($_POST['mkQuit'])) {
    $title = _("Quit Without Saving");
    $desc = _("Quitting without saving...");
    metarefresh(0, "$code_url/activity_hub.php", $title, $desc);
    exit;
} else {
    $name = _("Create a New Team");
    output_header($name, SHOW_STATSBAR, $theme_extra_args);
    echo "<div class='center-align'><br>";
    showEdit("", "", "", 1, 0);
    echo "</div>";
}
