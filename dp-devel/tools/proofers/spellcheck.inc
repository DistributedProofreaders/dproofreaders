<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'doctype.inc');
include_once($relPath.'sp_check_user.inc');
$aspell_dict="en.multi";
$text_file= $project.substr($imagefile,0,-4).".txt";
$aspell_command="cat {$text_dir}{$text_file} | {$aspell_local} --prefix={$aspell_root} --master={$aspell_dict} --ignore-accents -H -a";
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
?>
<html> 
<head> 
<title>Spell Check</title> 
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
  overflow:auto;
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
  overflow:auto;
  text-align:center;
  z-index:6;
  }
#tbtext {
  border:1px solid #000000;
  text-align:left;
  overflow:auto;
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
// write new file
if ($fd=fopen($text_dir.$text_file,"w"))
{
  $text_data=str_replace(array("\r","\n\n","\n"),array("\n","[lf]","[lf]"),$text_data);
  $text_array= explode("[lf]",$text_data);
  fwrite($fd,"!\n");
  foreach($text_array as $key=>$value)
    {
      // adding carat
      fwrite($fd,"^$value\n");
    }
  fclose($fd);
  // run aspell
  $return=`$aspell_command`;
  $return=str_replace(array("\r","\n"),array('',"[lf]"),$return);
  $return_array= explode("[lf]",$return);
  $return_lines= count($return_array);
  $text_lines= count($text_array);

  //pretty return to show newline [nl] vs ok lines [ok]
  // discard 0 as it is just the identifier
  $return_index=1;
  $check_array=array();
  $check_index=0;
  $check_ok=1;

  while($return_index<$return_lines-2)
  {
    // if incorrect
    if(strcmp($return_array[$return_index]{0},'&')==0 || strcmp($return_array[$return_index]{0},'#')==0)
    {
      $check_array[$check_index]=$return_array[$return_index];
      $check_ok=0;
    }
    // if correct
    else 
    {
      if ($check_ok==1)
      {
        $check_array[$check_index]="[ok]";
      }
      else
      {
        $check_array[$check_index]="[nl]";
        $check_ok=1;
      }
    }
    $check_index++;
    $return_index++;
  } // end line up arrays


  // print correction text
  $text_index=0;
  $text_all=array('start'=>'','end'=>'');
  $string_start=0;
  $select_count=1;

  function escapeStringEntities($text)
  {
    $ent_array=array();
    for ($i=161;$i<256;$i++)
      {$ent_array[chr($i)]="&#{$i};";}
    $text=strtr($text,$ent_array);
    return $text;
  }

  function spellSuggest($text_word, $text_count, $text_offset, $text_suggest)
  {
    global $text_all,$text_index,$string_start,$select_count;

    $text_all['end'].=escapeStringEntities(substr($text_all['start'],$string_start,($text_offset-1)-$string_start));
    // escape all words
      $text_suggest=escapeStringEntities($text_suggest);
    // break word list
     $sp_list=explode(', ',$text_suggest);
     $text_word=escapeStringEntities($text_word);

    // create html
      // hidden values line|offset|word length
        $text_html="<input type=\"hidden\" name=\"posit{$select_count}\" value=\"".
          "{$text_index}|{$text_offset}|".strlen($text_word)."\">";
        $text_html.="<input type=\"hidden\" name=\"wd{$select_count}\" value=\"{$text_word}\">";
      // option list
        $text_html.="<select name=\"sp{$select_count}\" onChange=\"setSpell($select_count,this.value);\">".
          "<option value=\"{$text_word}\" selected>{$text_word}</option>\r\n";
        $text_html.= "<option value=\"sp1input\">-- Insert --</option>\r\n";
        foreach ($sp_list as $key=>$value)
          {$text_html.= "<option value=\"$value\">$value</option>\r\n";}
        $text_html.="</select>";
    $select_count++;

    // put html in end
      $text_all['end'].=$text_html;

    // advance $string_start
      $string_start=($text_offset-1)+strlen($text_word);
  } // end spellSuggest

  function spellNoSuggest($text_word, $text_offset)
  {
    global $text_all,$text_index,$string_start,$select_count;

    $text_all['end'].=escapeStringEntities(substr($text_all['start'],$string_start,($text_offset-1)-$string_start));
    $text_word=escapeStringEntities($text_word);
    // create html
      // hidden values line|offset|word length
        $text_html="<input type=\"hidden\" name=\"posit{$select_count}\" value=\"".
          "{$text_index}|{$text_offset}|".strlen($text_word)."\">";
        $text_html.="<input type=\"hidden\" name=\"wd{$select_count}\" value=\"{$text_word}\">";
      // option list
        $text_html.="<select name=\"sp{$select_count}\" onChange=\"alert(this.value);\">".
          "<option value=\"{$text_word}\" selected>{$text_word}</option>\r\n";
        $text_html.= "<option value=\"sp1input\">-- Insert --</option>\r\n";
        $text_html.="</select>";
    $select_count++;

    // put html in end
      $text_all['end'].=$text_html;

    // advance $string_start
      $string_start=($text_offset-1)+strlen($text_word);
  } // end  spellNoSuggest

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
src="../../projects/<?PHP echo $npage['project'].'/'.$npage['image'];?>" 
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
      {echo "<td id=\"tdbottom\"><a href=\"../../projects/$projectname/$imagefile\" target=\"imageshowwin\">View Image</a></td></tr><tr>";}
?><td 
  id="tdtext" 
  valign="top">
<?PHP
  // print basic form html
    echo "<pre>".
      "<input type=\"hidden\" name=\"text_data\" value=\"".htmlentities($text_data)."\">";
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

  // print text with html
    foreach($check_array as $key=>$value)
    {
      if (strcmp($value,"[ok]")==0)
      {
        // print text as is
          echo escapeStringEntities($text_array[$text_index])."<BR>\r\n";
          $text_index++;
      }
      else if (strcmp($value,"[nl]")==0)
      {
        // new line
          $text_all['end'].=substr($text_all['start'],$string_start);
          echo $text_all['end']."<BR>\r\n";
          $string_start=0;
          $text_all['start']='';
          $text_all['end']='';
          $text_index++;
      }
      else if (strcmp($value{0},'#')==0)
      {
        // format==# original offset
          $inst_array=explode(' ',$value);
          $sp_original=$inst_array[1];
          $sp_offset=$inst_array[2];
          spellNoSuggest($sp_original, $sp_offset);
      }
      else if (strcmp($value{0},'&')==0)
      {
        // format==& original count offset: word, word, ....
          $sug_array=explode(":",$value);
          $inst_array=explode(' ',$sug_array[0]);
        // instructions
          $sp_original=$inst_array[1];
          $sp_count=$inst_array[2];
          $sp_offset=$inst_array[3];
          $sp_suggest=trim($sug_array[1]);
          $text_all['start']=$text_array[$text_index];
        spellSuggest($sp_original, $sp_count, $sp_offset, $sp_suggest);
      }
      else
      {
        echo "Error Processing Aspell return data<BR>\r\n";
      }
    } // end display text for spelling

  // basic end form html
    echo "</pre></td></tr><tr><td  id=\"tdtop\">";
    echo "<input type=\"hidden\" name=\"sptotal\" value=\"".($select_count-1)."\">".
      "<input type=\"submit\" name=\"spcorrect\" value=\"Submit Corrections\">&nbsp;&nbsp;&nbsp;".
      "<input type=\"submit\" name=\"spexit\" value=\"Quit\">".
      "</td></tr></table></form></div>";
} //end file open check
?>
</body></html>