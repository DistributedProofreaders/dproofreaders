<?php

$browser_title = sprintf(_("Moderate Proofreading Quiz: Part %d"), 2);
$intro_title = sprintf(_("Moderate Proofreading Quiz: Part %1\$d, Page %2\$d"), 2, 1);
$initial_instructions = $initial_instructions__P;
$initial_text = "faintly defined over-head.   `\n\nThe\ntraveller.\n\nP----was a traveller: anybody could\nsee that he was a traveller, and if he had\nthen been in any part of the globe, there\nwould not have been the least doubt that\nhe was a traveller travelling on his travels.\nHe looked like a traveller, and was\ndressed like a traveller. He had with him:\n\na travelling-cap    a coat\na portable-desk   a compass\na travelling-shirt    a hand organ\n\nThe hand-organ played its part very\npleasantly in the cabin of the \" Balakla-";
$solutions = ["faintly defined over-head.\n\nThe\ntraveller.\n\nP---- was a traveller; anybody could\nsee that he was a traveller, and if he had\nthen been in any part of the globe, there\nwould not have been the least doubt that\nhe was a traveller travelling on his travels.\nHe looked like a traveller, and was\ndressed like a traveller. He had with him:\n\na travelling-cap\na portable-desk\na travelling-shirt\na coat\na compass\na hand-organ\n\nThe hand-organ played its part very\npleasantly in the cabin of the \"Balakla-*"];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("We unwrap multiple columns of text into a single column <i>unless</i> the arrangement is important to the meaning.  In a table there is a correspondence between the items in each row, so we need to keep that in order to preserve the author's intent.  However, if the text is in two columns simply to save space on the paper, we convert it into a single column.") . "</p>";


// error messages

$messages["hyphenomit"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a hyphen (<kbd>-</kbd>) was omitted by the OCR software."),
];
$messages["quotespace"] = [
    "message_title" => _("Punctuation spacing"),
    "message_body" => _("A quotation mark has space on both sides."),
    "guideline" => "punctuat",
];
$messages["punctscanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a colon (<kbd>:</kbd>) in the text where there is a semicolon (<kbd>;</kbd>) in the image."),
];
$messages["flyspeck"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an accent mark (<kbd>`</kbd>) in the text where there is a speck in the image."),
];
$messages["listspace"] = [
    "message_title" => _("Blank lines in list"),
    "message_body" => _("In a list of short items like on this page, you do not need to put a blank line between the items of the list."),
];
$messages["listspace2"] = [
    "message_title" => _("Space around list"),
    "message_body" => _("Leave a blank line before and after the list to separate it from the regular text."),
];
$messages["longasem"] = [
    "message_title" => _("Long dash missing"),
    "message_body" => _("Proofread long dashes using four hyphens (<kbd>----</kbd>)."),
    "guideline" => "em_dashes",
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["hand organ"], "case_sensitive" => true, "error" => "hyphenomit"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" \" "], "case_sensitive" => true, "error" => "quotespace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["traveller:"], "case_sensitive" => true, "error" => "punctscanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["`"], "case_sensitive" => true, "error" => "flyspeck"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["kla-*"], "case_sensitive" => true, "error" => "P_eophyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["trav-els"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "\"", "case_sensitive" => true, "error" => "P_addedclosequote"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "he\ntrav", "secondtext" => "anybody", "case_sensitive" => true, "error" => "P_snmove"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "defined", "secondtext" => "he\ntrav", "case_sensitive" => true, "error" => "P_snmove"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Sidenote"], "case_sensitive" => false, "error" => "P_snmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["he trav"], "case_sensitive" => true, "error" => "P_snlinebreak"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "ing-shirt", "secondtext" => "coat", "case_sensitive" => true, "error" => "P_columns"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "\n\na", "case_sensitive" => true, "error" => "listspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["him:\na travelling", "organ\nThe"], "case_sensitive" => true, "error" => "listspace2"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["----"], "case_sensitive" => true, "error" => "longasem"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["----was", " ----"], "case_sensitive" => true, "error" => "P_dashneedspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n "], "case_sensitive" => true, "error" => "P_indent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/*", "*/"], "case_sensitive" => true, "error" => "P_nowrapmarkup"];
