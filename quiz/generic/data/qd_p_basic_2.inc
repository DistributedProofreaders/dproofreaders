<?php


$browser_title                = _("Basic Proofreading Quiz");
$intro_title                  = sprintf(_("Basic Proofreading Quiz, page %d"), 2);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "\"I'll do my best, but you can't expect much. I\nguess I can squeeze another cot into eight-seven\nfor the young man. There's -- let's see now\n-- who's in eighty-seven? Well, there's two\nBisons in the double bed, and one in the single,\nand Fat Ed Meyers in the cot and-- \"\n\nEmma McChesney stiffened to acute attention.\n\"Meyers?\" she interrupted. \"Do you mean Ed\nMeyers of the Strauss Sans-silk skirt Company?\"\n\n\"That's so.\"\n\"Did he get in to-day?\"\n\n\"Oh, he just came 15 minutes ago on the Ash-";
$solutions                    = array("\n\"I'll do my best, but you can't expect much. I\nguess I can squeeze another cot into eight-seven\nfor the young man. There's--let's see now--who's\nin eighty-seven? Well, there's two\nBisons in the double bed, and one in the single,\nand Fat Ed Meyers in the cot and----\"\n\nEmma McChesney stiffened to acute attention.\n\"Meyers?\" she interrupted. \"Do you mean Ed\nMeyers of the Strauss Sans-silk Skirt Company?\"\n\n\"That's so.\"\n\n\"Did he get in to-day?\"\n\n\"Oh, he just came 15 minutes ago on the Ash-*");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Later on during post-processing the text will be rewrapped, and as part of that process all the line breaks within paragraphs will be converted to spaces. During proofreading we remove spaces around hyphens and em-dashes so that when rewrapped, the spacing doesn't look strange.") . "</p>";


// error messages

$messages["skirt"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a lower case '<kbd>s</kbd>' in the text where there is a capital '<kbd>S</kbd>' in the image."),
);
$messages["para"] = array(
    "message_title" => _("Paragraph spacing"),
    "message_body" => _("There is a blank line missing between two paragraphs in the text."),
    "guideline" => "para_space",
);
$messages["today"] = array(
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please leave the text as the author wrote it, including older spellings like <i>to-day</i>."),
);


// error checks

$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\"I'll"), "case_sensitive" => TRUE, "error" => "P_paratop");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("skirt"), "case_sensitive" => TRUE, "error" => "skirt");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("---let's", "---who's"), "case_sensitive" => TRUE, "error" => "P_em_long");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("and--\"", "and --\"", "and-- \"", "and -- \""), "case_sensitive" => TRUE, "error" => "P_long_em");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" ----", "---- "), "case_sensitive" => TRUE, "error" => "P_spacedlongdash");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" --", "-- "), "case_sensitive" => TRUE, "error" => "P_spacedemdash");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n--"), "case_sensitive" => TRUE, "error" => "P_soldash");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\nnow--"), "case_sensitive" => TRUE, "error" => "P_lowerlinedash");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Ash-*"), "case_sensitive" => TRUE, "error" => "P_eophyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("so.\"\n\"Did"), "case_sensitive" => TRUE, "error" => "para");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("today"), "case_sensitive" => TRUE, "error" => "today");
$tests[] = array("type" => "longline", "lengthlimit" => 70, "error" => "P_longline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Ash-*\""), "case_sensitive" => TRUE, "error" => "P_addedclosequote");

