<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'iso_639_list.inc');
include_once($relPath.'metarefresh.inc');

function parse_po($messages_po) {
	$i = 0;
	$p = 0;
	$translation['comments'] = "";
	$comments_done = 0;

	$total_messages = count($messages_po);
	while ($i < $total_messages) {
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

?>
