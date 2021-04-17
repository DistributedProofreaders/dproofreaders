<?php


$browser_title = _("Old Texts Proofreading Quiz");
$intro_title = sprintf(_("Old Texts Proofreading Quiz, page %d"), 3);
$initial_instructions = _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines and the \"Project Comments\" for this quiz. When done click 'check'.");
$constant_message = "\n" .
                                    "<h2>" . _("Project Comments") . "</h2>\n" .
                                    "<p>" . sprintf(_("Proofread the nasal abbreviations, which can look like tildes or macrons, as macrons.  If you have trouble with the blackletter font, see <a href='%s' target='_blank'>Proofing Blackletter</a>."), $blackletter_url) . "</p>";
$initial_text = "for coolcnes in Summer. )\n\nCucnye. An admitable byrde in ljifpa=\nniola (no bigger then a thobe) hauing in\nher forehead two burnynge Lampes, and\ntwo vnderneath her itinges (which are\ndouble, a greater and a smaller payre} so\nshining in the njght (wherin onely she\nflyeth) that fiue or fixe of the tyed toge-\nther_{j} gyue as moch light as a torch. Further";
$solutions = ["for coolenes in Summer.\n\nCucuye. An admirable byrde in Hispaniola\n(no bigger then a thōbe) hauing in\nher forehead two burnynge Lampes, and\ntwo vnderneath her winges (which are\ndouble, a greater and a smaller payre) so\nshining in the nyght (wherin onely she\nflyeth) that fiue or sixe of thē tyed together,\ngyue as moch light as a torch. Fur-*"];
$parting_message = "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("If you come across any unusual symbols or characters when proofreading, please bring it up in the project discussion.  This will alert the project manager and post-processor to the situation, and help to improve consistency among the various proofreaders during the rounds.") . "</p>";


// error messages

$messages["blhyphen"] = [
    "message_title" => _("End-of-line Hyphenation"),
    "message_body" => _("In blackletter fonts the hyphen often looks like an equals sign (=).  Treat this like a normal hyphen, rejoining the word as necessary."),
];
$messages["projcomm"] = [
    "message_title" => _("Macron"),
    "message_body" => _("According to the \"project comments\" for this text, you should proofread the nasal abbreviations as macrons."),
    "guideline" => "d_chars",
];
$messages["flyspeck"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>)</kbd>' in the text where there is a stray mark in the image."),
];
$messages["coolcnes"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>c</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
];
$messages["Cucnye"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<kbd>n</kbd>' in the text where there is a '<kbd>u</kbd>' in the image."),
];
$messages["admitable"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>t</kbd>' in the text where there is an '<kbd>r</kbd>' in the image."),
];
$messages["Hscanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>lj</kbd>' in the text where there is a capital '<kbd>H</kbd>' in the image."),
];
$messages["itinges"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>it</kbd>' in the text where there is a '<kbd>w</kbd>' in the image."),
];
$messages["parenscanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a curly bracket '<kbd>}</kbd>' in the text where there is a closing parenthesis '<kbd>)</kbd>' in the image."),
];
$messages["njght"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>j</kbd>' in the text where there is a '<kbd>y</kbd>' in the image."),
];
$messages["commascanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a subscripted letter in the text where there is a comma (<kbd>,</kbd>) in the image."),
];
$messages["bltext"] = [
    "message_title" => _("Blackletter font"),
    "message_body" => sprintf(_("There seems to be a problem in the blackletter text. If you are having trouble reading it, see <a href='%s' target='_blank'>this page</a> for help."), $blackletter_url),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["ifpa", "fmaller", "fhining", "fhe", "fixe"], "case_sensitive" => true, "error" => "P_longs"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[f]", "[s]"], "case_sensitive" => true, "error" => "P_longsbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-\n"], "case_sensitive" => true, "error" => "P_eolhyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["tyed\n"], "case_sensitive" => true, "error" => "P_hyphenlower"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["toge-", "ispa-"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["a="], "case_sensitive" => true, "error" => "blhyphen"];
$tests[] = ["type" => "longline", "lengthlimit" => 60, "error" => "P_longline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Further"], "case_sensitive" => true, "error" => "P_catchword"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["Fur-*"], "case_sensitive" => true, "error" => "P_eophyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["having", "five", "gyve"], "case_sensitive" => true, "error" => "P_uvchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["underneath"], "case_sensitive" => true, "error" => "P_italu"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["thobe", "thombe", "~", "õ", "ẽ", "the tyed", "them"], "case_sensitive" => true, "error" => "projcomm"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[=o]", "[=e]"], "case_sensitive" => true, "error" => "P_macron"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["coolcnes"], "case_sensitive" => true, "error" => "coolcnes"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["er. )", "er.)"], "case_sensitive" => true, "error" => "flyspeck"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["cny"], "case_sensitive" => true, "error" => "Cucnye"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["admitable"], "case_sensitive" => true, "error" => "admitable"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["lj"], "case_sensitive" => true, "error" => "Hscanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["itinges"], "case_sensitive" => true, "error" => "itinges"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["re}"], "case_sensitive" => true, "error" => "parenscanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["njght"], "case_sensitive" => true, "error" => "njght"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["_{j}"], "case_sensitive" => true, "error" => "commascanno"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["Cucuye"], "case_sensitive" => true, "error" => "bltext"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["Hispaniola"], "case_sensitive" => true, "error" => "bltext"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
