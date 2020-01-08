<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

// Note: The text used for font sample images is stored in font_sample.txt

$title = _("Proofreading Font Comparison");
output_header($title, NO_STATSBAR);

// This page allows the user to select a font for comparison to DPCustomMono2.
$fonts_with_images = [
    "Arial",
    "Courier",
    "Lucida",
    "Lucida Console",
    "Monaco",
    "Times",
];
foreach($fonts_with_images as $font)
{
    if (!is_file(get_sample_image_for_font($font)))
        // We don't have a sample image file for this font,
        // so there's no point making it selectable.
        continue;

    $selectable_fonts[] = $font;
}
sort($selectable_fonts);

// determine user's current proofreading font, if any and use that as the compare_font
list($proofreading_font, , $proofreading_font_family) = get_user_proofreading_font();
if(!$proofreading_font)
{
    $proofreading_font = 'monospace';
}

// set the default compare_font to the user's proofreading font
// if it is selectable
if(in_array($proofreading_font, $selectable_fonts))
    $compare_font = $proofreading_font;
else
    $compare_font = $selectable_fonts[0];

// get any 'compare' variable from URL, if any
$compare_font = get_enumerated_param( $_GET, 'compare', $compare_font, $selectable_fonts );

// print page header
echo "<h1>$title</h1>\n";

echo "<p>" . _("This page shows samples of the available proofreading fonts and demonstrates the differences between DPCustomMono2 and other fonts.") . "</p>\n";

echo "<h2>" . _("Available Proofreading Fonts") . "</h2>";

echo "<p>" . sprintf(_("The following fonts can be selected in your <a href='%s'>preferences</a> for use in the proofreading interface. Browser default is whatever font your browser renders monospace text in unless told otherwise, often Courier or Courier New. The other fonts are available as web fonts and can be selected and used without having them installed on your computer."), "$code_url/userprefs.php?tab=1") . "</p>";

$show_user_custom_font = TRUE;
foreach(get_available_proofreading_font_faces() as $index => $name)
{
    if($index == 1) // other
        continue;

    if($index == 0)
    {
        $name = BROWSER_DEFAULT_STR;
        $font = 'monospace';
    }
    else
    {
        $font = $name;
    }

    if($font == $proofreading_font)
        $show_user_custom_font = FALSE;

    show_font_specimen($name, $font, $proofreading_font);
}

if($show_user_custom_font)
{
    echo "<h2 style='clear: both;'>" . _("Custom Proofreading Font") . "</h2>";
    echo "<p>" . _("Your current proofreading font is one you've specified by name. This is what a specimen looks like in that font.") . "</p>";
    show_font_specimen($proofreading_font, $proofreading_font);
}

echo "<h2 id='DPCustomMono2' style='clear: both;'>DPCustomMono2</h2>";

echo "<p style='clear: both;'>" . _("DPCustomMono2 is a font adapted by DP volunteers, based on the suggestions and ideas of many experienced proofreaders. Using DPCustomMono2 as your proofreading font can help you find mistakes."). "</p>\n";

echo "<p style='clear: both'>" . _("This site provides DPCustomMono2 as a web font and browsers that support web fonts do not require the font to be installed locally.") . " ";
echo sprintf(_("For more information on installing and using the font locally for offline activitlies like Post-Processing and Smooth Reading, see the <a href='%s'>DPCustomMono2</a> wiki page at pgdp.net."), "http://www.pgdp.net/wiki/Installing_DPCustomMono") . "</p>\n";

echo "<h3>" . _("Character Differences") . "</h3>";

echo "<p>" . _("Below are some examples of similar looking characters that are easier to distinguish in DPCustomMono2 than in other fonts, in this case your default browser monospace font.") . "</p>";

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
if($proofreading_font !== 'monospace' && $proofreading_font != 'DPCustomMono2')
    echo "<span style=\"font-family: $proofreading_font_family;\">" . html_safe($proofreading_font) . "</span><br>";
echo "<span style='font-family: DPCustomMono2;'>DPCustomMono2</span>";
echo "</div>";

foreach($character_sets as $set)
{
    echo "<div style='float: left; padding-right: 0.5em; margin-bottom: 1em;'>";
    echo "<span style='font-family: monospace;'>$set</span><br>";
    if($proofreading_font !== 'monospace' && $proofreading_font != 'DPCustomMono2')
        echo "<span style=\"font-family: $proofreading_font_family;\">$set</span><br>";
    echo "<span style='font-family: DPCustomMono2;'>$set</span>";
    echo "</div>";
}

echo "<h3 style='clear: both'>" . _("Comparisons with Other Fonts") . "</h3>";

echo "<p style='clear: both'>" . _("Select a font from the list below to see a sample text in the desired font compared to the sample text in DPCustomMono2.") . "</p>\n";

// build the list of font-selection links
$sample_font_links=array();
foreach ($selectable_fonts as $font)
{
    if($compare_font == $font)
        $sample_font_links[] = "<span style='font-family: \"$font\"'>$font</span>";
    else
        $sample_font_links[] = "<a style='font-family: \"$font\"' href='?compare=$font#fontlinks'>$font</a>";
}


echo "<p id='fontlinks'>" . implode("\n| ", $sample_font_links) . "</p>\n";

// print out the comparison images
echo "
    <table class='fontcompare'>

    <tr>
    <th>" . sprintf(_("Selected Font (%s)"), $compare_font) . "</th>
    <th>DPCustomMono2</th>
    </tr>

    <tr>
    <td><img src='" . get_sample_image_for_font($compare_font) . "'></td>
    <td><img src='" . get_sample_image_for_font('DPCustomMono2') . "'></td>
    </tr>

    </table>
";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_sample_image_for_font($font)
// Given a font name, return the path (relative to the current directory)
// for the sample image for that font (whether the file exists or not).
{
    // We don't want to have files with spaces in their names,
    // so if the font name contains any spaces, replace each
    // with an underscore in the file name.
    $base = str_replace(' ', '_', $font);
    return "images/{$base}.png";
}

function show_font_specimen($name, $font, $proofreading_font = NULL)
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

    if($font == $proofreading_font)
        echo "<p><i>" . _("This is your current proofreading font.") . "</i></p>";
    echo "</div>";
}

// vim: sw=4 ts=4 expandtab
