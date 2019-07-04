<?php
include_once('quiz_defaults.inc');


$browser_title                = _("Moderate Proofreading Quiz");
$intro_title                  = sprintf(_("Moderate Proofreading Quiz, page %d"), 8);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "tween the quercus* and the olivet there exists a\nhatred so inveterate, that transplanted, either of\nthem, to a site previously occupied by the other,\nthey will die.I The quercus too, if planted near\nthe stoechas, will perish. There is a mortal feud\nexisting also between the cabbage and the vine;\n\n* See B. xi. c. 40. t See B. vii. cc. 5, 8, &c.\n\nI As Fee justly remarks, the greater part of these\nso-called sympathies and antipathies must be looked\nupon as so many fables.";
$solutions                    = array("*tween the quercus[*] and the olive[*] there exists a\nhatred so inveterate, that transplanted, either of\nthem, to a site previously occupied by the other,\nthey will die.[*] The quercus too, if planted near\nthe stœchas, will perish. There is a mortal feud\nexisting also between the cabbage and the vine;\n\n* See B. xi. c. 40.\n\n* See B. vii. cc. 5, 8, &c.\n\n* As Fée justly remarks, the greater part of these\nso-called sympathies and antipathies must be looked\nupon as so many fables.");

// error messages

$messages["accente"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: an accent over an '<tt>e</tt>' was omitted by the OCR software."),
);
$messages["ampchange"] = array(
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please keep punctuation and symbols such as the ampersand (&amp;) as they appear in the original book."),
);
$messages["oescanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<tt>oe</tt>' in the text where there is an '&oelig;' ('oe' ligature) in the image."),
);
$messages["fnscannot"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<tt>t</tt>' in the text where there is a footnote marker in the image. When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;, etc.) we replace them all with <tt>[*]</tt> in the text, and <tt>*</tt> next to the footnote itself."),
);
$messages["fnscannoI"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>I</tt>' in the text where there is a footnote marker in the image. When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;, etc.) we replace them all with <tt>[*]</tt> in the text, and <tt>*</tt> next to the footnote itself."),
);
$messages["fnspace"] = array(
    "message_title" => _("Space around footnote"),
    "message_body" => _("Leave a blank line between the regular text and the footnotes to separate them."),
    "guideline" => "footnotes",
);
$messages["fnmarkermissing"] = array(
    "message_title" => _("Footnote marker"),
    "message_body" => _("When footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;, etc.) we replace them all with <tt>[*]</tt> in the text, and <tt>*</tt> next to the footnote itself."),
    "guideline" => "footnotes",
);
$messages["nobrackets"] = array(
    "message_title" => _("Footnote marker brackets"),
    "message_body" => _("In the text, footnote markers should be in square brackets, like this: <tt>[*]</tt>."),
    "guideline" => "footnotes",
);
$messages["fnmarkerspace"] = array(
    "message_title" => _("Footnote marker spacing"),
    "message_body" => _("Put the footnote marker right next to the word (or punctuation mark) being footnoted and leave a space on the other side of it, like this: <br><tt>word[*] word</tt>."),
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
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Fée"), "case_sensitive" => TRUE, "error" => "P_accentbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[oe]"), "case_sensitive" => TRUE, "error" => "P_oe");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("†", "‡", chr(134), chr(135), "[A]", "[B]", "[C]", "[1]", "[**]"), "case_sensitive" => FALSE, "error" => "fnmarkermissing");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("quercus*", "olive*", "die.*"), "case_sensitive" => TRUE, "error" => "nobrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" [*]", "]and", "]there", "]The"), "case_sensitive" => TRUE, "error" => "fnmarkerspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n[*]"), "case_sensitive" => TRUE, "error" => "P_fnbottombrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("*See", "*As"), "case_sensitive" => TRUE, "error" => "P_fnbottomnospace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Footnote"), "case_sensitive" => FALSE, "error" => "P_fnmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n* See"), "case_sensitive" => TRUE, "error" => "P_fnbottommarker");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n* As"), "case_sensitive" => TRUE, "error" => "P_fnbottommarker");

// vim: sw=4 ts=4 expandtab
