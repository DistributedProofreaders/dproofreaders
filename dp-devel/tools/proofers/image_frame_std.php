<?
// Send real HTTP headers to user-agents - at least one of these headers should
// be honored by all clients/proxies/caches.
//
// Date in the past
header("Expires: Mon, 01 Sep 2000 09:00:00 GMT");

// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");

$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include($relPath."doctype.inc");

// get cookie
$tpage=new processpage();
$npage=$tpage->getPageCookie();

?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<title>Image Frame</title>
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
</html>
