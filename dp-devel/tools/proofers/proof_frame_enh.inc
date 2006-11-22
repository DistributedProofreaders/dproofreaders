<?PHP
include_once($relPath.'http_headers.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'slim_header.inc');
include_once('PPage.inc');
include_once('image_block_enh.inc');

// $ppage is assumed to be set before this file is included.


// set image and text height and width
  if ($userP['i_layout']=='1')
      {
        $textWidth=$userP['v_tframe'];
        $imageWidth=(100-$userP['v_tframe'])-1;
        $textHeight=99;
        $imageHeight=99;
        $textTop="0px";
        $textLeft=(100-$userP['v_tframe'])."%";
      }
  else
      {
        $textWidth=99;
        $imageWidth=99;
        $textHeight=$userP['h_tframe'];
        $imageHeight=(100-$userP['h_tframe'])-1;
        $textTop=(100-$userP['h_tframe'])."%";
        $textLeft="1%";
      }

slim_header("Proofreading Page",TRUE,FALSE);
?>
<script language="JavaScript" type="text/javascript">
<!--
function ldAll()
{top.initializeStuff(1);}
function scrollImage(sDir)
{top.scrollImage(sDir);}
function scrollOver(sDir)
{top.scrollOver(sDir);}
function stopOver()
{top.stopOver();}
function getCurSel()
{top.getCurSel();}
function getCurCaret()
{top.getCurCaret();}
function showIZ()
{top.showIZ();
return false;}
function showActual()
{ return top.showActual(); }
// -->
</script>
<style type="text/css">
<!--
body {
  font-family: verdana, arial, helvetica, sans-serif;
  font-size: 12px;
  color:#000000;
  background-color:#CDCDC1;
  text-align:center;
  overflow:hidden;
  }
<?
  ibe_echo_styles( $imageWidth, $imageHeight );
?>
#controlframe {
  <?PHP
/*    if ($userP['i_layout']=='1')
      {
        echo " position:absolute;\r\n";
        echo "left:".$textLeft.";\r\n";
        echo "top:".$textTop.";\r\n";
        echo "width:".$textWidth."%;\r\n";
      }
    else {echo "position:relative;\r\n";}
*/
    echo " position:absolute;\r\n";
    echo "left:".$textLeft.";\r\n";
    echo "top:".$textTop.";\r\n";
    echo "width:".$textWidth."%;\r\n";
    echo "height:".$textHeight."%;\r\n";
  ?>
  clip:rect(0px, 100%, 100%, 0px);
  background-color:#CDCDC1;
  overflow:auto;
  z-index:6;
  text-align:center;
  }
#tbtext {
  border:1px solid #000000;
  text-align:center;
  overflow:auto;
  }
#tdtop {
  border:1px solid #000000;
  background-color:#CDC0B0;
  padding:2px;
  }
#tdtext {
  border:1px solid #000000;
  background-color:#CDCDC1;
  padding:2px;
  }
#tdbottom {
  border:1px solid #000000;
  background-color:#EEDFCC;
  padding:2px;
  }
#text_data {
  padding:2px;
  background-color:#FFF8DC;
  color:#000000;
  }
.dropsmall {
  font-size: 75%;
  background-color:#FFF8DC;
  }
.dropnormal {
  background-color:#FFF8DC;
  }
.boxnormal {
  background-color:#FFF8DC;
  }
-->
</style>
</head><body
  text="#000000"
  topmargin="0"
  onload="ldAll()">
<?

  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
  $iWidth=round((1000*$iWidth)/100);

  ibe_echo_block( $ppage->url_for_image(), $iWidth );

?>
<div
id="controlframe" align="center"><form
      name="editform" id="editform"
      method="POST"
      action="processtext.php"><?PHP

$ppage->echo_hidden_fields();

?><table
  id="tbtext"
  cellspacing="0"
  align="center"><tr><td
    id="tdtop"
    valign="middle"><?PHP
  include('button_menu.inc');
?></td></tr><tr><td
  id="tdtext"
  valign="top"><?PHP

  $ppage->echo_proofing_textarea( TRUE );

?></td></tr><tr><td
  id="tdbottom"
  valign="top"><?PHP

$ppage->echo_info();

?>
  </td>
  </tr>
  </table>
  </form>
  </div>
</body>
</html>
