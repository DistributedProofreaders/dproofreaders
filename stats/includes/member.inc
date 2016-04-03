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

function showMbrInformation( $curMbr, $tally_name )
{
    showMbrProfile($curMbr);

    showMbrTallySelector( $curMbr['u_id'], $tally_name );

    if ( is_null($tally_name) )
    {
        showMbrAccess($curMbr);
    }
    else
    {
        showMbrPageStats($curMbr, $tally_name);
        showMbrNeighbors($curMbr, $tally_name);
        $now = time();
        if (($now - $curMbr['date_created']) > 86400)
        {
            showMbrHistory($curMbr, $tally_name);
        }
    }
}

function showXmlButton( $username )
{
    global $code_url;

    $xml_doc_url = "$code_url/stats/members/mbr_xml.php?username=$username";
    $xml_img_url = "$code_url/graphics/xml.gif";
    $xml_img_tag = "<img src='$xml_img_url' style='border: 0; width: 36px; height: 14px; vertical-align: middle' alt='XML'>";
    $xml_button = "<a href='$xml_doc_url'>$xml_img_tag</a>";
    return $xml_button;
}

function showMbrProfile($curMbr) {

    echo "\n";
    echo "<table style='width: 100%;'>";
    echo "\n";
    echo "<tr>";
    echo "<td style='vertical-align: top;' rowspan='2'>";

    showForumProfile($curMbr['username']);

    echo "</td>";

    echo "<td style='vertical-align: top;'>";

    showMbrDpProfile($curMbr);

    echo "</td></tr>";
    
    echo "<td style='vertical-align: top;'>";

    showMbrTeams($curMbr);

    echo "</td></tr></table>";
}

function days_to_now($timestamp)
// return a string like "Today!", or "Yesterday", or "2 days ago" depending on
// a timestamp 
{
    // Ensure that all calls to this function have the same base time.
    static $now = NULL;
    if (is_null($now)) $now = time();

    $days = floor(($now - $timestamp)/86400);
    if ($days == 0)
        return _("Today!");
    elseif ($days == 1)
        return _("Yesterday");
    else
        return sprintf(ngettext(_("%s day ago"), _("%s days ago"), $days),  
            number_format($days)); 
}

function showMbrDpProfile( $curMbr )
{
    global $site_abbreviation;
    // Days since joined:
    $daysInExistenceString = days_to_now($curMbr['date_created']);
    if ($curMbr['t_last_activity'] != 0)
    {
        $daysSinceLastSeen = days_to_now($curMbr['t_last_activity']);
    }

    $t = new ThemedTable(
        2,
        // TRANSLATORS: %s is the site abbreviation
        sprintf(_("%s Profile"),$site_abbreviation)
    );
    $t->set_column_widths( 30, 70 );

    $t->row(
        _("Date Joined"),
        date("m/d/Y", $curMbr['date_created']) . "&nbsp;($daysInExistenceString)"
    );
    $t->row(
        _("Last Seen"),
        ($curMbr['t_last_activity'] == 0)
        ? _("Never")
        : date("m/d/Y", $curMbr['t_last_activity']) . "&nbsp;($daysSinceLastSeen)"
    );
    $t->row(
        _('Roles'),
        showMbrRoles($curMbr)
    );
    $t->row(
        _("Stats Feed"),
        showXmlButton($curMbr['username'])
        );

    $t->end();
}

