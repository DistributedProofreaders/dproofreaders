<?
// This include file holds functions needed for various language issues.
// It is meant to be designed as if it is a class - at first I wanted to make
// a class, but as it needs no instantiation and PHP does not support static 
// variables, I am doing it this way and counting on programmers' discipline. 

// Most important of all, DO NOT USE THE ARRAYS DIRECTLY! Their design WILL
// change! Use the functions, or write your own functions if none of the
// existing ones is appropriate.

$locales=array(
        "en"=>"en_EN",
        "es"=>"es_ES.utf8",
        "fi"=>"fi_FI.utf8",
        "nl"=>"nl_NL.utf8",
        "pt"=>"pt_PT.utf8",
        "sr"=>"sr_YU.utf8@cyrillic",
        "sv"=>"sv_SE.utf8"
);

$phpbblangs=Array(
	"en_EN"=>"english",
	"es_ES.utf8"=>"spanish",
	"nl_NL.utf8"=>"dutch",
	"pt_PT.utf8"=>"portuguese",
	"sr_YU.utf8@cyrillic"=>"serbian_cyrillic",
	"fi_FI.utf8"=>"finnish"
);

include_once($relPath.'iso_639_list.inc');

function installed_langs() {
	return array(
	        "en_EN",
        	"es_ES.utf8",
        	"fi_FI.utf8",
        	"nl_NL.utf8",
        	"pt_PT.utf8",
        	"sr_YU.utf8@cyrillic",
        	"sv_SE.utf8",
	);
}

function uninstalled_langs()
{
	return array(
		"de_DE.utf8",
		"it_IT.utf8",
	);
}

function lang_code($shortcode) {
	global $locales;

	$l=&$shortcode;
	if($locales[$l]||$locales[$l=substr($l,0,5)]||$locales[$l=substr($l,0,2)]) return $locales[$l];
	return FALSE;
}

function lang_name($langcode)
{
	$a=array(
		"da_DK.utf8"=>"Dansk",
		"de_DE.utf8"=>"Deutsch",
		"en_EN"=>"English",
		"es_ES.utf8"=>"Español",
		"fr_FR.utf8"=>"Français",
		"it_IT.utf8"=>"Italiano",
		"nl_NL.utf8"=>"Nederlands",
		"no_NO.utf8"=>"Norsk",
		"pl_PL.utf8"=>"Polski",
		"pt_PT.utf8"=>"Português",
		"sr_YU.utf8@cyrillic"=>"Српски",
		"fi_FI.utf8"=>"Suomi",
		"sv_SE.utf8"=>"Svenska"	
	);
	return $a[$langcode];
}

function eng_name($langcode)
{
	global $iso_639;
	return $iso_639[substr($langcode,0,2)];
}

function phpbb_lang($langcode) {
	global $phpbblangs;
	
	return $phpbblangs[$langcode];
}
