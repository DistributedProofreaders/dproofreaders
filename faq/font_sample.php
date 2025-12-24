<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

// Note: The text used for font sample images is stored in font_sample.txt

$title = _("Proofreading Font Comparison");
output_header($title, NO_STATSBAR);

// print page header
echo "<h1>$title</h1>\n";

echo "<p>" . _("This page shows samples of the available proofreading fonts and demonstrates the differences between DP Sans Mono and other fonts.") . "</p>\n";

echo "<h2>" . _("Available Proofreading Fonts") . "</h2>";

echo "<p>" . sprintf(_("The following fonts can be selected in your <a href='%s'>preferences</a> for use in the proofreading interface. Browser default is whatever font your browser renders monospace text in unless told otherwise, often Courier or Courier New. The other fonts are available as web fonts and can be selected and used without having them installed on your computer."), "$code_url/userprefs.php?tab=1") . "</p>";

foreach (get_available_proofreading_font_faces() as $index => $name) {
    if ($index == 1) { // other
        continue;
    }

    if ($index == 0) {
        $name = BROWSER_DEFAULT_STR;
        $font = 'monospace';
    } else {
        $font = $name;
    }

    show_font_specimen($name, $font);
}

echo "<h2 id='DPSansMono' style='clear: both;'>DP Sans Mono</h2>";

echo "<p style='clear: both;'>" . _("DP Sans Mono is a font adapted by DP volunteers, based on the suggestions and ideas of many experienced proofreaders. Using DP Sans Mono as your proofreading font can help you find mistakes."). "</p>\n";

echo "<p style='clear: both'>" . _("This site provides DP Sans Mono as a web font and browsers that support web fonts do not require the font to be installed locally.") . " ";
echo sprintf(_("For more information on installing and using the font locally for offline activities like Post-Processing and Smooth Reading, see the <a href='%s'>DP Sans Mono</a> wiki page at pgdp.net."), "https://www.pgdp.net/wiki/DP_Sans_Mono") . "</p>\n";

echo "<h3>" . _("Character Differences") . "</h3>";

echo "<p>" . _("Below are some examples of similar looking characters that are easier to distinguish in DP Sans Mono than in other fonts, in this case your default browser monospace font.") . "</p>";

$character_sets = [
    'O0o',
    '1Ili!',
    ':;',
    ',.',
    '3BE',
    'KR',
    'Vv',
    'Ww',
    'Xx',
    'vy',
    '`\'',
    'F&pound;L',
];

echo "<div style='float: left; padding-right: 1em; margin-bottom: 1em;'>";
echo "<span style='font-family: monospace;'>" . BROWSER_DEFAULT_STR . "</span><br>";
echo "<span style='font-family: DP Sans Mono;'>DP Sans Mono</span>";
echo "</div>";

foreach ($character_sets as $set) {
    echo "<div style='float: left; padding-right: 0.5em; margin-bottom: 1em;'>";
    echo "<span style='font-family: monospace;'>$set</span><br>";
    echo "<span style='font-family: DP Sans Mono;'>$set</span>";
    echo "</div>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_font_specimen($name, $font)
{
    echo "<div style='float: left; margin-right: 1em; margin-top: 0;'>";
    //echo "<h3>$name</h3>";
    echo "<p style='font-family: $font; margin-top: 0; '>";
    echo "<span style='font-size: 1.5em; font-weight: bold;'>$name</span><br>";
    echo "ABCDEFGHIJKLMNOPQRSTUVWXYZ<br>";
    echo "abcdefghijklmnopqrstuvwxyz<br>";
    echo "0123456789<br>";
    echo "!@#$%^&*()[]{}&lt;&gt;'\";:.,\/?<br>";
    echo "</p>";
    echo "</div>";
}
