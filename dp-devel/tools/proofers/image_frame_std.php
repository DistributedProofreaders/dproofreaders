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
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
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

</body>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
</head>
</html>
