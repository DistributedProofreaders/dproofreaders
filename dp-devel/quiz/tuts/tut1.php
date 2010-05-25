<?php
$relPath='../../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

theme( _('Proofreading Tutorial and Interactive Quiz -- Step 1'),'header');

echo "<h1>" . _("Tutorial") . "</h1>";

echo "<h2>" . _("Intro") . "</h2>";

echo "<p>" . _("In this tutorial you will be presented extracts from the Proofreading Guidelines. After each part you will be led to a quiz page, where you can try out the newly learned rules.") . "</p>";

echo "<p>" . _("Don't be alarmed by the number of changes you'll have to apply in the quizzes. On normal easy texts the rate of things to correct is usually much lower than that.") . "</p>";

echo "<h2>" . _("Part 1") . "</h2>";

echo "<h3>" . _("Project Comments") . "</h3>";

echo "<p>" . _("On the interface page where you start proofreading pages, there is a section called 'Project Comments' containing info specific to that project (book). <em>Read these before you start proofreading pages!</em> If the Project Manager wants you to format something in this book differently from the way specified in the Guidelines, that will be noted here.  Instructions in the Project Comments <em>override</em> the rules in these Guidelines, so follow them.  (This is also where the Project Manager gives you interesting tidbits of information about the books, like where the source came from, etc.)") . "</p>";

echo "<h3>" . _("The Primary Rule") . "</h3>";

echo "<p>" . _("In doing your proofreading, the primary rule to follow is that the final electronic book seen by a reader, possibly many years in the future, should <b>accurately convey the intent of the author</b>.") . "</p>";

echo "<p>" . _("So the general rule is <em>Don't change what the author wrote!</em>  If the author spelled words oddly, leave them spelled that way. If the author wrote outrageous racist or biased statements, leave them that way. If the author seems to put italics or a footnote every third word, leave them italicized or footnoted.") . "</p>";

echo "<p>" . _("On the other hand, we do change minor printing items that don't affect the sense of what the author wrote.");

echo "<br><br>" . _("For example, typesetters in the 1700's &amp; 1800's often inserted a &frac34;ths space before punctuation such as a semicolon or comma. Our OCR scanners generally read this as a space character. But when proofreading, we remove that space; since it distracts modern readers and removing it doesn't affect the meaning of the author's words. Typesetters of that time also used the 'long s' (&fnof; or &#383;) in words, which looks similar to a modern 'f'.  In English, the 'long s' and the regular 's' are the same, so we make them all the regular 's'.") . "</p>";


echo "<h3><a name='italics'>" . _("Italics") . "</a></h3>";

echo "<p>" . _("Text that is italicized should have <tt>&lt;i&gt;</tt> inserted at the start and <tt>&lt;/i&gt;</tt> inserted at the end of the italics. (Note the '/' in the closing symbol.)") . "</p>";

echo "<p>" . _("Punctuation goes OUTSIDE the italics, unless it is an entire sentence or section that is being italicized, or the punctuation is itself part of a phrase, title or abbreviation that is being italicised. (For instance, the . that mark that words in the title of a journal like <i>Phil. Trans.</i> have been abbreviated is part of the title for italicisation purposes, and are included within the italic tags, thus: <tt>&lt;i&gt;Phil. Trans.&lt;/i&gt;</tt>)") . "</p>";

echo "<h3><a name='bold'>" . _("Bold Text") . "</a> </h3>";

echo "<p>" . _("Bold text (text printed in a heavier typeface) should be marked with <tt>&lt;b&gt;</tt> inserted before the bold text and <tt>&lt;/b&gt;</tt> after it.") . "</p>";

echo "<a href='../generic/main.php?type=step1'>" . _("continue") . "</a>";

theme("", "footer");
?>
