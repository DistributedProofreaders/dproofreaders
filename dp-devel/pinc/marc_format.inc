<?
error_reporting(0);
include_once($relPath.'iso_lang_list.inc');
$literary_form_array = array("a" => "Art", "b" => "Biography", 3 => "Comedy", "c" => "Comic Strip", 4 => "Cooking", "d" => "Drama", "e" => "Essay", 1 => "Fiction", "g" => "Geography", 5 => "Historical", 6 => "History", 7 => "Humor", 'Humor', "i" => "Letter", "l" => "Linquistics", 8 => "Math", 9 => "Medicine", "m" => "Mixed Form", "v" => 'Music', 0 => "Non Fiction", "f" => "Novel", "y" => "Periodical", "p" => "Poetry", "r" => "Romance", "z" => "Science", "h" => "Satire", "j" => "Short Story", "s" => "Speech", "u" => "Unknown", "|" => "Unknown");

function marc_key_search($rec, $tag, $subfield) {
	foreach ($rec as $key => $value) {
		if (!empty($subfield)) {
			if (preg_match("/^\(3,$tag\)\(3,[0123456789[:space:]]+\)\(3,$subfield\)$/", $value[0])) {
				return $key;
			}
		} else {
			if (preg_match("/^\(3,$tag\)\(3,[0123456789@[:space:]]+\)$/", $value[0])) {
				return $key;
			}
		}
	}
}

function marc_title($rec) {
	$marc_title = $rec[marc_key_search($rec,"245","a")][1];
	  $marc_title = trim(preg_replace("/\/$|:$/", "", $marc_title));
	$marc_edition = $rec[marc_key_search($rec,"250","a")][1];
	  $marc_edition = trim($marc_edition);

    	if (isset($marc_edition)) { $marc_title = $marc_title.", ".$marc_edition; }

	return $marc_title;
}

function marc_author($rec) {
	$marc_author_100 = $rec[marc_key_search($rec,"100","a")][1];
	  $marc_author_100 = trim(preg_replace("/,$/", "", $marc_author_100));

	if (empty($marc_author_100)) {
	  $marc_author_700 = $rec[marc_key_search($rec,"700","a")][1];
	  $marc_author_700 = trim(preg_replace("/,$/", "", $marc_author_700));
	}

    	if (empty($marc_author_100) && empty($marc_author_700)) {
    	  $marc_author_710 = $rec[marc_key_search($rec,"710","a")][1];
	  $marc_author_710 = trim($marc_author_710);
	}

    	if (empty($marc_author_100) && empty($marc_author_700)) {
    		$marc_author = $marc_author_710;
    	} elseif (empty($marc_author_100) && isset($marc_author_700)) {
    		$marc_author = $marc_author_700;
    	} else {
    		$marc_author = $marc_author_100;
    	}

	return $marc_author;
}

function marc_lccn($rec) {
	$marc_lccn = $rec[marc_key_search($rec,"010","a")][1];
	  $marc_lccn = trim($marc_lccn);
    	return $marc_lccn;
}

function marc_isbn($rec) {
	$marc_isbn = $rec[marc_key_search($rec,"020","a")][1];
	  $marc_isbn = trim(substr($marc_isbn, 0, 10));
    	return $marc_isbn;
}

function marc_pages($rec) {
	$marc_pages = $rec[marc_key_search($rec,"300","a")][1];
	  $marc_pages = trim(preg_replace("/:$/", "", $marc_pages));
    	return $marc_pages;
}

function marc_date($rec) {
	$marc_date = $rec[marc_key_search($rec,"260","c")][1];
	  $marc_date = trim(preg_replace("/.$/", "", $marc_date));
    	return $marc_date;
}

function marc_language($rec) {
	$marc_008 = $rec[marc_key_search($rec,"008","")][1];
   	$marc_language = substr($marc_008, 35, 3);
    	$marc_language = convert_short_lang($marc_language);
    	return $marc_language;
}

function marc_literary_form($rec) {
	global $literary_form_array;
	$marc_008 = $rec[marc_key_search($rec,"008","")][1];
    	$marc_literary_form = substr($marc_008, 33, 1);
    	$marc_literary_form = $literary_form_array[$marc_literary_form];
    	return $marc_literary_form;
}

