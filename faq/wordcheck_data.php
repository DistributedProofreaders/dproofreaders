<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("WordCheck Site Data");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>";

echo "<h2 id='site_word_lists'>" . _("Site Word Lists") . "</h2>";
echo "<p>" . _("Site-level words are stored in language-specific files.") . "</p>";
echo "<p>" . _("Site-level Good and Bad word lists are used when calculating Flagged words in a body of text. Here is the current set of such lists.") . "</p>";

createWordListTable(get_site_good_bad_word_lists());

echo "<p>" . _("Possible Bad word lists are used to suggest possible Bad words for a Project Manager. Here is the current set of such lists.") . "</p>";

createWordListTable(get_site_possible_bad_word_lists());

function createWordListTable($word_lists)
{
    // return if there aren't any word_lists
    if (count($word_lists) == 0) {
        echo "<p style='padding-left: 2em'><i>" . _("None") . "</i></p>";
        return;
    }

    // start the table and build the header
    echo "<table style='padding-left: 2em'>";
    echo "<tr>";
    echo "<th>" . _("Name") . "</th>";
    echo "<th>" . _("Number of Words") . "</th>";
    echo "<th>" . _("Last modified") . "</th>";
    echo "</tr>";

    // loop through the word lists building rows as we go
    foreach ($word_lists as $word_list_file => $word_list_url) {
        $filename = basename($word_list_file);
        $word_count = count(explode("\n", file_get_contents($word_list_file))) - 1;
        $modifiedString = date('Y-m-d H:i', filemtime($word_list_file));
        echo "<tr>";
        echo "<td><a href=\"$word_list_url\">$filename</a></td>";
        echo "<td style='text-align: right'>$word_count</td>";
        echo "<td>$modifiedString</td>";
        echo "</tr>";
    }

    // close the table and we're done
    echo "</table>";
}

echo "<h2 id='site_dictionaries'>" . _("Site Dictionaries") . "</h2>";
echo "<p>" . _("The following languages have dictionaries installed on the site.") . "</p>";

echo "<ul>";
$languages = array_values(get_languages_with_dictionaries());
sort($languages);
foreach ($languages as $language) {
    echo "<li>$language</li>";
}
echo "</ul>";
