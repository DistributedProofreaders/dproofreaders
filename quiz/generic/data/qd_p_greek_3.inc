<?php


$browser_title = _("Greek Transliteration Quiz");
$intro_title = sprintf(_("Greek Transliteration Quiz, page %d"), 3);
$initial_instructions = $initial_instructions__greek;
$initial_text = "the famous knight Sir Guy, the slayer of the\ngreat Dun Cow which had laid waste the\nwhole county of Warwick. But besides slaying\nthe cow, he did many other noble deeds, like\nthe heroes of old:\n\n[Greek: ton 'Êrhaklea ton Diòs aneipov\nellonon kai tô tês Aêdas niee,\nMinô te ohimai nomo0etên kaì\n'Radamanthnn ton Knôoion";
$solutions = ["the famous knight Sir Guy, the slayer of the\ngreat Dun Cow which had laid waste the\nwhole county of Warwick. But besides slaying\nthe cow, he did many other noble deeds, like\nthe heroes of old:\n\n[Greek: ton Hêraklea tou Dios aneipon\nengonon kai tô tês Lêdas hyiee,\nMinô te oimai ton nomothetên kai\nRhadamanthyn ton Knôsion]"];
$parting_message = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("The spelling of the letter 'rho' can help you to remember that the '<kbd>h</kbd>' goes after the '<kbd>r</kbd>' when marking rough breathing on rho.") . "</p>";


// error messages

$messages["L_D"] = [
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is a '<kbd>D</kbd>' in your transliteration that should be an '<kbd>L</kbd>'.  The Greek letter <kbd>Δ</kbd> (capital delta, transliterated '<kbd>D</kbd>') is a triangle, while <kbd>Λ</kbd> (capital lambda, transliterated '<kbd>L</kbd>') looks like an upside-down V."),
];
$messages["g_l"] = [
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is an '<kbd>l</kbd>' in your transliteration that should be a '<kbd>g</kbd>' or '<kbd>n</kbd>'.  The Greek letter lambda (transliterated '<kbd>l</kbd>') looks like <kbd>λ</kbd>, while the Greek letter gamma (transliterated '<kbd>g</kbd>' or '<kbd>n</kbd>') looks like <kbd>γ</kbd>."),
];
$messages["L_A"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>A</kbd>' in the text where there is the Greek letter <kbd>Λ</kbd> (capital lambda, transliterated '<kbd>L</kbd>') in the image."),
];
$messages["mo0et"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>0</kbd>' (zero) in the text where there is the Greek letter <kbd>θ</kbd> (theta, transliterated '<kbd>th</kbd>') in the image."),
];
$messages["missingword"] = [
    "message_title" => _("Missing word"),
    "message_body" => _("There is a word missing in your transliteration."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/#", "#/"], "case_sensitive" => true, "error" => "P_blockmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\""], "case_sensitive" => true, "error" => "P_addedquotes"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["τ", "ο", "ν", "α"], "case_sensitive" => true, "error" => "G_unicode"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["Dios", "aneipon", "Lêdas", "oimai", "kai\n"], "case_sensitive" => true, "error" => "G_nochange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["greek"], "case_sensitive" => true, "error" => "G_lcgreek"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek:"], "case_sensitive" => true, "error" => "G_nomarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[Greek:\n"], "case_sensitive" => true, "error" => "G_markupline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek: "], "case_sensitive" => true, "error" => "G_markupspace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["]"], "case_sensitive" => true, "error" => "G_endbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n]"], "case_sensitive" => true, "error" => "G_markupline2"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "Greek", "case_sensitive" => true, "error" => "G_markuprepeat"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [": tou", "aueip", "eipou", "gouou", "Miuô", "mai tou", "uomo", "têu", "Kuo", "siou", "mau", "u to"], "case_sensitive" => true, "error" => "G_n_u"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ton Dios", "niee", "thnn"], "case_sensitive" => true, "error" => "G_u_n"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Knôoion"], "case_sensitive" => true, "error" => "G_s_o"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Dêdas"], "case_sensitive" => true, "error" => "L_D"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ello", "eglo", "elgo", "enlo"], "case_sensitive" => true, "error" => "g_l"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["erak", "kai to", "tes L", "Ledas", "Mino", "theten", "Knos"], "case_sensitive" => true, "error" => "G_missingcirc"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["v"], "case_sensitive" => true, "error" => "G_n_v"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Aêdas"], "case_sensitive" => true, "error" => "L_A"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["mo0et"], "case_sensitive" => true, "error" => "mo0et"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Th"], "case_sensitive" => true, "error" => "G_thetacase"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["mai nomo"], "case_sensitive" => true, "error" => "missingword"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["e,"], "case_sensitive" => true, "error" => "G_missingcomma"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ò", "é", "ì", "í", "á", "\\", "/", "^"], "case_sensitive" => true, "error" => "G_extraaccent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" thon", "klhea", "tohu", "Dihos", "nehip", "kahi", "thô", "thês", "Lhêdas", "ihee", "Mhinô", "dhama", "Knhôs"], "case_sensitive" => true, "error" => "G_extrah"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["hoimai", "ohimai", "haneipon", "hengonon"], "case_sensitive" => true, "error" => "G_smooth_rough"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["'Ê", "'ê", "'R"], "case_sensitive" => false, "error" => "G_rough_apos"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" Êrak", " êrak", "\nRada"], "case_sensitive" => false, "error" => "G_capmissingh"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["hra"], "case_sensitive" => false, "error" => "G_rbrho"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["hÊ", "HÊ", "RH"], "case_sensitive" => true, "error" => "G_rbcap"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["uhiee", "yhiee", " uiee", " yiee"], "case_sensitive" => true, "error" => "G_rbdiph"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["eggo"], "case_sensitive" => true, "error" => "G_gg"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["toy", "huiee", "thun"], "case_sensitive" => true, "error" => "G_upsilon"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["pon\n"], "case_sensitive" => true, "error" => "G_missinglb"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["iee,\n"], "case_sensitive" => true, "error" => "G_missinglb"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["kai\n"], "case_sensitive" => true, "error" => "G_missinglb"];
