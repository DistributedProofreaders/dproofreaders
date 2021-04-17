<?php


$browser_title = _("Moderate Proofreading Quiz");
$intro_title = sprintf(_("Moderate Proofreading Quiz, page %d"), 8);
$initial_instructions = $initial_instructions__P;
$initial_text = "tween the quercus* and the olivet there exists a\nhatred so inveterate, that transplanted, either of\nthem, to a site previously occupied by the other,\nthey will die.I The quercus too, if planted near\nthe stoechas, will perish. There is a mortal feud\nexisting also between the cabbage and the vine;\n\n* See B. xi. c. 40. t See B. vii. cc. 5, 8, &c.\n\nI As Fee justly remarks, the greater part of these\nso-called sympathies and antipathies must be looked\nupon as so many fables.";
$solutions = ["*tween the quercus[*] and the olive[*] there exists a\nhatred so inveterate, that transplanted, either of\nthem, to a site previously occupied by the other,\nthey will die.[*] The quercus too, if planted near\nthe stœchas, will perish. There is a mortal feud\nexisting also between the cabbage and the vine;\n\n* See B. xi. c. 40.\n\n* See B. vii. cc. 5, 8, &c.\n\n* As Fée justly remarks, the greater part of these\nso-called sympathies and antipathies must be looked\nupon as so many fables."];

// error messages

$messages["accente"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: an accent over an '<kbd>e</kbd>' was omitted by the OCR software."),
];
$messages["ampchange"] = [
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please keep punctuation and symbols such as the ampersand (&amp;) as they appear in the original book."),
];
$messages["oescanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>oe</kbd>' in the text where there is an '<kbd>œ</kbd>' ('oe' ligature) in the image."),
];
$messages["fnscannot"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>t</kbd>' in the text where there is a footnote marker in the image. When footnotes are marked with a series of special characters (*, †, ‡, §, etc.) we replace them all with <kbd>[*]</kbd> in the text, and <kbd>*</kbd> next to the footnote itself."),
];
$messages["fnscannoI"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>I</kbd>' in the text where there is a footnote marker in the image. When footnotes are marked with a series of special characters (<kbd>*</kbd>, <kbd>†</kbd>, <kbd>‡</kbd>, <kbd>§</kbd>, etc.) we replace them all with <kbd>[*]</kbd> in the text, and <kbd>*</kbd> next to the footnote itself."),
];
$messages["fnspace"] = [
    "message_title" => _("Space around footnote"),
    "message_body" => _("Leave a blank line between the regular text and the footnotes to separate them."),
    "guideline" => "footnotes",
];
$messages["fnmarkermissing"] = [
    "message_title" => _("Footnote marker"),
    "message_body" => _("When footnotes are marked with a series of special characters (*, †, ‡, §, etc.) we replace them all with <kbd>[*]</kbd> in the text, and <kbd>*</kbd> next to the footnote itself."),
    "guideline" => "footnotes",
];
$messages["nobrackets"] = [
    "message_title" => _("Footnote marker brackets"),
    "message_body" => _("In the text, footnote markers should be in square brackets, like this: <kbd>[*]</kbd>."),
    "guideline" => "footnotes",
];
$messages["fnmarkerspace"] = [
    "message_title" => _("Footnote marker spacing"),
    "message_body" => _("Put the footnote marker right next to the word (or punctuation mark) being footnoted and leave a space on the other side of it, like this: <br><kbd>word[*] word</kbd>."),
    "guideline" => "footnotes",
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["Fee"], "case_sensitive" => true, "error" => "accente"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["*tween"], "case_sensitive" => true, "error" => "P_sophyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["etc"], "case_sensitive" => true, "error" => "ampchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["stoechas"], "case_sensitive" => true, "error" => "oescanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["olivet", "[t]", "t See"], "case_sensitive" => true, "error" => "fnscannot"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [".I", ".[I]", "I As"], "case_sensitive" => true, "error" => "fnscannoI"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["vine;\n*"], "case_sensitive" => true, "error" => "fnspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["40. *", "40.  *", "40.   *", "40.\n*"], "case_sensitive" => true, "error" => "P_fnspace2"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["Fée"], "case_sensitive" => true, "error" => "P_accentbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[oe]"], "case_sensitive" => true, "error" => "P_oe"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["†", "‡", chr(134), chr(135), "[A]", "[B]", "[C]", "[1]", "[**]"], "case_sensitive" => false, "error" => "fnmarkermissing"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["quercus*", "olive*", "die.*"], "case_sensitive" => true, "error" => "nobrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" [*]", "]and", "]there", "]The"], "case_sensitive" => true, "error" => "fnmarkerspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n[*]"], "case_sensitive" => true, "error" => "P_fnbottombrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["*See", "*As"], "case_sensitive" => true, "error" => "P_fnbottomnospace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Footnote"], "case_sensitive" => false, "error" => "P_fnmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n* See"], "case_sensitive" => true, "error" => "P_fnbottommarker"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n* As"], "case_sensitive" => true, "error" => "P_fnbottommarker"];
