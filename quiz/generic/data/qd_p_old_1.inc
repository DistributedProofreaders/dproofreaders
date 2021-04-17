<?php


$browser_title = _("Old Texts Proofreading Quiz");
$intro_title = sprintf(_("Old Texts Proofreading Quiz, page %d"), 1);
$initial_instructions = $initial_instructions__P;
$initial_text = "three in.number, refled that Shakespeare\nhas introduced three daughters into his\ntragedy of King Lear, which has often\ndrawn tears from the eyes of multitudes.'\n\nIndeed, your affe&amp;ing tale might better\nsuit the intention of an opera: were a fuffi-cient\nnumber of cats put under the tuition\nof proper matters, nobody can tell what an\naflonifhing chorus might be produced. If\nthis proposal shall be embraced, I make no\ncloubt of its being the wonder of all\nEurope,";
$solutions = ["three in number, reflect that Shakespeare\nhas introduced three daughters into his\ntragedy of King Lear, which has often\ndrawn tears from the eyes of multitudes.\n\nIndeed, your affecting tale might better\nsuit the intention of an opera: were a sufficient\nnumber of cats put under the tuition\nof proper masters, nobody can tell what an\nastonishing chorus might be produced. If\nthis proposal shall be embraced, I make no\ndoubt of its being the wonder of all"];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Because OCR programs often use a modern dictionary to help them resolve unclear areas of the image, sometimes they will modernize the spelling.  We keep the text as the author wrote it, so when proofreading it is important to watch out for any modern spellings and return them to the older forms if necessary in order to match the image.") . "</p>";


// error messages

$messages["flyspeck"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a period (<kbd>.</kbd>) in the text where there is a stray mark in the image."),
];
$messages["refled"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>d</kbd>' in the text where there is '<kbd>ct</kbd>' in the image."),
];
$messages["flyspeck2"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an apostrophe (<kbd>'</kbd>) in the text where there is a stray mark in the image."),
];
$messages["ctamp"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an ampersand (<kbd>&amp;</kbd>) in the text where there is '<kbd>ct</kbd>' in the image."),
];
$messages["matters"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>tt</kbd>' in the text where there is '<kbd>st</kbd>' in the image."),
];
$messages["aslonish"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>l</kbd>' in the text where there is a '<kbd>t</kbd>' in the image."),
];
$messages["cloubt"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>cl</kbd>' in the text where there is a '<kbd>d</kbd>' in the image."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["fpear", "fuit", "fuffi", "mafters", "aftoni", "fhing", "propofal", "fhall"], "case_sensitive" => true, "error" => "P_longs"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[s]", "[f]"], "case_sensitive" => true, "error" => "P_longsbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-cient"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["in."], "case_sensitive" => true, "error" => "flyspeck"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["refled"], "case_sensitive" => true, "error" => "refled"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["'"], "case_sensitive" => true, "error" => "flyspeck2"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["affe&"], "case_sensitive" => true, "error" => "ctamp"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["matters"], "case_sensitive" => true, "error" => "matters"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["loni"], "case_sensitive" => true, "error" => "aslonish"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["cloubt"], "case_sensitive" => true, "error" => "cloubt"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Europe"], "case_sensitive" => true, "error" => "P_catchword"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["reflect"], "case_sensitive" => true, "error" => "P_ctlig"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["affecting"], "case_sensitive" => true, "error" => "P_ctlig"];
