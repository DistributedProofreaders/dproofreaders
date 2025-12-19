<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'User.inc');
include_once($relPath.'js_newwin.inc'); // get_js_for_links_to_project_pages(),  get_onclick_attr_for_link_to_project_page()
include_once($relPath.'gradual.inc'); // get_pages_proofed_maybe_simulated()
include_once($relPath.'graph_data.inc'); // get_round_backlog_stats()

require_login();

$verbose = get_integer_param($_GET, "verbose", 0, 0, 1);
$flush_cache = get_bool_param($_GET, "flush_cache", false);

$username = User::current_username();
if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
    $username = $_GET['username'] ?? $username;
    if (!User::is_valid_user($username)) {
        die("Invalid username.");
    }
}

$round_view_options = get_view_options($username);

// Get changes to the round views. If not set, we
// pull the last selected option from UserSettings.
$userSettings = & Settings::get_Settings(User::current_username());
$round_view = get_enumerated_param(
    $_GET,
    "round_view",
    $userSettings->get_value("my_suggestions:round_view", "impact"),
    array_keys($round_view_options)
);

// Update saved view settings if they've changed
foreach (["round_view"] as $setting) {
    if ($$setting != $userSettings->get_value("my_suggestions:$setting")) {
        $userSettings->set_value("my_suggestions:$setting", $$setting);
    }
}

$user_suggestions = new UserSuggestions($username);
$selection_criteria = $user_suggestions->get_criteria($flush_cache);

if ($username == User::current_username()) {
    $title = _("My Suggestions");
} else {
    $title = sprintf(_("%s's Suggestions"), $username);
}

$extra_args['js_data'] = get_js_for_links_to_project_pages();

output_header($title, NO_STATSBAR, $extra_args);

output_link_box($username, $verbose);

echo "<h1>" . html_safe($title) . "</h1>";

echo "<p>" . _("Looking for something to work on? You're in the right place! All of the projects below are available for you to start proofreading <i>right now</i>!") . "</p>";

// if the user is a beginner we want to hold their hand and just show them
// beginner/easy projects in the $ELR_round.
if ($selection_criteria["filters"]["is_beginner"]) {
    welcome_see_beginner_forum($selection_criteria["filters"]["elr_pages"], $ELR_round->id, $username);
    $round_view = "getting-started";
} else {
    unset($round_view_options["getting-started"]);

    echo "<p>" . _("This page incorporates information about what you've worked on before (project languages, genres, etc), what rounds you can work in, what rounds have the biggest backlog, and some math to suggest projects for you.") . "</p>";

    show_page_menu($round_view_options, $round_view, $username, $verbose, 'round_view');
}

[$projects, $explain_why] = $user_suggestions->get_suggestions($round_view);
$colspecs = get_table_column_specs();
if (!$verbose) {
    unset($colspecs["priority"]);
}
if (count($projects) == 0) {
    echo "<p>" . $round_view_options[$round_view]["text_none"] . "</p>";
} else {
    if ($explain_why) {
        // we don't html_safe() as they can contain HTML
        echo "<p>$explain_why</p>";
    }

    output_suggestion_table($projects, $colspecs, $username);
}

if ($verbose) {
    echo "<hr>";
    echo "<h2>" . _("Verbose") . "</h2>";
    echo "<p>" . $round_view_options[$round_view]["text_verbose"] . "</p>";
    output_selection_criteria($selection_criteria);
}

// --------------------------------------------------------------------------

function output_link_box($username, $verbose)
{
    echo "<div id='linkbox'>";
    if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
        echo "<form method='get'><p>";
        echo _("See projects for another user") . "<br>";
        echo "<input type='text' name='username' value='" . attr_safe($username) . "' autocapitalize='none' required>";
        echo "<input type='submit' value='" . attr_safe(_("Refresh")) . "'>";
        $checked = $verbose ? "checked" : "";
        echo "<input type='checkbox' name='verbose' value='1' $checked>";
        echo "</p></form>\n";
    }
    echo "</div>";
}

