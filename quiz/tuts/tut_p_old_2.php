<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_('Old Texts Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Old Texts Proofreading Tutorial, Page %d"), 2) . "</h2>\n";
echo "<h3>" . _("Usage of u/v and i/j") . "</h3>\n";
echo "<p>" . _("In the 1600s and before, the letters <i>u</i>, <i>v</i>, <i>i</i>, and <i>j</i> were used differently than they are today.  Often <i>v</i> was used only at the beginning of a word, while <i>u</i> was used in the middle and end, as in these words: <i>vpon</i>, <i>vntil</i>, <i>haue</i>, <i>giue</i>.  In addition, usually <i>i</i> was used where we have a <i>j</i> today: <i>iudge</i>, <i>obiect</i>.  The letter j may occur in Roman numerals, though, such as <i>iij</i>.  In capital letters, there usually was no <i>U</i> or <i>J</i>; only <i>V</i> and <i>I</i> were used.") . "</p>\n";
echo "<p>" . _("Unless the Project Manager gives special instructions to the contrary, proofread these just the way they appear in the image.  Do <i>not</i> modernize the spelling.") . "</p>\n";

echo "<h3>" . _("Ampersands") . "</h3>\n";
echo "<p>" . _("These are some examples of ampersands (&amp;) in different fonts:") . "</p>\n";
echo "<p><img src='../generic/images/amp.png' width='39' height='41' alt='&amp;'>";
echo " <img src='../generic/images/amp_ital.png' width='49' height='44' alt='&amp;'>";
echo " <img src='../generic/images/amp_blackletter.png' width='33' height='45' alt='&amp;'>";
echo " <img src='../generic/images/amp_blackletter2.png' width='31' height='41' alt='&amp;'></p>\n";
echo "<p>" . _("As in many books through the 1800s, the phrase <i>et cetera</i> may be abbreviated as <kbd>&amp;c.</kbd> rather than the modern <kbd>etc</kbd>.  Leave it as the author wrote it.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_old_2'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
