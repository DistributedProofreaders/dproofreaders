<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(sprintf(_("Moderate Proofreading Tutorial: Part %d"), 2));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial: Part %1\$d, Page %2\$d"), 2, 3) . "</h2>\n";
echo "<h3>" . _("Accented/Non-ASCII Characters") . "</h3>\n";
echo "<p>" . _("The œ character (oe ligature) should be inserted into the proofread text, just like other special characters.") . "</p>\n";
echo "<p>" . sprintf(
    _("Inserting non-ASCII characters is described in the guidelines: <a href='%s' target='_blank'>Characters with Diacritical Marks</a> and <a href='%s' target='_blank'>Inserting Special Characters</a>."),
    get_faq_url("proofreading_guidelines.php") . "#d_chars",
    get_faq_url("proofreading_guidelines.php") . "#insert_char"
) . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod2_3'>" . _("Continue to quiz page") . "</a></p>\n";
