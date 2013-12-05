<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_('Moderate Proofreading Tutorial -- Page 8'));

echo "<h2>" . _("Moderate Proofreading Tutorial, Page 8") . "</h2>\n";
echo "<h3>" . _("Accented/Non-ASCII Characters") . "</h3>\n";
if(!$utf8_site)
{
    echo "<p>" . _("The &oelig; character (oe ligature) is not in Latin-1, so we mark it with brackets like in <tt>man[oe]uvre</tt>, or <tt>[OE]dipus</tt> for the capital &OElig;. Note that the &aelig; character (ae ligature, as in <tt>encyclop&aelig;dia</tt>) is in Latin-1, so that character should be inserted directly.") . "</p>\n";
} else {
    echo "<p>" . _("The &oelig; character (oe ligature) should be inserted into the proofread text, just like other special characters.") . "</p>\n";
}

echo "<p><a href='../generic/main.php?type=p_mod2_3'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
