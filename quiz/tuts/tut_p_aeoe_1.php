<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Ligatures Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Ligatures Proofreading Tutorial, Page %d"), 1) . "</h2>\n";

echo "<p>" . _("The two ligatures æ and œ can be difficult to distinguish when they are printed italics.  However, the œ (oe) ligature is usually rounder at the top, while the 'a' of æ is more teardrop-shaped.  For example:") . "</p>\n";
echo "<table class='basic'>\n<tr><th>œ</th><th>æ</th></tr>\n";
echo "<tr><td><img src='../generic/images/oelig_ital.png' alt='oe ligature' width='33' height='30'></td>\n";
echo "<td><img src='../generic/images/aelig_ital_teardrop.png' alt='ae ligature' width='34' height='32'></td>\n</tr></table>\n";

echo "<p>" . sprintf(_("You can insert each of these characters using the character picker in the proofreading interface or using keyboard shortcuts. Tables for <a href='%1\$s' target='_blank'>Windows</a> and <a href='%2\$s' target='_blank'>Macintosh</a> which list these shortcuts are in the Proofreading Guidelines."), "../../faq/proofreading_guidelines.php#a_chars_win", "../../faq/proofreading_guidelines.php#a_chars_mac") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_aeoe_1'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
