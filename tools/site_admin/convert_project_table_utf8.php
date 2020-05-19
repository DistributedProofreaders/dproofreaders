<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');

require_login();

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}

$title = _("Convert Project Table to UTF-8");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

echo "<p>" . _("This tool will convert individual project tables to UTF-8 if they are not already. If the project table is already UTF-8 no changes will happen.") . "</p>";

$projectid = validate_projectID('projectid', @$_REQUEST['projectid'], true);

if ( !$projectid )
{
    echo "<form method='GET'>";
    echo "Project: ";
    echo "<input type='text' name='projectid' size='23' required>";
    echo "<input type='submit' value='Go'>";
    echo "</form>\n";
    exit;
}

$project = new Project($projectid);
$title = $project->nameofwork;

echo "<pre>";
echo "projectid: $projectid\n";
echo "title    : $title\n";
echo "</pre>\n";

if($project->is_utf8)
{
    echo "<p>" . _("Project table is already UTF-8.") . "</p>";
}
else
{
    echo "<p>" . _("Project tabie is not UTF-8.") . "<p>";
    echo "<p>" . _("Converting project table...") . "</p>";
    $project->convert_to_utf8();
    echo "<p>" . _("Done") . "</p>";
}
