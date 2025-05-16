<?php
$relPath = "../pinc/";
include_once($relPath."base.inc");
include_once($relPath."metarefresh.inc");

$lang_options = array_keys(get_locale_translation_selection_options());
$lang_options[] = '';

// These should always be set if the user got here correctly.
// They won't be set if someone accesses this URL directly.
$language = get_enumerated_param($_POST, 'lang', '', $lang_options);
$location = $_POST['returnto'] ?? "$code_url/index.php";

if ($language) {
    // set the cookie
    dp_setcookie("language", $language, time() + 31536000);
} else {
    // delete the cookie
    dp_setcookie("language", '', time() - 3600);
}

metarefresh(0, $location);
