<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'iso_639_list.inc');
include_once($relPath.'metarefresh.inc');

theme(_("Translation Center"), "header");

if(!user_is_site_translator()) {
	echo _("You can not add a new language.");
	theme("","footer");
	exit();
}

if (isset($_GET['func'])) { $func = $_GET['func']; } else { $func = ""; }

if (empty($_GET['lang']) && $func == "newlang") {
	$dir = opendir($dyn_locales_dir);
	$i = 0;
	while (false != ($file = readdir($dir))) {
		if ($file != "." && $file != ".." & $file != "CVS" && $file != "translators") {
			$existing_lang[$i] = $file;
			$i++;
		}
	}

	echo "<table border='0' width='100%' cellpadding='0' cellspacing='3'><ul>";
	foreach ($iso_639 as $short_lang => $full_lang) {
		if (!in_array($short_lang, $existing_lang)) {
			echo "<tr><td width='50%' align='left'><li>$full_lang</li></td><td width='50%' align='left'>[ <a href='new_lang.php?func=create_newlang&amp;lang=".$short_lang."'>Create Translation File</a> ]</td></tr>";
		}
	}
	echo "</ul></table>";
}

if (!empty($_GET['lang']) && $func == "create_newlang") {
	$lang = $_GET['lang'];
	mkdir("$dyn_locales_dir/$lang", 0755);
	mkdir("$dyn_locales_dir/$lang/LC_MESSAGES/", 0755);

	chdir($code_dir);
	exec("xgettext `find -name \"*.php\" -o -name \"*.inc\"` -p locale/$lang/LC_MESSAGES/ --keyword=_ -C");

	chdir("$dyn_locales_dir/$lang/LC_MESSAGES/");
	exec("msgfmt messages.po -o messages.mo");

	metarefresh(0, "index.php?func=translate&amp;lang=$lang", "", "");
}

theme('','footer');
?>
