<?php


$browser_title                = _("Basic Proofreading Quiz");
$initial_text                 = "repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\nDEAREST mother, this is indeed\nlike some of\nOakwood's happy hours, \" exclaimed\nEmmeline , that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, arid raised her laughing eyes";
$solutions                    = array("repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\n\"DEAREST mother, this is indeed\nlike some of\nOakwood's happy hours,\" exclaimed\nEmmeline, that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, and raised her laughing eyes");
$intro_title                  = _("Basic Proofreading Quiz, extra practice");
$initial_instructions         = $initial_instructions__P;


$tests[] = array("type" => "forbiddentext", "searchtext" => array("arid"), "case_sensitive" => FALSE, "error" => "arid");
$tests[] = array("type" => "expectedtext", "searchtext" => array("\"DE"), "case_sensitive" => FALSE, "error" => "missingquote");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(", \""), "case_sensitive" => FALSE, "error" => "P_closequotespace");
$tests[] = array("type" => "forbiddentext", "searchtext" => array(" ,"), "case_sensitive" => FALSE, "error" => "P_commaspace");
$tests[] = array("type" => "forbiddentext", "searchtext" => array("Dearest"), "case_sensitive" => true, "error" => "P_decappedchap");
$tests[] = array("type" => "expectedtext", "searchtext" => array("agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\n\""), "case_sensitive" => FALSE, "error" => "spacedchap");
$tests[] = array("type" => "expectedtext", "searchtext" => array("indeed\n"), "case_sensitive" => true, "error" => "P_lbchange");
$tests[] = array("type" => "expectedtext", "searchtext" => array("of\n"), "case_sensitive" => true, "error" => "P_lbchange");

$messages["arid"] = array(
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': '<kbd>ri</kbd>' in the text where there is an '<kbd>n</kbd>' in the image."),
    "hints" => array(
        array(
            "hint_title" => _("Scanno: hints"),
            "hint_body" => _("Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually."),
        ),
        array(
            "hint_title" => _("Scanno: hints"),
            "hint_body" => _("You are looking for an occurrence of 'ri' that is wrong. There is only one word with 'ri' in the text. Once you've found it you will immediately know it is wrong."),
        ),
        array(
            "hint_title" => _("Scanno: hints"),
            "hint_body" => _("If you can't find any word with 'ri', consider copying the text into an editor and searching for 'ri'. You'll get a result, guaranteed!"),
        ),
        array(
            "hint_title" => _("Scanno: hints"),
            "hint_body" => _("No, we won't give away the solution, after all this is a quiz!"),
        )
    )
);
$messages["missingquote"] = array(
    "message_title" => _("Double quote missing"),
    "message_body" => _("Since from the context one can see there should be a double quote at the start of the chapter, and this is only missing for typesetting reasons, we insert one there."),
    "guideline" => "chap_head"
);
$messages["spacedchap"] = array(
    "message_title" => _("Chapter heading spaced"),
    "message_body" => _("Leave the formatting of the chapter heading to the formatting rounds, and leave a single blank line before and after each part of the heading."),
    "guideline" => "chap_head"
);
$messages["numberinheader"] = array(
    "message_title" => _("Number of blank lines within chapter heading section incorrect"),
    "message_body" => _("There should be 1 blank line between different parts of the chapter heading."),
);


