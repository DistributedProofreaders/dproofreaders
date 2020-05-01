<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_('Greek Transliteration Tutorial'));

echo "<h2>" . sprintf(_("Greek Transliteration Tutorial, Page %d"), 5) . "</h2>\n";

echo "<h3>" . _("Two Double Letters") . "</h3>\n";
echo "<p>" . _("This form of sigma: ς normally only occurs at the end of a word. If you meet one in the middle of a word, it's almost always a letter called \"stigma\" and the top bit of it usually extends further to the right. It gets transliterated as <kbd>st</kbd>.") . "</p>\n";
echo "<p><img src='../generic/images/ou_lig.png' width='20' height='23' alt='" . _("Greek ou ligature") . "'> " . _("This letter is a shorthand way of writing <kbd>ou</kbd>. Think of it as υ (upsilon) balanced on top of ο (omicron).  ") . "</p>\n";

echo "<h3>" . _("Variant Forms") . "</h3>\n";
echo "<p>" . _("Some other forms of Greek letters are:") . "</p>\n";
echo "<table border='1' cellspacing='0' cellpadding='5' style='text-align:center'>\n";
echo "  <tr><th>" . _("Image") . "</th><th>Letter </th><th>" . _("Transliteration") . "</th></tr>\n";
echo "  <tr>\n";
echo "    <td><img src='../generic/images/beta_var.png' width='20' height='32' alt='" . _("beta") . "'></td>\n";
echo "    <td>" . _("beta") . "</td>\n";
echo "    <td><kbd>b</kbd></td></tr>\n";
echo "  <tr>\n";
echo "    <td><img src='../generic/images/kappa_var.png' width='23' height='23' alt='" . _("kappa") . "'></td>\n";
echo "    <td>" . _("kappa") . "</td>\n";
echo "    <td><kbd>k</kbd></td></tr>\n";
echo "  <tr>\n";
echo "    <td><img src='../generic/images/pi_var.png' width='47' height='25' alt='" . _("pi") . "'></td>\n";
echo "    <td>" . _("pi") . "</td>\n";
echo "    <td><kbd>p</kbd></td></tr>\n";
echo "  <tr>\n";
echo "    <td><img src='../generic/images/rho_var.png' width='16' height='30' alt='" . _("rho") . "'></td>\n";
echo "    <td>" . _("rho") . "</td>\n";
echo "    <td><kbd>r</kbd></td></tr>\n";
echo "</table>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_greek_5'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
