<?php


$browser_title = _("Basic Proofreading Quiz");
$intro_title = sprintf(_("Basic Proofreading Quiz, page %d"), 1);
$initial_instructions = $initial_instructions__P;
$initial_text = "THE GLORY OF MOTION.      62\n\nbefore I matriculated at Oxford, Mr. Palmer,\nM. P. for Bath, had accomplished two tbings,\nvery hard to do on our little planet: he had in-\nvented mail-coaches, arid he had married the\ndaughter of a duke. He was, therefore, just\ntwice as great a man as Galileo, who certain-\nly invented (or discovered) the satellites of\nJupiter, those very next things extant to mail-\ncoaches in the two capital points of speed and\nkeeping time, hut who did not marry the\ndaughter of a duke.\n\nFf";
$solutions = ["before I matriculated at Oxford, Mr. Palmer,\nM. P. for Bath, had accomplished two things,\nvery hard to do on our little planet: he had invented\nmail-coaches, and he had married the\ndaughter of a duke. He was, therefore, just\ntwice as great a man as Galileo, who certainly\ninvented (or discovered) the satellites of\nJupiter, those very next things extant to mail-coaches\nin the two capital points of speed and\nkeeping time, but who did not marry the\ndaughter of a duke."];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("We remove page headers and footers because later on in the process all the pages will be joined together into one text.  If we left the header or footer on each page it would disrupt the flow of the text.") . "</p>";


// error messages

$messages["tbings"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a '<kbd>b</kbd>' in the text where there is an '<kbd>h</kbd>' in the image."),
];
$messages["arid"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': '<kbd>ri</kbd>' in the text where there is an '<kbd>n</kbd>' in the image."),
];
$messages["hut"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': an '<kbd>h</kbd>' in the text where there is a '<kbd>b</kbd>' in the image."),
];
$messages["eolhyphen2"] = [
    "message_title" => _("Hyphenation"),
    "message_body" => _("Since the word \"mail-coaches\" also appears in the middle of a line on this page, we know that the author wrote the word with a hyphen.  There is no need to remove the hyphen or add an asterisk when rejoining the word."),
];
$messages["initials"] = [
    "message_title" => _("Spacing of initials"),
    "message_body" => _("Leave a space between initials if there is a space in the image."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["tbi"], "case_sensitive" => true, "error" => "tbings"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["arid"], "case_sensitive" => true, "error" => "arid"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["hut"], "case_sensitive" => true, "error" => "hut"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["GLORY", "62"], "case_sensitive" => true, "error" => "P_header"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\nbefore"], "case_sensitive" => true, "error" => "P_headerline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-\n"], "case_sensitive" => true, "error" => "P_eolhyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["had\ninvented", "who\ncertainly", "to\nmail"], "case_sensitive" => true, "error" => "P_hyphenlower"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["mail-*", "mailcoaches"], "case_sensitive" => true, "error" => "eolhyphen2"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["in-vented", "certain-ly"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Ff"], "case_sensitive" => true, "error" => "P_footer"];
$tests[] = ["type" => "longline", "lengthlimit" => 70, "error" => "P_longline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["M.P."], "case_sensitive" => true, "error" => "initials"];
