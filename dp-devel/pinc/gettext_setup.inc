<?PHP
// Localisation setup

// Try to extract the interface language from user preferences
if (isset($userP)){
    $intlang = $userP['u_intlang'];
} else {
    // Set default interface language to English if it's not specified
    if (!isset($lang)) {
	$intlang = "en_EN";
    }
}

$locale = setlocale(LC_ALL, $intlang);

// Specify location of translation tables and choose domain
$gettext_domain = 'messages';
if ( function_exists('bindtextdomain') )
{
    bindtextdomain($gettext_domain, $code_dir . "/locale");
    textdomain($gettext_domain);
}

?>
