<?php

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_mod2_1","pass");
}

$browser_title                = _("Moderate Proofreading Quiz");
$intro_title                  = _("Moderate Proofreading Quiz, page 6");
$initial_instructions         = _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.");
$ocr_text                     = "faintly defined over-head.   `\n\nThe\ntraveller.\n\nP----was a traveller: anybody could\nsee that he was a traveller, and if he had\nthen been in any part of the globe, there\nwould not have been the least doubt that\nhe was a traveller travelling on his travels.\nHe looked like a traveller, and was\ndressed like a traveller. He had with him:\n\na travelling-cap    a coat\na portable-desk   a compass\na travelling-shirt    a hand organ\n\nThe hand-organ played its part very\npleasantly in the cabin of the \" Balakla-";
$solutions                    = array("faintly defined over-head.\n\nThe\ntraveller.\n\nP---- was a traveller; anybody could\nsee that he was a traveller, and if he had\nthen been in any part of the globe, there\nwould not have been the least doubt that\nhe was a traveller travelling on his travels.\nHe looked like a traveller, and was\ndressed like a traveller. He had with him:\n\na travelling-cap\na portable-desk\na travelling-shirt\na coat\na compass\na hand-organ\n\nThe hand-organ played its part very\npleasantly in the cabin of the \"Balakla-*");
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("We unwrap multiple columns of text into a single column <i>unless</i> the arrangement is important to the meaning.  In a table there is a correspondence between the items in each row, so we need to keep that in order to preserve the author's intent.  However, if the text is in two columns simply to save space on the paper, we convert it into a single column.") . "</p>";


// error messages

$messages["hyphenomit"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno in the text: a hyphen (<tt>-</tt>) was omitted by the OCR software.") . "</p>",
    "hints" => array(),
);
$messages["quotespace"] = array(
    "message_text" => "<h2>" . _("Punctuation spacing") . "</h2>\n" .
        "<p>" . _("A quotation mark has space on both sides.") . "</p>",
    "hints" => array(),
    "guideline" => "punctuat",
);
$messages["punctscanno"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a colon (<tt>:</tt>) in the text where there is a semicolon (<tt>;</tt>) in the image.") . "</p>",
    "hints" => array(),
);
$messages["flyspeck"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an accent mark (<tt>`</tt>) in the text where there is a speck in the image.") . "</p>",
    "hints" => array(),
);
$messages["listspace"] = array(
    "message_text" => "<h2>" . _("Blank lines in list") . "</h2>\n" .
        "<p>" . _("In a list of short items like on this page, you do not need to put a blank line between the items of the list.") . "</p>",
    "hints" => array(),
);
$messages["listspace2"] = array(
    "message_text" => "<h2>" . _("Space around list") . "</h2>\n" .
        "<p>" . _("Leave a blank line before and after the list to separate it from the regular text.") . "</p>",
    "hints" => array(),
);
$messages["longasem"] = array(
    "message_text" => "<h2>" . _("Long dash missing") . "</h2>\n" .
        "<p>" . _("Proofread long dashes using four hyphens (<tt>----</tt>).") . "</p>",
    "hints" => array(),
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

// vim: sw=4 ts=4 expandtab
