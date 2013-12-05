<?php
include_once($relPath."Quiz.inc");

function quizsolved()
{
  global $pguser;
  if (isset($pguser))
      record_quiz_attempt($pguser,"p_basicx_2","pass");
}



$browser_title                = _("Basic Proofreading Quiz");
$ocr_text                     = "repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\nDEAREST mother, this is indeed\nlike some of\nOakwood's happy hours, \" exclaimed\nEmmeline , that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, arid raised her laughing eyes";
$solutions                    = array("repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\n\"DEAREST mother, this is indeed\nlike some of\nOakwood's happy hours,\" exclaimed\nEmmeline, that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, and raised her laughing eyes");
$intro_title                  = _("Basic Proofreading Quiz, extra practice");
$initial_instructions         = $initial_instructions__P;
$solved_message               = "<h2>" . _("Quiz successfully solved") . "</h2>\n" . _("Congratulations, no errors found!");


$tests[] = array("type" => "forbiddentext", "searchtext" => array("arid"), "case_sensitive" => FALSE, "error" => "arid");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\"DE"), "case_sensitive" => FALSE, "error" => "missingquote");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(", \""), "case_sensitive" => FALSE, "error" => "P_closequotespace");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" ,"), "case_sensitive" => FALSE, "error" => "P_commaspace");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("Dearest"), "case_sensitive" => true, "error" => "P_decappedchap");
$tests[] = array("type" => "expectedtext", "searchtext" => array("agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\n\""), "case_sensitive" => FALSE, "error" => "spacedchap");
$tests[] = array("type" => "expectedtext", "searchtext" => array("indeed\n"), "case_sensitive" => true, "error" => "P_lbchange");
$tests[] = array("type" => "expectedtext", "searchtext" => array("of\n"), "case_sensitive" => true, "error" => "P_lbchange");

$messages["arid"] = array(
    "message_text" => "<h2>" . _("Scanno") . "</h2>\n" .
        "<p>" . _("There is still a 'scanno': '<tt>ri</tt>' in the text where there is an '<tt>n</tt>' in the image.") . "</p>",
    "hints" => array(
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>\n" .
                "<p>" . _("Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually.") . "</p>"
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>\n" .
                "<p>" . _("You are looking for an occurance of 'ri' that is wrong. There is only only words with 'ri' in the text. Once you've found it you will immediately know it is wrong.") . "</p>"
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>\n" .
                "<p>" . _("If you can't find any word with 'ri', consider copying the text into an editor and searching for 'ri'. You'll get a result, guaranteed!") . "</p>"
        ),
        array(
            "hint_text" => "<h2>" . _("Scanno: hints") . "</h2>\n" .
                "<p>" . _("No, we won't give away the solution, after all this is a quiz!") . "</p>"
        )
    )
);
$messages["missingquote"] = array(
    "message_text" => "<h2>" . _("Double quote missing") . "</h2>\n" .
        "<p>" . _("Since from the context one can see there should be a double quote at the start of the chapter, and this is only missing for typesetting reasons, we insert one there.") . "</p>",
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["spacedchap"] = array(
    "message_text" => "<h2>" . _("Chapter heading spaced") . "</h2>\n" .
        "<p>" . _("Leave the formatting of the chapter heading to the formatting rounds, and leave a single blank line before and after each part of the heading.") . "</p>",
    "hints" => array(),
    "guideline" => "chap_head"
);
$messages["numberinheader"] = array(
    "message_text" => "<h2>" . _("Number of blank lines within chapter heading section incorrect") . "</h2>\n" .
        "<p>" . _("There should be 1 blank line between different parts of the chapter heading.") . "</p>",
    "hints" => array()
);


// vim: sw=4 ts=4 expandtab
