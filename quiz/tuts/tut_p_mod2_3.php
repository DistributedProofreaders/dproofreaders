<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(sprintf(_("Moderate Proofreading Tutorial: Part %d"), 2));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial: Part %1\$d, Page %2\$d"), 2, 3) . "</h2>\n";
echo "<h3>" . _("Accented/Non-ASCII Characters") . "</h3>\n";
echo "<p>" . _("The œ character (oe ligature) should be inserted into the proofread text, just like other special characters.") . "</p>\n";
echo "<h3>" . _("Inserting Special Characters") . "</h3>\n";
echo "<p>" . _("If they are not on your keyboard, there are several ways to input special characters:") . "</p>\n";
echo "<ul>\n";
echo "<li>" . _("The character picker at the bottom of the proofreading interface.") . "</li>\n";
echo "<li>" . _("Keyboard shortcuts.") . "<br>\n";
echo sprintf(
    _("Tables for <a href='%1\$s' target='_blank'>Windows</a> and <a href='%2\$s' target='_blank'>Macintosh</a> which list these shortcuts are in the Proofreading Guidelines."),
    get_faq_url("proofreading_guidelines.php") . "#a_chars_win",
    get_faq_url("proofreading_guidelines.php") . "#a_chars_mac"
);
echo "</li>\n";
echo "<li>";
echo sprintf(
    _("Other methods described in the guidelines: <a href='%s' target='_blank'>Characters with Diacritical Marks</a> and <a href='%s' target='_blank'>Inserting Special Characters</a>."),
    get_faq_url("proofreading_guidelines.php") . "#d_chars",
    get_faq_url("proofreading_guidelines.php") . "#insert_char"
);
echo "</li>\n";
echo "</ul>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod2_3'>" . _("Continue to quiz page") . "</a></p>\n";
