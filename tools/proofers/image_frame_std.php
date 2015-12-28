<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'http_headers.inc');
include_once('PPage.inc');

require_login();

$ppage = get_requested_PPage($_GET);

include_once($relPath.'slim_header.inc');
slim_header("Image Frame",TRUE,FALSE);
?>
</head><body bgcolor="#CDC0B0"><center><div align="center" id="imagedisplay"><img
name="scanimage" id="scanimage" title="" alt=""
src="<?php echo $ppage->url_for_image(); ?>"
width="<?php
  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
    $iWidth=round((1000*$iWidth)/100);
  echo $iWidth;
?>"></div></center>
</body>

<?php
// vim: sw=4 ts=4 expandtab
