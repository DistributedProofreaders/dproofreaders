<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<?
$relPath="./../../../pinc/";
include_once($relPath.'slim_header.inc');

slim_header("",FALSE,FALSE);
?>
</head>
<frameset rows="*,*">
<frame name="hierodisplay" src="display.php">
<frame name="hierotable" src="table.php?table=b">
</frameset>
<noframes>
<? echo _("Your browser currently does not display frames!"); ?>
</noframes>
</body>
</html>
