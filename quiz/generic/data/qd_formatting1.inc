<?php


$browser_title = _("Formatting Quiz");
$intro_title = sprintf(_("Formatting Quiz, page %d"), 1); ;
$initial_instructions = $initial_instructions__F;
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$initial_text = "Accordingly, he suggested to the manager of the\nhouse that the front be brightened up at night by\nelectrical signs, one row of lights spelling his name\n\"BURTON\" and another row of lights spelling the\nname \"HOLMES.\"\nThe manager told him it was too much of an \ninnovation for him to authorize and referred him to\nthe owner of the theatre. Mr. Holmes travelled\nseveral hours into the country to consult with the\nowner, who referred him to his agent in the city. The\nagent in turn sent Mr. Holmes to the janitor of the\ntheatre.";
$solutions = ["\nAccordingly, he suggested to the manager of the\nhouse that the front be brightened up at night by\nelectrical signs, one row of lights spelling his name\n\"<sc>Burton</sc>\" and another row of lights spelling the\nname \"<sc>Holmes</sc>.\"\n\nThe <b>manager</b> told him it was too much of an\ninnovation for <i>him</i> to authorize and referred him to\nthe owner of the theatre. Mr. Holmes travelled\nseveral hours into the country to consult with the\nowner, who referred him to his agent in the city. The\nagent in turn sent Mr. Holmes to the janitor of the\ntheatre."];


// error messages
$messages["nosmallcaps"] = [
    "message_title" => sprintf(_("<span style='%s'>Small Caps</span> not marked"), "font-variant: small-caps;"),
    "message_body" => "",
    "guideline" => "small_caps",
];
$messages["smcappunct"] = [
    "message_title" => _("Error in small caps markup"),
    "message_body" => _("Place the <kbd>&lt;sc&gt;</kbd> at the start of small caps and the <kbd>&lt;/sc&gt;</kbd> at the end.  As with other markup, only include punctuation if the punctuation is part of an entire phrase or sentence that you are marking."),
    "guideline" => "small_caps",
];
$messages["smcapwholeword"] = [
    "message_title" => _("Error in small caps markup"),
    "message_body" => sprintf(_("<span style='%s'>Mixed Case</span> small caps should have its case changed so that the 'tall' letters are uppercase, and the 'short' letters are lowercase.  Place the <kbd>&lt;sc&gt;</kbd> at the start of small-capped word or phrase and the <kbd>&lt;/sc&gt;</kbd> at the end."), "font-variant: small-caps;"),
    "guideline" => "small_caps",
];
$messages["noital"] = [
    "message_title" => _("Italics missed"),
    "message_body" => _("There is one word in italics in the text; please surround it with <kbd>&lt;i&gt;</kbd> <kbd>&lt;/i&gt;</kbd>."),
    "guideline" => "italics",
];
$messages["nobold"] = [
    "message_title" => _("Bold text missed"),
    "message_body" => _("There is one bold word in the text; please surround it with <kbd>&lt;b&gt;</kbd> <kbd>&lt;/b&gt;</kbd>."),
    "guideline" => "bold",
];
$messages["italcorrupt"] = [
    "message_title" => _("Error in italics markup"),
    "message_body" => _("Somehow the italics markup you've done seems to be corrupt. Start the italics with <kbd>&lt;i&gt;</kbd> and end it with <kbd>&lt;/i&gt;</kbd>."),
    "guideline" => "italics",
];
$messages["boldcorrupt"] = [
    "message_title" => _("Error in bold markup"),
    "message_body" => _("Somehow the bold markup you've done seems to be corrupt. Start the bold text with <kbd>&lt;b&gt;</kbd> and end it with <kbd>&lt;/b&gt;</kbd>."),
    "guideline" => "bold",
];
$messages["smcapcorrupt"] = [
    "message_title" => _("Error in small caps markup"),
    "message_body" => _("Somehow the small caps markup you've done seems to be incorrect. Start the small caps with <kbd>&lt;sc&gt;</kbd> and end it with <kbd>&lt;/sc&gt;</kbd>."),
    "guideline" => "small_caps",
];
$messages["missingline"] = [
    "message_title" => _("Missing blank line"),
    "message_body" => _("Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a new line at the start of the page."),
    "guideline" => "para_space",
];
$messages["cappedsmcaps"] = [
    "message_title" => _("Small Caps not correctly decapitalised"),
    "message_body" => sprintf(_("<span style='%s'>Mixed Case</span> small caps should have its case changed so that the 'tall' letters are uppercase, and the 'short' letters are lowercase."), "font-variant: small-caps;"),
    "guideline" => "small_caps",
];
$messages["changedspelling"] = [
    "message_title" => _("Spelling changed"),
    "message_body" => _("Do not modernise, or 'correct' the spelling of the author. In any event, 'travelled' is correct British English."),
];


// error checks
$tests[] = ["type" => "markupmissing", "opentext" => "<sc>", "closetext" => "</sc>", "case_sensitive" => true, "error" => "nosmallcaps"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["OLMES", "URTON"], "case_sensitive" => true, "error" => "cappedsmcaps"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\"</sc>", ".</sc>", "<sc>\""], "case_sensitive" => true, "error" => "smcappunct"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["B<sc>", "H<sc>"], "case_sensitive" => true, "error" => "smcapwholeword"];
$tests[] = ["type" => "forbiddentext", "searchtext" => "traveled", "case_sensitive" => true, "error" => "changedspelling"];
$tests[] = ["type" => "markupmissing", "opentext" => "<i>", "closetext" => "</i>", "case_sensitive" => false, "error" => "noital"];
$tests[] = ["type" => "markupmissing", "opentext" => "<b>", "closetext" => "</b>", "case_sensitive" => false, "error" => "nobold"];
$tests[] = ["type" => "markupcorrupt", "opentext" => "<i>", "closetext" => "</i>", "case_sensitive" => false, "error" => "italcorrupt"];
$tests[] = ["type" => "markupcorrupt", "opentext" => "<b>", "closetext" => "</b>", "case_sensitive" => false, "error" => "boldcorrupt"];
$tests[] = ["type" => "markupcorrupt", "opentext" => "<sc>", "closetext" => "</sc>", "case_sensitive" => false, "error" => "smcapcorrupt"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nAccordingly"], "case_sensitive" => true, "error" => "missingline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\nThe "], "case_sensitive" => true, "error" => "missingline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["s</sc>"], "case_sensitive" => true, "error" => "smcapcorrupt"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["n</sc>"], "case_sensitive" => true, "error" => "smcapcorrupt"];
