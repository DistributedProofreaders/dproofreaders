<?php


$browser_title                = _("Old Texts Proofreading Quiz");
$intro_title                  = sprintf(_("Old Texts Proofreading Quiz, page %d"), 1);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "three in.number, refled that Shakespeare\nhas introduced three daughters into his\ntragedy of King Lear, which has often\ndrawn tears from the eyes of multitudes.'\n\nIndeed, your affe&amp;ing tale might better\nsuit the intention of an opera: were a fuffi-cient\nnumber of cats put under the tuition\nof proper matters, nobody can tell what an\naflonifhing chorus might be produced. If\nthis proposal shall be embraced, I make no\ncloubt of its being the wonder of all\nEurope,";
$solutions                    = array("three in number, reflect that Shakespeare\nhas introduced three daughters into his\ntragedy of King Lear, which has often\ndrawn tears from the eyes of multitudes.\n\nIndeed, your affecting tale might better\nsuit the intention of an opera: were a sufficient\nnumber of cats put under the tuition\nof proper masters, nobody can tell what an\nastonishing chorus might be produced. If\nthis proposal shall be embraced, I make no\ndoubt of its being the wonder of all");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Because OCR programs often use a modern dictionary to help them resolve unclear areas of the image, sometimes they will modernize the spelling.  We keep the text as the author wrote it, so when proofreading it is important to watch out for any modern spellings and return them to the older forms if necessary in order to match the image.") . "</p>";


// error messages

$messages["flyspeck"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a period (<kbd>.</kbd>) in the text where there is a stray mark in the image."),
);
$messages["refled"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>d</kbd>' in the text where there is '<kbd>ct</kbd>' in the image."),
);
$messages["flyspeck2"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an apostrophe (<kbd>'</kbd>) in the text where there is a stray mark in the image."),
);
$messages["ctamp"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an ampersand (<kbd>&amp;</kbd>) in the text where there is '<kbd>ct</kbd>' in the image."),
);
$messages["matters"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>tt</kbd>' in the text where there is '<kbd>st</kbd>' in the image."),
);
$messages["aslonish"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>l</kbd>' in the text where there is a '<kbd>t</kbd>' in the image."),
);
$messages["cloubt"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>cl</kbd>' in the text where there is a '<kbd>d</kbd>' in the image."),
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

