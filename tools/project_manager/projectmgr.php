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
include_once($relPath.'ProjectSearchResults.inc');
include_once('projectmgr.inc');

require_login();

$default_view = ($userP['i_pmdefault'] == 0 ?  "user_all" : "user_active");
$show_view = array_get($_GET, 'show', $default_view);

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

output_header(_("Project Management"), NO_STATSBAR);

$PROJECT_IS_ACTIVE_sql = "(state NOT IN ('".PROJ_SUBMIT_PG_POSTED."','".PROJ_DELETE."'))";

echo_manager_header();

// Construct and submit the search query.
if($show_view == "site_active") {
    $view_title = _("Active Projects");
    $condition = $PROJECT_IS_ACTIVE_sql;
} elseif($show_view == "user_all") {
    $condition = "username = '$pguser'";
    $view_title = _("Your Projects");
} else {
    // "user_active" plus some corner cases
    $condition = "$PROJECT_IS_ACTIVE_sql AND username = '$pguser'";
    $view_title = _("Your Active Projects");
}

// Determine whether to use special colors or not
// (this does not affect the alternating between two
// background colors) in the project listing.
$userSettings =& Settings::get_Settings($pguser);
$show_special_colors = !$userSettings->get_boolean('hide_special_colors');

echo "<h1>", _("Project Management"), "</h1>\n";
echo "<h2>$view_title</h2>";

$search_results = new ProjectSearchResults(100);
$results_offset = intval(@$_GET['results_offset']);
$search_results->render($condition, $results_offset, $show_special_colors);

// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No".
// The legend has been put at the bottom of the page
// because the use of colors is presumably mostly
// useful as a check that no typo was made. The
// exact color probably doesn't matter and,
// furthermore, the PM 'knows' the project and
// what's so special about it.
if (!$userSettings->get_boolean('hide_special_colors')) {
    echo_special_legend(" 1 = 1");
}
echo "<br>";

// vim: sw=4 ts=4 expandtab
