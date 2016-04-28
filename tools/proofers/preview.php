<div id = 'prevdiv' class='invis'>
  <div id = "id_controls">
    <input type='button' onclick="PrevControl.hide()" value = "Quit">
    <span class='ilb'><label>Colour markup<input type="checkbox" id="id_colon" onchange="PrevControl.enableCol(this.checked)" ></label></span>
    <span class='ilb'>Image
      <input type="button" value="-" onclick="top.reSizeRelative(0.91);">
      <input type="button" value="+" onclick="top.reSizeRelative(1.10);">
    </span>
    <span class='ilb'>Text
      <input type="button" value="-" onclick="PrevControl.reSizeText(0.91);">
      <input type="button" value="+" onclick="PrevControl.reSizeText(1.1);">
    </span>
    <span class='ilb'>Font <select id="id_font_sel" onchange="PrevControl.selFont(this.value)"></select></span>
    <span class='ilb'>
      <label><input type="radio" name="viewSel" value="T" onclick="PrevControl.write('T')" id="id_tags">Tags</label>
      <label><input type="radio" name="viewSel" value="NT" onclick="PrevControl.write('NT')" checked>No Tags</label>
      <label><input type="radio" name="viewSel" value="RW" onclick="PrevControl.write('RW')">Re-wrap</label>
    </span>
    <span class='ilb'>Issues <input type="text" id="id_iss" size="1" readonly></span>
    <span class='ilb'>Poss. Issues <input type="text" id="id_poss_iss" size="1" readonly></span>
    <input type='button' onclick="PrevControl.configure()" value = "Configure">
  </div>
  <div id="id_tp_outer">
    <div id="text_preview">
    </div>
  </div>
</div>

<div id="id_config_panel" class='invis'>
  <div id='color_test'>
  </div>
  <div class="clearfix box1">
    <div id="id_markmenu">
        <input type="radio" name="mSel" id="id_tradio" onclick="PrevControl.setColors('t')"> Default<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('i')"> Italic<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('b')"> Bold<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('g')"> Gesperrt<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('sc')"> Small caps<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('f')"> Font change<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('etc')"> Other tags<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('err')"> Issues<br>
        <input type="radio" name="mSel" onclick="PrevControl.setColors('hlt')"> Possible Issues<br>
    </div>
    <div id="color_selector">
        <input type="color" id="id_forecol" onchange="PrevControl.setFCol()"> Text
        <span id="id_span_frbox"><input type="checkbox" id="id_frbox" onchange="PrevControl.setFCol()" checked> Default</span><br>
        <input type="color" id="id_backcol" onchange="PrevControl.setBCol()"> Background
        <span id="id_span_bkbox"><input type="checkbox" id="id_bkbox" onchange="PrevControl.setBCol()" checked> Default</span><br>
    </div>
  </div>
  <div class="box2">
    <span class="ilb"><input type='text' id='id_font_name'><input type='button' onclick="PrevControl.addFont()" value = "Add Font"></span>
    <span class="ilb"><select id="id_fsel1"></select><input type='button' onclick="PrevControl.remFont()" value = "Remove Font"></span>
   </div>
  <div class="box2">
    <input type='button' onclick="PrevControl.OKConfig()" value = "OK">
    <input type='button' onclick="PrevControl.cancelConfig()" value = "Cancel">
  </div>
</div>
