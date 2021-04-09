<?php


$browser_title                = _("Moderate Proofreading Quiz");
$intro_title                  = sprintf(_("Moderate Proofreading Quiz, page %d"), 1);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "warm bath on March 8th (who shall say hereafter\nthat science is unfeeling?), upon which the grateful\nsnail put his head cautiously out of his shell,\nwalked up to the top of the basin, and began to take\na survey of Eritish institutions with his four eyebearing\ntentacles. So strange a recovery from a\nlong torpid condition deserved an exceptional amount\nof scientific recognition. The desert snail at\n\nDesert snail.\n\nonce found himself famous. Nay, he actually sat\nfor his portrait to an eminent zoological artist, Mr.\n\nB3";
$solutions                    = array(
                                    "warm bath on March 8^{th} (who shall say hereafter\nthat science is unfeeling!), upon which the grateful\nsnail put his head cautiously out of his shell,\nwalked up to the top of the basin, and began to take\na survey of British institutions with his four eye-*bearing\ntentacles. So strange a recovery from a\nlong torpid condition deserved an exceptional amount\nof scientific recognition. The desert snail at\n\nDesert snail.\n\nonce found himself famous. Nay, he actually sat\nfor his portrait to an eminent zoological artist, Mr.",
                                    "warm bath on March 8^{th} (who shall say hereafter\nthat science is unfeeling!), upon which the grateful\nsnail put his head cautiously out of his shell,\nwalked up to the top of the basin, and began to take\na survey of British institutions with his four eye-bearing\ntentacles. So strange a recovery from a\nlong torpid condition deserved an exceptional amount\nof scientific recognition. The desert snail at\n\nDesert snail.\n\nonce found himself famous. Nay, he actually sat\nfor his portrait to an eminent zoological artist, Mr.",
                                );
$criteria                     = array("e-*b");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("In many books a page footer consisting of a number and/or letter will appear on some pages but not others.  These are <i>printer's marks</i>, used to assist the printer in assembling the sections of the book in order.  They often only appear every 16 pages.  Printer's marks should be deleted like any other page footer.") . "</p>";


// error messages

$messages["qmark"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a question mark (<kbd>?</kbd>) in the text where there is an exclamation point (<kbd>!</kbd>) in the image."),
);
$messages["Eritish"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': an '<kbd>E</kbd>' in the text where there is a '<kbd>B</kbd>' in the image."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("?"), "case_sensitive" => TRUE, "error" => "qmark");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Eritish"), "case_sensitive" => TRUE, "error" => "Eritish");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("8th"), "case_sensitive" => TRUE, "error" => "P_supnomark");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("^th"), "case_sensitive" => TRUE, "error" => "P_supnobraces");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("a-mount", "eyebearing"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "longline", "lengthlimit" => 70, "error" => "P_longline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("B3"), "case_sensitive" => TRUE, "error" => "P_footer");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<b>", "</b>"), "case_sensitive" => FALSE, "error" => "P_boldmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Illustration"), "case_sensitive" => FALSE, "error" => "P_illmarkup");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "Desert snail", "secondtext" => "famous", "case_sensitive" => TRUE, "error" => "P_captionmove");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "recognition", "secondtext" => "Desert snail", "case_sensitive" => TRUE, "error" => "P_captionmove");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\nDesert snail.\n\n"), "case_sensitive" => TRUE, "error" => "P_captionspace");

