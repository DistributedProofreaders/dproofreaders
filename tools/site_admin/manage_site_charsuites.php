<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'CharSuites.inc');

require_login();

// check to see if the user is authorized to be here
if ( !(user_is_a_sitemanager()) )
{
    die("You are not authorized to use this form.");
}

$messages = [];

$all_charsuites = CharSuites::get_all();

// if there was a post, do things
if(count($_POST))
{
    foreach($all_charsuites as $charsuite)
    {
        if(isset($_POST[$charsuite->name]))
        {
            if(!$charsuite->is_enabled())
            {
                CharSuites::enable($charsuite->name);
                $messages[] = sprintf(_("Enabled character suite %s"), $charsuite->title);
            }
        }
        else
        {
            if($charsuite->is_enabled())
            {
                CharSuites::disable($charsuite->name);
                $messages[] = sprintf(_("Disabled character suite %s"), $charsuite->title);
            }
        }
    }
}

$title = _("Manage Site Character Suites");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

foreach($messages as $message)
{
    echo "<p class='warning'>$message</p>";
}

echo "<p>" . _("Use this page to view and manage site character suites. Disabled character suites cannot be added to new projects but can still be used with existing projects configured to use them.") . "</p>";


echo "<form method='post'>";
echo "<table class='themed theme_striped' style='width: auto;'>";
echo "<tr>";
echo "<th>" . _("Character Suite") . "</th>";
echo "<th>" . _("Short Name") . "</th>";
echo "<th>" . _("Enabled") . "</th>";
echo "<th class='right-align'>" . _("# Projects") . "</th>";
echo "</tr>";
usort($all_charsuites, function($a, $b) { return strcmp($a->title, $b->title); });
foreach($all_charsuites as $charsuite)
{
    echo "<tr>";
    $charsuite_slug = attr_safe($charsuite->name);
    echo "<td>" . $charsuite->title . "</td>";
    echo "<td><a href='../charsuites.php?charsuite=$charsuite_slug'>" . $charsuite->name . "</a></td>";
    $checked = $charsuite->is_enabled() ? "CHECKED" : "";
    echo "<td><input type='checkbox' name='$charsuite_slug' value='$charsuite_slug' $checked></td>";
    echo "<td class='right-align'>" . count(Project::projects_using_charsuite($charsuite)) . "</td>";
    echo "</tr>";
}
echo "</table>";
$submit = attr_safe(_("Update site character suites"));
echo "<input type='submit' value='$submit'>";
echo "</form>";

