<?php
include("./../../pinc/v_site.inc");
include("./../../pinc/slim_header.inc");
slim_header(_("Search/Replace"));
?>
<form>
<table id="tbl">
<tr><td align="right">
<? echo _("Search:"); ?>
</td><td>
<input type="text" name="search">
</td></tr>
<tr><td align="right">
<? echo _("Replace:"); ?>
</td><td>
<input type="text" name="replace">
</td></tr>
</table>
<center><input type="button" value="<? echo _("Do it.");?>" onClick="opener.docRef.editform.text_data.value=opener.docRef.editform.text_data.value.replace(new RegExp(search.value,'g'),replace.value);"></center>
</form>
<p>Warning: Undo is not possible!</p>
</body>
</html>
