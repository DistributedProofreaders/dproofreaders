<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'iso_3166_list.inc');
include_once($relPath.'metarefresh.inc');
include_once('parse_po.inc');

theme(_("Translation Center"), "header");

if (isset($_POST['lang']) && isset($_POST['save_po'])) {

	chdir($code_dir."/locale/".$_POST['lang']."/LC_MESSAGES/");
	$result = mysql_query("SELECT real_name, email FROM users WHERE username = '$pguser'");
	$real_name = mysql_result($result, 0, "real_name");
	$email_addr = mysql_result($result, 0, "email");
	$po_file = fopen("messages.po", "w");
	fputs($po_file, "# ".str_replace("\n", "\n# ", $_POST['comments'])."\n");
	fputs($po_file, "msgid \"\"\n");
	fputs($po_file, "msgstr \"\"\n");
	fputs($po_file, "\"Project-Id-Version: 1.0\\n\"\n");
	fputs($po_file, "\"POT-Creation-Date: ".date("Y-m-j h:iO")."\\n\"\n");
	fputs($po_file, "\"PO-Revision-Date: ".date("Y-m-j h:iO")."\\n\"\n");
	fputs($po_file, "\"Last-Translator: $real_name &lt;$email_addr&gt;\\n\"\n");
	fputs($po_file, "\"Language-Team: ".$iso_3166[$_POST['lang']]." &lt;$email_addr&gt;\\n\"\n");
	fputs($po_file, "\"MIME-Version: 1.0\\n\"\n");
	fputs($po_file, "\"Content-Type: text/plain; charset=UTF-8\\n\"\n");
	fputs($po_file, "\"Content-Transfer-Encoding: 8bit\\n\"\n\n");

	$i = 0;
	while ($i <= $_POST['numofTranslations']) {
		$translation_location = unserialize(base64_decode($_POST['location_'.$i]));
		$translation_msgid = unserialize(base64_decode($_POST['msgid_'.$i]));
		$translation_msgstr = str_replace("\n", "\"\n\"", $_POST['msgstr_'.$i]);
		$translation_msgstr = stripslashes($translation_msgstr);
 
		fputs($po_file, $translation_location);
		fputs($po_file, "msgid \"$translation_msgid\"\n");
		fputs($po_file, "msgstr \"$translation_msgstr\"\n\n");
		$i++;
	}
	fclose($po_file);

	exec("msgfmt messages.po -o messages.mo");

	echo "<center><b><i><font size='+2'>"._("Translation Completed!")."</font></i></b><br><br>";
	echo "<a href='$code_url/locale/translators/'>"._("Please click here to return to the Translation Center.")."</a></center>";
}

if (isset($_POST['lang']) && isset($_POST['rebuild_strings'])) {

	$translation = parse_po(file($code_dir."/locale/".$_POST['lang']."/LC_MESSAGES/messages.po"));

	chdir($code_dir);
	exec("xgettext -j `find -name \"*.php\" -o -name \"*.inc\"` -p locale/".$_POST['lang']."/LC_MESSAGES/ --keyword=_ -C");

	$i=4;
	$lines = file($code_dir."/locale/".$_POST['lang']."/LC_MESSAGES/messages.po");
	$po_file = fopen($code_dir."/locale/".$_POST['lang']."/LC_MESSAGES/messages.po", "w");
	fputs($po_file, "# ".str_replace("\n", "\n# ", $_POST['comments'])."\n");
	while ($i < count($lines)) {
		fputs($po_file, $lines[$i]);
		$i++;
	}
	fclose($po_file);

	echo "<center><b><i><font size='+2'>"._("Strings Rebuilt!")."</font></i></b><br><br>";
	echo "<a href='index.php?func=translate&lang=".$_POST['lang']."'>"._("Please click here to return to translate the ").$iso_3166[$_POST['lang']]." "._("language file").".</a>";
}

theme('','footer');
?>