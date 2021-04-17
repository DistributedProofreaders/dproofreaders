<?php


$browser_title = _("Moderate Proofreading Quiz");
$intro_title = sprintf(_("Moderate Proofreading Quiz, page %d"), 5);
$initial_instructions = $initial_instructions__P;
$initial_text = "when she discovered a new suitor, she had\nrecourse to a Rakshasa, who swallowed that\nunhappy suitor wholeb. So Natabhrukuti\nlooked towards the city wall, and she saw\nAja. And at the very first glance, she fell so\nviolently in love with him^c that she could\n\nb This method of disposing of objectionable suit-ors\nis unfortunately not available in Europe.\n\nc Who ever loved that loved not at first sight?\nEvery Oriental would side with Shakspeare in\nthis matter: love, in the East, is not love, unless";
$solutions = ["when she discovered a new suitor, she had\nrecourse to a Rákshasa, who swallowed that\nunhappy suitor whole[b]. So Natabhrúkutí\nlooked towards the city wall, and she saw\nAja. And at the very first glance, she fell so\nviolently in love with him[c] that she could\n\nb This method of disposing of objectionable suitors\nis unfortunately not available in Europe.\n\nc Who ever loved that loved not at first sight?\nEvery Oriental would side with Shakspeare in\nthis matter: love, in the East, is not love, unless"];


// error messages

$messages["accenta"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: an accent over an '<kbd>a</kbd>' was omitted by the OCR software."),
];
$messages["accentu"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: an accent over a '<kbd>u</kbd>' was omitted by the OCR software."),
];
$messages["accenti"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: an accent over an '<kbd>i</kbd>' was omitted by the OCR software."),
];
$messages["tbmarkup"] = [
    "message_title" => _("Thought break markup added"),
    "message_body" => _("Please ignore the horizontal line that divides the main text from the footnotes."),
    "guideline" => "footnotes",
];
$messages["noteadded"] = [
    "message_title" => _("Note added"),
    "message_body" => _("The spelling 'Shakspeare' is a common variant; there is no need for a note about it. During normal proofreading it would be fine to add a note if you think the printer may have made an error, but for the purposes of this quiz please remove your note."),
];
$messages["fnspace"] = [
    "message_title" => _("Space around footnote"),
    "message_body" => _("Leave a blank line between the regular text and the footnotes to separate them."),
    "guideline" => "footnotes",
];
$messages["fnsuper"] = [
    "message_title" => _("Footnote marker"),
    "message_body" => _("There is no need for superscript markup for footnote markers.  Instead, the marker in the text should be surrounded by square brackets, like this: <kbd>[b]</kbd>."),
    "guideline" => "footnotes",
];
$messages["nobrackets"] = [
    "message_title" => _("Footnote marker brackets"),
    "message_body" => _("In the text, footnote markers should be in square brackets, like this: <kbd>[b]</kbd>."),
    "guideline" => "footnotes",
];
$messages["fnmarkerspace"] = [
    "message_title" => _("Footnote marker spacing"),
    "message_body" => _("Put the footnote marker right next to the word (or punctuation mark) being footnoted, like this: <br><kbd>word[c] word</kbd>."),
    "guideline" => "footnotes",
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["Rakshasa"], "case_sensitive" => true, "error" => "accenta"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Natabhru"], "case_sensitive" => true, "error" => "accentu"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["kuti"], "case_sensitive" => true, "error" => "accenti"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["Rák"], "case_sensitive" => true, "error" => "P_accentbracket"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["rúk"], "case_sensitive" => true, "error" => "P_accentbracket"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["tí\n"], "case_sensitive" => true, "error" => "P_accentbracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["suit-ors"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "longline", "lengthlimit" => 70, "error" => "P_longline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\""], "case_sensitive" => true, "error" => "P_addedquotes"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<tb>"], "case_sensitive" => false, "error" => "tbmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["eare[*", "eare [*"], "case_sensitive" => false, "error" => "noteadded"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["could\nb"], "case_sensitive" => false, "error" => "fnspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Europe.\nc"], "case_sensitive" => false, "error" => "P_fnspace2"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Footnote"], "case_sensitive" => false, "error" => "P_fnmarkup"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["unless*", "unless]", "unless["], "case_sensitive" => true, "error" => "P_fncontinued"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["2", "*", "B", "C"], "case_sensitive" => true, "error" => "P_fnmarkermissing"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["^"], "case_sensitive" => true, "error" => "fnsuper"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["wholeb", "himc", "whole b", "him c"], "case_sensitive" => false, "error" => "nobrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" [b]", " [c]"], "case_sensitive" => false, "error" => "fnmarkerspace"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "[b]", "case_sensitive" => true, "error" => "P_fnbottombrackets"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "[c]", "case_sensitive" => true, "error" => "P_fnbottombrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["bThis", "cWho"], "case_sensitive" => true, "error" => "P_fnbottomnospace"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nb This"], "case_sensitive" => true, "error" => "P_fnbottommarker"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\nc Who"], "case_sensitive" => true, "error" => "P_fnbottommarker"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["Shakspeare"], "case_sensitive" => true, "error" => "P_spellingchange"];