function showMbrRoles($curMbr)
{
    global $users_ELR_page_tallyboard;
    $current_P_page_tally = $users_ELR_page_tallyboard->get_current_tally( $curMbr['u_id'] );

    $settings =& Settings::get_Settings($curMbr['username']);

    $roles = array();

    if ($current_P_page_tally < 50)
    {
        $roles[] = array( 'newbie', _("Newbie"), 25 );
    }
    else
    {
        $roles[] = array( 'proofer', _("Proofreader"), 25 );
    }

    // Formatter

    if ($curMbr['manager'] == "yes")
    {
        $roles[] = array( 'pm', _("Project Manager"), 50 );
    }

    global $Round_for_round_id_;
    foreach ( $Round_for_round_id_ as $round )
    {
        if ( $round->is_a_mentor_round() )
        {
            $access_id = $round->id . "_mentor.access";
            if ($settings->get_boolean($access_id))
            {
                $label = sprintf(_("Mentor in round %s"), $round->id);
                $roles[] = array( 'mentor', $label, 25 );
            }
        }
    }

    if ($settings->get_boolean('PP.access'))
    {
        $roles[] = array( 'pp', _("Post-Processor"), 25 );
    }

    if ($settings->get_boolean('DU.access'))
    {
        $roles[] = array( 'du', _("Direct Uploader"), 25 );
    }

    if ($settings->get_boolean('PPV.access'))
    {
        $roles[] = array( 'ppv', _("Post-Processing Verifier"), 25 );
    }

    if ($settings->get_boolean('proj_facilitator'))
    {
        $roles[] = array( 'pf', _("Project Facilitator"), 25 );
    }

    if ($curMbr['sitemanager'] == "yes")
    {
        $roles[] = array( 'sa', _("Site Administrator"), 25 );
    }

    $mbrStatus = "";
    foreach ( $roles as $role )
    {
        global $code_url;
        list($id, $label, $icon_width) = $role;
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

function showForumProfile($username) {
    global $site_abbreviation;

    $bb_user = get_forum_user_details($username);

    $view_url = attr_safe(get_url_to_view_profile($bb_user["id"]));
    $text = _('View');
    $viewlink = "&nbsp;[<a href='$view_url'>$text</a>]";

    if (isset($GLOBALS['pguser']) && $bb_user["username"] == $GLOBALS['pguser'])
    {
        $edit_url = get_url_to_edit_profile();
        $text = _('Edit');
        $editlink = "&nbsp;[<a href='$edit_url'>$text</a>]";
    }
    else
    {
        $editlink = '';
    }

    $t = new ThemedTable(
        2,
        _("Forum Profile") . $viewlink . $editlink
    );

    $avatar = get_url_for_user_avatar($username);

    if ($avatar == NULL)
    {
        $rhs = _("(none)");
    }
    else
    {
        $rhs = "<img style='border: 0;' src='$avatar' alt='avatar'>";
    }
    $t->row(
        _("Avatar"),
        $rhs
    );

    if ($bb_user["rank"] != 0)
    {
        $bb_title = get_forum_rank_title($bb_user["rank"]);
        $t->row(
               '',
               $bb_title
        );
    }

    $t->hr( 90 );

    $t->row(
        _("Posts"),
        $bb_user["posts"]
    );

    if (!empty($bb_user["from"]))      { $t->row( _("Location"),   $bb_user["from"] ); }
    if (!empty($bb_user["occ"]))       { $t->row( _("Occupation"), $bb_user["occ"] ); }
    if (!empty($bb_user["interests"])) { $t->row( _("Interests"),  $bb_user["interests"] ); }
    if (!empty($bb_user["website"]))   { $t->row( _("Website"),    "<a href='" . $bb_user["website"] . "'>".$bb_user["website"]."</a>" ); }

    $t->hr( 90 );

    $contact_data = array(
        array(
            !empty($bb_user["viewemail"]),
            _("E-mail"),
            $bb_user["email"],
            "mailto:" . $bb_user["email"],
            ''
        ),
        array(
            TRUE,
            _("Private Message"),
            $username,
            attr_safe(get_url_to_compose_message_to_user($username)),
            ''
        ),
        array(
            !empty($bb_user["aim"]),
            "AIM",
            $bb_user["aim"],
            "aim:goim?screenname=".$bb_user["aim"]."&amp;message=Hello!+Are+you+there?++I+am+IMing+you+from+$site_abbreviation",
            ''
        ),
        array(
            !empty($bb_user["msnm"]),
            "MSN",
            $bb_user["msnm"],
            "javascript:alert(\"" . $bb_user["msnm"] . "\")",
            ''
        ),
        array(
            !empty($bb_user["yim"]),
            _("Yahoo Message"),
            $bb_user["yim"],
            "http://edit.yahoo.com/config/send_webmesg?.target=" . $bb_user["yim"] . "&amp;.src=pg",
            "_new"
        ),
        array(
            !empty($bb_user["icq"]),
            "ICQ",
            $bb_user["icq"],
            "http://wwp.icq.com/scripts/search.dll?to=" . $bb_user["icq"],
            "_new"
        ),
    );

    foreach ( $contact_data as $contact )
    {
        list( $condition, $label, $value, $url, $target ) = $contact;

        if (!$condition) continue;

        $target_str = ( empty($target) ? '' : " target='$target'" );

        $t->row(
            $label,
            "<a href='$url'$target_str>$value</a>"
        );
    }

    $t->end();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showMbrAccess( $curMbr )
{
    if (!user_can_see_user_access_chart_of($curMbr['username'])) return;

    echo "<i>(";
    echo _('This chart is not generally visible.');
    echo ")</i><br>\n";

    show_user_access_chart( $curMbr['username'] );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showMbrTallySelector( $u_id, $tally_name )
{
    global $page_tally_names;

    $choices = '';
    foreach ( $page_tally_names as $t_name => $t_title )
    {
        if (!empty($choices))
        {
            $choices .= " | ";
        }

        $text = $t_name;
        if ($t_name != $tally_name)
        {
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

    if ( is_null($tally_name) ) return;

    $tally_title = $page_tally_names[$tally_name];
    echo "\n";
    echo "<p style='text-align: center;'>";
    echo $tally_title;
    echo "</p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showMbrPageStats( $curMbr, $tally_name )
{
    $users_tallyboard = new TallyBoard( $tally_name, 'U' );

    $vitals = $users_tallyboard->get_vitals( $curMbr['u_id'] );

    $now = time();

    // There are two special cases needed here:
    // Firstly, the R* tally should span the time period from date_created to the site R* apocalypse
    // Secondly, the P1 tally should start from the site P genesis, or date_created, whichever is later.

    $site_R_apocalypse = 1117576800;
    $site_P_genesis = 1117720800;

    if ($tally_name == "R*")
    {
        $firstWorkInRound = $curMbr['date_created'];
        $daysInRoundInteger = floor(($site_R_apocalypse - $firstWorkInRound)/86400);
    }
    elseif ($tally_name == "P1")
    {
        $firstWorkInRound = ($curMbr['date_created'] > $site_P_genesis) ? $curMbr['date_created'] : $site_P_genesis;
        $daysInRoundInteger = floor(($now - $firstWorkInRound)/86400);
    }
    else
    {
        $firstWorkInRound = get_first_granted_date($curMbr['username'],$tally_name);
        $daysInRoundInteger = floor(($now - $firstWorkInRound)/86400);
    }

    $daysInRoundString = sprintf(_("%s days"), 
        number_format($daysInRoundInteger)); // '1,234 days' (not localized)

    $bestDayTime = date("M. jS, Y", ($vitals->best_delta_timestamp-1));

    if ($daysInRoundInteger > 0) {
        $daily_Average = $vitals->current_tally/$daysInRoundInteger;
    } else {
        $daily_Average = 0;
    }

    $t = new ThemedTable(
        2,
        _("Page Statistics")
    );

    if ($firstWorkInRound != 0)
    {
        $round_row = date("m/d/Y", $firstWorkInRound) . "&nbsp;($daysInRoundString)";
    }
    else
    {
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
        . "&nbsp;<i>(" . date("M. jS, Y", $vitals->best_rank_timestamp-1) . ")</i>"
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

function showMbrNeighbors($curMbr, $tally_name) {
    global $site_abbreviation;
    $now = time();

    $t = new ThemedTable(
        4,
        _("Neighbors"),
        array('striped' => TRUE)
    );
    $t->set_column_widths( 15, 28, 28, 29 );
    $t->column_headers(
        _("Rank"),
        _("Username"),
        sprintf(_("Date Joined %s"),$site_abbreviation),
        _("Overall Pages Completed")
    );

    $neighbors =
        user_get_page_tally_neighborhood(
            $tally_name, $curMbr['username'], 4 );
    foreach ( $neighbors as $rel_posn => $neighbor )
    {
        $rank = $neighbor->get_current_page_tally_rank();
        $pagescompleted = number_format($neighbor->get_current_page_tally());

        if (!$neighbor->is_anonymized()) {

            $u_id = $neighbor->get_u_id();
            $username = $neighbor->get_username();
            $date_created = $neighbor->get_date_joined();

            $username_html = "<a href='".$GLOBALS['code_url']."/stats/members/mdetail.php?id=$u_id&amp;tally_name=$tally_name'>$username</a>";
            $day_html = date("m/d/Y", $date_created)." <i>("
                . sprintf(_("%s days"), number_format(floor(($now - $date_created)/86400)))
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

function showMbrTeams($curMbr) {

    if (empty($curMbr['team_1']) && empty($curMbr['team_2']) && empty($curMbr['team_3'])) { return; }

    $t = new ThemedTable(
        2,
        _("Teams"),
        array('striped' => TRUE)
    );
    $t->set_column_widths( 50, 50 );
    $t->column_headers(
        _("Team Name"),
        _("Active Members")
    );

    $result = mysql_query("
        SELECT *
        FROM user_teams
        WHERE id IN ({$curMbr['team_1']}, {$curMbr['team_2']}, {$curMbr['team_3']})
    ") or die(mysql_error());

    while ($row = mysql_fetch_assoc($result)) {
        $url = $GLOBALS['code_url']."/stats/teams/tdetail.php?tid=".$row['id'];
        $t->row(
            "<a href='$url'>".$row['teamname']."</a>",
            sprintf(_("%s members"), number_format($row['active_members']))
        );
    }

    $t->end();
}

function showMbrHistory($curMbr, $tally_name) {

    if (@$_GET['range'] == 'all') {
        $range = 'all';
    } else {
        $range = get_integer_param($_GET, 'range', 30, 1, null);
    }

    $choices = array();
    $u_id = $curMbr['u_id'];
    foreach( array( 7, 14, 30, 60, 365, 'all' ) as $d )
    {
        $text =
            ($d == 'all')
            ? _('Lifetime')
            : sprintf( _('Last %d Days'), $d );
        $choices[] = "<a href='mdetail.php?id=$u_id&amp;tally_name=$tally_name&amp;range=$d'>$text</a>";
    }
    $choices_str = join( $choices, ' | ' );

    $image_url = $GLOBALS['code_url']."/stats/jpgraph_files/tallyboard_deltas.php?tally_name=$tally_name&amp;holder_type=U&amp;holder_id=$u_id&amp;days_back=$range";

    $t = new ThemedTable(
        1,
        _("History")
    );
    $t->row(
        "<p style='text-align: center;'>$choices_str</p>" .
        "<p style='text-align: center;'><img src='$image_url' style='width: 600px; height: 300px;'></p>"
    );
    $t->end();
}

// vim: sw=4 ts=4 expandtab
?>
