<html><head></head><body>
<?
$relPath='../../pinc/';
include($relPath.'v_site.inc');
$project = $_GET['project'];
$imagefile = $_GET['imagefile'];
printf ("<img src=\"$projects_url/%s/%s\" width=750 border=0>", $project, $imagefile);
?>
</body></html>
