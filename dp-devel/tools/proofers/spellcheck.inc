<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'slim_header.inc');
include_once('spellcheck_text.inc');

$text_data= isset($_POST['text_data'])? stripslashes($_POST['text_data']):'';

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
        $textTop=100-$userP['h_tframe']."%";
        $textLeft="1%";
      }
echo $docType."\r\n";
slim_header("Spell Check",TRUE,FALSE);
?>
<SCRIPT LANGUAGE="JavaScript" SRC="dpspell.js" TYPE="text/javascript"></SCRIPT>
<script language="JavaScript" type="text/javascript">
<!--
function ldAll()
{top.menuframe.ldAll(<?PHP
  if ($userP['i_type']==1)
    {echo "2";}
  else
    {echo "3";}
?>);}
function scrollImage(sDir)
{top.menuframe.scrollImage(sDir);}
function scrollOver(sDir)
{top.menuframe.scrollOver(sDir);}
function stopOver()
{top.menuframe.stopOver();}
function getCurSel()
{top.menuframe.getCurSel();}
function getCurCaret()
{top.menuframe.getCurCaret();}
function showIZ()
{top.menuframe.showIZ();alert('done it');
return false;}
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
#imagehorz {
  position:absolute;
  left:25px;
  top:0px;
  <?PHP
    echo "width:".($imageWidth-3)."%;\r\n";
  ?>
  height:25px;
  z-index:3;
  }
#imagevert {
  position:absolute;
  left:0px;
  top:25px;
  width:25px;
  <?PHP
    echo "height:".($imageHeight-3)."%;\r\n";
  ?>
  z-index:4;
  }
#imageframe {
  <?PHP
/*    if ($userP['i_layout']=='1')
      {
        echo "position:absolute;\r\n".
          "top:0px;\r\n".
          "left:0px;\r\n";
        echo "width:".$imageWidth."%;\r\n";
      }
    else {echo "position:relative;\r\n";}
*/
    echo "position:absolute;\r\n".
      "top:25px;\r\n".
      "left:25px;\r\n";
    echo "width:".($imageWidth-3)."%;\r\n";
    echo "height:".($imageHeight-3)."%;\r\n";
  ?>
  clip:rect(0px, 100%, 100%, 0px);
  z-index:2;
  text-align:center;
  }
#imagedisplay {
  position:absolute;
  left:0px;
  top:0px;
  z-index:1;
  background-color:#EEDFCC;
  }
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
  text-align:center;
  z-index:6;
  }
#allframe { 
  position:absolute;
  left:0;
  top:0;
  width:100%;
  height=100%;
  background-color:#CDCDC1;
  text-align:center;
  z-index:6;
  }
#tbtext {
  border:1px solid #000000;
  text-align:left;
  } 
#tdtop {
  border:1px solid #000000;
  background-color:#CDC0B0;
  text-align:center;
  padding:2px;
  }
#tdtext {
  border:1px solid #000000;
  background-color:#FFF8DC;
  padding:2px;
  }
#tdbottom {
  border:1px solid #000000;
  background-color:#EEDFCC;
  text-align:center;
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

