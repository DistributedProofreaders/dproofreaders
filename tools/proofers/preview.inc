<?php
$relPath = "./../../pinc/";

function get_preview_js_vars()
{
    $superscript = _("superscript");
    $subscript = _("subscript");

    $tag_names = [
        "t" => _("Plain text"),
        "i" => _("Italic"),
        "b" => _("Bold"),
        "g" => _("Gesperrt"),
        "sc" => _("Small caps"),
        "f" => _("Font change"),
        "etc" => _("Other tags"),
        "err" => _("Issues"),
        "hlt" => _("Poss. Issues"),
        "blockquote" => _("Block quote"),
        "nowrap" => _("No wrap"),
    ];

    $demo_string = <<<DEMO
        {$tag_names['t']} _{{$subscript}} <i>{$tag_names['i']}</i> ^{{$superscript}}
        <b>{$tag_names['b']}</b> <g>{$tag_names['g']}</g> <sc>{$tag_names['sc']}</sc> <f>{$tag_names['f']}</f>

        /#
        This is a block quote
        #/

        /*
        This is no-wrap text
        */
        DEMO;

    $preview_strings = json_encode([
        "background" => _("Background"),
        "text" => _("Text"),
        "previewDemo" => $demo_string,
        "ieWarn" => _("This function is not available for Internet Explorer versions less than 9"),
    ]);

    $tag_names = json_encode($tag_names);

    return "var tagNames = $tag_names; var previewStrings = $preview_strings; ";
}

function output_preview_div()
{
    global $code_url;

    $quit = attr_safe(_("Quit"));
    $configure = attr_safe(_("Configure"));
    $add_font = attr_safe(_("Add Font"));
    $remove_font = attr_safe(_("Remove Font"));
    $ok = attr_safe(_("OK"));
    $cancel = attr_safe(_("Cancel"));
    $some_suppressed = attr_safe(_('Some warnings are suppressed'));
    $color_markup = html_safe(_("Color markup"));
    $image = html_safe(_("Image"));
    $text = html_safe(_("Text"));
    $font = html_safe(_("Font"));
    $tags = html_safe(_("Tags"));
    $no_tags = html_safe(_("No Tags"));
    $re_wrap = html_safe(_("Re-wrap"));
    $issues = html_safe(_("Issues"));
    $poss_iss = html_safe(_("Poss. Issues"));
    $other_tags = html_safe(_("Other tags"));
    $allow_underine = html_safe(_("Allow <u> for underline"));
    $math_preview = html_safe(_("Preview Math"));
    $suppress_warnings = html_safe(_('Suppress warnings'));
    $choose_colors = html_safe(_("Choose Colors"));
    $select = html_safe(_("Select"));
    $options = html_safe(_("Options"));
    $initial_mode = html_safe(_("Initial view mode"));
    $char_before_start = html_safe(_("Character or punctuation before inline start tag"));
    $side_note_blank = html_safe(_("A blank line should precede and follow Sidenote"));

    // draw the format preview and controls
    echo <<<END
        <div id='prevdiv' class='no_display'>
            <div id='format_preview' class='flex_container'>
                <div id="id_tp_outer" class='stretchbox text-pane'>
                    <div id="text_preview">
                    </div>
                </div>
                <div id="id_controls" class="fixedbox control-pane">
                    <input type='button' onclick="previewControl.hide()" value="$quit">
                    <span class='ilb styleoption'><label for="id_color_on">$color_markup</label>
                        <input type="checkbox" id="id_color_on" onchange="previewControl.enableColor(this.checked)" >
                    </span>
                    <span class='ilb'>$text
                        <input type="button" value="-" onclick="previewControl.reSizeText(0.91);">
                        <input type="button" value="+" onclick="previewControl.reSizeText(1.1);">
                    </span>
                    <span class='ilb'>$font <select id="id_font_sel"></select></span>
                    <span class='ilb styleoption'>
                        <input type="radio" name="viewSel" id="show_tags"><label for="show_tags">$tags</label>
                        <input type="radio" name="viewSel" id="no_tags"><label for="no_tags">$no_tags</label>
                        <input type="radio" name="viewSel" id="flat"><label for="flat">$text</label>
                    </span>
                    <span class='ilb styleoption'>
                        <input type="checkbox" id="re_wrap"><label for="re_wrap">$re_wrap</label>
                    </span>
                    <span class='ilb'>$issues <input type="text" id="id_iss" size="1" readonly></span>
                    <span class='ilb'>$poss_iss <input type="text" id="id_poss_iss" size="1" readonly></span>
                    <img src='$code_url/graphics/exclamation.gif' id='id_some_supp' title='$some_suppressed'>
                    <input type='button' onclick="previewControl.configure()" value="$configure">
                </div>
            </div>
        </div>
        END;

    // draw the configuration panel
    echo "<div id='id_config_panel' class='no_display'>
<pre id='color_test'></pre>\n";

    echo "<fieldset><legend>$choose_colors</legend>\n<table id='color_table'></table>\n</fieldset>\n";

    echo <<<END1
            <fieldset>
                <legend>$options</legend>
                <input type="checkbox" id="id_underline"><label for="id_underline">$allow_underine</label><br>
                <input type="checkbox" id="id_math_preview"><label for="id_math_preview">$math_preview</label>
            </fieldset>
            <fieldset>
                <legend>$suppress_warnings</legend>
                <label><input type='checkbox' id='charBeforeStart'>$char_before_start</label><br>
                <label><input type='checkbox' id='sideNoteBlank'>$side_note_blank</label><br>
            </fieldset>
            <fieldset>
                <legend>$initial_mode</legend>
                <select id="id_init_mode">
                    <option value="show_tags">$tags</option>
                    <option value="no_tags">$no_tags</option>
                    <option value="flat">$text</option>
                </select>
            </fieldset>
            <div class="box2">
                <input type='button' onclick="previewControl.OKConfig()" value="$ok">
                <input type='button' onclick="previewControl.cancelConfig()" value="$cancel">
            </div>
        </div>
        END1;
}

function get_preview_font_data_js()
{
    $font_styles = get_font_styles();
    $font_styles["serif"] = _("Browser serif");
    $font_styles["sans-serif"] = _("Browser sans-serif");

    $font_families = get_full_font_families();
    $font_families["serif"] = "serif";
    $font_families["sans-serif"] = "sans-serif";

    return "fontStyles = " . json_encode($font_styles) . ";
            fontFamilies = " . json_encode($font_families) . ";";
}
