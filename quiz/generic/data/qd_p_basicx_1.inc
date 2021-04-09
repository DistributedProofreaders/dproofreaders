<?php


$browser_title                = _("Basic Proofreading Quiz");
$initial_text                 = "a detective, why was he watching? There was\nindeed no reward offered whatsoever for his arrest.\nPerhaps he belonged to the wretched type of beings\nwho do pride themselves on their public spirit--\nmen who wrote letters to the newspapers and\ninterfered in other people's business. He might now\nwell have wanted to show his public spirit by hand-\ning him over to the police. The newspaper in his\nhand! Of course. He had read his description there,\nand identified him.\nCharles now found himself conjecturing how the\nman would set about carrying out his task of pub-";
$solutions                    = array("a detective, why was he watching? There was\nindeed no reward offered whatsoever for his arrest.\nPerhaps he belonged to the wretched type of beings\nwho do pride themselves on their public spirit--men\nwho wrote letters to the newspapers and\ninterfered in other people's business. He might now\nwell have wanted to show his public spirit by handing\nhim over to the police. The newspaper in his\nhand! Of course. He had read his description there,\nand identified him.\n\nCharles now found himself conjecturing how the\nman would set about carrying out his task of pub-*");
$intro_title                  = _("Basic Proofreading Quiz, extra practice");
$initial_instructions         = $initial_instructions__P;




$tests[] = array("type" => "expectedtext", "searchtext" => array("pub-*"), "case_sensitive" => TRUE, "error" => "P_eophyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" --", "-- "), "case_sensitive" => TRUE, "error" => "P_spacedemdash");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("---"), "case_sensitive" => TRUE, "error" => "P_em_long");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("--\n"), "case_sensitive" => TRUE, "error" => "P_eoldash");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("\n--"), "case_sensitive" => TRUE, "error" => "P_soldash");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("\nspirit"), "case_sensitive" => TRUE, "error" => "dashlower");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("-\n"), "case_sensitive" => TRUE, "error" => "P_eolhyphen");
$tests[] = array("type" => "expectedtext", "searchtext" => array("handing"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("\nhanding"), "case_sensitive" => TRUE, "error" => "P_hyphenlower");
$tests[] = array("type" => "expectedlinebreaks", "number" => 2, "starttext" => "him.", "stoptext" => "Charles now", "case_sensitive" => TRUE, "errorhigh" => "toomanylb", "errorlow" => "P_para");
$tests[] = array("type" => "longline", "lengthlimit" => 60, "error" => "P_longline");


$messages["dashlower"] = array(
    "message_title" => _("Em-dash placement"),
    "message_body" => _("When an em-dash appears at the end of a line, leave it there, and move the first word of the next line <i>up</i> to that line. Put the line break back in after the word that you moved."),
    "guideline" => "eol_hyphen",
);
$messages["toomanylb"] = array(
    "message_title" => _("Too many linebreaks inserted"),
    "message_body" => _("Paragraphs should be divided by exactly one blank line."),
    "guideline" => "para_space",
);


