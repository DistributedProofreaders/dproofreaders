<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Moderate Proofreading Tutorial'));

echo "<h1>" . _("Proofreading Tutorial") . "</h1>\n";
echo "<h2>" . _("Intro") . "</h2>\n";
echo "<p>" . _("In this tutorial you will be presented extracts from the Proofreading Guidelines. After each part you will be led to a quiz page, where you can try out the newly learned rules. These Moderate Proofreading Quizzes build on the topics already covered in the tutorial for the Basic Proofreading Quiz.") . "</p>\n";

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial, Page %d"), 1) . "</h2>\n";
echo "<h3>" . _("Superscripts") . "</h3>\n";
echo "<p>" . _("Older books often abbreviated words as contractions, and printed them as superscripts. Proofread these by inserting a single caret (<kbd>^</kbd>) followed by the superscripted text. If the superscript continues for more than one character, then surround the text with curly braces <kbd>{</kbd> and <kbd>}</kbd> as well. For example:") . "</p>\n";
echo "<table class='basic' summary='" . _("Superscripts example") . "'>\n";
echo "  <tbody class='left-align'>\n";
echo "    <tr><th>" . _("Original Image:") . "</th></tr>\n";
echo "    <tr>\n";
echo "      <td>Gen<sup>rl</sup> Washington defeated L<sup>d</sup> Cornwallis's army.</td>\n";
echo "    </tr>\n";
echo "    <tr><th>" . _("Correctly Proofread Text:") . "</th></tr>\n";
echo "    <tr>\n";
echo "      <td><kbd>Gen^{rl} Washington defeated L^d Cornwallis's army.</kbd></td>\n";
echo "    </tr>\n";
echo "  </tbody>\n";
echo "</table>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod1_1'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
