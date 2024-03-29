<?php


$browser_title = _("Formatting Quiz");
$intro_title = sprintf(_("Formatting Quiz, page %d"), 3);
$initial_instructions = $initial_instructions__F;
// Note that we don't translate the page text since it, obviously,
// needs to match the image which is in English.
$initial_text = "Chapter 8\n\nTHE MERMAIDS' LAGOON\nIn which our heroes discover the perils of ignoring thought breaks.\n\nIF YOU SHUT your eyes and are a lucky one, you may see\n\nat times a shapeless pool of lovely pale colours\nsuspended in the darkness; then if you squeeze your\neyes tighter, the pool begins to take shape, and the\ncolours become so vivid that with another squeeze they\nmust go on fire. But just before they go on fire you see\nthe lagoon.\n\nThe children often spent long summer days on this\nlagoon, swimming or floating most of the time, playing\nthe mermaid games in the water, and so forth. You";
$solutions = ["\n\n\n\nChapter 8\n\nTHE MERMAIDS' LAGOON\n\nIn which our heroes discover the perils of ignoring thought breaks.\n\n\nIf you shut your eyes and are a lucky one, you may see\nat times a shapeless pool of lovely pale colours\nsuspended in the darkness; then if you squeeze your\neyes tighter, the pool begins to take shape, and the\ncolours become so vivid that with another squeeze they\nmust go on fire. But just before they go on fire you see\nthe lagoon.\n\n<tb>\n\nThe children often spent long summer days on this\nlagoon, swimming or floating most of the time, playing\nthe mermaid games in the water, and so forth. You"];


// error messages
$messages["notfour"] = [
    "message_title" => _("Number of blank lines before chapter heading incorrect"),
    "message_body" => _("There should be 4 blank lines before the chapter heading."),
    "guideline" => "chap_head",
];
$messages["nottwo"] = [
    "message_title" => _("Number of blank lines between chapter heading and text incorrect"),
    "message_body" => _("There should be 2 blank lines before the start of the regular paragraph text."),
    "guideline" => "chap_head",
];
$messages["numberinheader"] = [
    "message_title" => _("Number of blank lines within chapter heading incorrect"),
    "message_body" => _("There should be 1 blank line between different parts of the chapter heading."),
    "guideline" => "chap_head",
];
$messages["extraline"] = [
    "message_title" => _("Extra blank line"),
    "message_body" => _("An extra blank line has been accidentally inserted by the proofreaders. Remove this line to match the image."),
];
$messages["extratb"] = [
    "message_title" => _("Extra thought break markup"),
    "message_body" => _("There is only one thought break (<kbd>&lt;tb&gt;</kbd>) on the page.  Separate the chapter heading from the regular text using blank lines, not thought break markup."),
    "guideline" => "chap_head",
];
$messages["nothoughtbreak"] = [
    "message_title" => _("Missing thought break"),
    "message_body" => _("A thought break (<kbd>&lt;tb&gt;</kbd>) should be placed between the paragraphs that have a break between them."),
    "guideline" => "extra_s",
];
$messages["thoughtbreakspc"] = [
    "message_title" => _("Incorrect thought break spacing"),
    "message_body" => _("A thought break (<kbd>&lt;tb&gt;</kbd>) should have a blank line before and after it."),
    "guideline" => "extra_s",
];
$messages["nobold"] = [
    "message_title" => _("Bold markup in heading"),
    "message_body" => _("Chapter Headers may be printed in a different or larger font that may appear to be bold, but we do not mark them as bold."),
    "guideline" => "chap_head",
];
$messages["chapheadnotdecapped"] = [
    "message_title" => _("Chapter start not decapitalised"),
    "message_body" => _("Change the case of the first words of a chapter to match the rest of the text. Printing the first word(s) of a chapter in all caps or small caps is just a typesetting convention."),
    "guideline" => "chap_head",
];
$messages["chapheadsc"] = [
    "message_title" => _("Small caps markup added to chapter start"),
    "message_body" => _("Printing the first word(s) of a chapter in all caps or small caps is just a typesetting convention, so instead of inserting small caps markup, simply change the case to match the rest of the text."),
    "guideline" => "chap_head",
];
$messages["chapheadoverkill"] = [
    "message_title" => _("Chapter start completely decapitalised"),
    "message_body" => _("You've got the right idea, but gone a little too far. Leave the first letter capitalised, as it's at the start of the sentence."),
    "guideline" => "chap_head",
];


// error checks
$tests[] = ["type" => "forbiddentext", "searchtext" => "IF YOU SHUT", "case_sensitive" => true, "error" => "chapheadnotdecapped"];
$tests[] = ["type" => "forbiddentext", "searchtext" => "if you shut", "case_sensitive" => true, "error" => "chapheadoverkill"];
$tests[] = ["type" => "forbiddentext", "searchtext" => "breaks.\n\n<tb>", "case_sensitive" => true, "error" => "extratb"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "<tb>", "case_sensitive" => true, "error" => "extratb"];
$tests[] = ["type" => "forbiddentext", "searchtext" => "<b>", "case_sensitive" => true, "error" => "nobold"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\n\n\nChapter"], "case_sensitive" => true, "error" => "notfour"];
$tests[] = ["type" => "expectedlinebreaks", "number" => 3, "starttext" => "breaks.", "stoptext" => "If", "case_sensitive" => false, "errorhigh" => "nottwo", "errorlow" => "nottwo"];
$tests[] = ["type" => "expectedlinebreaks", "number" => 2, "starttext" => "LAGOON", "stoptext" => "In which", "case_sensitive" => false, "errorhigh" => "numberinheader", "errorlow" => "numberinheader"];
$tests[] = ["type" => "expectedlinebreaks", "number" => 2, "starttext" => "8", "stoptext" => "THE", "case_sensitive" => false, "errorhigh" => "numberinheader", "errorlow" => "numberinheader"];
$tests[] = ["type" => "forbiddentext", "searchtext" => "\n\nat times", "case_sensitive" => true, "error" => "extraline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => "<sc>", "case_sensitive" => false, "error" => "chapheadsc"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["<tb>"], "case_sensitive" => true, "error" => "nothoughtbreak"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\n<tb>\n\n"], "case_sensitive" => true, "error" => "thoughtbreakspc"];
