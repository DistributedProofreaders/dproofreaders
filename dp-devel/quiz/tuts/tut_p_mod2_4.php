<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

output_header(_('Moderate Proofreading Tutorial -- Page 9'));

echo "<h2>" . _("Moderate Proofreading Tutorial, Page 9") . "</h2>\n";
echo "<h3>" . _("Quote Marks on each line") . "</h3>\n";
echo "<p>" . _("Proofread quotation marks at the beginning of each line of a quotation by removing all of them <b>except for</b> the one at the start of the quotation. If a quotation like this goes on for multiple paragraphs, leave the quote mark that appears on the first line of each paragraph.") . "</p>\n";
echo "<p>" . _("Often there is no closing quotation mark until the very end of the quoted section of text, which may not be on the same page you are proofreading. Leave it that way&mdash;do not add closing quotation marks that are not in the page image.") . "</p>\n";

echo "<p><a href='../generic/main.php?type=p_mod2_4&quiz_id=MPQ2'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
