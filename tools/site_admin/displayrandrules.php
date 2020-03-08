<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

$document = get_enumerated_param($_GET, "document", key(RandomRule::$document_values), array_keys(RandomRule::$document_values));
$langcode = strtolower(array_get($_GET, "langcode", "en"));

require_login();

$title = _("Random Rules");
output_header($title, NO_STATSBAR);

echo "<h1>" . html_safe($title) . "</h1>";

if ( user_is_a_sitemanager() )
{
    echo "<p><a href='manage_random_rules.php'>" . _("Manage Random Rules") . "</a></p>";
}

echo "<p>" . sprintf(_('Rules from document %1$s for langcode %2$s.'), RandomRule::$document_values[$document], $langcode) . "</hp>";

foreach(RandomRule::get_rules($document, $langcode) as $rule)
{
    echo "<hr>\n";
    echo "<div><b>ID:</b> $rule->id &mdash; $rule->subject (anchored as \"#$rule->anchor\")</div>\n";
    echo "<div>$rule->rule</div>\n";
}

// vim: sw=4 ts=4 expandtab
