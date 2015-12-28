<?php
$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'pg.inc');
include_once($relPath.'dpsession.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'list_projects.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'languages.inc');

define('NO_STATSBAR',   False);
define('SHOW_STATSBAR', True);

$user_is_logged_in = dpsession_resume();

if(isset($userP['i_theme']) && $userP['i_theme'] != '' ) {
    $theme_name = $userP['i_theme'];
} else {
    $theme_name = "project_gutenberg";
}
include_once($relPath."templates/$theme_name/theme.tpl");
$theme = ${$theme_name};

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
    global $code_url, $theme, $theme_name, $pguser, $userP;

    // Display stats bar on left (1) or right (0)
    $statsbar_align = isset($pguser) ? $userP['u_align'] : 0;

    // The theme layout consists of:
    // * Two full width headers at the top,
    // * A table with several columns containing sidebars & main body content
    // * A full width footer at the bottom.

    // All our themes begin with the initial markup, logo and navbar, and open a
    // table for further layout.
    html_header($nameofpage, $extra_args);
    html_logobar();
    echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
    echo "<tr>\n";

    if ($show_statsbar) {
        if ($statsbar_align == 1) {
            // If we're displaying the stats bar on the left, then emit:
            // * A 25% width column with the stats bar in it
            // * A 13px column with the rounded corner
            // * A 75% width column for the main page content, and return.
            echo "<td align='left' valign='top' width='25%' style='padding: .5em;' bgcolor='".$theme['color_navbar_bg']."'>\n";
            html_statsbar($nameofpage);
            echo "</td>\n";
            echo "<td align='right' valign='top' bgcolor='".$theme['color_mainbody_bg']."'><img border='0' src='$code_url/pinc/templates/".$theme_name."".$theme['image_curve_left']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>\n";
            echo "<td align='left' valign='top' width='75%' style='padding: .5em;' bgcolor='".$theme['color_mainbody_bg']."'>\n";
        } else {
            // If the stats bar is on the right then emit:
            // * A 5px column with the background colour
            // * A 75% width column for the main page content, and return.
            echo "<td align='left' valign='top' width='5' bgcolor='".$theme['color_mainbody_bg']."'>&nbsp;</td>\n";
            echo "<td align='left' valign='top' width='75%' style='padding: .5em;' bgcolor='".$theme['color_mainbody_bg']."'>\n";
        }
    } else {
        // If we've displaying no stats bar then emit:
        // * A 5px column with the background colour
        // * A 100% width column for the main page content, and return.
        echo "<td align='left' valign='top' width='5' bgcolor='".$theme['color_mainbody_bg']."'>&nbsp;</td>\n";
        echo "<td align='left' valign='top' width='100%' bgcolor='".$theme['color_mainbody_bg']."'>\n";
    }

    // Call output_footer when the main page content has been emitted so we can
    // close the table and emit the footer.
    register_shutdown_function('output_footer', $nameofpage, $show_statsbar);
}

function output_footer($nameofpage, $show_statsbar = True) {
    global $code_url, $theme, $theme_name, $pguser, $userP;

    // Display stats bar on left (1) or right (0)
    $statsbar_align = isset($pguser) ? $userP['u_align'] : 0;

    // The main page content has been emited so close the main page content column.
    echo "</td>\n";
    if ($show_statsbar && $statsbar_align == 0) {
        // If we're displaying the the stats bar on the right, then emit:
        // * A 13px column with the rounded corner
        // * A 25% width column with the stats bar in it
        echo "<td align='right' valign='top' bgcolor='" . $theme['color_mainbody_bg'] . "'><img border='0' src='$code_url/pinc/templates/".$theme_name."".$theme['image_curve_right']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>\n";
        echo "<td align='left' valign='top' width='25%' style='padding: .5em;' bgcolor='" . $theme['color_navbar_bg'] . "'>\n";
        html_statsbar($nameofpage);
        echo "</td>\n";
    }
    // Finally close the table, and emit the footer.
    echo "</tr></table>\n";
    html_footer();
}

