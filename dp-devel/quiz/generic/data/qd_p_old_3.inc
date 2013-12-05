<?php
include_once('quiz_defaults.inc'); // $blackletter_url $old_texts_url $utf8_site

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_old_3","pass");
}

$browser_title                = _("Old Texts Proofreading Quiz");
$intro_title                  = _("Old Texts Proofreading Quiz, page 3");
$initial_instructions         = _("Try to correct the text on the bottom left, so it matches the text in the image above following the Proofreading Guidelines and the \"Project Comments\" for this quiz. When done click 'check'.");
$constant_message             = "\n" .
                                    "<h2>" . _("Project Comments") . "</h2>\n" .
                                    "<p>" . sprintf(_("Proofread the nasal abbreviations, which can look like tildes or macrons, as macrons.  If you have trouble with the blackletter font, see <a href='%s' target='_blank'>Proofing Blackletter</a>."), $blackletter_url) . "</p>";
$ocr_text                     = "for coolcnes in Summer. )\n\nCucnye. An admitable byrde in ljifpa=\nniola (no bigger then a thobe) hauing in\nher forehead two burnynge Lampes, and\ntwo vnderneath her itinges (which are\ndouble, a greater and a smaller payre} so\nshining in the njght (wherin onely she\nflyeth) that fiue or fixe of the tyed toge-\nther_{j} gyue as moch light as a torch. Further";
if(!$utf8_site) {
    $solutions                = array("for coolenes in Summer.\n\nCucuye. An admirable byrde in Hispaniola\n(no bigger then a th[=o]be) hauing in\nher forehead two burnynge Lampes, and\ntwo vnderneath her winges (which are\ndouble, a greater and a smaller payre) so\nshining in the nyght (wherin onely she\nflyeth) that fiue or sixe of th[=e] tyed together,\ngyue as moch light as a torch. Fur-*");
}
else {
    $solutions                = array("for coolenes in Summer.\n\nCucuye. An admirable byrde in Hispaniola\n(no bigger then a thōbe) hauing in\nher forehead two burnynge Lampes, and\ntwo vnderneath her winges (which are\ndouble, a greater and a smaller payre) so\nshining in the nyght (wherin onely she\nflyeth) that fiue or sixe of thē tyed together,\ngyue as moch light as a torch. Fur-*");
}
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("If you come across any unusual symbols or characters when proofreading, please bring it up in the project discussion.  This will alert the project manager and post-processor to the situation, and help to improve consistency among the various proofreaders during the rounds.") . "</p>";


// error messages

$messages["longsbracket"] = array(
    "message_text" => "<h2>" . _("Long s") . "</h2>\n" .
        "<p>" . _("Unless the project comments instruct otherwise, please proofread the long s as plain s without any brackets.") . "</p>\n" .
        "<p>" . sprintf(_("See the <a href='%s#Long_s' target='_blank'>Long s</a> section of the wiki page for details."), $old_texts_url) . "</p>",
    "hints" => array(),
);
$messages["blhyphen"] = array(
    "message_text" => "<h2>" . _("End-of-line Hyphenation") . "</h2>\n" .
        "<p>" . _("In blackletter fonts the hyphen often looks like an equals sign (=).  Treat this like a normal hyphen, rejoining the word as necessary.") . "</p>",
    "hints" => array(),
);
$messages["projcomm"] = array(
    "message_text" => "<h2>" . _("Macron") . "</h2>\n" .
        "<p>" . _("According to the \"project comments\" for this text, you should proofread the nasal abbreviations as macrons.") . "</p>",
    "hints" => array(),
    "guideline" => "d_chars",
);
$messages["flyspeck"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>)</tt>' in the text where there is a stray mark in the image.") . "</p>",
    "hints" => array(),
);
$messages["coolcnes"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>c</tt>' in the text where there is an '<tt>e</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["Cucnye"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: an '<tt>n</tt>' in the text where there is a '<tt>u</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["admitable"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>t</tt>' in the text where there is an '<tt>r</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["Hscanno"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: '<tt>lj</tt>' in the text where there is a capital '<tt>H</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["itinges"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: '<tt>it</tt>' in the text where there is a '<tt>w</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["parenscanno"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a curly bracket '<tt>}</tt>' in the text where there is a closing parenthesis '<tt>)</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["njght"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a '<tt>j</tt>' in the text where there is a '<tt>y</tt>' in the image.") . "</p>",
    "hints" => array(),
);
$messages["commascanno"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a scanno: a subscripted letter in the text where there is a comma (<tt>,</tt>) in the image.") . "</p>",
    "hints" => array(),
);
$messages["bltext"] = array(
    "message_text" => "<h2>" . _("Blackletter font") . "</h2>\n" .
        "<p>" . sprintf(_("There seems to be a problem in the blackletter text. If you are having trouble reading it, see <a href='%s' target='_blank'>this page</a> for help."), $blackletter_url) . "</p>",
    "hints" => array(),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ifpa", "fmaller", "fhining", "fhe", "fixe"), "case_sensitive" => TRUE, "error" => "P_longs");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[f]", "[s]"), "case_sensitive" => TRUE, "error" => "P_longsbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("-\n"), "case_sensitive" => TRUE, "error" => "P_eolhyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("tyed\n"), "case_sensitive" => TRUE, "error" => "P_hyphenlower");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("toge-", "ispa-"), "case_sensitive" => TRUE, "error" => "P_hyphenleft");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("a="), "case_sensitive" => TRUE, "error" => "blhyphen");
$tests[] = array("type" => "longline", "lengthlimit" => 60, "error" => "P_longline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Further"), "case_sensitive" => TRUE, "error" => "P_catchword");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Fur-*"), "case_sensitive" => TRUE, "error" => "P_eophyphen");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("having", "five", "gyve"), "case_sensitive" => TRUE, "error" => "P_uvchange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("underneath"), "case_sensitive" => TRUE, "error" => "P_italu");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("thobe", "thombe", "~", "õ", "ẽ", "the tyed", "them"), "case_sensitive" => TRUE, "error" => "projcomm");
if(!$utf8_site) {
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[o=]", "[e=]", "[=x]", "[-o]", "[-e]"), "case_sensitive" => TRUE, "error" => "P_macron");
}
else {
    $tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[=o]", "[=e]"), "case_sensitive" => TRUE, "error" => "P_macron");
}
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("coolcnes"), "case_sensitive" => TRUE, "error" => "coolcnes");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("er. )", "er.)"), "case_sensitive" => TRUE, "error" => "flyspeck");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("cny"), "case_sensitive" => TRUE, "error" => "Cucnye");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("admitable"), "case_sensitive" => TRUE, "error" => "admitable");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("lj"), "case_sensitive" => TRUE, "error" => "Hscanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("itinges"), "case_sensitive" => TRUE, "error" => "itinges");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("re}"), "case_sensitive" => TRUE, "error" => "parenscanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("njght"), "case_sensitive" => TRUE, "error" => "njght");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("_{j}"), "case_sensitive" => TRUE, "error" => "commascanno");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Cucuye"), "case_sensitive" => TRUE, "error" => "bltext");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("Hispaniola"), "case_sensitive" => TRUE, "error" => "bltext");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");

// vim: sw=4 ts=4 expandtab
