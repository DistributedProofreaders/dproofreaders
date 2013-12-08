<?php


$browser_title                = _("Fraktur Proofreading Quiz");
$intro_title                  = _("Fraktur Proofreading Quiz");
$initial_instructions         = _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. There is no need to proofread the English text, as it is already correct. When done click 'check'.");
$ocr_text                     = "self-condemnation, the Glass and Porcelain\ndupe, the Antiquity and Coin-hunting dupe,\nand especially the Book-collecting dupe.\nDupes of every kind, however, may find their\nreproof in the simple German lines,\n\nWer Rarren offt viel preoigen will,\nBei ihnen nicht wtrd schaffen viel;\nDaun all's rvas man am beften redt,\nTer Narr zum argsten falsch verstebt,";
$solutions                    = array("self-condemnation, the Glass and Porcelain\ndupe, the Antiquity and Coin-hunting dupe,\nand especially the Book-collecting dupe.\nDupes of every kind, however, may find their\nreproof in the simple German lines,\n\nWer Narren offt viel predigen will,\nBey ihnen nicht wird schaffen viel:\nDann all's was man am besten redt,\nDer Narr zum ärgsten falsch versteht,");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("The capital letters I and J are identical in fraktur.  Often (but not always) if the letter comes before a consonant it's an I, and before a vowel it's a J.  If you aren't sure, ask in the project discussion for confirmation.") . "</p>";
$links_out                    = "<a href='../start.php?show_only=proof' target='_top'>" . _("Back to proofreading quiz home") . "</a>";


// error messages

$messages["fraktext"] = array(
    "message_title" => _("Fraktur text"),
    "message_body" => sprintf(_("If you have trouble reading the fraktur font, try using <a target='_blank' href='%s'>this fraktur tool</a>."), "http://www.kurald-galain.com/fraktur2ascii.html"),
);
$messages["Rarren"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>R</tt>' in the text where there is an '<tt>N</tt>' in the image."),
);
$messages["preoigen"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>o</tt>' in the text where there is a '<tt>d</tt>' in the image."),
);
$messages["Bei"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>i</tt>' in the text where there is a '<tt>y</tt>' in the image."),
);
$messages["wtrd"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<tt>t</tt>' in the text where there is an '<tt>i</tt>' in the image."),
);
$messages["colonscanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a semicolon (<tt>;</tt>) in the text where there is a colon (<tt>:</tt>) in the image."),
);
$messages["Daun"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<tt>u</tt>' in the text where there is an '<tt>n</tt>' in the image."),
);
$messages["rv"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<tt>rv</tt>' in the text where there is a '<tt>w</tt>' in the image."),
);
$messages["Ter"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<tt>T</tt>' in the text where there is a '<tt>D</tt>' in the image."),
);
$messages["accenta"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: an accent over an '<tt>a</tt>' was omitted by the OCR software."),
);
$messages["verstebt"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<tt>b</tt>' in the text where there is an '<tt>h</tt>' in the image."),
);
$messages["longs2"] = array(
    "message_title" => _("Long s"),
    "message_body" => _("There is an 'f' in the text that is actually a 'long s' in the image.  Although the letters look similar, the long s is a different letter: it has a missing or incomplete crossbar, while the f has a full crossbar.  Proofread the long s just like a normal s."),
);
$messages["longs"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>f</tt>' in the text where there is a long s in the image."),
);
$messages["y_n"] = array(
    "message_title" => _("Fraktur characters"),
    "message_body" => _("There is a '<tt>y</tt>' in the image that is marked as an '<tt>n</tt>' in the text."),
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
