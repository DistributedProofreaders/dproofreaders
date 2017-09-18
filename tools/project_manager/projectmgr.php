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
        array('search_form', 'search', 'blank_search_form', 'user_all', 'user_active',
              'site_active', 'blank', 'ua_search_form'));
} catch(Exception $e) {
    $show_view = 'blank';
}

// Redirect to the new search page for bookmarked URLs
if($show_view == "search")
{
    // pull out everything after the ? and redirect
    $url = "$code_url/tools/search.php" . substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], '?'));
    metarefresh(0, $url);
}

if(!user_is_PM())
{
    die(_("Your user permissions do not allow access to this script."));
}

$header_args = array(
    "css_files" => array("$code_url/styles/statsbar.css", "$code_url/styles/dropdown.css"),
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
elseif($show_view == "site_active")
{
    $sub_title = _("All Active Projects");
    $_SESSION['search_data'] = array(
        'state' => array(PROJ_SUBMIT_PG_POSTED, PROJ_DELETE),
        'state_inv' => 'on'
    );
    $condition = $PROJECT_IS_ACTIVE_sql;
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
else // ($show_view == 'search')
{
    if(empty($_POST))
        $condition = array_get($_SESSION, 'search_condition', "1");
    else
    {
        // Construct the search query.
        $condition = $search_form->get_widget_contribution($_POST);
        $_SESSION['search_condition'] = $condition;
        // save the POST data to use to initialise the search form
        $_SESSION['search_data'] = $_POST;
    }
    $sub_title = _("Search Results");
}

// In order to create the sidebar, wrap everything in some divs
echo "<div style='display: table; margin:-.5em'>
    <div class='sidebar-color' style='display:table-row;'>";

// Main page content
echo "<div style='display: table-cell; padding:.5em; background-color: #ffffff; width: 75%; border-top-right-radius: 1.2em'>";
echo "<h1>", _("Project Management"), "</h1>\n";
// possibly show message, but don't exit
check_user_can_load_projects(false);
show_news_for_page('PM');
echo "\n<h2>$sub_title</h2>\n";
echo_shortcut_links($show_view);
if($show_view != "blank")
    echo_refine_search();
echo "</div>";

// Sidebar content
echo "<div id='statsbar'>\n";
echo_manager_links();
echo "</div>";

echo "</div></div>";

if($show_view == "blank")
    exit();

$search_results = new ProjectSearchResults($show_view);
$search_results->render($condition);

function set_session_user_active()
{
    global $pguser;
    $_SESSION['search_data'] = array(
        'project_manager' => $pguser,
        'state' => array(PROJ_SUBMIT_PG_POSTED, PROJ_DELETE),
        'state_inv' => 'on'
    );
}

class ShortCutLink
{
    public function __construct($txt, $code, $show_view = " ")
    {
        $this->text = $txt;
        $this->url = $code;
        $this->active = ($show_view != $code);
    }

    function echo_link()
    {
        if($this->active)
            echo "<a href='{$_SERVER['PHP_SELF']}?show=$this->url'>$this->text</a>&ensp;\n";
        else
            echo "$this->text&ensp;\n";
    }
}

function echo_shortcut_links($show_view)
{
    $links = array(
        // TRANSLATORS: Abbreviation for Project Manager
        new ShortCutLink(_("View your Active PM Projects"), "user_active", $show_view),
        // TRANSLATORS: Abbreviation for Project Manager
        new ShortCutLink(_("View All your PM Projects"), "user_all", $show_view),
        // TRANSLATORS: Abbreviation for Project Manager
        new ShortCutLink(_("Search your Active PM Projects"), "ua_search_form")
    );

    foreach($links as $link)
        $link->echo_link();
}

// vim: sw=4 ts=4 expandtab