// --------------------------------------------------------------------------

function get_table_column_specs()
{
    // $colspecs = array (
    //     $id => array ( 'label' => $label, 'class' => $class )
    // );
    // $id is the column name as passed by GET argument.
    // $label is the translatable label displayed in the column header
    // $class is the HTML class to use for the field on output
    return [
        'title' => [
            'label' => _('Title'),
        ],
        'round' => [
            'label' => _('Round'),
        ],
        'language' => [
            'label' => _('Language'),
        ],
        'genre' => [
            'label' => _('Genre'),
        ],
        'difficulty' => [
            'label' => _('Difficulty'),
        ],
        'created' => [
            'label' => _('Created'),
        ],
        'n_available_pages' => [
            'label' => _('Available<br>Pages'),
            'class' => 'right-align',
        ],
        'percent_done' => [
            'label' => _('Done'),
            'class' => 'right-align',
        ],
        'days_checkedout' => [
            'label' => _('Days in State'),
            'class' => 'right-align',
        ],
        'priority' => [
            'label' => _("Sort Key"),
        ],
    ];
}

// to make sure that some projects are displayed, iterate over the view order
function get_view_options($username)
{
    return [
        "impact" => [
            "label" => _("Biggest impact"),
            "text_none" => _("No biggest impact projects found."),
            "text_verbose" => _("This view shows a selection of projects available in the rounds you are approved to work in weighted by (in descending order) the rounds with the biggest backlog, the date the project was created, and the languages you most often proofread in."),
        ],
        "familiar" => [
            "label" => _("Something familiar"),
            "text_none" => _("No projects you've worked on prior are currently available, check back later."),
            "text_verbose" => _("This view shows selection of projects you've worked on in the past that are currently available in a round you are approved to work in weighted equally by the date the project was created and the rounds with the biggest backlog."),
        ],
        "style" => [
            "label" => _("Just my style"),
            "text_none" => _("No similar projects found, probably not enough data to make a good suggestion."),
            "text_verbose" => _("This view shows selection of projects available in the rounds you are approved to work in weighted equally by the genre, languages, and difficulty you typically work in, then weighted (in descending order) by the rounds with the biggest backlog and the date the project was created."),
        ],
        "different" => [
            "label" => _("Find something different"),
            "text_none" => _("No new and intriguing projects found."),
            "text_verbose" => _("This view shows selection of projects available in the rounds you are approved to work in <i>excluding</i> those you've worked on already and those with genres you work most frequently in then weighted (in descending order) by the languages you typically work in and the project's percentage complete in the current round."),
        ],
        "getting-started" => [
            "label" => _("Getting Started"),
            "text_none" => _("No beginner projects found or you are not yet approved to proofread."),
            "text_verbose" => _("This view highlights beginner and easy projects for new proofreaders."),
        ],
    ];
}

function show_page_menu($all_view_modes, $round_view, $username, $verbose, $key)
{
    $qs_username = "";
    if (User::current_username() != $username) {
        $qs_username = "username=$username";
    }

    $qs_verbose = "";
    if ($verbose) {
        if ($qs_username) {
            $qs_verbose = "&amp;";
        }
        $qs_verbose .= "verbose=1";
    }

    output_tab_bar($all_view_modes, $round_view, $key, "$qs_username$qs_verbose");
}

function show_headings($colspecs, $username, $anchor)
{
    echo "<tr>\n";
    foreach ($colspecs as $col_id => $colspec) {
        $class = '';
        if (isset($colspec['class'])) {
            $class = sprintf("class='%s'", $colspec['class']);
        }
        echo "<th $class>";
        $qs_username = "";
        if ($username != User::current_username()) {
            $qs_username = "username=" . urlencode($username) . '&amp;';
        }
        echo $colspec['label'];
        echo "</th>";
    }
    echo "</tr>\n";
}

