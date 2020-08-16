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
$projectid = get_projectID_param($_REQUEST, "projectid", TRUE);

$charsuite = NULL;

if($charsuite_name && !$projectid)
{
    try {
        $charsuite = CharSuites::get($charsuite_name);
    } catch (UnexpectedValueException $e) {
        // try loading the suite as though it were a project id
        // for a custom project suite
        try {
            $project = new Project($charsuite_name);
            $charsuite = $project->get_custom_charsuite();
        } catch (NonexistentProjectException $e) {
            // continue
        }
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
    $title = _("Character Suite");
    output_header($title, NO_STATSBAR, $extra_args);
    echo "<h1>" . html_safe($title) . "</h1>";
    echo "<p><a href='?'>" . _("View all character suites") . "</a></p>";
    echo "<p>";
    echo _("Below are all the characters with their Unicode codepoints that are available within this character suite.");
    echo " ";
    echo _("Hovering over a character will show its Unicode name in a tooltip.");
    echo "</p>";
    if($font !== NULL)
    {
        output_font_test_form($font);
    }
    output_charsuite($charsuite, TRUE, $font);
    output_pickerset($charsuite->pickerset, $charsuite->codepoints);
}
elseif($projectid)
{
    $project = new Project($projectid);
    $title = _("Project Character Suites");
    output_header($title, NO_STATSBAR, $extra_args);
    echo "<h1>" . html_safe($title) . "</h1>";
    echo "<p>" . sprintf(
        _("Character Suites for <a href='%s'>%s</a>."),
        "$code_url/project.php?id=$projectid",
        html_safe($project->nameofwork)
    ) . "</p>";
    $charsuites = $project->get_charsuites();
    foreach($charsuites as $charsuite)
    {
        output_charsuite($charsuite, FALSE, $font);
    }
}
else
{
    $title = _("All Character Suites");
    output_header($title, NO_STATSBAR, $extra_args);
    echo "<h1>" . html_safe($title) . "</h1>";
    echo "<p>";
    echo _("Below are all enabled character suites in the system.");
    echo " ";
    echo _("Hovering over a character will show its Unicode name in a tooltip.");
    echo "</p>";
    if($font !== NULL)
    {
        output_font_test_form($font);
    }
    $enabled_charsuites = CharSuites::get_enabled();
    foreach($enabled_charsuites as $charsuite)
    {
        output_charsuite($charsuite, FALSE, $font);
    }

    $all_charsuites = CharSuites::get_all();
    if(count($all_charsuites) > count($enabled_charsuites))
    {
        echo "<h1>" . _("Disabled Character Suites") . "</h1>";
        echo "<p>" . _("The following character suites are installed but not enabled and cannot be used for new projects. They may not be finalized.") . "</p>";

        foreach($all_charsuites as $charsuite)
        {
            if(!$charsuite->is_enabled())
                output_charsuite($charsuite, FALSE, $font);
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

function output_charsuite($charsuite, $show_detail, $test_font=NULL)
{
    $slug = utf8_url_slug($charsuite->title);
    echo "<h2 id='$slug'>" . html_safe($charsuite->title) . "</h2>";
    if($charsuite->description)
    {
        echo "<p>" . html_safe($charsuite->description) . "</p>";
    }

    if(!$show_detail)
    {
        $encoded_name = urlencode($charsuite->name);
        $font_attr = $test_font !== NULL ? ("&amp;font=" . urlencode($test_font)) : "";
        echo "<p><a href='?charsuite=$encoded_name$font_attr'>" . _("View character suite details") . "</a></p>";
    }
    elseif(!$charsuite->is_enabled())
    {
        echo "<p class='warning'>". _("This character suite is installed but not enabled and cannot be used for new projects.") . "</p>";
    }

    $characters = convert_codepoint_ranges_to_characters($charsuite->codepoints);
    output_characters_table($characters);

    if($show_detail && $charsuite->reference_urls)
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
        echo "<h3>" . html_safe($header) . "</h3>";

        // now the picker rows
        echo "<table class='basic'>";
        foreach($coderows as $row)
        {
            echo "<tr>";
            $characters = convert_codepoint_ranges_to_characters($row);
            $picker_characters = array_merge($picker_characters, $characters);
            output_characters_slice($characters);
            echo "</tr>";
        }
        echo "</table>";
    }
    $all_characters = convert_codepoint_ranges_to_characters($all_codepoints);
    $remainder = array_diff($all_characters, array_unique($picker_characters));
    if(count($all_characters) != count(array_unique($picker_characters)) && $remainder)
    {
        echo "<h3>" . _("Characters not in a picker set") . "</h3>";
        echo "<table class='basic'>";
        echo "<tr>";
        output_characters_table($remainder);
        echo "</tr>";
        echo "</table>";
    }
}

function output_characters_table($characters, $table_width=16)
{
    # maximum number of codepoints to output
    $MAX_CODEPOINTS = 2048;

    echo "<table class='basic'>";

    $offset = 0;
    while(($slice = array_slice($characters, $offset, $table_width)) &&
        $offset < $MAX_CODEPOINTS)
    {
        echo "<tr>";
        output_characters_slice($slice);
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

function output_characters_slice($slice)
{
    foreach($slice as $char)
    {
        if($char !== NULL)
        {
            $title = attr_safe(utf8_character_name($char));
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
