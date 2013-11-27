<?php

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_basic_4","pass");
}

$browser_title                = _("Basic Proofreading Quiz");
$welcome                      = "<h2>" . _("Basic Proofreading Quiz, page 4") . "</h2>\n" .
                                    "<p>" . _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines. When done click 'check'.") . "</p>";
$ocr_text                     = "work he was printing would be good for\nnothing but waste paper, might\nnot be realised. The work\nappeared about the end of\nDecember 18|8 with 1819 on\nthe title-page. Schopenhauer\nhad meanwhile proceeded in\nSeptember to Italy, where lie\nrevised the final proofs.\n\nHigh art.\n\nSo far as the reception of the work was\nconcerned, Goethe \" had received it with\ngreat joy and began instantly to read it. An\n\nWallace, Life, p. 108.";
$solutions                    = array("work he was printing would be good for\nnothing but waste paper, might\nnot be realised.[*] The work\nappeared about the end of\nDecember 1818 with 1819 on\nthe title-page. Schopenhauer\nhad meanwhile proceeded in\nSeptember to Italy, where he\nrevised the final proofs.\n\nHigh art.\n\nSo far as the reception of the work was\nconcerned, Goethe \"had received it with\ngreat joy and began instantly to read it. An\n\n* Wallace, Life, p. 108.");
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("Sometimes a word or punctuation mark may seem incorrect, but it could turn out to be what the author intended. However, if you believe the printer made an error, proofread it as the image shows and and[**duplicate word] add a note describing yuor[**typo for your?] concern, as shown [**missing word here?] this sentence[**.]") . "</p>";


// error messages

$messages["datescanno"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a 'scanno': a '<tt>|</tt>' (vertical bar) in the text where there is a '<tt>1</tt>' (number one) in the image.") . "</p>",
    "hints" => array(),
);
$messages["lie"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a 'scanno': '<tt>li</tt>' in the text where there is an '<tt>h</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["nobrackets"] = array(
    "message_text" => "<h2>" . _("Footnote marker brackets") . "</h2>\n" .
        "<p>" . _("In the text, the footnote marker should be in square brackets, like this: <tt>[*]</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes",
);
$messages["fnmarkerspace"] = array(
    "message_text" => "<h2>" . _("Footnote marker spacing") . "</h2>\n" .
        "<p>" . _("Put the footnote marker right next to the word being footnoted (or its punctuation mark), like this: <br><tt>word,[*] word</tt>.") . "</p>",
    "hints" => array(),
    "guideline" => "footnotes",
);
$messages["fnmarkerplace"] = array(
    "message_text" => "<h2>" . _("Footnote marker placement") . "</h2>\n" .
        "<p>" . _("Put the footnote marker in the same place as in the original book; do not change the order of the footnote marker and nearby punctuation.") . "</p>",
    "hints" => array(),
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
