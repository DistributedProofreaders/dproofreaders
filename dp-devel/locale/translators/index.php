<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'iso_3166_list.inc');
include_once($relPath.'metarefresh.inc');

theme("Translation Center", "header");

if (isset($_GET['func'])) { $func = $_GET['func']; } else { $func = ""; }
function parse_po($messages_po) {
	$i = 0;
	$p = 0;
	$translation['comments'] = "";
	$comments_done = 0;


	while ($i < count($messages_po)) {
		if (substr($messages_po[$i], 0, 2) == "# " && $comments_done != 1) {
			$translation['comments'] .= trim(preg_replace("/^#/", "", $messages_po[$i]))."\n";
			if (substr($messages_po[$i+1], 0, 1) != "#") { $comments_done = 1; }
		}
		if (substr($messages_po[$i], 0, 2) == "#:") {
			while (!empty($messages_po[$i]) && (substr($messages_po[$i], 0, 2) == "#:" || substr($messages_po[$i], 0, 2) == "#,")) {
				if (!isset($translation['location'][$p])) { $translation['location'][$p] = ""; }
				$formatted_location = trim($messages_po[$i]);
				$translation['location'][$p] .= $formatted_location."\n";
				$i++;
			}

			while (!empty($messages_po[$i]) && (substr($messages_po[$i], 0, 5) == "msgid" || substr($messages_po[$i], 0, 1) == "\"")) {
				if (!isset($translation['msgid'][$p])) { $translation['msgid'][$p] = ""; }
				$formatted_msgid = trim(preg_replace("/^msgid/", "", $messages_po[$i]));
				$formatted_msgid = trim(substr($formatted_msgid, 1, (strlen($formatted_msgid)-2)));
				$translation['msgid'][$p] .= " ".$formatted_msgid;
				$i++;
			}

			while (!empty($messages_po[$i]) && (substr($messages_po[$i], 0, 6) == "msgstr" || substr($messages_po[$i], 0, 1) == "\"")) {
				if (!isset($translation['msgstr'][$p])) { $translation['msgstr'][$p] = ""; }
				$formatted_msgstr = trim(preg_replace("/^msgstr/", "", $messages_po[$i]));
				$formatted_msgstr = trim(substr($formatted_msgstr, 1, (strlen($formatted_msgstr)-2)));
				$translation['msgstr'][$p] .= " ".$formatted_msgstr;
				$i++;
			}
			$p++;
		}
		$i++;
	}
	return $translation;
}

if (empty($_REQUEST['lang']) && empty($func)) {
	echo "<center><b><i><font size='+2'>"._("Translation Center")."</font></i></b></center><br>";
	echo _("We currently have the following languages available for translation.  These languages are based upon the ISO-3166 list and if you do not see a language you would like to proof available please click <a href='index.php?func=newlang'>here</a>.");
	echo "<br><br><center>"._("Please click the <i>Translate</i> link next to the language you would like to translate to begin:")."</center><br><br>";

	$dir = opendir($code_dir."/locale/");
	echo "<table border='0' cellspacing='3' cellpadding='' width='100%'><ul>";
 	while (false !== ($file = readdir($dir))) {
 		if ($file != "." && $file != ".." && $file != "CVS" && $file != "translators") {
 			echo "<tr><td width='50%'><li>".$iso_3166[$file]."</li></td><td width='50%'>[ <a href='index.php?func=translate&lang=$file'>"._("Translate")."</a> ]</td></tr>";
 		}
 	}
 	echo "</ul></table>";
}

if (empty($_GET['lang']) && $func == "newlang") {
	$dir = opendir($code_dir."/locale/");
	$i = 0;
	while (false != ($file = readdir($dir))) {
		if ($file != "." && $file != ".." & $file != "CVS" && $file != "translators") {
			$existing_lang[$i] = $file;
			$i++;
		}
	}

	echo "<table border='0' width='100%' cellpadding='0' cellspacing='3'><ul>";
	foreach ($iso_3166 as $short_lang => $full_lang) {
		if (!in_array($short_lang, $existing_lang)) {
			echo "<tr><td width='50%' align='left'><li>$full_lang</li></td><td width='50%' align='left'>[ <a href='index.php?func=create_newlang&lang=".$short_lang."'>Create Translation File</a> ]</td></tr>";
		}
	}
	echo "</ul></table>";
}

