<?php

$browser_title = _("Basic Proofreading Quiz, Extra Practice");
$initial_text = "repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\nDEAREST mother, this is indeed\nlike some of\nOakwood's happy hours, \" exclaimed\nEmmeline , that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, arid raised her laughing eyes";
$solutions = ["repentant and remorseful agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\n\"DEAREST mother, this is indeed\nlike some of\nOakwood's happy hours,\" exclaimed\nEmmeline, that same evening, as with\nchildish glee she had placed herself at her\nmother's feet, and raised her laughing eyes"];
$intro_title = sprintf(_("Basic Proofreading Quiz, Extra Practice, Page %d"), 2);
$initial_instructions = $initial_instructions__P;

$tests[] = ["type" => "forbiddentext", "searchtext" => ["arid"], "case_sensitive" => false, "error" => "arid"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["\"DE"], "case_sensitive" => false, "error" => "missingquote"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [", \""], "case_sensitive" => false, "error" => "P_closequotespace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => [" ,"], "case_sensitive" => false, "error" => "P_commaspace"];
$tests[] = ["type" => "forbiddentext", "searchtext" => ["Dearest"], "case_sensitive" => true, "error" => "P_decappedchap"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["agony.\n\nCHAPTER VII.\n\nAt Oakwood\n\n\""], "case_sensitive" => false, "error" => "spacedchap"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["indeed\n"], "case_sensitive" => true, "error" => "P_lbchange"];
$tests[] = ["type" => "expectedtext", "searchtext" => ["of\n"], "case_sensitive" => true, "error" => "P_lbchange"];

$messages["arid"] = [
    "message_title" => _("Scanno"),
    "message_body" => _("There is still a 'scanno': '<kbd>ri</kbd>' in the text where there is an '<kbd>n</kbd>' in the image."),
    "hints" => [
        [
            "hint_title" => _("Scanno: hints"),
            "hint_body" => _("Read the text again, slowly and carefully. Try not to look at the words, look at the letters individually."),
        ],
        [
            "hint_title" => _("Scanno: hints"),
            "hint_body" => _("You are looking for an occurrence of 'ri' that is wrong. There is only one word with 'ri' in the text. Once you've found it you will immediately know it is wrong."),
        ],
        [
            "hint_title" => _("Scanno: hints"),
            "hint_body" => _("If you can't find any word with 'ri', consider copying the text into an editor and searching for 'ri'. You'll get a result, guaranteed!"),
        ],
        [
            "hint_title" => _("Scanno: hints"),
            "hint_body" => _("No, we won't give away the solution, after all this is a quiz!"),
        ],
    ],
];
$messages["missingquote"] = [
    "message_title" => _("Double quote missing"),
    "message_body" => _("Since from the context one can see there should be a double quote at the start of the chapter, and this is only missing for typesetting reasons, we insert one there."),
    "guideline" => "chap_head",
];
$messages["spacedchap"] = [
    "message_title" => _("Chapter heading spaced"),
    "message_body" => _("Leave the formatting of the chapter heading to the formatting rounds, and leave a single blank line before and after each part of the heading."),
    "guideline" => "chap_head",
];
$messages["numberinheader"] = [
    "message_title" => _("Number of blank lines within chapter heading section incorrect"),
    "message_body" => _("There should be 1 blank line between different parts of the chapter heading."),
];
