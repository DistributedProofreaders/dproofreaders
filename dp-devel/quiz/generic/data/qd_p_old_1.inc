<?php

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_old_1","pass");
}

$browser_title                = _("Old Texts Proofreading Quiz");
$intro_title                  = _("Old Texts Proofreading Quiz, page 1");
$initial_instructions         = $initial_instructions__P;
$ocr_text                     = "three in.number, refled that Shakespeare\nhas introduced three daughters into his\ntragedy of King Lear, which has often\ndrawn tears from the eyes of multitudes.'\n\nIndeed, your affe&amp;ing tale might better\nsuit the intention of an opera: were a fuffi-cient\nnumber of cats put under the tuition\nof proper matters, nobody can tell what an\naflonifhing chorus might be produced. If\nthis proposal shall be embraced, I make no\ncloubt of its being the wonder of all\nEurope,";
$solutions                    = array("three in number, reflect that Shakespeare\nhas introduced three daughters into his\ntragedy of King Lear, which has often\ndrawn tears from the eyes of multitudes.\n\nIndeed, your affecting tale might better\nsuit the intention of an opera: were a sufficient\nnumber of cats put under the tuition\nof proper masters, nobody can tell what an\nastonishing chorus might be produced. If\nthis proposal shall be embraced, I make no\ndoubt of its being the wonder of all");
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Because OCR programs often use a modern dictionary to help them resolve unclear areas of the image, sometimes they will modernize the spelling.  We keep the text as the author wrote it, so when proofreading it is important to watch out for any modern spellings and return them to the older forms if necessary in order to match the image.") . "</p>";


// error messages

$messages["flyspeck"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a period (<tt>.</tt>) in the text where there is a stray mark in the image.") . "</p>",
);
$messages["refled"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>d</tt>' in the text where there is '<tt>ct</tt>' in the image.") . "</p>",
);
$messages["flyspeck2"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an apostrophe (<tt>'</tt>) in the text where there is a stray mark in the image.") . "</p>",
);
$messages["ctamp"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an ampersand (<tt>&amp;</tt>) in the text where there is '<tt>ct</tt>' in the image.") . "</p>",
);
$messages["matters"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: '<tt>tt</tt>' in the text where there is '<tt>st</tt>' in the image.") . "</p>",
);
$messages["aslonish"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an '<tt>l</tt>' in the text where there is a '<tt>t</tt>' in the image.") . "</p>",
);
$messages["cloubt"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: '<tt>cl</tt>' in the text where there is a '<tt>d</tt>' in the image.") . "</p>",
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("fpear", "fuit", "fuffi", "mafters", "aftoni", "fhing", "propofal", "fhall"), "case_sensitive" => TRUE, "error" => "P_longs");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[s]", "[f]"), "case_sensitive" => TRUE, "error" => "P_longsbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("-cient"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("in."), "case_sensitive" => TRUE, "error" => "flyspeck");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("refled"), "case_sensitive" => TRUE, "error" => "refled");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("'"), "case_sensitive" => TRUE, "error" => "flyspeck2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("affe&"), "case_sensitive" => TRUE, "error" => "ctamp");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("matters"), "case_sensitive" => TRUE, "error" => "matters");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("loni"), "case_sensitive" => TRUE, "error" => "aslonish");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("cloubt"), "case_sensitive" => TRUE, "error" => "cloubt");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Europe"), "case_sensitive" => TRUE, "error" => "P_catchword");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("reflect"), "case_sensitive" => TRUE, "error" => "P_ctlig");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("affecting"), "case_sensitive" => TRUE, "error" => "P_ctlig");

// vim: sw=4 ts=4 expandtab
