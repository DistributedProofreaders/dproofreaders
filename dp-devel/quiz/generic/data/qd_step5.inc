<?php
include_once($relPath."Quiz.inc");

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"step5","pass");
}

$browser_title                = _("DP -- Proofreading Quiz");
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$ocr_text                     = "We ask ourselves how Byron's poem\nYou have the Pyrrhic dance as yet,\nWhere is the Pyrrhic phalanx\ngone?\nOf two such lessons, why forget\nThe nobler and the manlier one?\nis related to these well known words:\n\nWhen in the course of human events, it\nbecomes necessary for one people to dissolve the\npolitical hands which have connected ...\n\nNot at all. we suspect.";
$solutions                    = array("We ask ourselves how Byron's poem\n\nYou have the Pyrrhic dance as yet,\nWhere is the Pyrrhic phalanx\ngone?\nOf two such lessons, why forget\nThe nobler and the manlier one?\n\nis related to these well known words:\n\nWhen in the Course of human events, it\nbecomes necessary for one people to dissolve the\npolitical bands which have connected....\n\nNot at all, we suspect.");
$showsolution                 = TRUE;
$welcome                      = "<h2>" . _("Quiz, Part 5") . "</h2>" . _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.");
$solved_message               = "<h2>" . _("Part 5 of quiz successfully solved") . "</h2>" . _("Congratulations, no errors found!");
$links_out                    = sprintf("<a href='../start.php' target='_top'>%s</a>", _("Quizzes home."));


// error messages
$messages["hands"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>" .
        _("You've missed one typical 'scanno' in the text. A 'b' mis-read as an 'h'."),
    "hints" => array(
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
            _("Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.")
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
            _("You are looking for an 'h' that is wrong. There are 21 words with an 'h' in the text. Two of those words would also make sense if you replaced the 'h' by a 'b'. Check them with the original and you'll know.")
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
            _("If you can't find all 21 words with an 'h', consider copying the text into an editor and searching for 'h'.")
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
            _("No, we won't give away the solution, after all this is a quiz!")
        )
    )
);
$messages["boldfont"] = array(
    "message_text" => "<h2>" . _("Font change marked as bold") . "</h2>" .
        _("You've marked a change in font as bold. In general, ignore changes in font or font size."),
    "hints" => array()
);
$messages["markup"] = array(
    "message_text" => "<h2>" . _("Markup inserted") . "</h2>" .
        _("Leave markup insertion for the formatting rounds."),
    "hints" => array()
);
$messages["joinword"] = array(
    "message_text" => "<h2>" . _("Long poetry line joined") . "</h2>" .
        _("Leave the joining of long poetry lines to the formatting rounds"),
    "hints" => array()
);
$messages["commaperiod"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>" .
        _("A comma has been misread as a period."),
    "hints" => array()
);
$messages["blanklines"] = array(
    "message_text" => "<h2>" . _("Not enough space around poetry") . "</h2>" .
        _("Leave a blank line before and after a block of poetry."),
    "hints" => array()
);
$messages["capitalmissed"] = array(
    "message_text" => "<h2>" . _("Uncapitalised word") . "</h2>" .
        _("A capital letter has been read as lowercase by the OCR."),
    "hints" => array()
);
$messages["ellipsis"] = array(
    "message_text" => "<h2>" . _("Spaced Ellipsis") . "</h2>" .
        _("At the end of a sentence an ellipsis should have four dots, or three dots plus a '<tt>!</tt>' or '<tt>?</tt>', and no space before it."),
    "hints" => array()
);


// error checks
$tests[] = array("type" => "forbiddentext", "searchtext" => "<b>Byron's</b>", "case_sensitive" => TRUE, "error" => "boldfont");
$tests[] = array("type" => "expectedtext", "searchtext" => array("bands"), "case_sensitive" => FALSE, "error" => "hands");
$tests[] = array("type" => "forbiddentext", "searchtext" => "/*", "case_sensitive" => TRUE, "error" => "markup");
$tests[] = array("type" => "forbiddentext", "searchtext" => "/#", "case_sensitive" => TRUE, "error" => "markup");
$tests[] = array("type" => "expectedtext", "searchtext" => array("phalanx\n"), "case_sensitive" => FALSE, "error" => "joinword");
$tests[] = array("type" => "expectedtext", "searchtext" => array("at all,"), "case_sensitive" => FALSE, "error" => "commaperiod");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n\nYou have"), "case_sensitive" => FALSE, "error" => "blanklines");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n\nis related"), "case_sensitive" => FALSE, "error" => "blanklines");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n\nWhen "), "case_sensitive" => FALSE, "error" => "blanklines");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\n\nNot at"), "case_sensitive" => FALSE, "error" => "blanklines");
$tests[] = array("type" => "expectedtext", "searchtext" => array("the Course"), "case_sensitive" => true, "error" => "capitalmissed");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" ...", "ed...\n"), "case_sensitive" => TRUE, "error" => "ellipsis");

// vim: sw=4 ts=4 expandtab
