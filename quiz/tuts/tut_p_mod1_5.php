<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Moderate Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial, Page %d"), 5) . "</h2>\n";
echo "<h3>" . _("Footnotes/Endnotes") . "</h3>\n";
echo "<p>" . _("Place each footnote on a separate line in order of appearance, with a blank line before each one.") . "</p>\n";
echo "<p>" . _("Do not include any horizontal lines separating the footnotes from the main text.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod1_5'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
