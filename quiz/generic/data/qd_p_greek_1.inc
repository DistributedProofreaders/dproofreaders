<?php


$browser_title = _("Greek Transliteration Quiz");
$intro_title = sprintf(_("Greek Transliteration Quiz, page %d"), 1);
$initial_instructions = $initial_instructions__greek;
$initial_text = "as panis siccus, dry bread; or, (if\nthe learned reader thinks it will\ntaste better in Greek,) aproc &sect;rjpoe";
$solutions = ["as panis siccus, dry bread; or, (if\nthe learned reader thinks it will\ntaste better in Greek,) [Greek: artos xêros]"];
$parting_message = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("If you come across some Greek while proofreading and you don't feel confident in your transliteration, feel free to post in the project discussion and mention the page number.  Other site volunteers can check your transliteration and let you know if you need to make any changes.") . "</p>";


// error messages

$messages["x_z"] = [
    "message_title" => _("Commonly confused letters"),
    "message_body" => _("There is a '<kbd>z</kbd>' in your transliteration that should be an '<kbd>x</kbd>'.  The Greek letter zeta (transliterated '<kbd>z</kbd>') looks like <kbd>ζ</kbd>, while the Greek letter xi (transliterated '<kbd>x</kbd>') looks like <kbd>ξ</kbd>."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["α", "ο", "ρ"], "case_sensitive" => true, "error" => "G_unicode"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["aproc", "§rjpoe"], "case_sensitive" => true, "error" => "G_nochange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["greek"], "case_sensitive" => true, "error" => "G_lcgreek"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek:"], "case_sensitive" => true, "error" => "G_nomarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[Greek:\n"], "case_sensitive" => true, "error" => "G_markupline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Greek: "], "case_sensitive" => true, "error" => "G_markupspace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["]"], "case_sensitive" => true, "error" => "G_endbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n]"], "case_sensitive" => true, "error" => "G_markupline2"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["zêr"], "case_sensitive" => true, "error" => "x_z"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ero"], "case_sensitive" => true, "error" => "G_missingcirc"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["apto", "pos"], "case_sensitive" => true, "error" => "G_r_p"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["toc"], "case_sensitive" => true, "error" => "G_s_c"];
