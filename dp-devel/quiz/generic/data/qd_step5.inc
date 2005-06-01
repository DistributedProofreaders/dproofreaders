<?PHP
include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"step5","pass");
}

$browser_title                = "DP -- Proofreading Quiz";
$ocr_text                     = "We ask ourselves how Byron's poem\n\nYou have the Pyrrhic dance as yet,\nWhere is the Pyrrhic phalanx\n                 gone?\nOf two such lessons, why forget\nThe nobler and the manlier one?\n\nis related to these well known words:\n\nWhen in the Course of human events, it\nbecomes necessary for one people to dissolve the\npolitical hands which have connected ...\n\nNot at all. we suspect.";
$solutions                    = array("We ask ourselves how Byron's poem\n\nYou have the Pyrrhic dance as yet,\nWhere is the Pyrrhic phalanx gone?\nOf two such lessons, why forget\nThe nobler and the manlier one?\n\nis related to these well known words:\n\nWhen in the Course of human events, it\nbecomes necessary for one people to dissolve the\npolitical bands which have connected ...\n\nNot at all, we suspect.");
$showsolution                 = TRUE;
$welcome                 = "<h2>Quiz, part 5</h2>\nTry to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.";
$solved_message               = "<h2>Part 5 of quiz successfully solved</h2>\nCongratulations, no errors found!";
$links_out                    = "<a href='../start.php' target='_top'>Quizzes home.</a>";



$tests[] = array("type" => "forbiddentext", "searchtext" => "<b>Byron's</b>", "case_sensitive" => TRUE, "error" => "boldfont");
$tests[] = array("type" => "expectedtext", "searchtext" => array("bands"), "case_sensitive" => FALSE, "error" => "hands");
$tests[] = array("type" => "forbiddentext", "searchtext" => "/*", "case_sensitive" => TRUE, "error" => "markup");
$tests[] = array("type" => "forbiddentext", "searchtext" => "/#", "case_sensitive" => TRUE, "error" => "markup");
$tests[] = array("type" => "expectedtext", "searchtext" => array("phalanx gone"), "case_sensitive" => FALSE, "error" => "joinword");
$tests[] = array("type" => "expectedtext", "searchtext" => array("at all,"), "case_sensitive" => FALSE, "error" => "commaperiod");

$messages["hands"] = array("message_text" => "<h2>Scanno</h2>\n<p>You've missed one typical 'scanno' in the text. A 'b' mis-read as an 'h'.</p>", "hints" => array(array("hint_text" => "<h2>Scanno: hints</h2>\n<p>Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.</p>\n<p>You are looking for an 'h' that is wrong. There are 21 words with an 'h' in the text. Two of those words would also make sense if you replaced the 'h' by a 'b'. Check them with the original and you'll know.</p>\n<p>If you can't find all 21 words with an 'h', consider copying the text into an editor and searching for 'h'.</p>\n<p>No, we won't give away the solution, after all this is a quiz!</p>")));
$messages["boldfont"] = array("message_text" => "<h2>Font change marked as bold</h2>\nYou've marked a change in font as bold. In general, ignore changes in font or font size.", "hints" => array());
$messages["markup"] = array("message_text" => "<h2>Markup inserted</h2>\nLeave markup insertion for the formatting rounds.", "hints" => array());
$messages["joinword"] = array("message_text" => "<h2>Long poetry line not joined</h2>\nMove the end of the long poetry line up to the same line as the rest.", "hints" => array());
$messages["commaperiod"] = array("message_text" => "<h2>Scanno</h2>\nA comma has been misread as a period.", "hints" => array());
?>
