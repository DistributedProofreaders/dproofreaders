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
$projectid = validate_projectID("projectid", @$_REQUEST['projectid'], true);

$title = _("Project Quick Check");
$page_text = _("This page tests the project in an attempt to uncover some common errors.");

$theme_args['css_data'] = _get_stylesheet();
output_header($title, False, $theme_args);

echo "<h1>$title</h1>";

echo "<p>$page_text</p>";

// show the form
echo "<form method='GET'>";
echo "<table>";
echo  "<tr>"; 
echo   "<td>" . _("Project ID") . "</td>";
echo   "<td><input name='projectid' type='text' value='$projectid'></td>";
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

echo "<p>" . sprintf(_("Project ID: %s"), $projectid) . "</p>";
echo "<p><b>" . html_safe($project->nameofwork) . "</b></p>\n";
echo "<p>" . _("Author") . ": " . html_safe($project->authorsname) . "</p>\n";
echo "<p>" . _("Project Manager") . ": " . html_safe($project->username) . "</p>\n";
echo "<p>";
echo "<a href='$code_url/project.php?id=$projectid'>" . _("Project Page") . "</a>";
if($project->pages_table_exists)
    echo " | <a href='$code_url/tools/proofers/images_index.php?project=$projectid'>" . _("Image Index") . "</a>";
echo "</p>";

if ($project->can_be_managed_by_current_user || $project->PPer_is_current_user)
{
    // good, proceed.
}
else
{
    echo "<p class='error'>" . _("You are not authorized to run this script on that project.") . "</p>";
    exit;
}

// -----------------------------------------------------------------------------

$results = array();
foreach($test_functions as $function)
{
    $result[$function] = $function($projectid);
}

echo "<h1>" . _("Result Summary") . "</h1>";
echo "<table>";
echo "<tr>";
echo "<th>" . _("Test") . "</th>";
echo "<th>" . _("Status") . "</th>";
echo "<th>" . _("Summary") . "</th>";
echo "</tr>";
foreach($test_functions as $function)
{
    echo "<tr>";
    echo "<td>" . $result[$function]["name"] . "</td>";
    echo "<td><a href='#$function'>" . $result[$function]["status"] . "</a></td>";
    echo "<td>" . $result[$function]["summary"] . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h1>" . _("Result Details") . "</h1>";
foreach($test_functions as $function)
{
    echo "<a name='$function'></a>";
    echo "<h2>" . $result[$function]["name"] . "</h2>";
    echo "<p>" . sprintf(_("Status: %s"), $result[$function]["status"]) . "</p>";
    echo "<p>" . $result[$function]["description"] . "</p>";
    echo $result[$function]["details"];
}


//---------------------------------------------------------------------------
// supporting page functions

function _get_stylesheet() {
    global $css_for_bad_bytes_tables;
    return "
        p.error { color: red; }
        p.warning { color: blue; }
        table th { background-color: black; color: white; }
        table td { padding-right: 0.5em; }
        $css_for_bad_bytes_tables
    ";
}

// vim: sw=4 ts=4 expandtab
