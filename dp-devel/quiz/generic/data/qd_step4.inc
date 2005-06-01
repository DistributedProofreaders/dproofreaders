<?PHP
include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"step4","pass");
}

$browser_title                = "DP -- Proofreading Quiz";
$ocr_text                     = "printing would be good for nothing but\nwaste paper, might not\nbe realised. The work\nappeared about the end\nof December 18|8 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where lie revised the\nfinal proofs.\n\nHigh art.\n\nSo far as the reception of the work was\n\nWallace, p. 108.";
$solutions                    = array("printing would be good for nothing but\nwaste paper, might not\nbe realised.[*] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\nHigh art.\n\nSo far as the reception of the work was\n\nWallace, p. 108.");
$showsolution                 = TRUE;
$welcome                      = "<h2>Quiz, part 4</h2>\nTry to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.";
$solved_message               = "<h2>Part 4 of quiz successfully solved</h2>\nCongratulations, no errors found!";
$links_out                    = "<a href='../generic/main.php?type=step5&quiz_id=PQ' target='_top'>Next step of quiz</a><br /><a href='../start.php' target='_top'>Quizzes home.</a>";



$tests[] = array("type" => "longline", "lengthlimit" => 60, "error" => "longline");
$tests[] = array("type" => "forbiddentext", "searchtext" => "(A)", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "{A}", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "(1)", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "{1}", "case_sensitive" => FALSE, "error" => "sqbr");
$tests[] = array("type" => "forbiddentext", "searchtext" => "lie", "case_sensitive" => TRUE, "error" => "lie");
$tests[] = array("type" => "expectedtext", "searchtext" => array("[*]"), "case_sensitive" => FALSE, "error" => "fnmarkermissing");
$tests[] = array("type" => "forbiddentext", "searchtext" => "[Footnote", "case_sensitive" => FALSE, "error" => "footmarked");
$tests[] = array("type" => "forbiddentext", "searchtext" => "18|8", "case_sensitive" => FALSE, "error" => "datescanno");
$tests[] = array("type" => "forbiddentext", "searchtext" => "[Illustration", "case_sensitive" => FALSE, "error" => "illusmarked");

$messages["sqbr"] = array("message_text" => "<h2>Incorrect brackets.</h2>\nPlease use square brackets [] for illustration and footnote markup.", "hints" => array());
$messages["longline"] = array("message_text" => "<h2>Long line</h2>\nYou've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line.", "hints" => array());
$messages["fnmarkermissing"] = array("message_text" => "<h2>Footnote marker missing</h2>\nIt seems you haven't inserted the required footnote marker. The marker should be in square brackets, and have its symbol replaced with an *.", "hints" => array());
$messages["fnmarkerother"] = array("message_text" => "<h2>Problem with footnote marker.</h2>\nSomething not exactly detectable doesn't seem to be right with the footnote marker. The line containing it should look like this:<br><tt>be realised.[*] The work</tt>", "hints" => array());
$messages["fnmarkerwrong"] = array("message_text" => "<h2>Footnote marker wrong.</h2>\nThe footnote marker should look like this: <tt>[A]</tt>.", "hints" => array());
$messages["fnpos"] = array("message_text" => "<h2>Footnote at wrong position.</h2>\nIt seems you have moved the footnote. Please leave it at the end of the page.", "hints" => array());
$messages["lie"] = array("message_text" => "<h2>Scanno</h2>\n<p>You've missed one typical 'scanno' in the text. An 'h' mis-read as 'li'.</p>", "hints" => array(array("hint_text" => "<h2>Scanno: hints</h2>\n<p>Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.</p>\n<p>You are looking for an occurance of 'li' that is wrong. There are only 2 words with 'li' in the text. Once you've found them you will immediately know which one is the culprit.</p>\n<p>If you can only find 1 word with a 'li', consider copying the text into an editor and searching for 'li'. You'll get 2 results, guaranteed!</p>\n<p>No, we won't give away the solution, after all this is a quiz!</p>")));
$messages["nocaption"] = array("message_text" => "<h2>Illustration caption missing.</h2>\nIt seems you haven't included the illustration caption. Put the illustration caption within [Illustration: ]", "hints" => array());
$messages["footmarked"] = array("message_text" => "<h2>Footnote markup inserted.</h2>\nLeave the insertion of the footnote markup for the formatting rounds.", "hints" => array());
$messages["datescanno"] = array("message_text" => "<h2>Scanno.</h2>\nYou've missed a scanno in the text: a 1 misread as a |.", "hints" => array());
$messages["illusmarked"] = array("message_text" => "<h2>Illustration markup inserted.</h2>\nLeave the insertion of the illustration markup for the formatting rounds.", "hints" => array());
?>




































