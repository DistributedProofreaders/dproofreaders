<?php


$browser_title = _("Moderate Proofreading Quiz");
$intro_title = sprintf(_("Moderate Proofreading Quiz, page %d"), 7);
$initial_instructions = $initial_instructions__P;
$initial_text = "its terrestrial excursions. Just above\nthe gills, lie climbing perch has\ninvented a wholly original water chamber\ncontaining within it a\nfrilled bony organ, which\nenables it to extract oxygen\n--or O2 to scientists--from\nthe stored-up water\nduring the course of its aerial peregrinations\n. While on shore it picks up\nsmall insects, worms, and grubs; but it\nalso has vegetarian tastes of us own,\nand does not despise fruits and berries";
$solutions = ["its terrestrial excursions. Just above\nthe gills, the climbing perch has\ninvented a wholly original water chamber,\ncontaining within it a\nfrilled bony organ, which\nenables it to extract oxygen--or\nO_{2} to scientists--from\nthe stored-up water\nduring the course of its aërial peregrinations.\nWhile on shore it picks up\nsmall insects, worms, and grubs; but it\nalso has vegetarian tastes of its own,\nand does not despise fruits and berries."];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("The texts that we proofread sometimes have different spellings than what we use today, including accents in words like <kbd>coöperate</kbd> and <kbd>preëminent</kbd>. OCR programs often miss the accents, so be sure to check the image carefully when proofreading.") . "</p>";


// error messages

$messages["lie"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<kbd>li</kbd>' in the text where there is '<kbd>th</kbd>' in the image."),
];
$messages["us"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<kbd>u</kbd>' in the text where there is '<kbd>it</kbd>' in the image."),
];
$messages["periodomit"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a period (<kbd>.</kbd>) was omitted by the OCR software."),
];
$messages["lbpunct"] = [
    "message_title" => _("Punctuation placement"),
    "message_body" => _("There is a period (<kbd>.</kbd>) at the start of a line in the text.  Please move it so that it is at the end of the preceding word, without any spaces or line breaks between the word and the period."),
];
$messages["indent"] = [
    "message_title" => _("Indentation"),
    "message_body" => _("There is a space at the start of a line.  During proofreading, please leave the text left aligned without any indentation."),
];
$messages["commaomit"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a comma (<kbd>,</kbd>) was omitted by the OCR software."),
];
$messages["accente"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: an accent over an '<kbd>e</kbd>' was omitted by the OCR software."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["lie"], "case_sensitive" => true, "error" => "lie"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" us"], "case_sensitive" => true, "error" => "us"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["ries."], "case_sensitive" => true, "error" => "periodomit"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["--\n"], "case_sensitive" => true, "error" => "P_eoldash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n--"], "case_sensitive" => true, "error" => "P_soldash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\noxyg", "\nscient"], "case_sensitive" => true, "error" => "P_lowerlinedash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-\n"], "case_sensitive" => true, "error" => "P_eolhyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\nchamber", "\npereg"], "case_sensitive" => true, "error" => "P_hyphenlower"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["cham-", "oxy-", "gri-"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "longline", "lengthlimit" => 53, "error" => "P_longline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["---"], "case_sensitive" => true, "error" => "P_em_long"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" --", "-- "], "case_sensitive" => true, "error" => "P_spacedemdash"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nfrill"], "case_sensitive" => true, "error" => "P_lbchange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nenabl"], "case_sensitive" => true, "error" => "P_lbchange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nduring"], "case_sensitive" => true, "error" => "P_lbchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n."], "case_sensitive" => true, "error" => "lbpunct"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" ."], "case_sensitive" => true, "error" => "P_commaspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n "], "case_sensitive" => true, "error" => "indent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["chamber\n"], "case_sensitive" => true, "error" => "commaomit"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["_"], "case_sensitive" => true, "error" => "P_subnomark"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["_2"], "case_sensitive" => true, "error" => "P_subnobraces"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["aer"], "case_sensitive" => true, "error" => "accente"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["aër"], "case_sensitive" => true, "error" => "P_accentbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<b>", "</b>"], "case_sensitive" => false, "error" => "P_boldmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Illustration"], "case_sensitive" => false, "error" => "P_illmarkup"];
