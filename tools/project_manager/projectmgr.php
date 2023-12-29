<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Settings.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'ProjectSearchForm.inc');
include_once($relPath.'ProjectSearchResults.inc');
include_once($relPath.'site_news.inc');
include_once('projectmgr.inc'); // echo_manager_links();

require_login();

$user = User::load_current();
$user_settings = Settings::get_Settings($user->username);
$default_view = $user_settings->get_value("pm_view", "user_all");

try {
    $show_view = get_enumerated_param(
        $_GET,
        'show',
        $default_view,
        ['search_form', 'search', 'user_all', 'user_active',
            'blank', 'set_columns', 'config', ]
    );
} catch (Exception $e) {
    $show_view = 'blank';
}

if (!user_is_PM()) {
    // Redirect to the new search page for bookmarked URLs
    $url = "$code_url/tools/search.php";
    if ($show_view == "search") {
        // pull out everything after the ? and redirect
        $url .= substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], '?'));
    }
    metarefresh(0, $url);
}

// exits if handled
handle_set_cols($show_view, "PM");

$server_timezone = date_default_timezone_get();
$lang_code = short_lang_code();
$js_files = [
    "$code_url/tools/dropdown.js",
    "$code_url/tools/project_manager/projectmgr.js",
    "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js",
    "https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.23/moment-timezone-with-data.js",
];

if ($lang_code != "en") {
    $js_files[] = "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/{$lang_code}.js";
}

$header_args = [
    "js_files" => $js_files,
    "js_data" =>
        "var serverTimezone = '$server_timezone';",
];

output_header(_("Project Management"), NO_STATSBAR, $header_args);

// exits if handled
handle_config($show_view, "PM", _("Configure Project Management Page"));

$PROJECT_IS_ACTIVE_sql = "(state NOT IN ('".PROJ_SUBMIT_PG_POSTED."','".PROJ_DELETE."'))";

if ($show_view == 'search_form') {
    $search_form = new ProjectSearchForm();
    echo "<h1>", _("Project Management"), "</h1>";
    $search_form->render();
    exit();
}

if ($show_view == "user_all") {
    $condition = "username = '$user->username'";
    // adjust $_GET so will work corectly with refine search and sort and navigate
    // keep "user_all" or we won't know it is user all
    $_GET = array_merge($_GET, ['project_manager' => $user->username]);
} elseif ($show_view == "user_active") {
    $condition = "$PROJECT_IS_ACTIVE_sql AND username = '$user->username'";
    $_GET = array_merge($_GET, [
        'project_manager' => $user->username,
        'state' => array_diff($PROJECT_STATES_IN_ORDER, [PROJ_SUBMIT_PG_POSTED, PROJ_DELETE]),
    ]);
} else { // $show_view == 'search'
    $search_form = new ProjectSearchForm();
    $condition = $search_form->get_condition();
}

$search_results = new ProjectSearchResults("PM");

echo_manager_links();
echo "<h1>", _("Project Management"), "</h1>\n";
// possibly show message, but don't exit
check_user_can_load_projects(false);
show_news_for_page('PM');
echo_shortcut_links($show_view);

if ($show_view == "blank") {
    exit();
}

$search_results->render($condition);

function echo_shortcut_links($show_view)
{
    $views = [
        "user_active" => [
            // TRANSLATORS: Abbreviation for Project Manager
            "label" => _("Your Active PM Projects"),
        ],
        "user_all" => [
            // TRANSLATORS: Abbreviation for Project Manager
            "label" => _("All Your PM Projects"),
        ],
        "search" => [
            "label" => _("Search"),
            "url" => get_refine_search_url(),
        ],
    ];

    output_tab_bar($views, $show_view, "show", "", "auto");

    $links = [];
    if ($show_view == "search") {
        $links[] = get_refine_search_link();
    }

    if ($show_view != "blank") {
        $links[] = get_search_configure_link();
    }

    if ($links) {
        echo "<p>" . implode(" | ", $links) . "</p>";
    }
}
