<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(sprintf(_("Moderate Proofreading Tutorial: Part %d"), 1));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial: Part %1\$d, Page %2\$d"), 1, 5) . "</h2>\n";
echo "<h3>" . _("Footnotes/Endnotes") . "</h3>\n";
echo "<p>" . _("In the main text of the page, the character that marks a footnote
    location should be surrounded with square brackets (<kbd>[</kbd> and <kbd>]</kbd>)
    and placed next to the word<kbd>[*]</kbd> being footnoted or its punctuation mark.<kbd>[*]</kbd>") . "</p>\n";
echo "<p>" . _("At the bottom of the page, place each footnote on a separate line in
    order of appearance, with a blank line before each one.") . "</p>\n";
echo "<p>" . _("Do not include any horizontal lines separating the footnotes from the main text.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod1_5'>" . _("Continue to quiz page") . "</a></p>\n";
