<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

// from theme.inc
html_header($code_url, $theme, '');

// make a pretty little logobar with links to pers. page and proofing guidelines
echo "<table cellspacing=0 cellpadding=0 width='100%'>";
echo "<tr width='100%'>";
echo "<td width='100%' bgcolor='".$theme['color_logobar_bg']."'>\n";
echo "<a href='$code_url' target='_top'><img src='$code_url/pinc/templates/".$theme_name."".$theme['image_logo']."' width='360' height='68' alt='Distributed Proofreaders' border='0'></a>\n";
echo "</td>";
echo "</tr>";	
echo "</table>";
// close table, and for some weird reason, start a new table

echo "<table cellspacing=0 cellpadding=0 width='100%'>"; 
echo "<tr>";
echo "<td width='20%' align='left' bgcolor='".$theme['color_headerbar_bg']."'>\n";
echo "<a href='$code_url/default.php' target='_top'>\n";
echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>DP</font></a>\n";
echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'> &#187;  Proofreading Quiz</font>\n";
echo "</td>\n";
echo "<td /* width='50%' */ align='right' bgcolor='".$theme['color_headerbar_bg']."'>\n";
echo "<a href='$code_url/tools/proofers/proof_per.php' target='_top'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Personal Page") . "</font></a>";
echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>&nbsp;<b>&#183;</b>&nbsp;</font>";
echo "<a href='$code_url/faq/document.php' target='_top'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Proofreading Guidelines") . "</font></a>";
echo "</td>";
echo "</tr>";
echo "</table>"; 

// javascript to email problems
echo "<script type='text/javascript' language='javascript'>";
echo "function sendmail()";
echo "{\n  document.forms[0].text.value = top.pf.document.forms[0].elements[0].value;\n  document.forms[0].submit(); \n  alert('Mail sent, thanks!');};";
echo "</script>";
?>
