<?php
// This include file holds functions needed for various language issues.
// It is meant to be designed as if it is a class - at first I wanted to make
// a class, but as it needs no instantiation and PHP does not support static 
// variables, I am doing it this way and counting on programmers' discipline. 

// Most important of all, DO NOT USE THE ARRAYS DIRECTLY! Their design WILL
// change! Use the functions, or write your own functions if neither of the
// existing ones is appropriate.

include_once($relPath.'lang_data.inc');

// Return a list of installed languages (as defined in lang_data.inc).
function installed_langs()
{
    return $GLOBALS['inst_lang_data'];
}

// This function returns array of all languages that are not installed on the
// site, but whose installation is planned.
// See maybe_show_language_selector() in theme.inc for details on how this is
// used.
function uninstalled_langs()
{
    return $GLOBALS['uninst_lang_data'];
}

function lang_code($language_code) {
    global $locales, $inst_lang_data;

    // see if there's a preferred variation
    $shortcode = $language_code;
    if(isset($locales[$shortcode]) ||
       isset($locales[$shortcode=substr($shortcode,0,5)]) ||
       isset($locales[$shortcode=substr($shortcode,0,2)]))
        return $locales[$shortcode];

    // if not, return one of the ones that's installed
    if(in_array($language_code, $inst_lang_data))
        return $language_code;

    return FALSE;
}

// Return the 2-letter short code for the specified locale specifier.
// If no locale is passed in, the locale of the current user is used.
function short_lang_code($langcode=FALSE)
{
    if($langcode===FALSE)
        $langcode=$GLOBALS['locale'];

    return substr($langcode, 0, 2);
}

// Returns ISO 639 code(s) for language(s) of a project.
// $which could be "primary", "secondary" or "all":
// - "primary" returns the code of primary language
// - "secondary" returns the code of secondary language, or NULL if the
//   secondary language doesn't exist
// - "all" returns array with the code(s) of both language(s); if the secondary
//   language doesn't exist, the array only has one element
// If a language doesn't have ISO 639 code, NULL is returned; this might change
// Some code copied from tools/proofers/spellcheck_text.inc
function proj_lang_code($proj_lang,$which="all")
{
    global $relPath;

    static $iso_639 = NULL;
    if(!$iso_639) include($relPath.'iso_639_list.inc');

    $languages = preg_split('/ with /', $proj_lang );

    switch($which) {
    case "primary":
        return array_search( $languages[0], $iso_639 );
        break;
    case "secondary":
        return array_search( $languages[1], $iso_639 );
        break;
    case "all":
        $res=array();
        foreach($languages as $v)
            array_push( $res, array_search( $v, $iso_639 ) );

        return $res;
        break;
    }
}

// Given a 2-letter language code, return the language name in that
// language.
function lang_name($langcode)
{
    global $relPath, $lang_name_data;
    static $iso_639_loc = NULL;
    if(!$iso_639_loc) include($relPath.'iso_639_loc_list.inc');

    if(isset($lang_name_data[$langcode]))
        return $lang_name_data[$langcode];

    return $iso_639_loc[substr($langcode,0,2)];
}

// Given a 2-letter language code, return the language name in English.
function eng_name($langcode)
{
    global $relPath, $eng_name_data;

    static $iso_639 = NULL;
    if(!$iso_639) include($relPath.'iso_639_list.inc');

    if(isset($eng_name_data[$langcode]))
        return $eng_name_data[$langcode];

    return $iso_639[substr($langcode,0,2)];
}

// Given a 2-letter language code, return a name for that language both
// in English and in the native language.
function bilingual_name($langcode)
{
    $a=eng_name($langcode);
    $b=lang_name($langcode);

    if($a==$b) {
        return $a;
    } else if(!$a) {
        return $b;
    } else {
        return trim("$b ($a)");
    }
}

// Return a direction for a given language. Returns either:
// LTR for left-to-right languages
// RTL for right-to-left languages
function lang_direction($langcode=FALSE)
{
    if($langcode===FALSE)
        $langcode=$GLOBALS['locale'];

    switch(substr($langcode,0,2)) {
        case "ar":
        case "fa":
        case "he":
        case "ur": //surely more
            return "RTL";
            break;
        default:
            return "LTR";
            break;
    }
}

function lang_dir($langcode=FALSE)
{
    global $lang_dir_data;

    if($langcode===FALSE)
        $langcode=$GLOBALS['locale'];

    $a=substr($langcode,0,2);

    if(in_array($a,$lang_dir_data))
        return $a."/";

    return "";
}

// Returns an associative array of those languages with a dictionary that is
// installed on the system
//   $array[$langcode]=$language;
function get_languages_with_dictionaries() {
    global $relPath, $aspell_prefix;

    static $iso_639 = NULL;
    if(!$iso_639) include($relPath.'iso_639_list.inc');

    $returnArray = array();

    foreach($iso_639 as $langcode => $language) {
        $dict_file = "$aspell_prefix/lib/aspell/$langcode.multi";
        if( is_file( $dict_file ) ) {
            $returnArray[$langcode] = $language;
        }
    }

    return $returnArray;
}

// Return all installed system locales
function get_installed_system_locales()
{
    static $system_locales = array();
    if($system_locales)
        return $system_locales;

    exec("locale -a", $system_locales);

    return $system_locales;
}

// This function returns forum of a particular language. If there is no such
// forum, it might return a team thread or whatever is appropriate (or nothing,
// of course).
function lang_forum($langcode=FALSE)
{
    global $lang_forum_data;

    if($langcode===FALSE)
        $langcode=$GLOBALS['locale'];

    if (isset($lang_forum_data[$langcode]))
        return $lang_forum_data[$langcode];
    else
        return false;
}

function lang_html_header($langcode=FALSE)
{
    $string=" lang='" . short_lang_code($langcode) . "'";
    if(lang_direction($langcode) == "RTL")
        $string.=" dir='RTL'";
    return $string;
}

// vim: sw=4 ts=4 expandtab
