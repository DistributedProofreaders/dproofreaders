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
// put ?feedb=blah into $feedb
$feedb = $_GET[feedb];

// A margin
echo "<div style='margin: .5em;'>";
// /#The user has not spaced the#/
if ($feedb == 'bqspacing') {
  echo "<h2>Block Quotes</h2>";
  echo "Please leave exactly one empty line before the block quote starting marker /#. 
Also leave one blank line after the block quote closing marker #/.
";
}


// The user should use their hands to fix the scanno
elseif ($feedb == 'hands') {
echo "<h2>Scanno</h2>";
echo "You've missed one typical 'scanno' in the text. A 'b' mis-read as an 'h'.";
}
// The user forgot the blockquote
elseif ($feedb == 'nobc') {
echo "<h2>Block Quotation</h2>";
echo "You have not or incorrectly marked the block quotation in the text. Enclose it with /# ... #/, with each marker on a line of its own.
";
}
                   
// nopindent
elseif ($feedb == 'nopindent') {
echo "<h2>Poetry line(s) not indented</h2>";
echo "The poems in the text have relative indentation. Try to represent that in the proofed text.";
}
// The user forgot the poetry
elseif ($feedb == 'nopoetry') {
echo "<h2>Poetry markup</h2>";
echo "You have not or incorrectly marked the poem in the text. Enclose it with /* ... */, with each marker on a line of its own.";
}
// The user  used   an    odd     number     of       spaces
elseif ($feedb == 'otherpindent') {
echo "<h2>Poetry indentation not as expected</h2>";
echo "For the indentation of poetry lines there is an unofficial semi-standard of using multiples of two spaces. Not following this is not exactly an error, but in this quiz for the sake of the dumb testing routines please use indents of two spaces.";
}

elseif ($feedb == 'plinenotjoined') {
echo "<h2>Poetry line not joined</h2>";
echo "There is one long poetry line, broken up into two lines. Please join those lines.";
}
// The user has missed the poetry
elseif ($feedb == 'pmspacing') {
echo "<h2>Poetry markup</h2>";
echo "Please leave exactly one empty line before the poetry starting marker /*.
Also leave one blank line after the poetry closing marker */.
";
}
// they finally got it
elseif ($feedb == 'ok') {
echo "<h2>Quiz successfully solved</h2>";
echo "
Congratulations, no errors found!<p>
That's it for now! These 5 parts covered the most important things to watch out for in proofing. When in doubt you should always consult the <a href='$code_url/faq/document.php' target='_top'>Proofing Guidelines</a> or ask in the forums if that doesn't help.</p>
<p>
You'll find books to proofread on your <a href='$code_url/tools/proofers/proof_per.php' target='_top'> Personal Page</a>, or you can <a href='$code_url/faq/quiz/start.php' target='top'>return to the start of the quiz</a>.";
}
// they tried to edit the text, or something :roll:
elseif ($feedb == 'other') {
echo "<h2>Difference with expected text</h2>";
echo "<p>There is still a difference between your text and the expected one. Finding the reason for this is beyond the current scope of the analysing software.</p><p>This is the expected text:<br>";
echo "
<form action=''><textarea rows='20' cols='30' name='output' wrap='off'>
We ask ourselves how Byron's poem\n
/*
You have the Pyrrhic dance as yet,
  Where is the Pyrrhic phalanx gone?
Of two such lessons, why forget
  The nobler and the manlier one?
*/\n
is related to these well known words:\n
/#
When in the Course of human events, it
becomes necessary for one people to dissolve the
political bands which have connected ...
#/\n
Not at all we suspect.</textarea>
<p>
This is the last step of the quiz.
Use the link above to return to your Personal Page.</p>";}
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
echo "message doesn't make any sense, please <a href='mailto:$quizemail'>send a feedback email</a>. ";
echo "</p>"; 
}?>
</div> 
</body>
</html>
