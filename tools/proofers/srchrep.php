<?php
$relPath = './../../pinc/';
include_once($relPath."base.inc");
include_once($relPath."slim_header.inc");
include_once($relPath."misc.inc");

require_login();

slim_header(_("Search/Replace"), [
    "js_files" => [
        "$code_url/tools/proofers/srchrep.js",
    ],
]);
?>
<form>
<table id="tbl">
<tr><td class="right-align">
<?php echo _("Search"); ?>:
</td><td>
<input type="text" name="search" id='search' autofocus>
</td></tr>
<tr><td class="right-align">
<?php echo _("Replace"); ?>:
</td><td>
<input type="text" name="replace" id='replace'>
</td></tr>
<tr><td class="right-align">
<label for='is_regex'><?php echo _("Regular Expression?"); ?></label>
</td><td>
<input type="checkbox" name="is_regex" id='is_regex'>
</td></tr>
</table>
<p class='center-align'>
    <input type="button" value="<?php echo attr_safe(_("Replace all.")); ?>" onClick="srchrep.doReplace()">
    <input type="button" id='undo' value="<?php echo attr_safe(_("Undo.")); ?>" onClick="srchrep.restoreSavedText()" disabled>
</center>
</form>
<p><?php echo _("Warning: Only the most recent replace operation may be reverted! Any changes made after the replace operation will be lost!"); ?></p>
<a href='<?php echo "$code_url/faq/prooffacehelp.php#srchrep"; ?>' target='helpNewWin'><?php echo _('Regular Expression Help'); ?></a>

<?php
