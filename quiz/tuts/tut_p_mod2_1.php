<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(sprintf(_("Moderate Proofreading Tutorial: Part %d"), 2));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial: Part %1\$d, Page %2\$d"), 2, 1) . "</h2>\n";
echo "<h3>" . _("Paragraph Side-Descriptions (Sidenotes)") . "</h3>\n";
echo "<p>" . _("Some books have short descriptions of the paragraph along the side of the text. These are called sidenotes. Proofread the sidenote text as it is printed, preserving the line breaks (while handling end-of-line hyphenation and dashes normally).") . "</p>\n";
echo "<p>" . _("Leave a blank line before and after the sidenote so that it can be distinguished from the text around it. The OCR may place the sidenotes anywhere on the page, and may even intermingle the sidenote text with the rest of the text. Separate them so that the sidenote text is all together, but don't worry about the position of the sidenotes on the page.") . "</p>\n";

echo "<h3>" . _("Multiple Columns") . "</h3>\n";
echo "<p>" . _("Proofread ordinary text that has been printed in multiple columns as a single column. Place the text from the left-most column first, the text from the next column below that, and so on. Do not mark where the columns were split, just join them together.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod2_1'>" . _("Continue to quiz page") . "</a></p>\n";