function marc_subject($rec) {
	$i = 0;
	$marc_subject = "";

	while ($i < count($rec)) {
		if (preg_match("/^\(3,650\)\(3,([^)]*)\)\(3,a\)$/", $rec[$i][0])) {
    			$marc_subject .= " ".trim($rec[$i][1]).",";
    		}
    		$i++;
    	}

	$marc_subject = trim(preg_replace("/,$/", "", $marc_subject));
    	return $marc_subject;
}

function marc_description($rec) {
	$marc_description = $rec[marc_key_search($rec,"520","a")][1];
	  $marc_description = trim(preg_replace("/:$/", "", $marc_description));
    	return $marc_description;
}

function marc_publisher($rec) {
	$marc_publisher_name = $rec[marc_key_search($rec,"260","b")][1];
	  $marc_publisher_name = trim(preg_replace("/,$/", "", $marc_publisher_name));
    	$marc_publisher_date = $rec[marc_key_search($rec,"260","c")][1];
	  $marc_publisher_date = trim(preg_replace("/.$/", "", $marc_publisher_date));

    	if (isset($marc_publisher_name) && isset($marc_publisher_date)) {
    		$marc_publisher = $marc_publisher_name.", ".$marc_publisher_date;
    	} else {
    		$marc_publisher = $marc_publisher_name;
    	}

    	return $marc_publisher;
}

function convert_short_lang($marc_language) {
	global $lang_list;
	$totalLanguages = count($lang_list);
	$i = 0;
	while ($i <= $totalLanguages) {
		if ((substr($lang_list[$i]["lang_code"], 0, 3) == $marc_language) || (substr($lang_list[$i]["lang_code"], 4, 3) == $marc_language)) {
			$marc_language = $lang_list[$i]["lang_name"];
			break;
		}
		$i++;
	}
	return $marc_language;
}

function create_dc_xml_oai($projectid, $scannercredit, $genre, $language, $author, $title, $rec) {
	$filename = $GLOBALS['projects_dir']."/$projectid/dc.xml";

	if (!file_exists($filename)) {
		touch($filename);
	}

	$result = mysql_query("SELECT real_name FROM users WHERE username = '".$GLOBALS['pguser']."'");
	$createdby = "Produced by ".mysql_result($result, 0, "real_name").", ";
	if (!empty($scannercredit) && $scannercredit != $GLOBALS['pguser']) {
		$result = mysql_query("SELECT real_name FROM users WHERE username = '$scannercredit'");
		$createdby .= mysql_result($result, 0, "real_name").", ";
	}
	$createdby .= " Charles Franks and the Online Distributed Proofreading Team.";

      $xmlpage = "<"."?"."xml version=\"1.0\" encoding=\"{$GLOBALS['charset']}\" ?".">
	<dc xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://purl.org/dc/elements/1.1/ http://www.openarchives.org/OAI/dc.xsd\">
	  <title>$title</title>
	  <creator>$author</creator>
	  <subject>".marc_subject($rec)."</subject>
	  <description>".marc_description($rec)."</description>
	  <publisher>Project Gutenberg</publisher>
	  <contributer>$createdby</contributer>
	  <date>".marc_date($rec)."</date>
	  <type>$genre</type>
	  <format>XML</format>
	  <identifier>http://www.pgdp.net/tools/catalog/project_home.php?project=$projectid</identifier>
	  <source>LCCN: ".marc_lccn($rec)."</source>
	  <language>$language</language>
	  <rights>http://www.pgdp.net/agreement.php</rights>
	</dc>";

	$fp = fopen($filename, "w");
	fwrite($fp, $xmlpage);
	fclose($fp);
}

