<?php
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');

function shared_strings()
{
    return array(
        "italic" => html_safe(_("Italic")),
        "bold" => html_safe(_("Bold")),
        "gesperrt" => html_safe(_("Gesperrt")),
        "small_caps" => html_safe(_("Small caps")),
        "font_change" => html_safe(_("Font change"))
    );
}
    
function output_preview_strings()
{
    $plain = html_safe(_("Plain text"));
    $superscript = html_safe(_("superscript"));
    $ss = shared_strings();

    $demo_string = "$plain <i>$ss[italic]</i> <b>$ss[bold]</b> <g>$ss[gesperrt]</g> <sc>$ss[small_caps]</sc> <f>$ss[font_change]</f> ^{" . "$superscript}";
    
    $msg = array(
        "noStartTag" => html_safe(_("No start tag for this end tag")),
        "noEndTag" => html_safe(_("No end tag for this start tag")),
        "noEndTagInPara" => html_safe(_("No corresponding end tag in paragraph")),
        "misMatchTag" => html_safe(_("End tag does not match start tag")),
        "nestedTag" => html_safe(_("Tag nested within same tag")),
        "unRecTag" => html_safe(_("Unrecognized tag")),
        "tabChar" => html_safe(_("Tab should not be used")),
        "BLine1" => html_safe(_("This should have a blank line before")),
        "BLine2" => html_safe(_("This should have a blank line after")),
        "NWinNW" => html_safe(_("No-wrap inside no-wrap")),
        "BQinNW" => html_safe(_("Block quote inside no-wrap")),
        "aloneTag" => html_safe(_("This tag should be alone on its line")),
        "OolPrev" => html_safe(_("Out-of-line start tag should not be preceded by normal text")),
        "OolNext" => html_safe(_("Out-of-line end tag should not be followed by normal text")),
        "blankLines" => html_safe(_("Only 1, 2 or 4 blank lines should be used")),
        "spaceAfterStart" => html_safe(_("Space or punctuation just inside start tag")),
        "nlAfterStart" => html_safe(_("Newline after start tag")),
        "nlBeforeEnd" => html_safe(_("Newline before end tag")),
        "spaceBeforeEnd" => html_safe(_("Space before end tag")),
        "noBold" => html_safe(_("Heading should not be entirely bold")),
        "scNoCap" => html_safe(_("Small caps must contain at least one upper case character")),
        "charBeforeStart" => html_safe(_("Character or punctuation before inline start tag")),
        "charAfterEnd" => html_safe(_("Character after inline end tag")),
        "puncBEnd" => html_safe(_(",; or : before end tag")),
        "noCloseBrack" => html_safe(_("No matching closing bracket")),
        "footnoteId" => html_safe(_("Missing footnote identifier"))
    );

    $preview_msg = json_encode($msg);
    echo "var previewMessages = JSON.parse('$preview_msg'); var previewDemo = '$demo_string';";
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
    $ss = shared_strings();
    $text = html_safe(_("Text"));
    $background = html_safe(_("Background"));

    echo <<<END
<div id = 'prevdiv' class='no_display'>
  <div id = "id_controls">
    <input type='button' onclick="PrevControl.hide()" value = "$quit">
    <span class='ilb'><label>$color_markup<input type="checkbox" id="id_colon" onchange="PrevControl.enableCol(this.checked)" ></label></span>
    <span class='ilb'>$image
      <input type="button" value="-" onclick="top.reSizeRelative(0.91);">
      <input type="button" value="+" onclick="top.reSizeRelative(1.10);">
    </span>
    <span class='ilb'>$text
      <input type="button" value="-" onclick="PrevControl.reSizeText(0.91);">
      <input type="button" value="+" onclick="PrevControl.reSizeText(1.1);">
    </span>
    <span class='ilb'>$font <select id="id_font_sel" onchange="PrevControl.selFont(this.value)"></select></span>
    <span class='ilb'>
      <label><input type="radio" name="viewSel" value="T" onclick="PrevControl.write('T')" id="id_tags">$tags</label>
      <label><input type="radio" name="viewSel" value="NT" onclick="PrevControl.write('NT')" checked>$no_tags</label>
      <label><input type="radio" name="viewSel" value="RW" onclick="PrevControl.write('RW')">$re_wrap</label>
    </span>
    <span class='ilb'>$issues <input type="text" id="id_iss" size="1" readonly></span>
    <span class='ilb'>$poss_iss <input type="text" id="id_poss_iss" size="1" readonly></span>
    <input type='button' onclick="PrevControl.configure()" value = "$configure">
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
        <input type="radio" name="mSel" id="id_tradio" onclick="PrevControl.setColors('t')"> $default<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('i')"> $ss[italic]<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('b')"> $ss[bold]<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('g')"> $ss[gesperrt]<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('sc')"> $ss[small_caps]<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('f')"> $ss[font_change]<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('etc')"> Other tags<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('err')"> $issues<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('hlt')"> $poss_iss<br>
    </div>
    <div id="color_selector">
        <input type="color" id="id_forecol" onchange="PrevControl.setFCol()"> $text
        <span id="id_span_frbox"><input type="checkbox" id="id_frbox" onchange="PrevControl.setFCol()" checked> $default</span><br>
        <input type="color" id="id_backcol" onchange="PrevControl.setBCol()"> $background
        <span id="id_span_bkbox"><input type="checkbox" id="id_bkbox" onchange="PrevControl.setBCol()" checked> $default</span><br>
    </div>
  </div>
  <div class="box2">
    <span class="ilb"><input type='text' id='id_font_name'><input type='button' onclick="PrevControl.addFont()" value = "$add_font"></span>
    <span class="ilb"><select id="id_fsel1"></select><input type='button' onclick="PrevControl.remFont()" value = "$remove_font"></span>
   </div>
  <div class="box2">
    <input type='button' onclick="PrevControl.OKConfig()" value = "$ok">
    <input type='button' onclick="PrevControl.cancelConfig()" value = "$cancel">
  </div>
</div>

END;
}