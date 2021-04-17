<?php


$browser_title = _("Moderate Proofreading Quiz");
$intro_title = sprintf(_("Moderate Proofreading Quiz, page %d"), 2);
$initial_instructions = $initial_instructions__P;
$initial_text = "364   ALICE ORVILLE.\n\n\"When folks can't do as they will, they must do\nas they can, I've heard say.\"\n\nThus we leave our adventurers and return north-\neast to the land from which they are receding. We\ndid n't know what else to do here, reader, for we\nwere quite as tired of the characters as you wore,\nand wanted to get them off our hands in some\nway. * * * A few people think E--e can tell\nstories tolerably well. But she can't, reader! We";
$solutions = ["\n\"When folks can't do as they will, they must do\nas they can, I've heard say.\"\n\nThus we leave our adventurers and return north-*east\nto the land from which they are receding. We\ndidn't know what else to do here, reader, for we\nwere quite as tired of the characters as you were,\nand wanted to get them off our hands in some\nway. * * * A few people think E--e can tell\nstories tolerably well. But she can't, reader! We"];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                // xgettext:no-php-format
                                "<p>" . _("Enlarging the image in the proofreading interface can often help you to identify characters that may seem unclear at first.  You can use the +25% button repeatedly in the standard interface, or simply enter the size you want, such as 200%, in the enhanced interface.") . "</p>";


// error messages

$messages["wore"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': an '<kbd>o</kbd>' in the text where there is an '<kbd>e</kbd>' in the image."),
];
$messages["asterspace"] = [
    "message_title" => _("Punctuation spacing"),
    "message_body" => _("Leave spaces between asterisks if they appear that way in the original book."),
];
$messages["asterchanged"] = [
    "message_title" => _("Asterisks"),
    "message_body" => _("If the original book uses an asterisk, keep this as the author wrote it."),
];
$messages["para"] = [
    "message_title" => _("Paragraph spacing"),
    "message_body" => _("Leave a single blank line between paragraphs, even if there is an extra gap in the image."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["364", "ALICE"], "case_sensitive" => true, "error" => "P_header"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\"When"], "case_sensitive" => true, "error" => "P_paratop"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["wore"], "case_sensitive" => true, "error" => "wore"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["did n"], "case_sensitive" => true, "error" => "P_contracspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["**"], "case_sensitive" => true, "error" => "asterspace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["* * *"], "case_sensitive" => true, "error" => "asterchanged"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-\n"], "case_sensitive" => true, "error" => "P_eolhyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["northeast", "north-east"], "case_sensitive" => true, "error" => "P_eolhyphenaster"];
$tests[] = ["type" => "longline", "lengthlimit" => 70, "error" => "P_longline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["---"], "case_sensitive" => true, "error" => "P_em_long"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-- ", " --"], "case_sensitive" => true, "error" => "P_dashneedspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<tb>", "Illustration"], "case_sensitive" => false, "error" => "P_tbmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n\n\n"], "case_sensitive" => true, "error" => "para"];
