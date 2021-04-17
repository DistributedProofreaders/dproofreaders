<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Moderate Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial, page %d"), 4) . "</h2>\n";
echo "<h3>" . _("Common OCR Problems") . "</h3>\n";
echo "<p>" . _("OCR commonly has trouble distinguishing between the similar characters.  Some examples are:") . "</p>\n";
echo "<ul>";
echo "<li>" . _("The digit '1' (one), the lowercase letter 'l' (ell), and the uppercase letter 'I'. Note that in some fonts the number one may look like <small>I</small> (like a small capital letter 'i').") . "</li>\n";
echo "<li>" . _("The digit '0' (zero), and the uppercase letter 'O'.") . "</li>\n";
echo "<li>" . _("Dashes &amp; hyphens: Proofread these carefully&mdash;OCR'd text often has only one hyphen for an em-dash that should have two.") . "</li>\n";
echo "<li>" . _("Parentheses ( ) and curly braces { }.") . "</li>\n";
echo "</ul>";
echo "<p>" . _("Watch out for these. Normally the context of the sentence is sufficient to determine which is the correct character, but be careful&mdash;often your mind will automatically 'correct' these as you are reading.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod1_4'>" . _("Continue to quiz page") . "</a></p>\n";
