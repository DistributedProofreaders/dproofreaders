<?php


$browser_title = _("Fraktur Proofreading Quiz");
$intro_title = _("Fraktur Proofreading Quiz");
$initial_instructions = _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. There is no need to proofread the English text, as it is already correct. When done click 'check'.");
$initial_text = "self-condemnation, the Glass and Porcelain\ndupe, the Antiquity and Coin-hunting dupe,\nand especially the Book-collecting dupe.\nDupes of every kind, however, may find their\nreproof in the simple German lines,\n\nWer Rarren offt viel preoigen will,\nBei ihnen nicht wtrd schaffen viel;\nDaun all's rvas man am beften redt,\nTer Narr zum argsten falsch verstebt,";
$solutions = ["self-condemnation, the Glass and Porcelain\ndupe, the Antiquity and Coin-hunting dupe,\nand especially the Book-collecting dupe.\nDupes of every kind, however, may find their\nreproof in the simple German lines,\n\nWer Narren offt viel predigen will,\nBey ihnen nicht wird schaffen viel:\nDann all's was man am besten redt,\nDer Narr zum ärgsten falsch versteht,"];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("The capital letters I and J are identical in fraktur.  Often (but not always) if the letter comes before a consonant it's an I, and before a vowel it's a J.  If you aren't sure, ask in the project discussion for confirmation.") . "</p>";


// error messages

$messages["fraktext"] = [
    "message_title" => _("Fraktur text"),
    "message_body" => sprintf(_("If you have trouble reading the fraktur font, try using <a target='_blank' href='%s'>this fraktur tool</a>."), "http://www.kurald-galain.com/fraktur2ascii.html"),
];
$messages["Rarren"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>R</kbd>' in the text where there is an '<kbd>N</kbd>' in the image."),
];
$messages["preoigen"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>o</kbd>' in the text where there is a '<kbd>d</kbd>' in the image."),
];
$messages["Bei"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>i</kbd>' in the text where there is a '<kbd>y</kbd>' in the image."),
];
$messages["wtrd"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>t</kbd>' in the text where there is an '<kbd>i</kbd>' in the image."),
];
$messages["colonscanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a semicolon (<kbd>;</kbd>) in the text where there is a colon (<kbd>:</kbd>) in the image."),
];
$messages["Daun"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>u</kbd>' in the text where there is an '<kbd>n</kbd>' in the image."),
];
$messages["rv"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>rv</kbd>' in the text where there is a '<kbd>w</kbd>' in the image."),
];
$messages["Ter"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>T</kbd>' in the text where there is a '<kbd>D</kbd>' in the image."),
];
$messages["accenta"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: an accent over an '<kbd>a</kbd>' was omitted by the OCR software."),
];
$messages["verstebt"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>b</kbd>' in the text where there is an '<kbd>h</kbd>' in the image."),
];
$messages["longs2"] = [
    "message_title" => _("Long s"),
    "message_body" => _("There is an '<kbd>f</kbd>' in the text that is actually a '<kbd>long s</kbd>' in the image.  Although the letters look similar, the <kbd>long s</kbd> is a different letter: it has a missing or incomplete crossbar (<kbd>ſ</kbd>), while the <kbd>f</kbd> has a full crossbar.  Proofread the <kbd>long s</kbd> just like a normal <kbd>s</kbd>."),
];
$messages["longs"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>f</kbd>' in the text where there is a <kbd>long s</kbd> in the image."),
];
$messages["y_n"] = [
    "message_title" => _("Fraktur characters"),
    "message_body" => _("There is a '<kbd>y</kbd>' in the image that is marked as an '<kbd>n</kbd>' in the text."),
];


// error checks

$tests[] = ["type" => "expectedtext", "searchtext" => ["predigen", "was", "Bey", "besten", "versteht"], "case_sensitive" => true, "error" => "fraktext"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Rarren"], "case_sensitive" => true, "error" => "Rarren"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["preoigen"], "case_sensitive" => true, "error" => "preoigen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Bei"], "case_sensitive" => true, "error" => "Bei"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["wtrd"], "case_sensitive" => true, "error" => "wtrd"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["viel;"], "case_sensitive" => true, "error" => "colonscanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Daun"], "case_sensitive" => true, "error" => "Daun"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["rv"], "case_sensitive" => true, "error" => "rv"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Ter"], "case_sensitive" => true, "error" => "Ter"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["argsten"], "case_sensitive" => true, "error" => "accenta"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["verstebt"], "case_sensitive" => true, "error" => "verstebt"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["fch", "gften", "rft"], "case_sensitive" => true, "error" => "longs2"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["beften"], "case_sensitive" => true, "error" => "longs"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Ben"], "case_sensitive" => true, "error" => "y_n"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[:a]"], "case_sensitive" => true, "error" => "P_accentbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["oft"], "case_sensitive" => true, "error" => "P_spellingchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\""], "case_sensitive" => true, "error" => "P_addedquotes"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["lines,\nW"], "case_sensitive" => true, "error" => "P_poetryblanklines"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n "], "case_sensitive" => true, "error" => "P_indent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/*", "*/"], "case_sensitive" => true, "error" => "P_poemmarkup"];
