<?php


$browser_title = _("Basic Proofreading Quiz");
$intro_title = sprintf(_("Basic Proofreading Quiz, page %d"), 4);
$initial_instructions = $initial_instructions__P;
$initial_text = "work he was printing would be good for\nnothing but waste paper, might\nnot be realised. The work\nappeared about the end of\nDecember 18|8 with 1819 on\nthe title-page. Schopenhauer\nhad meanwhile proceeded in\nSeptember to Italy, where lie\nrevised the final proofs.\n\nHigh art.\n\nSo far as the reception of the work was\nconcerned, Goethe \" had received it with\ngreat joy and began instantly to read it. An\n\nWallace, Life, p. 108.";
$solutions = ["work he was printing would be good for\nnothing but waste paper, might\nnot be realised.[*] The work\nappeared about the end of\nDecember 1818 with 1819 on\nthe title-page. Schopenhauer\nhad meanwhile proceeded in\nSeptember to Italy, where he\nrevised the final proofs.\n\nHigh art.\n\nSo far as the reception of the work was\nconcerned, Goethe \"had received it with\ngreat joy and began instantly to read it. An\n\n* Wallace, Life, p. 108."];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Sometimes a word or punctuation mark may seem incorrect, but it could turn out to be what the author intended. However, if you believe the printer made an error, proofread it as the image shows and and[**duplicate word] add a note describing yuor[**typo for your?] concern, as shown [**missing word here?] this sentence[**.]") . "</p>";


// error messages

$messages["datescanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a '<kbd>|</kbd>' (vertical bar) in the text where there is a '<kbd>1</kbd>' (number one) in the image."),
];
$messages["lie"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': '<kbd>li</kbd>' in the text where there is an '<kbd>h</kbd>' in the image."),
];
$messages["nobrackets"] = [
    "message_title" => _("Footnote marker brackets"),
    "message_body" => _("In the text, the footnote marker should be in square brackets, like this: <kbd>[*]</kbd>."),
    "guideline" => "footnotes",
];
$messages["fnmarkerspace"] = [
    "message_title" => _("Footnote marker spacing"),
    "message_body" => _("Put the footnote marker right next to the word being footnoted (or its punctuation mark), like this: <br><kbd>word,[*] word</kbd>."),
    "guideline" => "footnotes",
];
$messages["fnmarkerplace"] = [
    "message_title" => _("Footnote marker placement"),
    "message_body" => _("Put the footnote marker in the same place as in the original book; do not change the order of the footnote marker and nearby punctuation."),
    "guideline" => "footnotes",
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["lized"], "case_sensitive" => true, "error" => "P_spellingchange"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["18|8"], "case_sensitive" => true, "error" => "datescanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["lie"], "case_sensitive" => true, "error" => "lie"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" \" "], "case_sensitive" => true, "error" => "P_openquotespace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["work\n"], "case_sensitive" => true, "error" => "P_lbchange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["in\n"], "case_sensitive" => true, "error" => "P_lbchange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["*"], "case_sensitive" => true, "error" => "P_fnneedaster"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[*]"], "case_sensitive" => true, "error" => "nobrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" ["], "case_sensitive" => true, "error" => "fnmarkerspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["ed["], "case_sensitive" => true, "error" => "fnmarkerplace"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "[*]", "case_sensitive" => true, "error" => "P_fnbottombrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["*Wallace"], "case_sensitive" => true, "error" => "P_fnbottomnospace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Footnote"], "case_sensitive" => false, "error" => "P_fnmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["* Wallace"], "case_sensitive" => true, "error" => "P_fnbottommarker"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Illustration"], "case_sensitive" => false, "error" => "P_illmarkup"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "High art", "secondtext" => "reception", "case_sensitive" => true, "error" => "P_captionmove"];
$tests[] = ["type" => "wrongtextorder", "firsttext" => "proofs", "secondtext" => "High art", "case_sensitive" => true, "error" => "P_captionmove"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n\nHigh art.\n\n"], "case_sensitive" => true, "error" => "P_captionspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["An\""], "case_sensitive" => true, "error" => "P_addedclosequote"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["An\n\n"], "case_sensitive" => true, "error" => "P_fnspace"];
