<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include($relPath."doctype.inc");

// get cookie
$tpage=new processpage();
$npage=$tpage->getPageCookie();

?>
<html><head><title>Image Frame</title>
</head><body bgcolor="#CDC0B0"><center><div align="center" id="imagedisplay"><img
name="scanimage" id="scanimage" title="" alt=""
src="<?PHP echo $projects_url.'/'.$npage['project'].'/'.$npage['image'];?>"
width="<?PHP
  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
    $iWidth=round((1000*$iWidth)/100);
  echo $iWidth;
?>"></div></center>

<?
//Preload the next 4 pages
//Load the page that is next.  Then load the page that is 2 pages from the one we are doing
//Then we load the 4th page & 6th page after what we are currently proofing.
//This is to help with the possibility that not just one person is proofing the project
echo "<img alt='Preload Image' width='1' height='1' src='$projects_url/".$npage['project']."/".str_pad(($npage['image']+1),3,0,STR_PAD_LEFT).".png'>\n";
echo "<img alt='Preload Image' width='1' height='1' src='$projects_url/".$npage['project']."/".str_pad(($npage['image']+2),3,0,STR_PAD_LEFT).".png'>\n";
echo "<img alt='Preload Image' width='1' height='1' src='$projects_url/".$npage['project']."/".str_pad(($npage['image']+4),3,0,STR_PAD_LEFT).".png'>\n";
echo "<img alt='Preload Image' width='1' height='1' src='$projects_url/".$npage['project']."/".str_pad(($npage['image']+6),3,0,STR_PAD_LEFT).".png'>\n";
?>

</body></html>
