<?
$relPath="../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'list_projects.inc');

function showtexts($etext_limit, $type, $orderby, $field_name, $search_char, $show_total) {
	global $code_url;

	if ($type == "bronze") {
		$state = SQL_CONDITION_BRONZE;
		$text = " "._("Now Proofreading")."</font>  "._("These books are currently being processed through our site; sign in and start helping!!!!")."<br>";
	} elseif ($type == "silver") {
		$state = SQL_CONDITION_SILVER;
		$text = " "._("In Progress")."</font>  "._("These books have been processed through our site but have not yet posted to the Project Gutenberg archive (currently going through their final proofreading/assembly).")."<br>";
	} elseif ($type == "gold") {
		$state = SQL_CONDITION_GOLD;
		$text = " "._("Complete")."</font>  "._("These books have been processed through our site and posted to the Project Gutenberg archive.");
		$text .= "
			<a href='$code_url/feeds/backend.php?content=posted'>
				<img src='$code_url/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle'></a>
			<a href='$code_url/feeds/backend.php?content=posted&type=rss'>
				<img src='$code_url/graphics/rss.gif' border='0' width='36' height='14' style='vertical-align:middle'></a>
			<br>";
	} else {
		$state = "1";
		$text = " "._("All")."</font>";
	}

	if (!empty($search_char)) {
		$state .= " AND $field_name = '$search_char%'";
	}

	if (!empty($orderby)) {
		$state .= " ORDER BY $field_name $orderby";
	}

	$total = mysql_num_rows(mysql_query("SELECT projectid FROM projects WHERE $state"));

	if ($etext_limit > 0) {
 		$state .= " LIMIT $etext_limit";
	}

	if (!empty($show_total)) {
		echo "<img src='$code_url/graphics/{$type}_star.jpg' border='0' height='38' width='40' alt='$type star'> = <font face='Verdana' size='4'>".number_format($total)." $text";
	}

	list_projects( $state, "", "" );

	if (!empty($show_total) && $total > $etext_limit) {
		echo "<font face='Verdana' size='1'>--<a href='$code_url/stats/books/book_list.php?etext_limit=0&type=$type&orderby=$orderby&field_name=$field_name&search_char=$search_char&show_total='>"._("See more...")."</a></font><br><br>";
	}
}
?>
