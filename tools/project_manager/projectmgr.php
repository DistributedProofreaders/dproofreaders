<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'ProjectSearchForm.inc');
include_once($relPath.'ProjectSearchResults.inc');
include_once($relPath.'site_news.inc');
include_once('projectmgr.inc'); // echo_manager_links();

require_login();

switch ($userP['i_pmdefault'])
{
    case 0:
        $default_view = "user_all";
        break;
    case 1:
        $default_view = "user_active";
        break;
    default:
        $default_view = "blank";
}

try {
    $show_view = get_enumerated_param($_GET, 'show', $default_view,
        array('search_form', 'p_search', 'search', 'blank_search_form', 'user_all', 'user_active',
              'blank', 'ua_search_form'));
} catch(Exception $e) {
    $show_view = 'blank';
}

if(!user_is_PM())
{
    // Redirect to the new search page for bookmarked URLs
    $url = "$code_url/tools/search.php";
    if($show_view == "search")
    {
        // pull out everything after the ? and redirect
        $url .= substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], '?'));
    }
    metarefresh(0, $url);
}

$header_args = array(
    "css_files" => array("$code_url/styles/statsbar.css"),
    "js_files" => array("$code_url/tools/dropdown.js"));

output_header(_("Project Management"), NO_STATSBAR, $header_args);

$search_form = new ProjectSearchForm();

$PROJECT_IS_ACTIVE_sql = "(state NOT IN ('".PROJ_SUBMIT_PG_POSTED."','".PROJ_DELETE."'))";

if($show_view == 'blank_search_form')
{
    $_SESSION['search_data'] = array();
    $show_view = 'search_form';
}
elseif($show_view == 'ua_search_form')
{
    set_session_user_active();
    $show_view = 'search_form';
}

if ($show_view == 'search_form')
{
    echo "<h1>", _("Project Management"), "</h1>";
    $search_form->render();
    exit();
}

if($show_view == "blank")
{
    // TRANSLATORS: Abbreviation for Project Manager
    $sub_title = sprintf(_("PM: %s"), $pguser);
}
elseif($show_view == "user_all")
{
    $condition = "username = '$pguser'";
    $_SESSION['search_data'] = array('project_manager' => $pguser);
    // TRANSLATORS: Abbreviation for Project Manager
    $sub_title = sprintf(_("All PM projects: %s"), $pguser);
}
elseif ($show_view == "user_active")
{
    $condition = "$PROJECT_IS_ACTIVE_sql AND username = '$pguser'";
    set_session_user_active();
    // TRANSLATORS: Abbreviation for Project Manager
    $sub_title = sprintf(_("Active PM projects: %s"), $pguser);
}
else // $show_view == 'p_search' or 'search'
{
    $condition = $search_form->get_condition($show_view);
    // change search to p_search to use saved data if needed later
    // since we are not re-using GET data
    $show_view = 'p_search';
    $sub_title = _("Search Results");
}

$search_results = new ProjectSearchResults($show_view);

// In order to create the sidebar, wrap everything in some divs
echo "<div style='display: table; margin:-.5em -.5em 0 -.5em'>
    <div class='sidebar-color' style='display:table-row;'>";
if($userP['u_align']) // statsbar at left
{
    echo_sidebar();
    echo_main_content('left-statsbar');
}
else
{
    echo_main_content('right-statsbar');
    echo_sidebar();
}
echo "</div></div>";

if($show_view == "blank")
    exit();

$search_results->render($condition);

function echo_main_content($bar_class)
{
    global $sub_title, $show_view, $search_results;

    echo "<div id='pm_content' class='$bar_class'>";
    echo "<h1>", _("Project Management"), "</h1>\n";
    // possibly show message, but don't exit
    check_user_can_load_projects(false);
    show_news_for_page('PM');
    echo "\n<h2 id='head'>$sub_title</h2>\n";
    echo_shortcut_links($show_view, $search_results);
    echo "</div>";
}

function echo_sidebar()
{
    echo "<div id='statsbar'>\n";
    echo_manager_links();
    echo "</div>";
}

function set_session_user_active()
{
    global $pguser, $PROJECT_STATES_IN_ORDER;

    $_SESSION['search_data'] = array(
        'project_manager' => $pguser,
        'state' => array_diff($PROJECT_STATES_IN_ORDER, array(PROJ_SUBMIT_PG_POSTED, PROJ_DELETE)),
    );
}

function create_shortcut_link($text, $url, $show_view="")
{
    if($show_view != $url)
        return "<a href='{$_SERVER['PHP_SELF']}?show=$url'>$text</a>";
    else
        return $text;
}

function echo_shortcut_links($show_view, $search_results)
{
    $links = array(
        // TRANSLATORS: Abbreviation for Project Manager
        create_shortcut_link(_("View your Active PM Projects"), "user_active", $show_view),
        // TRANSLATORS: Abbreviation for Project Manager
        create_shortcut_link(_("View All your PM Projects"), "user_all", $show_view),
        // TRANSLATORS: Abbreviation for Project Manager
        create_shortcut_link(_("Search your Active PM Projects"), "ua_search_form")
    );

    if($show_view != "blank")
        $links[] = get_refine_search_link();

    $links[] = $search_results->get_search_configure_link();

    echo implode(" | ", $links);
}

// vim: sw=4 ts=4 expandtab
