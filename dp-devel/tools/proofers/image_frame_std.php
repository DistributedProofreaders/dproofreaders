<?
$relPath="./../../pinc/";
include_once($relPath.'http_headers.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include_once('page_misc.inc');

// get cookie
$npage = getPageCookie();

include_once($relPath.'slim_header.inc');
slim_header("Image Frame",TRUE,FALSE);
?>
</head><body bgcolor="#CDC0B0"><center><div align="center" id="imagedisplay"><img
name="scanimage" id="scanimage" title="" alt=""
src="<?PHP echo page_image_url($npage); ?>"
width="<?PHP
  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
    $iWidth=round((1000*$iWidth)/100);
  echo $iWidth;
?>"></div></center>

</body>
</html>
