<?php


$browser_title = _("Ligatures Proofreading Quiz");
$intro_title = sprintf(_("Ligatures Proofreading Quiz, page %d"), 1);
$initial_instructions = $initial_instructions__P;
$initial_text = "Colleges.' But his chef-d'ceuvre was his\nemphatic recognition of all the doctors, both the\nproctors, as if the numerical antithesis threw\nthose excellent personages into a charming\ntableau vinant.'\n\nThey proceeded down High Street, and then\nsaw Mr. Baternan, a bore at least of the second\nmagnitude. It is very difficult duly to delineate\na borc in a narrative, for the very reason that he\nis a bore. But eventually the truth bursts on\nyou, apparent dirae facies, you are in the\nclutches of a bore. You may yield, or you may\nflee; you cannot conquer.";
$solutions = ["Colleges.' But his chef-d'œuvre was his\nemphatic recognition of all the doctors, both the\nproctors, as if the numerical antithesis threw\nthose excellent personages into a charming\ntableau vivant.\"\n\nThey proceeded down High Street, and then\nsaw Mr. Bateman, a bore at least of the second\nmagnitude. It is very difficult duly to delineate\na bore in a narrative, for the very reason that he\nis a bore. But eventually the truth bursts on\nyou, apparent diræ facies, you are in the\nclutches of a bore. You may yield, or you may\nflee; you cannot conquer."];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("If something is not clear in the image, please leave a [**note] in the text or ask in the project discussion rather than guessing.") . "</p>";


// error messages

$messages["ceuvre"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ce</kbd>' in the text where there is an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the image."),
];
$messages["vinant"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>n</kbd>' in the text where there is a '<kbd>v</kbd>' in the image."),
];
$messages["quotescanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a single quote mark (<kbd>'</kbd>) in the text where there is a double quote mark (<kbd>\"</kbd>) in the image."),
];
$messages["Baternan"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>rn</kbd>' in the text where there is an '<kbd>m</kbd>' in the image."),
];
$messages["borc"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>c</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
];
$messages["oe_ae"] = [
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the image that is marked as an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the text.  In italics they may look very similar, but the <kbd>œ</kbd> ligature is usually rounder at the top, while the 'a' of <kbd>æ</kbd> is more teardrop-shaped."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
];
$messages["ae_oe"] = [
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the image that is marked as an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the text.  In italics they may look very similar, but the <kbd>œ</kbd> ligature is usually rounder at the top, while the 'a' of <kbd>æ</kbd> is more teardrop-shaped."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["ceuvre"], "case_sensitive" => true, "error" => "ceuvre"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["vinant"], "case_sensitive" => true, "error" => "vinant"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ant.'"], "case_sensitive" => true, "error" => "quotescanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Baternan"], "case_sensitive" => true, "error" => "Baternan"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["borc"], "case_sensitive" => true, "error" => "borc"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ae"], "case_sensitive" => true, "error" => "P_ae"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["oeu", "[oe]"], "case_sensitive" => true, "error" => "P_oe"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["dir[oe]", "diroe", "dirœ"], "case_sensitive" => true, "error" => "ae_oe"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["æuvre"], "case_sensitive" => true, "error" => "oe_ae"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
