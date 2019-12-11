<?php
$relPath='../pinc/';
include_once($relPath."base.inc");
include_once($relPath."theme.inc");
include_once($relPath."unicode.inc");
include_once($relPath."Glyphsets.inc");
include_once($relPath."misc.inc"); // array_get()

$glyphset_name = array_get($_GET, "glyphset", NULL);
$font = array_get($_REQUEST, "font", NULL);
$set = array_get($_REQUEST, "set", 'default');

try {
    $glyphset = Glyphsets::get_glyphset($glyphset_name, $set);
} catch (UnexpectedValueException $e) {
    $glyphset = NULL;
}

$extra_args = [];
if($font)
{
    $extra_args['css_data'] = ".gs-char { font-family: $font; }";
}

if($glyphset)
{
    $title = sprintf(_("Glyphset: %s"), $glyphset_name);
    output_header($title, NO_STATSBAR, $extra_args);
    echo "<h1>$title</h1>";
    if($font !== NULL)
    {
        output_font_test_form($font);
    }
    output_glyphset($glyphset, NULL, $font);
    output_pickerset($glyphset->pickerset, $glyphset->codepoints);
}
else
{
    $title = _("All Glyphsets");
    output_header($title, NO_STATSBAR, $extra_args);
    echo "<h1>$title</h1>";
    echo "<p>" . _("Below are all available glyphsets in the system.") . "</p>";
    if($font !== NULL)
    {
        output_font_test_form($font);
    }
    $glyphsets = Glyphsets::get_glyphsets();
    foreach($glyphsets as $glyphset)
    {
        output_glyphset($glyphset, $glyphset->name, $font);
    }

    $proposed_glyphsets = Glyphsets::get_glyphsets('proposed');
    if($proposed_glyphsets)
    {
        echo "<h1>" . _("Proposed Glyphsets") . "</h1>";
        echo "<p>" . _("The following are proposed glyphsets. They are not finalized and cannot be used in projects.") . "</p>";

        foreach($proposed_glyphsets as $glyphset)
        {
            output_glyphset($glyphset, $glyphset->name, $font, "proposed");
        }
    }
}

#----------------------------------------------------------------------------

function output_font_test_form($font)
{
    echo "<div class='callout'>";
    echo "<form method='GET'>";
    echo  _("Try font") . ": ";
    echo "<input name='font' value='" . attr_safe($font) . "'> ";
    echo "<input type='submit'>";
    echo "<br>" . _("Available web fonts") . ": ";
    $fonts = [
        "DPCustomMono2",
        "DejaVu Sans Mono",
        "Noto Sans Mono",
        "Noto Mono",
    ];
    $urls = [];
    foreach($fonts as $font)
    {
        $font_url_encoded = urlencode($font);
        $font_http_encoded = html_safe($font);
        $urls[] = "<a href='?font=$font_url_encoded'>$font_http_encoded</a>";
    }
    echo implode(", ", $urls);
    echo "</div>";
}

function output_glyphset($glyphset, $title=NULL, $test_font=NULL, $set='default')
{
    if($title)
    {
        $slug = utf8_url_slug($title);
        echo "<h2 id='$slug'>$title</h2>";
        $encoded_name = urlencode($glyphset->name);
        $font_attr = $test_font !== NULL ? ("&amp;font=" . urlencode($test_font)) : "";
        $set_attr = $set !== 'default' ? ("&amp;set=" . urlencode($set)) : "";
        echo "<p><a href='?glyphset=$encoded_name$font_attr$set_attr'>" . _("View glyphset details") . "</a></p>";
    }

    echo "<p>" . _("Below are all the glyphs with their Unicode codepoints that are available within this Glyphset. Hovering over a character will show its Unicode name in a tooltip.") . "</p>";

    output_codepoints_table($glyphset->codepoints);

    if(!$title)
    {
        echo "<p>" . _("Reference URLs") . ":";
        echo "<ul>";
        foreach($glyphset->reference_urls as $url)
        {
            echo "<li><a href='$url'>$url</a></li>";
        }
        echo "</ul>";
        echo "</p>";
    }
}

function output_pickerset($pickerset, $all_codepoints)
{
    echo "<h2>" . _("Character Picker Sets") . "</h2>";
    echo "<p>" . _("The following groupings represent sets of glyphs available in the character picker within the proofreading interface for projects using this glyphset. Each grouping is labeled by a one- to four-character string that is used for the grouping's menu within the character picker.") . "</p>";
    $set = $pickerset->get_subsets();
    $picker_characters = [];
    foreach($set as $menu => $coderows)
    {
        // first the menu item
        echo "<h3>$menu</h3>";

        // now the 2 rows
        echo "<table class='basic'>";
        echo "<tr>";
        $row1_characters = convert_codepoint_ranges_to_characters($coderows[0]);
        $picker_characters = array_merge($picker_characters, $row1_characters);
        output_codepoints_slice($row1_characters);
        echo "</tr>";
        echo "<tr>";
        $row2_characters = convert_codepoint_ranges_to_characters($coderows[1]);
        $picker_characters = array_merge($picker_characters, $row2_characters);
        output_codepoints_slice($row2_characters);
        echo "</tr>";
        echo "</table>";
    }
    $all_characters = convert_codepoint_ranges_to_characters($all_codepoints);
    $remainder = array_diff($all_characters, array_unique($picker_characters));
    if(count($all_characters) != count(array_unique($picker_characters)) && $remainder)
    {
        echo "<h3>" . _("Codepoints not in picker") . "</h3>";
        echo "<table class='basic'>";
        echo "<tr>";
        output_codepoints_slice($remainder);
        echo "</tr>";
        echo "</table>";
    }
}

function output_codepoints_table($glyphset, $table_width=16)
{
    $characters = convert_codepoint_ranges_to_characters($glyphset);

    echo "<table class='basic'>";

    $offset = 0;
    while($slice = array_slice($characters, $offset, $table_width))
    {
        echo "<tr>";
        output_codepoints_slice($slice);
        echo "</tr>";
        $offset += $table_width;
    }
    echo "</table>";
}

function output_codepoints_slice($slice)
{
    foreach($slice as $char)
    {
        if($char !== NULL)
        {
            $title = IntlChar::charName($char);
            $codepoint = utf8_chr_to_hex($char);
            echo "<td class='center-align' title='$title'>";
            echo "<span class='gs-char'>$char</span><br>";
            echo "<span class='gs-codepoint'>$codepoint</span>";
            echo "</td>";
        }
        else
        {
            // this is just a placeholder
            echo "<td></td>";
        }
    }
}
