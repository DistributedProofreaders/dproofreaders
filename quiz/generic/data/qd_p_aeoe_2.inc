<?php


$browser_title = _("Ligatures Proofreading Quiz");
$intro_title = sprintf(_("Ligatures Proofreading Quiz, page %d"), 2);
$initial_instructions = $initial_instructions__P;
$initial_text = "apple. The lofty Terebinthacece, with their\nwalnut-like fruit, are inhabited by swarms of\nsquirrels, as well as birds remarkable for their\ngay-colored plumage. Among the most common\nin those districts are the red-bellied tanagra\n(Tanagra ignivcntris, Orb.) and the fire-colored\npyranga (Phcenisoma bivittata, Tsch.). Individual\nbirds belonging to the Penelope family (P.\nrufiventris and adspersa. Tsch.) and the green\npepper-eater (Pieroglossus cceruleo-cinctus,\nTsch.) are found in the lower forests.";
$solutions = ["apple. The lofty Terebinthaceæ, with their\nwalnut-like fruit, are inhabited by swarms of\nsquirrels, as well as birds remarkable for their\ngay-colored plumage. Among the most common\nin these districts are the red-bellied tanagra\n(Tanagra igniventris, Orb.) and the fire-colored\npyranga (Phœnisoma bivittata, Tsch.). Individual\nbirds belonging to the Penelope family (P.\nrufiventris and adspersa, Tsch.) and the green\npepper-eater (Pteroglossus cæruleo-cinctus,\nTsch.) are found in the lower forests."];


// error messages

$messages["aescanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ce</kbd>' in the text where there is an '<kbd>ae</kbd>' ligature in the image."),
];
$messages["those"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>o</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
];
$messages["ignivcntris"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>c</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
];
$messages["Phcenisoma"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ce</kbd>' in the text where there is an '<kbd>oe</kbd>' ligature in the image."),
];
$messages["commascanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a period (<kbd>.</kbd>) in the text where there is a comma (<kbd>,</kbd>) in the image."),
];
$messages["Pieroglossus"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>i</kbd>' in the text where there is a '<kbd>t</kbd>' in the image."),
];
$messages["oe_ae"] = [
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the image that is marked as an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the text.  In italics they may look very similar, but the <kbd>æ</kbd> often has a little bump sticking up in the middle showing the vertical line of the a."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
];
$messages["ae_oe"] = [
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the image that is marked as an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the text.  In italics they may look very similar, but the <kbd>æ</kbd> often has a little bump sticking up in the middle showing the vertical line of the a."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
];
$messages["ae_oe2"] = [
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the image that is marked as an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the text.  In italics they may look very similar, <kbd>æ</kbd> often has a little bump sticking up in the middle showing the vertical line of the a.  Additionally, in Latin words the '<kbd>oe</kbd>' ligature never appears at the end of the word."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["com-"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["thacece", "cceru"], "case_sensitive" => true, "error" => "aescanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["those"], "case_sensitive" => true, "error" => "those"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ignivcntris"], "case_sensitive" => true, "error" => "ignivcntris"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Phcenisoma"], "case_sensitive" => true, "error" => "Phcenisoma"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["adspersa."], "case_sensitive" => true, "error" => "commascanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Pieroglossus"], "case_sensitive" => true, "error" => "Pieroglossus"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ae"], "case_sensitive" => true, "error" => "P_ae"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[oe]", "Phoe"], "case_sensitive" => true, "error" => "P_oe"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["c[oe]r", "coer", "cœr"], "case_sensitive" => true, "error" => "ae_oe"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["thace[oe]", "thaceoe", "thaceœ"], "case_sensitive" => true, "error" => "ae_oe2"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Phæ"], "case_sensitive" => true, "error" => "oe_ae"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
