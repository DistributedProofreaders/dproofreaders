<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

// Note: The text used for font sample images is stored in font_sample.txt

$title = _("Proofreading Font Comparison");
output_header($title, NO_STATSBAR);

// This page allows the user to select a font
// (for comparison to DPCustomMono2)
// from the following list:
$selectable_fonts = array();
foreach(get_available_proofreading_font_faces() as $index => $font)
{
    if (!is_file(get_sample_image_for_font($font)))
        // We don't have a sample image file for this font,
        // so there's no point making it selectable.
        continue;

    if ($font == "DPCustomMono2")
        // We do have a sample image file for this font,
        // but it always appears on the right of the page,
        // so there's no point in making it a selectable font.
        continue;

    $selectable_fonts[] = $font;
}
sort($selectable_fonts);

// determine user's current proofreading font, if any and use that as the compare_font
list($proofreading_font, ) = get_user_proofreading_font();

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

echo "<p>" . _("This page demonstrates the differences between <span style='font-family: DPCustomMono2'>DPCustomMono2</span> and other fonts.") . "</p>\n";

echo "<p>" . sprintf(_("DPCustomMono2 is a font adapted by DP volunteers, based on the suggestions and ideas of many experienced proofreaders. Using DPCustomMono2 as your proofreading font can help you find mistakes. You can change the font that you use for proofreading in your <a href='%s'>preferences</a>."), "$code_url/userprefs.php") . "</p>\n";

if(!empty($proofreading_font))
    echo "<p>" . sprintf(_("Your current proofreading font is <b>%s</b>."), $proofreading_font) . "</p>\n";

echo "<p>" . _("This site provides DPCustomMono2 as a web font and browsers that support it do not require the font to be installed locally.") . "</p>";

echo "<p style='font-family: DPCustomMono2'>" . _("If your browser supports web fonts or you already have DPCustomMono2 installed, you will see this paragraph in that typeface. If this paragraph's font doesn't look radically different to that of the paragraph above, you can download DPCustomMono2 from <a href='DPCustomMono2.ttf'>here</a> (right click the link, and choose Save Target As..) After you have installed the font please refresh this page to make sure DPCustomMono2 is installed correctly.") . "</p>\n";

echo "<p>" . sprintf(_("For more information on installing and using the font, see the <a href='%s'>Installing DPCustomMono</a> wiki page at pgdp.net."), "http://www.pgdp.net/wiki/Installing_DPCustomMono") . "</p>\n";

echo "<hr width='70%'>\n";

// build the list of font-selection links
$sample_font_links=array();
foreach ($selectable_fonts as $font)
{
        if($compare_font == $font)
            $sample_font_links[] = "<span style='font-family: \"$font\"'>$font</span>";
        else
            $sample_font_links[] = "<a style='font-family: \"$font\"' href='?compare=$font'>$font</a>";
}


echo "<p>" . _("Select a font from the list below to see a sample text in the desired font compared to the sample text in DPCustomMono2.") . "</p>\n";

echo "<p>" . implode("\n| ", $sample_font_links) . "</p>\n";

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

// vim: sw=4 ts=4 expandtab
