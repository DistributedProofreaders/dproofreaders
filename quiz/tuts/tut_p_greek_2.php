<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Greek Transliteration Tutorial'));

echo "<h2>" . sprintf(_("Greek Transliteration Tutorial, page %d"), 2) . "</h2>\n";
echo "<h3>" . _("Letters with Multiple Transliterations") . "</h3>\n";
echo "<p>" . _("The letter υ (upsilon) can be transliterated as either u or y. Generally, if upsilon follows another vowel, use \"u\". Otherwise, use \"y\". This isn't required, though; you can use u everywhere if you want.") . "</p>\n";
echo "<p>" . _("<b>Note:</b> For the purposes of this quiz, please use the vowel/consonant distinction described above when transliterating.  This is only due to the limitations of the quiz, and is not required when proofreading normally.") . "</p>\n";

echo "<h3>" . _("Diacritical Marks") . "</h3>\n";
echo "<p>" . _("Vowels in Greek words can have the following diacritical marks (accents):") . "<br>\n";
echo "<img src='../generic/images/Greek_accents.png' width='200' height='33' alt='" . _("accented Greek vowels") . "'></p>\n";
echo "<p>" . sprintf(_("When transliterating Greek, you should ignore all of these except for the first one (the \"rough breathing\" mark).  If a vowel has a rough breathing mark over it, we indicate this by adding an \"<kbd>h</kbd>\" to the start of the transliterated word.  For example, these two words: %s would be transliterated as <kbd>[Greek: hodous]</kbd> and <kbd>[Greek: odous]</kbd>, respectively."), 
                     "<br>\n<img src='../generic/images/rough_smooth_breathing.png' width='214' height='45' alt='" . _("two Greek words differing only in breathing mark") . "'><br>\n") . "</p>\n";
echo "<p>" . _("The breathing mark may be combined with another accent, which sometimes makes it difficult to tell which way the breathing mark goes, especially in badly printed text.  They can become easier to see if you enlarge the image. If a vowel has both a breathing mark and an acute or grave accent in the image, the breathing mark normally appears on the left.  Other clues which can help in identifying a rough breathing mark are: ") . "</p>\n";
echo "<ul>\n";
echo "<li>" . _("Rough breathing marks only come at the beginning of a word.  (There are rare exceptions, though.)") . "</li>\n";
echo "<li>" . _("If the word begins with a vowel, it should have either a rough breathing mark (the first accent in the image above; curls like a C) or a smooth breathing mark (the second example in the image above; curls like a comma).") . "</li>\n";
echo "<li>" . _("If it's rough breathing, add an \"<kbd>h</kbd>\" at the beginning of the word in the transliteration.  If it's smooth breathing, ignore it.") . "</li>\n";
echo "</ul>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_greek_2'>" . _("Continue to quiz page") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