function output_suggestion_table($projects, $colspecs, $username)
{
    global $code_url;

    echo "<table class='themed theme_striped' style='width: auto;'>";

    show_headings($colspecs, $username, 'round_view');

    foreach ($projects as $row) {
        echo "<tr>\n";

        echo "<td>";
        $url = "$code_url/project.php?id=" . $row["projectid"];
        $onclick_attr = get_onclick_attr_for_link_to_project_page($url);
        echo "<a href='$url' $onclick_attr>" . html_safe($row["nameofwork"]) . "</a>";
        echo "</td>\n";

        if (isset($colspecs['round'])) {
            echo "<td class='nowrap'>";
            echo $row["round"];
            echo "</td>\n";
        }

        if (isset($colspecs['time'])) {
            echo "<td class='nowrap'>";
            echo date('Y-m-d H:i', $row["max_timestamp"]);
            echo "</td>\n";
        }

        if (isset($colspecs['language'])) {
            echo "<td class='nowrap'>";
            echo $row["language"];
            echo "</td>\n";
        }

        if (isset($colspecs['genre'])) {
            echo "<td class='nowrap'>";
            echo $row["genre"];
            echo "</td>\n";
        }

        if (isset($colspecs['difficulty'])) {
            echo "<td class='nowrap'>";
            // Make the difficulty label here match that in show_project_listing()
            if (Rounds::get_by_id($row["round"])->is_a_mentee_round()
                && $row["difficulty"] == "beginner") {
                echo _("BEGINNERS ONLY");
            } else {
                echo $row["difficulty"];
            }
            echo "</td>\n";
        }

        if (isset($colspecs['created'])) {
            echo "<td class='nowrap'>";
            echo icu_date_template("short", $row["created"]);
            echo "</td>\n";
        }

        if (isset($colspecs['n_available_pages']) && isset($colspecs['percent_done'])) {
            echo "<td class='right-align'>";
            echo $row["n_available_pages"];
            echo "</td>\n";

            echo "<td class='right-align'>";
            echo sprintf("%d%%", $row["percent_done"] * 100);
            echo "</td>\n";
        }

        if (isset($colspecs['days_checkedout'])) {
            echo "<td class='right-align'>";
            echo sprintf("%0.1f", $row["days_checkedout"]);
            echo "</td>\n";
        }

        if (isset($colspecs['priority'])) {
            echo "<td class='right-align'>";
            echo sprintf("%0.f", $row["priority"]);
            echo "</td>\n";
        }

        echo "</tr>\n";
    }

    echo "</table>\n";
    echo "<br>\n";
}

function output_selection_criteria($criteria)
{
    echo "<p>";
    echo _("Available selection criteria. Not all views use all criteria and there is special logic for beginners.");
    echo "<ul>";
    foreach ($criteria as $key => $values) {
        echo "<li><b>$key</b>: ";
        if ($key == "filters") {
            echo "<ul>";
            foreach ($values as $filter => $constraint) {
                echo "<li><b>$filter</b>: ";
                if (is_array($constraint)) {
                    echo join(" | ", $constraint);
                } elseif (is_bool($constraint)) {
                    echo($constraint ? "True" : "False");
                } else {
                    echo $constraint;
                }
                echo "</li>";
            }
            echo "</ul>";
        } elseif ($key == "weights") {
            echo "<ul>";
            foreach ($values as $category => $category_weights) {
                echo "<li><b>$category</b>";
                echo "<ul>";
                foreach ($category_weights as $sub_category => $weights) {
                    echo "<li><b>$sub_category</b>: ";
                    $parts = [];
                    foreach ($weights as $value => $weight) {
                        $parts[] = sprintf("$value: %0.2f", $weight);
                    }
                    echo join(" | ", $parts);
                    echo "</li>";
                }
                echo "</ul>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo $values;
        }
        echo "</li>";
    }
    echo "</ul>";
    echo "</p>";
}

function array_get_first($array, $part)
{
    $key = array_keys($array)[0];
    if ($part == "key") {
        return $key;
    } else {
        return $array[$key];
    }
}