function html_header($nameofpage, $extra_args = array()) {
    global $code_url, $theme, $site_abbreviation;
    global $charset, $intlang;
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
    echo "<html ".lang_html_header($intlang).">\n<head>\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">\n";
    echo "<link rel='shortcut icon' href='$code_url/favicon.ico'>\n";
    echo "<title>$site_abbreviation";
    if (isset($nameofpage)) { echo ": $nameofpage"; }
    echo "</title>\n";
    // Global theme CSS
    echo "\n<link type='text/css' rel='Stylesheet' href='";
    echo "$code_url/pinc/templates/" . $theme['template_unix_name'] . "/main.css'>\n";

    // Global layout CSS
    echo "<link type='text/css' rel='Stylesheet' href='$code_url/styles/layout.css'>";

    // Per-page CSS
    // this first navbar blob needs to be moved to a universal stylesheet but
    // that'll also require adjustements for the $theme replacements
    echo "<style type='text/css'>\n";
    echo "
        table.navbar {
            margin: 0;
            border: 0;
            width: 100%;
            background-color: ".$theme['color_headerbar_bg'].";
        }
        table.navbar td {
            font-family: ".$theme['font_headerbar'].";
            font-size: 0.75em;
        }
        table.navbar a,
        table.navbar a:link,
        table.navbar a:visited,
        table.navbar span.text {
            color: " .$theme['color_headerbar_font'].";
        }
        table.navbar span.currentpage {
            color: " .$theme['color_headerbar_font'].";
            font-weight: bold;
        }
    ";
    // Any additional style definitions requested by the caller
    if (isset($extra_args['css_data']))
    {
         echo $extra_args['css_data'];
    }
    echo "</style>\n";

    // Per-page Javascript
    if (isset($extra_args['js_data']))
    {
        echo "<script type='text/javascript'>\n" .
            $extra_args['js_data'] .
            "</script>\n";
    }

    echo "</head>\n\n";
    echo "<body bgcolor='" . $theme['body_bgcolor'] . "' text='" . $theme['body_text'];
    echo "' link='" . $theme['body_link'] . "' vlink='" . $theme['body_vlink'];
    echo "' alink='" . $theme['body_alink'];
    echo "' style='margin: 0;'>\n\n";
}

