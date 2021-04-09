<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Thorn Proofreading Tutorial'));

echo "<h2>" . _("Thorn Proofreading Tutorial") . "</h2>\n";

echo "<p>" . sprintf(_("A <strong>thorn</strong> is a letter that was used in Old and Middle English, and is still used in Icelandic.  It usually represents the \"th\" sound.  In %s projects, it usually looks like:"), $site_abbreviation) . "</p>\n";
echo "<table class='basic'>\n";
echo "  <tr>\n";
echo "    <th>Capital</th>\n";
echo "    <td><img src='../generic/images/thorn_cap.png' alt='capital thorn' width='21' height='32'>\n";
echo "      <img src='../generic/images/thorn_cap2.png' alt='capital thorn' width='26' height='41'>\n";
echo "    </td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <th>Lower Case</th>\n";
echo "    <td><img src='../generic/images/thorn_lower.png' alt='lower case thorn' width='20' height='35'>\n";
echo "      <img src='../generic/images/thorn_lower2.png' alt='lower case thorn' width='20' height='45'>\n";
echo "    </td>\n";
echo "  </tr>\n";
echo "</table>\n";

echo "<p>" . _("The loop of the capital thorn is normally larger, and the stem may not drop down as far below the line as the lower case.");
echo " " . _("You can insert them using the character picker at the bottom of the Proofreading Interface.") . "</p>\n";

echo "<p><a href='../generic/main.php?quiz_page_id=p_thorn'>" . _("Continue to quiz page") . "</a></p>\n";

