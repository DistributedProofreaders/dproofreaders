<?php
include_once('quiz_defaults.inc'); // $Greek_translit_url


$browser_title                = _("Greek Transliteration Quiz");
$intro_title                  = sprintf(_("Greek Transliteration Quiz, page %d"), 5);
$initial_instructions         = $initial_instructions__greek;
$initial_text                 = "a profound impression on the coolest\nspectator, even in that age, when men\nwere more accustomed to stabbing\nthan in our delicate days of gunshot\nwounds. 'O be (BeAiaágioc) xarariAayeis\notiioco re arieSn Kai Biaaa eyy6s ri8\nectjxóri tiegitiAaxels Siacpnyeiv tayvai--\n(De Bello Gotthico, ii. 8.)";
$solutions                    = array("a profound impression on the coolest\nspectator, even in that age, when men\nwere more accustomed to stabbing\nthan in our delicate days of gunshot\nwounds. [Greek: Ho de (Belisarios) kataplageis\nopisô te apestê kai Bessa engys pou\nestêkoti periplakeis diaphygein ischysi]--(De\nBello Gotthico, ii. 8.)");
$parting_message              = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . sprintf(_("If you see other Greek characters that don't appear in the Greek transliteration tool, try looking at the wiki <a href='%s' target='_blank'>Transliterating Greek</a> article.  The 'Older and Obscure Items' section shows variant forms of some more letters and has links to ligature charts, which can help you to identify unusual symbols."), $Greek_translit_url) . "</p>";


// error messages

$messages["d_b"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>b</kbd>' in the text where there is the Greek letter <kbd>δ</kbd> (delta, transliterated '<kbd>d</kbd>') in the image."),
);
$messages["g_y"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>y</kbd>' in the text where there is the Greek letter <kbd>γ</kbd> (gamma, transliterated '<kbd>g</kbd>') in the image."),
);
$messages["t_r"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>r</kbd>' in the text where there is the Greek letter <kbd>τ</kbd> (tau, transliterated '<kbd>t</kbd>') in the image."),
);
$messages["Bissa"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>i</kbd>' in the text where there is the Greek letter <kbd>ε</kbd> (epsilon, transliterated '<kbd>e</kbd>') in the image."),
);
$messages["missingparen"] = array(
    "message_title" => _("Missing punctuation"),
    "message_body" => _("A parenthesis in the image is missing in your transliteration."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("---"), "case_sensitive" => TRUE, "error" => "P_em_long");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("-- ", " --"), "case_sensitive" => TRUE, "error" => "P_spacedemdash");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("--\n"), "case_sensitive" => TRUE, "error" => "P_eoldash");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("α", "ι", "ε", "π"), "case_sensitive" => TRUE, "error" => "G_unicode");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("opisô", " te ", "isch", "diaph", "plageis"), "case_sensitive" => TRUE, "error" => "G_nochange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("greek"), "case_sensitive" => TRUE, "error" => "G_lcgreek");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek:"), "case_sensitive" => TRUE, "error" => "G_nomarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[Greek:\n"), "case_sensitive" => TRUE, "error" => "G_markupline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek: "), "case_sensitive" => TRUE, "error" => "G_markupspace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("]"), "case_sensitive" => TRUE, "error" => "G_endbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n]"), "case_sensitive" => TRUE, "error" => "G_markupline2");
$tests[] = array("type" => "multioccurrence", "searchtext" => "Greek", "case_sensitive" => TRUE, "error" => "G_markuprepeat");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("geiu"), "case_sensitive" => TRUE, "error" => "G_n_u");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("gns", "nge", "chns"), "case_sensitive" => TRUE, "error" => "G_u_n");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ioa", "ioô", "eooa", "eosa", "ioch", "oi]"), "case_sensitive" => TRUE, "error" => "G_s_o");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("chata", "chai", "choti", "acheis"), "case_sensitive" => TRUE, "error" => "G_k_ch");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("gos", "oge", "chosi"), "case_sensitive" => TRUE, "error" => "G_u_o");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("aps"), "case_sensitive" => TRUE, "error" => "G_ph_ps");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Ch"), "case_sensitive" => TRUE, "error" => "G_chicase");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("opiso", "e kai", "ekoti"), "case_sensitive" => TRUE, "error" => "G_missingcirc");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" be "), "case_sensitive" => TRUE, "error" => "d_b");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("xata", "xoti", "xeis"), "case_sensitive" => TRUE, "error" => "G_k_x");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("yei"), "case_sensitive" => TRUE, "error" => "g_y");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ioc"), "case_sensitive" => TRUE, "error" => "G_s_c");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" re ", "arap", "ori "), "case_sensitive" => TRUE, "error" => "t_r");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("iaf"), "case_sensitive" => TRUE, "error" => "G_ph_f");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Bissa"), "case_sensitive" => TRUE, "error" => "Bissa");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("--]"), "case_sensitive" => TRUE, "error" => "G_punctplace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("(B"), "case_sensitive" => FALSE, "error" => "missingparen");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array(") "), "case_sensitive" => TRUE, "error" => "missingparen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("è", "á", "ì", "í", "é", "ó", "ú", "ý", "\\", "/", "^"), "case_sensitive" => TRUE, "error" => "G_extraaccent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("dhe", "lisha", "gehis", "phisô", "phe", "ahi", "Bhe", "ghus", "ghys", "khoti", "ehis", "ehin"), "case_sensitive" => TRUE, "error" => "G_extrah");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("hopisô", "hape", "hegg", "heng", "hestê", "hesê", "hisch"), "case_sensitive" => TRUE, "error" => "G_smooth_rough");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("'O"), "case_sensitive" => TRUE, "error" => "G_rough_apos");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" O"), "case_sensitive" => TRUE, "error" => "G_capmissingh");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("HO", "hO"), "case_sensitive" => TRUE, "error" => "G_rbcap");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("egg"), "case_sensitive" => TRUE, "error" => "G_gg");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("poy", "gus", "phug", "chusi"), "case_sensitive" => TRUE, "error" => "G_upsilon");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("pou"), "case_sensitive" => TRUE, "error" => "G_oulig");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("arios"), "case_sensitive" => TRUE, "error" => "G_varrho");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("peri"), "case_sensitive" => TRUE, "error" => "G_varrho");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nopis"), "case_sensitive" => TRUE, "error" => "G_missinglb");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("ou\ne"), "case_sensitive" => TRUE, "error" => "G_missinglb");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("pest"), "case_sensitive" => TRUE, "error" => "G_stigma");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nest"), "case_sensitive" => TRUE, "error" => "G_stigma");

// vim: sw=4 ts=4 expandtab
