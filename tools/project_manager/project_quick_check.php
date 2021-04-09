<?php
// Run a bunch of quick tests on a given project,
// looking for common errors/problems.
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // array_get(), get_enumerated_param(), html_safe()
include_once($relPath.'Project.inc'); // Project::Project
include_once($relPath.'project_quick_check.inc');

require_login();

set_time_limit(0); // no time limit

// get data passed into the page
$projectid = get_projectID_param($_REQUEST, "projectid", true);

$title = _("Project Quick Check");
$page_text = _("This page tests the project in an attempt to uncover some common errors.");

output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

echo "<p>$page_text</p>";

// show the form
echo "<form method='GET'>";
echo "<table>";
echo  "<tr>";
echo   "<td>" . _("Project ID") . "</td>";
echo   "<td><input name='projectid' type='text' value='$projectid' size='40' required></td>";
echo  "</tr>";
echo "</table>";
echo "<input type='submit' value='Test'>";
echo "</form>";

// stop if no projectid was specified
if(empty($projectid))
{
    exit;
}

echo "<hr>";

// #############################################################################

$project = new Project($projectid);

echo "<h1>" . _("Project Summary") . "</h1>";

echo "<table class='basic'>";
echo "<tr>";
echo    "<th>" . _("Title") . "</th>";
echo    "<td>" . html_safe($project->nameofwork) . "</td>";
echo "</tr>";
echo "<tr>";
echo    "<th>" . _("Project ID") . "</th>";
echo    "<td>$projectid</td>";
echo "</tr>";
echo "<tr>";
echo    "<th>" . _("Author") . "</th>";
echo    "<td>" . html_safe($project->authorsname) . "</td>";
echo "</tr>";
echo "<tr>";
echo    "<th>" . _("Project Manager") . "</th>";
echo    "<td>" . html_safe($project->username) . "</td>";
echo "</tr>";
echo "</table>";

echo "<p>";
echo "<a href='$code_url/project.php?id=$projectid'>" . _("Project Page") . "</a>";
if($project->pages_table_exists)
    echo " | <a href='$code_url/tools/proofers/images_index.php?project=$projectid'>" . _("Image Index") . "</a>";
echo "</p>";

if (!$project->user_can_do_quick_check())
{
    echo "<p class='error'>" . _("You are not authorized to run this script on that project.") . "</p>";
    exit;
}

// -----------------------------------------------------------------------------

$results = get_pqc_test_results($projectid);

echo "<h1>" . _("Result Summary") . "</h1>";
show_pqc_result_summary($results);

echo "<h1>" . _("Result Details") . "</h1>";
foreach($results as $function => $test_result)
{
    echo "<a name='$function'></a>";
    echo "<h2>" . $test_result["name"] . "</h2>";
    $css = get_css_for_pqc_status($test_result["status"]);
    echo "<p $css>" . sprintf(_("Status: %s"), $test_result["status"]) . "</p>";
    echo "<p>" . $test_result["description"] . "</p>";
    echo $test_result["details"];
}

