<?php


$browser_title = _("Moderate Proofreading Quiz");
$intro_title = sprintf(_("Moderate Proofreading Quiz, page %d"), 1);
$initial_instructions = $initial_instructions__P;
$initial_text = "warm bath on March 8th (who shall say hereafter\nthat science is unfeeling?), upon which the grateful\nsnail put his head cautiously out of his shell,\nwalked up to the top of the basin, and began to take\na survey of Eritish institutions with his four eyebearing\ntentacles. So strange a recovery from a\nlong torpid condition deserved an exceptional amount\nof scientific recognition. The desert snail at\n\nDesert snail.\n\nonce found himself famous. Nay, he actually sat\nfor his portrait to an eminent zoological artist, Mr.\n\nB3";
$solutions = [
    "warm bath on March 8^{th} (who shall say hereafter\nthat science is unfeeling!), upon which the grateful\nsnail put his head cautiously out of his shell,\nwalked up to the top of the basin, and began to take\na survey of British institutions with his four eye-*bearing\ntentacles. So strange a recovery from a\nlong torpid condition deserved an exceptional amount\nof scientific recognition. The desert snail at\n\nDesert snail.\n\nonce found himself famous. Nay, he actually sat\nfor his portrait to an eminent zoological artist, Mr.",
    "warm bath on March 8^{th} (who shall say hereafter\nthat science is unfeeling!), upon which the grateful\nsnail put his head cautiously out of his shell,\nwalked up to the top of the basin, and began to take\na survey of British institutions with his four eye-bearing\ntentacles. So strange a recovery from a\nlong torpid condition deserved an exceptional amount\nof scientific recognition. The desert snail at\n\nDesert snail.\n\nonce found himself famous. Nay, he actually sat\nfor his portrait to an eminent zoological artist, Mr.",
];
$criteria = ["e-*b"];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("In many books a page footer consisting of a number and/or letter will appear on some pages but not others.  These are <i>printer's marks</i>, used to assist the printer in assembling the sections of the book in order.  They often only appear every 16 pages.  Printer's marks should be deleted like any other page footer.") . "</p>";


// error messages

$messages["qmark"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a question mark (<kbd>?</kbd>) in the text where there is an exclamation point (<kbd>!</kbd>) in the image."),
];
$messages["Eritish"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': an '<kbd>E</kbd>' in the text where there is a '<kbd>B</kbd>' in the image."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["?"], "case_sensitive" => true, "error" => "qmark"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Eritish"], "case_sensitive" => true, "error" => "Eritish"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["8th"], "case_sensitive" => true, "error" => "P_supnomark"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["^th"], "case_sensitive" => true, "error" => "P_supnobraces"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["a-mount", "eyebearing"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "longline", "lengthlimit" => 70, "error" => "P_longline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["B3"], "case_sensitive" => true, "error" => "P_footer"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<b>", "</b>"], "case_sensitive" => false, "error" => "P_boldmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Illustration"], "case_sensitive" => false, "error" => "P_illmarkup"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "Desert snail", "secondtext" => "famous", "case_sensitive" => true, "error" => "P_captionmove"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "recognition", "secondtext" => "Desert snail", "case_sensitive" => true, "error" => "P_captionmove"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\nDesert snail.\n\n"], "case_sensitive" => true, "error" => "P_captionspace"];
