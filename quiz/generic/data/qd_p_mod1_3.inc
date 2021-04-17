<?php


$browser_title = _("Moderate Proofreading Quiz");
$intro_title = sprintf(_("Moderate Proofreading Quiz, page %d"), 3);
$initial_instructions = $initial_instructions__P;
$initial_text = "pedagogue, of Birmingham manufacture, &lt;i&gt;viz,\nDr&lt;/i&gt;. Parr, had amassed a considerable quantity of\ngold plate. But growing every day more afraid\nof being murdered, which he knew that he could\nnot stand, he transferred the whole to the black-\nsmith; conceiving, no doubt, that the murder of\na blacksmith would fall more lightly on the\nsalus reipublicae, than that of a pedagogue. But I\nhave heard this greatly disputed ; and it seems\nnow generally agreed, that one good horse-shoe\nis worth about 2% Spital sermons.";
$criteria = ["<i>"];
$solutions = [
    "pedagogue, of Birmingham manufacture, <i>viz.,\nDr</i>. Parr, had amassed a considerable quantity of\ngold plate. But growing every day more afraid\nof being murdered, which he knew that he could\nnot stand, he transferred the whole to the blacksmith;\nconceiving, no doubt, that the murder of\na blacksmith would fall more lightly on the\nsalus reipublicæ, than that of a pedagogue. But I\nhave heard this greatly disputed; and it seems\nnow generally agreed, that one good horse-shoe\nis worth about 2-1/4 Spital sermons.",
    "pedagogue, of Birmingham manufacture, viz.,\nDr. Parr, had amassed a considerable quantity of\ngold plate. But growing every day more afraid\nof being murdered, which he knew that he could\nnot stand, he transferred the whole to the blacksmith;\nconceiving, no doubt, that the murder of\na blacksmith would fall more lightly on the\nsalus reipublicæ, than that of a pedagogue. But I\nhave heard this greatly disputed; and it seems\nnow generally agreed, that one good horse-shoe\nis worth about 2-1/4 Spital sermons.",
];
$parting_message = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("During proofreading we write out fractions as <kbd>1/4</kbd> rather than <kbd>¼</kbd> because not all fractions are available as single symbols. Other fractions such as 2/7 may also appear in the text and it would be inconsistent to mix the two notations.") . "</p>";

// error messages

$messages["periodmissing"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: a period (<kbd>.</kbd>) was omitted by the OCR software."),
];
$messages["fracscanno"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a percent sign (<kbd>%</kbd>) in the text where there is a fraction in the image."),
];
$messages["ae"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': '<kbd>ae</kbd>' in the text where there is an '<kbd>æ</kbd>' ('ae' ligature) in the image.  You can insert the <kbd>æ</kbd> character using the character picker below."),
];
$messages["fracsymbol"] = [
    "message_title" => _("Fraction"),
    "message_body" => _("Write out fractions when proofreading.  For instance, <kbd>2½</kbd> becomes <kbd>2-1/2</kbd>."),
    "guideline" => "fract_s",
];
$messages["frachyphen"] = [
    "message_title" => _("Fraction"),
    "message_body" => _("When proofreading a fraction, use a hyphen to connect it to the whole number.  For instance, <kbd>2½</kbd> becomes <kbd>2-1/2</kbd>."),
    "guideline" => "fract_s",
];
$messages["eolhyphen2"] = [
    "message_title" => _("Hyphenation"),
    "message_body" => _("Since the word \"blacksmith\" also appears in the middle of a line on this page, we know that the author wrote the word without a hyphen.  There is no need to keep the hyphen or add an asterisk when rejoining the word."),
];
$messages["italmarkup"] = [
    "message_title" => _("Italics markup moved"),
    "message_body" => _("Italicized text may occasionally appear with <kbd>&lt;i&gt;</kbd> inserted at the start and <kbd>&lt;/i&gt;</kbd> inserted at the end of the italics. Do not change this formatting markup, even if it is incorrect, unless it is so distracting that you find it hard to proofread.  In that case, remove it completely.  The formatters will fix or add markup later in the process."),
    "guideline" => "formatting",
];


// error checks

$tests[] = ["type" => "forbiddentext", "searchtext" => ["viz,"], "case_sensitive" => true, "error" => "periodmissing"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["%"], "case_sensitive" => true, "error" => "fracscanno"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["cae"], "case_sensitive" => true, "error" => "ae"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["[ae]"], "case_sensitive" => true, "error" => "P_aebracket"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" ;"], "case_sensitive" => true, "error" => "P_colonspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["¼"], "case_sensitive" => true, "error" => "fracsymbol"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["2 1/4"], "case_sensitive" => true, "error" => "frachyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["-\n"], "case_sensitive" => true, "error" => "P_eolhyphen"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["black-"], "case_sensitive" => true, "error" => "eolhyphen2"];
$tests[] = ["type" => "longline", "lengthlimit" => 70, "error" => "P_longline"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["<i>viz.,\nDr</i>.", " viz.,\nDr. "], "case_sensitive" => true, "error" => "italmarkup"];
