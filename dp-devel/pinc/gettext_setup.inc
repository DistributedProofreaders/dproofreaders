<?PHP
// Localisation setup

// Try to extract the interface language from user preferences
if (isset($userP)){
    $language = $userP['u_intlang'];
} else {
    // Set default interface language to English if it's not specified
    if (!isset($language)) {
	$language = "en_EN";
    }
}

$locale = setlocale(LC_ALL, $language);

// Specify location of translation tables and choose domain
$gettext_domain = 'messages';
if ( function_exists('bindtextdomain') )
{
    bindtextdomain($gettext_domain, $code_dir . "/locale");
    textdomain($gettext_domain);
}
else
{
    // Allow for sites that do not have PHP's gettext extension installed.
    function _($str) { return $str; }
}

?>
