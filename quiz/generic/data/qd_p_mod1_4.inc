<?php


$browser_title = _("Moderate Proofreading Quiz");
$intro_title = sprintf(_("Moderate Proofreading Quiz, page %d"), 4);
$initial_instructions = $initial_instructions__P;
$initial_text = "And the meteor on the grave,\nAnd the wisp on the morass;6\nWhen the falling stars are shooting,\nAnd the answer'd owls are hooting.\n\n6[Manfred was done into Italian by a translator\n\"who was unable to find in the dictionaries . . .\nany other signification of the 'wisp' of this line\nthan 'a bundle of straw.\"' Byron offered him\n2OO francs if he would destroy the MS. He at\nfirst refused, but finally signed the agree-\nment.--Life, p. 3l6, note.]";
$solutions = ["And the meteor on the grave,\nAnd the wisp on the morass;[6]\nWhen the falling stars are shooting,\nAnd the answer'd owls are hooting,\n\n6 [Manfred was done into Italian by a translator\n\"who was unable to find in the dictionaries ...\nany other signification of the 'wisp' of this line\nthan 'a bundle of straw.'\" Byron offered him\n200 francs if he would destroy the MS. He at\nfirst refused, but finally signed the agreement.--Life,\np. 316, note.]"];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("When the text is rewrapped during post-processing, the end of a line is converted into a space.  This means that during proofreading, anything that should have a space after it (such as an ellipsis) can be left at the end of a line.") . "</p>";


// error messages

$messages["onescanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': an '<kbd>l</kbd>' (lower case L) in the text where there is a '<kbd>1</kbd>' (number one) in the image."),
];
$messages["zeroscanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': an '<kbd>O</kbd>' (capital letter o) in the text where there is a '<kbd>0</kbd>' (zero) in the image."),
];
$messages["quotescanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: adjacent quotation marks were OCR'd incorrectly."),
];
$messages["eolellips"] = [
    "message_title" => _("Ellipsis"),
    "message_body" => _("An ellipsis can appear at the end of a line; there is no need to bring up a word from the following line."),
];
$messages["fnsuper"] = [
    "message_title" => _("Footnote marker"),
    "message_body" => _("There is no need for superscript markup for footnote markers.  Instead, the marker in the text should be surrounded by square brackets, like this: <kbd>[6]</kbd>."),
    "guideline" => "footnotes",
];
$messages["nobrackets"] = [
    "message_title" => _("Footnote marker brackets"),
    "message_body" => _("In the text, the footnote marker should be in square brackets, like this: <kbd>[6]</kbd>."),
    "guideline" => "footnotes",
];
$messages["fnmarkerspace"] = [
    "message_title" => _("Footnote marker spacing"),
    "message_body" => _("Put the footnote marker right next to the word (or punctuation mark) being footnoted, like this: <br><kbd>word[6] word</kbd>."),
    "guideline" => "footnotes",
];
$messages["bracket"] = [
    "message_title" => _("Bracket removed"),
    "message_body" => _("Leave the punctuation as the author wrote it, including any brackets in the footnotes."),
];
$messages["aposchange"] = [
    "message_title" => _("Primary Rule"),
    "message_body" => _("The primary rule of proofreading is <i>\"Don't change what the author wrote!\"</i> Please keep the spelling and punctuation as the author wrote it, including any contractions."),
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["hooting."], "case_sensitive" => true, "error" => "P_commascanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["3l6"], "case_sensitive" => true, "error" => "onescanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["O"], "case_sensitive" => true, "error" => "zeroscanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["straw.\"'"], "case_sensitive" => true, "error" => "quotescanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [". . ."], "case_sensitive" => true, "error" => "P_ellspaced"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["any\n"], "case_sensitive" => true, "error" => "eolellips"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["ies ..."], "case_sensitive" => true, "error" => "P_midellips"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["e-\n"], "case_sensitive" => true, "error" => "P_eolhyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\nagreement"], "case_sensitive" => true, "error" => "P_hyphenlower"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["agree-ment"], "case_sensitive" => true, "error" => "P_hyphenleft"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["--\n"], "case_sensitive" => true, "error" => "P_eoldash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n--"], "case_sensitive" => true, "error" => "P_soldash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-- ", " --"], "case_sensitive" => true, "error" => "P_spacedemdash"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["---"], "case_sensitive" => true, "error" => "P_em_long"];
$tests[] = ["type" => "longline", "lengthlimit" => 65, "error" => "P_longline"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["<i>", "</i>"], "case_sensitive" => false, "error" => "P_italmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["[Manf"], "case_sensitive" => true, "error" => "bracket"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["note.]"], "case_sensitive" => true, "error" => "bracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["hooting,\n6"], "case_sensitive" => true, "error" => "P_fnspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["morass;*", "morass;[*]", "morass;[A]", "morass; *", "morass; [*]"], "case_sensitive" => true, "error" => "P_fnmarkermissing"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["^6"], "case_sensitive" => true, "error" => "fnsuper"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["morass;6"], "case_sensitive" => true, "error" => "nobrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["morass; [6]"], "case_sensitive" => true, "error" => "fnmarkerspace"];
$tests[] = ["type" => "multioccurrence", "searchtext" => "[6]", "case_sensitive" => true, "error" => "P_fnbottombrackets"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["6[M"], "case_sensitive" => true, "error" => "P_fnbottomnospace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Footnote"], "case_sensitive" => false, "error" => "P_fnmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\n6 [M"], "case_sensitive" => true, "error" => "P_fnbottommarker"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["\n "], "case_sensitive" => true, "error" => "P_indent"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["/*", "*/"], "case_sensitive" => true, "error" => "P_poemmarkup"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["answer'd"], "case_sensitive" => true, "error" => "aposchange"];
