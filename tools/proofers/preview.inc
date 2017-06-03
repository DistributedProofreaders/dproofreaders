<?php
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');

function shared_strings($escape_function="html_safe")
{
    return array(
        "italic" => $escape_function(_("Italic")),
        "bold" => $escape_function(_("Bold")),
        "gesperrt" => $escape_function(_("Gesperrt")),
        "small_caps" => $escape_function(_("Small caps")),
        "font_change" => $escape_function(_("Font change")),
    );
}
    
function output_preview_strings()
{
    global $charset;

    $plain = javascript_safe(_("Plain text"));
    $superscript = javascript_safe(_("superscript"));
    $ss = shared_strings("javascript_safe");

    $demo_string = "$plain <i>$ss[italic]</i> <b>$ss[bold]</b> <g>$ss[gesperrt]</g> <sc>$ss[small_caps]</sc> <f>$ss[font_change]</f>^{" . "$superscript}";
    $ie_warn = javascript_safe(_("This function is not available for Internet Explorer versions less than 9"), $charset);
    
    $msg = array(
        "noStartTag" => javascript_safe(_("No start tag for this end tag")),
        "noEndTag" => javascript_safe(_("No end tag for this start tag")),
        "noEndTagInPara" => javascript_safe(_("No corresponding end tag in paragraph")),
        "misMatchTag" => javascript_safe(_("End tag does not match start tag")),
        "nestedTag" => javascript_safe(_("Tag nested within same tag")),
        "unRecTag" => javascript_safe(_("Unrecognized tag")),
        "tabChar" => javascript_safe(_("Tab should not be used")),
        "charBefore" => javascript_safe(_("No characters should precede this")),
        "blankBefore" => javascript_safe(_("A blank line should precede this")),
        "blankAfter" => javascript_safe(_("A blank line should follow %s")),
        "NWinNW" => javascript_safe(_("No-wrap inside no-wrap")),
        "BQinNW" => javascript_safe(_("Block quote inside no-wrap")),
        "aloneTag" => javascript_safe(_("This tag should be alone on its line")),
        "OolPrev" => javascript_safe(_("Out-of-line start tag should not be preceded by normal text")),
        "OolNext" => javascript_safe(_("Out-of-line end tag should not be followed by normal text")),
        "blankLines124" => javascript_safe(_("Only 1, 2 or 4 blank lines should be used")),
        "puncAfterStart" => javascript_safe(_("Punctuation after start tag")),
        "spaceAfterStart" => javascript_safe(_("Space after start tag")),
        "nlAfterStart" => javascript_safe(_("Newline after start tag")),
        "nlBeforeEnd" => javascript_safe(_("Newline before end tag")),
        "spaceBeforeEnd" => javascript_safe(_("Space before end tag")),
        "noBold" => javascript_safe(_("Heading should not be entirely bold")),
        "scNoCap" => javascript_safe(_("Small caps must contain at least one upper case character")),
        "charBeforeStart" => javascript_safe(_("Character or punctuation before inline start tag")),
        "charAfterEnd" => javascript_safe(_("Character after inline end tag")),
        "puncBEnd" => javascript_safe(_(",; or : before end tag")),
        "noCloseBrack" => javascript_safe(_("No matching closing bracket")),
        "footnoteId" => javascript_safe(_("Footnote identifier should be a letter or number")),
        "starAnchor" => javascript_safe(_("Footnote anchor should be an upper-case letter")),
        "noFootnote" => javascript_safe(_("No corresponding footnote on this page")),
        "noAnchor" => javascript_safe(_("No anchor for this footnote")),
        "noColon" => javascript_safe(_("Footnote must have a colon")),
        "colonNext" => javascript_safe(_("The colon should immediately follow *[Footnote")),
        "spaceNext" => javascript_safe(_("Footnote should be followed by one space and identifier")),
        "dupNote" => javascript_safe(_("Duplicate footnote identifier")),
        "continueFirst" => javascript_safe(_("Continuation footnote should precede others"))

    );

    // json_encode() assumes everything is in UTF-8, but we may not be so
    // just build the JSON object ourselves
    $json_entries = [];
    foreach($msg as $key => $value)
    {
        $json_entries[] = "\"$key\": \"$value\"";
    }
    $preview_msg = "{" . implode(",", $json_entries) . "}";

    echo "var previewMessages = JSON.parse('$preview_msg'); var previewDemo = '$demo_string'; var ieWarn = '$ie_warn'";
}

function output_preview_div()
{
    $quit = attr_safe(_("Quit"));
    $configure = attr_safe(_("Configure"));
    $add_font = attr_safe(_("Add Font"));
    $remove_font = attr_safe(_("Remove Font"));
    $ok = attr_safe(_("OK"));
    $cancel = attr_safe(_("Cancel"));

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
    $ss = shared_strings("html_safe");
    $text = html_safe(_("Text"));
    $background = html_safe(_("Background"));
    $allow_underine = html_safe(_("Allow underline"));

    echo <<<END
<div id='prevdiv' class='no_display'>
  <div id="id_controls">
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
      <label><input type="radio" name="viewSel" onclick="previewControl.write('show_tags')" id="id_tags">$tags</label>
      <label><input type="radio" name="viewSel" onclick="previewControl.write('no_tags')" checked>$no_tags</label>
      <label><input type="radio" name="viewSel" onclick="previewControl.write('re_wrap')">$re_wrap</label>
    </span>
    <span class='ilb'>$issues <input type="text" id="id_iss" size="1" readonly></span>
    <span class='ilb'>$poss_iss <input type="text" id="id_poss_iss" size="1" readonly></span>
    <input type='button' onclick="previewControl.configure()" value="$configure">
  </div>
  <div id="id_tp_outer">
    <div id="text_preview">
    </div>
  </div>
</div>

<div id="id_config_panel" class='no_display'>
  <div id='color_test'>
  </div>
  <div class="clearfix box1">
    <div id="id_markmenu">
        <input type="radio" name="mSel" id="id_default_radio" onclick="previewControl.setTagColors('t')"> $default<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('i')"> $ss[italic]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('b')"> $ss[bold]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('g')"> $ss[gesperrt]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('sc')"> $ss[small_caps]<br>
        <input type="radio" name="mSel" onclick="previewControl.setTagColors('f')"> $ss[font_change]<br>
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
  <div class="box2">
    <span class="ilb"><input type='text' id='id_font_name'><input type='button' onclick="previewControl.addFont()" value="$add_font"></span>
    <span class="ilb"><select id="id_remove_sel"></select><input type='button' onclick="previewControl.removeFont()" value="$remove_font"></span>
   </div>
  <div class="box2">
    <span class='ilb'><label>$allow_underine<input type="checkbox" id="id_underline"></label></span>
    <input type='button' onclick="previewControl.OKConfig()" value="$ok">
    <input type='button' onclick="previewControl.cancelConfig()" value="$cancel">
  </div>
</div>

END;
}
