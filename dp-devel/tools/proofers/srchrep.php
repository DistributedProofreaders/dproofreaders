<?php
$relPath = './../../pinc/';
include_once($relPath."site_vars.php");
include_once($relPath."slim_header.inc");
slim_header(_("Search/Replace"));
?>

<script type='text/javascript'>
var saved_text = '';

function do_replace()
{
    var search_textbox = document.getElementById('search');
    var search = search_textbox.value;
    var regex_checkbox = document.getElementById('is_regex');
    var replacetext = document.getElementById('replace');
    save_text();
    var is_regex = regex_checkbox.checked;
    if (!is_regex)
    {
        search = preg_quote(search);
    }
    opener.parent.docRef.editform.text_data.value=opener.parent.docRef.editform.text_data.value.replace(new RegExp(search,'g'),replacetext.value);
    set_undo_button_disabled(false);
}

function save_text()
{
    saved_text = opener.parent.docRef.editform.text_data.value;
}

function restore_saved_text()
{
    opener.parent.docRef.editform.text_data.value = saved_text;
    set_undo_button_disabled(true);
}

function set_undo_button_disabled(state)
{
    var undo_button = document.getElementById('undo');
    undo_button.disabled = state;
}

function test_and_enable_nonregex()
{
    var str = '.^';
    var newstr = preg_quote(str);
    if (newstr == '\\.\\^')
    {
        var regex_checkbox = document.getElementById('is_regex');
        regex_checkbox.checked = false;
        regex_checkbox.disabled = false;
    }
}

function show_regex_help()
{
    var regex_help_para = document.getElementById('regex_help');
    regex_help_para.style.display = 'block';
    var arrow_span = document.getElementById('regex_arrow');
    arrow_span.innerHTML = '&#9660;';
}

function hide_regex_help()
{
    var regex_help_para = document.getElementById('regex_help');
    regex_help_para.style.display = 'none';
    var arrow_span = document.getElementById('regex_arrow');
    arrow_span.innerHTML = '&#9654;';
}

function toggle_regex_help()
{
    var regex_help_para = document.getElementById('regex_help');
    if (regex_help_para.style.display == 'none')
    {
        show_regex_help();
    }
    else
    {
        hide_regex_help();
    }
}

function preg_quote(str)
{
   escapees = new Array("\\",".","+","*","?","[","^","]","$","(",")","{","}","=","!","<",">","|");
   var i = 0;
   var subs = "";
   var repl = "";
   for (i=0;i<escapees.length; i++)
   {
       repl = new RegExp("\\" + escapees[i], "g")
       subs = "\\" + escapees[i];

       str = str.replace(repl, subs);
   }
   return(str);
}

window.onload = test_and_enable_nonregex;
</script>
<style type='text/css'>
#regex_help {display: none;}
#regex_help_title {color: blue; cursor: pointer; cursor: hand;}
</style>
<form>
<table id="tbl">
<tr><td align="right">
<? echo _("Search:"); ?>
</td><td>
<input type="text" name="search" id='search' />
</td></tr>
<tr><td align="right">
<? echo _("Replace:"); ?>
</td><td>
<input type="text" name="replace" id='replace' />
</td></tr>
<tr><td align="right">
<label for='is_regex'><? echo _("Regular Expression?"); ?></label>
</td><td>
<input type="checkbox" name="is_regex" id='is_regex' checked disabled />
</td></tr>
</table>
<center>
    <input type="button" value="<? echo _("Replace all."); ?>" onClick="do_replace()">
    <input type="button" id='undo' value="<? echo _("Undo."); ?>" onClick="restore_saved_text()" disabled />
</center>
</form>
<p><? echo _("Warning: Undo is only possible for the most recent replace!"); ?></p>
<p id='regex_help_title' onclick='toggle_regex_help();'><span id='regex_arrow'>&#9654;</span>
<?= _('Regular expression?')?></p>
<p id='regex_help'>
. &mdash; any character<br />
[a-z0-9] &mdash; lowercase letters and numbers<br />
a{4} &mdash; four lowercase As<br />
[Aa]{6} &mdash; six As of either case<br />
A{2,8} &mdash; between 2 and 8 capital As<br />
[hb]e &mdash; 'he' or 'be'<br />
</p>
</body>
</html>
