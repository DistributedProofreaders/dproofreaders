<?php


$browser_title = _("Greek Transliteration Quiz");
$intro_title = sprintf(_("Greek Transliteration Quiz, page %d"), 4);
$initial_instructions = $initial_instructions__greek;
$initial_text = "how they kicked their crowns away from\nthem. Of all the enemies they had, they\nthemselves were the most fatal:\n\n[Greek: \"Ô popoi, ohion dê nu theous\nBrotoì aitioôntai,\nex êmeôn gap phaoi kak\nemmenai oi de kai antoi\nophêioin ataothaliêoin nter\nmoron alge eChouin]";
$solutions = ["how they kicked their crowns away from\nthem. Of all the enemies they had, they\nthemselves were the most fatal:\n\n[Greek: Ô popoi, hoion dê ny theous\nbrotoi aitioôntai,\nex hêmeôn gar phasi kak'\nemmenai; hoi de kai autoi\nsphêsin atasthaliêsin hyper\nmoron alge' echousin.]"];
$parting_message = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                // TRANSLATORS: PM = project manager
                                "<p>" . _("Occasionally a Project Manager asks proofreaders to include accents in their Greek transliterations.  Since this is not part of the normal guidelines, please only do this if the PM or PPer requests it.  If there are no special instructions, then you should ignore all Greek accents except the rough breathing mark.") . "</p>";


// error messages

$messages["beta_eszett"] = [
    "message_title" => _("Beta proofread as ß"),
    "message_body" => _("The <kbd>ß</kbd> character is not a Greek letter.  Please transliterate <kbd>β</kbd> (beta) as '<kbd>b</kbd>', as shown in the Greek transliteration tool."),
];
$messages["Brotoi"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a capital '<kbd>B</kbd>' in the text where there is the Greek letter <kbd>β</kbd> (beta, transliterated '<kbd>b</kbd>') in the image."),
];
$messages["p_t"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>t</kbd>' in the text where there is the Greek letter <kbd>π</kbd> (pi, transliterated '<kbd>p</kbd>') in the image."),
];
$messages["missings"] = [
    "message_title" => _("Missing letter"),
    "message_body" => _("There is an '<kbd>s</kbd>' (the transliteration of <kbd>σ</kbd>, sigma) missing in your transliteration."),
];
$messages["missingperiod"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a period (<kbd>.</kbd>) was omitted by the OCR software."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n "], "case_sensitive" => true, "error" => "P_indent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/*", "*/"], "case_sensitive" => true, "error" => "P_poemmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["τ", "α", "κ", "ο"], "case_sensitive" => true, "error" => "G_unicode"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["brotoi", "gar", "phasi", "autoi", "echousin"], "case_sensitive" => true, "error" => "G_nochange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["greek"], "case_sensitive" => true, "error" => "G_lcgreek"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek:"], "case_sensitive" => true, "error" => "G_nomarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[Greek:\n"], "case_sensitive" => true, "error" => "G_markupline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek: "], "case_sensitive" => true, "error" => "G_markupspace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["]"], "case_sensitive" => true, "error" => "G_endbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n]"], "case_sensitive" => true, "error" => "G_markupline2"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "Greek", "case_sensitive" => true, "error" => "G_markuprepeat"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["iou", "uu", "yu", "ôutai", "eôu", "meuai", "siu", "orou"], "case_sensitive" => true, "error" => "G_n_u"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["nn", "eons", "antoi", "nper", "onsin"], "case_sensitive" => true, "error" => "G_u_n"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["aoi ", "ataotha", "ophê", "oin"], "case_sensitive" => true, "error" => "G_s_o"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" no ", "oos", "aotoi", "oper", "oosin"], "case_sensitive" => true, "error" => "G_u_o"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" fas", "\nsf"], "case_sensitive" => true, "error" => "G_ph_f"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Ch"], "case_sensitive" => true, "error" => "G_chicase"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Th"], "case_sensitive" => true, "error" => "G_thetacase"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ß"], "case_sensitive" => true, "error" => "beta_eszett"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["O ", "on de", "oontai", "eme", "meon", "sphe", "lie"], "case_sensitive" => true, "error" => "G_missingcirc"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["B"], "case_sensitive" => true, "error" => "Brotoi"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["gap", "pep", "pot"], "case_sensitive" => true, "error" => "G_r_p"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ter"], "case_sensitive" => true, "error" => "p_t"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ouin"], "case_sensitive" => true, "error" => "missings"];
$tests[] = ["type" => "expectedtext", "searchtext" => [".]", "]."], "case_sensitive" => true, "error" => "missingperiod"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["]."], "case_sensitive" => true, "error" => "G_punctplace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["tai,"], "case_sensitive" => true, "error" => "G_missingcomma"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" oion", " oi", " uper", " yper", " êmeôn"], "case_sensitive" => true, "error" => "G_missingh"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ö", "é", "á", "è", "ó", "ì", "í", "\\", "/", "^"], "case_sensitive" => true, "error" => "G_extraaccent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["phopoi", "dhê", "theohus", "tohi", "tiho", "mheôn", "khak", "dhe", "lhi", "pher", "mhoron", "ghar", "kahi"], "case_sensitive" => true, "error" => "G_extrah"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ahitio", "haitio", "hex", "hô", "hemm", "haut", "ahut", "ahyt", "hatas", "halge", "hecho"], "case_sensitive" => false, "error" => "G_smooth_rough"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ohi"], "case_sensitive" => true, "error" => "G_rbdiph"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\"Ô", "'Ô"], "case_sensitive" => true, "error" => "G_smooth_apos"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["êisin", "|"], "case_sensitive" => true, "error" => "G_iotasub"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["kak\n", "alge "], "case_sensitive" => true, "error" => "G_missingapos"];
$tests[] = ["type" => "expectedtext", "searchtext" => [";"], "case_sensitive" => true, "error" => "G_missingpunct"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["eoys", "aytoi", "oysin", "nu", "huper"], "case_sensitive" => true, "error" => "G_upsilon"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["toi\n"], "case_sensitive" => true, "error" => "G_missinglb"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["tai,\n"], "case_sensitive" => true, "error" => "G_missinglb"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nbro"], "case_sensitive" => true, "error" => "P_joinline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nemm"], "case_sensitive" => true, "error" => "P_joinline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nmo"], "case_sensitive" => true, "error" => "P_joinline"];
