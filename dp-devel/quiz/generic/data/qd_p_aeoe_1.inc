<?php
include_once('quiz_defaults.inc'); // $ae_oe_ligatures_url $utf8_site

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_aeoe_1","pass");
}


$browser_title                = _("Ligatures Proofreading Quiz");
$intro_title                  = _("Ligatures Proofreading Quiz, page 1");
$initial_instructions         = $initial_instructions__P;
$ocr_text                     = "Colleges.' But his chef-d'ceuvre was his\nemphatic recognition of all the doctors, both the\nproctors, as if the numerical antithesis threw\nthose excellent personages into a charming\ntableau vinant.'\n\nThey proceeded down High Street, and then\nsaw Mr. Baternan, a bore at least of the second\nmagnitude. It is very difficult duly to delineate\na borc in a narrative, for the very reason that he\nis a bore. But eventually the truth bursts on\nyou, apparent dirae facies, you are in the\nclutches of a bore. You may yield, or you may\nflee; you cannot conquer.";
if(!$utf8_site) {
    $solutions                = array("Colleges.' But his chef-d'[oe]uvre was his\nemphatic recognition of all the doctors, both the\nproctors, as if the numerical antithesis threw\nthose excellent personages into a charming\ntableau vivant.\"\n\nThey proceeded down High Street, and then\nsaw Mr. Bateman, a bore at least of the second\nmagnitude. It is very difficult duly to delineate\na bore in a narrative, for the very reason that he\nis a bore. But eventually the truth bursts on\nyou, apparent diræ facies, you are in the\nclutches of a bore. You may yield, or you may\nflee; you cannot conquer.");
}
else {
    $solutions                = array("Colleges.' But his chef-d'œuvre was his\nemphatic recognition of all the doctors, both the\nproctors, as if the numerical antithesis threw\nthose excellent personages into a charming\ntableau vivant.\"\n\nThey proceeded down High Street, and then\nsaw Mr. Bateman, a bore at least of the second\nmagnitude. It is very difficult duly to delineate\na bore in a narrative, for the very reason that he\nis a bore. But eventually the truth bursts on\nyou, apparent diræ facies, you are in the\nclutches of a bore. You may yield, or you may\nflee; you cannot conquer.");
}
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("If something is not clear in the image, please leave a [**note] in the text or ask in the project discussion rather than guessing.") . "</p>";


// error messages

$messages["ceuvre"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: '<tt>ce</tt>' in the text where there is an '<tt>oe</tt>' ligature in the image.") . "</p>",
    "hints" => array(),
);
$messages["vinant"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an '<tt>n</tt>' in the text where there is a '<tt>v</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["quotescanno"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a single quote mark (<tt>'</tt>) in the text where there is a double quote mark (<tt>\"</tt>) in the image.") . "</p>",
    "hints" => array(),
);
$messages["Baternan"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: '<tt>rn</tt>' in the text where there is an '<tt>m</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["borc"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>c</tt>' in the text where there is an '<tt>e</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["oe_ae"] = array(
    "message_text" => "<h2>" . _("Ligature incorrectly identified") . "</h2>\n" .
        "<p>" . _("There is an &oelig; ('<tt>oe</tt>' ligature) in the image that is marked as an &aelig; ('<tt>ae</tt>' ligature) in the text.  In italics they may look very similar, but the &oelig; ligature is usually rounder at the top, while the 'a' of &aelig; is more teardrop-shaped.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url) . "</p>",
    "hints" => array(),
);
$messages["ae_oe"] = array(
    "message_text" => "<h2>" . _("Ligature incorrectly identified") . "</h2>\n" .
        "<p>" . _("There is an &aelig; ('<tt>ae</tt>' ligature) in the image that is marked as an &oelig; ('<tt>oe</tt>' ligature) in the text.  In italics they may look very similar, but the &oelig; ligature is usually rounder at the top, while the 'a' of &aelig; is more teardrop-shaped.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s' target='_blank'>ae and oe ligatures</a> wiki page for details."), $ae_oe_ligatures_url) . "</p>",
    "hints" => array(),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ceuvre"), "case_sensitive" => TRUE, "error" => "ceuvre");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("vinant"), "case_sensitive" => TRUE, "error" => "vinant");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ant.'"), "case_sensitive" => TRUE, "error" => "quotescanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Baternan"), "case_sensitive" => TRUE, "error" => "Baternan");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("borc"), "case_sensitive" => TRUE, "error" => "borc");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ae"), "case_sensitive" => TRUE, "error" => "P_ae");
if(!$utf8_site) {
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("oeu", "œ", chr(156)), "case_sensitive" => TRUE, "error" => "P_oe");
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("dir[oe]", "diroe"), "case_sensitive" => TRUE, "error" => "ae_oe");
}
else {
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("oeu", "[oe]"), "case_sensitive" => TRUE, "error" => "P_oe");
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("dir[oe]", "diroe", "dirœ"), "case_sensitive" => TRUE, "error" => "ae_oe");
}
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("æuvre"), "case_sensitive" => TRUE, "error" => "oe_ae");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");

// vim: sw=4 ts=4 expandtab
