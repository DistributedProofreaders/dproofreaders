<?php
$relPath = './../../pinc/';
include_once($relPath."base.inc");
include_once($relPath."slim_header.inc");
include_once($relPath."misc.inc");

require_login();

slim_header(_("Search/Replace"));
?>

<script>
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
<tr><td class="right-align">
<?php echo _("Search"); ?>:
</td><td>
<input type="text" name="search" id='search'>
</td></tr>
<tr><td class="right-align">
<?php echo _("Replace"); ?>:
</td><td>
<input type="text" name="replace" id='replace'>
</td></tr>
<tr><td class="right-align">
<label for='is_regex'><?php echo _("Regular Expression?"); ?></label>
</td><td>
<input type="checkbox" name="is_regex" id='is_regex' checked disabled>
</td></tr>
</table>
<p class='center-align'>
    <input type="button" value="<?php echo attr_safe(_("Replace all.")); ?>" onClick="do_replace()">
    <input type="button" id='undo' value="<?php echo attr_safe(_("Undo.")); ?>" onClick="restore_saved_text()" disabled>
</center>
</form>
<p><?php echo _("Warning: Undo is only possible for the most recent replace!"); ?></p>
<p id='regex_help_title' onclick='toggle_regex_help();'><span id='regex_arrow'>&#9654;</span>
<?php echo _('Regular Expression?'); ?></p>
<p id='regex_help'><?php
// TRANSLATORS: Description of the . character in a regualar expression
echo ". &mdash; " . _("any character") . "<br>\n";
// TRANSLATORS: Description of the [a-z0-9] class in a regular expression
echo "[a-z0-9] &mdash; " . _("lowercase letters and numbers") . "<br>\n";
// TRANSLATORS: Description of a{4} in a regular expression for an example
echo "a{4} &mdash; " . _("four lowercase As") . "<br>\n";
// TRANSLATORS: Description of [Aa]{6} in a regular expression for an example
echo "[Aa]{6} &mdash; " . _("six As of either case") . "<br>\n";
// TRANSLATORS: Description of A{2,8} in a regular expression for an example
echo "A{2,8} &mdash; " . _("between 2 and 8 capital As") . "<br>\n";
// TRANSLATORS: Description of [hb]e in a regular expression for an example
echo "[hb]e &mdash; " . _("'he' or 'be'") . "<br>\n";
?></p>

<?php
// vim: sw=4 ts=4 expandtab
