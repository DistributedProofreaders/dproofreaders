<? $relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
// from theme.inc
html_header($code_url, $theme, '');
?>
<style type='text/css'>
// <!--
body {background: #ffffff;}
//-->
</style>
<?
// make a pretty little logobar with links to pers. page and proofing guidelines
 echo "<table cellspacing=0 cellpadding=0 width='100%'><tr width='100%'>";
 echo "<td width='100%' bgcolor='".$theme['color_logobar_bg']."'>\n";
 echo "<a href='$code_url' target='_top'><img src='$code_url/pinc/templates/".$theme_name."".$theme['image_logo']."' width='360' height='68' alt='Distributed Proofreaders' border='0'></a>\n</td></tr>";	
 // close table, and for some weird reason, start a new table
 echo "</table><table cellspacing=0 cellpadding=0 width='100%'>"; 
 echo "<tr><td width='20%' align='left' bgcolor='".$theme['color_headerbar_bg']."'>\n";
 echo "<a href='$code_url/default.php' target='_top'>\n";
 echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>DP</font></a>\n";
 echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'> &#187;  Proofing Quiz</font>\n";
 echo "</td>\n";
 echo "<td /* width='50%' */ align='right' bgcolor='".$theme['color_headerbar_bg']."'>\n";
 echo "<a href='$code_url/tools/proofers/proof_per.php' target='_top'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Personal Page") . "</font></a>";
 echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>&nbsp;<b>&#183;</b>&nbsp;</font>";
 echo "<a href='$code_url/faq/document.php' target='_top'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Proofreading Guidelines") . "</font></a></td>";
 echo "</tr></table>"; 
// javascript to email problems
echo "<script type='text/javascript' language='javascript'>";
echo "function sendmail()";
echo "{\n  document.forms[0].text.value = top.pf.document.forms[0].elements[0].value;\n  document.forms[0].submit(); \n  alert('Mail sent, thanks!');};";
echo "</script>";
// *      *       *       *       *
// Now to the good stuff


// A margin
echo "<div style='margin: .5em;'>";

// put ?feedb=blah into $feedb
$feedb = $_GET[feedb];
// if tbe error is tbe scanno (har har har)
if ($feedb == 'tbe') {
  echo "<h2>Scanno</h2>";
  echo "<p>You've missed one typical 'scanno' in the text. An 'h' mis-read as  a 'b'.</p>\n";
}
// wrong bold
elseif ($feedb == 'boldwrong') {
echo "<h2>Problem with bold markup</h2>";
echo "There is probably a problem connected with bold markup. The only word which should be marked as bold is 'Cartwright'.";
}
                   
// corrupt bold
elseif ($feedb == 'boldcorrupt') {
echo "<h2>Error in bold markup</h2>";
echo "Somehow the bold markup you've done seems to be corrupt. Start the bold text with &lt;b&gt; and end it with &lt;/b&gt;.";
}
// punctuation in bold
elseif ($feedb == 'commainbold') {
echo "<h2>Punctuation within bold markup</h2>";
echo "Generally, punctuation should not be included in the bold markup.";
}
// punctuation in italics
elseif ($feedb == 'commainital') {
echo "<h2>Punctuation within italics markup</h2>";
echo "Generally, punctuation should not be included in the italics markup.";
}
                   
// spaced markup
elseif ($feedb == 'spacedmarkup') {
echo "<h2>Spaced markup</h2>";
echo "You've marked up italics or bold in a way that there is a space after an opening tag or before a closing tag. Put the markers directly around the italics or bold text, with no additional space in between.";
}
// corrupt italics
elseif ($feedb == 'italcorrupt') {
echo "<h2>Error in italics markup</h2>";
echo "Somehow the italics markup you've done seems to be corrupt. Start the italics with &lt;i&gt; and end it with &lt;/i&gt;.";
}
// Multiple bold markup
elseif ($feedb == 'multiplebold') {
echo "<h2>Multiple bold markup</h2>";
echo "Re-check what you've marked as bold. In the original there is only one word in bold.";
}
// italics prob
elseif ($feedb == 'italprob') {
echo "<h2>Problem with italics markup</h2>";
echo "There is probably a problem connected with italics markup. The only word which should be marked as italics is 'vigilant'.";
}
// multitalics
elseif ($feedb == 'multipleital') {
echo "<h2>Multiple italics markup</h2>";
echo "Re-check what you've marked as italics. In the original there is only one word in italics.";
}
// no bold
elseif ($feedb == 'nobold') {
echo "<h2>Bold text missed</h2>";
echo "There is one bold word in the text, please surround it with &lt;b&gt; &lt;/b&gt;.";
}
// no italics
elseif ($feedb == 'noital') {
echo "<h2>Italics missed</h2>";
echo "There is one word in italics in the text, please surround it with &lt;i&gt; &lt;/i&gt;.";
}
// they finally got it
elseif ($feedb == 'ok') {
echo "<h2>Part 1 of quiz successfully solved</h2>";
echo "Congratulations, no errors found!<p>\n<a href='../2/tut.php' target='_top'>Next step of tutorial</a><br>\n<a href='../2/main.php' target='_top'>Next step of quiz</a>";
}
// they tried to edit the text, or something :roll:
elseif ($feedb == 'other') {
echo "<h2>Difference with expected text</h2>";
echo "<p>There is still a difference between your text and the expected one. Finding the reason for this is beyond the current scope of the analysing software.</p><p>This is the expected text:<br>";
echo "
<form action=''><textarea rows='20' cols='60' name='output' wrap='off'>
Good-natured and unsuspicious,
perhaps also not sufficiently <i>vigilant</i>,
Harvey was long in discovering how
he was pillaged. <b>Cartwright</b>, the
name of the person who was preying
on his employer, was not a young
man. He was between forty and fifty
years of age, and had been in various
situations, where he had always given
</textarea>
<p>
<a href='../2/tut.php' target='_top'>next step of tutorial</a><br>
<a href='../2/main.php' target='_top'>next step of quiz</a>
</p>";}
//  
//  
// otherwise, print the problem
else {
echo "<h2>Problem with quiz file</h2>";
echo "The checking script did not return a known value. Please use the link below to send an automated email about this. The value returned was: $feedb."; }
 
// correct that text, dude
echo "<p>";
echo "Try to correct that, press 'restart' to restart or have a look at the <a target='_top' href='./tut.php'>tutorial part</a> again.";
echo "</p>";
// space the scawwy email text
echo "<p>&nbsp;</p>";
// give the user a button to push if the error sense no makes
echo "<p>";
echo "The algorithm for finding errors in this quiz is a quite simple one. If you feel the ";
echo "message doesn't make any sense, please <a href='mailto:$quizemail'>send a feedback email</a>. ";
echo "</p>"; 
 ?>
 </div>
</body>
</html>
