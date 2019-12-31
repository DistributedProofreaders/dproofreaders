<?php
$relPath="./../../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');

require_login();

slim_header_frameset(_("Hieroglyphs"));
?>
<frameset rows="*,*">
<frame name="hierodisplay" src="display.php">
<frame name="hierotable" src="table.php?table=b">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
