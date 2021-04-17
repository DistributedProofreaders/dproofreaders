<?php


$browser_title = _("Greek Transliteration Quiz");
$intro_title = sprintf(_("Greek Transliteration Quiz, page %d"), 2);
$initial_instructions = $initial_instructions__greek;
$initial_text = "quaint epigram of Epigonus upon a frog,\nwho falling into a pipe of wine, cried\nout,\n\noe6 rives bSc)p\nirlvovai ,uavitjv ac6ippova uaivó,usvoi.";
$solutions = ["quaint epigram of Epigonus upon a frog,\nwho falling into a pipe of wine, cried\nout,\n\n[Greek: pheu tines hydôr\npinousi maniên sôphrona mainomenoi.]"];
$parting_message = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("In focusing your attention on the letters, it's easy not to notice that there are also punctuation marks in the Greek text. Check to be sure that all the punctuation is present in and around your transliterations.") . "</p>";


// error messages

$messages["p_ir"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ir</kbd>' in the text where there is the Greek letter <kbd>π</kbd> (pi, transliterated '<kbd>p</kbd>') in the image."),
];
$messages["m_u"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>u</kbd>' in the text where there is the Greek letter <kbd>μ</kbd> (mu, transliterated '<kbd>m</kbd>') in the image."),
];
$messages["missingperiod"] = [
    "message_title" => _("Missing punctuation"),
    "message_body" => _("A period (<kbd>.</kbd>) in the image is missing in your transliteration."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n "], "case_sensitive" => true, "error" => "P_indent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/*", "*/"], "case_sensitive" => true, "error" => "P_poemmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["out,\n\n"], "case_sensitive" => true, "error" => "P_poetryblanklines"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\""], "case_sensitive" => true, "error" => "P_addedquotes"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ρ", "α", "ι", "µ"], "case_sensitive" => true, "error" => "G_unicode"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["pheu", "tines", "pinousi", "mani", "rona", "main", "enoi"], "case_sensitive" => true, "error" => "G_nochange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["greek"], "case_sensitive" => true, "error" => "G_lcgreek"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek:"], "case_sensitive" => true, "error" => "G_nomarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[Greek:\n"], "case_sensitive" => true, "error" => "G_markupline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek: "], "case_sensitive" => true, "error" => "G_markupspace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["]"], "case_sensitive" => true, "error" => "G_endbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n]"], "case_sensitive" => true, "error" => "G_markupline2"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "Greek", "case_sensitive" => true, "error" => "G_markuprepeat"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["iues", "piuo", "maui", "iêu", "maiu", "euoi", "roua"], "case_sensitive" => true, "error" => "G_n_u"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["phen", "ndôr", "onsi"], "case_sensitive" => true, "error" => "G_u_n"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ouoi", "oôph"], "case_sensitive" => true, "error" => "G_s_o"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["oosi", "odôr"], "case_sensitive" => true, "error" => "G_u_o"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Ph"], "case_sensitive" => true, "error" => "G_phicase"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["dor", "ien", "soph"], "case_sensitive" => true, "error" => "G_missingcirc"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["v"], "case_sensitive" => true, "error" => "G_n_v"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ir"], "case_sensitive" => true, "error" => "p_ir"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["p\n", "pona"], "case_sensitive" => true, "error" => "G_r_p"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" uai", "uen"], "case_sensitive" => true, "error" => "m_u"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" fe", "ôf"], "case_sensitive" => false, "error" => "G_ph_f"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["]."], "case_sensitive" => true, "error" => "G_punctplace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["."], "case_sensitive" => true, "error" => "missingperiod"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" udôr", " ydô"], "case_sensitive" => true, "error" => "G_missingh"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ú", "ý", "í", "ó", "/", "\\", "^"], "case_sensitive" => true, "error" => "G_extraaccent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["phehu", "thines", "phin", "manhiê", "shô", "mainho"], "case_sensitive" => true, "error" => "G_extrah"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["phey", "noysi", "hudôr"], "case_sensitive" => true, "error" => "G_upsilon"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\np"], "case_sensitive" => true, "error" => "G_missinglb"];
