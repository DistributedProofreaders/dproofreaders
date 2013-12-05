<?php
include_once($relPath."Quiz.inc");

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"formatting3","pass");
}

$browser_title                = _("Formatting Quiz");
$intro_title                  = "<h2>" . _("Formatting Quiz, page 3") . "</h2>";
$initial_instructions         = _("Try to correct the text.");
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$ocr_text                     = "Chapter 8\n\nTHE MERMAIDS' LAGOON\nIn which our heroes discover the perils of ignoring thought breaks.\n\nIF YOU SHUT your eyes and are a lucky one, you may see\n\nat times a shapeless pool of lovely pale colours\nsuspended in the darkness; then if you squeeze your\neyes tighter, the pool begins to take shape, and the\ncolours become so vivid that with another squeeze they\nmust go on fire. But just before they go on fire you see\nthe lagoon.\n\nThe children often spent long summer days on this\nlagoon, swimming or floating most of the time, playing\nthe mermaid games in the water, and so forth. You";
$solutions                    = array("\n\n\n\nChapter 8\n\nTHE MERMAIDS' LAGOON\n\nIn which our heroes discover the perils of ignoring thought breaks.\n\n\nIf you shut your eyes and are a lucky one, you may see\nat times a shapeless pool of lovely pale colours\nsuspended in the darkness; then if you squeeze your\neyes tighter, the pool begins to take shape, and the\ncolours become so vivid that with another squeeze they\nmust go on fire. But just before they go on fire you see\nthe lagoon.\n\n<tb>\n\nThe children often spent long summer days on this\nlagoon, swimming or floating most of the time, playing\nthe mermaid games in the water, and so forth. You");
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>" ._("Congratulations, no errors found!");


// error messages
$messages["notfour"] = array(
    "message_text" => "<h2>" . _("Number of blank lines before chapter heading incorrect") . "</h2>" .
        _("There should be 4 blank lines before the chapter heading."),
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["nottwo"] = array(
    "message_text" => "<h2>" . _("Number of blank lines between chapter heading and text incorrect") . "</h2>" .
        _("There should be 2 blank lines before the start of the regular paragraph text."),
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["numberinheader"] = array(
    "message_text" => "<h2>" . _("Number of blank lines within chapter heading incorrect") . "</h2>" .
        _("There should be 1 blank line between different parts of the chapter heading."),
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["extraline"] = array(
    "message_text" => "<h2>" . _("Extra blank line") . "</h2>" .
        _("An extra blank line has been accidentally inserted by the proofreaders. Remove this line to match the image."),
    "hints" => array()
);
$messages["extratb"] = array(
    "message_text" => "<h2>" . _("Extra thought break markup") . "</h2>" .
        _("There is only one thought break (<tt>&lt;tb&gt;</tt>) on the page.  Separate the chapter heading from the regular text using blank lines, not thought break markup."),
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["nothoughtbreak"] = array(
    "message_text" => "<h2>" . _("Missing thought break") . "</h2>" .
        _("A thought break (<tt>&lt;tb&gt;</tt>) should be placed between the paragraphs that have a break between them."),
    "hints" => array(),
    "guideline" => "extra_s"
);
$messages["thoughtbreakspc"] = array(
    "message_text" => "<h2>" . _("Incorrect thought break spacing") . "</h2>" .
        _("A thought break (<tt>&lt;tb&gt;</tt>) should have a blank line before and after it."),
    "hints" => array(),
    "guideline" => "extra_s"
);
$messages["nobold"] = array(
    "message_text" => "<h2>" . _("Bold markup in heading") . "</h2>" .
        _("Chapter Headers may be printed in a different or larger font that may appear to be bold, but we do not mark them as bold."),
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["chapheadnotdecapped"] = array(
    "message_text" => "<h2>" . _("Chapter start not decapitalised") . "</h2>" .
        _("Change the case of the first words of a chapter to match the rest of the text. Printing the first word(s) of a chapter in all caps or small caps is just a typesetting convention."),
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["chapheadsc"] = array(
    "message_text" => "<h2>" . _("Small caps markup added to chapter start") . "</h2>" .
        _("Printing the first word(s) of a chapter in all caps or small caps is just a typesetting convention, so instead of inserting small caps markup, simply change the case to match the rest of the text."),
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["chapheadoverkill"] = array(
    "message_text" => "<h2>" . _("Chapter start completely decapitalised") . "</h2>" .
        _("You've got the right idea, but gone a little too far. Leave the first letter capitalised, as it's at the start of the sentence."),
    "hints" => array(),
    "guideline" => "chap_head"
);


// error checks
$tests[] = array("type" => "forbiddentext", "searchtext" => "IF YOU SHUT", "case_sensitive" => TRUE, "error" => "chapheadnotdecapped");
$tests[] = array("type" => "forbiddentext", "searchtext" => "if you shut", "case_sensitive" => true, "error" => "chapheadoverkill");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  "breaks.\n\n<tb>", "case_sensitive" => TRUE, "error" => "extratb");
$tests[] = array("type" => "multioccurrence", "searchtext" =>  "<tb>", "case_sensitive" => TRUE, "error" => "extratb");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  "<b>", "case_sensitive" => TRUE, "error" => "nobold");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n\n\nChapter"), "case_sensitive" => TRUE, "error" => "notfour");
$tests[] = array("type" => "expectedlinebreaks", "number" => 3, "starttext" => "breaks.", "stoptext" => "If", "case_sensitive" => FALSE, "errorhigh" => "nottwo", "errorlow" => "nottwo");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "LAGOON", "stoptext" => "In which", "case_sensitive" => FALSE, "errorhigh" => "numberinheader", "errorlow" => "numberinheader");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "8", "stoptext" => "THE", "case_sensitive" => FALSE, "errorhigh" => "numberinheader", "errorlow" => "numberinheader");
$tests[] = array("type" => "forbiddentext", "searchtext" => "\n\nat times", "case_sensitive" => TRUE, "error" => "extraline");
$tests[] = array("type" => "forbiddentext", "searchtext" => "<sc>", "case_sensitive" => false, "error" => "chapheadsc");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("<tb>"), "case_sensitive" => TRUE, "error" => "nothoughtbreak");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\n<tb>\n\n"), "case_sensitive" => TRUE, "error" => "thoughtbreakspc");

// vim: sw=4 ts=4 expandtab
