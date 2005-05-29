<?PHP

include_once($relPath."Quiz.inc");
function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"formatting3","pass");
}

$browser_title                = "Formatting Quiz";
$welcome                      = "<h2>Formatting Quiz, page 3</h2> Try to correct the text.";
$ocr_text                     = "Chapter 8\n\nTHE MERMAIDS' LAGOON\nIn which our heroes discover the perils of ignoring thought breaks.\n\nIf you shut your eyes and are a lucky one, you may see\n\nat times a shapeless pool of lovely pale colours\nsuspended in the darkness; then if you squeeze your\neyes tighter, the pool begins to take shape, and the\ncolours become so vivid that with another squeeze they\nmust go on fire. But just before they go on fire you see\nthe lagoon.\n\nThe children often spent long summer days on this\nlagoon, swimming or floating most of the time, playing\nthe mermaid games in the water, and so forth. You";
$solutions                    = array("\n\n\n\nChapter 8\n\nTHE MERMAIDS' LAGOON\n\nIn which our heroes discover the perils of ignoring thought breaks.\n\n\nIf you shut your eyes and are a lucky one, you may see\nat times a shapeless pool of lovely pale colours\nsuspended in the darkness; then if you squeeze your\neyes tighter, the pool begins to take shape, and the\ncolours become so vivid that with another squeeze they\nmust go on fire. But just before they go on fire you see\nthe lagoon.\n\n       *       *       *       *       *\n\nThe children often spent long summer days on this\nlagoon, swimming or floating most of the time, playing\nthe mermaid games in the water, and so forth. You");
$showsolution                 = TRUE;
$solved_message               = "<h2>Quiz successfully solved</h2> Congratulations, no errors found!";
$links_out                    = "<a href='../generic/main.php?type=formatting4&quiz_id=FQ' target='_top'>Next step of quiz</a><br /><a href='../start.php' target='_top'>Quizzes home.</a>";


// error messages

$messages["notfour"] = array("message_text" => "<h2>Number of blank lines before chapter header incorrect</h2>\nThere should be 4 blank lines before the chapter header.", "hints" => array());
$messages["nottwo"] = array("message_text" => "<h2>Number of blank lines between chapter header section and text incorrect</h2>\nThere should be 2 blank lines before the start of the text.", "hints" => array());
$messages["numberinheader"] = array("message_text" => "<h2>Number of blank lines within chapter header section incorrect</h2>\nThere should be 1 blank line between different parts of the chapter header.", "hints" => array());
$messages["extraline"] = array("message_text" => "<h2>Extra blank line</h2> An extra blank line has been accidentally inserted by the proofreaders. Remove this line to match the image.", "hints" => array());
$messages["nothoughtbreak"] = array("message_text" => "<h2>Missing thought break</h2> A thought break should be placed between the paragraphs that have a break between them. (Normally you can copy this from the toolbar in the proofreading interface, but here is one you can copy for now: <pre>       *       *       *       *       *</pre>).", "hints" => array());
$messages["thoughtbreakspc"] = array("message_text" => "<h2>Incorrect thought break spacing</h2> A thought break should have a blank line before and after it.", "hints" => array());

// error checks
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n\n\nChapter"), "case_sensitive" => TRUE, "error" => "notfour");
$tests[] = array("type" => "expectedlinebreaks", "number" => 3, "starttext" => "breaks.", "stoptext" => "If", "case_sensitive" => FALSE, "errorhigh" => "nottwo", "errorlow" => "nottwo");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "LAGOON", "stoptext" => "In which", "case_sensitive" => FALSE, "errorhigh" => "numberinheader", "errorlow" => "numberinheader");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "8", "stoptext" => "THE", "case_sensitive" => FALSE, "errorhigh" => "numberinheader", "errorlow" => "numberinheader");
$tests[] = array("type" => "forbiddentext", "searchtext" => "\n\nat times", "case_sensitive" => TRUE, "error" => "extraline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("       *       *       *       *       *"), "case_sensitive" => TRUE, "error" => "nothoughtbreak");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n       *       *       *       *       *\n\n"), "case_sensitive" => TRUE, "error" => "thoughtbreakspc");

?>
