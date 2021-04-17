<?php


$browser_title = _("Basic Proofreading Quiz");
$intro_title = sprintf(_("Basic Proofreading Quiz, page %d"), 3);
$initial_instructions = $initial_instructions__P;
$initial_text = "bundles into the house, while Mirabell's mother\nwent on home in her automobile.\n\nCHAPTER II\n\nThe Rabbit's New Home\n\nOh, Mother! What have you?\" cried the\nvoice of a little girl, as the lady entered\nthe house with the bundle.\n\n\"Is it...something good to cat..?\" asked a\nboy's voice.\n\n\"Now, children, you must not ask too many\nquestions.\" said their mother. \"This isn't exactly\nCHRISTMAS, but it will soon be EASTER, and we";
$solutions = ["bundles into the house, while Mirabell's mother\nwent on home in her automobile.\n\nCHAPTER II\n\nThe Rabbit's New Home\n\n\"Oh, Mother! What have you?\" cried the\nvoice of a little girl, as the lady entered\nthe house with the bundle.\n\n\"Is it ... something good to eat...?\" asked a\nboy's voice.\n\n\"Now, children, you must not ask too many\nquestions,\" said their mother. \"This isn't exactly\nCHRISTMAS, but it will soon be EASTER, and we"];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("While proofreading, if you encounter something that isn't covered in the guidelines or that you are not sure how to handle, post your question in the Project Discussion thread (a link to the project-specific thread is in the Project Comments).  The Project Manager or other site volunteers will let you know how to handle it.") . "</p>";


// error messages
$messages["cat"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a '<kbd>c</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
];
$messages["chapchange"] = [
    "message_title" => _("Chapter heading changed"),
    "message_body" => _("Leave the text of the chapter heading as it appears in the image. Do not change ALL CAPS to Title Case or otherwise alter the text."),
    "guideline" => "chap_head",
];
$messages["ellspaced"] = [
    "message_title" => _("Ellipsis Spacing"),
    "message_body" => _("Please do not leave spaces between the dots of an ellipsis.  The dots should be right next to each other, like ... or...."),
    "guideline" => "period_p",
];


// error checks
$tests[] = ["type" => "forbiddentext", "searchtext" => ["cat"], "case_sensitive" => true, "error" => "cat"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["questions."], "case_sensitive" => true, "error" => "P_commascanno"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["The Rabbit's New Home"], "case_sensitive" => true, "error" => "chapchange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["CHAPTER II"], "case_sensitive" => true, "error" => "chapchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n\n\n"], "case_sensitive" => true, "error" => "P_spacedchap"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["II\nThe"], "case_sensitive" => true, "error" => "P_missinglb"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\nOh"], "case_sensitive" => true, "error" => "P_missingquote"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [". . ."], "case_sensitive" => true, "error" => "ellspaced"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["it ... something"], "case_sensitive" => true, "error" => "P_midellips"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["eat...?"], "case_sensitive" => true, "error" => "P_endellips"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<sc>", "</sc>"], "case_sensitive" => false, "error" => "P_scmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["EASTER"], "case_sensitive" => true, "error" => "P_casechange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["CHRISTMAS"], "case_sensitive" => true, "error" => "P_casechange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<b>", "</b>"], "case_sensitive" => false, "error" => "P_boldmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["we\""], "case_sensitive" => true, "error" => "P_addedclosequote"];
