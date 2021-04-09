<?php


$browser_title                = _("Basic Proofreading Quiz");
$intro_title                  = sprintf(_("Basic Proofreading Quiz, page %d"), 3);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "bundles into the house, while Mirabell's mother\nwent on home in her automobile.\n\nCHAPTER II\n\nThe Rabbit's New Home\n\nOh, Mother! What have you?\" cried the\nvoice of a little girl, as the lady entered\nthe house with the bundle.\n\n\"Is it...something good to cat..?\" asked a\nboy's voice.\n\n\"Now, children, you must not ask too many\nquestions.\" said their mother. \"This isn't exactly\nCHRISTMAS, but it will soon be EASTER, and we";
$solutions                    = array("bundles into the house, while Mirabell's mother\nwent on home in her automobile.\n\nCHAPTER II\n\nThe Rabbit's New Home\n\n\"Oh, Mother! What have you?\" cried the\nvoice of a little girl, as the lady entered\nthe house with the bundle.\n\n\"Is it ... something good to eat...?\" asked a\nboy's voice.\n\n\"Now, children, you must not ask too many\nquestions,\" said their mother. \"This isn't exactly\nCHRISTMAS, but it will soon be EASTER, and we");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("While proofreading, if you encounter something that isn't covered in the guidelines or that you are not sure how to handle, post your question in the Project Discussion thread (a link to the project-specific thread is in the Project Comments).  The Project Manager or other site volunteers will let you know how to handle it.") . "</p>";


// error messages
$messages["cat"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a '<kbd>c</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
);
$messages["chapchange"] = array(
    "message_title" => _("Chapter heading changed"),
    "message_body" => _("Leave the text of the chapter heading as it appears in the image. Do not change ALL CAPS to Title Case or otherwise alter the text."),
    "guideline" => "chap_head",
);
$messages["ellspaced"] = array(
    "message_title" => _("Ellipsis Spacing"),
    "message_body" => _("Please do not leave spaces between the dots of an ellipsis.  The dots should be right next to each other, like ... or...."),
    "guideline" => "period_p",
);


// error checks
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("cat"), "case_sensitive" => TRUE, "error" => "cat");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("questions."), "case_sensitive" => TRUE, "error" => "P_commascanno");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("The Rabbit's New Home"), "case_sensitive" => TRUE, "error" => "chapchange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("CHAPTER II"), "case_sensitive" => TRUE, "error" => "chapchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n\n\n"), "case_sensitive" => TRUE, "error" => "P_spacedchap");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("II\nThe"), "case_sensitive" => TRUE, "error" => "P_missinglb");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\nOh"), "case_sensitive" => TRUE, "error" => "P_missingquote");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(". . ."), "case_sensitive" => TRUE, "error" => "ellspaced");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("it ... something"), "case_sensitive" => TRUE, "error" => "P_midellips");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("eat...?"), "case_sensitive" => TRUE, "error" => "P_endellips");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<sc>", "</sc>"), "case_sensitive" => FALSE, "error" => "P_scmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("EASTER"), "case_sensitive" => TRUE, "error" => "P_casechange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("CHRISTMAS"), "case_sensitive" => TRUE, "error" => "P_casechange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<b>", "</b>"), "case_sensitive" => FALSE, "error" => "P_boldmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("we\""), "case_sensitive" => TRUE, "error" => "P_addedclosequote");

