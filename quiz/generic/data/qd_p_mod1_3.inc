<?php


$browser_title                = _("Moderate Proofreading Quiz");
$intro_title                  = sprintf(_("Moderate Proofreading Quiz, page %d"), 3);
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "pedagogue, of Birmingham manufacture, &lt;i&gt;viz,\nDr&lt;/i&gt;. Parr, had amassed a considerable quantity of\ngold plate. But growing every day more afraid\nof being murdered, which he knew that he could\nnot stand, he transferred the whole to the black-\nsmith; conceiving, no doubt, that the murder of\na blacksmith would fall more lightly on the\nsalus reipublicae, than that of a pedagogue. But I\nhave heard this greatly disputed ; and it seems\nnow generally agreed, that one good horse-shoe\nis worth about 2% Spital sermons.";
$criteria                     = array("<i>");
$solutions                    = array(
                                    "pedagogue, of Birmingham manufacture, <i>viz.,\nDr</i>. Parr, had amassed a considerable quantity of\ngold plate. But growing every day more afraid\nof being murdered, which he knew that he could\nnot stand, he transferred the whole to the blacksmith;\nconceiving, no doubt, that the murder of\na blacksmith would fall more lightly on the\nsalus reipublicæ, than that of a pedagogue. But I\nhave heard this greatly disputed; and it seems\nnow generally agreed, that one good horse-shoe\nis worth about 2-1/4 Spital sermons.",
                                    "pedagogue, of Birmingham manufacture, viz.,\nDr. Parr, had amassed a considerable quantity of\ngold plate. But growing every day more afraid\nof being murdered, which he knew that he could\nnot stand, he transferred the whole to the blacksmith;\nconceiving, no doubt, that the murder of\na blacksmith would fall more lightly on the\nsalus reipublicæ, than that of a pedagogue. But I\nhave heard this greatly disputed; and it seems\nnow generally agreed, that one good horse-shoe\nis worth about 2-1/4 Spital sermons.",
                                );
$parting_message              = "<h3>" . _("Handy Fact") . "</h3>\n" .
                                    "<p>" . _("During proofreading we write out fractions as <tt>1/4</tt> rather than <tt>&frac14;</tt> because not all fractions are available as single symbols. Other fractions such as 2/7 may also appear in the text and it would be inconsistent to mix the two notations.") . "</p>";

// error messages

$messages["periodmissing"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno' in the text: a period (<tt>.</tt>) was omitted by the OCR software."),
);
$messages["fracscanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': a percent sign (<tt>%</tt>) in the text where there is a fraction in the image."),
);
$messages["ae"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': '<tt>ae</tt>' in the text where there is an '<tt>&aelig;</tt>' ('ae' ligature) in the image.  You can insert the &aelig; character using the character picker below."),
);
$messages["fracsymbol"] = array(
    "message_title" => _("Fraction"),
    "message_body" => _("Write out fractions when proofreading.  For instance, <tt>2&frac12;</tt> becomes <tt>2-1/2</tt>."),
    "guideline" => "fract_s",
);
$messages["frachyphen"] = array(
    "message_title" => _("Fraction"),
    "message_body" => _("When proofreading a fraction, use a hyphen to connect it to the whole number.  For instance, <tt>2&frac12;</tt> becomes <tt>2-1/2</tt>."),
    "guideline" => "fract_s",
);
$messages["eolhyphen2"] = array(
    "message_title" => _("Hyphenation"),
    "message_body" => _("Since the word \"blacksmith\" also appears in the middle of a line on this page, we know that the author wrote the word without a hyphen.  There is no need to keep the hyphen or add an asterisk when rejoining the word."),
);
$messages["italmarkup"] = array(
    "message_title" => _("Italics markup moved"),
    "message_body" => _("Italicized text may occasionally appear with <tt>&lt;i&gt;</tt> inserted at the start and <tt>&lt;/i&gt;</tt> inserted at the end of the italics. Do not change this formatting markup, even if it is incorrect, unless it is so distracting that you find it hard to proofread.  In that case, remove it completely.  The formatters will fix or add markup later in the process."),
    "guideline" => "formatting",
); 


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("viz,"), "case_sensitive" => TRUE, "error" => "periodmissing");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("%"), "case_sensitive" => TRUE, "error" => "fracscanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("cae"), "case_sensitive" => TRUE, "error" => "ae");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[ae]"), "case_sensitive" => TRUE, "error" => "P_aebracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array(" ;"), "case_sensitive" => TRUE, "error" => "P_colonspace");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("¼"), "case_sensitive" => TRUE, "error" => "fracsymbol");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("2 1/4"), "case_sensitive" => TRUE, "error" => "frachyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("-\n"), "case_sensitive" => TRUE, "error" => "P_eolhyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("black-"), "case_sensitive" => TRUE, "error" => "eolhyphen2");
$tests[] = array("type" => "longline", "lengthlimit" => 70, "error" => "P_longline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("<i>viz.,\nDr</i>.", " viz.,\nDr. "), "case_sensitive" => TRUE, "error" => "italmarkup");

// vim: sw=4 ts=4 expandtab
