<?php
include_once('quiz_defaults.inc'); // $utf8_site

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_mod2_3","pass");
}

$browser_title                = _("Moderate Proofreading Quiz");
$welcome                      = "<h2>" . _("Moderate Proofreading Quiz, page 8") . "</h2>\n" .
                                    "<p>" . _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.") . "</p>";
$ocr_text                     = "tween the quercus* and the olivet there exists a\nhatred so inveterate, that transplanted, either of\nthem, to a site previously occupied by the other,\nthey will die.I The quercus too, if planted near\nthe stoechas, will perish. There is a mortal feud\nexisting also between the cabbage and the vine;\n\n* See B. xi. c. 40. t See B. vii. cc. 5, 8, &c.\n\nI As Fee justly remarks, the greater part of these\nso-called sympathies and antipathies must be looked\nupon as so many fables.";
if(!$utf8_site) { 
    $solutions                = array("*tween the quercus[*] and the olive[*] there exists a\nhatred so inveterate, that transplanted, either of\nthem, to a site previously occupied by the other,\nthey will die.[*] The quercus too, if planted near\nthe st[oe]chas, will perish. There is a mortal feud\nexisting also between the cabbage and the vine;\n\n* See B. xi. c. 40.\n\n* See B. vii. cc. 5, 8, &c.\n\n* As Fée justly remarks, the greater part of these\nso-called sympathies and antipathies must be looked\nupon as so many fables.");
    $solved_message           = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("The &aelig; (ae ligature) is part of the Latin-1 character set, which we use when proofreading, so we can insert it directly into the proofreading interface.  On the other hand, the &oelig; (oe ligature) is not in Latin-1, so we use brackets instead: <tt>[oe]</tt>.  Later on during post-processing this will be replaced with the actual symbol or plain oe, depending on the situation.") . "</p>";
}
else {
    $solutions                = array("*tween the quercus[*] and the olive[*] there exists a\nhatred so inveterate, that transplanted, either of\nthem, to a site previously occupied by the other,\nthey will die.[*] The quercus too, if planted near\nthe stœchas, will perish. There is a mortal feud\nexisting also between the cabbage and the vine;\n\n* See B. xi. c. 40.\n\n* See B. vii. cc. 5, 8, &c.\n\n* As Fée justly remarks, the greater part of these\nso-called sympathies and antipathies must be looked\nupon as so many fables.");
    $solved_message           = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>";
}

// error messages

$messages["accente"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno in the text: an accent over an '<tt>e</tt>' was omitted by the OCR software.") . "</p>",
    "hints" => array(),
);
$messages["ampchange"] = array(
    "message_text" => "<h2>" . _("Primary Rule") . "</h2>\n" .
        "<p>" . _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please keep punctuation and symbols such as the ampersand (&amp;) as they appear in the original book.") . "</p>",
    "hints" => array(),
);
if(!$utf8_site) { 
    $messages["oescanno"] = array(
        "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
            "<p>" . _("There is still a scanno: '<tt>oe</tt>' in the text where there is an '&oelig;' ('oe' ligature) in the image.  Proofread the 'oe' ligature using brackets: <tt>[oe]</tt>.") . "</p>",
        "hints" => array(),
    );
    $messages["oelig"] = array(
        "message_text" => "<h2>" . _("Ligature") . "</h2>\n" .
            "<p>" . _("Proofread the 'oe' ligature (&oelig;) using brackets: <tt>[oe]</tt>, not the actual &oelig; character.") . "</p>",
        "hints" => array(),
        "guideline" => "a_chars",
    );
}
else {
    $messages["oescanno"] = array(
        "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
            "<p>" . _("There is still a scanno: '<tt>oe</tt>' in the text where there is an '&oelig;' ('oe' ligature) in the image.") . "</p>",
        "hints" => array(),
    );
}
$messages["fnscannot"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>t</tt>' in the text where there is a footnote marker in the image. When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;, etc.) we replace them all with <tt>[*]</tt> in the text, and <tt>*</tt> next to the footnote itself.") . "</p>",
    "hints" => array(),
);
$messages["fnscannoI"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an '<tt>I</tt>' in the text where there is a footnote marker in the image. When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;, etc.) we replace them all with <tt>[*]</tt> in the text, and <tt>*</tt> next to the footnote itself.") . "</p>",
    "hints" => array(),
);
$messages["fnspace"] = array(
    "message_text" => "<h2>" . _("Space around footnote") . "</h2>\n" .
        "<p>" . _("Leave a blank line between the regular text and the footnotes to separate them.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes",
);
$messages["fnmarkermissing"] = array(
    "message_text" => "<h2>" . _("Footnote marker") . "</h2>\n" .
        "<p>" . _("When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;, etc.) we replace them all with <tt>[*]</tt> in the text, and <tt>*</tt> next to the footnote itself.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes",
);
$messages["nobrackets"] = array(
    "message_text" => "<h2>" . _("Footnote marker brackets") . "</h2>\n" .
        "<p>" . _("In the text, footnote markers should be in square brackets, like this: <tt>[*]</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes",
);
$messages["fnmarkerspace"] = array(
    "message_text" => "<h2>" . _("Footnote marker spacing") . "</h2>\n" .
        "<p>" . _("Put the footnote marker right next to the word (or punctuation mark) being footnoted and leave a space on the other side of it, like this: <br><tt>word[*] word</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes",
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Fee"), "case_sensitive" => TRUE, "error" => "accente");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("*tween"), "case_sensitive" => TRUE, "error" => "P_sophyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("etc"), "case_sensitive" => TRUE, "error" => "ampchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("stoechas"), "case_sensitive" => TRUE, "error" => "oescanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("olivet", "[t]", "t See"), "case_sensitive" => TRUE, "error" => "fnscannot");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(".I", ".[I]", "I As"), "case_sensitive" => TRUE, "error" => "fnscannoI");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("vine;\n*"), "case_sensitive" => TRUE, "error" => "fnspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("40. *", "40.  *", "40.   *", "40.\n*"), "case_sensitive" => TRUE, "error" => "P_fnspace2");
if(!$utf8_site) { 
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[/e]", "['e]"), "case_sensitive" => TRUE, "error" => "P_accentbracket");
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("œ", chr(156)), "case_sensitive" => TRUE, "error" => "oelig");
}
else {
    $tests[] = array("type" => "expectedtext", "searchtext" =>  array("Fée"), "case_sensitive" => TRUE, "error" => "P_accentbracket");
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[oe]"), "case_sensitive" => TRUE, "error" => "P_oe");
}
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("†", "‡", chr(134), chr(135), "[A]", "[B]", "[C]", "[1]", "[**]"), "case_sensitive" => FALSE, "error" => "fnmarkermissing");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("quercus*", "olive*", "die.*"), "case_sensitive" => TRUE, "error" => "nobrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" [*]", "]and", "]there", "]The"), "case_sensitive" => TRUE, "error" => "fnmarkerspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n[*]"), "case_sensitive" => TRUE, "error" => "P_fnbottombrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("*See", "*As"), "case_sensitive" => TRUE, "error" => "P_fnbottomnospace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Footnote"), "case_sensitive" => FALSE, "error" => "P_fnmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n* See"), "case_sensitive" => TRUE, "error" => "P_fnbottommarker");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n* As"), "case_sensitive" => TRUE, "error" => "P_fnbottommarker");

// vim: sw=4 ts=4 expandtab
