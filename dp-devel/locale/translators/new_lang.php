<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'languages.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // array_get() & get_enumerated_param()

require_login();
undo_all_magic_quotes();

theme(_("Translation Center"), "header");

if(!user_is_site_translator()) {
    echo _("You can not add a new language.");
    theme("","footer");
    exit();
}

$lang = array_get($_GET, "lang", "");
$func = get_enumerated_param($_GET, "func", NULL, array("newlang","create_newlang"), true);

if ($lang == "" && $func == "newlang") {
    $dir = opendir($dyn_locales_dir);
    $i = 0;
    while (false != ($file = readdir($dir))) {
        if ($file != "." && $file != ".." & $file != "CVS" && $file != "translators") {
            $existing_lang[$i] = $file;
            $i++;
        }
    }

    echo "<table border='0' width='100%' cellpadding='0' cellspacing='3'><ul>";
    include($relPath.'iso_639_list.inc'); // pull in $iso_639
    foreach ($iso_639 as $short_lang => $long_name) {
        if (!in_array($short_lang, $existing_lang)) {
            echo "<tr><td width='50%' align='left'><li>" . eng_name($short_lang) . "</li></td><td width='50%' align='left'>[ <a href='new_lang.php?func=create_newlang&amp;lang=$short_lang'>" . _("Create Translation File") . "</a> ]</td></tr>";
        }
    }
    echo "</ul></table>";
}

if ($lang != "" && $func == "create_newlang") {
    mkdir("$dyn_locales_dir/$lang", 0755);
    mkdir("$dyn_locales_dir/$lang/LC_MESSAGES/", 0755);

    chdir($code_dir);
    exec("$xgettext_executable `find -name \"*.php\" -o -name \"*.inc\"` -p $dyn_locales_dir/$lang/LC_MESSAGES/ --keyword=_ -C");

    chdir("$dyn_locales_dir/$lang/LC_MESSAGES/");
    exec("msgfmt messages.po -o messages.mo");

    metarefresh(0, "index.php?func=translate&amp;lang=$lang", "", "");
}

theme('','footer');

// vim: sw=4 ts=4 expandtab
