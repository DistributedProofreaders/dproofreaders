<?
// This include file holds functions needed for various language issues.
// It is meant to be designed as if it is a class - at first I wanted to make
// a class, but as it needs no instantiation and PHP does not support static 
// variables, I am doing it this way and counting on programmers' discipline. 

// Most important of all, DO NOT USE THE ARRAYS DIRECTLY! Their design WILL
// change! Use the functions, or write your own functions if neither of the
// existing ones is appropriate.

include_once($relPath.'iso_639_list.inc');
include_once($relPath.'iso_639_loc_list.inc');
include_once($relPath.'lang_data.inc');

function installed_langs() {
	return $GLOBALS['inst_lang_data'];
}

// This function returns array of all languages that are not installed on the
// site, but whose installation is planned. Languages with "strange" codes
// (hy, br, cy, ...) are those for which there is no gettext() support.
function uninstalled_langs()
{
	return $GLOBALS['uninst_lang_data'];
}

function lang_code($shortcode) {
	global $locales;

	$l=&$shortcode;
	if($locales[$l]||$locales[$l=substr($l,0,5)]||$locales[$l=substr($l,0,2)]) return $locales[$l];
	return FALSE;
}

function short_lang_code($langcode=FALSE)
{
	if($langcode===FALSE) $langcode=$GLOBALS['locale'];

	return $langcode?substr($langcode,0,2):"en";
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
	global $iso_639;

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

function lang_name($langcode)
{
	global $iso_639_loc,$lang_name_data;
	if(isset($lang_name_data[$langcode])) return $lang_name_data[$langcode];
	return $iso_639_loc[substr($langcode,0,2)];
}

function eng_name($langcode)
{
	global $iso_639,$eng_name_data;
	if(isset($eng_name_data[$langcode])) return $eng_name_data[$langcode];
	return $iso_639[substr($langcode,0,2)];
}

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

function lang_direction($langcode=FALSE)
{
	if($langcode===FALSE) $langcode=$GLOBALS['locale'];

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

	if($langcode===FALSE) $langcode=$GLOBALS['locale'];

	$a=substr($langcode,0,2);

	if(in_array($a,$lang_dir_data)) return $a."/";
	return "";
}

// This function returns forum of a particular language. If there is no such
// forum, it might return a team thread or whatever is appropriate (or nothing,
// of course).
function lang_forum($langcode=FALSE)
{
	global $lang_forum_data;

	if($langcode===FALSE) $langcode=$GLOBALS['locale'];

	if (isset($lang_forum_data[$langcode])) { return $lang_forum_data[$langcode]; } else { return false; }
}

function phpbb_lang($langcode) {
	global $phpbblangs;
	
	return $phpbblangs[$langcode];
}

function lang_html_header($langcode=FALSE)
{
	return " lang='".short_lang_code($langcode).((lang_direction($langcode)=="RTL")?"' dir='RTL'":"'");
}

?>