function html_logobar() {
    global $code_url, $theme, $theme_name, $site_name;
    global $maintenance;
    echo "\n<table width='100%' cellSpacing=0 cellPadding=0 border=0>\n";
    echo "<tr>\n";
    echo "<td width='50%' bgcolor='".$theme['color_logobar_bg']."'>\n";
    echo "<a href='$code_url'><img src='$code_url/pinc/templates/".$theme_name."".$theme['image_logo']."' width='360' height='68' alt='$site_name' border='0'></a>\n";
    echo "</td>\n";

    echo "<td width='50%' bgcolor='" . $theme['color_logobar_bg'] .  "' align='center' valign='middle'>\n";

    if ($maintenance) {
        echo _("MAINTENANCE MODE");
        echo "<br>\n";
    }

    $psd = get_project_status_descriptor('posted');

    if (0) {
        // show number of projects posted to PG
        $result = mysql_query( "
            SELECT COUNT(*)
            FROM projects
            WHERE $psd->state_selector
        ");
        $numproj = mysql_result($result,0);
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='4'><b>";
        echo sprintf(
            _('%s projects completed!'),
            number_format($numproj));
        echo "</b></font>\n";
        echo "<br>";
    }

    if (1) {
        // show number of books posted to PG
        // (takes into account that some books are processed as several projects)

        $result = mysql_query( "
            SELECT COUNT(distinct postednum)
            FROM projects
            WHERE $psd->state_selector
        ");
        $numproj = mysql_result($result,0);
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='3'><b>";
        echo sprintf(
            _('%s titles preserved for the world!'),
            number_format($numproj));
        echo "</b></font>\n";
        show_completed_projects(TRUE);
    }

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    html_navbar();
}

function html_navbar()
// Display the horizontal bar containing links to various important points
// within the site (and the login form if the user is not logged in).
{
    global $theme, $code_url, $site_abbreviation, $pguser, $forums_url, $blog_url, $wiki_url;

    echo "<table class='navbar' width='100%'>";
    echo "<tr>";

    //Code to display login form if not logged in
    if (!isset($pguser)) {
        $links = array();
        $links[] = array('text' => $site_abbreviation, 'url' => "default.php");
        if ( !empty($blog_url) )
            $links[] = array('text' => _("Blog"), 'url' => $blog_url);
        $links[] = array('text' => _("Forums"), 'url' => $forums_url);
        echo "<td>";
        echo headerbar_text_array($links);
        echo "</td>\n";

        echo "<td align='right'>";

        $login_form = "<form style='margin: 0; display: inline;' action='$code_url/accounts/login.php' method='post'>\n";

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

        $login_form .= "<input type='hidden' name='destination' value='" . htmlspecialchars($destination,ENT_QUOTES) . "'>";

        // login link text
        // TRANSLATORS: ID is an abbreviation for username in the navbar
        $login_form .= "<span class='text'>" . _("ID") . ":</span> <input type='text' name='userNM' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;";
        $login_form .= "<span class='text'>" . _("Password") . ":</span> <input type='password' name='userPW' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;";
        $login_form .= "<input type='submit' value='" . _("Sign In") ."' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>";
        $login_form .= "</form>";

        $links=array();
        $links[] = array('text' => $login_form);
        $links[] = array('text' => _('Register'), 'url' => "accounts/addproofer.php");
        $links[] = array('text' => _('Help'), 'url' => "faq/".lang_dir()."faq_central.php");
        echo headerbar_text_array($links);
        echo "</td>\n";
    } else {
        //Code to display if the user is logged in

        // Fetch some values from the database for use in links later
        $numofPMs = get_number_of_unread_messages($pguser);

        echo "<td>";
        // left side
        echo "<span style='float: left; padding: 2px;'>";
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

        $links[] = array('text' => _("Project Search"), 'url' => "tools/project_manager/projectmgr.php?show=search_form");

        echo headerbar_text_array($links);

        echo "</span>";

        // right side
        // white-space: nowrap is required to have the right side shift
        // under the left side if the window isn't large enough to have both
        // on the same line.
        echo "<span style='text-align: right; float: right; padding: 2px; white-space: nowrap;'>";
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
        $links[] = array('text' => _("Help"), 'url' => "faq/".lang_dir()."faq_central.php");
        $links[] = array('text' => sprintf(_("Log Out (%s)"),$pguser), 'url' => "tools/logout.php");

        echo headerbar_text_array($links);
        echo "</span>";
        echo "</td>\n";
    }

    echo "</tr>\n";
    echo "</table>\n";
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
        $title = @$entry['title'];

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
            $title_attr= '';
            if(!empty($title))
                $title_attr= "title='" . attr_safe($title) . "'";

            // if we're on the current page, create a span
            if(is_url_for_current_page($url))
                $entry_string = "<span class='currentpage' $title_attr>$text</span>";
            // if we're not, create a link 
            else
                $entry_string = "<a href='$url' $title_attr>$text</a>";
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

    // SITE-SPECIFIC
    //Display the donation icon and link to donate.
    show_donation_stuff();
    echo "<hr>";

    // Show statistics that are pertinent to the particular request.
    // (i.e., to the main content of the page).

    // The tally_name may be unspecified, or a tally name.
    global $page_tally_names;
    $valid_tally_names = array_keys($page_tally_names);
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
            echo "<br />\n";
            show_birthdays();

        }
        echo "<br>\n";

        if (strpos($_SERVER['PHP_SELF'],'default.php') !== false) {
            // Show project listings to anyone, signed in or not, front page only
            // Show the 7 most recently completed, tersely
            echo "<center><b><u>" . _("Recently Completed Titles") . ":</u></b></center>\n";
            $state = SQL_CONDITION_GOLD;
            list_projects_tersely( $state, "ORDER BY modifieddate DESC", "LIMIT 7" );

            // Show "More..." link
            echo "<p style='text-align: center;'>";
            echo "<a href='list_etexts.php?x=g&amp;sort=5'>";
            echo _("More Completed Titles...") . "</a></p>";

            // Show 3 (random) of the 10 most recently arrived (most recent P1)
            echo "<center><b><u>" . _("Recently Begun") . ":</u></b></center>\n";
            $state = SQL_CONDITION_BRONZE;
            $limit_phrase = "LIMIT 3 OFFSET " . rand(0, 7);
            list_projects_tersely( $state, "ORDER BY modifieddate DESC", $limit_phrase );

            // Show "More..." link
            echo "<p style='text-align: center;'>";
            echo "<a href='list_etexts.php?x=b&amp;sort=5'>";
            echo _("More Recently Begun Titles...") . "</a></p>";
        }

        // Only show completed projects list to signed in users, to
        // keep front page uncluttered for visitors.
        if (isset($pguser)) {
            show_completed_projects();
        }
    }
    else
    {
        show_tally_specific_stats( $tally_name );
    }

    echo "<br>";
    echo "<center>";
    echo "<font size='2'>";
    echo "<a href='$code_url/stats/stats_central.php'>";
    echo _("More Statistics...");
    echo "</a>";
    echo "</font>";
    echo "</center>\n";

    if (isset($pguser))
    {
        // requestor is a logged-in user.

        echo "<br><hr width='75%' align='center'><br>\n";
        show_user_teams();

        echo "<br><hr width='75%' align='center'><br>\n";
        show_key_help_links();
    }

    // SITE-SPECIFIC
    //Display the "About DP" section if user is not logged in
    if (!isset($pguser))
    {
        echo "<hr><p><font size='2'>";
        echo sprintf(_("Distributed Proofreaders was founded in 2000 by Charles Franks to support the digitization of Public Domain books. Originally conceived to assist <a href='%s'>Project Gutenberg</a> (PG), Distributed Proofreaders (DP) is now the main source of PG e-books. In 2002, Distributed Proofreaders became an official PG site. In May 2006, Distributed Proofreaders became a separate legal entity and continues to maintain a strong relationship with PG."), $PG_home_url);
        echo "</font></p>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function maybe_show_language_selector()
{
    global $code_url, $userP, $intlang, $user_is_logged_in;

    // if user is logged in, return instead of showing the drop-down
    if($user_is_logged_in)
        return;

    $options = get_locale_translation_selection_options();
    if(count($options) <= 1)
        return;

    ?>
    <script language="JavaScript" type="text/javascript"><!--
        function submitLang(i) {
            top.document.langform.submit();
        }
    // --></script>
    <?php

    echo "<form name=langform action='$code_url/tools/setlangcookie.php' method=POST>\n";
    echo _("Set language") . ": ";
    echo "<select name=lang onChange='submitLang(this)'>\n";
    echo "<option value=''>" . _("Browser default") . "</option>";
    foreach($options as $locale => $label)
        echo "<option value='$locale'".(($locale==$intlang)?" selected":"").">$label</option>\n";
    echo "</select><input type=hidden name=returnto value='".$_SERVER['REQUEST_URI']."'>\n</form>\n";
    echo "<hr>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_activity_links()
// return a list of round/stage links for the user
// the returned links are in a format usable by headerbar_text_array
{
    global $Stage_for_id_, $pguser, $userP;

    if (is_null($pguser)) return;
    // Should show links to stages that are accessible to people
    // that aren't logged in? (i.e., SR)?

    $links = array();

    if (user_is_PM())
    {
        // if the PM has a default PM Page defined, use it
        if ($userP['i_pmdefault'] == 0)
        {
            $default_pm_page="?show=user_all";
        }
        elseif ($userP['i_pmdefault'] == 1)
        {
            $default_pm_page="?show=user_active";
        }
        else
        {
            $default_pm_page="";
        }

        $links[] = array('url' => "tools/project_manager/projectmgr.php$default_pm_page", 'text' => 'PM', 'title' => _('Manage Projects'));
    }

    // Get all stages the user has access to
    $stages_can_access_and_access_prereqs = get_stages_user_can_work_in($pguser);

    // we still need to loop through $Stage_for_id_ to output the stages
    // in order
    foreach( $Stage_for_id_ as $stage)
    {
        if(isset($stages_can_access_and_access_prereqs[$stage->id]))
        {
            $links[] = array('url' => $stage->relative_url, 'text' => $stage->id, 'title' => $stage->name);
        }
    }

    return $links;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_tally_specific_stats( $tally_name )
{
    global $code_url, $theme, $userP, $pguser;

    // Put the whole thing in a table, just so we can put a box around it.
    echo "<br>";
    echo "<center>";
    echo "<table width='96%' border='1' style='border: 1px solid #111; border-collapse: collapse'>";
    echo "<tr>";
    echo "<td>";

    // Show the site statistics

    $site_stats = get_site_page_tally_summary($tally_name);

    global $page_tally_names;
    echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";
    echo "<b>" . $page_tally_names[$tally_name] . "</b><br><br>\n";
    echo _("Server Time") . ": " . strftime("%R") . "<br><br>\n";
    echo "<b>" . _("Site Statistics") . ":</b></font></center>\n";
    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";

    show_tally_vs_goal(
        _("Today's Goal") . ":",  $site_stats->curr_day_goal,
        _("Today's Total") . ":", $site_stats->curr_day_actual );

    show_tally_vs_goal(
        _("Yesterday's Goal") . ":",  $site_stats->prev_day_goal,
        _("Yesterday's Total") . ":", $site_stats->prev_day_actual );

    show_tally_vs_goal(
        strftime(_("%B's Goal") . ":"),  $site_stats->curr_month_goal,
        strftime(_("%B's Total") . ":"), $site_stats->curr_month_actual );

    // Number of users
    $res = mysql_query("SELECT COUNT(*) FROM users" );
    list($num_users) = mysql_fetch_row($res);
    echo '&nbsp;', sprintf( _('%s users'), number_format($num_users) ), "<br>\n";

    $tallyboard = new TallyBoard( $tally_name, 'U' );
    $num_positive_users = $tallyboard->get_num_holders_with_positive_tally();
    echo '&nbsp;',
        sprintf(
            _('%s users with at least one %s page'),
            number_format($num_positive_users), $tally_name ),
        "<br>\n";

    echo "</font><br><hr width='75%' align='center'><br>\n";
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
        $result = mysql_query("SELECT u_id FROM users WHERE username='$pguser' LIMIT 1");
        $u_id = mysql_result($result, 0, "u_id"); // could use $usern->get_u_id() ??

        // get yesterday's and today's page count
        $snapshot_info = $tallyboard->get_info_from_latest_snapshot($u_id);
        $yesterday_page_delta = $snapshot_info['tally_delta'];
        $today_page_delta = $usern->get_current_page_tally() - $snapshot_info['tally_value'];

        echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><b>" . _("Personal Statistics") . ":</b></font></center>\n";
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
        echo "&nbsp;" . _("Pages Yesterday") . ": ";
        echo number_format($yesterday_page_delta)."<br>\n";
        echo "&nbsp;" . _("Pages Today") . ": ";
        echo number_format($today_page_delta)."<br>\n";
        echo "&nbsp;" . _("Pages Total") . ": ";
        echo number_format($usern->get_current_page_tally())."<br>\n";
        echo "&nbsp;" . _("Current Position") . ": ";
        echo number_format($usern->get_current_page_tally_rank())."<br>\n";
        echo "&nbsp;" . _("Current Rank") . ": ";
        echo "$rankname<br></font>\n";
        echo "<center><font size='2'><a href='$code_url/stats/members/mdetail.php?id=".$u_id."&tally_name=".$tally_name."'>" . $tally_name . " " .  _("Details...") . "</a></font></center>\n";
        echo "<" . "!-- " . $userP['u_id'] . " -->";

        // -------------------------------------------------------------
        //Show the Neighbor table if the user has requested so
        if ($userP['u_neigh']) {
            echo "<br><hr width='75%' align='center'><br>\n";
            echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><b>";
            echo _("Your Neighborhood") . ": ";
            echo "</b></font></center>\n";
            echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";

            foreach ( $neighbors as $rel_posn => $neighbor )
            {
                echo "&nbsp;";
                if ($rel_posn==0) echo "<b><i>";
                echo $neighbor->get_current_page_tally_rank(), ".) ";
                echo ( $neighbor->is_anonymized() ? _('Anonymous') : $neighbor->get_username() ), " - ";
                echo number_format($neighbor->get_current_page_tally());
                if ($rel_posn==0) echo "</i></b>";
                echo "<br>\n";
            }
        }
    }

    echo "<br>";
    echo "</td></tr></table>";
    echo "</center>";
}

function show_tally_vs_goal($label_for_goal, $goal, $label_for_actual, $actual)
{
    echo "&nbsp;$label_for_goal ", number_format($goal);
    echo "<br>\n";

    echo "&nbsp;$label_for_actual ", number_format($actual);
    if ($goal)
    {
        // Show $actual as a percentage of $goal.
        echo " (", number_format(($actual/$goal)*100, 2), "%)";
    }
    echo "<br><br>\n";

    echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_user_teams()
{
    global $theme, $code_url, $userP;

    echo "<center>";
    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
    echo "<b>" .  _("Your Teams") . ":</b>";
    echo "</font>";
    echo "</center>";
    echo "\n";
    $teamRes=mysql_query("
        SELECT teamname, id
        FROM user_teams
        WHERE id IN ({$userP['team_1']}, {$userP['team_2']}, {$userP['team_3']})
    ");
    while($row = mysql_fetch_assoc($teamRes))
    {
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
        echo "&nbsp;";
        echo "<a href='$code_url/stats/teams/tdetail.php?tid=".$row['id']."'>".$row['teamname']."</a>";
        echo "</font>";
        echo "<br>";
        echo "\n";
    }
        echo "<center>";
    echo "<font size='2'>";
    echo "<a href='$code_url/stats/teams/tlist.php'>"._("View all teams...")."</a>";
    echo "</font>";
    echo "</center>";
    echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_birthdays()
{
    global $theme, $site_abbreviation;

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

    $sql = sprintf("
        SELECT username, date_created
        FROM users
        WHERE
            FROM_UNIXTIME(date_created, '%%m-%%d') = '%s'
            AND t_last_activity > %s
            AND date_created < %s
        ORDER BY date_created ASC
    ", strftime("%m-%d"), $oldest_activity, $timestamp_at_midnight);

    $result = mysql_query($sql);

    if (mysql_num_rows($result) == 0)
        return;

    echo "<center><b>" . sprintf(_("Today's %s Birthdays"),$site_abbreviation) . ":</b></center>\n";

    echo "<div style='text-align: center; font-family: $theme[font_navbar];
        color: $theme[color_navbar_font];'>";

    $today = getdate(time());
    while ( list($username, $date_created) = mysql_fetch_row($result) )
    {
        $user_date = getdate($date_created);
        $years = $today['year'] - $user_date['year'];

        echo "<font size='2'>$username ($years)</font><br />";
    }

    echo "</div><br><hr width='75%' align='center'>\n";
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_completed_projects($terse = FALSE)
{
    global $theme, $code_url;

    if ($terse) {
        echo "\n<br>\n";
        $num_months = 1;
    } else {
        echo "<center><b>" . _("Completed Projects") . ":</b></center>\n";
        echo "<table border=0 cellspacing=0 cellpadding=0 width='100%'>\n";
        $num_months = 12;
    }
    $thismonth = date("n");
    $thisyear = date("Y");
    for ( $months_ago = $num_months; $months_ago >= 0; $months_ago-- )
    {
        // midnight that begins the 1st day of this month and the next:
        $begindate = mktime(0,0,0,$thismonth-$months_ago,  1,$thisyear);
        $enddate   = mktime(0,0,0,$thismonth-$months_ago+1,1,$thisyear);

        $displaydate = strftime(_("%b %Y"), $begindate );

        $result = mysql_query("
            SELECT COUNT(*)
            FROM projects
            WHERE modifieddate >= $begindate
                and modifieddate <= $enddate
                and state = '".PROJ_SUBMIT_PG_POSTED."'
        ");
        $totalprojects = mysql_result($result,0);

        if ($terse) {
            // TRANSLATORS: string is in the format: $totalprojects in $date
            // where $date is an already-translated strftime()-formatted string
            // with the month and year
            echo sprintf(_('%1$d in %2$s'),$totalprojects,$displaydate) . "&nbsp;&mdash;&nbsp;";
        } else {
            echo "<tr>";
            echo   "<td width='60%' align='right'>";
            echo     "<font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>";
            echo       "&nbsp;$displaydate&nbsp;&mdash;";
            echo     "</font>";
            echo   "</td>";
            echo   "<td width='40%' align='left'>";
            echo     "<font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>";
            echo       "&nbsp;$totalprojects&nbsp;";
            echo     "</font>";
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
    global $theme, $code_url, $forums_url;

    echo "<center>";
    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
    echo "<b>"._("Key Help Documents").":</b>";
    echo "</font>";
    echo "</center>\n";

    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
    echo "<a href='$code_url/faq/".lang_dir()."faq_central.php'>"._("Frequently Asked Questions")."</a>";
    echo "</font>";
    echo "<br>\n";

    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
    echo "<a href='$code_url/faq/".lang_dir()."proofreading_guidelines.php'>"._("Proofreading Guidelines")."</a>";
    echo " (<a href='$code_url/faq/".lang_dir()."proofing_summary.pdf'>"._("PDF Summary")."</a>)";
    echo "</font>";
    echo "<br>\n";

    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
    echo "<a href='$code_url/faq/".lang_dir()."document.php'>"._("Formatting Guidelines")."</a>";
    echo " (<a href='$code_url/faq/".lang_dir()."formatting_summary.pdf'>"._("PDF Summary")."</a>)";
    echo "</font>";
    echo "<br>\n";

    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
    echo "<a href='$forums_url/viewtopic.php?t=2368'>"._("Tips and How-To's")."</a>";
    echo "</font>";
    echo "<br>\n";

    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
    echo "<a href='$code_url/tasks.php'>"._("Support Request")."</a>";
    echo "</font>";
    echo "<br>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_donation_stuff()
{
    global $theme, $theme_name, $code_url, $site_name, $wiki_url;

    $paypal_business_id = 'DPFoundation@pgdp.net';
    $info_url = $wiki_url.'/Distributed_Proofreaders_Foundation#Donations';

    echo "<center>";
    echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>\n";
    echo "<input type='hidden' name='cmd' value='_xclick'>\n";
    echo "<input type='hidden' name='business' value='$paypal_business_id'>\n";
    echo "<input type='hidden' name='item_name' value='donation'>\n";
    echo "<input type='image' src='$code_url/pinc/templates/".$theme_name.$theme['image_donate']."' name='submit' alt='"
        . attr_safe(sprintf(_("Donate to %s"), $site_name)) . "'>\n";
    echo "</form>";
    echo "</center>\n";

    echo "<center>";
    echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>";
    echo sprintf(
        _("Please help support <b>DP</b>! Donate by <b><i>PayPal</i></b> or <b><i>check</i></b> to the <a href='%s'>Distributed Proofreaders Foundation</a>"),
        $info_url
    );
    echo "</font>";
    echo "</center>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function html_footer() {
    global $code_url, $theme, $site_name, $starttime;

    $mtime = explode(" ",microtime());
    $endtime = $mtime[1] + $mtime[0];
    $totaltime = ($endtime - $starttime);

    //Bottom Copyright Text
    echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_copyright_bg']."'>\n";
    echo "<tr>";
    echo "<td width='10'>&nbsp;</td>";
    echo "<td width='100%'>";
    echo "<center>";

    echo "<font color='".$theme['color_copyright_font']."' face='".$theme['font_copyright']."' size='1'>";
    echo _("Copyright")." ". $site_name;
    echo " ("._("Page Build Time").": ".substr($totaltime, 0, 5).") ";
    echo "</font>";

    echo "<a href='$code_url/tasks.php'>";
    echo "<font color='".$theme['color_copyright_font']."' face='".$theme['font_copyright']."' size='1'>";
    echo _("Report a Bug");
    echo "</font>";
    echo "</a>";

    echo "</center>";
    echo "</td>";
    echo "</tr>\n";
    echo "</table>\n";

    //The End
    echo "\n</body>\n";
    echo "</html>\n";
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
//                    - if TRUE, the returned color will be calculated based
//                      on how much of the current day has passed. This is
//                      useful so that at the beginning of a new day the bar
//                      isn't at the low color.
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
        $progress_bar_width = min($percent_complete, 100);
    }
    else
    {
        $percent_complete = 100;
        $progress_bar_width = 100;
    }

    // Calculate the progress bar color scaling based on the time of day.
    if($prorate_goal_for_color)
    {
        $t_start_of_today = mktime(0,0,0,date('m'),date('d'),date('y'));
        $fraction_of_day_passed = (time() - $t_start_of_today) / (60*60*24);
        // Note: Calculation assumes a 24-hour day which is true except
        // possibly for the first and last days of daylight saving time
        // depending on how PHP calculates DST in the mktime() call.
    }
    else
    {
        $fraction_of_day_passed = 1;
    }

    foreach($color_thresholds as $threshold => $color)
    {
        if($percent_complete >= ($threshold * $fraction_of_day_passed))
        {
            $progress_bar_color = $color;
            break;
        }
    }

    return array($progress_bar_width, $progress_bar_color, $percent_complete);
}

// vim: sw=4 ts=4 expandtab
?>
