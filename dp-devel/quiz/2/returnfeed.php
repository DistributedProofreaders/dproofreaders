<? $relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
// from theme.inc
html_header($code_url, $theme, '');
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
// para
if ($feedb == 'para') {
  echo "<h2>Paragraph not marked</h2>";
  echo "<p>Paragraphs should be divided by a blank line.</p>\n";
}
// eol hyph, mdash
elseif ($feedb == 'eolhyphen') {
echo "<h2>End-of-line hyphen or dash</h2>";
echo "You've left a hyphen or dash at the end of a line. The first word of the next line should be moved up to that line (and possibly the word should be joined and the hyphen deleted.)";
}
// eop hyph
elseif ($feedb == 'eophyphen') {
echo "<h2>End-of-page hyphen</h2>";
echo "If there is a hyphen or dash at the end of a page, it should be marked by a '*' directly following the dash or hyphen.";
}
// hyph
elseif ($feedb == 'hyphen') {
echo "<h2>End-of-line Hyphenation</h2>";
echo "Unless it really is a hyphenated word like well-meaning, remove the end-of-line hyphenation, and join the two halves of a previously hyphenated word back together.";
}
// long line
elseif ($feedb == '../longline') {
echo "<h2>Long line</h2>";
echo "You've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line.";
}
// normal text as italics
elseif ($feedb == 'extraital') {
echo "<h2>Normal text marked up as italics</h2>";
echo "Sometimes our preprocessing software inserts mark-ups (like &lt;i&gt; &lt;/i&gt;) when they are unnecessary. You just found an instance of this. ";
}
 
// they finally got it
elseif ($feedb == 'ok') {
echo "<h2>Part 2 of quiz successfully solved</h2>";
echo "Congratulations, no errors found!<p>\n<a href='../3/tut.php' target='_top'>Next step of tutorial</a><br>\n<a href='../3/main.php' target='_top'>Next step of quiz</a>";
}
// they tried to edit the text, or something :roll:
elseif ($feedb == 'other') {
echo "<h2>Difference with expected text</h2>";
echo "<p>There is still a difference between your text and the expected one. Finding the reason for this is beyond the current scope of the analysing software.</p><p>This is the expected text:<br>";
echo "
<form action=''><textarea rows='20' cols='60' name='output' wrap='off'>
a detective, why was he watching? There was
indeed no reward offered whatsoever for his arrest.
Perhaps he belonged to the wretched type of beings
who do pride themselves on their public spirit--men
who wrote letters to the newspapers and
interfered in other people's business. He might now
well have wanted to show his public spirit by handing
him over to the police. The newspaper in his
hand! Of course. He had read his description there,
and identified him.
Charles now found himself conjecturing how the
man would set about carrying out his task of pub-*
</textarea>
<p>
<a href='../3/tut.php' target='_top'>next step of tutorial</a><br>
<a href='../3/main.php' target='_top'>next step of quiz</a>
</p>";}
//  
//  
// otherwise, print the problem
else {
echo "<h2>Problem with quiz file</h2>";
echo "The checking script did not return a known value. Please use the link below to send an automated email about this. The value returned was: $feedb."; }
 
if ($feedb != "ok") {
// correct that text, dude
echo "<p>";
echo "Try to correct that, press 'restart' to restart or have a look at the <a target='_top' href='./tut.php'>tutorial part</a> again.";
echo "</p>";
// space the scawwy email text
echo "<p>&nbsp;</p>";
// give the user a button to push if the error sense no makes
echo "<p>";
echo "The algorithm for finding errors in this quiz is a quite simple one. If you feel the ";
echo "message doesn't make any sense, please post a feedback message in <a href='"
echo $forums_url;
echo "/viewtopic.php?t=9165'>this forum topic</a>.";
echo "</p>"; 
}
 ?>
  </div>
</body>
</html>
