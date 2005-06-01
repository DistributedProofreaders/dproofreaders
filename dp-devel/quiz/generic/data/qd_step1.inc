<?PHP
include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"step1","pass");
}

$browser_title                = "DP -- Proofreading Quiz";
$ocr_text                     = "Good-natured and unsuspicious,\nperhaps also not sufficiently vigilant,\nHarvey was long in discovering how\nhe was pillaged. Cartwr&iacute;ght, the\nname of tbe person who was preying\non his employer, was not a young\nman. He was between forty and fifty\nyears of age, and had been in various\nsituations, where he had always given";
$solutions                    = array("Good-natured and unsuspicious,\nperhaps also not sufficiently vigilant,\nHarvey was long in discovering how\nhe was pillaged. Cartwright, the\nname of the person who was preying\non his employer, was not a young\nman. He was between forty and fifty\nyears of age, and had been in various\nsituations, where he had always given");
$showsolution                 = TRUE;
$welcome                      = "<h2>Quiz, part 1</h2>\nTry to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.";
$solved_message               = "<h2>Part 1 of quiz successfully solved</h2>\nCongratulations, no errors found!";
$links_out                    = "<a href='../generic/main.php?type=step2&quiz_id=PQ' target='_top'>Next step of quiz</a>";

$tests[] = array("type" => "forbiddentext", "searchtext" => "tbe", "case_sensitive" => TRUE, "error" => "tbe");
$tests[] = array("type" => "forbiddentext", "searchtext" => "<i>", "case_sensitive" => FALSE, "error" => "ital");
$tests[] = array("type" => "forbiddentext", "searchtext" => "<b>", "case_sensitive" => FALSE, "error" => "bold");
$tests[] = array("type" => "expectedtext", "searchtext" => array("Cartwright"), "case_sensitive" => TRUE, "error" => "acutei");

$messages["tbe"] = array("message_text" => "<h2>Scanno</h2>\n<p>You've missed one typical 'scanno' in the text. An 'h' mis-read as  a 'b'.</p>\n", "hints" => array(array("hint_text" => "<h2>Scanno: hints</h2>\n<p>Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.</p>\n<p>You are looking for a 'b' that is wrong. There are only 3 words with a 'b' in the text. Once you've found them you will immediately know which one is the culprit.</p>\n<p>If you can only find 2 words with a 'b', consider copying the text into an editor and searching for 'b'. You'll get 3 results, guaranteed!</p>\n<p>No, we won't give away the solution, after all this is a quiz!</p>\n")));
$messages["ital"] = array("message_text" => "<h2>Italics markup inserted</h2>\nLeave the insertion of the italics markup for the formatting rounds.", "hints" => array());
$messages["bold"] = array("message_text" => "<h2>Bold markup inserted</h2>\nLeave the insertion of the bold markup for the formatting rounds.", "hints" => array());
$messages["acutei"] = array("message_text" => "<h2>Scanno</h2>\nYou've missed a typical scanno: a letter has been recognised with an incorrect accent.", "hints" => array());
?>
