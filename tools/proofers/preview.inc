<?php
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');

function get_style_strings()
{
    return array(
        "italic" => _("Italic"),
        "bold" => _("Bold"),
        "gesperrt" => _("Gesperrt"),
        "small_caps" => _("Small caps"),
        "font_change" => _("Font change"),
    );
}

function get_preview_messages()
{
    return array(
        "noStartTag" => _("No start tag for this end tag"),
        "noEndTag" => _("No end tag for this start tag"),
        "noEndTagInPara" => _("No corresponding end tag in paragraph"),
        "misMatchTag" => _("End tag does not match start tag"),
        "nestedTag" => _("Tag nested within same tag"),
        "unRecTag" => _("Unrecognized tag"),
        "tabChar" => _("Tab should not be used"),
        "charBefore" => _("No characters should precede this"),
        "blankBefore" => _("A blank line should precede this"),
        "blankAfter" => _("A blank line should follow %s"),
        "NWinNW" => _("No-wrap inside no-wrap"),
        "BQinNW" => _("Block quote inside no-wrap"),
        "charAfter" => _("No characters should follow %s"),
        "OolPrev" => _("Out-of-line start tag should not be preceded by normal text"),
        "OolNext" => _("Out-of-line end tag should not be followed by normal text"),
        "blankLines124" => _("Only 1, 2 or 4 blank lines should be used"),
        "puncAfterStart" => _("Punctuation after start tag"),
        "spaceAfterStart" => _("Space after start tag"),
        "nlAfterStart" => _("Newline after start tag"),
        "nlBeforeEnd" => _("Newline before end tag"),
        "spaceBeforeEnd" => _("Space before end tag"),
        "noBold" => _("Heading should not be entirely bold"),
        "scNoCap" => _("Small caps must contain at least one upper case character"),
        "charBeforeStart" => _("Character or punctuation before inline start tag"),
        "charAfterEnd" => _("Character after inline end tag"),
        "puncBEnd" => _(",; or : before end tag"),
        "noCloseBrack" => _("No matching closing bracket"),
        "footnoteId" => _("Footnote identifier should be a letter or number"),
        "starAnchor" => _("Footnote anchor should be an upper-case letter"),
        "noFootnote" => _("No corresponding footnote on this page"),
        "noAnchor" => _("No anchor for this footnote"),
        "noColon" => _("Footnote must have a colon"),
        "colonNext" => _("The colon should immediately follow *[Footnote"),
        "spaceNext" => _("Footnote should be followed by one space and identifier"),
        "dupNote" => _("Duplicate footnote identifier"),
        "continueFirst" => _("Continuation footnote should precede others"),
        "sideNoteBlank" => _("A blank line should precede and follow Sidenote"),
        "emptyTag" => _("Empty tag"),
        "multipleAnchors" => _("Multiple anchors for same footnote")
    );
}

function get_preview_js_vars()
{
    global $charset, $utf8_site;

    $messages = get_preview_messages();
    $plain = _("Plain text");
    $superscript = _("superscript");
    $subscript = _("subscript");
    $styles = get_style_strings();

    $demo_string = javascript_safe("$plain _{{$subscript}} <i>$styles[italic]</i> ^{{$superscript}} <b>$styles[bold]</b> <g>$styles[gesperrt]</g> <sc>$styles[small_caps]</sc> <f>$styles[font_change]</f>");
    $ie_warn = javascript_safe(_("This function is not available for Internet Explorer versions less than 9"), $charset);

    if(!$utf8_site)
    {
        array_walk($messages, function(&$item) { $item = utf8_encode($item);} );
    }
    $preview_msg = json_encode($messages);

    return "var previewMessages = $preview_msg; var previewDemo = '$demo_string'; var ieWarn = '$ie_warn';";
}

