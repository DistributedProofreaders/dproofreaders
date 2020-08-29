<?php
$relPath="../pinc/";
include_once($relPath."base.inc");
include_once($relPath."misc.inc");
include_once($relPath."metarefresh.inc");

// These should always be set if the user got here correctly.
// They won't be set if someone accesses this URL directly.
$language = array_get($_POST, 'lang', '');
$location = array_get($_POST, 'returnto', "$code_url/default.php");

if($language)
{
    // set the cookie
    setcookie("language", $language, time()+31536000, "/");
}
else
{
    // delete the cookie
    setcookie("language", '', time()-3600, "/");
}

metarefresh(0, $location);

// vim: sw=4 ts=4 expandtab
