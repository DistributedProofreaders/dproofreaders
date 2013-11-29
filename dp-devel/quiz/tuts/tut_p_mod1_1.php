<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_('Moderate Proofreading Tutorial -- Page 1'));

echo "<h1>" . _("Proofreading Tutorial") . "</h1>\n";
echo "<h2>" . _("Intro") . "</h2>\n";
echo "<p>" . _("In this tutorial you will be presented extracts from the Proofreading Guidelines. After each part you will be led to a quiz page, where you can try out the newly learned rules. These Moderate Proofreading Quizzes build on the topics already covered in the tutorial for the Basic Proofreading Quiz.") . "</p>\n";

echo "<h2>" . _("Moderate Proofreading Tutorial, Page 1") . "</h2>\n";
echo "<h3>" . _("Superscripts") . "</h3>\n";
echo "<p>" . _("Older books often abbreviated words as contractions, and printed them as superscripts. Proofread these by inserting a single caret (<tt>^</tt>) followed by the superscripted text. If the superscript continues for more than one character, then surround the text with curly braces <tt>{</tt> and <tt>}</tt> as well. For example:") . "</p>\n";
echo "<table width='100%' align='center' border='1' cellpadding='4' cellspacing='0' summary='" . _("Superscripts example") . "'>\n";
echo "  <tbody>\n";
echo "    <tr><th align='left' bgcolor='cornsilk'>" . _("Original Image:") . "</th></tr>\n";
echo "    <tr>\n";
echo "      <td valign='top'>Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwall's army.</td>\n";
echo "    </tr>\n";
echo "    <tr><th align='left' bgcolor='cornsilk'>" . _("Correctly Proofread Text:") . "</th></tr>\n";
echo "    <tr>\n";
echo "      <td valign='top'><tt>Gen^{rl} Washington defeated L^d Cornwall's army.</tt></td>\n";
echo "    </tr>\n";
echo "  </tbody>\n";
echo "</table>\n";

echo "<p><a href='../generic/main.php?type=p_mod1_1&quiz_id=MPQ1'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
