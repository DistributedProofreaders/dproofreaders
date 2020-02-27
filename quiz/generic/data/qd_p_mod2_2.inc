<?php
include_once('quiz_defaults.inc');


$browser_title                = _("Moderate Proofreading Quiz");
$intro_title                  = sprintf(_("Moderate Proofreading Quiz, page %d"), 7);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "its terrestrial excursions. Just above\nthe gills, lie climbing perch has\ninvented a wholly original water chamber\ncontaining within it a\nfrilled bony organ, which\nenables it to extract oxygen\n--or O2 to scientists--from\nthe stored-up water\nduring the course of its aerial peregrinations\n. While on shore it picks up\nsmall insects, worms, and grubs; but it\nalso has vegetarian tastes of us own,\nand does not despise fruits and berries";
$solutions                    = array("its terrestrial excursions. Just above\nthe gills, the climbing perch has\ninvented a wholly original water chamber,\ncontaining within it a\nfrilled bony organ, which\nenables it to extract oxygen--or\nO_{2} to scientists--from\nthe stored-up water\nduring the course of its aërial peregrinations.\nWhile on shore it picks up\nsmall insects, worms, and grubs; but it\nalso has vegetarian tastes of its own,\nand does not despise fruits and berries.");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("The texts that we proofread sometimes have different spellings than what we use today, including accents in words like <kbd>co&ouml;perate</kbd> and <kbd>pre&euml;minent</kbd>. OCR programs often miss the accents, so be sure to check the image carefully when proofreading.") . "</p>";


// error messages

$messages["lie"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>li</kbd>' in the text where there is '<kbd>th</kbd>' in the image."),
);
$messages["us"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>u</kbd>' in the text where there is '<kbd>it</kbd>' in the image."),
);
$messages["periodomit"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a period (<kbd>.</kbd>) was omitted by the OCR software."),
);
$messages["lbpunct"] = array(
    "message_title" => _("Punctuation placement"),
    "message_body" => _("There is a period (<kbd>.</kbd>) at the start of a line in the text.  Please move it so that it is at the end of the preceding word, without any spaces or line breaks between the word and the period."),
);
$messages["indent"] = array(
    "message_title" => _("Indentation"),
    "message_body" => _("There is a space at the start of a line.  During proofreading, please leave the text left aligned without any indentation."),
);
$messages["commaomit"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a comma (<kbd>,</kbd>) was omitted by the OCR software."),
);
$messages["accente"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: an accent over an '<kbd>e</kbd>' was omitted by the OCR software."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("lie"), "case_sensitive" => TRUE, "error" => "lie");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" us"), "case_sensitive" => TRUE, "error" => "us");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("ries."), "case_sensitive" => TRUE, "error" => "periodomit");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("--\n"), "case_sensitive" => TRUE, "error" => "P_eoldash");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n--"), "case_sensitive" => TRUE, "error" => "P_soldash");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\noxyg", "\nscient"), "case_sensitive" => TRUE, "error" => "P_lowerlinedash");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("-\n"), "case_sensitive" => TRUE, "error" => "P_eolhyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\nchamber", "\npereg"), "case_sensitive" => TRUE, "error" => "P_hyphenlower");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("cham-", "oxy-", "gri-"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "longline", "lengthlimit" => 53, "error" => "P_longline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("---"), "case_sensitive" => TRUE, "error" => "P_em_long");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" --", "-- "), "case_sensitive" => TRUE, "error" => "P_spacedemdash");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nfrill"), "case_sensitive" => TRUE, "error" => "P_lbchange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nenabl"), "case_sensitive" => TRUE, "error" => "P_lbchange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nduring"), "case_sensitive" => TRUE, "error" => "P_lbchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n."), "case_sensitive" => TRUE, "error" => "lbpunct");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" ."), "case_sensitive" => TRUE, "error" => "P_commaspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "indent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("chamber\n"), "case_sensitive" => TRUE, "error" => "commaomit");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("_"), "case_sensitive" => TRUE, "error" => "P_subnomark");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("_2"), "case_sensitive" => TRUE, "error" => "P_subnobraces");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("aer"), "case_sensitive" => TRUE, "error" => "accente");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("aër"), "case_sensitive" => TRUE, "error" => "P_accentbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<b>", "</b>"), "case_sensitive" => FALSE, "error" => "P_boldmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Illustration"), "case_sensitive" => FALSE, "error" => "P_illmarkup");

// vim: sw=4 ts=4 expandtab
