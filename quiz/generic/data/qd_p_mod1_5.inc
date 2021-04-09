<?php


$browser_title                = _("Moderate Proofreading Quiz");
$intro_title                  = sprintf(_("Moderate Proofreading Quiz, page %d"), 5);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "when she discovered a new suitor, she had\nrecourse to a Rakshasa, who swallowed that\nunhappy suitor wholeb. So Natabhrukuti\nlooked towards the city wall, and she saw\nAja. And at the very first glance, she fell so\nviolently in love with him^c that she could\n\nb This method of disposing of objectionable suit-ors\nis unfortunately not available in Europe.\n\nc Who ever loved that loved not at first sight?\nEvery Oriental would side with Shakspeare in\nthis matter: love, in the East, is not love, unless";
$solutions                    = array("when she discovered a new suitor, she had\nrecourse to a Rákshasa, who swallowed that\nunhappy suitor whole[b]. So Natabhrúkutí\nlooked towards the city wall, and she saw\nAja. And at the very first glance, she fell so\nviolently in love with him[c] that she could\n\nb This method of disposing of objectionable suitors\nis unfortunately not available in Europe.\n\nc Who ever loved that loved not at first sight?\nEvery Oriental would side with Shakspeare in\nthis matter: love, in the East, is not love, unless");


// error messages

$messages["accenta"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: an accent over an '<kbd>a</kbd>' was omitted by the OCR software."),
);
$messages["accentu"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: an accent over a '<kbd>u</kbd>' was omitted by the OCR software."),
);
$messages["accenti"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: an accent over an '<kbd>i</kbd>' was omitted by the OCR software."),
);
$messages["tbmarkup"] = array(
    "message_title" => _("Thought break markup added"),
    "message_body" => _("Please ignore the horizontal line that divides the main text from the footnotes."),
    "guideline" => "footnotes",
);
$messages["noteadded"] = array(
    "message_title" => _("Note added"),
    "message_body" => _("The spelling 'Shakspeare' is a common variant; there is no need for a note about it. During normal proofreading it would be fine to add a note if you think the printer may have made an error, but for the purposes of this quiz please remove your note."),
);
$messages["fnspace"] = array(
    "message_title" => _("Space around footnote"),
    "message_body" => _("Leave a blank line between the regular text and the footnotes to separate them."),
    "guideline" => "footnotes",
);
$messages["fnsuper"] = array(
    "message_title" => _("Footnote marker"),
    "message_body" => _("There is no need for superscript markup for footnote markers.  Instead, the marker in the text should be surrounded by square brackets, like this: <kbd>[b]</kbd>."),
    "guideline" => "footnotes",
);
$messages["nobrackets"] = array(
    "message_title" => _("Footnote marker brackets"),
    "message_body" => _("In the text, footnote markers should be in square brackets, like this: <kbd>[b]</kbd>."),
    "guideline" => "footnotes",
);
$messages["fnmarkerspace"] = array(
    "message_title" => _("Footnote marker spacing"),
    "message_body" => _("Put the footnote marker right next to the word (or punctuation mark) being footnoted, like this: <br><kbd>word[c] word</kbd>."),
    "guideline" => "footnotes",
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Rakshasa"), "case_sensitive" => TRUE, "error" => "accenta");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Natabhru"), "case_sensitive" => TRUE, "error" => "accentu");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("kuti"), "case_sensitive" => TRUE, "error" => "accenti");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Rák"), "case_sensitive" => TRUE, "error" => "P_accentbracket");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("rúk"), "case_sensitive" => TRUE, "error" => "P_accentbracket");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("tí\n"), "case_sensitive" => TRUE, "error" => "P_accentbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("suit-ors"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "longline", "lengthlimit" => 70, "error" => "P_longline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\""), "case_sensitive" => TRUE, "error" => "P_addedquotes");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<tb>"), "case_sensitive" => FALSE, "error" => "tbmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("eare[*", "eare [*"), "case_sensitive" => FALSE, "error" => "noteadded");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("could\nb"), "case_sensitive" => FALSE, "error" => "fnspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Europe.\nc"), "case_sensitive" => FALSE, "error" => "P_fnspace2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Footnote"), "case_sensitive" => FALSE, "error" => "P_fnmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("unless*", "unless]", "unless["), "case_sensitive" => TRUE, "error" => "P_fncontinued");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("2", "*", "B", "C"), "case_sensitive" => TRUE, "error" => "P_fnmarkermissing");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("^"), "case_sensitive" => TRUE, "error" => "fnsuper");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("wholeb", "himc", "whole b", "him c"), "case_sensitive" => FALSE, "error" => "nobrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" [b]", " [c]"), "case_sensitive" => FALSE, "error" => "fnmarkerspace");
$tests[] = array("type" => "multioccurrence", "searchtext" => "[b]", "case_sensitive" => TRUE, "error" => "P_fnbottombrackets");
$tests[] = array("type" => "multioccurrence", "searchtext" => "[c]", "case_sensitive" => TRUE, "error" => "P_fnbottombrackets");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("bThis", "cWho"), "case_sensitive" => TRUE, "error" => "P_fnbottomnospace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nb This"), "case_sensitive" => TRUE, "error" => "P_fnbottommarker");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("\nc Who"), "case_sensitive" => TRUE, "error" => "P_fnbottommarker");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Shakspeare"), "case_sensitive" => TRUE, "error" => "P_spellingchange");

