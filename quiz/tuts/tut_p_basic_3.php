<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Basic Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Basic Proofreading Tutorial, page %d"), 3) . "</h2>\n";
echo "<h3>" . _("Chapter Headings") . "</h3>\n";
echo "<p>" . _("Proofread chapter headings as they appear in the image.") . "</p>\n";
echo "<p>" . _("A chapter heading may start a bit farther down the page than the page header and won't have a page number on the same line. Chapter Headings are often printed all caps; if so, keep them as all caps.") . "</p>\n";
echo "<p>" . _("Watch out for a missing double quote at the start of the first paragraph, which some publishers did not include or which the OCR missed due to a large capital in the image. If the author started the paragraph with dialog, insert the double quote.") . "</p>\n";

echo "<h3>" . _("Large, Ornate opening Capital letter (Drop Cap)") . "</h3>\n";
echo "<p>" . _("Proofread a large and ornate graphic first letter of a chapter, section, or paragraph as if it were an ordinary letter.") . "</p>\n";

echo "<h3>" . _("Period Pause \"...\" (Ellipsis)") . "</h3>\n";
echo "<p>" . _("An ellipsis should have three dots.  Regarding the spacing, in the middle of a sentence treat the three dots as a single word (i.e., usually a space before the 3 dots and a space after). At the end of a sentence treat the ellipsis as ending punctuation, with no space before it.") . "</p>\n";
echo "<p>" . _("Note that there will also be an ending punctuation mark at the end of a sentence, so in the case of a period there will be 4 dots total. Remove extra dots, if any, or add new ones, if necessary, to bring the number to three (or four) as appropriate.") . "</p>\n";

echo "<h3>" . _("Formatting") . "</h3>\n";
echo "<p>" . _("You may sometimes find formatting already present in the text. <b>Do not add or correct this formatting information</b>; the formatters will do that later in the process. Some examples of formatting tasks include &lt;b&gt;bold&lt;/b&gt; for <b>bold</b> text.") . "</p>\n";

echo "<h3>" . _("Words in Small Capitals") . "</h3>\n";
echo "<p>" . _("Please proofread only the characters in <span style='font-variant: small-caps'>Small Caps</span> (capital letters which are smaller than the standard capitals). Do not worry about case changes. If the OCR'd text is already ALL-CAPPED, Mixed-Cased, or lower-cased, leave it ALL-CAPPED, Mixed-Cased, or lower-cased.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_basic_3'>" . _("Continue to quiz page") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
