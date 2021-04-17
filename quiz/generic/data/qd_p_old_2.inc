<?php


$browser_title = _("Old Texts Proofreading Quiz");
$intro_title = sprintf(_("Old Texts Proofreading Quiz, page %d"), 2);
$initial_instructions = $initial_instructions__P;
$initial_text = "150 \  Df Boioling.\n\nmost are fliort, ouer, or wide, and few iuftle\nin to Fortune. On one side you may *ee the\nMimick, twisting his Body into feueral\nPostures, to add to the Swiftness or Slowness\nof his Bowl; On the other Side the Orator,\nvfing his perfwafiue Intreaties to hasten or\nretard the Speed of his Bowl; if it be unre-\nfponfiue, then he cryes Short, O Short, &amp;c,\nwhen tis gone ten yards ouer the Iack. But\nnot to detain youany longer in characterizing\nthis excellent sport, I shall instruft you in some\n' , Rules,";
$solutions = ["most are short, ouer, or wide, and few iustle\nin to Fortune. On one side you may see the\nMimick twisting his Body into seueral\nPostures, to add to the Swiftness or Slowness\nof his Bowl; On the other side the Orator,\nvsing his perswasiue Intreaties to hasten or\nretard the Speed of his Bowl; if it be vnresponsiue,\nthen he cryes Short, O Short, &c,\nwhen tis gone ten yards ouer the Iack. But\nnot to detain you any longer in characterizing\nthis excellent sport, I shall instruct you in some"];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Spelling and punctuation is often inconsistent in older texts, even from one sentence to the next.  Leave it as the author wrote it, but do leave a note or ask in the project discussion if you feel that there may be a printing error.") . "</p>";


// error messages

$messages["periodadded"] = [
    "message_title" => _("Primary Rule"),
    "message_body" => _("You have added a period that does not appear in the image.  Please keep the punctuation as the author wrote it."),
];
$messages["ampchange"] = [
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please keep punctuation and symbols such as the ampersand (&amp;) as they appear in the original book."),
];
$messages["aposadded"] = [
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please do not add apostrophes that do not appear in the image; keep the punctuation as the author wrote it."),
];
$messages["sliort"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>li</kbd>' in the text where there is an '<kbd>h</kbd>' in the image."),
];
$messages["longsscanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an asterisk (<kbd>*</kbd>) in the text where there is a <kbd>long s</kbd> in the image."),
];
$messages["stray_comma"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a comma (<kbd>,</kbd>) in the text where there is no punctuation in the image."),
];
$messages["Side"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a capital letter in the text where there is a lower case letter in the image."),
];
$messages["youany"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: the space between two words is missing."),
];
$messages["instruft"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ft</kbd>' in the text where there is '<kbd>ct</kbd>' in the image."),
];
$messages["flyspeck2"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a punctuation mark in the text where there are stray marks in the image."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["130", "150", "Boioling", "Bowling"], "case_sensitive" => true, "error" => "P_header"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\nmost"], "case_sensitive" => true, "error" => "P_headerline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["fhort", "uftle", "fide", "fee", "twifting", "feueral", "Poftures", "nefs", "fing", "perfw", "wafi", "haften", "refpo", "ponfi", "fport", "inftru", "fhall", "fome"], "case_sensitive" => true, "error" => "P_longs"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[s]", "[f]"], "case_sensitive" => true, "error" => "P_longsbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-\n"], "case_sensitive" => true, "error" => "P_eolhyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["it be\n"], "case_sensitive" => true, "error" => "P_hyphenlower"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "longline", "lengthlimit" => 70, "error" => "P_longline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["over", "everal", "using", "ive"], "case_sensitive" => true, "error" => "P_uvchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["unresponsiue"], "case_sensitive" => true, "error" => "P_italu"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["j"], "case_sensitive" => false, "error" => "P_ijchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["&c."], "case_sensitive" => true, "error" => "periodadded"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["etc"], "case_sensitive" => true, "error" => "ampchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["'tis"], "case_sensitive" => true, "error" => "aposadded"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["liort"], "case_sensitive" => true, "error" => "sliort"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["*ee"], "case_sensitive" => true, "error" => "longsscanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ick,"], "case_sensitive" => true, "error" => "stray_comma"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Side"], "case_sensitive" => true, "error" => "Side"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["youany"], "case_sensitive" => true, "error" => "youany"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["truft"], "case_sensitive" => true, "error" => "instruft"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["acter"], "case_sensitive" => true, "error" => "P_ctlig"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["truct"], "case_sensitive" => true, "error" => "P_ctlig"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Rules"], "case_sensitive" => true, "error" => "P_catchword"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n'", "\n,"], "case_sensitive" => true, "error" => "flyspeck2"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
