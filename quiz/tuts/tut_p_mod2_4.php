<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Moderate Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial, page %d"), 9) . "</h2>\n";
echo "<h3>" . _("Quote marks on each line") . "</h3>\n";
echo "<p>" . _("Proofread quotation marks at the beginning of each line of a quotation by removing all of them <b>except for</b> the one at the start of the quotation. If a quotation like this goes on for multiple paragraphs, leave the quote mark that appears on the first line of each paragraph.") . "</p>\n";
echo "<p>" . _("Often there is no closing quotation mark until the very end of the quoted section of text, which may not be on the same page you are proofreading. Leave it that way&mdash;do not add closing quotation marks that are not in the page image.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod2_4'>" . _("Continue to quiz page") . "</a></p>\n";

