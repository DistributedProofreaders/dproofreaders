<?PHP
// Localization setup

// Try to extract the interface language from user preferences
if (isset($userP)){
    $intlang = $userP['u_intlang'];
} else {
    // Set default interface language to language passed with URL or English if it's not specified
 define (lang, '');
    if (!isset($lang)) {
        $intlang = $_GET['lang'];
    if ($intlang == ''){
        $intlang  = "en_EN";
    }
    }
}

$locale = setlocale(LC_ALL, $intlang);

// Specify location of translation tables and choose domain
$gettext_domain = 'messages';
if ( function_exists('bindtextdomain') )
{
    bindtextdomain($gettext_domain, $code_dir . "/locale");
    textdomain($gettext_domain);
    bind_textdomain_codeset ($gettext_domain, "UTF-8");
}

?>