<?PHP
  // print basic image html
    if ($userP['i_type']==1)
      {
?>
<div 
id="imagehorz"><table 
  id="tbhorz" 
  width="100%"><tr><td 
    align="left"><a 
    href="JavaScript:scrollImage('left')"><img 
      src="gfx/a1_left.png" width="11" height="11" alt="Move Left" title="Move Left" border="0"></a>&nbsp;&nbsp;&nbsp;<a 
    href="JavaScript: //" onmouseover="scrollOver('left')" onmouseout="stopOver()"><img 
      src="gfx/a2_left.png" width="11" height="11" alt="Scroll Left" title="Scroll Left" border="0"></a></td><td 
      align="right"><a 
      href="JavaScript: //" onmouseover="scrollOver('right')" onmouseout="stopOver()"><img 
        src="gfx/a2_right.png" width="11" height="11" alt="Scroll Right" title="Scroll Right" border="0"></a>&nbsp;&nbsp;&nbsp;<a 
     href="JavaScript:scrollImage('right')"><img 
       src="gfx/a1_right.png" width="11" height="11" alt="Move Right" title="Move Right" border="0"></a></td></tr></table></div><div 
id="imagevert"><table 
  id="tbvert" 
  height="95%"><tr><td 
    valign="top"><a 
    href="JavaScript:scrollImage('up')"><img 
      src="gfx/a1_up.png" width="11" height="11" alt="Move Up" title="Move Up" border="0"></a><p><a 
    href="JavaScript: //" onmouseover="scrollOver('up')" onmouseout="stopOver()"><img 
      src="gfx/a2_up.png" width="11" height="11" alt="Scroll Up" title="Scroll Up" border="0"></a></p></td></tr><tr><td 
      valign="bottom"><a 
      href="JavaScript: //" onmouseover="scrollOver('down')" onmouseout="stopOver()"><img 
        src="gfx/a2_down.png" width="11" height="11" alt="Scroll Down" title="Scroll Down" border="0"></a><p><a 
    href="JavaScript:scrollImage('down')"><img 
      src="gfx/a1_down.png" width="11" height="11" alt="Move Down" title="Move Down" border="0"></a></p></td></tr></table></div><div 
id="imageframe"><div 
id="imagedisplay"><a href="JavaScript: //"><img 
name="scanimage" id="scanimage" title="" alt="" 
src="<?PHP echo $projects_url.'/'.$npage['project'].'/'.$npage['image'];?>" 
border="0" width="<?PHP 
  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
  $iWidth=round((1000*$iWidth)/100);
  echo $iWidth;
?>"></a></div></div><div 
id="controlframe"><?PHP 
      }
    else
      {
       echo "<div \r\nid=\"allframe\">";
      }
?><form 
name="spcorrects" action="processtext.php" method="POST"><table 
  id="tbtext" 
  cellpadding="10" 
  align="center"><tr><?PHP
    if ($userP['i_type'] !=1)
      {echo "<td id=\"tdbottom\"><a href=\"$projects_url/$projectname/$imagefile\" target=\"imageshowwin\">View Image</a></td></tr><tr>";}
?><td 
  id="tdtext" 
  valign="top">
<?PHP
    // set values for hidden form fields
      $sHidFS="<INPUT \nTYPE=\"hidden\" VALUE=\"";
      $sHidFM="\" NAME=\"";
      $sHidFM2="\" ID=\"";
      $sHidFE="\">";
      echo $sHidFS.$imagefile.$sHidFM.'imagefile'.$sHidFM2.'imagefile'.$sHidFE;
      echo $sHidFS.$fileid.$sHidFM.'fileid'.$sHidFM2.'fileid'.$sHidFE;
      echo $sHidFS.$proofstate.$sHidFM.'proofstate'.$sHidFM2.'proofstate'.$sHidFE;
      echo $sHidFS.$pagestate.$sHidFM.'pagestate'.$sHidFM2.'pagestate'.$sHidFE;
      echo $sHidFS.$project.$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
      echo $sHidFS.$lang.$sHidFM.'lang'.$sHidFM2.'lang'.$sHidFE;
      if (isset($editone)) { echo $sHidFS.$editone.$sHidFM.'editone'.$sHidFM2.'editone'.$sHidFE;}
      if (isset($saved)) { echo $sHidFS.$saved.$sHidFM.'saved'.$sHidFM2.'saved'.$sHidFE;}

    spellcheck_text( $text_data, $project, $imagefile );
?>
</td>
</tr>
<tr>
    <td id="tdtop">
        <input
            type="submit"
            name="spcorrect"
            value="Submit Corrections"
            title="Keep spelling corrections and return to proofreading this page"
        >
        &nbsp;&nbsp;&nbsp;
        <input
            type="submit"
            name="spexit"
            value="Quit Spell Check"
            title="Abandon spelling corrections and return to proofreading this page"
        >
    </td>
</tr>
</table>
</form>
</div>
</body>
</html>
