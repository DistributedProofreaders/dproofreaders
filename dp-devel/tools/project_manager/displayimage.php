<html><head></head><body>
<?
$relPath='../../pinc/';
include($relPath.'v_site.inc');
$project = $_GET['project'];
$imagefile = $_GET['imagefile'];

if (!empty($_GET['percent'])) {
    $width = 10 * $_GET['percent'];
} else $width = 1000;

printf ("<img src=\"$projects_url/%s/%s\" width=$width border=0>", $project, $imagefile);
?>
<form method=get action="displayimage.php">
<input type=hidden name="project" value="<?echo $project;?>">
<input type=hidden name="imagefile" value="<?echo $imagefile;?>">
<input type=text maxlength=3 name="percent"> % <input type="submit" value="Resize">
</form>
</body></html>
