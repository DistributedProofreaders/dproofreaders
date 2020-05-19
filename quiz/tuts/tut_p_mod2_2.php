<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

output_header(_('Moderate Proofreading Tutorial'));

echo "<h2>" . sprintf(_("Moderate Proofreading Tutorial, Page %d"), 7) . "</h2>\n";
echo "<h3>" . _("Subscripts") . "</h3>\n";
echo "<p>" . _("Subscripted text is often found in scientific works, but is not common in other material. Proofread subscripted text by inserting an underline character <kbd>_</kbd> and surrounding the text with curly braces <kbd>{</kbd> and <kbd>}</kbd>. For example:") . "</p>\n";

echo "<table width='100%' align='center' border='1' cellpadding='4' cellspacing='0' summary='" . _("Subscripts example") . "'>\n";
echo "  <tbody>\n";
echo "    <tr><th align='left' bgcolor='cornsilk'>" . _("Original Image:") . "</th></tr>\n";
echo "    <tr>\n";
echo "      <td valign='top'>H<sub>2</sub>O.</td>\n";
echo "    </tr>\n";
echo "    <tr><th align='left' bgcolor='cornsilk'>" . _("Correctly Proofread Text:") . "</th></tr>\n";
echo "    <tr>\n";
echo "      <td valign='top'><kbd>H_{2}O.</kbd></td>\n";
echo "    </tr>\n";
echo "  </tbody>\n";
echo "</table>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_mod2_2'>" . _("Continue to quiz") . "</a></p>\n";

// vim: sw=4 ts=4 expandtab
