<?
$relPath='../pinc/';
include_once($relPath.'project_states.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');

$user_is_a_sitemanager = user_is_a_sitemanager();

if (!isset($_GET['name']))
{
	$title = _("Release Queues");
	theme($title,'header');
	echo "<br><h2>$title</h2>";
	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='99%'>\n";
	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'>";
	echo "<td colspan='7'><center><font color='".$theme['color_headerbar_font']."'><b>".$title."</b></font></center></td></tr>\n";
	{
		echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
		echo "<th>ordering</th>\n";
		echo "<th>enabled</th>\n";
		echo "<th>name</th>\n";
		echo "<th>current<br>length</th>\n";
		if ($user_is_a_sitemanager)
		{
			echo "<th>project_selector</th>\n";
			echo "<th>release_criterion</th>\n";
			echo "<th>comment</th>\n";
		}
		echo "</tr>\n";
	}

	$q_res = mysql_query("SELECT * FROM queue_defns ORDER BY ordering") or die(mysql_error());
	while ( $qd = mysql_fetch_assoc($q_res) )
	{
		$ename = urlencode( $qd['name'] );
		echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
		echo "<td>{$qd['ordering']}</td>\n";
		echo "<td>{$qd['enabled']}</td>\n";
		echo "<td><a href='release_queue.php?name=$ename'>{$qd['name']}</a></td>\n";
		$current_length =
			mysql_result(mysql_query("
				SELECT COUNT(*)
				FROM projects
				WHERE ({$qd['project_selector']})
					AND state='".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."'
			"),0);
		echo "<td>$current_length</td>\n";
		if ($user_is_a_sitemanager)
		{
			echo "<td>{$qd['project_selector']}</td>\n";
			echo "<td>{$qd['release_criterion']}</td>\n";
			echo "<td>{$qd['comment']}</td>\n";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
}
else
{
	$no_stats=0; // Only suppress stats on this page, since it is very wide.
	$name = $_GET['name'];

	$qd = mysql_fetch_assoc( mysql_query("
		SELECT * FROM queue_defns WHERE name='$name'
	"));
	$project_selector = $qd['project_selector'];
	$comment = $qd['comment'];

	$title = "\"$name\" " . _("Release Queue");
	$title = preg_replace('/(\\\\)/', "", $title); // Unescape apostrophes, etc.
	theme($title,'header');
	echo "<br><h2>$title</h2>";

	if ($user_is_a_sitemanager)
		{
			echo "<h4>project_selector: $project_selector</h4>\n\n";
			echo "<h4>$comment</h4>\n";
		}

	// Add Back to to Release Queues link
	echo "<p><a href='".$code_url."/stats/release_queue.php'>".("Back to Release Queues")."</a></p>\n";

        $comments_url1 = mysql_escape_string("<a href='".$code_url."/tools/proofers/projects.php?project=");
        $comments_url2 = mysql_escape_string("'>");
        $comments_url3 = mysql_escape_string("</a>");

	dpsql_dump_themed_query("
		SELECT

 			concat('$comments_url1',projectID,'$comments_url2', nameofwork, '$comments_url3')  as 'Name of Work',
			authorsname as 'Author\'s Name',
			language    as 'Language',
			genre       as 'Genre',
			difficulty  as 'Difficulty',
			username    as 'Project Manager',
			FROM_UNIXTIME(modifieddate) as 'Date Last Modified'
		FROM projects
		WHERE ($project_selector)
			AND state='".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."'
		ORDER BY modifieddate
	");
}

echo "<br>\n";
theme("", "footer");

?>
