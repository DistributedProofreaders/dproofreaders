<?php

include_once($relPath."Quiz.inc");

function quizsolved()

{

  global $pguser;

  if (isset($pguser))

      record_quiz_attempt($pguser,"step1","pass");

}



$browser_title                = _("DP -- Proofreading Quiz");
//Quiz test and solution in English so the user results evaluation works.
$ocr_text = "\n\"I will,\" said Madeline's mother, and then she\ncarried the bundles into the house, while Mirabell's\nmother went on home in her automobile.\n\n\"Oh, Mother! What have you?\" cried the voice of a\nlittle girl, as the lady entered the house with tbe\nbundle in which the Candy Rabbit was wrapped.\n\n\"Is it...something good to cat..?\" asked a boy's voice.\n\n\"Now, Herbert and Madeline, you must not ask too\nmany questions.\" said their mother, with a laugh.\n\"This isn't exactly CHRISTMAS, you know, but it will\nsoon be EASTER, and--\"";

$solutions                    = array("\n\"I will,\" said Madeline's mother, and then she\ncarried the bundles into the house, while Mirabell's\nmother went on home in her automobile.\n\n\"Oh, Mother! What have you?\" cried the voice of a\nlittle girl, as the lady entered the house with the\nbundle in which the Candy Rabbit was wrapped.\n\n\"Is it ... something good to eat...?\" asked a boy's voice.\n\n\"Now, Herbert and Madeline, you must not ask too\nmany questions,\" said their mother, with a laugh.\n\"This isn't exactly CHRISTMAS, you know, but it will\nsoon be EASTER, and----\"");

$showsolution                 = TRUE;

$welcome                      = "<h2>" . _("Quiz, Part 1") . "</h2><p>" . _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.");

$solved_message               = "<h2>" . _("Part 1 of quiz successfully solved") . "</h2><p>" . _("Congratulations, no errors found!");

$links_out                    = "<a href='../generic/main.php?type=step2&quiz_id=PQ' target='_top'>" . _("Next step of quiz") . "</a>";



$tests[] = array("type" => "forbiddentext", "searchtext" => "<i>", "case_sensitive" => FALSE, "error" => "ital");
$tests[] = array("type" => "forbiddentext", "searchtext" => "<sc>", "case_sensitive" => FALSE, "error" => "sci");
$tests[] = array("type" => "expectedtext", "searchtext" => array("questions,"), "case_sensitive" => TRUE, "error" => "comper");
$tests[] = array("type" => "expectedtext", "searchtext" => array("t ... s"), "case_sensitive" => TRUE, "error" => "ellipsis");
$tests[] = array("type" => "expectedtext", "searchtext" => array("t...?"), "case_sensitive" => TRUE, "error" => "ellipsis");
$tests[] = array("type" => "expectedtext", "searchtext" => array("CHRISTMAS"), "case_sensitive" => TRUE, "error" => "decap");
$tests[] = array("type" => "expectedtext", "searchtext" => array("EASTER"), "case_sensitive" => TRUE, "error" => "decap");
$tests[] = array("type" => "expectedtext", "searchtext" => array("and----\""), "case_sensitive" => TRUE, "error" => "longdash");
$tests[] = array("type" => "forbiddentext", "searchtext" => "tbe", "case_sensitive" => TRUE, "error" => "tbe");
$tests[] = array("type" => "forbiddentext", "searchtext" => "cat", "case_sensitive" => TRUE, "error" => "cat");

$messages["invex"] = array("message_text" => "<h2>" . _("Scanno") . "</h2>\n<p>" . _("You missed one typical scanno in the text: An '<tt>i</tt>' mis-read as a '<tt>i</tt>' inverted exclamation mark") . "</p>\n", "hints" => array());

$messages["ital"] = array("message_text" => "<h2>" . _("Italics markup inserted") . "</h2>\n" . _("Leave the insertion of the italics markup for the formatting rounds."), "hints" => array());
$messages["sci"] = array("message_text" => "<h2>" . _("Small caps markup inserted") . "</h2>\n" . _("Leave the insertion of the small caps markup for the formatting rounds."), "hints" => array());
$messages["comper"] = array("message_text" => "<h2>" . _("Scanno") . "</h2>\n" . _("You've missed a typical scanno: a comma (,) has been mis-recognised as a period (.)."), "hints" => array());
$messages["ellipsis"] = array("message_text" => "<h2>" . _("Ellipsis incorrectly proofread") . "</h2>\n" . _("Normalise the number of periods to three, and leave a space on either side of an ellipsis. The exception is at the beginning and end of a sentence, when you should leave no space."), "hints" => array());
$messages["decap"] = array("message_text" => "<h2>" . _("Small caps text decapitalised") . "</h2>\n" . _("Leave the case-correction of text in small caps to the formatting rounds."), "hints" => array());
$messages["longdash"] = array("message_text" => "<h2>" . _("Long dash incorrectly proofread") . "</h2>\n" . _("Match the number of dashes used in the proofread text to the size of the dash in the image."), "hints" => array());
$messages["tbe"] = array("message_text" => "<h2>" . _("Scanno") . "</h2>\n<p>" . _("You've missed one typical 'scanno' in the text: A 'h' mis-recognised as a 'b'.</p>\n"), "hints" => array());
$messages["cat"] = array("message_text" => "<h2>" . _("Scanno") . "</h2>\n<p>" . _("You've missed one typical 'scanno' in the text: An 'e' mis-recognised as a 'c'.</p>\n"), "hints" => array());
?>

