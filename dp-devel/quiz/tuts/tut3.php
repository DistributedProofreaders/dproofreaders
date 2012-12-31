<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

output_header(_('Proofreading Tutorial and Interactive Quiz -- Step 3'));

echo "<h2>" . _("Part 3") . "</h2>";

echo "<h3>" . _("Chapter Headers") . "</h3>";

echo "<p>" . _("Leave chapter headers in the text. A chapter header may start a bit farther down the page than the page header and won't have the page numbers on the same line.  Chapter Headers are often printed all caps; if so, keep them as all caps.") . "</p>";

echo "<p>" . _("Put 4 blank lines before the 'CHAPTER XXX'. (Include these blank lines even if the chapter starts on a new page; there are no 'pages' in an e-book, so the blank lines are needed.) Then leave 1 blank line between each additional part of the chapter header, such as a chapter description, opening quote, etc., and finally leave 2 blank lines before the start of the text of the chapter.") . "</p>";

echo "<p>" . _("Old books often printed the first word or two of every chapter in all caps, sometimes even the first word or two of every paragraph; change these to upper and lower case (first letter only capitalized).") . "</p>";

echo "<p>" . _("Watch out for a missing double quote at the start of the first paragraph, which some publishers did not include or which the OCR missed due to a large capital in the original.  If the author started the paragraph with dialog, insert the double quote, even if the publisher left it out or used a large capital instead.") . "</p>";

echo "<h3>" . _("Punctuation characters") . "</h3>";

echo "<p>" . _("In general, there is no space before punctuation characters (except the opening quote). Books typeset in the 1700's &amp; 1800's often had a partial space inserted before punctuation such as a semicolon or comma.  If scanned text has a space before punctuation, remove it.") . "</p>";

echo "<a href='../generic/main.php?type=step3'>" . _("continue") . "</a>";

?>
