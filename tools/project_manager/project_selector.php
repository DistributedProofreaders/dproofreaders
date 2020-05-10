<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'slim_header.inc');
include_once($relPath.'page_controls.inc'); // expanded_rounds()

require_login();

$default_origin = "$code_url/tools/project_manager/display_image_text.php";
$origin = array_get($_GET, "origin", $default_origin);
$error_message = array_get($_GET, "error", "");

$header_args = [
    "body_attributes" => 'class="no-margin"',
];
$title = _("Select a project");
slim_header($title, $header_args);

echo "<div class='control-form'>";

if($error_message)
{
    echo "<p class='error'>", $error_message, "</p>";
}
echo "<p>", $title, "</p>";

echo "<form method='get' action='", attr_safe($origin), "'>\n";
echo _("Project ID"), ": <input type='text' maxlength='25' name='project' size='25' required> \n";
echo "<input type='submit' value='", _("Select Project"), "'> &nbsp; &nbsp;";
echo _("Page"), ":&nbsp;<input type='text' name='imagefile' size='8'> ", _("(optional)"), " &nbsp; \n";
echo _("Round"), ": <select name='round_id' id='round'>\n";
foreach (expanded_rounds() as $round)
{
    echo "<option value='$round'>$round</option>\n";
}
echo "</select>\n";
echo "</form>
</div>\n"; // control-form
