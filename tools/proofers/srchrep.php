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
    var replacetext = document.getElementById('replace').value.replace(new RegExp('\\\\n', 'g'), '\r\n');
    save_text();
    var is_regex = regex_checkbox.checked;
    if (!is_regex)
    {
        search = preg_quote(search);
    }
    opener.parent.docRef.editform.text_data.value=opener.parent.docRef.editform.text_data.value.replace(new RegExp(search,'gu'),replacetext);
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
<p><?php echo _("Warning: Only the most recent replace operation may be reverted!"); ?></p>
<a href='<?php echo "$code_url/faq/prooffacehelp.php#srchrep"; ?>' target='helpNewWin'><?php echo _('Regular Expression Help'); ?></a>

<?php
// vim: sw=4 ts=4 expandtab
