<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'doctype.inc');
include_once($relPath.'c_pages.inc');
include_once($relPath.'prefs_options.inc');
$tpage=new processpage();
$lang=isset($lang)?$lang:1;

// get cookie
$npage=$tpage->getPageCookie();

// set page
$tpage->setPageState($npage['pagestate'],$npage['project'],$npage['fileid'],$npage['image'],$npage['proofstate']);

// get page text
$npage['text']=isset($inCheck)?$correct_text:$tpage->getText($npage['pagestate'],$npage['saved'],$npage['revert']);

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

echo $docType."\r\n";
?>
<html><head><title>Proofing Page</title>
<script language="JavaScript" type="text/javascript">
<!--
function ldAll()
{top.menuframe.ldAll(1);}
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
{top.menuframe.showIZ();
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
  overflow:hidden;
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
  height:25px:
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
  overflow:auto;
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
  onload="ldAll()"><div
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
id="controlframe" align="center"><form
      name="editform" id="editform"
      method="POST"
      action="processtext.php"><?PHP
// set values for hidden form fields
  $sHidFS="<input \r\ntype=\"hidden\" value=\"";
  $sHidFM="\" name=\"";
  $sHidFM2="\" id=\"";
  $sHidFE="\">";
  echo $sHidFS.$npage['image'].$sHidFM.'imagefile'.$sHidFM2.'imagefile'.$sHidFE;
  echo $sHidFS.$npage['fileid'].$sHidFM.'fileid'.$sHidFM2.'fileid'.$sHidFE;
  echo $sHidFS.$npage['proofstate'].$sHidFM.'proofstate'.$sHidFM2.'proofstate'.$sHidFE;
  echo $sHidFS.$npage['pagestate'].$sHidFM.'pagestate'.$sHidFM2.'pagestate'.$sHidFE;
  echo $sHidFS.$npage['project'].$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
  echo $sHidFS.$lang.$sHidFM.'lang'.$sHidFM2.'lang'.$sHidFE;
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
  // settings for horizontal or vertical interface
  if ($userP['i_layout']==1)
  {
    $tcols = $userP['v_tchars'];
    $trows = $userP['v_tlines'];
    $twrap = $userP['v_twrap'];
    $tfonti = $userP['v_fntf'];
    $tfont = $f_f[$tfonti];
  }
  else 
  {
    $tcols = $userP['h_tchars'];
    $trows = $userP['h_tlines'];
    $twrap = $userP['h_twrap'];
    $tfonti = $userP['h_fntf'];
    $tfont = $f_f[$tfonti];
  }
  // General settings
  $twrap=$twrap==1? '': " \r\nwrap=\"off\"";
  // display text area
  echo "<textarea \r\nname=\"text_data\" id=\"text_data\" \r\ncols=\"$tcols\" \r\nrows=\"$trows\" \r\nonselect=\"getCurSel()\" \r\nonclick=\"getCurCaret()\" \r\nonkeyup=\"getCurCaret()\" $twrap";
  if ($tfont != '') 
  { 
    echo ' style="font-family: ' . $tfont . '"'; 
  }
  echo ">";
  // SENDING PAGE-TEXT TO USER
  // We're sending it in an HTML document, so encode special characters.
  echo htmlspecialchars($npage['text'],ENT_NOQUOTES);
?></textarea></td></tr><tr><td
  id="tdbottom"
  valign="top"><?PHP
//display page number
  $pageNum=substr($npage['image'],0,-4);
  echo "<font size=\"-1\"><B>Page Number: $pageNum";
    if ($tpage->getProjectRound($npage['proofstate']) == 2)
    {
      $result = mysql_query("SELECT round1_user FROM {$npage['project']} WHERE image = '{$npage['image']}' AND fileid='{$npage['fileid']}'");
      $username = mysql_result($result, 0, "round1_user");
      $result = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
      $user_id = mysql_result($result, 0, "user_id");
      echo " - Proofed by: <a href=\"$forums_url/privmsg.php?mode=post&u=".$user_id."\" target=\"comments\">".$username."</a>";
    }
  echo "</b></font>";
?>
  </td>
  </tr>
  </table>
  </form>
  </div>
</body></html>
