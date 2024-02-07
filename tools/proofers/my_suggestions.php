<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'User.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'js_newwin.inc'); // get_js_for_links_to_project_pages(),  get_onclick_attr_for_link_to_project_page()
include_once($relPath.'gradual.inc'); // get_pages_proofed_maybe_simulated()
include_once($relPath.'graph_data.inc'); // get_round_backlog_stats()

require_login();

$verbose = get_integer_param($_GET, "verbose", 0, 0, 1);
$flush_cache = get_integer_param($_GET, "flush_cache", 0, 0, 1);

$username = User::current_username();
if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
    $username = array_get($_GET, 'username', $username);
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

$selection_criteria = get_user_suggestion_criteria($username, $flush_cache);

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

[$projects, $explain_why] = get_suggestions($round_view, $username, $selection_criteria);
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
        echo "<input type='text' name='username' value='" . attr_safe($username) . "' required>";
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
    global $code_url, $Round_for_round_id_;

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
            if ($Round_for_round_id_[$row["round"]]->is_a_mentee_round()
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

function get_suggestions($round_view, $username, $selection_criteria)
{
    global $Round_for_project_state_, $ELR_round;

    // common project fields needed for all SQL queries
    $project_select_fields = <<<SQL
            projectid, nameofwork, state, language, genre, difficulty,
            n_pages, n_available_pages,
            1 - (n_available_pages / n_pages) AS percent_done,
            (unix_timestamp() - modifieddate)/(24 * 60 * 60) AS days_checkedout,
            -- following two fields are used to later build a Project object
            -- to validate a user can work in it
            special_code, modifieddate
        SQL;

    // assign by value so we can alter with the new variable name
    $site_weights = &$selection_criteria["weights"]["site"];
    $user_weights = &$selection_criteria["weights"]["user"];
    $view_weights = &$selection_criteria["weights"]["view"][$round_view];

    // only pull projects in states that the user can access right now
    if ($selection_criteria["filters"]["accessible_states"]) {
        $avail_state_clause = sprintf(
            "state in (%s)",
            surround_and_join($selection_criteria["filters"]["accessible_states"], "'", "'", ',')
        );
    } else {
        // user is not approved to work in any state
        return [[], ""];
    }

    // weight beginner-level projects first if the user is a beginner
    if ($selection_criteria["filters"]["is_beginner"]) {
        $user_weights["difficulty"] = [
            "beginner" => 0.5,
            "easy" => 0.2,
        ];
        $beginner_clause = "1";
    } else {
        // if the user isn't a beginner, exclude all beginner projects
        $beginner_clause = sprintf("difficulty <> 'beginner'");
    }

    // get a sorted list of the rounds a user can work in sorted sorted by
    // which ones have the most work in them (eg backlog)
    $weighted_accessible_backlog = array_values(
        array_intersect(
            array_keys($site_weights["round"]),
            $selection_criteria["filters"]["accessible_rounds"]
        )
    );

    if ($round_view == "impact") {
        $explain_why = sprintf(
            _("Here are some of the oldest projects in rounds you can work in that have the largest backlogs, primarily %s. They've been weighted by your most commonly proofread language: %s. Working on any project in the list will help reduce the biggest backlogs and finish the oldest projects."),
            $weighted_accessible_backlog[0],
            array_get_first($user_weights["language"], "key")
        );

        $sql = "
            SELECT
                $project_select_fields
            FROM projects
            WHERE $avail_state_clause
                AND $beginner_clause
        ";
    } elseif ($round_view == "familiar") {
        $explain_why = sprintf(
            _("Here are some projects you've worked in before, weighted by the rounds with the largest backlogs, primarily %s."),
            $weighted_accessible_backlog[0]
        );

        $sql = sprintf(
            "
            SELECT
                user_project_info.projectid,
                $project_select_fields
            FROM user_project_info LEFT OUTER JOIN projects USING (projectid)
            WHERE
                user_project_info.t_latest_page_event > 0
                AND user_project_info.username='%s'
                AND $avail_state_clause
                AND $beginner_clause
            ",
            DPDatabase::escape($username)
        );

    } elseif ($round_view == "style") {
        // if we don't have any genre data, bail
        if (!$user_weights["genre"]) {
            return [[], ""];
        }

        $explain_why = sprintf(
            _("Here are a selection of projects primarily in <i>%s</i> weighted by your most common genre <i>%s</i> and difficulty <i>%s</i> followed by the rounds with the largest backlogs."),
            array_get_first($user_weights["language"], "key"),
            array_get_first($user_weights["genre"], "key"),
            array_get_first($user_weights["difficulty"], "key")
        );

        $sql = "
            SELECT
                $project_select_fields
            FROM projects
            WHERE $avail_state_clause
                AND $beginner_clause
        ";
    } elseif ($round_view == "different") {
        $explain_why = sprintf(
            _("Here are projects in %s, your most proofread language, that you've never worked on before."),
            array_get_first($user_weights["language"], "key")
        );

        if ($selection_criteria["filters"]["too_familiar_genres"]) {
            $genre_not_clause = sprintf(
                "genre not in (%s)",
                surround_and_join($selection_criteria["filters"]["too_familiar_genres"], "'", "'", ',')
            );

            $explain_why .= " " . sprintf(
                _("We've filtered out those with genres that you proofread in most often: %s."),
                join(", ", $selection_criteria["filters"]["too_familiar_genres"])
            );
        } else {
            $genre_not_clause = "1";
        }

        $sql = sprintf(
            "
            SELECT
                $project_select_fields
            FROM projects
            WHERE $avail_state_clause
                AND projectid NOT IN (
                    SELECT projectid
                    FROM user_project_info
                    WHERE
                        t_latest_page_event > 0
                        AND username='%s'
                    )
                AND $genre_not_clause
                AND $beginner_clause
            ",
            DPDatabase::escape($username)
        );
    } elseif ($round_view == "getting-started") {
        $explain_why = _("The following projects are good ones to get started in. Click one to open up the project page, read the project comments, and then click 'Start Proofreading'.");

        $sql = sprintf(
            "
            SELECT
                $project_select_fields
            FROM projects
            WHERE
                difficulty in ('beginner', 'easy', 'average')
                AND state = '%s'
            ",
            $ELR_round->project_available_state,
        );
    }

    // for each project returned from the query, generate a priority field
    // calculated by the view's weights paired with that field's weights from
    // the selection criteria. The priority field is used for sorting the rows
    $now = time();
    $result = DPDatabase::query($sql);
    $projects = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row["created"] = hexdec(substr($row["projectid"], 9, 8));
        $row["round"] = $Round_for_project_state_[$row["state"]]->id;

        $priority = 1;
        foreach ($view_weights as $field => $weight) {
            // dynamic fields calculated from project
            if ($field == "created") {
                $field_weight = ($now - $row["created"]) / $now;
            } elseif ($field == "percent_done") {
                $field_weight = 1 - $row["percent_done"];
            } else {
                $normalized_field_value = $row[$field];
                // only use the primary language for the project when weighting
                if ($field == "language") {
                    $normalized_field_value = Project::decode_language($row[$field])[0];
                }

                // Default field weight needs to be non-zero
                $field_weight = 0.01;

                // get the field weight from the user weights if one exists,
                // otherwise try the site weights
                if (isset($user_weights[$field][$normalized_field_value])) {
                    $field_weight = $user_weights[$field][$normalized_field_value];
                } elseif (isset($site_weights[$field][$normalized_field_value])) {
                    $field_weight = $site_weights[$field][$normalized_field_value];
                }
            }
            $priority *= $weight * $field_weight;
        }
        $row["priority"] = $priority;

        $projects[] = $row;
    }
    mysqli_free_result($result);

    uasort($projects, "compare_array_by_priority");

    $user = new User($username);
    $return_projects = [];
    // loop through the sorted rows checking each one against the ability
    // for the proofreader to work on it *right now* based on the reserve, etc.
    foreach (array_reverse($projects) as $project_row) {
        // only return 20 projects
        if (count($return_projects) >= 20) {
            break;
        }

        // Build the project from the row so we don't have to go back to
        // the database again for every project. This requires all the necessary
        // fields used here, and in dependent functions, be populated in the row.
        $project = new Project($project_row);

        try {
            $round = $Round_for_project_state_[$project->state];
            validate_user_against_project_reserve($user, $project, $round);
            $return_projects[] = $project_row;
        } catch (Exception $exception) {
            continue;
        }
    }
    return [$return_projects, $explain_why];
}

