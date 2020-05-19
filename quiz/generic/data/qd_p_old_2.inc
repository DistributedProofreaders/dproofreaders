<?php


$browser_title                = _("Old Texts Proofreading Quiz");
$intro_title                  = sprintf(_("Old Texts Proofreading Quiz, page %d"), 2);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "150 \  Df Boioling.\n\nmost are fliort, ouer, or wide, and few iuftle\nin to Fortune. On one side you may *ee the\nMimick, twisting his Body into feueral\nPostures, to add to the Swiftness or Slowness\nof his Bowl; On the other Side the Orator,\nvfing his perfwafiue Intreaties to hasten or\nretard the Speed of his Bowl; if it be unre-\nfponfiue, then he cryes Short, O Short, &amp;c,\nwhen tis gone ten yards ouer the Iack. But\nnot to detain youany longer in characterizing\nthis excellent sport, I shall instruft you in some\n' , Rules,";
$solutions                    = array("most are short, ouer, or wide, and few iustle\nin to Fortune. On one side you may see the\nMimick twisting his Body into seueral\nPostures, to add to the Swiftness or Slowness\nof his Bowl; On the other side the Orator,\nvsing his perswasiue Intreaties to hasten or\nretard the Speed of his Bowl; if it be vnresponsiue,\nthen he cryes Short, O Short, &c,\nwhen tis gone ten yards ouer the Iack. But\nnot to detain you any longer in characterizing\nthis excellent sport, I shall instruct you in some");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Spelling and punctuation is often inconsistent in older texts, even from one sentence to the next.  Leave it as the author wrote it, but do leave a note or ask in the project discussion if you feel that there may be a printing error.") . "</p>";


// error messages

$messages["periodadded"] = array(
    "message_title" => _("Primary Rule"),
    "message_body" => _("You have added a period that does not appear in the image.  Please keep the punctuation as the author wrote it."),
);
$messages["ampchange"] = array(
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please keep punctuation and symbols such as the ampersand (&amp;) as they appear in the original book."),
);
$messages["aposadded"] = array(
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please do not add apostrophes that do not appear in the image; keep the punctuation as the author wrote it."),
);
$messages["sliort"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>li</kbd>' in the text where there is an '<kbd>h</kbd>' in the image."),
);
$messages["longsscanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an asterisk (<kbd>*</kbd>) in the text where there is a <kbd>long s</kbd> in the image."),
);
$messages["stray_comma"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a comma (<kbd>,</kbd>) in the text where there is no punctuation in the image."),
);
$messages["Side"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a capital letter in the text where there is a lower case letter in the image."),
);
$messages["youany"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: the space between two words is missing."),
);
$messages["instruft"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>ft</kbd>' in the text where there is '<kbd>ct</kbd>' in the image."),
);
$messages["flyspeck2"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a punctuation mark in the text where there are stray marks in the image."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("130", "150", "Boioling", "Bowling"), "case_sensitive" => TRUE, "error" => "P_header");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\nmost"), "case_sensitive" => TRUE, "error" => "P_headerline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("fhort", "uftle", "fide", "fee", "twifting", "feueral", "Poftures", "nefs", "fing", "perfw", "wafi", "haften", "refpo", "ponfi", "fport", "inftru", "fhall", "fome"), "case_sensitive" => TRUE, "error" => "P_longs");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[s]", "[f]"), "case_sensitive" => TRUE, "error" => "P_longsbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("-\n"), "case_sensitive" => TRUE, "error" => "P_eolhyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("it be\n"), "case_sensitive" => TRUE, "error" => "P_hyphenlower");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("-"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "longline", "lengthlimit" => 70, "error" => "P_longline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("over", "everal", "using", "ive"), "case_sensitive" => TRUE, "error" => "P_uvchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("unresponsiue"), "case_sensitive" => TRUE, "error" => "P_italu");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("j"), "case_sensitive" => FALSE, "error" => "P_ijchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("&c."), "case_sensitive" => TRUE, "error" => "periodadded");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("etc"), "case_sensitive" => TRUE, "error" => "ampchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("'tis"), "case_sensitive" => TRUE, "error" => "aposadded");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("liort"), "case_sensitive" => TRUE, "error" => "sliort");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("*ee"), "case_sensitive" => TRUE, "error" => "longsscanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ick,"), "case_sensitive" => TRUE, "error" => "stray_comma");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Side"), "case_sensitive" => TRUE, "error" => "Side");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("youany"), "case_sensitive" => TRUE, "error" => "youany");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("truft"), "case_sensitive" => TRUE, "error" => "instruft");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("acter"), "case_sensitive" => TRUE, "error" => "P_ctlig");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("truct"), "case_sensitive" => TRUE, "error" => "P_ctlig");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Rules"), "case_sensitive" => TRUE, "error" => "P_catchword");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n'", "\n,"), "case_sensitive" => TRUE, "error" => "flyspeck2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");

// vim: sw=4 ts=4 expandtab
