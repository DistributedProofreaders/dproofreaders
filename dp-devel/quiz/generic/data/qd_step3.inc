<?php
include_once($relPath."Quiz.inc");

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"step3","pass");
}

$browser_title                = _("DP -- Proofreading Quiz");
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$ocr_text                     = "repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\nDEAREST mother, this is indeed\nlike some of\nOakwood's happy hours, \" exclaimed\nEmmeline , that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, arid raised her laughing eyes";
$solutions                    = array("repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\n\"DEAREST mother, this is indeed\nlike some of\nOakwood's happy hours,\" exclaimed\nEmmeline, that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, and raised her laughing eyes");
$showsolution                 = TRUE;
$welcome                      = "<h2>" . _("Quiz, Part 3") . "</h2>" . _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.");
$solved_message               = "<h2>" . _("Part 3 of quiz successfully solved") . "</h2>" . _("Congratulations, no errors found!");
$links_out                    = sprintf("<a href='../generic/main.php?type=step4&quiz_id=PQ' target='_top'>%s</a><br><a href='../start.php' target='_top'>%s</a>", _("Next step of quiz"), _("Quizzes home."));


// error messages
$messages["spquote"] = array(
    "message_text" => "<h2>" . _("Spaced double quote") . "</h2>" .
        _("You've left a closing double quote with a space before it in the text."),
    "hints" => array()
);
$messages["arid"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>" .
        _("You've missed one typical 'scanno' in the text. A 'n' mis-read as 'ri'."),
    "hints" => array(
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
            _("Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.")
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
            _("You are looking for an occurrence of 'ri' that is wrong. There is only one word with 'ri' in the text. Once you've found it you will immediately know it is wrong.")
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
            _("If you can't find any word with 'ri', consider copying the text into an editor and searching for 'ri'. You'll get a result, guaranteed!")
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>" .
            _("No, we won't give away the solution, after all this is a quiz!")
        )
    )
);
$messages["numberinheader"] = array(
    "message_text" => "<h2>" . _("Number of blank lines within chapter header section incorrect") . "</h2>" .
        _("There should be 1 blank line between different parts of the chapter header."),
    "hints" => array()
);
$messages["spcomma"] = array(
    "message_text" => "<h2>" . _("Spaced comma") . "</h2>" .
        _("You've left a comma with a space before it in the text."),
    "hints" => array()
);
$messages["longline"] = array(
    "message_text" => "<h2>" . _("Long line") . "</h2>" .
        _("You've probably joined two lines by deleting a line break. If you join words around hyphens or dashes, move only one word up to the end of the previous line."),
    "hints" => array()
);
$messages["missingquote"] = array(
    "message_text" => "<h2>" . _("Double quote missing") . "</h2>" .
        _("It seems you haven't added a double quote at the beginning of the new chapter. Since from the context one can see there should be a double quote starting that sentence and this is only missing for typesetting reasons we insert one there."),
    "hints" => array()
);
$messages["decappedchap"] = array(
    "message_text" => "<h2>" . _("First word of chapter decapitalised") . "</h2>" .
        _("Leave this to the formatting rounds, and concentrate on making sure that the text is correct."),
    "hints" => array()
);
$messages["spacedchap"] = array(
    "message_text" => "<h2>" . _("Chapter heading spaced") . "</h2>" .
        _("Leave the formatting of the chapter heading to the formatting rounds, and leave a single blank line before and after each part of the heading."),
    "hints" => array()
);


// error checks
$tests[] = array("type" => "expectedtext", "searchtext" => array("\"DE"), "case_sensitive" => FALSE, "error" => "missingquote");
$tests[] = array("type" => "forbiddentext", "searchtext" => ", \"", "case_sensitive" => FALSE, "error" => "spquote");
$tests[] = array("type" => "forbiddentext", "searchtext" => " ,", "case_sensitive" => FALSE, "error" => "spcomma");
$tests[] = array("type" => "forbiddentext", "searchtext" => "arid", "case_sensitive" => FALSE, "error" => "arid");
$tests[] = array("type" => "longline", "lengthlimit" => 60, "error" => "longline");
$tests[] = array("type" => "forbiddentext", "searchtext" => "Dearest", "case_sensitive" => true, "error" => "decappedchap");
$tests[] = array("type" => "expectedtext", "searchtext" => array("agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\n\""), "case_sensitive" => FALSE, "error" => "spacedchap");

// vim: sw=4 ts=4 expandtab
