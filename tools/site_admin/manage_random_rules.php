<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

// check to see if the user is authorized to be here
if ( !(user_is_a_sitemanager()) )
{
    die("You are not authorized to use this form.");
}

$action = array_get($_POST, "action", NULL);
$document = get_enumerated_param($_POST, "document", NULL, array_keys(RandomRule::$document_values), true);
$url = array_get($_POST, "url", NULL);
$langcode = strtolower(array_get($_POST, "langcode", NULL));

$title = _("Manage Random Rules");

output_header($title, NO_STATSBAR);

echo "<h1>" . html_safe($title) . "</h1>";

echo "<p>" . _("On the round pages, users are presented a random rule from the proofreading or formatting guidelines. These rules are stored in the database. The database is populated from the guidelines directly, either the guidelines contained within the code or those in the pgdp.net wiki (or similarly-formatted rules), via URL. If the guidelines are updated, the rules will need to be reloaded using this page. Rules are managed as a language-document unit and are deleted or updated as that unit, and cannot be replaced or added as individual rules.") . "</p>";

// do POST actions
if($action == 'update')
{
    RandomRule::reload_rules($url, $document, $langcode);
    // TRANSLATORS: %3$s is a URL
    echo "<p class='warning'>" . sprintf(_('Loaded rules for document %1$s and language %2$s from %3$s.'), RandomRule::$document_values[$document], $langcode, $url) . "</p>";
}
elseif($action == 'delete')
{
    RandomRule::delete_rules($document, $langcode);
    echo "<p class='warning'>" . sprintf(_('Deleted rules for document %1$s and language %2$s.'), RandomRule::$document_values[$document], $langcode) . "</p>";
}

$summary = RandomRule::get_summary();

echo "<table class='themed theme_striped' style='width: auto;'>";
echo "<tr>";
echo "<th>" . _("Language") . "</th>";
echo "<th>" . _("Document") . "</th>";
echo "<th>" . _("Number") . "</th>";
echo "<th></th>";
echo "<th></th>";
echo "</tr>";

$used_langcodes = [];
foreach($summary as $entry)
{
    $used_langcodes[] = $entry['langcode'];
    echo "<tr>";
    echo "<td>" . $entry['langcode']  . "</td>";
    echo "<td>" . RandomRule::$document_values[$entry['document']]  . "</td>";
    echo "<td>" . $entry['count'] . "</td>";
    echo "<td><a href='displayrandrules.php?document=" . $entry['document'] . "&amp;langcode=" . $entry['langcode'] . "'>" . _("View") . "</a></td>";
    echo "<td>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='action' value='delete'>";
        echo "<input type='hidden' name='langcode' value='" . attr_safe($entry['langcode']) . "'>";
        echo "<input type='hidden' name='document' value='" . attr_safe($entry['document']) . "'>";
        echo "<input type='submit' value='" . attr_safe(_("Delete")) . "'>";
        echo "</form>";
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

//----------------------------------------------------------------------------

echo "<h2>" . _("Update random rules") . "</h2>";

echo "<p>" . _("To update rules -- either adding rules for a new language or replacing rules for an existing langauge -- specify a two-letter language code, the document type, and a full URL to parse. The URL can be a pgdp.net wiki page (or a similarly formatted page) or documentation included with the code.") . "</p>";

echo "<p>" . _("To preview what the loaded rules will look like, before overwriting the existing rules, you can load them using a nonexistent language code such as 'test' and view the rules. Once you verify that they look as they should, load them into the correct language and delete your 'test'.") . "</p>";

echo "<form method='POST'>";
echo "<input type='hidden' name='action' value='update'>";
echo _("Language") . ": <input type='text' name='langcode' size='5' maxlength='5' required> ";
echo "<select name='document'>";
foreach(RandomRule::$document_values as $key => $value)
{
    echo "<option value='$key'>$value</option>";
}
echo "</select> ";
echo _("URL") . ": <input type='text' name='url' size='75' required> ";
echo "<input type='submit'>";
echo "</form>";

