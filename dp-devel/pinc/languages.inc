<?
// This include file holds functions needed for various language issues.
// It is meant to be designed as if it is a class - at first I wanted to make
// a class, but as it needs no instantiation and PHP does not support static 
// variables, I am doing it this way and counting on programmers' discipline. 

// Most important of all, DO NOT USE THE ARRAYS DIRECTLY! Their design WILL
// change! Use the functions, or write your own functions if neither of the
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
		"da_DK.utf8",
		"de_DE.utf8",
		"en_EN",
		"es_ES.utf8",
		"fr_FR.utf8",
		"it_IT.utf8",
		"nl_NL.utf8",
		"no_NO.utf8",
		"pl_PL.utf8",
		"pt_PT.utf8",
		"sr_YU.utf8@cyrillic",
		"fi_FI.utf8",
		"sv_SE.utf8",
	);
}

// This function returns array of all languages that are not installed on the
// site, but whose installation is planned. Languages with "strange" codes
// (hy, br, cy, ...) are those for which there is no gettext() support.
function uninstalled_langs()
{
	return array(
		"hy",
		"bg_BG.utf8",
		"be_BY.utf8",
		"br",
		"ca_ES.utf8",
		"cy",
		"cs_CZ.utf8",
		"et_EE.utf8",
		"el_GR.utf8",
		"eo",
		"eu_ES.utf8",
		"fo_FO.utf8",
		"fr_FR.utf8",
		"ga_IE.utf8",
		"gl_ES.utf8",
		"ka",
		"gla_UK",
		"he_IL.utf8",
		"hr_HR.utf8",
		"is_IS.utf8",
		"kl_GL.utf8",
		"kw_GB.utf8",
		"la",
		"lt_LT.utf8",
		"lv_LV.utf8",
		"hu_HU.utf8",
		"mk_MK.utf8",
		"mt_MT.utf8",
		"gv_GB.utf8",
		"nn_NO.utf8",
		"rom",
		"ro_RO.utf8",
		"ru_RU.utf8",
		"smi",
		"wen_u",
		"wen_l",
		"sq_AL.utf8",
		"sk_SK.utf8",
		"sl_SI.utf8",
		"slovio",
		"sr_YU.utf8",
		"chu",
		"tr_TR.utf8",
		"tzintzarian",
		"uk_UA.utf8",
		"wendish",
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
		"hy"=>"", //Armenian
                "bg_BG.utf8"=>"Български",
                "be_BY.utf8"=>"Беларускі",
                "br"=>"Brezhoneg",
                "ca_ES.utf8"=>"", //Catalan
                "cy"=>"Cymraeg",
                "cs_CZ.utf8"=>"Čeština",
                "da_DK.utf8"=>"Dansk",
                "de_DE.utf8"=>"Deutsch",
                "et_EE.utf8"=>"Eesti",
                "el_GR.utf8"=>"Ελληνικά",
                "en_EN"=>"English",
                "es_ES.utf8"=>"Español",
                "eo"=>"Esperanto",
                "eu_ES.utf8"=>"Euskara",
                "fo_FO.utf8"=>"", //Faroese
                "fr_FR.utf8"=>"Français",
                "ga_IE.utf8"=>"Gaeilge",
                "gl_ES.utf8"=>"Galego",
                "ka"=>"", //Georgian
                "gla_UK"=>"Ghàidhlig",
                "he_IL.utf8"=>"עברית",
                "hr_HR.utf8"=>"Hrvatski",
                "it_IT.utf8"=>"Italiano",
                "is_IS.utf8"=>"Íslenska",
		"kajkavian"=>"Kajkavski (Kajkavian)",
                "kl_GL.utf8"=>"Kalaallisut",
                "kw_GB.utf8"=>"", //Cornish
                "la"=>"Latina",
                "lt_LT.utf8"=>"Lietuvių",
                "lv_LV.utf8"=>"Latviešu",
                "hu_HU.utf8"=>"Magyar",
                "mk_MK.utf8"=>"Македонски",
                "mt_MT.utf8"=>"Malti",
                "gv_GB.utf8"=>"Manx",
                "nl_NL.utf8"=>"Nederlands",
                "no_NO.utf8"=>"Norsk",
                "nn_NO.utf8"=>"Nynorsk",
                "pl_PL.utf8"=>"Polski",
                "pt_PT.utf8"=>"Português",
                "rom"=>"Романи",
                "ro_RO.utf8"=>"Rom&acirc;nesc",
                "ru_RU.utf8"=>"Русский",
                "smi"=>"Sámigiella",
                "wen_u"=>"Serbščina, Horna",
                "wen_l"=>"Serbščina, Delna",
                "sq_AL.utf8"=>"Shqip",
                "sk_SK.utf8"=>"Slovenčina",
                "sl_SI.utf8"=>"Slovenščina",
                "slovio"=>"Словио (Slovio)",
                "sr_YU.utf8@cyrillic"=>"Српски",
                "sr_YU.utf8"=>"Srpski",
                "chu"=>"Старославянски",
                "fi_FI.utf8"=>"Suomi",
                "sv_SE.utf8"=>"Svenska",
                "tr_TR.utf8"=>"Türkçe",
                "tzintzarian"=>"(Tzintzarian)",
                "uk_UA.utf8"=>"Україньска",
                "wendish"=>"(Wendish)",
	);
	return $a[$langcode];
}

function eng_name($langcode)
{
	global $iso_639;
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

function phpbb_lang($langcode) {
	global $phpbblangs;
	
	return $phpbblangs[$langcode];
}

?>
