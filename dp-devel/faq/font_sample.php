<?php
$relPath='../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
new dbConnect();

/* TODO: 
    * not all proofreading fonts have images
    * code doesn't handle compare_font having spaces in it
      (which isn't a problem currently but would be if
      images were added for fonts with spaces)
*/

$title = _("Proofreading Font Comparison");
$no_stats=1;
theme($title,'header');

// load all fonts into an array except BROWSER_DEFAULT_STR,
// Monospaced, and DPCustomMono2 which are not selectable
// because they are either not a specific font or because
// it's DPCM2
$selectable_fonts = array();
foreach($f_f as $font)
{
    if($font == BROWSER_DEFAULT_STR ||
        $font == "Monospaced" ||
        $font == "DPCustomMono2")
        continue;

    $selectable_fonts[] = $font;
}
sort($selectable_fonts);

// determine user's current proofreading font, if any and use that as the compare_font
$proofreading_font="";
if ($userP['i_layout']==1)
{
    $proofreading_fonti = $userP['v_fntf'];
    $proofreading_font = $f_f[$proofreading_fonti];
}
else if ((count($userP) > 0) and ($userP['i_layout']==0))
{
    $proofreading_fonti = $userP['h_fntf'];
    $proofreading_font = $f_f[$proofreading_fonti];
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

echo "<p>" . _("This page demonstrates the differences between <span style='font-family: DPCustomMono2'>DPCustomMono2</span> and other fonts.") . "</p>\n";

echo "<p>" . sprintf(_("DPCustomMono2 is a font adapted by DP's own big_bill, based on the suggestions and ideas of many experienced proofreaders. Using DPCustomMono2 as your proofreading font can help you find mistakes. You can change the font that you use for proofreading in your <a href='%s'>preferences</a>."), "$code_url/userprefs.php") . "</p>\n";

if(!empty($proofreading_font))
    echo "<p>" . sprintf(_("Your current proofreading font is <b>%s</b>."), $proofreading_font) . "</p>\n";

echo "<p style='font-family: DPCustomMono2'>" . _("If you already have DPCustomMono2 installed, you will see this paragraph in that typeface. If this paragraph's font doesn't look radically different to that of the paragraph above, you can download DPCustomMono2 from <a href='DPCustomMono2.ttf'>here</a> (right click the link, and choose Save Target As..) After you have installed the font please refresh this page to make sure DPCustomMono2 is installed correctly.") . "</p>\n";

echo "<p>" . sprintf(_("For more information on installing and using the font, see the <a href='http://www.pgdp.net/wiki/Installing_DPCustomMono'>Installing DPCustomMono</a> wiki page at pgdp.net."),"$code_url/userprefs.php") . "</p>\n";

echo "<hr width='70%'>\n";

// build a list of fonts that we have comparison images for
$sample_font_links=array();
foreach ($selectable_fonts as $font)
{
    // don't print a link if we don't have an image for the font
    if(is_file("$code_dir/faq/images/${font}_A.gif"))
    {
        if($compare_font == $font)
            $sample_font_links[] = "<span style='font-family: $font'>$font</span>";
        else
            $sample_font_links[] = "<a style='font-family: $font' href='?compare=$font'>$font</a>";
    }
}


echo "<p>" . _("Select a font from the list below to see a sample text in the desired font compared to the sample text in DPCustomMono2.") . "</p>\n";

echo "<p>" . implode("\n| ", $sample_font_links) . "</p>\n";

// print out the comparison images
echo "<table class='fontcompare'>";
echo "<tr>";
echo "<th>" . sprintf(_("Selected Font (%s)"), $compare_font) . "</th>";
echo "<th>" . _("DPCustomMono2") . "</th>";
echo "</tr>";

echo "<tr>";
echo "<td><img src='images/${compare_font}_A.gif'></td>";
echo "<td><img src='images/DPCustomMono2_A.gif'></td>";
echo "</tr>";

echo "<tr>";
echo "<td><img src='images/${compare_font}_B.gif'></td>";
echo "<td><img src='images/DPCustomMono2_B.gif'></td>";
echo "</tr>";

echo "<tr>";
echo "<td><img src='images/${compare_font}_C.gif'></td>";
echo "<td><img src='images/DPCustomMono2_C.gif'></td>";
echo "</tr>";

echo "</table>";

theme("", "footer");

// vim: sw=4 ts=4 expandtab
?>
