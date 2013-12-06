<?php

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_mod2_5","pass");
}

$browser_title                = _("Moderate Proofreading Quiz");
$intro_title                  = _("Moderate Proofreading Quiz, page 10");
$initial_instructions         = $initial_instructions__P;
$ocr_text                     = "don't spare him in the slightest!    ,\n\nChrys. (virtuously indignant) Is it enough, if he\nhears mere hard words from me this day than\never Clinia2 heard from Demetrius?2\n\n[EXIT.\n\nNIC. (ruefully) That servant of mine is very\nmuch like a sore eye : if you haven't got one,\nyou don't want one and don't miss it; if you\nhave, you can't keep your hands off it. Why, if\nhe hadn 't happened by good luck to be here to-\n\n^2 Characters in some familiar play.";
$solutions                    = array("don't spare him in the slightest!\n\nChrys. (virtuously indignant) Is it enough, if he\nhears more hard words from me this day than\never Clinia[2] heard from Demetrius?[2]\n\n[EXIT.\n\nNIC. (ruefully) That servant of mine is very\nmuch like a sore eye: if you haven't got one,\nyou don't want one and don't miss it; if you\nhave, you can't keep your hands off it. Why, if\nhe hadn't happened by good luck to be here to-*\n\n2 Characters in some familiar play.");
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Be sure to read the Project Comments and project discussion before starting to work on a project.  There may be exceptions to the regular Proofreading Guidelines, or helpful information that will make proofreading the text easier for you.") . "</p>";


// error messages

$messages["mere"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an '<tt>e</tt>' in the text where there is an '<tt>o</tt>' in the image.") . "</p>",
);
$messages["flyspeck"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a comma (<tt>,</tt>) in the text where there is a stray mark in the image.") . "</p>",
);
$messages["bracketremove"] = array(
    "message_text" => "<h2>" . _("Bracket removed") . "</h2>\n" .
        "<p>" . _("Leave the punctuation as the author wrote it, including any brackets around stage directions.") . "</p>",
    "guideline" => "play_n",
);
$messages["bracketinsert"] = array(
    "message_text" => "<h2>" . _("Bracket inserted") . "</h2>\n" .
        "<p>" . _("Leave the punctuation for stage directions as the author wrote it.  There is no need to insert a closing bracket \"]\" when there is none in the original.") . "</p>",
    "guideline" => "play_n",
);
$messages["fnsuper"] = array(
    "message_text" => "<h2>" . _("Footnote marker") . "</h2>\n" .
        "<p>" . _("There is no need for superscript markup for footnote markers.  Instead, the marker in the text should be surrounded by square brackets, like this: <tt>[2]</tt>.") . "</p>",
    "guideline" => "footnotes",
);
$messages["nobrackets"] = array(
    "message_text" => "<h2>" . _("Footnote marker brackets") . "</h2>\n" .
        "<p>" . _("In the text, footnote markers should be in square brackets, like this: <tt>[2]</tt>.") . "</p>",
    "guideline" => "footnotes",
);
$messages["fnmarkerspace"] = array(
    "message_text" => "<h2>" . _("Footnote marker spacing") . "</h2>\n" .
        "<p>" . _("Put the footnote marker right next to the word (or punctuation mark) being footnoted and leave a space on the other side of it, like this: <br><tt>word[2] word</tt>.") . "</p>",
    "guideline" => "footnotes",
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("mere"), "case_sensitive" => TRUE, "error" => "mere");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(",\n\n"), "case_sensitive" => TRUE, "error" => "flyspeck");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("to-*"), "case_sensitive" => TRUE, "error" => "P_eophyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\""), "case_sensitive" => TRUE, "error" => "P_addedquotes");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" :"), "case_sensitive" => TRUE, "error" => "P_colonspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\nEXIT"), "case_sensitive" => FALSE, "error" => "bracketremove");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("EXIT.]"), "case_sensitive" => FALSE, "error" => "bracketinsert");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("hadn 't"), "case_sensitive" => TRUE, "error" => "P_contracspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("to-*\n2"), "case_sensitive" => TRUE, "error" => "P_fnspace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("2"), "case_sensitive" => TRUE, "error" => "P_fnmarkermissing");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("a^2", "?^2"), "case_sensitive" => TRUE, "error" => "fnsuper");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n^2"), "case_sensitive" => TRUE, "error" => "P_fnbottomsuper");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Clinia2", "?2", "Clinia 2", "? 2"), "case_sensitive" => TRUE, "error" => "nobrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" [2]", "]heard"), "case_sensitive" => TRUE, "error" => "fnmarkerspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n[2"), "case_sensitive" => TRUE, "error" => "P_fnbottombrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("2Ch"), "case_sensitive" => TRUE, "error" => "P_fnbottomnospace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Footnote"), "case_sensitive" => FALSE, "error" => "P_fnmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n2 Ch"), "case_sensitive" => TRUE, "error" => "P_fnbottommarker");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<sc>", "</sc>"), "case_sensitive" => FALSE, "error" => "P_scmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("NIC"), "case_sensitive" => TRUE, "error" => "P_casechange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Chrys"), "case_sensitive" => TRUE, "error" => "P_casechange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("EXIT"), "case_sensitive" => TRUE, "error" => "P_casechange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "P_indent");

// vim: sw=4 ts=4 expandtab
