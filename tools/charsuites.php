<?php
$relPath='../pinc/';
include_once($relPath."base.inc");
include_once($relPath."theme.inc");
include_once($relPath."unicode.inc");
include_once($relPath."CharSuites.inc");
include_once($relPath."misc.inc"); // array_get()

require_login();

$charsuite_name = array_get($_GET, "charsuite", NULL);
$font = array_get($_REQUEST, "font", NULL);
$projectid = validate_projectID($_REQUEST, @$_REQUEST["projectid"], TRUE);

$charsuite = NULL;

if(!$projectid)
{
    try {
        $charsuite = CharSuites::get($charsuite_name);
    } catch (UnexpectedValueException $e) {
        // continue
    }
}

$extra_args = [];
if($font)
{
    $extra_args['css_data'] = ".gs-char { font-family: $font; }";
}

if($charsuite)
{
    $title = sprintf(_("Character Suite: %s"), $charsuite->title);
    output_header($title, NO_STATSBAR, $extra_args);
    echo "<h1>$title</h1>";
    echo "<p><a href='?'>" . _("View all character suites") . "</a></p>";
    if($font !== NULL)
    {
        output_font_test_form($font);
    }
    output_charsuite($charsuite, NULL, $font);
    output_pickerset($charsuite->pickerset, $charsuite->codepoints);
}
elseif($projectid)
{
    $project = new Project($projectid);
    $title = _("Project Character Suites");
    output_header($title, NO_STATSBAR, $extra_args);
    echo "<h1>$title</h1>";
    echo "<p>" . sprintf(_("Character Suites for <b>%s</b>."), $project->nameofwork) . "</p>";
    $charsuites = $project->get_charsuites();
    foreach($charsuites as $charsuite)
    {
        output_charsuite($charsuite, $charsuite->title, $font);
    }
}
else
{
    $title = _("All Character Suites");
    output_header($title, NO_STATSBAR, $extra_args);
    echo "<h1>$title</h1>";
    echo "<p>" . _("Below are all enabled character suites in the system.") . "</p>";
    if($font !== NULL)
    {
        output_font_test_form($font);
    }
    $enabled_charsuites = CharSuites::get_enabled();
    foreach($enabled_charsuites as $charsuite)
    {
        output_charsuite($charsuite, $charsuite->title, $font);
    }

    $all_charsuites = CharSuites::get_all();
    if(count($all_charsuites) > count($enabled_charsuites))
    {
        echo "<h1>" . _("Disabled Character Suites") . "</h1>";
        echo "<p>" . _("The following character suites are installed but not enabled and cannot be used for new projects. They may not be finalized.") . "</p>";

        foreach($all_charsuites as $charsuite)
        {
            if(!$charsuite->is_enabled())
                output_charsuite($charsuite, $charsuite->title, $font);
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
        "DP Sans Mono",
        "DejaVu Sans Mono",
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

function output_charsuite($charsuite, $title=NULL, $test_font=NULL)
{
    if($title)
    {
        $slug = utf8_url_slug($title);
        echo "<h2 id='$slug'>$title</h2>";
        $encoded_name = urlencode($charsuite->name);
        $font_attr = $test_font !== NULL ? ("&amp;font=" . urlencode($test_font)) : "";
        echo "<p><a href='?charsuite=$encoded_name$font_attr'>" . _("View character suite details") . "</a></p>";
    }
    elseif(!$charsuite->is_enabled())
    {
        echo "<p class='warning'>". _("This charsuite is installed but not enabled and cannot be used for new projects.") . "</p>";
    }

    echo "<p>" . _("Below are all the characters with their Unicode codepoints that are available within this character suite. Hovering over a character will show its Unicode name in a tooltip.") . "</p>";

    output_codepoints_table($charsuite->codepoints);

    if(!$title)
    {
        echo "<p>" . _("Reference URLs") . ":";
        echo "<ul>";
        foreach($charsuite->reference_urls as $url)
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
    if(!$pickerset)
    {
        echo "<p>" . _("No picker set is defined for this character suite.") . "</p>";
        return;
    }
    echo "<p>" . _("The following groupings represent sets of characters available in the character picker within the proofreading interface for projects using this character suite. Each grouping is labeled by a one- to four-character string that is used for the grouping's menu within the character picker.") . "</p>";
    $set = $pickerset->get_subsets();
    $picker_characters = [];
    foreach($set as $menu => $coderows)
    {
        $header = $menu;
        $title = $pickerset->get_title($menu);
        if($menu != $title)
        {
            $header .= " - $title";
        }
        // first the menu item
        echo "<h3>$header</h3>";

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

function output_codepoints_table($charsuite, $table_width=16)
{
    # maximum number of codepoints to output
    $MAX_CODEPOINTS = 2048;

    $characters = convert_codepoint_ranges_to_characters($charsuite);

    echo "<table class='basic'>";

    $offset = 0;
    while(($slice = array_slice($characters, $offset, $table_width)) &&
        $offset < $MAX_CODEPOINTS)
    {
        echo "<tr>";
        output_codepoints_slice($slice);
        echo "</tr>";
        $offset += $table_width;
    }
    echo "</table>";

    if($offset >= $MAX_CODEPOINTS)
    {
        echo "<p class='warning'>";
        echo sprintf(
            _("Only %1\$s of %2\$s codepoints printed."),
            $offset,
            count($characters)
        );
        echo "</p>";
    }
}

function output_codepoints_slice($slice)
{
    foreach($slice as $char)
    {
        if($char !== NULL)
        {
            $title = utf8_character_name($char);
            $codepoint = string_to_codepoints_string($char, "<br>");
            $char = html_safe($char);
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
