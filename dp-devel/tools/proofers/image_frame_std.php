<?
$relPath="./../../pinc/";
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
src="../../projects/<?PHP echo $npage['project'].'/'.$npage['image'];?>" width="<?PHP
  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
    $iWidth=round((1000*$iWidth)/100);
  echo $iWidth;
?>"></div></center></body></html>