function output_preview_div()
{
    global $code_url;

    $messages = get_preview_messages();
    $quit = attr_safe(_("Quit"));
    $configure = attr_safe(_("Configure"));
    $add_font = attr_safe(_("Add Font"));
    $remove_font = attr_safe(_("Remove Font"));
    $ok = attr_safe(_("OK"));
    $cancel = attr_safe(_("Cancel"));
    $some_suppressed = attr_safe(_('Some issue types are suppressed'));
    $color_markup = html_safe(_("Color markup"));
    $image =  html_safe(_("Image"));
    $text = html_safe(_("Text"));
    $font = html_safe(_("Font"));
    $tags = html_safe(_("Tags"));
    $no_tags = html_safe(_("No Tags"));
    $re_wrap = html_safe(_("Re-wrap"));
    $issues = html_safe(_("Issues"));
    $poss_iss = html_safe(_("Poss. Issues"));
    $default = html_safe(_("Default"));
    $other_tags = html_safe(_("Other tags"));
    $background = html_safe(_("Background"));
    $allow_underine = html_safe(_("Allow <u> for underline"));
    $suppress_warnings = html_safe(_('Suppress warnings'));
    $choose_colors = html_safe(_("Choose Colors"));
    $initial_mode = html_safe(_("Initial view mode"));
    $styles = get_style_strings();
    array_walk($styles, function(&$item) { $item = html_safe($item);} );

    $supp_set = array('charBeforeStart', 'sideNoteBlank');
    $supp_item_string = "";
    foreach($supp_set as $supp_item)
    {
        $supp_item_string .= ("<label><input type='checkbox' id='$supp_item'>" . html_safe($messages[$supp_item]) . "</label><br>\n");
    }

    echo <<<END
<div id='prevdiv' class='no_display'>
 <div class='flex_container'>
  <div id="id_tp_outer" class='stretchbox'>
    <div id="text_preview">
    </div>
  </div>
  <div id="id_controls" class="fixedbox">
    <input type='button' onclick="previewControl.hide()" value="$quit">
    <span class='ilb'><label>$color_markup<input type="checkbox" id="id_color_on" onchange="previewControl.enableColor(this.checked)" ></label></span>
    <span class='ilb'>$image
      <input type="button" value="-" onclick="top.reSizeRelative(0.91);">
      <input type="button" value="+" onclick="top.reSizeRelative(1.10);">
    </span>
    <span class='ilb'>$text
      <input type="button" value="-" onclick="previewControl.reSizeText(0.91);">
      <input type="button" value="+" onclick="previewControl.reSizeText(1.1);">
    </span>
    <span class='ilb'>$font <select id="id_font_sel" onchange="previewControl.selectFont(this.value)"></select></span>
    <span class='ilb'>
      <label><input type="radio" name="viewSel" id="show_tags">$tags</label>
      <label><input type="radio" name="viewSel" id="no_tags">$no_tags</label>
      <label><input type="radio" name="viewSel" id="flat">$text</label>
    </span>
    <span class='ilb'>
    <label><input type="checkbox" id="re_wrap">$re_wrap</label>
    </span>
    <span class='ilb'>$issues <input type="text" id="id_iss" size="1" readonly></span>
    <span class='ilb'>$poss_iss <input type="text" id="id_poss_iss" size="1" readonly></span>
    <img src='$code_url/graphics/exclamation.gif' id='id_some_supp' title='$some_suppressed'>
    <input type='button' onclick="previewControl.configure()" value="$configure">
  </div>
 </div>
</div>

<div id="id_config_panel" class='no_display'>
  <div id='color_test'>
  </div>
  <fieldset>
    <legend>$choose_colors</legend>
    <div class="display-flex">
    <div id="id_markmenu">
        <input type="radio" name="mSel" id="id_default_radio" onclick="previewControl.setTagColors('t')"> $default<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('i')"> $styles[italic]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('b')"> $styles[bold]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('g')"> $styles[gesperrt]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('sc')"> $styles[small_caps]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('f')"> $styles[font_change]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('etc')"> $other_tags<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('err')"> $issues<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('hlt')"> $poss_iss<br>
    </div>
    <div id="color_selector">
        <input type="color" id="id_forecol" onchange="previewControl.setForegroundColor()"> $text
        <span id="span_foreground"><input type="checkbox" id="foreground_checkbox" onchange="previewControl.setForegroundColor()" checked> $default</span><br>
        <input type="color" id="id_backcol" onchange="previewControl.setBackgroundColor()"> $background
        <span id="span_background"><input type="checkbox" id="background_checkbox" onchange="previewControl.setBackgroundColor()" checked> $default</span><br>
    </div>
    </div>
  </fieldset>
  <div class="box2">
    <span class="ilb"><input type='text' id='id_font_name'><input type='button' onclick="previewControl.addFont()" value="$add_font"></span>
    <span class="ilb"><select id="id_remove_sel"></select><input type='button' onclick="previewControl.removeFont()" value="$remove_font"></span>
   </div>
  <div class="box2">
    <span class='ilb'><label>$allow_underine<input type="checkbox" id="id_underline"></label></span>
  </div>
  <fieldset>
    <legend>$suppress_warnings</legend>
    $supp_item_string
  </fieldset>
  <br><span class='ilb'>$initial_mode <select id="id_init_mode">
    <option value="show_tags">$tags</option>
    <option value="no_tags">$no_tags</option>
    <option value="flat">$text</option>
  </select></span>
  <div class="box2">
    <input type='button' onclick="previewControl.OKConfig()" value="$ok">
    <input type='button' onclick="previewControl.cancelConfig()" value="$cancel">
  </div>
</div>
END;
}
