<?PHP
// Localization setup
$locales=array(
	"en"=>"en_EN",
	"en-us"=>"en_EN",
        "es"=>"es_ES.utf8",
	"nl"=>"nl_NL.utf8",
	"pt"=>"pt_PT",
	"sr"=>"sr_YU.utf8@cyrillic"
);

// Try to extract the interface language from user preferences
if (isset($userP)){
    $intlang = $userP['u_intlang'];
} else if (isset($_GET['lang'])) {
    $intlang = $_GET['lang'];
} else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $langs=explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $intlang = "";
    foreach($langs as $i) {
        $i = explode(";",$i);
        $i = trim($i[0]);
        if($locales[$i] || $locales[substr($i,0,5)] || $locales[substr($i,0,2)]) {
            $intlang=$locales[$i];
            break;
        }
    }
    if (!$intlang) { $intlang = "en_EN"; }
} else {
    $intlang = "en_EN";
}

$locale = setlocale(LC_ALL, $intlang);

// Specify location of translation tables and choose domain
$gettext_domain = 'messages';
if ( function_exists('bindtextdomain') )
{
    bindtextdomain($gettext_domain, $dyn_locales_dir);
    textdomain($gettext_domain);
    bind_textdomain_codeset ($gettext_domain, $GLOBALS['charset']);
}

?>
