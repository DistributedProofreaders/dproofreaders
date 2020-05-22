<?php


$browser_title                = _("Basic Proofreading Quiz");
$intro_title                  = sprintf(_("Basic Proofreading Quiz, page %d"), 5);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "We ask ourselves how Byron's poem\nYou have the Pyrrhic dance as yet,\nWhere is the Pyrrhic phalanx\ngone?\nOf two such lessons, why forget\nThe nobler and the manlier one?\nis related to these well known words:\n\nWhen in the course of human events, it\nbecomes necessary for one people to\ndissolve . . . political hands.\n\nNot at all. we suspect.";
$solutions                    = array("We ask ourselves how Byron's poem\n\nYou have the Pyrrhic dance as yet,\nWhere is the Pyrrhic phalanx\ngone?\nOf two such lessons, why forget\nThe nobler and the manlier one?\n\nis related to these well known words:\n\nWhen in the Course of human events, it\nbecomes necessary for one people to\ndissolve ... political bands.\n\nNot at all, we suspect.");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . sprintf(_("DP Sans Mono is a custom font that helps proofreaders notice scannos. You can <a href='%1\$s' target='_blank'>compare it with other fonts</a> and select it for proofreading in your <a href='%2\$s' target='_blank'>user preferences</a>."), "../../faq/font_sample.php", "../../userprefs.php?tab=1") . "</p>";


// error messages

$messages["hands"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': an '<kbd>h</kbd>' in the text where there is a '<kbd>b</kbd>' in the image."),
);
$messages["capital"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a lower case '<kbd>c</kbd>' in the text where there is a capital '<kbd>C</kbd>' in the image."),
);
$messages["indent"] = array(
    "message_title" => _("Indentation"),
    "message_body" => _("Leave the indentation of text for the formatting rounds.  During proofreading, leave the text left aligned."),
    "guideline" => "formatting",
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("hands"), "case_sensitive" => TRUE, "error" => "hands");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("course"), "case_sensitive" => TRUE, "error" => "capital");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("all."), "case_sensitive" => TRUE, "error" => "P_commascanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(". . ."), "case_sensitive" => TRUE, "error" => "P_ellspaced");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("e ... p"), "case_sensitive" => TRUE, "error" => "P_midellips");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\""), "case_sensitive" => TRUE, "error" => "P_addedquotes");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("phalanx gone"), "case_sensitive" => TRUE, "error" => "P_joinline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("poem\nYou", "?\nis"), "case_sensitive" => TRUE, "error" => "P_poetryblanklines");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "P_indent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/*", "*/"), "case_sensitive" => TRUE, "error" => "P_poemmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/#", "#/"), "case_sensitive" => TRUE, "error" => "P_blockmarkup");

// vim: sw=4 ts=4 expandtab
