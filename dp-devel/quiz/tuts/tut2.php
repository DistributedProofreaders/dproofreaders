<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

theme( _('Proofreading Tutorial and Interactive Quiz -- Step 2'),'header');

echo "<h2>" . _("Part 2") . "</h2>";

echo "<h3><a name='para_space'>" . _("Paragraph Spacing/Indenting") . "</a></h3>";

echo "<p>" . _("Do not indent the start of paragraphs; instead put a blank line between paragraphs.") . "</p>";

echo "<h3><a name='eol_hyphen'>" . _("End-of-line Hyphenation") . "</a> </h3>";

echo "<p>" . _("Where a hyphen appears at the end of a line, join the two halves of the hyphenated word back together. If it is really a hyphenated word like well-meaning, join the two halves leaving the hyphen in-between. But if it was just hyphenated because it wouldn't fit on the line, and is not a word that is usually hyphenated, then join the two halves and remove the hyphen. Keep the joined word on the top line, and put a line break after it to preserve the line formatting--this makes it easier for the 2nd Round Proofreader. If it's the last word in a sentence and is followed by punctuation, then carry that punctuation onto the top line, too.") . "</p>";

echo "<p>" . _("<i>Em-dashes & long dashes</i>. These serve as separators between words--sometimes for emphasis like this--or when a speaker gets a word caught in his throat--! Proofread these as two hyphens. Don't leave a space before or after, even if it looks like there was a space in the original book image.") . "<br>" . _("Note: If a dash appears at the start or end of a line of your OCR'd text, join it with the other line so that there are no spaces or line breaks around it. Only if the author used a dash to start or end the paragraph or line of poetry or dialog should you leave it at the start or end of a line.") . "</p>";

echo "<h3><a name='eop_hyphen'>" . _("End-of-page Hyphenation") . "</a> </h3>";

echo "<p>" . _("Leave the hyphen at the end of the last line, but mark it with a <tt>*</tt> after the hyphen so the post-processor will notice it. On pages that start with part of a word from the previous page, place a <tt>*</tt> before the word.") . "</p>";

echo "<a href='../generic/main.php?type=step2'>" . _("continue") . "</a>";

theme('','footer'); 
?>
