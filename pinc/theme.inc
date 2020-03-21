<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'html_page_common.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'pg.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'list_projects.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'languages.inc');
include_once($relPath.'post_processing.inc'); // count_pp_projects_past_threshold()
include_once($relPath.'misc.inc'); // endswith(), get_enumerated_param(), attr_safe(), endswith()
include_once($relPath.'faq.inc');

// Backwards compatibility function. To be removed once all
// pages transition to output_header()
function theme($nameofpage, $location, $extra_args = array())
{
    global $no_stats;
    if ($location == "header") {
        output_header($nameofpage, !isset($no_stats), $extra_args);
    } else if ($location == "footer") {
        // No action is necessary here, because output_header()
        // registers output_footer() to be called automatically
        // by PHP before the page is finished.
    }
}

// Emit the opening markup and theme tags, and register a callback
// to close the page after the caller finishes output.
function output_header($nameofpage, $show_statsbar = True, $extra_args = array())
{
    global $pguser, $userP;

    // Display stats bar on left (1) or right (0)
    $statsbar_align = isset($pguser) ? $userP['u_align'] : 0;

    // The theme layout consists of:
    // * Two full width headers at the top,
    // * A table with several columns containing sidebars & main body content
    // * A full width footer at the bottom.

    // All our themes begin with the initial markup, logo and navbar, and open a
    // table for further layout.
    output_html_header($nameofpage, $extra_args, $show_statsbar);
    html_logobar();
    echo "\n<div id='page-container'>\n";
    echo "<div id='page-body-container'>\n";

    if ($show_statsbar) {
        if ($statsbar_align == 1) {
            // If we're displaying the stats bar on the left, then emit:
            // * A 25% width column with the stats bar in it
            // * A 1% column with the rounded corner
            // * A 74% width column for the main page content, and return.
            echo "<div id='statsbar'>\n";
            html_statsbar($nameofpage);
            echo "</div>\n";
            echo "<div id='rounded-corner' class='left-statsbar'></div>\n";
            echo "<div id='content-container' class='left-statsbar'>\n";
        } else {
            // If the stats bar is on the right then emit:
            // * A 74% width column for the main page content, and return.
            echo "<div id='content-container' class='right-statsbar'>\n";
        }
    } else {
        // If we've displaying no stats bar then emit:
        // * A 100% width column for the main page content, and return.
        echo "<div id='content-container'>\n";
    }

    // Call output_footer when the main page content has been emitted so we can
    // close the table and emit the footer.
    register_shutdown_function('output_footer', $nameofpage, $show_statsbar);
}

function output_footer($nameofpage, $show_statsbar = True) {
    global $code_url, $site_name, $PAGE_START_TIME;
    global $pguser, $userP;

    // Display stats bar on left (1) or right (0)
    $statsbar_align = isset($pguser) ? $userP['u_align'] : 0;

    // The main page content has been emited so close the main page content column.
    echo "</div>\n";
    if ($show_statsbar && $statsbar_align == 0) {
        // If we're displaying the the stats bar on the right, then emit:
        // * A 13px column with the rounded corner
        // * A 25% width column with the stats bar in it
        echo "<div id='rounded-corner' class='right-statsbar'></div>\n";
        echo "<div id='statsbar'>\n";
        html_statsbar($nameofpage);
        echo "</div>\n";
    }
    // Finally close the table, and emit the footer.
    echo "</div></div>\n";

    // Now the page footer
    $totaltime = microtime(true) - $PAGE_START_TIME;

    //Bottom Copyright Text
    echo "\n<div id='footer'>\n";
    echo "<p>\n";
    echo "<small>\n";

    echo "&copy;$site_name";
    echo " &middot; ";
    echo sprintf(_("Page built at %s in %0.3fs"), strftime("%Y-%m-%d %R %Z"), $totaltime);

    echo " &middot; ";
    echo "<a href='$code_url/credits.php'>";
    echo _("Credits");
    echo "</a>";

    echo " &middot; ";
    echo "<a href='$code_url/tasks.php'>";
    echo _("Report a Bug");
    echo "</a>";

    echo "</small>\n";
    echo "</p>\n";
    echo "</div>\n";

    output_html_footer();
}

