<?php


$browser_title                = _("Greek Transliteration Quiz");
$intro_title                  = sprintf(_("Greek Transliteration Quiz, page %d"), 2);
$initial_instructions         = $initial_instructions__greek;
$initial_text                 = "quaint epigram of Epigonus upon a frog,\nwho falling into a pipe of wine, cried\nout,\n\noe6 rives bSc)p\nirlvovai ,uavitjv ac6ippova uaivó,usvoi.";
$solutions                    = array("quaint epigram of Epigonus upon a frog,\nwho falling into a pipe of wine, cried\nout,\n\n[Greek: pheu tines hydôr\npinousi maniên sôphrona mainomenoi.]");
$parting_message              = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("In focusing your attention on the letters, it's easy not to notice that there are also punctuation marks in the Greek text. Check to be sure that all the punctuation is present in and around your transliterations.") . "</p>";


// error messages

$messages["p_ir"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ir</kbd>' in the text where there is the Greek letter <kbd>π</kbd> (pi, transliterated '<kbd>p</kbd>') in the image."),
);
$messages["m_u"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>u</kbd>' in the text where there is the Greek letter <kbd>μ</kbd> (mu, transliterated '<kbd>m</kbd>') in the image."),
);
$messages["missingperiod"] = array(
    "message_title" => _("Missing punctuation"),
    "message_body" => _("A period (<kbd>.</kbd>) in the image is missing in your transliteration."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "P_indent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/*", "*/"), "case_sensitive" => TRUE, "error" => "P_poemmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("out,\n\n"), "case_sensitive" => TRUE, "error" => "P_poetryblanklines");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\""), "case_sensitive" => TRUE, "error" => "P_addedquotes");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ρ", "α", "ι", "µ"), "case_sensitive" => TRUE, "error" => "G_unicode");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("pheu", "tines", "pinousi", "mani", "rona", "main", "enoi"), "case_sensitive" => TRUE, "error" => "G_nochange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("greek"), "case_sensitive" => TRUE, "error" => "G_lcgreek");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek:"), "case_sensitive" => TRUE, "error" => "G_nomarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[Greek:\n"), "case_sensitive" => TRUE, "error" => "G_markupline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek: "), "case_sensitive" => TRUE, "error" => "G_markupspace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("]"), "case_sensitive" => TRUE, "error" => "G_endbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n]"), "case_sensitive" => TRUE, "error" => "G_markupline2");
$tests[] = array("type" => "multioccurrence", "searchtext" => "Greek", "case_sensitive" => TRUE, "error" => "G_markuprepeat");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("iues", "piuo", "maui", "iêu", "maiu", "euoi", "roua"), "case_sensitive" => TRUE, "error" => "G_n_u");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("phen", "ndôr", "onsi"), "case_sensitive" => TRUE, "error" => "G_u_n");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ouoi", "oôph"), "case_sensitive" => TRUE, "error" => "G_s_o");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("oosi", "odôr"), "case_sensitive" => TRUE, "error" => "G_u_o");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Ph"), "case_sensitive" => TRUE, "error" => "G_phicase");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("dor", "ien", "soph"), "case_sensitive" => TRUE, "error" => "G_missingcirc");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("v"), "case_sensitive" => TRUE, "error" => "G_n_v");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ir"), "case_sensitive" => TRUE, "error" => "p_ir");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("p\n", "pona"), "case_sensitive" => TRUE, "error" => "G_r_p");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" uai", "uen"), "case_sensitive" => TRUE, "error" => "m_u");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" fe", "ôf"), "case_sensitive" => FALSE, "error" => "G_ph_f");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("]."), "case_sensitive" => TRUE, "error" => "G_punctplace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("."), "case_sensitive" => TRUE, "error" => "missingperiod");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" udôr", " ydô"), "case_sensitive" => TRUE, "error" => "G_missingh");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ú", "ý", "í", "ó", "/", "\\", "^"), "case_sensitive" => TRUE, "error" => "G_extraaccent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("phehu", "thines", "phin", "manhiê", "shô", "mainho"), "case_sensitive" => TRUE, "error" => "G_extrah");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("phey", "noysi", "hudôr"), "case_sensitive" => TRUE, "error" => "G_upsilon");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\np"), "case_sensitive" => TRUE, "error" => "G_missinglb");

