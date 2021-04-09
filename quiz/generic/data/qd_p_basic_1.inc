<?php


$browser_title                = _("Basic Proofreading Quiz");
$intro_title                  = sprintf(_("Basic Proofreading Quiz, page %d"), 1);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "THE GLORY OF MOTION.      62\n\nbefore I matriculated at Oxford, Mr. Palmer,\nM. P. for Bath, had accomplished two tbings,\nvery hard to do on our little planet: he had in-\nvented mail-coaches, arid he had married the\ndaughter of a duke. He was, therefore, just\ntwice as great a man as Galileo, who certain-\nly invented (or discovered) the satellites of\nJupiter, those very next things extant to mail-\ncoaches in the two capital points of speed and\nkeeping time, hut who did not marry the\ndaughter of a duke.\n\nFf";
$solutions                    = array("before I matriculated at Oxford, Mr. Palmer,\nM. P. for Bath, had accomplished two things,\nvery hard to do on our little planet: he had invented\nmail-coaches, and he had married the\ndaughter of a duke. He was, therefore, just\ntwice as great a man as Galileo, who certainly\ninvented (or discovered) the satellites of\nJupiter, those very next things extant to mail-coaches\nin the two capital points of speed and\nkeeping time, but who did not marry the\ndaughter of a duke.");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("We remove page headers and footers because later on in the process all the pages will be joined together into one text.  If we left the header or footer on each page it would disrupt the flow of the text.") . "</p>";


// error messages

$messages["tbings"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a '<kbd>b</kbd>' in the text where there is an '<kbd>h</kbd>' in the image."),
);
$messages["arid"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': '<kbd>ri</kbd>' in the text where there is an '<kbd>n</kbd>' in the image."),
);
$messages["hut"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': an '<kbd>h</kbd>' in the text where there is a '<kbd>b</kbd>' in the image."),
);
$messages["eolhyphen2"] = array(
    "message_title" => _("Hyphenation"),
    "message_body" => _("Since the word \"mail-coaches\" also appears in the middle of a line on this page, we know that the author wrote the word with a hyphen.  There is no need to remove the hyphen or add an asterisk when rejoining the word."),
);
$messages["initials"] = array(
    "message_title" => _("Spacing of initials"),
    "message_body" => _("Leave a space between initials if there is a space in the image."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("tbi"), "case_sensitive" => TRUE, "error" => "tbings");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("arid"), "case_sensitive" => TRUE, "error" => "arid");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("hut"), "case_sensitive" => TRUE, "error" => "hut");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("GLORY", "62"), "case_sensitive" => TRUE, "error" => "P_header");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\nbefore"), "case_sensitive" => TRUE, "error" => "P_headerline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("-\n"), "case_sensitive" => TRUE, "error" => "P_eolhyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("had\ninvented", "who\ncertainly", "to\nmail"), "case_sensitive" => TRUE, "error" => "P_hyphenlower");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("mail-*", "mailcoaches"), "case_sensitive" => TRUE, "error" => "eolhyphen2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("in-vented", "certain-ly"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Ff"), "case_sensitive" => TRUE, "error" => "P_footer");
$tests[] = array("type" => "longline", "lengthlimit" => 70, "error" => "P_longline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("M.P."), "case_sensitive" => TRUE, "error" => "initials");

