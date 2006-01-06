<?php
include("./../../pinc/v_site.inc");
include("./../../pinc/slim_header.inc");
slim_header(_("Search/Replace"));
?>

<script type='text/javascript'>
var saved_text = '';

function do_replace()
{
    var search = document.getElementById('search');
    var replacetext = document.getElementById('replace');
    save_text();
    var is_regex = true;
    if (is_regex)
	{
	    opener.parent.docRef.editform.text_data.value=opener.parent.docRef.editform.text_data.value.replace(new RegExp(search.value,'g'),replacetext.value);
    }
}

function save_text()
{
    saved_text = opener.parent.docRef.editform.text_data.value;
}

function restore_saved_text()
{
    opener.parent.docRef.editform.text_data.value = saved_text;
}
</script>

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
</table>
<center>
    <input type="button" value="<? echo _("Replace all."); ?>" onClick="do_replace()">
	<input type="button" value="<? echo _("Undo."); ?>" onClick="restore_saved_text()">
	</center>
</form>
<p><? echo _("Warning: Undo is only possible for the most recent replace!"); ?></p>
</body>
</html>
