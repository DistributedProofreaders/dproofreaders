<?php

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_greek_1","pass");
}

$browser_title                = _("Greek Transliteration Quiz");
$intro_title                  = _("Greek Transliteration Quiz, page 1");
$initial_instructions         = _("Use the Greek Transliterator (link below) to proofread the Greek text.  There is no need to proofread the English text, as it is already correct. When done click 'check'.");
$ocr_text                     = "as panis siccus, dry bread; or, (if\nthe learned reader thinks it will\ntaste better in Greek,) aproc &sect;rjpoe";
$solutions                    = array("as panis siccus, dry bread; or, (if\nthe learned reader thinks it will\ntaste better in Greek,) [Greek: artos xêros]");
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" .
                                    "<p>" . _("Congratulations, no errors found!") . "</p>\n" .
                                    "<h3>" . _("Handy Tip") . "</h3>\n" .
                                    "<p>" . _("If you come across some Greek while proofreading and you don't feel confident in your transliteration, feel free to post in the project discussion and mention the page number.  Other site volunteers can check your transliteration and let you know if you need to make any changes.") . "</p>";


// error messages

$messages["x_z"] = array(
    "message_text" => "<h2>" . _("Commonly confused letters") . "</h2>\n" .
        "<p>" . _("There is a '<tt>z</tt>' in your transliteration that should be an '<tt>x</tt>'.  The Greek letter zeta (transliterated '<tt>z</tt>') looks like &zeta;, while the Greek letter xi (transliterated '<tt>x</tt>') looks like &xi;.") . "</p>",
    "hints" => array(),
);


// error checks

$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("<i>", "</i>"), "case_sensitive" => FALSE, "error" => "P_italmarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("&#945;", "&#959;", "&#961;"), "case_sensitive" => TRUE, "error" => "G_unicode");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("aproc", "§rjpoe"), "case_sensitive" => TRUE, "error" => "G_nochange");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("greek"), "case_sensitive" => TRUE, "error" => "G_lcgreek");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek:"), "case_sensitive" => TRUE, "error" => "G_nomarkup");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("[Greek:\n"), "case_sensitive" => TRUE, "error" => "G_markupline");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("[Greek: "), "case_sensitive" => TRUE, "error" => "G_markupspace");
$tests[] = array("type" => "expectedtext", "searchtext" =>  array("]"), "case_sensitive" => TRUE, "error" => "G_endbracket");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("\n]"), "case_sensitive" => TRUE, "error" => "G_markupline2");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("zêr"), "case_sensitive" => TRUE, "error" => "x_z");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("ero"), "case_sensitive" => TRUE, "error" => "G_missingcirc");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("apto", "pos"), "case_sensitive" => TRUE, "error" => "G_r_p");
$tests[] = array("type" => "forbiddentext", "searchtext" =>  array("toc"), "case_sensitive" => TRUE, "error" => "G_s_c");

// vim: sw=4 ts=4 expandtab
