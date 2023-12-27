<?php
//A file full of member statistic functions

include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'page_tally.inc');
include_once($relPath.'ThemedTable.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'access_log.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'../stats/includes/common.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'graph_data.inc');

function showMbrInformation($user, $tally_name)
{
    showMbrProfile($user);

    showMbrTallySelector($user->u_id, $tally_name);

    if (is_null($tally_name)) {
        showMbrAccess($user);
    } else {
        showMbrPageStats($user, $tally_name);
        showMbrNeighbors($user, $tally_name);
        $now = time();
        if (($now - $user->date_created) > 86400) {
            showMbrHistory($user, $tally_name);
        }
    }
}

function showXmlButton($username)
{
    global $code_url;

    $xml_doc_url = "$code_url/stats/members/mbr_xml.php?username=$username";
    $xml_img_url = "$code_url/graphics/xml.gif";
    $xml_img_tag = "<img src='$xml_img_url' style='border: 0; width: 36px; height: 14px; vertical-align: middle' alt='XML'>";
    $xml_button = "<a href='$xml_doc_url'>$xml_img_tag</a>";
    return $xml_button;
}

function showMbrProfile($user)
{
    echo "\n";
    echo "<table style='width: 100%;'>";
    echo "\n";
    echo "<tr>";
    echo "<td style='vertical-align: top;' rowspan='2'>";

    showForumProfile($user->username);

    echo "</td>";

    echo "<td style='vertical-align: top;'>";

    showMbrDpProfile($user);

    echo "</td></tr>";

    echo "<tr><td style='vertical-align: top;'>";

    showMbrTeams($user);

    echo "</td></tr></table>";
}

/**
 * Return a string like "Today!", or "Yesterday", or "2 days ago" depending on
 * a timestamp
 */
function days_to_now($timestamp)
{
    // Ensure that all calls to this function have the same base time.
    static $now = null;
    if (is_null($now)) {
        $now = time();
    }

    $days = floor(($now - $timestamp) / 86400);
    if ($days == 0) {
        return _("Today!");
    } elseif ($days == 1) {
        return _("Yesterday");
    } else {
        return sprintf(
            ngettext(_("%s day ago"), _("%s days ago"), $days),
            number_format($days)
        );
    }
}

function showMbrDpProfile($user)
{
    global $site_abbreviation;
    // Days since joined:
    $daysInExistenceString = days_to_now($user->date_created);
    if ($user->t_last_activity != 0) {
        $daysSinceLastSeen = days_to_now($user->t_last_activity);
    }

    $t = new ThemedTable(
        2,
        // TRANSLATORS: %s is the site abbreviation
        sprintf(_("%s Profile"), $site_abbreviation)
    );
    $t->set_column_widths(30, 70);

    $t->row(
        _("Date Joined"),
        date("m/d/Y", $user->date_created) . "&nbsp;($daysInExistenceString)"
    );
    $t->row(
        _("Last Seen"),
        ($user->t_last_activity == 0)
        ? _("Never")
        : date("m/d/Y", $user->t_last_activity) . "&nbsp;($daysSinceLastSeen)"
    );
    $t->row(
        _('Roles'),
        showMbrRoles($user)
    );
    $t->row(
        _("Stats Feed"),
        showXmlButton($user->username)
    );

    $t->end();
}

function showMbrRoles($user)
{
    [$users_ELR_page_tallyboard, ] = get_ELR_tallyboards();
    $current_P_page_tally = $users_ELR_page_tallyboard->get_current_tally($user->u_id);

    $settings = & Settings::get_Settings($user->username);

    $roles = [];

    if ($current_P_page_tally < 50) {
        $roles[] = ['newbie', _("Newbie"), 25];
    } else {
        $roles[] = ['proofer', _("Proofreader"), 25];
    }

    if ($settings->get_boolean('manager')) {
        $roles[] = ['pm', _("Project Manager"), 50];
    }

    global $Round_for_round_id_;
    foreach ($Round_for_round_id_ as $round) {
        if ($round->is_a_mentor_round()) {
            $access_id = $round->id . "_mentor.access";
            if ($settings->get_boolean($access_id)) {
                $label = sprintf(_("Mentor in round %s"), $round->id);
                $roles[] = ['mentor', $label, 25];
            }
        }
    }

    if ($settings->get_boolean('PP.access')) {
        $roles[] = ['pp', _("Post-Processor"), 25];
    }

    if ($settings->get_boolean('DU.access')) {
        $roles[] = ['du', _("Direct Uploader"), 25];
    }

    if ($settings->get_boolean('PPV.access')) {
        $roles[] = ['ppv', _("Post-Processing Verifier"), 25];
    }

    if ($settings->get_boolean('proj_facilitator')) {
        $roles[] = ['pf', _("Project Facilitator"), 25];
    }

    if ($settings->get_boolean('sitemanager')) {
        $roles[] = ['sa', _("Site Administrator"), 25];
    }

    $mbrStatus = "";
    foreach ($roles as $role) {
        global $code_url;
        [$id, $label, $icon_width] = $role;
        $icon_url = "$code_url/graphics/icon_$id.png";
        $mbrStatus .= "<img
            src='$icon_url'
            alt='" . attr_safe($label) . "' title='" . attr_safe($label) . "'
            style='width: {$icon_width}px; height: 25px; border: 0; vertical-align: middle;'
        >&nbsp;";
    }
    $mbrStatus .= "&nbsp;";

    return $mbrStatus;
}

function showForumProfile($username)
{
    global $site_abbreviation;

    $bb_user = get_forum_user_details($username);

    $view_url = attr_safe(get_url_to_view_profile($bb_user["id"]));
    $text = _('View');
    $viewlink = "&nbsp;[<a href='$view_url'>$text</a>]";

    if (isset($GLOBALS['pguser']) && $bb_user["username"] == $GLOBALS['pguser']) {
        $edit_url = get_url_to_edit_profile();
        $text = _('Edit');
        $editlink = "&nbsp;[<a href='$edit_url'>$text</a>]";
    } else {
        $editlink = '';
    }

    $t = new ThemedTable(
        2,
        _("Forum Profile") . $viewlink . $editlink
    );

    $avatar = get_url_for_user_avatar($username);

    if ($avatar == null) {
        $rhs = "<i>" . pgettext("no avatar", "none") . "</i>";
    } else {
        $rhs = "<img style='border: 0;' src='$avatar' alt='avatar'>";
    }
    $t->row(
        _("Avatar"),
        $rhs
    );

    if ($bb_user["rank"] != 0) {
        $bb_title = get_forum_rank_title($bb_user["rank"]);
        $t->row(
            '',
            $bb_title
        );
    }

    $t->hr(90);

    $t->row(
        _("Posts"),
        $bb_user["posts"]
    );

    if (!empty($bb_user["from"])) {
        $t->row(_("Location"), $bb_user["from"]);
    }
    if (!empty($bb_user["occ"])) {
        $t->row(_("Occupation"), $bb_user["occ"]);
    }
    if (!empty($bb_user["interests"])) {
        $t->row(_("Interests"), $bb_user["interests"]);
    }
    if (!empty($bb_user["website"])) {
        $t->row(_("Website"), "<a href='" . $bb_user["website"] . "'>".$bb_user["website"]."</a>");
    }

    $t->hr(90);

    $email_data = [];
    if ($bb_user["viewemail"]) {
        $email_data = [[
            _("E-mail"),
            $bb_user["email"],
            "mailto:" . $bb_user["email"],
            '',
        ]];
    }
    $contact_data = [
        [
            _("Private Message"),
            $username,
            attr_safe(get_url_to_compose_message_to_user($username)),
            '',
        ],
        [
            "AIM",
            $bb_user["aim"],
            null,
            '',
        ],
        [
            "MSN",
            $bb_user["msnm"],
            null,
            '',
        ],
        [
            _("Yahoo Message"),
            $bb_user["yim"],
            "ymsgr:sendim?" . $bb_user["yim"],
            '',
        ],
        [
            "ICQ",
            $bb_user["icq"],
            "https://www.icq.com/people/" . $bb_user["icq"] . "/",
            "_new",
        ],
        [
            "Facebook",
            @$bb_user["facebook"],
            "https://www.facebook.com/" . @$bb_user["facebook"],
            "_new",
        ],
        [
            "Twitter",
            @$bb_user["twitter"],
            "https://www.twitter.com/" . @$bb_user["twitter"],
            "_new",
        ],
        [
            "Skype",
            @$bb_user["skype"],
            "skype:" . @$bb_user["skype"] . "?userinfo",
            '',
        ],
        [
            "YouTube",
            @$bb_user["youtube"],
            "http://youtube.com/user/" . @$bb_user["youtube"],
            "_new",
        ],
    ];

    $output_data = $email_data + $contact_data;

    foreach ($output_data as $contact) {
        [$label, $value, $url, $target] = $contact;

        if (!$value) {
            continue;
        }

        $output = $value;
        if ($url) {
            $target_str = (empty($target) ? '' : " target='$target'");
            $output = "<a href='$url'$target_str>$value</a>";
        }

        $t->row(
            $label,
            $output
        );
    }

    $t->end();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showMbrAccess($user)
{
    if (!user_can_see_user_access_chart_of($user->username)) {
        return;
    }

    echo "<i>(";
    echo _('This chart is not generally visible.');
    echo ")</i><br>\n";

    show_user_access_chart($user->username);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showMbrTallySelector($u_id, $tally_name)
{
    $choices = '';
    $page_tally_names = get_page_tally_names();
    foreach ($page_tally_names as $t_name => $t_title) {
        if (!empty($choices)) {
            $choices .= " | ";
        }

        $text = $t_name;
        if ($t_name != $tally_name) {
            $text = "<a href='mdetail.php?id=$u_id&amp;tally_name=$t_name'>$text</a>";
        }
        $choices .= $text;
    }
    echo "\n";
    echo "<p style='text-align: center;'>";
    echo _("Display user stats for different rounds:") . ' ' . $choices;
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

function showMbrPageStats($user, $tally_name)
{
    $users_tallyboard = new TallyBoard($tally_name, 'U');

    $vitals = $users_tallyboard->get_vitals($user->u_id);

    $now = time();

    // There are two special cases needed here:
    // Firstly, the R* tally should span the time period from date_created to the site R* apocalypse
    // Secondly, the P1 tally should start from the site P genesis, or date_created, whichever is later.

    $site_R_apocalypse = 1117576800;
    $site_P_genesis = 1117720800;

    if ($tally_name == "R*") {
        $firstWorkInRound = $user->date_created;
        $daysInRoundInteger = floor(($site_R_apocalypse - $firstWorkInRound) / 86400);
    } elseif ($tally_name == "P1") {
        $firstWorkInRound = ($user->date_created > $site_P_genesis) ? $user->date_created : $site_P_genesis;
        $daysInRoundInteger = floor(($now - $firstWorkInRound) / 86400);
    } else {
        $firstWorkInRound = get_first_granted_date($user->username, $tally_name);
        if ($firstWorkInRound) {
            $daysInRoundInteger = floor(($now - $firstWorkInRound) / 86400);
        } else {
            $daysInRoundInteger = 0;
        }
    }

    $daysInRoundString = sprintf(
        _("%s days"),
        number_format($daysInRoundInteger)
    ); // '1,234 days' (not localized)

    $bestDayTime = date("M. jS, Y", ($vitals->best_delta_timestamp - 1));

    if ($daysInRoundInteger > 0) {
        $daily_Average = $vitals->current_tally / $daysInRoundInteger;
    } else {
        $daily_Average = 0;
    }

    $t = new ThemedTable(
        2,
        _("Page Statistics")
    );

    if ($firstWorkInRound != 0) {
        $round_row = date("m/d/Y", $firstWorkInRound) . "&nbsp;($daysInRoundString)";
    } else {
        $round_row = '--';
    }

    $t->row(
        _("This Round"),
        $round_row
    );
    $t->row(
        _("Total Pages"),
        number_format($vitals->current_tally)
    );
    $t->row(
        _("Overall Rank"),
        number_format($vitals->current_rank)
    );
    $t->row(
        _("Highest Rank"),
        number_format($vitals->best_rank)
        . "&nbsp;<i>(" . date("M. jS, Y", $vitals->best_rank_timestamp - 1) . ")</i>"
        . "&nbsp;" . showChangeInRank($vitals->best_rank, $vitals->current_rank)
    );
    $t->row(
        _("Best Day Ever"),
        number_format($vitals->best_delta) . " " . _("pages")
        . " <i>(" . $bestDayTime . ")</i>"
    );
    $t->row(
        _("Daily Average"),
        number_format($daily_Average) . " " . _("pages")
    );

    $t->end();
}

function showMbrNeighbors($user, $tally_name)
{
    global $site_abbreviation;
    $now = time();

    $t = new ThemedTable(
        4,
        _("Neighbors"),
        ['theme_striped' => true]
    );
    $t->set_column_widths(15, 28, 28, 29);
    $t->column_headers(
        _("Rank"),
        _("Username"),
        sprintf(_("Date Joined %s"), $site_abbreviation),
        _("Overall Pages Completed")
    );

    $neighbors =
        user_get_page_tally_neighborhood(
            $tally_name,
            $user->username,
            max($user->u_neigh, 4)
        );
    foreach ($neighbors as $rel_posn => $neighbor) {
        $rank = $neighbor->get_current_page_tally_rank();
        $pagescompleted = number_format($neighbor->get_current_page_tally());

        if (!$neighbor->is_anonymized()) {
            $u_id = $neighbor->get_u_id();
            $username = $neighbor->get_username();
            $date_created = $neighbor->get_date_joined();

            $username_html = "<a href='".$GLOBALS['code_url']."/stats/members/mdetail.php?id=$u_id&amp;tally_name=$tally_name'>$username</a>";
            $day_html = date("m/d/Y", $date_created)." <i>("
                . sprintf(_("%s days"), number_format(floor(($now - $date_created) / 86400)))
                . ")</i>";
        } else {
            $username_html = "Anonymous";
            $day_html = "--/--/-- <i>(" . sprintf(_("%s days"), '---') .")</i>";
        }
        $t->row(
            $rank,
            $username_html,
            $day_html,
            $pagescompleted
        );
    }

    $t->end();
}

function showMbrTeams($user)
{
    global $code_url;

    $user_teams = $user->load_teams();
    if (!$user_teams) {
        return;
    }

    $t = new ThemedTable(
        2,
        _("Teams"),
        ['theme_striped' => true]
    );
    $t->set_column_widths(50, 50);
    $t->column_headers(
        _("Team Name"),
        _("Active Members")
    );

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

    while ([$tid, $teamname] = mysqli_fetch_row($result)) {
        $url = "$code_url/stats/teams/tdetail.php?tid=$tid";
        $t->row(
            "<a href='$url'>" . html_safe($teamname) . "</a>",
            sprintf(_("%s members"), number_format(Team::active_member_count($tid)))
        );
    }

    $t->end();
}

function showMbrHistory($user, $tally_name)
{
    if (@$_GET['range'] == 'all') {
        $range = 'all';
    } else {
        $range = get_integer_param($_GET, 'range', 30, 1, null);
    }

    $choices = [];
    $u_id = $user->u_id;
    foreach ([7, 14, 30, 60, 365, 'all'] as $d) {
        $text =
            ($d == 'all')
            ? _('Lifetime')
            : sprintf(_('Last %d Days'), $d);
        $choices[] = "<a href='mdetail.php?id=$u_id&amp;tally_name=$tally_name&amp;range=$d'>$text</a>";
    }
    $choices_str = join(' | ', $choices);

    $tallyboard_deltas_config = tallyboard_deltas($tally_name, "U", $u_id, $range);

    $t = new ThemedTable(
        1,
        _("History")
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
