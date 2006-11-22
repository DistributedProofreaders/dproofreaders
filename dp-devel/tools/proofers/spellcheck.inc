<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'slim_header.inc');
include_once('spellcheck_text.inc');
include_once('PPage.inc');
include_once('image_block_enh.inc');

// text_data should be set before we get here -- should take place in processtext.php
#$text_data= isset($_POST['text_data'])? stripslashes($_POST['text_data']): $text_data;

$revert_text=isset($_POST['revert_text']) ? stripslashes($_POST['revert_text']) : $text_data;


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

slim_header("Spell Check",TRUE,FALSE);
?>
<SCRIPT LANGUAGE="JavaScript" SRC="dpspell.js" TYPE="text/javascript"></SCRIPT>
<script language="JavaScript" type="text/javascript">
<!--
function ldAll()
{top.initializeStuff(<?PHP
  if ($userP['i_type']==1)
    {echo "2";}
  else
    {echo "3";}
?>);}
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
{top.showIZ();alert('done it');
return false;}

// function to accept specified words in the spellcheck
// it works by finding (span) elements with IDs in the format
// word_# and when found sets the content of the span
// to be just the word thereby removing the select and button
function acceptWord(word,wordOrig) {
    var wordIndex=1;
    var wordSpan=1;
    while(wordSpan) {
        // find occurances of the word
        var wordID = word + "_" + wordIndex;
        wordSpan = document.getElementById(wordID);
        if(wordSpan) {
            // check to see if this instance has been edited already
            // by checking the status of the AW button
            var button = document.getElementById("button_" + wordID);
            if(button && button.disabled == false) {
                // set contents to be the word itself
                wordSpan.innerHTML=wordOrig;
            }

            // save the word as the accept_words
            var acceptWordInput = document.getElementById("accept_words");
            acceptWordInput.value = acceptWordInput.value + " " + word;
            wordIndex ++;
        }
        // protection against run-away processes
        if(wordIndex>=1000) { break; }
    }
}

function disableAcceptWord(wordID) {
    var button = document.getElementById("button_" + wordID);
    if(button) {
        button.disabled = true;
    }
}
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
  text-align:center;
  z-index:6;
  overflow:auto;
  }
// This declaration is what breaks spellcheck display for Opera users
// Commenting it corrects the issue for the standard interface only.
// Enhanced interface still experiences the same issue, and will require
// more significant refactoring to address. (donovan)
// #allframe { 
//  position:absolute;
//  left:0;
//  top:0;
//  width:100%;
//  height=100%;
//  background-color:#CDCDC1;
//  text-align:center;
//  z-index:6;
//  overflow:auto;
//  }
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
<?

  if ($userP['i_layout']==1)
    {$iWidth=$userP['v_zoom'];}
  else {$iWidth=$userP['h_zoom'];}
  $iWidth=round((1000*$iWidth)/100);

  ibe_echo_block( $ppage->url_for_image(), $iWidth );

?>
<div 
id="controlframe"><?PHP 
      }
    else
      {
       echo "<div \r\nid=\"allframe\">";
      }
?>
<form name="spcorrects" action="processtext.php" method="POST">
<?PHP
    echo "<input type='hidden' name='revert_text' value='" . html_safe($revert_text) . "'>\n";
    echo "<input type='hidden' id='accept_words' name=\"accept_words\" value='" . implode(' ',$accept_words) . "'>\n";
    echo "<p>" . _("To run the page against the dictionary for an additional language, select one here and hit Check: ");
    $dict_list = load_available_dictionaries();

    echo "<select name='aux_language'>";
    echo "<option value=''>Language</option>";
    echo "\n";
    while(list($langcode,$language)=each($dict_list)) {
        echo "<option value='" .  htmlspecialchars($language,ENT_QUOTES) . "'";
        if(strcmp($language,$aux_language)==0) echo " selected";
        echo ">$language</option>\n";
    }
    echo "</select>";
?>
<input
    type="submit"
    name="rerunauxlanguage"
    value="<? echo _("Check"); ?>"
    title="<? echo _("Check page against an additional language"); ?>"
></p>

<?PHP
echo "<p>" . _("Note: Clicking the [AW] button will accept all instances of that word for this page, including corrections done to any instance of the word, and save the word for the Project Manager to consider adding to this project's dictionary.") . "</p>";
?>
<table id="tbtext" cellpadding="10" align="center"><tr>
<?PHP
    if ($userP['i_type'] !=1)
    {
        $url = $ppage->url_for_image();
        echo "<td id=\"tdbottom\"><a href=\"$url\" target=\"imageshowwin\">View Image</a></td></tr><tr>";
    }
?><td 
  id="tdtext" 
  valign="top">
<?PHP
    $ppage->echo_hidden_fields();

    spellcheck_text( $text_data, $ppage->projectid(), $ppage->imagefile(), $aux_language, $accept_words );
?>
</td>
</tr>
<tr>
    <td id="tdtop">
        <input
            type="submit"
            name="spcorrect"
            value="<? echo _("Submit Corrections"); ?>"
            title="<? echo _("Keep spelling corrections and return to proofreading this page"); ?>"
        >
        &nbsp;&nbsp;&nbsp;
        <input
            type="submit"
            name="spexit"
            value="<? echo _("Quit Spell Check"); ?>"
            title="<? echo _("Abandon spelling corrections and return to proofreading this page"); ?>"
        >
    </td>
</tr>
</table>
</form>
</div>
</body>
</html>
