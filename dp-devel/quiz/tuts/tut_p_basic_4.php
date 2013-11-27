<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

output_header(_('Basic Proofreading Tutorial -- Step 4'));

echo "<h2>" . _("Basic Proofreading Tutorial, Step 4") . "</h2>\n";
echo "<h3>" . _("Punctuation Spacing") . "</h3>\n";
echo "<p>" . _("In general, a punctuation mark should have a space after it but no space before it. If the OCR'd text has no space after a punctuation mark, add one; if there is a space before punctuation, remove it. However, punctuation marks that normally appear in pairs, such as \"quotation marks\", (parentheses), [brackets], and {braces} normally have a space before the opening mark, which should be retained.") . "</p>\n";

echo "<h3>" . _("Illustrations") . "</h3>\n";
echo "<p>" . _("Ignore illustrations, but proofread any caption text as it is printed, preserving the line breaks. If the caption falls in the middle of a paragraph, use blank lines to set it apart from the rest of the text.") . "</p>\n";

echo "<h3>" . _("Footnotes/Endnotes") . "</h3>\n";
echo "<p>" . _("Proofread footnotes by leaving the text of the footnote at the bottom of the page and placing a tag where it is referenced in the text.") . "</p>\n";
echo "<p>" . _("In the main text, the character that marks a footnote location should be surrounded with square brackets (<tt>[</tt> and <tt>]</tt>) and placed right next to the word being footnoted<tt>[1]</tt> or its punctuation mark,<tt>[2]</tt> as shown in the image and the two examples in this sentence. Footnote markers may be numbers, letters, or symbols. When footnotes are marked with a symbol or a series of symbols (*, &dagger;, &Dagger;, &sect;, etc.) we replace them all with <tt>[*]</tt> in the text, and <tt>*</tt> next to the footnote itself.") . "</p>\n";
echo "<p>" . _("At the bottom of the page, proofread the footnote text as it is printed, preserving the line breaks. Be sure to use the same tag before the footnote as you used in the text where the footnote was referenced. Use just the character itself for the tag, without any brackets or other punctuation.") . "</p>\n";

echo "<h3>" . _("Formatting") . "</h3>\n";
echo "<p>" . _("You may sometimes find formatting already present in the text. <b>Do not add or change this formatting information</b>; the formatters will do that later in the process. Some examples of formatting tasks include &lt;i&gt;italics&lt;/i&gt; for <i>italicized</i> text.") . "</p>\n";

echo "<p><a href='../generic/main.php?type=p_basic_4&quiz_id=BPQ1'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
