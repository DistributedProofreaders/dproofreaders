<?php


$browser_title                = _("Moderate Proofreading Quiz");
$intro_title                  = sprintf(_("Moderate Proofreading Quiz, page %d"), 6);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "faintly defined over-head.   `\n\nThe\ntraveller.\n\nP----was a traveller: anybody could\nsee that he was a traveller, and if he had\nthen been in any part of the globe, there\nwould not have been the least doubt that\nhe was a traveller travelling on his travels.\nHe looked like a traveller, and was\ndressed like a traveller. He had with him:\n\na travelling-cap    a coat\na portable-desk   a compass\na travelling-shirt    a hand organ\n\nThe hand-organ played its part very\npleasantly in the cabin of the \" Balakla-";
$solutions                    = array("faintly defined over-head.\n\nThe\ntraveller.\n\nP---- was a traveller; anybody could\nsee that he was a traveller, and if he had\nthen been in any part of the globe, there\nwould not have been the least doubt that\nhe was a traveller travelling on his travels.\nHe looked like a traveller, and was\ndressed like a traveller. He had with him:\n\na travelling-cap\na portable-desk\na travelling-shirt\na coat\na compass\na hand-organ\n\nThe hand-organ played its part very\npleasantly in the cabin of the \"Balakla-*");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("We unwrap multiple columns of text into a single column <i>unless</i> the arrangement is important to the meaning.  In a table there is a correspondence between the items in each row, so we need to keep that in order to preserve the author's intent.  However, if the text is in two columns simply to save space on the paper, we convert it into a single column.") . "</p>";


// error messages

$messages["hyphenomit"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a hyphen (<kbd>-</kbd>) was omitted by the OCR software."),
);
$messages["quotespace"] = array(
    "message_title" => _("Punctuation spacing"),
    "message_body" => _("A quotation mark has space on both sides."),
    "guideline" => "punctuat",
);
$messages["punctscanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a colon (<kbd>:</kbd>) in the text where there is a semicolon (<kbd>;</kbd>) in the image."),
);
$messages["flyspeck"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an accent mark (<kbd>`</kbd>) in the text where there is a speck in the image."),
);
$messages["listspace"] = array(
    "message_title" => _("Blank lines in list"),
    "message_body" => _("In a list of short items like on this page, you do not need to put a blank line between the items of the list."),
);
$messages["listspace2"] = array(
    "message_title" => _("Space around list"),
    "message_body" => _("Leave a blank line before and after the list to separate it from the regular text."),
);
$messages["longasem"] = array(
    "message_title" => _("Long dash missing"),
    "message_body" => _("Proofread long dashes using four hyphens (<kbd>----</kbd>)."),
    "guideline" => "em_dashes",
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("hand organ"), "case_sensitive" => TRUE, "error" => "hyphenomit");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" \" "), "case_sensitive" => TRUE, "error" => "quotespace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("traveller:"), "case_sensitive" => TRUE, "error" => "punctscanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("`"), "case_sensitive" => TRUE, "error" => "flyspeck");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("kla-*"), "case_sensitive" => TRUE, "error" => "P_eophyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("trav-els"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "multioccurrence", "searchtext" => "\"", "case_sensitive" => TRUE, "error" => "P_addedclosequote");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "he\ntrav", "secondtext" => "anybody", "case_sensitive" => TRUE, "error" => "P_snmove");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "defined", "secondtext" => "he\ntrav", "case_sensitive" => TRUE, "error" => "P_snmove");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Sidenote"), "case_sensitive" => FALSE, "error" => "P_snmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("he trav"), "case_sensitive" => TRUE, "error" => "P_snlinebreak");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "ing-shirt", "secondtext" => "coat", "case_sensitive" => TRUE, "error" => "P_columns");
$tests[] = array("type" => "multioccurrence", "searchtext" => "\n\na", "case_sensitive" => TRUE, "error" => "listspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("him:\na travelling", "organ\nThe"), "case_sensitive" => TRUE, "error" => "listspace2");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("----"), "case_sensitive" => TRUE, "error" => "longasem");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("----was", " ----"), "case_sensitive" => TRUE, "error" => "P_dashneedspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "P_indent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/*", "*/"), "case_sensitive" => TRUE, "error" => "P_nowrapmarkup");