function html_logobar() {
    global $code_url, $site_name;
    global $maintenance, $testing, $alert_message;

    echo "\n<div id='header'>\n";
    echo "<div id='logo'>\n";
    echo "<a href='$code_url'><img src='$code_url/graphics/dp-logo-360x68px.png' width='360' height='68' alt='$site_name'></a>\n";
    echo "</div>\n";

    echo "<div id='titles-preserved'><p>\n";

    $psd = get_project_status_descriptor('posted');

    if (0) {
        // show number of projects posted to PG
        $result = mysqli_query(DPDatabase::get_connection(),  "
            SELECT COUNT(*) as numproj
            FROM projects
            WHERE $psd->state_selector
        ");
        $row = mysqli_fetch_assoc($result);
        echo "<span class='sans-serif'><b>";
        echo sprintf(
            _('%s projects completed!'),
            number_format($row["numproj"]));
        echo "</b></span>\n";
    }

    if (1) {
        // show number of books posted to PG
        // (takes into account that some books are processed as several projects)

        $result = mysqli_query(DPDatabase::get_connection(),  "
            SELECT COUNT(distinct postednum) as numproj
            FROM projects
            WHERE $psd->state_selector
        ");
        $row = mysqli_fetch_assoc($result);
        echo "<span class='sans-serif'><b>";
        echo sprintf(
            _('%s titles preserved for the world!'),
            number_format($row["numproj"]));
        echo "</b></span>\n";
        show_completed_projects(TRUE);
    }

    echo "</p></div>\n";
    echo "</div>\n";

    html_navbar();

    if($testing || $maintenance || $alert_message)
    {
        $id = 'alertbar';
        $messages = [];
        if($testing)
        {
            $messages[] = _("This is a test site!");
            $id = 'testbar';
        }
        if($maintenance)
        {
            $messages[] = "<span class='error'>" . _("Site is in maintenance mode.") . "</span>";
            $id = 'testbar';
        }
        if($alert_message)
            $messages[] = $alert_message;
        echo "<div id='$id-outer'>";
        echo "<div id='$id'>";
        echo "<p><span>" . implode(" | ", $messages) . "</span></p>";
        echo "</div>";
        echo "</div>";
    }
}

function html_navbar()
// Display the horizontal bar containing links to various important points
// within the site (and the login form if the user is not logged in).
{
    global $code_url, $site_abbreviation, $pguser, $forums_url, $blog_url, $wiki_url;

    echo "<div id='navbar-outer'>";
    echo "<div id='navbar'>";

    //Code to display login form if not logged in
    if (!isset($pguser)) {
        $links = array();
        $links[] = array('text' => $site_abbreviation, 'url' => "default.php");
        if ( !empty($blog_url) )
            $links[] = array('text' => _("Blog"), 'url' => $blog_url);
        $links[] = array('text' => _("Forums"), 'url' => $forums_url);
        echo "<span id='navbar-left'>";
        echo headerbar_text_array($links);
        echo "</span>\n";

        echo "<div id='navbar-login'>";

        $login_form = "<form action='$code_url/accounts/login.php' method='post'><div>\n";

        // We pass a hidden 'destination' parameter to login.php to indicate
        // where the user should be sent (preferably, where they want to go)
        // after a successful login.
        //
        // If a 'destination' parameter has been passed to the current script,
        // we simply pass that on. Note that the pass-in can happen:
        // -- via the query string of a GET request (after a redirect), or
        // -- via the body of a POST request (after a failed login attempt).
        // We handle both these cases by checking $_REQUEST['destination'].
        //
        // Otherwise (if a destination hasn't been passed in), we use the URI
        // of the current request, so that after logging in, they are returned
        // to (the "logged-in" version of) the page that's currently being built.
        //
        $destination = array_get($_REQUEST, "destination", $_SERVER["REQUEST_URI"]);

        $login_form .= "<input type='hidden' name='destination' value='" . attr_safe($destination) . "'>";

        // login link text
        // TRANSLATORS: ID is an abbreviation for username in the navbar
        $login_form .= "<label for='loginform-userNM'>" . _("ID") . ":</label> ";
        $login_form .= "<input type='text' id='loginform-userNM' name='userNM' style='width: 7em;' required>&nbsp;";
        $login_form .= "<label for='loginform-userPW'>" . _("Password") . ":</label> ";
        $login_form .= "<input type='password' id='loginform-userPW' name='userPW' style='width: 7em;' required>&nbsp;";
        $login_form .= "<input type='submit' value='" . _("Sign In") ."'>";
        $login_form .= "</div></form>";

        $links=array();
        $links[] = array('text' => $login_form);
        $links[] = array('text' => _('Register'), 'url' => "accounts/addproofer.php");
        $links[] = array('text' => _('Help'), 'url' => get_faq_url("faq_central.php"));
        echo headerbar_text_array($links);
        echo "</div>\n";
    } else {
        //Code to display if the user is logged in

        // Fetch some values from the database for use in links later
        $numofPMs = get_number_of_unread_messages($pguser);

        // left side
        echo "<span id='navbar-left'>";
        echo headerbar_text_array(array(array('text' => $site_abbreviation, 'url' => "default.php")));

        echo " | ";

        $links = array();
        $links[] = array('text' => _("Activity Hub"), 'url' => "activity_hub.php");

        // use white-space: nowrap; to keep the rounds within []s together
        $activities_string = "<span style='white-space: nowrap;'>[ ";
        if(get_pages_proofed_maybe_simulated($pguser) < 100)
            $activities_string .= "<span class='text'>" . _("Activities") . ": </span>";

        $activities_string .= headerbar_text_array(get_activity_links());
        $activities_string .= " ]</span>";
        $links[] = array('text' => $activities_string);

        $links[] = array('text' => _("My Projects"), 'url' => "tools/proofers/my_projects.php");

        $links[] = array('text' => _("Project Search"), 'url' => "tools/search.php");

        echo headerbar_text_array($links);

        echo "</span>";

        // right side
        // white-space: nowrap is required to have the right side shift
        // under the left side if the window isn't large enough to have both
        // on the same line.
        echo "<span id='navbar-right'>";
        $links = array();
        $inbox_text = _("Inbox");
        if($numofPMs > 0) $inbox_text .= " " . sprintf(_("(%s unread)"),$numofPMs);
        $links[] = array('text' => $inbox_text, 'url' => get_url_for_inbox());

        $links[] = array('text' => _("Forums"), 'url' => $forums_url);
        if(lang_forum())
        {
            $links[] = array('text' => _("Your language forums"), 'url' => $forums_url."/".lang_forum());
        }

        if ( !empty($blog_url) )
        {
            $links[] = array('text' => _("Blog"), 'url' => $blog_url);
        }

        if ( !empty($wiki_url) )
        {
            $links[] = array('text' => _("Wiki"), 'url' => $wiki_url);
        }

        $links[] = array('text' => _("Stats"), 'url' => "stats/stats_central.php");
        $links[] = array('text' => _("Prefs"), 'url' => "userprefs.php");
        $links[] = array('text' => _('Help'), 'url' => get_faq_url("faq_central.php"));
        $links[] = array('text' => sprintf(_("Log Out (%s)"),$pguser), 'url' => "accounts/logout.php");

        echo headerbar_text_array($links);
        echo "</span>";
    }

    echo "</div>\n";
    echo "</div>\n";
}

function headerbar_text_array($entries)
// Prints out entries in the navbar contained within $entries.
// Each $entry is an associative array containing either:
//    'text' - text to display as-is without changes, can include HTML
// or:
//    'url' and 'text' - a link using 'text' as the label
// Either one can also have a 'title' as well that will be placed in a title
// attribute on the <a> or <span> tag. The function also determines if the
// current page is one of the urls and if so it doesn't create the link but
// instead wraps the text in a span.currentpage tag.
{
    global $code_url;

    $output_entries = array();

    foreach($entries as $entry)
    {
        $text = $entry['text'];
        $title = array_get($entry, 'title', '');
        $target = array_get($entry, 'target', NULL);

        if(!empty($entry['url']))
        {
            // ensure we have a full url, if not prefix the $code_url
            $url= $entry['url'];
            if(!startswith($url,"http"))
                $url= "$code_url/$url";

            // within the label, change any blanks to non-breaking spaces
            // so that the label doesn't break there
            $text = str_replace(' ', '&nbsp;', $text);

            // set the title if specified
            $title_attr = '';
            if($title)
                $title_attr = " title='" . attr_safe($title) . "'";

            // set the target if specified
            $target_attr = '';
            if($target)
                $target_attr = " target='$target'";

            // if we're on the current page, create a span
            if(is_url_for_current_page($url))
            {
                $entry_string = "<span class='currentpage'$title_attr>$text</span>";
            }
            // if we're not, create a link 
            else
            {
                $url = attr_safe($url);
                $entry_string = "<a href='$url'$title_attr$target_attr>$text</a>";
            }
        }
        elseif(!empty($title))
        {
            $entry_string = "<span title='" . attr_safe($title) . "'>$text</span>";
        }
        else
        {
            $entry_string = $text;
        }

        $output_entries[] = $entry_string;
    }

    // set the divider
    // &#183; == &middot; == middle-dot
    $divider = " &#183; ";

    return implode($divider, $output_entries);
}

function html_statsbar($nameofpage) {

    global $code_url, $PG_home_url, $pguser;
    global $Round_for_round_id_;
    maybe_show_language_selector();

    // For logged-in users, show links to key help documents:
    if (isset($pguser))
    {
        show_key_help_links();
        echo "<hr class='divider'>\n";
    }

    // Show statistics that are pertinent to the particular request.
    // (i.e., to the main content of the page).

    // The tally_name may be unspecified, or a tally name.
    $valid_tally_names = array_keys(get_page_tally_names());
    $tally_name = get_enumerated_param($_REQUEST, 'tally_name', null, $valid_tally_names, true);

    // If tally_name is unspecfied, try to see if round_id is set.
    if (!isset($tally_name)) {
        $rounds = array_keys($Round_for_round_id_);
        $tally_name = get_enumerated_param($_REQUEST, 'round_id', null, $rounds, true);
    }

    if (!isset($tally_name))
    {
        if (strpos($_SERVER['PHP_SELF'],'activity_hub.php') !== false)
        {
            show_birthdays();
        }

        if (strpos($_SERVER['PHP_SELF'],'default.php') !== false) {
            // Show project listings to anyone, signed in or not, front page only
            // Show the 7 most recently completed, tersely
            echo "<div id='completed-titles'>\n";
            echo "<h2>" . _("Recently Completed Titles") . "</h2>\n";
            $state = SQL_CONDITION_GOLD;
            list_projects_tersely( $state, "ORDER BY modifieddate DESC", "LIMIT 7" );

            // Show "More..." link
            echo "<p class='more-link'>";
            echo "<a href='list_etexts.php?x=g&amp;sort=5'>";
            echo _("More Completed Titles...") . "</a></p>";

            echo "</div>";

            // Show 3 (random) of the 10 most recently arrived (most recent P1)
            echo "<div id='recent-titles'>";
            echo "<h2>" . _("Recently Begun") . "</h2>\n";
            $state = SQL_CONDITION_BRONZE;
            $limit_phrase = "LIMIT 3 OFFSET " . rand(0, 7);
            list_projects_tersely( $state, "ORDER BY modifieddate DESC", $limit_phrase );

            // Show "More..." link
            echo "<p class='more-link'>";
            echo "<a href='list_etexts.php?x=b&amp;sort=5'>";
            echo _("More Recently Begun Titles...") . "</a></p>";

            echo "</div>";
        }

        // Only show completed projects list to signed in users, to
        // keep front page uncluttered for visitors.
        if (isset($pguser)) {
            echo "<div id='completed-projects'>\n";
            show_completed_projects();
            echo "</div>\n";
        }
    }
    else
    {
        show_tally_specific_stats( $tally_name );
    }

    echo "<p class='more-link'>";
    echo "<a href='$code_url/stats/stats_central.php'>";
    echo _("More Statistics...");
    echo "</a>";
    echo "</p>";

    if (isset($pguser))
    {
        // requestor is a logged-in user.
        echo "<hr class='divider'>\n";
        show_user_teams();
    }

    // SITE-SPECIFIC
    //Display the "About DP" section if user is not logged in
    if (!isset($pguser))
    {
        echo "<hr><p id='about-dp'>";
        echo sprintf(_("Distributed Proofreaders was founded in 2000 by Charles Franks to support the digitization of Public Domain books. Originally conceived to assist <a href='%s'>Project Gutenberg</a> (PG), Distributed Proofreaders (DP) is now the main source of PG e-books. In 2002, Distributed Proofreaders became an official PG site. In May 2006, Distributed Proofreaders became a separate legal entity and continues to maintain a strong relationship with PG."), $PG_home_url);
        echo "</p>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function maybe_show_language_selector()
{
    global $code_url, $userP, $user_is_logged_in;

    // if user is logged in, return instead of showing the drop-down
    if($user_is_logged_in)
        return;

    $options = get_locale_translation_selection_options();
    if(count($options) <= 1)
        return;

    ?>
    <script><!--
        function submitLang(i) {
            top.document.forms.langform.submit();
        }
    // --></script>
    <?php

    $intlang = get_desired_language();
    echo "<form id='langform' action='$code_url/tools/setlangcookie.php' method='POST'><div>\n";
    echo "<label for='langform-lang'>" . _("Set language") . ": </label>";
    echo "<select id='langform-lang' name='lang' onChange='submitLang(this)'>\n";
    echo "<option value=''>" . _("Browser default") . "</option>";
    foreach($options as $locale => $label)
        echo "<option value='$locale'".(($locale==$intlang)?" selected":"").">$label</option>\n";
    echo "</select><input type='hidden' name='returnto' value='".$_SERVER['REQUEST_URI']."'>\n</div></form>\n";
    echo "<hr>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_activity_links()
// return a list of round/stage links for the user
// the returned links are in a format usable by headerbar_text_array
{
    global $Stage_for_id_, $pguser;

    if (is_null($pguser)) return;
    // Should show links to stages that are accessible to people
    // that aren't logged in? (i.e., SR)?

    $links = array();

    if (user_is_PM())
    {
        $links[] = array('url' => "tools/project_manager/projectmgr.php", 'text' => pgettext('project manager', 'PM'), 'title' => _('Manage Projects'));
    }

    // Get all stages the user has access to
    $stages_can_access_and_access_prereqs = get_stages_user_can_work_in($pguser);

    // we still need to loop through $Stage_for_id_ to output the stages
    // in order
    foreach( $Stage_for_id_ as $stage)
    {
        if(isset($stages_can_access_and_access_prereqs[$stage->id]))
        {
            $text = $stage->id;

            // for PPers, get the number of their projects requiring attention
            if($stage->id == "PP")
            {
                $total = count_pp_projects_past_threshold($pguser);
                if($total)
                    $text .= " ($total)";
            }
            $links[] = array('url' => $stage->relative_url, 'text' => $text, 'title' => $stage->name);
        }
    }

    if(user_is_a_sitemanager())
    {
        $links[] = array(
            'url' => "tools/site_admin/index.php",
            // TRANSLATORS: SA is an abbreviation for Site Administrator
            'text' => _("SA"),
            'title' => _("Administer Site")
        );
    }

    return $links;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_tally_specific_stats( $tally_name )
{
    global $code_url, $userP, $pguser;

    // Put the whole thing in a div, just so we can put a box around it.
    echo "<div id='tally-stats'>\n";
    echo "<p id='server-time'>" . _("Server Time") . ": " . strftime("%R") . "</p>\n";

    // Show the site statistics

    $site_stats = get_site_page_tally_summary($tally_name);

    echo "<h2>" . sprintf(_("%s Site Statistics"), $tally_name) . "</h2>\n";

    echo "<table style='width: 100%;'>";
    echo "<tr>";
    echo "<th></th>";
    echo "<th class='center-align'>" . _("Goal") . "</th>";
    echo "<th class='center-align'>" . _("Total") . "</th>";
    echo "<th class='center-align'>" . _("Progress") . "</th>";
    echo "</tr>";
    show_tally_vs_goal(_("Today"), $site_stats->curr_day_goal, $site_stats->curr_day_actual);
    show_tally_vs_goal(_("Yesterday"), $site_stats->prev_day_goal, $site_stats->prev_day_actual);
    show_tally_vs_goal(strftime("%B"), $site_stats->curr_month_goal, $site_stats->curr_month_actual);
    echo "</table>";

    // Number of users
    echo "<p>\n";
    $res = mysqli_query(DPDatabase::get_connection(), "SELECT COUNT(*) FROM users" );
    list($num_users) = mysqli_fetch_row($res);
    echo sprintf( _('%s users'), number_format($num_users) ), "<br>\n";

    $tallyboard = new TallyBoard( $tally_name, 'U' );
    $num_positive_users = $tallyboard->get_num_holders_with_positive_tally();
    echo 
        sprintf(
            _('%1$s users with at least one %2$s page'),
            number_format($num_positive_users), $tally_name ),
        "\n";

    // ---------------------------------------------------------------------

    if (isset($pguser))
    {
        // The requestor is a logged-in user.
        // Show the user's personal statistics

        //Get the personal statistics array
        $neighbors =
            user_get_page_tally_neighborhood(
                $tally_name, $pguser, $userP['u_neigh']);
        $usern = $neighbors[0];

        //get rank
        global $testing;
        if ($testing)
        {
            // Don't let people discover the honorifics by
            // playing with inflated pagecounts at test-site.
           $rankname = _('Valued Tester');
        }
        else
        {
            $round = get_Round_for_round_id( $tally_name );
            if ($round)
            {
                $rankname = $round->get_honorific_for_page_tally(
                    $usern->get_current_page_tally() );
            }
            else
            {
                $rankname = _('[unknown]');
            }
        }

        // Get userid for personal stats link
        $user = new User($pguser);

        // get yesterday's and today's page count
        $snapshot_info = $tallyboard->get_info_from_latest_snapshot($user->u_id);
        $yesterday_page_delta = $snapshot_info['tally_delta'];
        $today_page_delta = $usern->get_current_page_tally() - $snapshot_info['tally_value'];

        $valid_tally_names = array_keys(get_page_tally_names());
        $site_total = 0;
        foreach($valid_tally_names as $tally_name_temp)
        {
            $round_tallyboard = new TallyBoard($tally_name_temp, 'U');
            $site_total += $round_tallyboard->get_current_tally($user->u_id);
        }

        echo "<h2>" . _("Personal Statistics") . "</h2>\n";
        echo "<p>\n";
        echo _("Your total site pages") . ": " . number_format($site_total) . "<br>\n";
        echo "<br>";
        echo sprintf(_("%s Pages Yesterday"), $tally_name) . ": ";
        echo number_format($yesterday_page_delta)."<br>\n";
        echo sprintf(_("%s Pages Today"), $tally_name) . ": ";
        echo number_format($today_page_delta)."<br>\n";
        echo sprintf(_("%s Pages Total"), $tally_name) . ": ";
        echo number_format($usern->get_current_page_tally())."<br>\n";
        echo sprintf(_("%s Current Position"), $tally_name) . ": ";
        echo number_format($usern->get_current_page_tally_rank())."<br>\n";
        echo sprintf(_("%s Current Rank"), $tally_name) . ": ";
        echo "$rankname<br>\n";
        echo "</p>\n";
        echo "<p class='more-link'><a href='$code_url/stats/members/mdetail.php?id=".$user->u_id."&amp;tally_name=".$tally_name."'>" . $tally_name . " " .  _("Details...") . "</a></p>\n";
        echo "<" . "!-- " . $userP['u_id'] . " -->";

        // -------------------------------------------------------------
        //Show the Neighbor table if the user has requested so
        if ($userP['u_neigh']) {
            echo "<h2>" . sprintf(_("Your %s Neighborhood"), $tally_name) . "</h2>\n";
            echo "<p>\n";

            foreach ( $neighbors as $rel_posn => $neighbor )
            {
                if ($rel_posn==0) echo "<span class='this-user'>";
                echo $neighbor->get_current_page_tally_rank(), ".) ";
                echo ( $neighbor->is_anonymized() ? _('Anonymous') : $neighbor->get_username() ), " - ";
                echo number_format($neighbor->get_current_page_tally());
                if ($rel_posn==0) echo "</span>";
                echo "<br>\n";
            }

            echo "</p>\n";
        }
    }

    echo "</div>";
}

function show_tally_vs_goal($label, $goal, $actual)
{
    $percent = $actual == 0 ? 0 : $actual / $goal * 100;
    echo "<tr>";
    echo "<th>$label</th>";
    echo "<td class='center-align'>" . number_format($goal) . "</td>";
    echo "<td class='center-align'>" . number_format($actual) . "</td>";
    echo "<td class='center-align'>" . sprintf("%d%%", $percent) . "</td>";
    echo "</tr>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_user_teams()
{
    global $code_url, $userP;

    echo "<div id='teams-nav'>\n";
    echo "<h2>" .  _("Your Teams") . "</h2>\n";
    echo "<ul>\n";
    $teamRes=mysqli_query(DPDatabase::get_connection(), "
        SELECT teamname, id
        FROM user_teams
        WHERE id IN ({$userP['team_1']}, {$userP['team_2']}, {$userP['team_3']})
    ");
    while($row = mysqli_fetch_assoc($teamRes))
    {
        echo "<li>";
        echo "<a href='$code_url/stats/teams/tdetail.php?tid=".$row['id']."'>".html_safe($row['teamname'])."</a>";
        echo "</li>";
        echo "\n";
    }
    echo "</ul>\n";
    echo "<p class='more-link'>";
    echo "<a href='$code_url/stats/teams/tlist.php'>"._("View all teams...")."</a>";
    echo "</p>\n";
    echo "</div>\n";
    echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_birthdays()
{
    global $site_abbreviation;

    $seconds_in_month = 30 * 24 * 60 * 60;

    // Only show birthdays for members who have been active in the past
    // $months_since_last_activity months.
    $months_since_last_activity = 6;

    // To ensure this query is fast, we base the times off of midnight today
    // instead of time(). This ensures that the SQL we send is identical for
    // every call of this function in a day. This allows the query to be
    // efficiently cached in the MySQL query cache. We also take care not to
    // use any functions that would prevent this query from being cached. See
    // http://dev.mysql.com/doc/refman/5.1/en/query-cache-operation.html
    $timestamp_at_midnight = mktime(0, 0, 0);
    $oldest_activity = $timestamp_at_midnight -
            ($months_since_last_activity * $seconds_in_month);

    // We exclude users whose privacy preference is set to anonymous.
    // Birthdays are only shown on the activity hub which requires a login
    // so users with a privacy preference of 'private' are ok to be included.
    $sql = sprintf("
        SELECT username, date_created
        FROM users
        WHERE
            FROM_UNIXTIME(date_created, '%%m-%%d') = '%s'
            AND t_last_activity > %s
            AND date_created < %s
            AND u_privacy != %d
        ORDER BY date_created ASC
    ", strftime("%m-%d"), $oldest_activity, $timestamp_at_midnight, PRIVACY_ANONYMOUS);

    $result = mysqli_query(DPDatabase::get_connection(), $sql);

    if (mysqli_num_rows($result) == 0)
        return;

    echo "<div id='birthdays'>\n";
    echo "<h2>" . sprintf(_("Today's %s Birthdays"),$site_abbreviation) . "</h2>\n";

    echo "<ul>";

    $today = getdate(time());
    while ( list($username, $date_created) = mysqli_fetch_row($result) )
    {
        $user_date = getdate($date_created);
        $years = $today['year'] - $user_date['year'];

        echo "<li>$username ($years)</li>";
    }

    echo "</ul></div><hr class='divider'>\n";
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_completed_projects($terse = FALSE)
{
    global $code_url;

    if ($terse) {
        echo "\n<br>\n";
        $num_months = 1;
    } else {
        echo "<h2>" . _("Completed Projects") . "</h2>\n";
        echo "<table>\n";
        $num_months = 12;
    }
    $thismonth = date("n");
    $thisyear = date("Y");
    for ( $months_ago = $num_months; $months_ago >= 0; $months_ago-- )
    {
        // midnight that begins the 1st day of this month and the next:
        $begindate = mktime(0,0,0,$thismonth-$months_ago,  1,$thisyear);
        $enddate   = mktime(0,0,0,$thismonth-$months_ago+1,1,$thisyear);

        // TRANSLATORS: This is an strftime-formatted string
        $displaydate = strftime(_("%b %Y"), $begindate );

        $result = mysqli_query(DPDatabase::get_connection(), "
            SELECT COUNT(*) as totalprojects
            FROM projects
            WHERE modifieddate >= $begindate
                and modifieddate <= $enddate
                and state = '".PROJ_SUBMIT_PG_POSTED."'
        ");
        $row = mysqli_fetch_assoc($result);
        $totalprojects = $row["totalprojects"];

        if ($terse) {
            // TRANSLATORS: string is in the format: $totalprojects in $date
            // where $date is an already-translated strftime()-formatted string
            // with the month and year
            echo sprintf(_('%1$d in %2$s'),$totalprojects,$displaydate) . "&nbsp;&mdash;&nbsp;";
        } else {
            echo "<tr>";
            echo   "<td class='month'>";
            echo     "&nbsp;$displaydate&nbsp;&mdash;";
            echo   "</td>";
            echo   "<td class='count'>";
            echo     "&nbsp;$totalprojects&nbsp;";
            echo   "</td>";
            echo "</tr>";
            echo "\n";
        }
    }
    if ($terse)
    {
        echo "<a href='$code_url/list_etexts.php?x=g&amp;sort=5'>" . _("More...") . "</a>\n";
    } else {
        echo "</table>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_key_help_links()
{
    $faq_central_url = get_faq_url("faq_central.php");
    $p_guide_url = get_faq_url("proofreading_guidelines.php");
    $f_guide_url = get_faq_url("formatting_guidelines.php");
    $p_summary = get_faq_url("proofing_summary.pdf");
    $f_summary = get_faq_url("formatting_summary.pdf");

    $items = array(
        "<a href='$faq_central_url'>"._("Frequently Asked Questions")."</a>",

        "<a href='$p_guide_url'>"._("Proofreading Guidelines")."</a>".
        " <span class='nowrap'>(<a href='$p_summary'>"._("PDF Summary")."</a></span>)",

        "<a href='$f_guide_url'>"._("Formatting Guidelines")."</a>".
        " <span class='nowrap'>(<a href='$f_summary'>"._("PDF Summary")."</a></span>)",
    );

    $url = get_faq_url("official-docs");
    if($url)
        $items[] = "<a href='$url'>"._("Other Official Documentation")."</a>";

    $url = get_faq_url("request-support");
    if($url)
        $items[] = "<a href='$url'>"._("Request Support")."</a>";

    echo "<div id='key-help-documents'>";
    echo "<h2>"._("Key Help Documents")."</h2>";
    echo "<ul>\n";

    foreach ($items as $item)
    {
        echo "<li>$item</li>\n";
    }
    echo "</ul>\n";
    echo "</div>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function calculate_progress_bar_properties($actual, $goal, $prorate_goal_for_color=TRUE, $color_thresholds=NULL)
// A simple progress bar can be created by using the following HTML:
//   <div class='progressbar' style='background-color: $color; width: $width%;'>&nbsp;</div>
// The <div> can be placed inside another container used to control
// the total width. This function assists in calculating the $color and $width
// values used above in addition to returning the actual percentage of the
// goal achieved.
// Arguments:
//   actual           - actual value obtained
//   goal             - goal
//   prorate_goal_for_color
//                    - if TRUE or 'day', the returned color will be calculated based
//                      on how much of the current day has passed. If set to 'month'
//                      the color will be based on how much of the month has passed.
//                      This is useful so that at the beginning of a new timefram the
//                      bar isn't at the low color.
//   color_thresholds - associative array containing the colors to use for
//                      each percentage completed range
//                      eg: array(100 => "lightgreen", 75 => "orange", 0 => "red");
//
// Returns three values via a non-associative array():
//   progress_bar_width - percentage width of the progress bar in range [0-100]
//   progress_bar_color - color of progress bar using step-wise colors
//   percent_complete   - actual percentage complete in whole numbers
{
    // Define the colors used for the status bar graph
    if(!is_array($color_thresholds))
    {
        // Thresholds defined here are assumed to be highest to
        // lowest. Thresholds passed into this function are sorted.
        $color_thresholds = array(100 => "lightgreen", 75 => "orange", 0 => "red");
    }
    else
    {
        krsort($color_thresholds);
    }

    // calculate the width and percentage complete
    if($goal > 0)
    { 
        $percent_complete = ceil(($actual / $goal) * 100);
        if($percent_complete < 0)
            $percent_complete = 0;
        $progress_bar_width = min($percent_complete, 100);
    }
    else
    {
        $percent_complete = 100;
        $progress_bar_width = 100;
    }

    // Calculate the progress bar color scaling based on the time of day.
    if($prorate_goal_for_color == 'bar' || $prorate_goal_for_color === TRUE)
    {
        $t_start_of_today = mktime(0,0,0,date('m'),date('d'),date('y'));
        $fraction_of_time_passed = (time() - $t_start_of_today) / (60*60*24);
        // Note: Calculation assumes a 24-hour day which is true except
        // possibly for the first and last days of daylight saving time
        // depending on how PHP calculates DST in the mktime() call.
    }
    elseif($prorate_goal_for_color == 'month')
    {
        $t_start_of_month = mktime(0, 0, 0, date('m'), 1, date('y'));
        $fraction_of_time_passed = (time() - $t_start_of_month) / (60*60*24*date("t"));
    }
    else
    {
        $fraction_of_time_passed = 1;
    }

    foreach($color_thresholds as $threshold => $color)
    {
        if($percent_complete >= ($threshold * $fraction_of_time_passed))
        {
            $progress_bar_color = $color;
            break;
        }
    }

    return array($progress_bar_width, $progress_bar_color, $percent_complete);
}

// -----------------------------------------------------------------------------

function get_dyn_image_url_for_file($filename)
// Given file name relative to $dyn_dir, find a matching image by testing
// for all web image extensions and return the full URL in $dyn_url.
// If no image is found for the file name, the function returns NULL.
{
    global $dyn_dir, $dyn_url;

    $image_extensions = array("", ".png",".jpg",".gif");

    foreach($image_extensions as $extension)
    {
        $test_path = "$dyn_dir/code_images/$filename$extension";
        $test_url  = "$dyn_url/code_images/$filename$extension";
        if ( file_exists($test_path) )
            return $test_url;
    }

    return NULL;
}

// Given an image, see if we have a version that is in the current
// user's locale. If so, return the <img> tag with $text as alt text.
// If not, return $text.
function get_translated_graphic_or_text($directory, $image, $text)
{
    $locale = get_desired_language();
    $dyn_url = get_dyn_image_url_for_file("$directory/$locale/$image");

    if($dyn_url)
        return sprintf("<img src='$dyn_url' alt='%s'>", attr_safe($text));
    else
        return $text;
}

// vim: sw=4 ts=4 expandtab