function compare_array_by_priority($a, $b)
{
    if ($a["priority"] == $b["priority"]) {
        return 0;
    }

    return ($a["priority"] < $b["priority"]) ? -1 : 1;
}

function get_user_suggestion_criteria($username, $flush_cache = false)
{
    global $Round_for_round_id_, $testing;

    // We cache this in the session table so we don't calculate it on every
    // page load. We keep it for an hour.
    $cache_valid_duration = 60 * 60;
    $cache_key = "my_suggestions:criteria";
    if (!$flush_cache &&
        $username == User::current_username() &&
        isset($_SESSION[$cache_key]) &&
        $_SESSION[$cache_key]["cache_expires_at"] > time()) {
        return $_SESSION[$cache_key];
    }

    // rounds the user can work in [ID] => $avail_state
    $states_can_work_in = [];
    foreach (get_stages_user_can_work_in($username) as $stage_id => $stage) {
        // only include rounds
        if (isset($Round_for_round_id_[$stage_id])) {
            $states_can_work_in[$stage_id] = $stage->project_available_state;
        }
    }

    $backlog_stats = get_round_backlog_stats(array_keys($Round_for_round_id_));
    arsort($backlog_stats);

    // ELR pages
    $elr_pages = get_pages_proofed_maybe_simulated($username);

    // get some info about projects they've worked on in the past (cumulative)
    // Language
    $user_languages = get_user_counts_for_project_field($username, "language");
    // reduce to just primary languages
    foreach ($user_languages as $language => $count) {
        if (stripos($language, "with") !== false) {
            @$user_languages[Project::decode_language($language)[0]] += $count;
            unset($user_languages[$language]);
        }
    }
    // if the user hasn't proofread any projects, lets assume they'll want to
    // proofread in the language the UI is in and fall back to English
    if (!$user_languages) {
        $user_languages = [
            lang_name(short_lang_code(get_user_language($username))) => 5,
        ];
        $user_languages["English"] = 1;
    }

    // Genre
    $user_genres = get_user_counts_for_project_field($username, "genre");

    // Difficulty
    $user_difficulty = get_user_counts_for_project_field($username, "difficulty");
    unset($user_difficulty["beginner"]);

    $criteria = [
        "filters" => [
            "accessible_rounds" => array_keys($states_can_work_in),
            "accessible_states" => array_values($states_can_work_in),
            "too_familiar_genres" => array_slice(array_keys($user_genres), 0, 5),
            "elr_pages" => $elr_pages,
            "is_beginner" => $elr_pages < 100,
        ],
        "weights" => [
            "view" => [
                "impact" => [
                    "round" => 100,
                    "created" => 80,
                    "language" => 50,
                ],
                "familiar" => [
                    "created" => 100,
                    "round" => 100,
                ],
                "style" => [
                    "genre" => 50,
                    "language" => 50,
                    "difficulty" => 50,
                    "round" => 10,
                    "created" => 1,
                ],
                "different" => [
                    "language" => 50,
                    "percent_done" => 20,
                ],
                "getting-started" => [
                    "language" => 100,
                    "difficulty" => 50,
                ],
            ],
            "site" => [
                "round" => calculate_normalized_weights($backlog_stats),
            ],
            "user" => [
                "language" => calculate_normalized_weights($user_languages),
                "genre" => calculate_normalized_weights($user_genres),
                "difficulty" => calculate_normalized_weights($user_difficulty),
            ],
        ],
    ];

    // only cache for the user looking at their own suggestions
    if ($username == User::current_username()) {
        $criteria["cache_expires_at"] = time() + $cache_valid_duration;
        $_SESSION[$cache_key] = $criteria;
    }

    return $criteria;
}

function get_user_counts_for_project_field($username, $field, $limit = 8)
{
    // $field *must* be a valid field in the projects table!
    $sql = sprintf(
        "
        SELECT
            COUNT($field) as count,
            $field
        FROM user_project_info LEFT OUTER JOIN projects USING (projectid)
        WHERE
            user_project_info.username='%s'
            AND user_project_info.t_latest_page_event > 0
        GROUP BY $field
        ORDER BY count DESC
        LIMIT %d
        ",
        $username,
        $limit
    );
    $field_counts = [];
    $result = DPDatabase::query($sql);
    while ([$count, $value] = mysqli_fetch_row($result)) {
        $field_counts[$value] = $count;
    }
    mysqli_free_result($result);
    return $field_counts;
}

function calculate_normalized_weights($criteria)
{
    $new_criteria = [];
    $total = array_sum(array_values($criteria));
    foreach ($criteria as $key => $val) {
        $new_criteria[$key] = $val / $total;
    }
    return $new_criteria;
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
