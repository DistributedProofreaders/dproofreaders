<?php 
$relPath = './../pinc/';
include_once($relPath.'base.inc');

// Change these according to site

//$src='$uploads_dir';
//$archive='/0/home/ftp/pub/gutenberg-images/dproofreaders';
//$dest='../../project';

$src='/tmp/src';
$archive='/tmp/arch';
$dest='/tmp/dest';


// This script takes only the project ID 

$projectid=$_GET['project'];

if (!$projectid) {
	header("Location: ".dirname($_SERVER['PHP_SELF']));
	exit;
}

$fsrc = $src.'/projectID'.$projectid;
$farch = $archive.'/projectID'.$projectid ;
$fdest = $dest.'/projectID'.$projectid;
$c=0;$t=0;$p=0;

?>
<html><head><title><?php echo _("Distributed Proofreaders: Tiff to Png Conversion"); ?></title></head>
<body>
<h3> <?php echo sprintf( _("Looking for TIFFs and PNGs in %1\$s."), $fsrc); ?><br>
<?php echo _("Please Wait"); ?> </h3><hr>

<?php
print str_repeat(" ", 300) . "\n"; //this should force IE to flush :-0
flush();
if (is_dir($fsrc)) {
	if (!is_dir($farch)) { mkdir ($farch, 0777); } // need to make sure this is the right mask
	if (!is_dir($fdest)) { mkdir ($fdest, 0777); } // these should keep php defaults
	$dir = opendir ($fsrc);
	umask (0113);
	while (false !== ($afile = readdir($dir))) {
		if ($afile=="." or $afile=="..") {continue;} //skip the dots . . . . . 
		$c++;
		$srcfile="$fsrc/$afile";
		if ((preg_match ("/^.+\.(tiff?)|(png)/i",$afile)) and
		    (preg_match ("/^[^ ]* ([TIFPNG]{3,4}).*$/",`identify $srcfile`,$res))){
			$archfile="$farch/$afile";
			$destfile="$fdest/".sprintf("%03d",$p+$t+1).".png";
			rename ($srcfile,$archfile);
			if ($res[1] == "TIFF") {
				$t++;
				echo sprintf( _("%1\$d. Found TIFF file: %2\$s, Converting to PNG."), $c, $afile);
				flush();
				`convert $archfile $destfile`;
				chmod ($destfile,0664);	//convert insits on messing ownership
				echo " " . _("Done.") . "<br>\n";
				flush();
			}
			else {
				$p++;
				echo sprintf( _("%1\$d. Found TIFF file: %2\$s, Just Copying."), $c, $afile) . "<br>\n";
				flush();
				copy ($archfile,$destfile);
			}
		}
		else {
			echo sprintf( _("%1\$d. Found non-TIFF/PNG file: %2\$s, Ignoring."), $c, $afile) . "<br>\n";
			flush();
		}
	}

}
closedir ($dir);
echo "<hr> " . sprintf( _("Found %1\$d files in %2\$s."), $c, $fsrc) . "<br>";
echo sprintf( _("%1\$d TIFFs converted and moved to %2\$s."), $t, $farch) . "<br>";
echo sprintf( _("%1\$d PNGs moved to %2\$s."), $p, $farch) . "<br>";
echo sprintf( _("%1\$d files ignored and left in %2\$s."), $c-($p+$t), $fsrc) . "<br>";
echo sprintf( _("%1\$d files renamed and put in %2\$s."), $p+$t, $fdest) . "<br>";
echo "<hr><a href='" . $_SERVER['HTTP_REFERER'] . "'>" . _("Back to Project Page") . "</a>";
?>
</body>
</html>
