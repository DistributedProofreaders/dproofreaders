<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'iso_639_list.inc');
include_once($relPath.'metarefresh.inc');
include_once('parse_po.inc');

theme(_("Translation Center"), "header");

if (isset($_GET['func'])) { $func = $_GET['func']; } else { $func = ""; }

if (empty($_REQUEST['lang']) && empty($func)) {
	echo "<center><b><i><font size='+2'>"._("Translation Center")."</font></i></b></center><br>";
	echo _("We currently have the following languages available for translation.  These languages are based upon the ISO-639 list and if you do not see a language you would like to proofread available please click <a href='new_lang.php?func=newlang'>here</a>.");
	echo "<br><br><center>"._("Please click the <i>Translate</i> link next to the language you would like to translate to begin:")."</center><br><br>";

	$dir = opendir($dyn_locales_dir);
	echo "<table border='0' cellspacing='3' cellpadding='' width='100%'><ul>";
 	while (false !== ($file = readdir($dir))) {
 		if ($file != "." && $file != ".." && $file != "CVS" && $file != "translators") {
 			echo "<tr><td width='50%'><li>".$iso_639[$file]."</li></td><td width='50%'>[ <a href='index.php?func=translate&lang=$file'>"._("Translate")."</a> ]</td></tr>";
 		}
 	}
 	echo "</ul></table>";
}

if (!empty($_GET['lang']) && $func == "translate") {
	$lang = $_GET['lang'];
	$translation = parse_po(file("$dyn_locales_dir/$lang/LC_MESSAGES/messages.po"));
	$i = 0;
	if (!isset($translation['location']))
	{
		echo "\n<p>"._("Something is wrong: I cannot find any translatable strings in the translation file. Perhaps the file is corrupt?");
	}
	else
	{
		$numOfTranslations = count($translation['location']);

		echo "<table align='center' border='0' cellpadding='0' cellspacing='0' width='95%'><tr><td>";
		echo "<form action='save_po.php' method='post'>";
		echo "<br><center><b>"._("Comments")."</b>:<br><textarea name='comments' rows=5 cols=85>".trim($translation['comments'])."</textarea></center><br>\n";

		while ($i < $numOfTranslations) {
			$location = trim(substr($translation['location'][$i], 2, strpos(substr($translation['location'][$i], 2), ":")));
			echo "<b><i>".visible_invisibles(htmlspecialchars($translation['msgid'][$i]))."</b></i>\n(<a href='$code_url/$location' target='_new'>Location</a>)<br>\n";
			echo "<input type='hidden' name='location_".$i."' value='".base64_encode(serialize($translation['location'][$i]))."'>\n<input type='hidden' name='msgid_".$i."' value='".base64_encode(serialize(trim($translation['msgid'][$i])))."'>\n";
			echo "<textarea name='msgstr_".$i."'rows=3 cols=85>".trim(htmlspecialchars($translation['msgstr'][$i]))."</textarea><br><br>\n\n";
			$i++;
			}

		echo "<input type='hidden' name='numofTranslations' value='".($numOfTranslations-1)."'>";
		echo "<input type='hidden' name='lang' value='$lang'>";
		echo "<center><input type='submit' name='save_po' value='"._("Save and Compile")."'>&nbsp;";
		echo "<input type='submit' name='rebuild_strings' value='"._("Rebuild String List")."'></center><br>";
		echo "</td></tr></table>";
		}
	}

theme('','footer');

function visible_invisibles($str)
{
	return preg_replace(
		array(
			"/^ /",
			"/ $/",
		),
		"<img src='space.gif' valign='bottom'>",
		$str
	);
}
?>
