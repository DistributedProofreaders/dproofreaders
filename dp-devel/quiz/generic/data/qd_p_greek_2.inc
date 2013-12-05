<?php

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_greek_2","pass");
}

$browser_title                = _("Greek Transliteration Quiz");
$intro_title                  = "<h2>" . _("Greek Transliteration Quiz, page 2") . "</h2>\n";
$initial_instructions         = "<p>" . _("Use the Greek Transliterator (link below) to proofread the Greek text.  There is no need to proofread the English text, as it is already correct. When done click 'check'.") . "</p>";
$ocr_text                     = "quaint epigram of Epigonus upon a frog,\nwho falling into a pipe of wine, cried\nout,\n\noe6 rives bSc)p\nirlvovai ,uavitjv ac6ippova uaiv&oacute;,usvoi.";
$solutions                    = array("quaint epigram of Epigonus upon a frog,\nwho falling into a pipe of wine, cried\nout,\n\n[Greek: pheu tines hydôr\npinousi maniên sôphrona mainomenoi.]");
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("In focusing your attention on the letters, it's easy not to notice that there are also punctuation marks in the Greek text. Check to be sure that all the punctuation is present in and around your transliterations.") . "</p>";


// error messages

$messages["p_ir"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: '<tt>ir</tt>' in the text where there is the Greek letter &pi; (pi, transliterated '<tt>p</tt>') in the image.") . "</p>",
    "hints" => array(),
);
$messages["m_u"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>u</tt>' in the text where there is the Greek letter &mu; (mu, transliterated '<tt>m</tt>') in the image.") . "</p>",
    "hints" => array(),
);
$messages["missingperiod"] = array(
    "message_text" => "<h2>" . _("Missing punctuation") . "</h2>\n" .
        "<p>" . _("A period (<tt>.</tt>) in the image is missing in your transliteration.") . "</p>",
    "hints" => array(),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "P_indent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/*", "*/"), "case_sensitive" => TRUE, "error" => "P_poemmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("out,\n\n"), "case_sensitive" => TRUE, "error" => "P_poetryblanklines");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\""), "case_sensitive" => TRUE, "error" => "P_addedquotes");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("&#961;", "&#945;", "&#953;", "&#956;", "µ"), "case_sensitive" => TRUE, "error" => "G_unicode");
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

// vim: sw=4 ts=4 expandtab