if (!empty($_GET['lang']) && $func == "create_newlang") {
	mkdir($code_dir."/locale/".$_GET['lang'], 0755);
	mkdir($code_dir."/locale/".$_GET['lang']."/LC_MESSAGES/", 0755);

	chdir($code_dir);
	exec("xgettext `find -name \"*.php\" -o -name \"*.inc\"` -p locale/".$_GET['lang']."/LC_MESSAGES/ --keyword=_ -C");

	chdir($code_dir."/locale/".$_GET['lang']."/LC_MESSAGES/");
	exec("msgfmt messages.po -o messages.mo");

	metarefresh(0, "index.php?func=translate&lang=".$_GET['lang']."", "", "");
}

if (!empty($_GET['lang']) && $func == "translate") {
	$translation = parse_po(file($code_dir."/locale/".$_GET['lang']."/LC_MESSAGES/messages.po"));
	$i = 0;

	echo "<table align='center' border='0' cellpadding='0' cellspacing='0' width='95%'><tr><td>";
	echo "<form action='index.php' method='post'>";
	echo "<br><center><b>Comments</b>:<br><textarea name='comments' rows=5 cols=85>".trim($translation['comments'])."</textarea></center><br>";

	while ($i < count($translation['location'])) {
		$location = trim(substr($translation['location'][$i], 2, strpos(substr($translation['location'][$i], 2), ":")));
		echo "<b><i>".trim(htmlentities($translation['msgid'][$i], ENT_NOQUOTES, "UTF-8"))."</b></i> (<a href='$code_url/$location' target='_new'>Location</a>)<br>";
		echo "<input type='hidden' name='location_".$i."' value='".base64_encode(serialize($translation['location'][$i]))."'><input type='hidden' name='msgid_".$i."' value='".base64_encode(serialize(trim($translation['msgid'][$i])))."'>";
		echo "<textarea name='msgstr_".$i."'rows=3 cols=85>".trim(htmlentities($translation['msgstr'][$i], ENT_NOQUOTES, "UTF-8"))."</textarea><br><br>";
		$i++;
	}

	echo "<input type='hidden' name='numofTranslations' value='".(count($translation['location'])-1)."'>";
	echo "<input type='hidden' name='lang' value='".$_GET['lang']."'>";
	echo "<center><input type='submit' name='save_po' value='Save and Compile'>&nbsp;";
	echo "<input type='submit' name='rebuild_strings' value='Rebuild String List'></center><br>";
	echo "</td></tr></table>";
}

if (isset($_POST['lang'])) {

	if (isset($_POST['save_po'])) {
		chdir($code_dir."/locale/".$_POST['lang']."/LC_MESSAGES/");
		$result = mysql_query("SELECT real_name, email FROM users WHERE username = '$pguser'");
		$real_name = mysql_result($result, 0, "real_name");
		$email_addr = mysql_result($result, 0, "email");
		  $po_file = fopen("messages.po.new", "w");
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
			  fputs($po_file, $translation_location);
			  fputs($po_file, "msgid \"$translation_msgid\"\n");
			  fputs($po_file, "msgstr \"$translation_msgstr\"\n\n");
			  $i++;
		  }
		  fclose($po_file);

		exec("recode ISO8859-1..UTF8 < messages.po.new > messages.po");
		unlink("messages.po.new");

		exec("msgfmt messages.po -o messages.mo");

		echo "<center><b><i><font size='+2'>"._("Translation Completed!")."</font></i></b><br><br>";
		echo _("Please click <a href='$code_url/locale/translators/'>here</a> to return to the Translation Center.")."</center>";
	} elseif (isset($_POST['rebuild_strings'])) {
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
		echo _("Please click ")."<a href='index.php?func=translate&lang=".$_POST['lang']."'>"._("here</a> to return to translation the ").$iso_3166[$_POST['lang']]." "._("language file.");
	}
}

theme('','footer');
?>