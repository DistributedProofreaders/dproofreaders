<?
$relPath='../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_project_info.inc');

$title = _("Most Requested Books");
theme($title,'header');

echo "<br><h2 style='color: $theme[color_headerbar_bg];'>$title</h2><br>\n
<p>You can sign up for notifications in the 'Event Subscriptions' section of the Project Comments page when proofreading.</p>";

echo "<br><br><h3 style='color: $theme[color_headerbar_bg];'>" . _("Most Requested Books Being Proofread") . "</h2><br>\n";

create_temporary_project_event_subscription_summary_table();

$comments_url1 = mysql_escape_string("<a href='$code_url/project.php?id=");
$comments_url2 = mysql_escape_string("'>");
$comments_url3 = mysql_escape_string("</a>");

// Looking at the other two queries, you might expect the first one to use
// SQL_CONDITION_BRONZE. However, that would exclude the WAITING_FOR_RELEASE
// states, which we apparently want to include here.
// Instead, custom-build a project-state condition that includes the
// WAITING and AVAILABLE states from each round.
$state_condition = '0';
for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
    $round = get_Round_for_round_number($rn);
    $state_condition .= "
        OR state='{$round->project_waiting_state}'
        OR state='{$round->project_available_state}'
    ";
}
dpsql_dump_themed_ranked_query("
    SELECT
        CONCAT('$comments_url1',projects.projectid,'$comments_url2', nameofwork, '$comments_url3') AS 'Title', 
        authorsname AS 'Author', 
        genre AS 'Genre',
        language AS 'Language',
        pesgbp.nouste_posted AS 'Notification Requests'
    FROM project_event_subscriptions_grouped_by_project AS pesgbp, projects
    WHERE pesgbp.projectid = projects.projectid
        AND ($state_condition)
    ORDER BY 'Notification Requests' DESC 
    LIMIT 50
");

echo "<br>\n";
echo "<br><br><h3 style='color: $theme[color_headerbar_bg];'>" . _("Most Requested Books In Post-Processing") . "</h2><br>\n";

//        $post_url1 = mysql_escape_string("<a href='$code_url/project.php?id=");

dpsql_dump_themed_ranked_query("
    SELECT
        CONCAT('$comments_url1',projects.projectid,'$comments_url2', nameofwork, '$comments_url3') AS 'Title', 
        authorsname AS 'Author', 
        genre AS 'Genre',
        language AS 'Language',
        pesgbp.nouste_posted AS 'Notification Requests'
    FROM project_event_subscriptions_grouped_by_project AS pesgbp, projects
    WHERE pesgbp.projectid = projects.projectid
        AND ".SQL_CONDITION_SILVER."
    ORDER BY 'Notification Requests' DESC 
    LIMIT 50
");

echo "<br>\n";
echo "<br><br><h3 style='color: $theme[color_headerbar_bg];'>" . _("Most Requested Books Posted to Project Gutenberg") . "</h2><br>\n";

$pg_url1 = mysql_escape_string("<a href='http://www.gutenberg.org/etext/");
dpsql_dump_themed_ranked_query("
    SELECT
        CONCAT('$pg_url1',postednum,'$comments_url2', nameofwork, '$comments_url3') AS 'Title', 
        authorsname AS 'Author', 
        genre AS 'Genre',
        language AS 'Language',
        int_level AS 'Notification Requests' 
    FROM projects 
    WHERE ".SQL_CONDITION_GOLD."
        AND int_level !=0
    ORDER BY 'Notification Requests' DESC 
    LIMIT 50
");

theme("","footer");

?>
