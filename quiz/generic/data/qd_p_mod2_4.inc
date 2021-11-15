<?php

$browser_title = sprintf(_("Moderate Proofreading Quiz: Part %d"), 2);
$intro_title = sprintf(_("Moderate Proofreading Quiz: Part %1\$d, Page %2\$d"), 2, 4);
$initial_instructions = $initial_instructions__P;
$initial_text = "\"It has been said,\" he began, withdrawing\nhis eyes reluctantly from an unusually large\ninsect upon the ceiling, \"that there are few\n\"situations in life that cannot be honourably\n\"settled--and without loss of time\n\"--either by suicide, a bag of gold,\n\"or by thrusting a despised anta-\n\"gonist over the edge of a precipice upon a\n\"dark night. This inoffensive person, however,\n\nA suitor\nis chosen";
$solutions = ["\n\"It has been said,\" he began, withdrawing\nhis eyes reluctantly from an unusually large\ninsect upon the ceiling, \"that there are few\nsituations in life that cannot be honourably\nsettled--and without loss of time--either\nby suicide, a bag of gold,\nor by thrusting a despised antagonist\nover the edge of a precipice upon a\ndark night. This inoffensive person, however,\n\nA suitor\nis chosen."];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("We remove extra quote marks when they appear on every line because the text will be rewrapped in post-processing, changing the line breaks.  If we left the extra quote marks in the text they would end up in the middle of the paragraph.") . "</p>";

// error messages

$messages["periodomit"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a period (<kbd>.</kbd>) was omitted by the OCR software."),
];
$messages["extraquote"] = [
    "message_title" => _("Quote marks on each line"),
    "message_body" => _("Proofread these by removing all of the quote marks except for the one at the start of the quotation."),
    "guideline" => "quote_ea",
];

// error checks

$tests[] = ["type" => "expectedtext", "searchtext" => ["chosen."], "case_sensitive" => true, "error" => "periodomit"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["however,\""], "case_sensitive" => true, "error" => "P_addedclosequote"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\"It"], "case_sensitive" => true, "error" => "P_paratop"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\"situations", "\"settled", "\"--", "\"by", "\"or", "\"dark", "\"over"], "case_sensitive" => true, "error" => "extraquote"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" --", "-- "], "case_sensitive" => true, "error" => "P_spacedemdash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["---"], "case_sensitive" => true, "error" => "P_em_long"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["anta-"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["--\n"], "case_sensitive" => true, "error" => "P_eoldash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n--"], "case_sensitive" => true, "error" => "P_soldash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\ntime"], "case_sensitive" => true, "error" => "P_lowerlinedash"];
$tests[] = ["type" => "longline", "lengthlimit" => 60, "error" => "P_longline"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "person", "secondtext" => "chosen", "case_sensitive" => true, "error" => "P_snmove"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Sidenote"], "case_sensitive" => false, "error" => "P_snmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["suitor is"], "case_sensitive" => true, "error" => "P_snlinebreak"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["gold,\n"], "case_sensitive" => true, "error" => "P_lbchange"];
