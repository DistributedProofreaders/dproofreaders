<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');

$title = _("Most Requested Books");
theme($title,'header');

echo "<br><h2 style='color: $theme[color_headerbar_bg];'>$title</h2><br>\n
<p>You can sign up for notifications by following the &quot;Click here to register for automatic email notification of when this has been posted to Project Gutenberg&quot; link on the Project Comments page when proofreading.</p>";

echo "<br><br><h3 style='color: $theme[color_headerbar_bg];'>" . _("Most Requested Books Being Proofread") . "</h2><br>\n";

        $comments_url1 = mysql_escape_string("<a href='".$code_url."/tools/proofers/projects.php?project=");
        $comments_url2 = mysql_escape_string("'>");
        $comments_url3 = mysql_escape_string("</a>");

dpsql_dump_themed_ranked_query("
SELECT
CONCAT('$comments_url1',projectID,'$comments_url2', nameofwork, '$comments_url3') AS 'Title', 
`authorsname` AS 'Author', 
`genre` AS 'Genre',
`language` AS 'Language',
COUNT(*) AS 'Notification Requests' 
FROM `usersettings`,`projects` 
WHERE `value` = `projectid` 
AND `setting` = 'posted_notice' 
AND (`state` = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."'
  OR `state` = '".PROJ_PROOF_SECOND_WAITING_FOR_RELEASE."'
  OR `state` = '".PROJ_PROOF_FIRST_AVAILABLE."'
  OR `state` = '".PROJ_PROOF_SECOND_AVAILABLE."'
  OR `state` = '".PROJ_PROOF_FIRST_VERIFY."'
  OR `state` = '".PROJ_PROOF_SECOND_VERIFY."')
GROUP BY `value` 
ORDER BY 'Notification Requests' DESC 
LIMIT 50");

echo "<br>\n";
echo "<br><br><h3 style='color: $theme[color_headerbar_bg];'>" . _("Most Requested Books In Post-Processing") . "</h2><br>\n";

//        $post_url1 = mysql_escape_string("<a href='".$code_url."/tools/post_proofers/post_comments.php?project=");

dpsql_dump_themed_ranked_query("
SELECT
concat('$comments_url1',projectID,'$comments_url2', nameofwork, '$comments_url3') AS 'Title', 
`authorsname` AS 'Author', 
`genre` AS 'Genre',
`language` AS 'Language',
COUNT(*) as 'Notification Requests' 
FROM `usersettings`,`projects` 
WHERE `value` = `projectid` 
AND `setting` = 'posted_notice' 
AND (`state` = 'proj_post_first_unavailable' OR `state` = 'proj_post_first_available' OR `state` = 'proj_post_first_checked_out' OR `state` = 'proj_post_second_available' OR `state` = 'proj_post_second_checked_out' OR `state` = 'proj_post_complete')
GROUP BY `value` 
ORDER BY 'Notification Requests' DESC 
LIMIT 50");

echo "<br>\n";
echo "<br><br><h3 style='color: $theme[color_headerbar_bg];'>" . _("Most Requested Books Posted to Project Gutenberg") . "</h2><br>\n";

        $pg_url1 = mysql_escape_string("<a href='http://www.gutenberg.org/etext/");
 dpsql_dump_themed_ranked_query("
SELECT
CONCAT('$pg_url1',postednum,'$comments_url2', nameofwork, '$comments_url3') AS 'Title', 
`authorsname` AS 'Author', 
`genre` AS 'Genre',
`language` AS 'Language',
`int_level` AS 'Notification Requests' 
FROM `projects` 
WHERE (`state` = 'proj_submit_pgunavailable' OR `state` = 'proj_submit_pgavailable' OR `state` = 'proj_submit_pgposting' OR `state` = 'proj_submit_pgposted' OR `state` = 'proj_correct_checked_out' OR `state` = 'proj_correct_available')
AND int_level !=0
ORDER BY 'Notification Requests' DESC 
LIMIT 50");


theme("","footer");

?>
