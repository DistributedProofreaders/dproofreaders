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

function lang_code($shortcode) {
	global $locales;

	$l=&$shortcode;
	if($locales[$l]||$locales[$l=substr($l,0,5)]||$locales[$l=substr($l,0,2)]) return $locales[$l];
	return FALSE;
}

function phpbb_lang($langcode) {
	global $phpbblangs;
	
	return $phpbblangs[$langcode];
}

function aspell_dict($engname) {
	$a=array(
		"English"=>"-d en",
//		"Finnish"=>"-d fi",
		"French"=>"-d fr",
		"German"=>"-d de",
		"Italian"=>"-d it",
		"Polish"=>"-d pl",
		"Portuguese"=>"-d pt",
		"Romanian"=>"-d ro",
		"Swedish"=>"-d sv"
//		"Serbian"=>"-d sr"
	);

	$s=preg_split("/ with /",$engname);
	
	return $a[$s[0]]." ".$a[$s[1]];
}
