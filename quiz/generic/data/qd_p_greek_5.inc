<?php


$browser_title = _("Greek Transliteration Quiz");
$intro_title = sprintf(_("Greek Transliteration Quiz, page %d"), 5);
$initial_instructions = $initial_instructions__greek;
$initial_text = "a profound impression on the coolest\nspectator, even in that age, when men\nwere more accustomed to stabbing\nthan in our delicate days of gunshot\nwounds. 'O be (BeAiaágioc) xarariAayeis\notiioco re arieSn Kai Biaaa eyy6s ri8\nectjxóri tiegitiAaxels Siacpnyeiv tayvai--\n(De Bello Gotthico, ii. 8.)";
$solutions = ["a profound impression on the coolest\nspectator, even in that age, when men\nwere more accustomed to stabbing\nthan in our delicate days of gunshot\nwounds. [Greek: Ho de (Belisarios) kataplageis\nopisô te apestê kai Bessa engys pou\nestêkoti periplakeis diaphygein ischysi]--(De\nBello Gotthico, ii. 8.)"];
$parting_message = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . sprintf(_("If you see other Greek characters that don't appear in the Greek transliteration tool, try looking at the wiki <a href='%s' target='_blank'>Transliterating Greek</a> article.  The 'Older and Obscure Items' section shows variant forms of some more letters and has links to ligature charts, which can help you to identify unusual symbols."), $Greek_translit_url) . "</p>";


// error messages

$messages["d_b"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>b</kbd>' in the text where there is the Greek letter <kbd>δ</kbd> (delta, transliterated '<kbd>d</kbd>') in the image."),
];
$messages["g_y"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>y</kbd>' in the text where there is the Greek letter <kbd>γ</kbd> (gamma, transliterated '<kbd>g</kbd>') in the image."),
];
$messages["t_r"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>r</kbd>' in the text where there is the Greek letter <kbd>τ</kbd> (tau, transliterated '<kbd>t</kbd>') in the image."),
];
$messages["Bissa"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>i</kbd>' in the text where there is the Greek letter <kbd>ε</kbd> (epsilon, transliterated '<kbd>e</kbd>') in the image."),
];
$messages["missingparen"] = [
    "message_title" => _("Missing punctuation"),
    "message_body" => _("A parenthesis in the image is missing in your transliteration."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["---"], "case_sensitive" => true, "error" => "P_em_long"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-- ", " --"], "case_sensitive" => true, "error" => "P_spacedemdash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["--\n"], "case_sensitive" => true, "error" => "P_eoldash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["α", "ι", "ε", "π"], "case_sensitive" => true, "error" => "G_unicode"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["opisô", " te ", "isch", "diaph", "plageis"], "case_sensitive" => true, "error" => "G_nochange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["greek"], "case_sensitive" => true, "error" => "G_lcgreek"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek:"], "case_sensitive" => true, "error" => "G_nomarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[Greek:\n"], "case_sensitive" => true, "error" => "G_markupline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek: "], "case_sensitive" => true, "error" => "G_markupspace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["]"], "case_sensitive" => true, "error" => "G_endbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n]"], "case_sensitive" => true, "error" => "G_markupline2"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "Greek", "case_sensitive" => true, "error" => "G_markuprepeat"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["geiu"], "case_sensitive" => true, "error" => "G_n_u"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["gns", "nge", "chns"], "case_sensitive" => true, "error" => "G_u_n"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ioa", "ioô", "eooa", "eosa", "ioch", "oi]"], "case_sensitive" => true, "error" => "G_s_o"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["chata", "chai", "choti", "acheis"], "case_sensitive" => true, "error" => "G_k_ch"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["gos", "oge", "chosi"], "case_sensitive" => true, "error" => "G_u_o"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["aps"], "case_sensitive" => true, "error" => "G_ph_ps"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Ch"], "case_sensitive" => true, "error" => "G_chicase"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["opiso", "e kai", "ekoti"], "case_sensitive" => true, "error" => "G_missingcirc"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" be "], "case_sensitive" => true, "error" => "d_b"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["xata", "xoti", "xeis"], "case_sensitive" => true, "error" => "G_k_x"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["yei"], "case_sensitive" => true, "error" => "g_y"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ioc"], "case_sensitive" => true, "error" => "G_s_c"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" re ", "arap", "ori "], "case_sensitive" => true, "error" => "t_r"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["iaf"], "case_sensitive" => true, "error" => "G_ph_f"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Bissa"], "case_sensitive" => true, "error" => "Bissa"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["--]"], "case_sensitive" => true, "error" => "G_punctplace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["(B"], "case_sensitive" => false, "error" => "missingparen"];
$tests[] = ["type" => "expectedtext", "searchtext" => [") "], "case_sensitive" => true, "error" => "missingparen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["è", "á", "ì", "í", "é", "ó", "ú", "ý", "\\", "/", "^"], "case_sensitive" => true, "error" => "G_extraaccent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["dhe", "lisha", "gehis", "phisô", "phe", "ahi", "Bhe", "ghus", "ghys", "khoti", "ehis", "ehin"], "case_sensitive" => true, "error" => "G_extrah"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["hopisô", "hape", "hegg", "heng", "hestê", "hesê", "hisch"], "case_sensitive" => true, "error" => "G_smooth_rough"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["'O"], "case_sensitive" => true, "error" => "G_rough_apos"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" O"], "case_sensitive" => true, "error" => "G_capmissingh"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["HO", "hO"], "case_sensitive" => true, "error" => "G_rbcap"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["egg"], "case_sensitive" => true, "error" => "G_gg"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["poy", "gus", "phug", "chusi"], "case_sensitive" => true, "error" => "G_upsilon"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["pou"], "case_sensitive" => true, "error" => "G_oulig"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["arios"], "case_sensitive" => true, "error" => "G_varrho"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["peri"], "case_sensitive" => true, "error" => "G_varrho"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nopis"], "case_sensitive" => true, "error" => "G_missinglb"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["ou\ne"], "case_sensitive" => true, "error" => "G_missinglb"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["pest"], "case_sensitive" => true, "error" => "G_stigma"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nest"], "case_sensitive" => true, "error" => "G_stigma"];
