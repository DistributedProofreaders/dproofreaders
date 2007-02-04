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


slim_header("WordCheck",TRUE,FALSE);

if($userP['i_type']==1)
    ibe_echo_script();

spellcheck_echo_script();
?>
<script language="JavaScript" type="text/javascript">
<!--
function ldAll()
{top.initializeStuff(<?PHP
  if ($userP['i_type']==1)
    {echo "2";}
  else
    {echo "3";}
?>);}
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
  margin-top: 0;
  }
<?
if($userP['i_type']==1)
    ibe_echo_styles();
?>
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
-->
</style>
</head><body 
  onload="ldAll()">

<?PHP
// print basic image html
if ($userP['i_type']==1) {
    if ($userP['i_layout']==1)
       { $iWidth = $userP['v_zoom']; }
    else { $iWidth = $userP['h_zoom']; }
    $iWidth = round((1000*$iWidth)/100);

    ibe_echo_block( $ppage->url_for_image(), $iWidth );

    echo '<div id="controlframe">';
} else {
    echo '<div>';
}
?>
<form name="spcorrects" action="processtext.php" method="POST">
<?PHP
    echo "<input type='hidden' name='revert_text' value='" . html_safe($revert_text) . "'>\n";
    echo "<input type='hidden' id='is_changed' name='is_changed' value='0'>\n";
    echo "<input type='hidden' id='accepted_words' name=\"accepted_words\" value='" . implode(' ',$accepted_words) . "'>\n";

    // run the text through the spellcheck - returns the form contents and a list of languages the page was checked against
    list($page_contents,$languages,$messages) =
        spellcheck_text( $text_data, $ppage->projectid(), $ppage->imagefile(), $aux_language, $accepted_words );

    echo "<p>";
    echo _("The text was checked against the following languages: ");
    echo "<b>";
    echo implode(', ', $languages);
    echo "</b>";
    echo "</p>";

    if ( count($messages) == 0 )
    {
        // echo "No warnings/errors were raised.<br>\n";
    }
    else
    {
        echo "The following warnings/errors were raised:<br>\n";
        foreach ( $messages as $message )
        {
            echo "$message<br>\n";
        }
    }

    echo "<p>" . _("Check against an additional language: ");

    echo "<select name='aux_language'>";
    echo "<option value=''>Language</option>";
    echo "\n";

    // get a list of languages with dictionaries installed on the system
    $dict_list = get_languages_with_dictionaries();
    foreach($dict_list as $langcode => $language) {
        // skip the languages that we just used.
        if(in_array($language,$languages)) continue;
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
echo "<p>" . _("Note: The <img src=\"$code_url/graphics/Book-Plus-Small.gif\" border=\"0\"> icon will accept all instances of that word for this page, and suggest it for inclusion in this project's dictionary. See also the <a href=\"$code_url/faq/spellcheck-faq.php\" target=\"FAQ\">WordCheck FAQ</a>.") . "</p>";
?>
<table id="tbtext" cellpadding="10" align="center"><tr>
<td 
  id="tdtext" 
  valign="top">
<?PHP
    $ppage->echo_hidden_fields();
    echo $page_contents;

?>
</td>
</tr>
<tr>
    <td id="tdtop">
        <input
            type="submit"
            name="spcorrect"
            value="<? echo _("Submit Corrections"); ?>"
            title="<? echo _("Keep corrections and return to proofreading this page"); ?>"
        >
        &nbsp;&nbsp;&nbsp;
        <input
            type="submit"
            name="spexit"
            value="<? echo _("Quit WordCheck"); ?>"
            title="<? echo _("Abandon corrections and return to proofreading this page"); ?>"
            onClick="return confirmExit();"
        >
    </td>
</tr>
<tr><td style="text-align: center">
<? echo _("Resize image:"); ?>
    <input title="<? echo _("Zoom Out 25%"); ?>" type="button" value="-25%"  onclick="top.reSizeRelative(0.75)">
    <input title="<? echo _("Zoom In 25%"); ?>" type="button" value="+25%" onclick="top.reSizeRelative(1.25)">
    <input title="<? echo _("Zoom to Original Size"); ?>" type="button" value="Original" onclick="top.reSizeRelative(-1)">
</td>
</tr>
</table>
</form>
</div>
</body>
</html>
<? // vim: sw=4 ts=4 expandtab
?>
