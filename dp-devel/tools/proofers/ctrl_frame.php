<?php
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'doctype.inc');
echo $docType."\r\n";
?>
<html><head><title>Control Frame</title>
<script language="JavaScript" src="dp_scroll.js" type="text/javascript"></script>
<script language="JavaScript" src="dp_proof.js" type="text/javascript"></script>
<style type="text/css">
<!--
body {
  font-family: verdana, arial, helvetica, sans-serif;
  font-size: 12px;
  color:#000000;
  background-color:#CDC0B0;
  text-align:center;
  }
A:link {
  color:#000000;
  text-decoration : none; 
  }
A:visited {
  color:#000000;
  text-decoration : none; 
  }
A:hover {
  color:#003300;
  font-weight: bold;
  text-decoration : none; 
  }
A:active {
  color:#000033;
  font-weight: bold;
  text-decoration : none; 
  }
.dropsmall {
  font-size: 75%;
  background-color:#FFF8DC;
  }
.dropnormal {
  background-color:#FFF8DC;
  }
-->
</style></head><body><table 
align="right" width="100%"><tr><td valign="top"><form 
name="tagform" id="tagform" onsubmit="return(false);"><?PHP
include('ptags.inc'); ?><br><a 
href="#" onclick="mNA()" title="Extended Latin-1 Character Set">Latin-1</a> | <a 
href="#" onclick="mGR()" title="Greek-to-ASCII Transliteration">Greek</a>
</form></td><td align="right" valign="top"><form 
name="xform" id="xform" method="POST" action="processtext.php" target="_top"><a 
href="../../faq/prooffacehelp.html" accesskey="1" target="helpNewWin"><img 
src="gfx/bt11.png" width="26" height="26" border="0" align="top" alt="Help" title="Help"></a>&nbsp;<a 
href="<?PHP
  if($userP['i_newwin']==0)
    {echo "proof_per.php";}
  else
    {echo "JavaScript:window.close();";}
?>" target="_top" onclick="return(confirm('Are you sure you want to \r\n\r\nQuit?'));"><img 
src="gfx/bt1_n.png" width="26" height="26" border="0" align="top" alt="Quit" title="Quit"></a></form></td></tr></table></body></html>