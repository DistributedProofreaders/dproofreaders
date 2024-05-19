<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');

require_login();

$query = trim(array_get($_GET, "q", "help"));

// "help" is just a string to get us here, but we don't want to keep it
if ($query == "help") {
    $query = "";
}

if ($query !== "") {
    // try a jump search first
    jump_search($query);

    // try a smart search
    smart_search($query);

    try {
        // try a prefix search
        prefix_search($query);

        $message = sprintf(_("Not sure what to do with '%s', maybe try one of the prefixes below."), $query);
    } catch (Exception $exception) {
        $message = sprintf(_("The input for your prefix search was invalid: %s"), $exception->getMessage());
    }
}

$title = _("Site Search");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

if (@$message) {
    echo "<p class='warning'>" . html_safe($message) . "</p>";
}

echo "<form>";
echo "<input type='text' name='q' value='" . attr_safe($query) . "'> ";
echo "<input type='submit' value='" . attr_safe(_("Search")) . "'>";
echo "</form>";

echo "<p>" . _("Site search attempts to figure out what you're looking for and get you to it quickly.") . "</p>";

echo "<ul>";
echo "<li>" . _("Enter a projectID to go to the project's page.") . "</li>";
echo "<li>" . sprintf(
    _("Enter an activity (%s) to go to the activity's page."),
    surround_and_join(array_keys(get_activity_jumps()), "<kbd>", "</kbd>", ", ")
) . "</li>";
echo "</ul>";

echo "<p>" . _("The following prefixes can be used to search across various categories.") . "</p>";

echo "<ul>";
foreach (get_prefix_searches() as $prefix => $data) {
    echo "<li>";
    echo "<kbd>$prefix:</kbd> - " . $data["desc"];
    if (@$data["alias"]) {
        echo " (" . sprintf(_("alias: %s"), "<kbd>" . $data["alias"] . ":</kbd>") . ")";
    }
    echo "</li>";
}
echo "</ul>";

echo "<p>" . _("In addition, the following words can be used to jump to specific site pages.") . "</p>";

echo "<ul>";
foreach (get_jump_words() as $prefix => $data) {
    echo "<li>";
    echo "<kbd>$prefix</kbd> - " . $data["desc"];
    if (@$data["alias"]) {
        echo " (" . sprintf(_("alias: %s"), "<kbd>" . $data["alias"] . "</kbd>") . ")";
    }
    echo "</li>";
}
echo "</ul>";

echo "<hr>";

echo "<p>" . _("You can access site search in three ways:") . "</p>";
echo "<ul>";
echo "<li>" . _("Use the search icon (the magnifying glass) in the header to start a site search. You can cancel it with the escape key.") . "</li>";
echo "<li>" . _("When on any page with the logo in the header, typing the <kbd>/</kbd> key on your keyboard will bring up the search field as long as an input field doesn't already have focus.") . "</li>";
echo "<li>" . sprintf(
    _("Add this page to your browser's custom search feature (eg <a href='%s'>smart keywords</a> in Firefox, <a href='%s'>site shortcuts</a> in Chrome).<br>The URL to use in searching is <kbd>%s</kbd>"),
    "https://support.mozilla.org/en-US/kb/how-search-from-address-bar",
    "https://support.google.com/chrome/answer/95426",
    "$code_url/tools/site_search.php?q=%s"
) . "</li>";
echo "</ul>";

//----------------------------------------------------------------------------

function get_prefix_searches(bool $flatten_aliases = false): array
{
    global $code_url, $wiki_url;

    $prefix_searches = [
        "title" => [
            "url" => "$code_url/tools/search.php?show=search&title=%s",
            "desc" => _("Project search for this in the title"),
            "alias" => "t",
        ],
        "author" => [
            "url" => "$code_url/tools/search.php?show=search&author=%s",
            "desc" => _("Project search for this in the author"),
            "alias" => "a",
        ],
        "pm" => [
            "url" => "$code_url/tools/search.php?show=search&project_manager=%s",
            "desc" => _("Project search for this project manager"),
        ],
        "user" => [
            "url" => "$code_url/stats/members/mbr_list.php?uname=%s",
            "desc" => _("Search for this username or part of a username"),
            "alias" => "u",
        ],
        "team" => [
            "url" => "$code_url/stats/teams/tlist.php?tname=%s",
            "desc" => _("Search for this team name or part of a team name"),
        ],
        "task" => [
            "url" => "$code_url/tasks.php?q=%s",
            "desc" => _("Task search by task ID or part of a task summary"),
        ],
    ];

    if (get_url_for_forum()) {
        $prefix_searches["forum"] = [
            "url" => get_url_for_search("%s", false),
            "desc" => _("Search for this in forum posts"),
            "alias" => "f",
        ];
    }

    if (!empty($wiki_url)) {
        $prefix_searches["wiki"] = [
            "url" => "$wiki_url/Special:Search/Index.php?profile=all&search=%s",
            "desc" => _("Search for this in the wiki"),
            "alias" => "w",
        ];
    }

    if ($flatten_aliases) {
        foreach ($prefix_searches as $key => &$data) {
            if (isset($data["alias"])) {
                $alias = $data["alias"];
                unset($data["alias"]);
                $prefix_searches[$alias] = $data;
            }
        }
    }

    return $prefix_searches;
}

