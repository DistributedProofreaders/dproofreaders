<?php


$browser_title                = _("Fraktur Proofreading Quiz");
$intro_title                  = _("Fraktur Proofreading Quiz");
$initial_instructions         = _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. There is no need to proofread the English text, as it is already correct. When done click 'check'.");
$initial_text                 = "self-condemnation, the Glass and Porcelain\ndupe, the Antiquity and Coin-hunting dupe,\nand especially the Book-collecting dupe.\nDupes of every kind, however, may find their\nreproof in the simple German lines,\n\nWer Rarren offt viel preoigen will,\nBei ihnen nicht wtrd schaffen viel;\nDaun all's rvas man am beften redt,\nTer Narr zum argsten falsch verstebt,";
$solutions                    = array("self-condemnation, the Glass and Porcelain\ndupe, the Antiquity and Coin-hunting dupe,\nand especially the Book-collecting dupe.\nDupes of every kind, however, may find their\nreproof in the simple German lines,\n\nWer Narren offt viel predigen will,\nBey ihnen nicht wird schaffen viel:\nDann all's was man am besten redt,\nDer Narr zum ärgsten falsch versteht,");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("The capital letters I and J are identical in fraktur.  Often (but not always) if the letter comes before a consonant it's an I, and before a vowel it's a J.  If you aren't sure, ask in the project discussion for confirmation.") . "</p>";


// error messages

$messages["fraktext"] = array(
    "message_title" => _("Fraktur text"),
    "message_body" => sprintf(_("If you have trouble reading the fraktur font, try using <a target='_blank' href='%s'>this fraktur tool</a>."), "http://www.kurald-galain.com/fraktur2ascii.html"),
);
$messages["Rarren"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>R</kbd>' in the text where there is an '<kbd>N</kbd>' in the image."),
);
$messages["preoigen"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>o</kbd>' in the text where there is a '<kbd>d</kbd>' in the image."),
);
$messages["Bei"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>i</kbd>' in the text where there is a '<kbd>y</kbd>' in the image."),
);
$messages["wtrd"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>t</kbd>' in the text where there is an '<kbd>i</kbd>' in the image."),
);
$messages["colonscanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a semicolon (<kbd>;</kbd>) in the text where there is a colon (<kbd>:</kbd>) in the image."),
);
$messages["Daun"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>u</kbd>' in the text where there is an '<kbd>n</kbd>' in the image."),
);
$messages["rv"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>rv</kbd>' in the text where there is a '<kbd>w</kbd>' in the image."),
);
$messages["Ter"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>T</kbd>' in the text where there is a '<kbd>D</kbd>' in the image."),
);
$messages["accenta"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: an accent over an '<kbd>a</kbd>' was omitted by the OCR software."),
);
$messages["verstebt"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>b</kbd>' in the text where there is an '<kbd>h</kbd>' in the image."),
);
$messages["longs2"] = array(
    "message_title" => _("Long s"),
    "message_body" => _("There is an '<kbd>f</kbd>' in the text that is actually a '<kbd>long s</kbd>' in the image.  Although the letters look similar, the <kbd>long s</kbd> is a different letter: it has a missing or incomplete crossbar (<kbd>ſ</kbd>), while the <kbd>f</kbd> has a full crossbar.  Proofread the <kbd>long s</kbd> just like a normal <kbd>s</kbd>."),
);
$messages["longs"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>f</kbd>' in the text where there is a <kbd>long s</kbd> in the image."),
);
$messages["y_n"] = array(
    "message_title" => _("Fraktur characters"),
    "message_body" => _("There is a '<kbd>y</kbd>' in the image that is marked as an '<kbd>n</kbd>' in the text."),
);


// error checks

$tests[] = array("type" => "expectedtext", "searchtext" =>  array("predigen", "was", "Bey", "besten", "versteht"), "case_sensitive" => TRUE, "error" => "fraktext");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Rarren"), "case_sensitive" => TRUE, "error" => "Rarren");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("preoigen"), "case_sensitive" => TRUE, "error" => "preoigen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Bei"), "case_sensitive" => TRUE, "error" => "Bei");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("wtrd"), "case_sensitive" => TRUE, "error" => "wtrd");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("viel;"), "case_sensitive" => TRUE, "error" => "colonscanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Daun"), "case_sensitive" => TRUE, "error" => "Daun");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("rv"), "case_sensitive" => TRUE, "error" => "rv");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Ter"), "case_sensitive" => TRUE, "error" => "Ter");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("argsten"), "case_sensitive" => TRUE, "error" => "accenta");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("verstebt"), "case_sensitive" => TRUE, "error" => "verstebt");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("fch", "gften", "rft"), "case_sensitive" => TRUE, "error" => "longs2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("beften"), "case_sensitive" => TRUE, "error" => "longs");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Ben"), "case_sensitive" => TRUE, "error" => "y_n");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[:a]"), "case_sensitive" => TRUE, "error" => "P_accentbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("oft"), "case_sensitive" => TRUE, "error" => "P_spellingchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\""), "case_sensitive" => TRUE, "error" => "P_addedquotes");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("lines,\nW"), "case_sensitive" => TRUE, "error" => "P_poetryblanklines");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "P_indent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/*", "*/"), "case_sensitive" => TRUE, "error" => "P_poemmarkup");

// vim: sw=4 ts=4 expandtab
