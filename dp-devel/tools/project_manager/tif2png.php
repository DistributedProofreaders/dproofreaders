<?php 

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
<html><head><title>Distributed Proofreaders: Tiff to Png Conversion</title></head>
<body>
<h3> Looking for TIFFs and PNGs in <?php echo ($fsrc); ?><br>
Please Wait </h3><hr>

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
				echo ("$c. Found TIFF file: $afile, Converting to PNG.");
				flush();
				`convert $archfile $destfile`;
				chmod ($destfile,0664);	//convert insits on messing ownership
				echo (" Done.<br>\n");
				flush();
			}
			else {
				$p++;
				echo ("$c. Found PNG file: $afile, Just Copying.<BR>\n");
				flush();
				copy ($archfile,$destfile);
			}
		}
		else {
			echo ("$c. Found non-TIFF/PNG file: $afile, Ignoring.<BR>\n");
			flush();
		}
	}

}
closedir ($dir);
echo ("<HR> Found $c files in $fsrc.<br>");
echo ("$t TIFFs converted and moved to $farch.<br>");
echo ("$p PNGs moved to $farch.<BR>");
echo ($c-($p+$t)." files ignored and left is $fsrc.<br>"); 
echo ($p+$t." Files renamed and put in $fdest.<br>");
echo ('<HR><A href="'.$_SERVER['HTTP_REFERER'].'">Back to Project Page</a>');
?>
</body>
</html>
