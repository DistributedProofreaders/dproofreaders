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
// The user has [forgotten] the square brackets.
if ($feedb == 'sqbr') {
  echo "<h2>Incorrect brackets.</h2>";
  echo "Please use square brackets [] for illustration and footnote markup.";
}
// long line
elseif ($feedb == '../longline') {
echo "<h2>Long line</h2>";
echo "You've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line.";
}
// :The user should live in Australia:
elseif ($feedb == 'fncolonmissing') {
echo "<h2>Footnote markup without colon.</h2>";
echo "Insert a colon between the footnote marker 'A' and the footnote text.";
}
                   
// The user used an odd footnote markup[8-934]
elseif ($feedb == 'fnfalsemarker') {
echo "<h2>Incorrect footnote tag.</h2>";
echo "It seems you have used different footnote tags. Please refer to the tutorial, or the Proofreading Guidelines.";
}

// Illustration tag in wrong place
elseif ($feedb == 'illupos') {
echo "<h2>Illustration position incorrect.</h2>";
echo "The illustration should be moved outside the paragraph, but next to the paragraph it was in.";
}

// The user forgot the footnote marker
elseif ($feedb == 'fnmarkermissing') {
echo "<h2>Footnote marker missing</h2>";
echo "It seems you haven't inserted the required footnote marker. The marker should be in square brackets.";
}

// The user used the symbol :(
elseif ($feedb == 'fnmarkerone') {
echo "<h2>Footnote marker wrong.</h2>";
echo "The footnote marker you've inserted is very nearly correct. But since the original marker is a symbol, you should choose an upper case letter: [A].";
}
                   
// The user has done something *really* weird
elseif ($feedb == 'fnmarkerother') {
echo "<h2>Problem with footnote marker.</h2>";
echo "Something not exactly detectable doesn't seem to be right with the footnote marker. The line containing it should look like this:<br><tt>be realised.[A] The work</tt>";
}
// The user has marked the footnote incorrectly
elseif ($feedb == 'fnmarkerwrong') {
echo "<h2>Footnote marker wrong.</h2>";
echo "The footnote marker should look like this: <tt>[A]</tt>.";
}
// Inline footnotes :(
elseif ($feedb == 'fnpos') {
echo "<h2>Footnote at wrong position.</h2>";
echo "It seems you have moved the footnote. Please leave it at the end of the page.";
}
// no marker for footnote.
elseif ($feedb == 'fnwomarker') {
echo "<h2>Footnote without marker.</h2>";
echo "Insert the footnote marker 'A' between the word 'Footnote' and the colon.";
}
// The    user    has    spaced   the    illustration    tag
elseif ($feedb == 'illumuchspace') {
echo "<h2>Multiple blank lines before or after illustration.</h2>";
echo "Please leave not more than one blank line before and after an illustration.";
}
// Theuserdidnotspacetheillustrationatall
elseif ($feedb == 'illunospace') {
echo "<h2>Blank line before or after illustration missing.</h2>";
echo "Please leave one blank line before and after an illustration.";
}
// [Illustration: something weird]
elseif ($feedb == 'illuother') {
echo "<h2>Problem with illustration.</h2>";
echo "Something not exactly detectable doesn't seem to be right with the illustration. It should look like this: <br><tt>[Illustration: High art.]</tt>";
}
// The incorrectly user illustration the put position
elseif ($feedb == 'illunospace') {
echo "<h2>Illustration position incorrect.</h2>";
echo "The illustration should be moved outside the paragraph, but next to the paragraph it was in.";
}
// That silly user; lie has missed the scanno!
elseif ($feedb == 'lie') {
echo "<h2>Scanno</h2>";
echo "You've missed one typical 'scanno' in the text. An 'h' mis-read as 'li'.";
}
// [Illustration: no caption]
elseif ($feedb == 'nocaption') {
echo "<h2>Illustration caption missing.</h2>";
echo "It seems you haven't included the illustration caption. Put the illustration caption within [Illustration: ]";
}
// No footnote marker
elseif ($feedb == 'nofn') {
echo "<h2>Footnote markup missing.</h2>";
echo "It seems you haven't marked the footnote at the bottom correctly. Put the footnote text within [Footnote _: ] placing the correct marker where the underscore is.";
}
// No illustration
elseif ($feedb == 'noillu') {
echo "<h2>Illustration missing.</h2>";
echo "It seems you haven't marked the illustration correctly. Put the illustration caption within [Illustration: ]";
}
// Spaced footnote marker [A]
elseif ($feedb == 'spacedfnmarker') {
echo "<h2>Spaced footnote marker.</h2>";
echo "The footnote marker should go immediately after the word, without a space in between.";
}
// they finally got it
elseif ($feedb == 'ok') {
echo "<h2>Part 4 of quiz successfully solved</h2>";
echo "Congratulations, no errors found!<p>\n<a href='../5/tut.php' target='_top'>Next step of tutorial</a><br>\n<a href='../5/main.php' target='_top'>Next step of quiz</a>";
}
// they tried to edit the text, or something :roll:
elseif ($feedb == 'other') {
echo "<h2>Difference with expected text</h2>";
echo "<p>There is still a difference between your text and the expected one. Finding the reason for this is beyond the current scope of the analysing software.</p><p>This is the expected text:<br>";
echo "
<form action=''><textarea rows='20' cols='60' name='output' wrap='off'>
printing would be good for nothing but
waste paper, might not
be realised.[A] The work
appeared about the end
of December 1818 with
1819 on the title-page.
Schopenhauer had
meanwhile proceeded in
September to Italy, where he revised the
final proofs.\n
[Illustration: High art.]\n
So far as the reception of the work was\n
[Footnote A: Wallace, p. 108.]</textarea>
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
 
 
if ($feedb != 'ok') {
// correct that text, dude
echo "<p>";
echo "Try to correct that, press 'restart' to restart or have a look at the <a target='_top' href='./tut.php'>tutorial part</a> again.";
echo "</p>";
// space the scawwy email text
echo "<p>&nbsp;</p>";
// give the user a button to push if the error sense no makes
echo "<p>";
echo "The algorithm for finding errors in this quiz is a quite simple one. If you feel the ";
echo "message doesn't make any sense, please post a feedback message in <a href='";
echo $forums_url;
echo "/viewtopic.php?t=9165'>this forum topic</a>.";
echo "</p>"; 
}
?>
 </div>
</body>
</html>
