<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Greek Transliteration Tutorial'));

echo "<h2>" . sprintf(_("Greek Transliteration Tutorial, Page %d"), 3) . "</h2>\n";

echo "<h3>" . _("Letters with Multiple Transliterations") . "</h3>\n";
echo "<p>" . _("The letter γ (gamma) is usually transliterated as g, but n is used instead when it occurs before certain letters:") . "</p>\n";
echo "<table border='1' cellspacing='0' cellpadding='5' style='text-align:center'>\n";
echo "  <tr><th>" . _("Greek") . "</th><th>" ._("Transliteration") . "</th></tr>\n";
echo "  <tr><td>γγ</td> <td>ng</td> </tr>\n";
echo "  <tr><td>γκ</td> <td>nk</td> </tr>\n";
echo "  <tr><td>γξ</td> <td>nx</td> </tr>\n";
echo "  <tr><td>γχ</td> <td>nch</td></tr>\n";
echo "</table>\n";
echo "<p>" . _("These combinations are listed in the Greek Transliterator tool, to the right of the other letters.") . "</p>\n";

echo "<h3>" . _("Diacritical Marks") . "</h3>\n";
echo "<p>" . _("Special cases:") . "</p>\n";
echo "<ul>\n";
echo "<li>" . _("If a word begins with a capitalized vowel, the accents and breathing marks are often printed to the left of the letter, rather than above it.  If it has rough breathing, make the vowel lower case and capitalize the H: <kbd>hÊraklês</kbd> becomes <kbd>Hêraklês</kbd>.") . "</li>\n";
echo "<li>" . _("If the word begins with a diphthong (two vowels together), the breathing mark will appear over the second vowel, but the \"<kbd>h</kbd>\" for rough breathing still goes at the very beginning of the word in the transliteration.") . "</li>\n";
echo "<li>" . _("Besides vowels, the rough breathing mark can also appear over one consonant: ρ (rho).  If a word begins with rho, it always has rough breathing, with the rho transliterated as \"<kbd>rh</kbd>\" (note that the \"<kbd>h</kbd>\" goes <i>after</i> the rho, rather than before as with vowels).") . "</li>\n";
echo "</ul>";

echo "<p><a href='../generic/main.php?quiz_page_id=p_greek_3'>" . _("Continue to quiz") . "</a></p>";

// vim: sw=4 ts=4 expandtab
