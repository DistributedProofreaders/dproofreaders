<?php

include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"formatting2","pass");
}
$browser_title                = _("Formatting Quiz");
$welcome                      = _("<h2>Formatting Quiz, page 2</h2> Try to correct the text.");
//Quiz test and solution in English so the user results evaluation works.
$ocr_text                     = "a box of St. Louis candy for his wife, and spins back\nhome, where he listens to music \"canned\" in New Jersey.\n\nTHE BETTER WAY\nCharles M. Schwab, congratulated in Pittsburgh on a\nlarge wired telegraph order contract that had been re-\nceived from one of the warring nations, said:\n\"Some call it luck, but they are mistaken -- whatever\nsuccess I have is due to hard work and not to luck.";
$solutions                    = array("a box of St. Louis candy for his wife, and spins back\nhome, where he listens to music \"canned\" in New Jersey.\n\n\nTHE BETTER WAY\n\nCharles M. Schwab, congratulated in Pittsburgh on a\nlarge wired telegraph order contract that had been received\nfrom one of the warring nations, said:\n\n\"Some call it luck, but they are mistaken--whatever\nsuccess I have is due to hard work and not to luck.");
$showsolution                 = TRUE;
$solved_message               = _("<h2>Quiz successfully solved</h2> Congratulations, no errors found!");
$links_out                    = sprintf( _("<a href='%1s'>Next step of quiz</a><br><a href='%2s'>Quizzes home.</a>"), "../generic/main.php?type=formatting3&quiz_id=FQ' target='_top", "../start.php' target='_top");




// error messages

$messages["sectionspacing"] = array("message_text" => "<h2>" . _("Section heading spacing incorrect") . "</h2> " . _("Leave two blank lines before a section heading, and one after it."), "hints" => array());
$messages["hyphenrej"] = array("message_text" => "<h2>" . _("Hyphen not rejoined") . "</h2> " . _("If any hyphens have not been rejoined by the pre-processing software or the proofreaders, bring the hyphenated word up so that it is all on one line."), "hints" => array());
$messages["spacedem"] = array("message_text" => "<h2>" . _("Spaced em-dash in text") . "</h2> <tt>" . _("Remove the spaces surrounding any dashes--like this!</tt>"), "hints" => array());
$messages["missingline"] = array("message_text" => "<h2>" . _("Missing blank line") . "</h2> " . _("Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a new line at the start of the page."), "hints" => array());
$messages["longline"] = array("message_text" => "<h2>" . _("Long line") . "</h2>\n" . _("You've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line."), "hints" => array());

// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" => array(" --", "-- "), "case_sensitive" => TRUE, "error" => "spacedem");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array(".\n\n\nTHE BETTER WAY\n\nC"), "case_sensitive" => TRUE, "error" => "sectionspacing");
$tests[] = array("type" => "forbiddentext", "searchtext" => "re-", "case_sensitive" => TRUE, "error" => "hyphenrej");
$tests[] = array("type" => "forbiddentext", "searchtext" => ":\n\"Some", "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "longline", "lengthlimit" => 80, "error" => "longline");
?>
