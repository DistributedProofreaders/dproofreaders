<? $relPath='../../../pinc/';
include_once('../small_theme.inc');

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
echo "<p>You've missed one typical 'scanno' in the text. A 'b' mis-read as an 'h'.</p>\n";
echo "<p>Desperate? Can't find it? Get some more hints <a href='./returnfeed.php?feedb=hands2'>here</a>.</p>\n";
}
// the user request hints to find the hands scanno
elseif ($feedb == 'hands2') {
echo "<h2>Scanno: hints</h2>";
echo "<p>Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.</p>\n";
echo "<p>You are looking for an 'h' that is wrong. There are 21 words with an 'h' in the text. Two of those words would also make sense if you replaced the 'h' by a 'b'. Check them with the original and you'll know.</p>\n";
echo "<p>If you can't find all 21 words with an 'h', consider copying the text into an editor and searching for 'h'.</p>\n";
echo "<p>No, we won't give away the solution, after all this is a quiz!</p>\n";
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
echo "The poems in the text have relative indentation. Try to represent that in the proofreaded text.";
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
// The user indenten the complete poem
elseif ($feedb == 'baseindent') {
echo "<h2>Poetry indentation</h2>";
echo "It seems you have indented the whole poem. Please try to represent only relative indentation, so that the leftmost lines are not indented.";
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
// The user hasn't put the poetry maker on a line of its own
elseif ($feedb == 'poetrymarkerown') {
echo "<h2>Problem with Poetry or Block Quotation markup</h2>";
echo "Please put the poetry markers /* and */ and block quotation markers /# and #/ each on a line of their own.";
}
// The user has included too much text in the block quote
elseif ($feedb == 'bqtoomuch') {
echo "<h2>Block quotation markup wrong</h2>";
echo "You've included too much text in the block quotation.";
}
// they finally got it
elseif ($feedb == 'ok') {
echo "<h2>Quiz successfully solved</h2>";
echo "
Congratulations, no errors found!<p>
That's it for now! These 5 parts covered the most important things to watch out for in proofreading. When in doubt you should always consult the <a href='$code_url/faq/document.php' target='_top'>Proofreading Guidelines</a> or ask in the forums if that doesn't help.</p>
<p>
You'll find books to proofread via the <a href='$code_url/activity_hub.php' target='_top'>Activity Hub</a>, or you can <a href='$code_url/faq/quiz/start.php' target='_top'>return to the start of the quiz</a>.";
}
// they tried to edit the text, or something :roll:
elseif ($feedb == 'other') {
echo "<h2>Difference with expected text</h2>";
echo "<p>There is still a difference between your text and the expected one. Finding the reason for this is beyond the current scope of the analysing software.</p><p>This is the expected text:<br>";
echo "
<form action=''><textarea rows='20' cols='60' name='output' wrap='off'>
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
Use the link above to return to the Activity Hub.</p>";}
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
echo "/viewtopic.php?t=9165' target='_blank'>this forum topic</a>.";
echo "</p>"; 
}
?>
</div> 
</body>
</html>
