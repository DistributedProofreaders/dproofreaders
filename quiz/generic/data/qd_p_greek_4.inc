<?php


$browser_title                = _("Greek Transliteration Quiz");
$intro_title                  = sprintf(_("Greek Transliteration Quiz, page %d"), 4);
$initial_instructions         = $initial_instructions__greek;
$initial_text                 = "how they kicked their crowns away from\nthem. Of all the enemies they had, they\nthemselves were the most fatal:\n\n[Greek: \"Ô popoi, ohion dê nu theous\nBrotoì aitioôntai,\nex êmeôn gap phaoi kak\nemmenai oi de kai antoi\nophêioin ataothaliêoin nter\nmoron alge eChouin]";
$solutions                    = array("how they kicked their crowns away from\nthem. Of all the enemies they had, they\nthemselves were the most fatal:\n\n[Greek: Ô popoi, hoion dê ny theous\nbrotoi aitioôntai,\nex hêmeôn gar phasi kak'\nemmenai; hoi de kai autoi\nsphêsin atasthaliêsin hyper\nmoron alge' echousin.]");
$parting_message              = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                // TRANSLATORS: PM = project manager
                                "<p>" . _("Occasionally a Project Manager asks proofreaders to include accents in their Greek transliterations.  Since this is not part of the normal guidelines, please only do this if the PM or PPer requests it.  If there are no special instructions, then you should ignore all Greek accents except the rough breathing mark.") . "</p>";


// error messages

$messages["beta_eszett"] = array(
    "message_title" => _("Beta proofread as ß"),
    "message_body" => _("The <kbd>ß</kbd> character is not a Greek letter.  Please transliterate <kbd>β</kbd> (beta) as '<kbd>b</kbd>', as shown in the Greek transliteration tool."),
);
$messages["Brotoi"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a capital '<kbd>B</kbd>' in the text where there is the Greek letter <kbd>β</kbd> (beta, transliterated '<kbd>b</kbd>') in the image."),
);
$messages["p_t"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>t</kbd>' in the text where there is the Greek letter <kbd>π</kbd> (pi, transliterated '<kbd>p</kbd>') in the image."),
);
$messages["missings"] = array(
    "message_title" => _("Missing letter"),
    "message_body" => _("There is an '<kbd>s</kbd>' (the transliteration of <kbd>σ</kbd>, sigma) missing in your transliteration."),
);
$messages["missingperiod"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a period (<kbd>.</kbd>) was omitted by the OCR software."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "P_indent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/*", "*/"), "case_sensitive" => TRUE, "error" => "P_poemmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("τ", "α", "κ", "ο"), "case_sensitive" => TRUE, "error" => "G_unicode");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("brotoi", "gar", "phasi", "autoi", "echousin"), "case_sensitive" => TRUE, "error" => "G_nochange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("greek"), "case_sensitive" => TRUE, "error" => "G_lcgreek");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek:"), "case_sensitive" => TRUE, "error" => "G_nomarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[Greek:\n"), "case_sensitive" => TRUE, "error" => "G_markupline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek: "), "case_sensitive" => TRUE, "error" => "G_markupspace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("]"), "case_sensitive" => TRUE, "error" => "G_endbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n]"), "case_sensitive" => TRUE, "error" => "G_markupline2");
$tests[] = array("type" => "multioccurrence", "searchtext" => "Greek", "case_sensitive" => TRUE, "error" => "G_markuprepeat");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("iou", "uu", "yu", "ôutai", "eôu", "meuai", "siu", "orou"), "case_sensitive" => TRUE, "error" => "G_n_u");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("nn", "eons", "antoi", "nper", "onsin"), "case_sensitive" => TRUE, "error" => "G_u_n");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("aoi ", "ataotha", "ophê", "oin"), "case_sensitive" => TRUE, "error" => "G_s_o");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" no ", "oos", "aotoi", "oper", "oosin"), "case_sensitive" => TRUE, "error" => "G_u_o");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" fas", "\nsf"), "case_sensitive" => TRUE, "error" => "G_ph_f");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Ch"), "case_sensitive" => TRUE, "error" => "G_chicase");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Th"), "case_sensitive" => TRUE, "error" => "G_thetacase");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ß"), "case_sensitive" => TRUE, "error" => "beta_eszett");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("O ", "on de", "oontai", "eme", "meon", "sphe", "lie"), "case_sensitive" => TRUE, "error" => "G_missingcirc");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("B"), "case_sensitive" => TRUE, "error" => "Brotoi");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("gap", "pep", "pot"), "case_sensitive" => TRUE, "error" => "G_r_p");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ter"), "case_sensitive" => TRUE, "error" => "p_t");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ouin"), "case_sensitive" => TRUE, "error" => "missings");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array(".]", "]."), "case_sensitive" => TRUE, "error" => "missingperiod");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("]."), "case_sensitive" => TRUE, "error" => "G_punctplace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("tai,"), "case_sensitive" => TRUE, "error" => "G_missingcomma");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" oion", " oi", " uper", " yper", " êmeôn"), "case_sensitive" => TRUE, "error" => "G_missingh");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ö", "é", "á", "è", "ó", "ì", "í", "\\", "/", "^"), "case_sensitive" => TRUE, "error" => "G_extraaccent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("phopoi", "dhê", "theohus", "tohi", "tiho", "mheôn", "khak", "dhe", "lhi", "pher", "mhoron", "ghar", "kahi"), "case_sensitive" => TRUE, "error" => "G_extrah");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ahitio", "haitio", "hex", "hô", "hemm", "haut", "ahut", "ahyt", "hatas", "halge", "hecho"), "case_sensitive" => FALSE, "error" => "G_smooth_rough");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ohi"), "case_sensitive" => TRUE, "error" => "G_rbdiph");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\"Ô", "'Ô"), "case_sensitive" => TRUE, "error" => "G_smooth_apos");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("êisin", "|"), "case_sensitive" => TRUE, "error" => "G_iotasub");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("kak\n", "alge "), "case_sensitive" => TRUE, "error" => "G_missingapos");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array(";"), "case_sensitive" => TRUE, "error" => "G_missingpunct");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("eoys", "aytoi", "oysin", "nu", "huper"), "case_sensitive" => TRUE, "error" => "G_upsilon");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("toi\n"), "case_sensitive" => TRUE, "error" => "G_missinglb");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("tai,\n"), "case_sensitive" => TRUE, "error" => "G_missinglb");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nbro"), "case_sensitive" => TRUE, "error" => "P_joinline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nemm"), "case_sensitive" => TRUE, "error" => "P_joinline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nmo"), "case_sensitive" => TRUE, "error" => "P_joinline");

