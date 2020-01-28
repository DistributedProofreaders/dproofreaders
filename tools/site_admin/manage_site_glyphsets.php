<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'Glyphsets.inc');

require_login();

// check to see if the user is authorized to be here
if ( !(user_is_a_sitemanager()) )
{
    die("You are not authorized to use this form.");
}

$messages = [];

$all_glyphsets = Glyphsets::get_all();

// if there was a post, do things
if(count($_POST))
{
    foreach($all_glyphsets as $glyphset)
    {
        if(isset($_POST[$glyphset->name]))
        {
            if(!$glyphset->is_enabled())
            {
                Glyphsets::enable($glyphset->name);
                $messages[] = sprintf(_("Enabled glyphset %s"), $glyphset->title);
            }
        }
        else
        {
            if($glyphset->is_enabled())
            {
                Glyphsets::disable($glyphset->name);
                $messages[] = sprintf(_("Disabled glyphset %s"), $glyphset->title);
            }
        }
    }
}

$title = _("Manage Site Glyphsets");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

foreach($messages as $message)
{
    echo "<p class='warning'>$message</p>";
}

echo "<p>" . _("Use this page to view and manage site glyphsets. Disabled glyphsets cannot be added to new projects but can still be used with existing projects configured to use them.") . "</p>";


echo "<form method='post'>";
echo "<table class='themed theme_striped' style='width: auto;'>";
echo "<tr>";
echo "<th>" . _("Glyphset") . "</th>";
echo "<th>" . _("Slug") . "</th>";
echo "<th>" . _("Enabled") . "</th>";
echo "<th class='right-align'>" . _("# Projects") . "</th>";
echo "</tr>";
usort($all_glyphsets, function($a, $b) { return strcmp($a->title, $b->title); });
foreach($all_glyphsets as $glyphset)
{
    echo "<tr>";
    $glyphset_slug = attr_safe($glyphset->name);
    echo "<td>" . $glyphset->title . "</td>";
    echo "<td><a href='../glyphsets.php?glyphset=$glyphset_slug'>" . $glyphset->name . "</a></td>";
    $checked = $glyphset->is_enabled() ? "CHECKED" : "";
    echo "<td><input type='checkbox' name='$glyphset_slug' value='$glyphset_slug' $checked></td>";
    echo "<td class='right-align'>" . count(Project::projects_using_glyphset($glyphset)) . "</td>";
    echo "</tr>";
}
echo "</table>";
$submit = attr_safe(_("Update site glyphsets"));
echo "<input type='submit' value='$submit'>";
echo "</form>";

