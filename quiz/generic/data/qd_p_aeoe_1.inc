<?php


$browser_title                = _("Ligatures Proofreading Quiz");
$intro_title                  = sprintf(_("Ligatures Proofreading Quiz, page %d"), 1);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "Colleges.' But his chef-d'ceuvre was his\nemphatic recognition of all the doctors, both the\nproctors, as if the numerical antithesis threw\nthose excellent personages into a charming\ntableau vinant.'\n\nThey proceeded down High Street, and then\nsaw Mr. Baternan, a bore at least of the second\nmagnitude. It is very difficult duly to delineate\na borc in a narrative, for the very reason that he\nis a bore. But eventually the truth bursts on\nyou, apparent dirae facies, you are in the\nclutches of a bore. You may yield, or you may\nflee; you cannot conquer.";
$solutions                    = array("Colleges.' But his chef-d'œuvre was his\nemphatic recognition of all the doctors, both the\nproctors, as if the numerical antithesis threw\nthose excellent personages into a charming\ntableau vivant.\"\n\nThey proceeded down High Street, and then\nsaw Mr. Bateman, a bore at least of the second\nmagnitude. It is very difficult duly to delineate\na bore in a narrative, for the very reason that he\nis a bore. But eventually the truth bursts on\nyou, apparent diræ facies, you are in the\nclutches of a bore. You may yield, or you may\nflee; you cannot conquer.");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("If something is not clear in the image, please leave a [**note] in the text or ask in the project discussion rather than guessing.") . "</p>";


// error messages

$messages["ceuvre"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ce</kbd>' in the text where there is an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the image."),
);
$messages["vinant"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>n</kbd>' in the text where there is a '<kbd>v</kbd>' in the image."),
);
$messages["quotescanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a single quote mark (<kbd>'</kbd>) in the text where there is a double quote mark (<kbd>\"</kbd>) in the image."),
);
$messages["Baternan"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>rn</kbd>' in the text where there is an '<kbd>m</kbd>' in the image."),
);
$messages["borc"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>c</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
);
$messages["oe_ae"] = array(
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the image that is marked as an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the text.  In italics they may look very similar, but the <kbd>œ</kbd> ligature is usually rounder at the top, while the 'a' of <kbd>æ</kbd> is more teardrop-shaped."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
);
$messages["ae_oe"] = array(
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the image that is marked as an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the text.  In italics they may look very similar, but the <kbd>œ</kbd> ligature is usually rounder at the top, while the 'a' of <kbd>æ</kbd> is more teardrop-shaped."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ceuvre"), "case_sensitive" => TRUE, "error" => "ceuvre");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("vinant"), "case_sensitive" => TRUE, "error" => "vinant");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ant.'"), "case_sensitive" => TRUE, "error" => "quotescanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Baternan"), "case_sensitive" => TRUE, "error" => "Baternan");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("borc"), "case_sensitive" => TRUE, "error" => "borc");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ae"), "case_sensitive" => TRUE, "error" => "P_ae");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("oeu", "[oe]"), "case_sensitive" => TRUE, "error" => "P_oe");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("dir[oe]", "diroe", "dirœ"), "case_sensitive" => TRUE, "error" => "ae_oe");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("æuvre"), "case_sensitive" => TRUE, "error" => "oe_ae");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");

// vim: sw=4 ts=4 expandtab