function update_marc_db($rec) {
	global $projectid, $literary_form_array, $lang_list;

	//Update the Name of Work
	  if (!empty($_POST['nameofwork'])) { $rec[marc_key_search($rec,"245","a")][1] = $_POST['nameofwork']; }

	//Update the Authors Name
	  if (!empty($_POST['authorsname'])) {
	  	if (!empty($rec[marc_key_search($rec,"100","a")][1]) && $author_set != 1) {
	  		$rec[marc_key_search($rec,"100","a")][1] = $_POST['authorsname'];
	  		$author_set = 1;
	  	}
	  	if (!empty($rec[marc_key_search($rec,"700","a")][1]) && $author_set != 1) {
	  		$rec[marc_key_search($rec,"700","a")][1] = $_POST['authorsname'];
	  		$author_set = 1;
	  	}
	  	if (!empty($rec[marc_key_search($rec,"710","a")][1]) && $author_set != 1) {
	  		$rec[marc_key_search($rec,"710","a")][1] = $_POST['authorsname'];
	  		$author_set = 1;
	  	}
	  }

	//Update the Primary Language
	for ($i=0;$i<count($lang_list);$i++)  {
		if ($_POST['pri_language'] == $lang_list[$i]['lang_name']) {
			$curr_lang = $lang_list[$i]['lang_code'];
		}
	}
	$marc_008 = $rec[marc_key_search($rec,"008","")][1];
	$rec[marc_key_search($rec,"008","")][1] = substr($marc_008, 0, 35)."$curr_lang".substr($marc_008, 38);

	//Update the Genre
	$key = array_search($_POST['genre'], $literary_form_array);
	$marc_008 = $rec[marc_key_search($rec,"008","")][1];
	$rec[marc_key_search($rec,"008","")][1] = substr($marc_008, 0, 33)."$key".substr($marc_008, 34);

	//Update the marc_records database with the updated marc record
	mysql_query("UPDATE marc_records SET updated_array = '".base64_encode(serialize($rec))."' WHERE projectid = '$projectid'");

	return $rec;
}

function convert_standard_marc($rec) {
	$directory = "";
	$data = "";
	$stack = array();

	foreach ($rec as $tag => $tag_value) {
		if (strlen($tag_value[0]) == 7) {
			$i = 1;
			$length = 0;
			$directory .= substr($tag_value[0], 3, 3);

			while (substr($rec[$tag+$i][0], 0, 7) == $tag_value[0] && strlen($rec[$tag+$i][0]) != 7) {
				if (count($rec[$tag+$i]) == 1) {
					if (strlen($rec[$tag+$i][0]) == 12) { $length++; } else { $length = $length + 2; }
				} elseif (count($rec[$tag+$i]) == 2) {
					if (!preg_match("/^\(3,...\)\(3,@\)$/", $rec[$tag+$i][0])) { $length = $length + 2; }
				}
				$length = $length + strlen($rec[$tag+$i][1]);
				$i++;
			}

			if (strlen($directory) == 3) { $start = "00000"; } else { $start = (substr($directory, -12, 4) + substr($directory, -8, 5)); }

			$start = str_pad($start, 5, "0", STR_PAD_LEFT);
			$length = str_pad($length+1, 4, "0", STR_PAD_LEFT);
			$directory .= $length.$start;

			foreach ($rec as $field => $field_value) {
				if (preg_match("/^\(3,".substr($tag_value[0], 3, 3)."\)\(3,[012[:space:]]+\)$/", $field_value[0]) && !in_array(substr($tag_value[0], 3, 3), $stack)) {
					$data .= "\x1E".substr($field_value[0], 10, 2);
				}
				if (preg_match("/^\(3,".substr($tag_value[0], 3, 3)."\)\(3,[012[:space:]]+\)\(3,.\)$/", $field_value[0]) && !in_array(substr($tag_value[0], 3, 3), $stack)) {
					$data .= "\x1F".substr($field_value[0], -2, 1).$field_value[1];
				}
				if (preg_match("/^\(3,".substr($tag_value[0], 3, 3)."\)\(3,@\)$/", $field_value[0])) {
					$data .= "\x1E".$field_value[1];
				}
			}
			array_push($stack, substr($tag_value[0], 3, 3));
		}
	}

	$data .= "\x1E\x1D";
	$leader = str_pad((strlen($directory)+strlen($data)+24), 5, "0", STR_PAD_LEFT)."cam  22".str_pad((strlen($directory)+25), 5, "0", STR_PAD_LEFT)." u 4500";
	$raw_marc = $leader.$directory.$data;
	return $raw_marc;
}
?>
