<?php


$browser_title                = _("Formatting Quiz");
$intro_title                  = sprintf(_("Formatting Quiz, page %d"), 1);;
$initial_instructions         = $initial_instructions__F;
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$initial_text                 = "Accordingly, he suggested to the manager of the\nhouse that the front be brightened up at night by\nelectrical signs, one row of lights spelling his name\n\"BURTON\" and another row of lights spelling the\nname \"HOLMES.\"\nThe manager told him it was too much of an \ninnovation for him to authorize and referred him to\nthe owner of the theatre. Mr. Holmes travelled\nseveral hours into the country to consult with the\nowner, who referred him to his agent in the city. The\nagent in turn sent Mr. Holmes to the janitor of the\ntheatre.";
$solutions                    = array("\nAccordingly, he suggested to the manager of the\nhouse that the front be brightened up at night by\nelectrical signs, one row of lights spelling his name\n\"<sc>Burton</sc>\" and another row of lights spelling the\nname \"<sc>Holmes</sc>.\"\n\nThe <b>manager</b> told him it was too much of an\ninnovation for <i>him</i> to authorize and referred him to\nthe owner of the theatre. Mr. Holmes travelled\nseveral hours into the country to consult with the\nowner, who referred him to his agent in the city. The\nagent in turn sent Mr. Holmes to the janitor of the\ntheatre.");


// error messages
$messages["nosmallcaps"] = array(
    "message_title" => sprintf(_("<span style='%s'>Small Caps</span> not marked"), "font-variant: small-caps;"),
    "message_body" => "",
    "guideline" => "small_caps",
);
$messages["smcappunct"] = array(
    "message_title" => _("Error in small caps markup"),
    "message_body" => _("Place the <kbd>&lt;sc&gt;</kbd> at the start of small caps and the <kbd>&lt;/sc&gt;</kbd> at the end.  As with other markup, only include punctuation if the punctuation is part of an entire phrase or sentence that you are marking."),
    "guideline" => "small_caps",
);
$messages["smcapwholeword"] = array(
    "message_title" => _("Error in small caps markup"),
    "message_body" => sprintf(_("<span style='%s'>Mixed Case</span> small caps should have its case changed so that the 'tall' letters are uppercase, and the 'short' letters are lowercase.  Place the <kbd>&lt;sc&gt;</kbd> at the start of small-capped word or phrase and the <kbd>&lt;/sc&gt;</kbd> at the end."), "font-variant: small-caps;"),
    "guideline" => "small_caps",
);
$messages["noital"] = array(
    "message_title" => _("Italics missed"),
    "message_body" => _("There is one word in italics in the text; please surround it with <kbd>&lt;i&gt;</kbd> <kbd>&lt;/i&gt;</kbd>."),
    "guideline" => "italics",
);
$messages["nobold"] = array(
    "message_title" => _("Bold text missed"),
    "message_body" => _("There is one bold word in the text; please surround it with <kbd>&lt;b&gt;</kbd> <kbd>&lt;/b&gt;</kbd>."),
    "guideline" => "bold",
);
$messages["italcorrupt"] = array(
    "message_title" => _("Error in italics markup"),
    "message_body" => _("Somehow the italics markup you've done seems to be corrupt. Start the italics with <kbd>&lt;i&gt;</kbd> and end it with <kbd>&lt;/i&gt;</kbd>."),
    "guideline" => "italics",
);
$messages["boldcorrupt"] = array(
    "message_title" => _("Error in bold markup"),
    "message_body" => _("Somehow the bold markup you've done seems to be corrupt. Start the bold text with <kbd>&lt;b&gt;</kbd> and end it with <kbd>&lt;/b&gt;</kbd>."),
    "guideline" => "bold",
);
$messages["smcapcorrupt"] = array(
    "message_title" => _("Error in small caps markup"),
    "message_body" => _("Somehow the small caps markup you've done seems to be incorrect. Start the small caps with <kbd>&lt;sc&gt;</kbd> and end it with <kbd>&lt;/sc&gt;</kbd>."),
    "guideline" => "small_caps",
);
$messages["missingline"] = array(
    "message_title" => _("Missing blank line"),
    "message_body" => _("Paragraphs are delimited by blank lines. If a page starts with a new paragraph, place a new line at the start of the page."),
    "guideline" => "para_space",
);
$messages["cappedsmcaps"] = array(
    "message_title" => _("Small Caps not correctly decapitalised"),
    "message_body" => sprintf( _("<span style='%s'>Mixed Case</span> small caps should have its case changed so that the 'tall' letters are uppercase, and the 'short' letters are lowercase."), "font-variant: small-caps;"),
    "guideline" => "small_caps",
);
$messages["changedspelling"] = array(
    "message_title" => _("Spelling changed"),
    "message_body" => _("Do not modernise, or 'correct' the spelling of the author. In any event, 'travelled' is correct British English."),
);


// error checks
$tests[] = array("type" => "markupmissing", "opentext" => "<sc>", "closetext" => "</sc>", "case_sensitive" => TRUE, "error" => "nosmallcaps");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("OLMES", "URTON"), "case_sensitive" => TRUE, "error" => "cappedsmcaps");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("\"</sc>", ".</sc>", "<sc>\""), "case_sensitive" => TRUE, "error" => "smcappunct");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("B<sc>", "H<sc>"), "case_sensitive" => TRUE, "error" => "smcapwholeword");
$tests[] = array("type" => "forbiddentext", "searchtext" => "traveled", "case_sensitive" => TRUE, "error" => "changedspelling");
$tests[] = array("type" => "markupmissing", "opentext" => "<i>", "closetext" => "</i>", "case_sensitive" => FALSE, "error" => "noital");
$tests[] = array("type" => "markupmissing", "opentext" => "<b>", "closetext" => "</b>", "case_sensitive" => FALSE, "error" => "nobold");
$tests[] = array("type" => "markupcorrupt", "opentext" => "<i>", "closetext" => "</i>", "case_sensitive" => FALSE, "error" => "italcorrupt");
$tests[] = array("type" => "markupcorrupt", "opentext" => "<b>", "closetext" => "</b>", "case_sensitive" => FALSE, "error" => "boldcorrupt");
$tests[] = array("type" => "markupcorrupt", "opentext" => "<sc>", "closetext" => "</sc>", "case_sensitive" => FALSE, "error" => "smcapcorrupt");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nAccordingly"), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\nThe "), "case_sensitive" => TRUE, "error" => "missingline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("s</sc>"), "case_sensitive" => TRUE, "error" => "smcapcorrupt");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("n</sc>"), "case_sensitive" => TRUE, "error" => "smcapcorrupt");

