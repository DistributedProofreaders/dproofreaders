<?php


$browser_title                = _("Basic Proofreading Quiz");
$intro_title                  = sprintf(_("Basic Proofreading Quiz, page %d"), 4);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "work he was printing would be good for\nnothing but waste paper, might\nnot be realised. The work\nappeared about the end of\nDecember 18|8 with 1819 on\nthe title-page. Schopenhauer\nhad meanwhile proceeded in\nSeptember to Italy, where lie\nrevised the final proofs.\n\nHigh art.\n\nSo far as the reception of the work was\nconcerned, Goethe \" had received it with\ngreat joy and began instantly to read it. An\n\nWallace, Life, p. 108.";
$solutions                    = array("work he was printing would be good for\nnothing but waste paper, might\nnot be realised.[*] The work\nappeared about the end of\nDecember 1818 with 1819 on\nthe title-page. Schopenhauer\nhad meanwhile proceeded in\nSeptember to Italy, where he\nrevised the final proofs.\n\nHigh art.\n\nSo far as the reception of the work was\nconcerned, Goethe \"had received it with\ngreat joy and began instantly to read it. An\n\n* Wallace, Life, p. 108.");
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Sometimes a word or punctuation mark may seem incorrect, but it could turn out to be what the author intended. However, if you believe the printer made an error, proofread it as the image shows and and[**duplicate word] add a note describing yuor[**typo for your?] concern, as shown [**missing word here?] this sentence[**.]") . "</p>";


// error messages

$messages["datescanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a '<kbd>|</kbd>' (vertical bar) in the text where there is a '<kbd>1</kbd>' (number one) in the image."),
);
$messages["lie"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': '<kbd>li</kbd>' in the text where there is an '<kbd>h</kbd>' in the image."),
);
$messages["nobrackets"] = array(
    "message_title" => _("Footnote marker brackets"),
    "message_body" => _("In the text, the footnote marker should be in square brackets, like this: <kbd>[*]</kbd>."),
    "guideline" => "footnotes",
);
$messages["fnmarkerspace"] = array(
    "message_title" => _("Footnote marker spacing"),
    "message_body" => _("Put the footnote marker right next to the word being footnoted (or its punctuation mark), like this: <br><kbd>word,[*] word</kbd>."),
    "guideline" => "footnotes",
);
$messages["fnmarkerplace"] = array(
    "message_title" => _("Footnote marker placement"),
    "message_body" => _("Put the footnote marker in the same place as in the original book; do not change the order of the footnote marker and nearby punctuation."),
    "guideline" => "footnotes",
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("lized"), "case_sensitive" => TRUE, "error" => "P_spellingchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("18|8"), "case_sensitive" => TRUE, "error" => "datescanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("lie"), "case_sensitive" => TRUE, "error" => "lie");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" \" "), "case_sensitive" => TRUE, "error" => "P_openquotespace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("work\n"), "case_sensitive" => TRUE, "error" => "P_lbchange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("in\n"), "case_sensitive" => TRUE, "error" => "P_lbchange");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("*"), "case_sensitive" => TRUE, "error" => "P_fnneedaster");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[*]"), "case_sensitive" => TRUE, "error" => "nobrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" ["), "case_sensitive" => TRUE, "error" => "fnmarkerspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ed["), "case_sensitive" => TRUE, "error" => "fnmarkerplace");
$tests[] = array("type" => "multioccurrence", "searchtext" => "[*]", "case_sensitive" => TRUE, "error" => "P_fnbottombrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("*Wallace"), "case_sensitive" => TRUE, "error" => "P_fnbottomnospace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Footnote"), "case_sensitive" => FALSE, "error" => "P_fnmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("* Wallace"), "case_sensitive" => TRUE, "error" => "P_fnbottommarker");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Illustration"), "case_sensitive" => FALSE, "error" => "P_illmarkup");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "High art", "secondtext" => "reception", "case_sensitive" => TRUE, "error" => "P_captionmove");
$tests[] = array("type" => "wrongtextorder", "firsttext" => "proofs", "secondtext" => "High art", "case_sensitive" => TRUE, "error" => "P_captionmove");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\n\nHigh art.\n\n"), "case_sensitive" => TRUE, "error" => "P_captionspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("An\""), "case_sensitive" => TRUE, "error" => "P_addedclosequote");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("An\n\n"), "case_sensitive" => TRUE, "error" => "P_fnspace");

// vim: sw=4 ts=4 expandtab
