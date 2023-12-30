<?php
//A file full of functions used in team stats

include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'site_vars.php');
include_once($relPath.'page_tally.inc');
include_once($relPath.'ThemedTable.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'../stats/includes/common.inc');
include_once($relPath.'graph_data.inc');

$team_avatars_dir = "$dyn_dir/teams/avatar";
$team_avatars_url = "$dyn_url/teams/avatar";

$team_icons_dir = "$dyn_dir/teams/icon";
$team_icons_url = "$dyn_url/teams/icon";

// Define the maximum number of teams the user can be a member of.
define("MAX_USER_TEAM_MEMBERSHIP", 6);

function select_from_teams($where_body, $other_clauses = '')
{
    [, $teams_ELR_page_tallyboard] = get_ELR_tallyboards();
    [$joined_with_team_ELR_page_tallies, $team_ELR_page_tally_column] =
        $teams_ELR_page_tallyboard->get_sql_joinery_for_current_tallies('id');

    if ($where_body == '') {
        $where_body = '1';
    }

    $q = "
        SELECT
            user_teams.*,
            $team_ELR_page_tally_column AS page_count
        FROM user_teams
            $joined_with_team_ELR_page_tallies
        WHERE $where_body
        $other_clauses
    ";
    $res = DPDatabase::query($q);
    return $res;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showTeamInformation($curTeam, $tally_name)
{
    showTeamProfile($curTeam);

    showTeamTallySelector($curTeam, $tally_name);

    if (!is_null($tally_name)) {
        showTeamStats($curTeam, $tally_name);
        showTeamMbrs($curTeam, $tally_name);

        // Only show the team history if they are more than a day old
        $now = time();
        if (($now - $curTeam['created']) > 86400) {
            showTeamHistory($curTeam, $tally_name);
        }
    }
}

// -----------------------------------------------------------------------------

function showTeamProfile($curTeam, $preview = false)
{
    global $team_avatars_url;
    global $code_url;

    $user = User::load_current();
    $team_id = $curTeam['id'];

    if ($team_id) {
        $xml_doc_url = "$code_url/stats/teams/teams_xml.php?id=$team_id";
    } else {
        $xml_doc_url = "";
    }
    $xml_img_url = "$code_url/graphics/xml.gif";
    $xml_img_tag = "<img src='$xml_img_url' style='border: 0; width: 36px; height: 14px; vertical-align: middle;' alt='XML'>";
    $xml_button = "<a href='$xml_doc_url'>$xml_img_tag</a>";

    // Allow team owner and site administrators to edit the team
    if (!$preview && $user && (($user->u_id == $curTeam['owner']) || user_is_a_sitemanager())) {
        $op_url = "tedit.php?tid=$team_id";
        $text = _('Edit');
        $editlink = "&nbsp;[<a href='$op_url'>$text</a>]";
    } else {
        $editlink = '';
    }

    if ($preview || !$user) {
        // The requestor is not logged in,
        // so they can't join or quit the team.
        $joinquitlink = '';
    } else {
        if (!in_array($team_id, $user->load_teams())) {
            $op = 'jointeam';
            $text = _('Join');
        } else {
            $op = 'quitteam';
            $text = _('Quit');
        }
        $op_url = "../members/$op.php?tid=$team_id";
        $joinquitlink = "&nbsp;[<a href='$op_url'>$text</a>]";
    }

    if ($curTeam['topic_id']) {
        $last_post_date = get_last_post_time_in_topic($curTeam['topic_id']);
    } else {
        $last_post_date = null;
    }
    if ($last_post_date != null) {
        $last_post_date = date("n/j/Y g:i:sA", $last_post_date);
    }

    $now = time();
    $daysInExistence = number_format(floor(($now - $curTeam['created']) / 86400));

    if (empty($curTeam['avatar'])) {
        $curTeam['avatar'] = "avatar_default.png";
    }

    echo "<table style='width: 100%;'>";
    echo "<tr>";
    echo "<td style='width: 30%; text-align: center;'>";
    echo "<img style='border: 0; max-width: 150px;' src='$team_avatars_url/";
    echo $curTeam['avatar'] . "' alt='".attr_safe(strip_tags($curTeam['teamname']))."'></td>";
    echo "<td style='width: 70%'>";

    $t = new ThemedTable(
        2,
        html_safe($curTeam['teamname']) . "&nbsp;$xml_button$editlink$joinquitlink",
        ['width' => 100]
    );
    $t->set_column_widths(25, 75);
    $t->set_column_alignments('left', 'left');

    $t->row(
        _("Created"),
        date("m/d/Y", $curTeam['created'])
        . "&nbsp;(" . sprintf(_("%s days"), $daysInExistence) . ")"
    );

    $t->row(
        _("Created by"),
        $curTeam['createdby']
    );

    $owner_url = "$code_url/stats/members/mdetail.php?id=" . $curTeam['owner'];
    $owner_name = get_username_for_uid($curTeam['owner']);
    $t->row(
        _("Leader"),
        "<a href='$owner_url'>" . html_safe($owner_name) . "</a>"
    );

    // Team descriptions may contain limited HTML formatting (see
    // stripAllString() for the full list) and should not be wrapped
    // in html_safe().
    $t->row(
        _("Description"),
        $curTeam['team_info']
    );

    $webpage = $curTeam['webpage'];
    $t->row(
        _("Website"),
        "<a href='" . attr_safe($webpage) . "' target='_blank'>" . html_safe($webpage) . "</a>"
    );

    $topic_url = "$code_url/stats/teams/team_topic.php?team=$team_id";
    $t->row(
        _("Forums"),
        "<a href='$topic_url'>"._("Team Discussion")."</a> "
        . (is_null($last_post_date) ? '' : "("._("Last Post:")." $last_post_date)")
    );

    if ($team_id) {
        $mbrCountRank = team_get_member_count_rank($team_id);
    } else {
        $mbrCountRank = 0;
    }
    $t->row(
        _("Members")
            . " <i>("._("Rank").")</i>",
        number_format($curTeam['member_count'])
            . "&nbsp;<i>(#$mbrCountRank)</i>"
    );
    $t->row(
        _("Current Members"),
        number_format(Team::active_member_count($team_id))
    );
    $t->row(
        _("Retired Members"),
        number_format($curTeam['member_count'] - Team::active_member_count($team_id))
    );

    $t->end();

    echo "</td></tr></table>";
}

function team_get_member_count_rank($team_id)
{
    $result = DPDatabase::query("
        SELECT id
        FROM user_teams
        ORDER BY member_count DESC
    ");
    $i = 1;
    while ([$id] = mysqli_fetch_row($result)) {
        if ($id == $team_id) {
            return $i;
        }
        $i++;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showTeamTallySelector($curTeam, $tally_name)
{
    $team_id = $curTeam['id'];

    $choices = '';
    $page_tally_names = get_page_tally_names();
    foreach ($page_tally_names as $t_name => $t_title) {
        if (!empty($choices)) {
            $choices .= " | ";
        }

        $text = $t_name;
        if ($t_name != $tally_name) {
            $text = "<a href='tdetail.php?tid=$team_id&amp;tally_name=$t_name'>$text</a>";
        }
        $choices .= $text;
    }
    echo "\n";
    echo "<p style='text-align: center;'>";
    echo _("Display team stats for different rounds:") . ' ' . $choices;
    echo "</p>";

    echo "\n";
    echo "<hr style='width: 95%;'>";

    if (is_null($tally_name)) {
        return;
    }

    $tally_title = $page_tally_names[$tally_name];
    echo "\n";
    echo "<p style='text-align: center;'>";
    echo $tally_title;
    echo "</p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showTeamStats($curTeam, $tally_name)
{
    $teams_tallyboard = new TallyBoard($tally_name, 'T');

    $vitals = $teams_tallyboard->get_vitals($curTeam['id']);

    $bestDayTime = date("M. jS, Y", ($vitals->best_delta_timestamp - 1));

    $t = new ThemedTable(
        2,
        _("Team Statistics"),
        ['theme_striped' => true]
    );
    $t->set_column_widths(60, 40);

    $t->row(
        _("Total Pages")
            . " <i>("._("Rank").")</i>",
        "<b>" . number_format($vitals->current_tally) . "</b>"
            . "&nbsp;<i>(#$vitals->current_rank)</i>"
    );

    $avg_pages_per_day = get_daily_average($curTeam['created'], $vitals->current_tally);
    $t->row(
        _("Avg. Pages per Day"),
        "<b>" . number_format($avg_pages_per_day, 1) . "</b>"
    );
    $t->row(
        _("Most Pages Done per Day"),
        "<b>" . number_format($vitals->best_delta) . "</b>"
            . "&nbsp;<i>($bestDayTime)</i>"
    );

    $t->end();
}

function showTeamMbrs($curTeam, $tally_name)
{
    global $code_url, $site_abbreviation;

    $order = get_enumerated_param($_GET, 'order', 'mbr', ['mbr', 'pages', 'date']);
    $direction = get_enumerated_param($_GET, 'direction', 'asc', ['asc', 'desc']);
    $req_team_id = get_integer_param($_GET, 'tid', null, 0, null);

    $subtitle = null;
    $latestMbr = DPDatabase::query("SELECT users.username AS Username, users.u_privacy AS Privacy FROM user_teams INNER JOIN users ON user_teams.latestUser = users.u_id WHERE user_teams.id = ".$curTeam['id']." AND user_teams.latestUser <> 0");
    $row = mysqli_fetch_assoc($latestMbr);
    if ($row) {
        if ($row["Privacy"] == PRIVACY_PRIVATE && isset($GLOBALS['pguser'])) {
            $subtitle = sprintf(_("Please welcome <b>%s</b> as the latest member to join this team!"), $row["Username"]);
        }
    }

    $t = new ThemedTable(
        3,
        _("Team Member Details"),
        [
            'theme_striped' => true,
            'subtitle' => $subtitle,
        ]
    );
    $t->set_column_widths(35, 35, 30);

    $header_content_ = [];

    $column_descriptors =
        [
            ['mbr',   'username',      'asc',  _("Username")],
            ['pages', 'current_tally', 'desc', _("Pages Completed")],
            ['date',  'date_created',  'asc',  sprintf(_("Date Joined %s"), $site_abbreviation)],
        ];
    foreach ($column_descriptors as $column_descriptor) {
        [$cd_order, $cd_dbcolumn, $cd_default_dir, $cd_header_text] =
            $column_descriptor;
        if ($cd_order == $order) {
            // This is the column by which we are to sort the table.

            // So here's the db-column to ORDER BY in the SQL query.
            $orderby = $cd_dbcolumn;

            // Mark this column-header with a direction-indicator.
            $dir_indicator = ($direction == "asc") ? '&#9650;' : '&#9660;'; // up and down symbols

            // And provide a link to sort by this column in the opposite direction.
            $dir = ($direction == "asc") ? "desc" : "asc";
        } else {
            // This is a non-sorting column (for this request).

            $dir_indicator = '';

            // And provide a link to sort by this column in its default direction.
            $dir = $cd_default_dir;
        }
        $url = "tdetail.php?tid=".$curTeam['id']."&amp;tally_name=$tally_name&amp;order=$cd_order&amp;direction=$dir";

        $header_content_[] = "<a href='$url'>$cd_header_text</a>$dir_indicator";
    }

    $t->column_headers($header_content_);

    $users_tallyboard = new TallyBoard($tally_name, 'U');

    [$joined_with_user_tallies, $user_tally_column] =
        $users_tallyboard->get_sql_joinery_for_current_tallies('u_id');

    $sql = sprintf(
        "
        SELECT *, $user_tally_column AS current_tally
        FROM users $joined_with_user_tallies
        WHERE u_id IN (
            SELECT u_id
            FROM user_teams_membership
            WHERE t_id = %d
        )
        ORDER BY $orderby $direction
        ",
        $req_team_id
    );
    $mbrQuery = DPDatabase::query($sql);

    $totalAnonUsers = 0;
    $totalPagesAnonUsers = 0;
    while ($curMbr = mysqli_fetch_assoc($mbrQuery)) {
        if ($curMbr['u_privacy'] == PRIVACY_ANONYMOUS) {
            $totalAnonUsers++;
            $totalPagesAnonUsers += $curMbr['current_tally'];
        } elseif ($curMbr['u_privacy'] == PRIVACY_PRIVATE && !isset($GLOBALS['pguser'])) {
            $totalAnonUsers++;
            $totalPagesAnonUsers += $curMbr['current_tally'];
        } else {
            $username = $curMbr['username'];
            $member_url = "$code_url/stats/members/mdetail.php?id=" . $curMbr['u_id'];
            $t->row(
                "<a href='$member_url'>$username</a>",
                number_format($curMbr['current_tally']),
                date("m/d/Y", $curMbr['date_created'])
            );
        }
    }
    if ($totalAnonUsers > 0) {
        $t->row(
            isset($GLOBALS['pguser']) ? _("Anonymous users") : _("Anonymous/Private users"),
            number_format($totalPagesAnonUsers),
            "&nbsp;"
        );
    }

    $t->end();
}

function showTeamHistory($curTeam, $tally_name)
{
    if (@$_GET['range'] == 'all') {
        $range = 'all';
    } else {
        $range = get_integer_param($_GET, 'range', 30, 1, null);
    }

    $choices = [];
    foreach ([7, 14, 30, 60, 365, 'all'] as $d) {
        $text =
            ($d == 'all')
            ? _('Lifetime')
            : sprintf(_('Last %d Days'), $d);
        $choices[] = "<a href='tdetail.php?tid=".$curTeam['id']."&amp;tally_name=$tally_name&amp;range=$d'>$text</a>";
    }
    $choices_str = join(' | ', $choices);

    $tallyboard_deltas_config = tallyboard_deltas($tally_name, "T", $curTeam['id'], $range);

    $t = new ThemedTable(
        1,
        _("Team Statistics History")
    );

    $t->row(
        "<p style='text-align: center;'>$choices_str</p>" .
        "<div style='display:flex;flex-direction:column;align-items:center'><div id='tallyboard_deltas' style='width:600px;height:300px'></div></div>"
    );

    $t->end();
    echo "<script type='application/javascript'>";
    echo build_svg_graph_inits([["barLineGraph", "tallyboard_deltas", $tallyboard_deltas_config]]);
    echo "</script>";
}

function stripAllString($ttext)
{
    return str_replace(
        ['[b]', '[B]', '[/b]', '[/B]', '[i]', '[I]', '[/i]', '[/I]', '[p]', '[P]', '[/p]', '[/P]', '[lb]', '[LB]'],
        ['<b>', '<b>', '</b>', '</b>', '<i>', '<i>', '</i>', '</i>', '<p>', '<p>', '</p>', '</p>', '<br>', '<br>'],
        html_safe(strip_tags($ttext), ENT_NOQUOTES)
    );
}

function unstripAllString($ttext)
{
    return str_replace(
        ['<b>', '</b>', '<i>', '</i>', '<p>', '</p>', '<br>'],
        ['[b]', '[/b]', '[i]', '[/i]', '[p]', '[/p]', '[lb]'],
        $ttext
    );
}

function showEdit($tname, $ttext, $twebpage, $tedit, $tsid)
{
    global $teamimages;

    $user = User::load_current();

    echo "<form enctype='multipart/form-data' id='mkTeam' name='mkTeam' action='";
    if ($tedit == 1) {
        echo "new_team.php";
    } else {
        echo "tedit.php";
    }
    echo "' method='post' target='_top'>";
    echo "<input type='hidden' name='tsid' value='$tsid'>";
    if (@$teamimages['avatar']) {
        echo "<input type='hidden' name='tavatar' value='".$teamimages['avatar']."'>";
    }
    if (@$teamimages['icon']) {
        echo "<input type='hidden' name='ticon' value='".$teamimages['icon']."'>";
    }

    echo "\n<table class='themed'>";
    echo "<tr><th colspan='2'>";
    if ($tedit != 1) {
        echo _("Edit Team Information");
    } else {
        echo _("New Proofreading Team");
    }
    echo "</th></tr>";

    echo "<tr>";
    echo "<td class='right-align'><b>"._("Team Name")."</b>:</td>";
    echo "<td class='left-align'><input type='text' value='" . attr_safe($tname) . "' name='teamname' size='50' maxlength='50' required>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamname');\">?</a></b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='right-align'><b>"._("Team Webpage")."</b>:</td>";
    echo "<td class='left-align'><input type='text' value='$twebpage' name='teamwebpage' size='50' maxlength='255'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamwebpage');\">?</a></b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='right-align'><b>"._("Team Avatar")."</b>:</td>";
    echo "<td class='left-align'><input type='file' name='teamavatar' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamavatar');\">?</a></b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='right-align'><b>"._("Team Icon")."</b>:</td>";
    echo "<td class='left-align'><input type='file' name='teamicon' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamicon');\">?</a></b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td  class='right-align' style='vertical-align: top;'><b>"._("Team Description")."</b></td>";
    echo "<td  class='left-align' style='vertical-align: top;'><textarea name='text_data' cols='40' rows='6'>" . html_safe(unstripAllString($ttext)) . "</textarea>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamdesc');\">?</a></b></td>";
    echo "</tr>";

    echo "<tr>";
    echo "<td class='center-align' colspan='2'>";
    $user_teams = $user->load_teams();
    if ($tedit == 1 && count($user_teams) >= MAX_USER_TEAM_MEMBERSHIP) {
        echo "<tr><td style='text-align: center;' colspan='2'>"._("You must join the team to create it, which team space would you like to use?")."<br>";
        echo "<select name='otid' title='".attr_safe(_("Team List"))."'>";
        $sql = sprintf(
            "
            SELECT id, teamname
            FROM user_teams
            WHERE id IN (%s)
            ORDER BY teamname
            ",
            implode(",", $user_teams)
        );
        $result = DPDatabase::query($sql);
        while ([$id, $teamname] = mysqli_fetch_row($result)) {
            echo "<option value='$id'>" . html_safe($teamname) . "</option>";
        }
        echo "</select>";
    } else {
        echo "<input type='hidden' name='teamall' value='1'>";
    }
    echo "</td>";
    echo "</tr>";

    if ($tedit == 1) {
        echo "\n<tr><th style='text-align: center;' colspan='2'>";
        echo "<input type='submit' name='mkPreview' value='".attr_safe(_("Preview Team Display"))."'>&nbsp;&nbsp;&nbsp;";
        echo "<input type='submit' name='mkMake' value='".attr_safe(_("Make Team"))."'>&nbsp;&nbsp;&nbsp;";
        echo "<input type='submit' name='mkQuit' value='".attr_safe(_("Quit"))."' formnovalidate>";
        echo "</th></tr>";
    } else {
        echo "\n<tr><th style='text-align: center;' colspan='2'>";
        echo "<input type='submit' name='edPreview' value='".attr_safe(_("Preview Changes"))."'>&nbsp;&nbsp;&nbsp;";
        echo "<input type='submit' name='edMake' value='".attr_safe(_("Save Changes"))."'>&nbsp;&nbsp;&nbsp;";
        echo "<input type='submit' name='edQuit' value='".attr_safe(_("Quit"))."' formnovalidate>";
        echo "</th></tr>";
    }
    echo "</table>";
    echo "</form>";
}


function uploadImages($preview, $tid, $type)
{
    global $team_avatars_dir, $team_icons_dir;
    $teamimages = [
        'avatar' => null,
        'icon' => null,
    ];

    if (!empty($_FILES['teamavatar']['tmp_name']) && ($type == "both" || $type == "avatar")) {
        if (strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".png" || strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".jpg" || strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".gif") {
            if ($_FILES['teamavatar']['size'] > 2097152) {
                echo "<p class='error'>"._("The avatar uploaded is too large.  Please limit the file size to 2MB or less.")."</p>";
                return;
            }
            $avatarID = uniqid("avatar_").substr($_FILES['teamavatar']['name'], -4);
            $upload_avatar_dir = "$team_avatars_dir/$avatarID";
            move_uploaded_file($_FILES['teamavatar']['tmp_name'], $upload_avatar_dir);
            if ($preview != 1) {
                DPDatabase::query("UPDATE user_teams SET avatar='$avatarID' WHERE id = $tid");
            }
            $teamimages['avatar'] = $avatarID;
        } else {
            echo "<p class='error'>"._("The avatar uploaded must be either a JPEG, GIF, or PNG file.")."</p>";
            return;
        }
    }

    if (!empty($_FILES['teamicon']['tmp_name']) && ($type == "both" || $type == "icon")) {
        if (strtolower(substr($_FILES['teamicon']['name'], -4)) == ".png" || strtolower(substr($_FILES['teamicon']['name'], -4)) == ".jpg" || strtolower(substr($_FILES['teamicon']['name'], -4)) == ".gif") {
            if ($_FILES['teamicon']['size'] > 1048576) {
                echo "<p class='error'>"._("The icon uploaded is too large. Please limit the file size to 1MB or less.")."</p>";
                return;
            }
            $iconID = uniqid("icon_").substr($_FILES['teamicon']['name'], -4);
            $upload_icon_dir = "$team_icons_dir/$iconID";
            move_uploaded_file($_FILES['teamicon']['tmp_name'], $upload_icon_dir);
            if ($preview != 1) {
                DPDatabase::query("UPDATE user_teams SET icon='$iconID' WHERE id = $tid");
            }
            $teamimages['icon'] = $iconID;
        } else {
            echo "<p class='error'>"._("The icon uploaded must be either a JPEG, GIF, or PNG file.")."</p>";
            return;
        }
    }

    deleteImages();
    return $teamimages;
}

function deleteImages()
{
    global $team_avatars_dir, $team_icons_dir;
    $oneHourAgo = time() - 600;

    $files_to_always_keep = [
        "avatar_default.png",
        "avatar_default2.png",
        "dp_avatar.png",
        "icon_default.png",
        "icon_default2.png",
        "dp_icon.png",
    ];

    // Delete unused avatars
    $result = DPDatabase::query("SELECT id,avatar FROM user_teams");
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $activeAvatars[$id] = $row['avatar'];
    }

    $avatars = get_filelist($team_avatars_dir);
    $unused_avatars = array_diff($avatars, $activeAvatars, $files_to_always_keep);
    foreach ($unused_avatars as $file) {
        if (filemtime("$team_avatars_dir/$file") <= $oneHourAgo) {
            unlink("$team_avatars_dir/$file");
        }
    }

    //Delete unused icons
    $result = DPDatabase::query("SELECT id,icon FROM user_teams");
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $activeIcons[$id] = $row['icon'];
    }

    $icons = get_filelist($team_icons_dir);
    $unused_icons = array_diff($icons, $activeIcons, $files_to_always_keep);
    foreach ($unused_icons as $file) {
        if (filemtime("$team_icons_dir/$file") <= $oneHourAgo) {
            unlink("$team_icons_dir/$file");
        }
    }
}
