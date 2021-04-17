<?php


$browser_title = _("Moderate Proofreading Quiz");
$intro_title = sprintf(_("Moderate Proofreading Quiz, page %d"), 10);
$initial_instructions = $initial_instructions__P;
$initial_text = "don't spare him in the slightest!    ,\n\nChrys. (virtuously indignant) Is it enough, if he\nhears mere hard words from me this day than\never Clinia2 heard from Demetrius?2\n\n[EXIT.\n\nNIC. (ruefully) That servant of mine is very\nmuch like a sore eye : if you haven't got one,\nyou don't want one and don't miss it; if you\nhave, you can't keep your hands off it. Why, if\nhe hadn 't happened by good luck to be here to-\n\n^2 Characters in some familiar play.";
$solutions = ["don't spare him in the slightest!\n\nChrys. (virtuously indignant) Is it enough, if he\nhears more hard words from me this day than\never Clinia[2] heard from Demetrius?[2]\n\n[EXIT.\n\nNIC. (ruefully) That servant of mine is very\nmuch like a sore eye: if you haven't got one,\nyou don't want one and don't miss it; if you\nhave, you can't keep your hands off it. Why, if\nhe hadn't happened by good luck to be here to-*\n\n2 Characters in some familiar play."];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Be sure to read the Project Comments and project discussion before starting to work on a project.  There may be exceptions to the regular Proofreading Guidelines, or helpful information that will make proofreading the text easier for you.") . "</p>";


// error messages

$messages["mere"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>e</kbd>' in the text where there is an '<kbd>o</kbd>' in the image."),
];
$messages["flyspeck"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a comma (<kbd>,</kbd>) in the text where there is a stray mark in the image."),
];
$messages["bracketremove"] = [
    "message_title" => _("Bracket removed"),
    "message_body" => _("Leave the punctuation as the author wrote it, including any brackets around stage directions."),
    "guideline" => "play_n",
];
$messages["bracketinsert"] = [
    "message_title" => _("Bracket inserted"),
    "message_body" => _("Leave the punctuation for stage directions as the author wrote it.  There is no need to insert a closing bracket \"]\" when there is none in the original."),
    "guideline" => "play_n",
];
$messages["fnsuper"] = [
    "message_title" => _("Footnote marker"),
    "message_body" => _("There is no need for superscript markup for footnote markers.  Instead, the marker in the text should be surrounded by square brackets, like this: <kbd>[2]</kbd>."),
    "guideline" => "footnotes",
];
$messages["nobrackets"] = [
    "message_title" => _("Footnote marker brackets"),
    "message_body" => _("In the text, footnote markers should be in square brackets, like this: <kbd>[2]</kbd>."),
    "guideline" => "footnotes",
];
$messages["fnmarkerspace"] = [
    "message_title" => _("Footnote marker spacing"),
    "message_body" => _("Put the footnote marker right next to the word (or punctuation mark) being footnoted and leave a space on the other side of it, like this: <br><kbd>word[2] word</kbd>."),
    "guideline" => "footnotes",
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["mere"], "case_sensitive" => true, "error" => "mere"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [",\n\n"], "case_sensitive" => true, "error" => "flyspeck"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["to-*"], "case_sensitive" => true, "error" => "P_eophyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\""], "case_sensitive" => true, "error" => "P_addedquotes"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" :"], "case_sensitive" => true, "error" => "P_colonspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\nEXIT"], "case_sensitive" => false, "error" => "bracketremove"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["EXIT.]"], "case_sensitive" => false, "error" => "bracketinsert"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["hadn 't"], "case_sensitive" => true, "error" => "P_contracspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["to-*\n2"], "case_sensitive" => true, "error" => "P_fnspace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["2"], "case_sensitive" => true, "error" => "P_fnmarkermissing"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["a^2", "?^2"], "case_sensitive" => true, "error" => "fnsuper"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n^2"], "case_sensitive" => true, "error" => "P_fnbottomsuper"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Clinia2", "?2", "Clinia 2", "? 2"], "case_sensitive" => true, "error" => "nobrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" [2]", "]heard"], "case_sensitive" => true, "error" => "fnmarkerspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n[2"], "case_sensitive" => true, "error" => "P_fnbottombrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["2Ch"], "case_sensitive" => true, "error" => "P_fnbottomnospace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Footnote"], "case_sensitive" => false, "error" => "P_fnmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n2 Ch"], "case_sensitive" => true, "error" => "P_fnbottommarker"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<sc>", "</sc>"], "case_sensitive" => false, "error" => "P_scmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["NIC"], "case_sensitive" => true, "error" => "P_casechange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["Chrys"], "case_sensitive" => true, "error" => "P_casechange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["EXIT"], "case_sensitive" => true, "error" => "P_casechange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n "], "case_sensitive" => true, "error" => "P_indent"];
