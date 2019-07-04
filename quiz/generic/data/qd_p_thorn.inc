<?php
include_once('quiz_defaults.inc'); // $thorn_url


$browser_title                = _("Thorn Proofreading Quiz");
$intro_title                  = _("Thorn Proofreading Quiz");
$initial_instructions         = $initial_instructions__P;
$initial_text                 = "them to beware of green meat, as it weakens\nthe belly:\n\nbeware of saladis, greno metis, &amp; of frutes rawe\nfor bey make many a man liaue a feble mawe.\n]>erfore, of suche fresch lustas set not an hawe,\nFor suche wantoun appetites ai not worth a\n\nstrawe.";
$solutions                    = array("them to beware of green meat, as it weakens\nthe belly:\n\nbeware of saladis, grene metis, & of frutes rawe\nfor þey make many a man̄ haue a feble mawe.\nÞerfore, of suche fresch lustes set not an hawe,\nFor suche wantoun appetites ar not worth a\nstrawe.");


// error messages

$messages["greno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>o</tt>' in the text where there is an '<tt>e</tt>' in the image."),
);
$messages["bey"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: a '<tt>b</tt>' in the text where there is a '<tt>&thorn;</tt>' in the image."),
);
$messages["liaue"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<tt>li</tt>' in the text where there is an '<tt>h</tt>' in the image."),
);
$messages["thornscanno"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: '<tt>]></tt>' in the text where there is a '<tt>&THORN;</tt>' in the image."),
);
$messages["lustas"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>a</tt>' in the text where there is an '<tt>e</tt>' in the image."),
);
$messages["ai"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno: an '<tt>i</tt>' in the text where there is an '<tt>r</tt>' in the image."),
);
$messages["macronmissing"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a scanno in the text: a macron (horizontal line) over an '<tt>n</tt>' was omitted by the OCR software."),
);
$messages["macron"] = array(
    "message_title" => _("Macron"),
    "message_body" => _("Following the proofreading guidelines, please use the appropriate Unicode character for a letter with a macron (straight line) over it.  For instance, proofread an '<tt>a</tt>' with a macron over it as <tt>&#257;</tt>.  If the specific character does not exist in Unicode, then enter the base letter followed by the Unicode \"combining macron\" character in order to put the macron on the letter."),
    "guideline" => "d_chars",
);
$messages["macron2"] = array(
    "message_title" => _("Macron"),
    "message_body" => _("Since the letter 'n' with a macron (straight line) over it does not exist in Unicode, you should proofread it by first entering the <tt>n</tt> and then the combining macron character."),
    "guideline" => "d_chars",
);
$messages["thorn"] = array(
    "message_title" => _("Thorn incorrectly proofread"),
    "message_body" => _("The capital and lower case thorns (&THORN; and &thorn;) often appear in Old and Middle English texts.  These characters are available in the \"+\" drop-down menu at the bottom of the screen."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>Thorn</a> wiki page for details."), $thorn_url),
);
$messages["thorncase"] = array(
    "message_title" => _("Capital/Lower Case Thorn"),
    "message_body" => _("In the books that we proofread generally the capital thorn (<tt>&THORN;</tt>) has a much larger loop.  The lower case thorn (<tt>&thorn;</tt>) has a smaller loop and often sticks down farther below the line."),
    "wiki_ref" => sprintf(_("See the <a href='%s' target='_blank'>Thorn</a> wiki page for details."), $thorn_url),
);
$messages["blankline2"] = array(
    "message_title" => _("Blank Lines"),
    "message_body" => _("The OCR software incorrectly inserted a blank line within the poem.  Please remove it."),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("greno"), "case_sensitive" => TRUE, "error" => "greno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("bey"), "case_sensitive" => TRUE, "error" => "bey");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("liaue"), "case_sensitive" => TRUE, "error" => "liaue");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("]>"), "case_sensitive" => TRUE, "error" => "thornscanno");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("lustas"), "case_sensitive" => TRUE, "error" => "lustas");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ai"), "case_sensitive" => TRUE, "error" => "ai");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("man "), "case_sensitive" => TRUE, "error" => "macronmissing");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[=n]", "[=x]", "ñ"), "case_sensitive" => TRUE, "error" => "macron");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("man̄"), "case_sensitive" => TRUE, "error" => "macron2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("pey", "per", "Der", "ber"), "case_sensitive" => FALSE, "error" => "thorn");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("Þey", "þer"), "case_sensitive" => TRUE, "error" => "thorncase");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("belly:\nbe"), "case_sensitive" => TRUE, "error" => "P_poetryblanklines");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n\nstrawe"), "case_sensitive" => TRUE, "error" => "blankline2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("a strawe"), "case_sensitive" => TRUE, "error" => "P_joinline");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n "), "case_sensitive" => TRUE, "error" => "P_indent");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("/*", "*/"), "case_sensitive" => TRUE, "error" => "P_poemmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");

// vim: sw=4 ts=4 expandtab
