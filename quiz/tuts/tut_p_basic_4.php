<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_('Basic Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Basic Proofreading Tutorial, Step %d"), 4) . "</h2>\n";
echo "<h3>" . _("Punctuation spacing") . "</h3>\n";
echo "<p>" . _("In general, a punctuation mark should have a space after it but no space before it. If the OCR'd text has no space after a punctuation mark, add one; if there is a space before punctuation, remove it. However, punctuation marks that normally appear in pairs, such as \"quotation marks\", (parentheses), [brackets], and {braces} normally have a space before the opening mark, which should be retained.") . "</p>\n";

echo "<h3>" . _("Illustrations") . "</h3>\n";
echo "<p>" . _("Ignore illustrations, but proofread any caption text as it is printed, preserving the line breaks. If the caption falls in the middle of a paragraph, use blank lines to set it apart from the rest of the text.") . "</p>\n";

echo "<h3>" . _("Footnotes/Endnotes") . "</h3>\n";
echo "<p>" . _("Proofread footnotes by leaving the text of the footnote at the bottom of the page and placing a tag where it is referenced in the text.") . "</p>\n";
echo "<p>" . _("In the main text, the character that marks a footnote location should be surrounded with square brackets (<kbd>[</kbd> and <kbd>]</kbd>) and placed right next to the word being footnoted<kbd>[1]</kbd> or its punctuation mark,<kbd>[2]</kbd> as shown in the image and the two examples in this sentence. Footnote markers may be numbers, letters, or symbols. When footnotes are marked with a symbol or a series of symbols (<kbd>*</kbd>, <kbd>†</kbd>, <kbd>‡</kbd>, <kbd>§</kbd>, etc.) we replace them all with <kbd>[*]</kbd> in the text, and <kbd>*</kbd> next to the footnote itself.") . "</p>\n";
echo "<p>" . _("At the bottom of the page, proofread the footnote text as it is printed, preserving the line breaks. Be sure to use the same tag before the footnote as you used in the text where the footnote was referenced. Use just the character itself for the tag, without any brackets or other punctuation.") . "</p>\n";

echo "<h3>" . _("Formatting") . "</h3>\n";
echo "<p>" . _("You may sometimes find formatting already present in the text. <b>Do not add or change this formatting information</b>; the formatters will do that later in the process. Some examples of formatting tasks include &lt;i&gt;italics&lt;/i&gt; for <i>italicized</i> text.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_basic_4'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
