<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Basic Proofreading Tutorial'));

echo "<h1>" . _("Proofreading Tutorial") . "</h1>\n";
echo "<h2>" . _("Intro") . "</h2>\n";
echo "<p>" . _("In this tutorial you will be presented extracts from the Proofreading Guidelines. After each part you will be led to a quiz page, where you can try out the newly learned rules.") . "</p>\n";
echo "<p>" . _("Don't be alarmed by the number of changes you'll have to apply in the quizzes. On normal easy texts the rate of things to correct is usually much lower than that.") . "</p>\n";

echo "<h2>" . sprintf(_("Basic Proofreading Tutorial, page %d"), 1) . "</h2>\n";
echo "<h3>" . _("Project Comments") . "</h3>\n";
echo "<p>" . _("When you select a project for proofreading, the Project Page is loaded. On this page there is a section called \"Project Comments\" containing information specific to that project (book). <b>Read these before you start proofreading pages!</b> If the Project Manager wants you to do something in this book differently from the way specified in these Guidelines, that will be noted here. Instructions in the Project Comments <em>override</em> the rules in these Guidelines, so follow them. There may also be instructions in the project comments that apply to the formatting phase, which do not apply during proofreading.") . "</p>\n";

echo "<h3>" . _("The Primary Rule") . "</h3>\n";
echo "<p>\"<em>" . _("Don't change what the author wrote!") . "\"</em></p>\n";
echo "<p>" . _("The final electronic book seen by a reader, possibly many years in the future, should accurately convey the intent of the author. If the author spelled words oddly, we leave them spelled that way. If the author wrote outrageous racist or biased statements, we leave them that way. If the author put commas, superscripts, or footnotes every third word, we keep the commas, superscripts, or footnotes. We are proofreaders, <b>not</b> editors.") . "</p>\n";
echo "<p>" . _("We do change minor typographical conventions that don't affect the sense of what the author wrote.") . "</p>\n";

echo "<h3>" . _("Page Headers/Page Footers") . "</h3>\n";
echo "<p>" . _("Remove page headers and page footers, but <em>not</em> footnotes, from the text.") . "</p>\n";
echo "<p>" . _("The page headers are normally at the top of the image and have a page number opposite them. Page headers may be the same all through the book (often the title of the book and the author's name), they may be the same for each chapter (often the chapter number), or they may be different on each page (describing the action on that page). Remove them all, regardless, including the page number.") . "</p>\n";

echo "<h3>" . _("End-of-line Hyphenation") . "</h3>\n";
echo "<p>" . _("Where a hyphen appears at the end of a line, join the two halves of the hyphenated word back together. Remove the hyphen when you join it, unless it is really a hyphenated word like well-meaning. Keep the joined word on the top line, and put a line break after it to preserve the line formatting&mdash;this makes it easier for volunteers in later rounds.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_basic_1'>" . _("Continue to quiz page") . "</a></p>\n";

