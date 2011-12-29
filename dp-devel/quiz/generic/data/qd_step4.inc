<?php
include_once($relPath."Quiz.inc");

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"step4","pass");
}

$browser_title                = _("DP -- Proofreading Quiz");
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$ocr_text                     = "printing would be good for nothing but\nwaste paper, might not\nbe realised. The work\nappeared about the end\nof December 18|8 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where lie revised the\nfinal proofs.\n\nHigh art.\n\nSo far as the reception of the work was\n\nWallace, p. 108.";
$solutions                    = array("printing would be good for nothing but\nwaste paper, might not\nbe realised.[*] The work\nappeared about the end\nof December 1818 with\n1819 on the title-page.\nSchopenhauer had\nmeanwhile proceeded in\nSeptember to Italy, where he revised the\nfinal proofs.\n\nHigh art.\n\nSo far as the reception of the work was\n\n* Wallace, p. 108.");
$showsolution                 = TRUE;
$welcome                      = "<h2>" . _("Quiz, Part 4") . "</h2>" . _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.");
$solved_message               = "<h2>" . _("Part 4 of quiz successfully solved") . "</h2>" . _("Congratulations, no errors found!");
$links_out                    = sprintf("<a href='../generic/main.php?type=step5&quiz_id=PQ' target='_top'>%s</a><br><a href='../start.php' target='_top'>%s</a>", _("Next step of quiz"), _("Quizzes home."));


// error messages
$messages["sqbr"] = array(
    "message_text" => "<h2>" . _("Incorrect brackets.") . "</h2>" .
        _("Please use square brackets [] for illustration and footnote markup."),
    "hints" => array()
);
$messages["longline"] = array(
    "message_text" => "<h2>" . _("Long line") . "</h2>" .
        _("You've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line."),
    "hints" => array()
);
$messages["fnmarkermissing"] = array(
    "message_text" => "<h2>" . _("Footnote marker missing") . "</h2>" .
        _("It seems you haven't inserted the required footnote marker. The marker should be in square brackets, and have its symbol replaced with an *."),
    "hints" => array()
);
$messages["fnmarkerother"] = array(
    "message_text" => "<h2>" . _("Problem with footnote marker.") . "</h2>" .
        _("Something not exactly detectable doesn't seem to be right with the footnote marker. The line containing it should look like this:<br><tt>be realised.[*] The work</tt>"),
    "hints" => array()
);
$messages["fnmarkerwrong"] = array(
    "message_text" => "<h2>" . _("Footnote marker wrong.") . "</h2>" .
        _("The footnote marker should look like this: <tt>[*]</tt>."),
    "hints" => array()
);
$messages["fnpos"] = array(
    "message_text" => "<h2>" . _("Footnote at wrong position.") . "</h2>" .
        _("It seems you have moved the footnote. Please leave it at the end of the page."),
    "hints" => array()
);
$messages["lie"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>" .
        _("You've missed one typical 'scanno' in the text. An 'h' mis-read as 'li'."),
    "hints" => array(array("hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
        "<p>" . _("Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.") .
        "<p>" . _("You are looking for an occurrence of 'li' that is wrong. There are only 2 words with 'li' in the text. Once you've found them you will immediately know which one is the culprit.") .
        "<p>" . _("If you can only find 1 word with a 'li', consider copying the text into an editor and searching for 'li'. You'll get 2 results, guaranteed!") .
        "<p>" . _("No, we won't give away the solution, after all this is a quiz!")))
);
$messages["nocaption"] = array(
    "message_text" => "<h2>" . _("Illustration caption missing.") . "</h2>" .
        _("It seems you haven't included the illustration caption. Put the illustration caption within [Illustration: ]"),
    "hints" => array()
);
$messages["footmarked"] = array(
    "message_text" => "<h2>" . _("Footnote markup inserted.") . "</h2>" .
        _("Leave the insertion of the footnote markup for the formatting rounds."),
    "hints" => array()
);
$messages["datescanno"] = array(
    "message_text" => "<h2>" . _("Scanno.") . "</h2>" .
        _("You've missed a scanno in the text: a 1 misread as a |."),
    "hints" => array()
);
$messages["illusmarked"] = array(
    "message_text" => "<h2>" . _("Illustration markup inserted.") . "</h2>" .
        _("Leave the insertion of the illustration markup for the formatting rounds."),
    "hints" => array()
);
$messages["fnbottommissing"] = array(
    "message_text" => "<h2>" . _("Footnote marker missing at footnote text") . "</h2>" .
        _("Precede the footnote text with the same mark you used in the text, without square brackets."),
    "hints" => array()
);
$messages["fnbottomnospace"] = array(
    "message_text" => "<h2>" . _("Footnote marker not spaced footnote text") . "</h2>" .
        _("Precede the footnote text with the same mark you used in the text, without square brackets but <b>with a space between them</b>."),
    "hints" => array()
);


// error checks
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
$tests[] = array("type" => "forbiddentext", "searchtext" => "*Wallace", "case_sensitive" => FALSE, "error" => "fnbottomnospace");
$tests[] = array("type" => "expectedtext", "searchtext" => array("* Wallace"), "case_sensitive" => FALSE, "error" => "fnbottommissing");

// vim: sw=4 ts=4 expandtab