function get_jump_words(bool $flatten_aliases = false): array
{
    global $code_url, $wiki_url, $blog_url;

    $jump_words = [
        "activityhub" => [
            "url" => "$code_url/activity_hub.php",
            "desc" => _("Activity Hub"),
            "alias" => "ah",
        ],
        "home" => [
            "url" => "$code_url/default.php",
            "desc" => _("Home Page"),
        ],
        "myprojects" => [
            "url" => "$code_url/tools/proofers/my_projects.php",
            "desc" => _("My Projects"),
            "alias" => "myproj",
        ],
        "mysuggestions" => [
            "url" => "$code_url/tools/proofers/my_suggestions.php",
            "desc" => _("My Suggestions"),
            "alias" => "mysugg",
        ],
        "preferences" => [
            "url" => "$code_url/userprefs.php",
            "desc" => _("Personal Preferences"),
            "alias" => "prefs",
        ],
        "quizzes" => [
            "url" => "$code_url/quiz/start.php",
            "desc" => _("Interactive Quizzes and Tutorials"),
            "alias" => "quiz",
        ],
        "statistics" => [
            "url" => "$code_url/stats/stats_central.php",
            "desc" => _("Statistics Central"),
            "alias" => "stats",
        ],
        "tasks" => [
            "url" => "$code_url/tasks.php",
            "desc" => _("Task Center"),
        ],
    ];

    if (get_url_for_forum()) {
        $jump_words["forum"] = [
            "url" => get_url_for_forum(),
            "desc" => _("Forums"),
        ];
        $jump_words["inbox"] = [
            "url" => get_url_for_inbox(),
            "desc" => _("Inbox"),
        ];
    }

    if (!empty($wiki_url)) {
        $jump_words["wiki"] = [
            "url" => $wiki_url,
            "desc" => _("Wiki"),
        ];
    }

    if (!empty($blog_url)) {
        $jump_words["blog"] = [
            "url" => $blog_url,
            "desc" => _("Blog"),
        ];
    }

    if (user_is_PM()) {
        $jump_words["pm"] = [
            "url" => "$code_url/tools/project_manager/projectmgr.php",
            "desc" => _("Project Management"),
        ];
        $jump_words["rfm"] = [
            "url" => "$code_url/tools/project_manager/remote_file_manager.php",
            "desc" => _("Remote File Manager"),
        ];
    }

    if (user_is_a_sitemanager()) {
        $jump_words["sa"] = [
            "url" => "$code_url/tools/site_admin/index.php",
            "desc" => _("Site Administration"),
        ];
    }

    if ($flatten_aliases) {
        foreach ($jump_words as $key => &$data) {
            if (isset($data["alias"])) {
                $alias = $data["alias"];
                unset($data["alias"]);
                $jump_words[$alias] = $data;
            }
        }
    }

    ksort($jump_words);
    return $jump_words;
}

function get_activity_jumps(): array
{
    global $code_url;

    $activity_jumps = [];
    foreach (Activities::get_all() as $activity) {
        if ($activity instanceof Round) {
            $activity_jumps[$activity->id] = "$code_url/tools/proofers/round.php?round_id=$activity->id";
        } elseif ($activity instanceof Pool) {
            $activity_jumps[$activity->id] = "$code_url/tools/pool.php?pool_id=$activity->id";
        } elseif ($activity->id === "SR") {
            // SR is such a special snowflake
            $activity_jumps[$activity->id] = "$code_url/tools/post_proofers/smooth_reading.php";
        }
    }

    return $activity_jumps;
}

function jump_search(string $query)
{
    $query = strtolower($query);
    $jump_words = get_jump_words(true);
    if (isset($jump_words[$query])) {
        metarefresh(0, $jump_words[$query]["url"], "", "", true);
    }
}

function smart_search(string $query)
{
    global $code_url;

    // is it a valid projectID?
    try {
        new Project($query);
        metarefresh(0, "$code_url/project.php?id=$query");
    } catch (NonexistentProjectException $e) {
        // pass
    }

    // is it an Activity?
    $activity_jumps = get_activity_jumps();
    if (isset($activity_jumps[strtoupper($query)])) {
        metarefresh(0, $activity_jumps[strtoupper($query)]);
    }
}

function prefix_search(string $query)
{
    $matches = [];
    if (!preg_match('/^(\w+):\s*(.*)$/', $query, $matches)) {
        return;
    }

    $prefix_searches = get_prefix_searches(true);
    $data = $prefix_searches[strtolower($matches[1])] ?? null;
    if (!$data) {
        return;
    }

    $query = $matches[2];

    metarefresh(0, sprintf($data["url"], urlencode($query)), "", "", true);
}
