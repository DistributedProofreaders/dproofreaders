<?
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
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
		if (startswith($messages_po[$i], "# ") && $comments_done != 1) {
			$translation['comments'] .= trim(preg_replace("/^#/", "", $messages_po[$i]))."\n";
			if (! startswith($messages_po[$i+1], "#")) { $comments_done = 1; }
		}
		if (startswith($messages_po[$i], "#:")) {
			while (!empty($messages_po[$i]) && (startswith($messages_po[$i], "#:") || startswith($messages_po[$i], "#,"))) {
				if (!isset($translation['location'][$p])) { $translation['location'][$p] = ""; }
				$formatted_location = trim($messages_po[$i]);
				$translation['location'][$p] .= $formatted_location."\n";
				$i++;
			}

			while (!empty($messages_po[$i]) && (startswith($messages_po[$i], "msgid") || startswith($messages_po[$i], "\""))) {
				if (!isset($translation['msgid'][$p])) { $translation['msgid'][$p] = ""; }
				$formatted_msgid = trim(preg_replace("/^msgid/", "", $messages_po[$i]));
				$formatted_msgid = substr($formatted_msgid, 1, (strlen($formatted_msgid)-2));
				$translation['msgid'][$p] .= $formatted_msgid;
				$i++;
			}

			while (!empty($messages_po[$i]) && (startswith($messages_po[$i], "msgstr") || startswith($messages_po[$i], "\""))) {
				if (!isset($translation['msgstr'][$p])) { $translation['msgstr'][$p] = ""; }
				$formatted_msgstr = trim(preg_replace("/^msgstr/", "", $messages_po[$i]));
				$formatted_msgstr = substr($formatted_msgstr, 1, (strlen($formatted_msgstr)-2));
				$translation['msgstr'][$p] .= $formatted_msgstr;
				$i++;
			}
			$p++;
		}
		$i++;
	}
	return $translation;
}

?>
