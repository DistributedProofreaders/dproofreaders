<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Random Rules");
output_header($title, NO_STATSBAR);

echo "<h1>" . html_safe($title) . "</h1>";

$documents = [
    "proofreading_guidelines.php",
    "formatting_guidelines.php",
];

foreach($documents as $document)
{
    echo "<h2>" . sprintf(_("Rules from %s"), $document) . "</h2>";
    foreach(RandomRule::get_rules($document) as $rule)
    {
        echo "<hr>\n";
        echo "<div><b>ID:</b> $rule->id &mdash; $rule->subject (anchored as \"#$rule->anchor\")</div>\n";
        echo "<div>$rule->rule</div>\n";
    }
}

// vim: sw=4 ts=4 expandtab
