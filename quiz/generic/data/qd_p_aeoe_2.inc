<?php


$browser_title                = _("Ligatures Proofreading Quiz");
$intro_title                  = sprintf(_("Ligatures Proofreading Quiz, page %d"), 2);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "apple. The lofty Terebinthacece, with their\nwalnut-like fruit, are inhabited by swarms of\nsquirrels, as well as birds remarkable for their\ngay-colored plumage. Among the most common\nin those districts are the red-bellied tanagra\n(Tanagra ignivcntris, Orb.) and the fire-colored\npyranga (Phcenisoma bivittata, Tsch.). Individual\nbirds belonging to the Penelope family (P.\nrufiventris and adspersa. Tsch.) and the green\npepper-eater (Pieroglossus cceruleo-cinctus,\nTsch.) are found in the lower forests.";
$solutions                    = array("apple. The lofty Terebinthaceæ, with their\nwalnut-like fruit, are inhabited by swarms of\nsquirrels, as well as birds remarkable for their\ngay-colored plumage. Among the most common\nin these districts are the red-bellied tanagra\n(Tanagra igniventris, Orb.) and the fire-colored\npyranga (Phœnisoma bivittata, Tsch.). Individual\nbirds belonging to the Penelope family (P.\nrufiventris and adspersa, Tsch.) and the green\npepper-eater (Pteroglossus cæruleo-cinctus,\nTsch.) are found in the lower forests.");


// error messages

$messages["aescanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ce</kbd>' in the text where there is an '<kbd>ae</kbd>' ligature in the image."),
);
$messages["those"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>o</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
);
$messages["ignivcntris"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>c</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
);
$messages["Phcenisoma"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ce</kbd>' in the text where there is an '<kbd>oe</kbd>' ligature in the image."),
);
$messages["commascanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a period (<kbd>.</kbd>) in the text where there is a comma (<kbd>,</kbd>) in the image."),
);
$messages["Pieroglossus"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>i</kbd>' in the text where there is a '<kbd>t</kbd>' in the image."),
);
$messages["oe_ae"] = array(
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the image that is marked as an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the text.  In italics they may look very similar, but the <kbd>æ</kbd> often has a little bump sticking up in the middle showing the vertical line of the a."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
);
$messages["ae_oe"] = array(
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the image that is marked as an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the text.  In italics they may look very similar, but the <kbd>æ</kbd> often has a little bump sticking up in the middle showing the vertical line of the a."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
);
$messages["ae_oe2"] = array(
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an <kbd>æ</kbd> ('<kbd>ae</kbd>' ligature) in the image that is marked as an <kbd>œ</kbd> ('<kbd>oe</kbd>' ligature) in the text.  In italics they may look very similar, <kbd>æ</kbd> often has a little bump sticking up in the middle showing the vertical line of the a.  Additionally, in Latin words the '<kbd>oe</kbd>' ligature never appears at the end of the word."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("com-"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("thacece", "cceru"), "case_sensitive" => TRUE, "error" => "aescanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("those"), "case_sensitive" => TRUE, "error" => "those");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ignivcntris"), "case_sensitive" => TRUE, "error" => "ignivcntris");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Phcenisoma"), "case_sensitive" => TRUE, "error" => "Phcenisoma");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("adspersa."), "case_sensitive" => TRUE, "error" => "commascanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Pieroglossus"), "case_sensitive" => TRUE, "error" => "Pieroglossus");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ae"), "case_sensitive" => TRUE, "error" => "P_ae");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[oe]", "Phoe"), "case_sensitive" => TRUE, "error" => "P_oe");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("c[oe]r", "coer", "cœr"), "case_sensitive" => TRUE, "error" => "ae_oe");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("thace[oe]", "thaceoe", "thaceœ"), "case_sensitive" => TRUE, "error" => "ae_oe2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Phæ"), "case_sensitive" => TRUE, "error" => "oe_ae");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");

// vim: sw=4 ts=4 expandtab
