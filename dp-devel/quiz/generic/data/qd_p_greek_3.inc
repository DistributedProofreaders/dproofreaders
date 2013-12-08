<?php


$browser_title                = _("Greek Transliteration Quiz");
$intro_title                  = _("Greek Transliteration Quiz, page 3");
$initial_instructions         = $initial_instructions__greek;
$ocr_text                     = "the famous knight Sir Guy, the slayer of the\ngreat Dun Cow which had laid waste the\nwhole county of Warwick. But besides slaying\nthe cow, he did many other noble deeds, like\nthe heroes of old:\n\n[Greek: ton '&Ecirc;rhaklea ton Di&ograve;s aneipov\nellonon kai t&ocirc; t&ecirc;s A&ecirc;das niee,\nMin&ocirc; te ohimai nomo0et&ecirc;n ka&igrave;\n'Radamanthnn ton Kn&ocirc;oion";
$solutions                    = array("the famous knight Sir Guy, the slayer of the\ngreat Dun Cow which had laid waste the\nwhole county of Warwick. But besides slaying\nthe cow, he did many other noble deeds, like\nthe heroes of old:\n\n[Greek: ton Hêraklea tou Dios aneipon\nengonon kai tô tês Lêdas hyiee,\nMinô te oimai ton nomothetên kai\nRhadamanthyn ton Knôsion]");
$parting_message              = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("The spelling of the letter 'rho' can help you to remember that the '<tt>h</tt>' goes after the '<tt>r</tt>' when marking rough breathing on rho.") . "</p>";


// error messages

$messages["L_D"] = array(
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is a '<tt>D</tt>' in your transliteration that should be an '<tt>L</tt>'.  The Greek letter &Delta; (capital delta, transliterated '<tt>D</tt>') is a triangle, while &Lambda; (capital lambda, transliterated '<tt>L</tt>') looks like an upside-down V."),
);
$messages["g_l"] = array(
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is an '<tt>l</tt>' in your transliteration that should be a '<tt>g</tt>' or '<tt>n</tt>'.  The Greek letter lambda (transliterated '<tt>l</tt>') looks like &lambda;, while the Greek letter gamma (transliterated '<tt>g</tt>' or '<tt>n</tt>') looks like &gamma;."),
);
$messages["L_A"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>A</tt>' in the text where there is the Greek letter &Lambda; (capital lambda, transliterated '<tt>L</tt>') in the image."),
);
$messages["mo0et"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<tt>0</tt>' (zero) in the text where there is the Greek letter &theta; (theta, transliterated '<tt>th</tt>') in the image."),
);
$messages["missingword"] = array(
    "message_title" => _("Missing word"),
    "message_body" => _("There is a word missing in your transliteration."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/#", "#/"), "case_sensitive" => TRUE, "error" => "P_blockmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\""), "case_sensitive" => TRUE, "error" => "P_addedquotes");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("&#964;", "&#959;", "&#957;", "&#945;"), "case_sensitive" => TRUE, "error" => "G_unicode");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Dios", "aneipon", "Lêdas", "oimai", "kai\n"), "case_sensitive" => TRUE, "error" => "G_nochange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("greek"), "case_sensitive" => TRUE, "error" => "G_lcgreek");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek:"), "case_sensitive" => TRUE, "error" => "G_nomarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[Greek:\n"), "case_sensitive" => TRUE, "error" => "G_markupline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek: "), "case_sensitive" => TRUE, "error" => "G_markupspace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("]"), "case_sensitive" => TRUE, "error" => "G_endbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n]"), "case_sensitive" => TRUE, "error" => "G_markupline2");
$tests[] = array("type" => "multioccurrence", "searchtext" => "Greek", "case_sensitive" => TRUE, "error" => "G_markuprepeat");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(": tou", "aueip", "eipou", "gouou", "Miuô", "mai tou", "uomo", "têu", "Kuo", "siou", "mau", "u to"), "case_sensitive" => TRUE, "error" => "G_n_u");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ton Dios", "niee", "thnn"), "case_sensitive" => TRUE, "error" => "G_u_n");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Knôoion"), "case_sensitive" => TRUE, "error" => "G_s_o");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Dêdas"), "case_sensitive" => TRUE, "error" => "L_D");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ello", "eglo", "elgo", "enlo"), "case_sensitive" => TRUE, "error" => "g_l");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("erak", "kai to", "tes L", "Ledas", "Mino", "theten", "Knos"), "case_sensitive" => TRUE, "error" => "G_missingcirc");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("v"), "case_sensitive" => TRUE, "error" => "G_n_v");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Aêdas"), "case_sensitive" => TRUE, "error" => "L_A");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("mo0et"), "case_sensitive" => TRUE, "error" => "mo0et");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Th"), "case_sensitive" => TRUE, "error" => "G_thetacase");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("mai nomo"), "case_sensitive" => TRUE, "error" => "missingword");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("e,"), "case_sensitive" => TRUE, "error" => "G_missingcomma");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ò", "é", "ì", "í", "á", "\\", "/", "^"), "case_sensitive" => TRUE, "error" => "G_extraaccent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" thon", "klhea", "tohu", "Dihos", "nehip", "kahi", "thô", "thês", "Lhêdas", "ihee", "Mhinô", "dhama", "Knhôs"), "case_sensitive" => TRUE, "error" => "G_extrah");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("hoimai", "ohimai", "haneipon", "hengonon"), "case_sensitive" => TRUE, "error" => "G_smooth_rough");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("'Ê", "'ê", "'R"), "case_sensitive" => FALSE, "error" => "G_rough_apos");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" Êrak", " êrak", "\nRada"), "case_sensitive" => FALSE, "error" => "G_capmissingh");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("hra"), "case_sensitive" => FALSE, "error" => "G_rbrho");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("hÊ", "HÊ", "RH"), "case_sensitive" => TRUE, "error" => "G_rbcap");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("uhiee", "yhiee", " uiee", " yiee"), "case_sensitive" => TRUE, "error" => "G_rbdiph");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("eggo"), "case_sensitive" => TRUE, "error" => "G_gg");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("toy", "huiee", "thun"), "case_sensitive" => TRUE, "error" => "G_upsilon");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("pon\n"), "case_sensitive" => TRUE, "error" => "G_missinglb");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("iee,\n"), "case_sensitive" => TRUE, "error" => "G_missinglb");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("kai\n"), "case_sensitive" => TRUE, "error" => "G_missinglb");

// vim: sw=4 ts=4 expandtab
