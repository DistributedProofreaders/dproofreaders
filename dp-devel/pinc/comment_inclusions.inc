<?PHP
include_once($relPath.'authors.inc');

// Project comments can contain two kinds of special markup:
//   [template=........]
// and
//   [biography=....]
// This markup should always be replaced with the
// proper template/biography before the project
// comments are displayed, zipped up for the
// post-processor, etc.
//
function parse_project_comments($comments) {
	global $relPath;

	// insert templates instead of [template=abcd.txt]
	$template_count = substr_count($comments, "[template=");
	if (!empty($template_count)) {
		$i = 1;
		while ($i <= $template_count) {
			$comments_backup = $comments;
			$comments = substr($comments_backup, 0, strpos($comments_backup, "[template="))."<br>";
			$comments .= file_get_contents($relPath."templates/comment_files/".substr($comments_backup, (strpos($comments_backup, "[template=")+10), 8));
			$comments .= "<br>".substr($comments_backup, (strpos($comments_backup, ".txt]")+5));
			$i++;
		}
	}

	// insert biographies instead of [biography=123]-markup
	$biography_count = substr_count($comments, '[biography=');
	if (!empty($biography_count)) {
		$i = 1;
		while ($i <= $biography_count) {
			$comments_backup = $comments;
			// where the [biography=123] starts and ends
			$pos = strpos($comments_backup, '[biography=');
			$pos2 = strpos($comments_backup, ']', $pos);
			$comments = substr($comments_backup, 0, $pos).'<br>';
			$comments .= get_biography(substr($comments_backup, $pos+11, $pos2-$pos-11));
			$comments .= '<br>'.substr($comments_backup, $pos2+1);
			$i++;
		}
	}
	return $comments;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

?>
