<html><head></head><body>
<?
$project = $_GET['project'];
$imagefile = $_GET['imagefile'];
printf ("<img src=\"../../projects/%s/%s\" width=750 border=0>", $project, $imagefile);
?>
</body></html>
