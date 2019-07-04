<?php
include_once('quiz_defaults.inc'); // $ae_oe_ligatures_url


$browser_title                = _("Ligatures Proofreading Quiz");
$intro_title                  = sprintf(_("Ligatures Proofreading Quiz, page %d"), 2);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "apple. The lofty Terebinthacece, with their\nwalnut-like fruit, are inhabited by swarms of\nsquirrels, as well as birds remarkable for their\ngay-colored plumage. Among the most common\nin those districts are the red-bellied tanagra\n(Tanagra ignivcntris, Orb.) and the fire-colored\npyranga (Phcenisoma bivittata, Tsch.). Individual\nbirds belonging to the Penelope family (P.\nrufiventris and adspersa. Tsch.) and the green\npepper-eater (Pieroglossus cceruleo-cinctus,\nTsch.) are found in the lower forests.";
$solutions                    = array("apple. The lofty Terebinthaceæ, with their\nwalnut-like fruit, are inhabited by swarms of\nsquirrels, as well as birds remarkable for their\ngay-colored plumage. Among the most common\nin these districts are the red-bellied tanagra\n(Tanagra igniventris, Orb.) and the fire-colored\npyranga (Phœnisoma bivittata, Tsch.). Individual\nbirds belonging to the Penelope family (P.\nrufiventris and adspersa, Tsch.) and the green\npepper-eater (Pteroglossus cæruleo-cinctus,\nTsch.) are found in the lower forests.");


// error messages

$messages["aescanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<tt>ce</tt>' in the text where there is an '<tt>ae</tt>' ligature in the image."),
);
$messages["those"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>o</tt>' in the text where there is an '<tt>e</tt>' in the image."),
);
$messages["ignivcntris"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<tt>c</tt>' in the text where there is an '<tt>e</tt>' in the image."),
);
$messages["Phcenisoma"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<tt>ce</tt>' in the text where there is an '<tt>oe</tt>' ligature in the image."),
);
$messages["commascanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a period (<tt>.</tt>) in the text where there is a comma (<tt>,</tt>) in the image."),
);
$messages["Pieroglossus"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>i</tt>' in the text where there is a '<tt>t</tt>' in the image."),
);
$messages["oe_ae"] = array(
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an &oelig; ('<tt>oe</tt>' ligature) in the image that is marked as an &aelig; ('<tt>ae</tt>' ligature) in the text.  In italics they may look very similar, but the &aelig; often has a little bump sticking up in the middle showing the vertical line of the a."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
);
$messages["ae_oe"] = array(
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an &aelig; ('<tt>ae</tt>' ligature) in the image that is marked as an &oelig; ('<tt>oe</tt>' ligature) in the text.  In italics they may look very similar, but the &aelig; often has a little bump sticking up in the middle showing the vertical line of the a."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url),
);
$messages["ae_oe2"] = array(
    "message_title" => _("Ligature incorrectly identified"),
    "message_body" => _("There is an &aelig; ('<tt>ae</tt>' ligature) in the image that is marked as an &oelig; ('<tt>oe</tt>' ligature) in the text.  In italics they may look very similar, but the &aelig; often has a little bump sticking up in the middle showing the vertical line of the a.  Additionally, in Latin words the '<tt>oe</tt>' ligature never appears at the end of the word."),
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
