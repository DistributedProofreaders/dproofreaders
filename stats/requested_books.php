<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_project_info.inc');

require_login();

$title = _("Most Requested Books");
output_header($title);

echo "<h1>$title</h1>\n";
echo "<p>" . _("You can sign up for notifications in the Event Subscriptions section of the Project Comments page when proofreading.") . "</p>";

echo "<p class='warning'>This page has been temporarily disabled due to its high load on the system. We're working to fix this.</p>";
exit;

echo "<h2>" . _("Most Requested Books Being Proofread") . "</h2>\n";

create_temporary_project_event_subscription_summary_table();

$comments_url1 = DPDatabase::escape("<a href='$code_url/project.php?id=");
$comments_url2 = DPDatabase::escape("'>");
$comments_url3 = DPDatabase::escape("</a>");

// Looking at the other two queries, you might expect the first one to use
// SQL_CONDITION_BRONZE. However, that would exclude the WAITING_FOR_RELEASE
// states, which we apparently want to include here.
// Instead, custom-build a project-state condition that includes the
// WAITING and AVAILABLE states from each round.
$state_condition = '0';
for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++) {
    $round = get_Round_for_round_number($rn);
    $state_condition .= "
        OR state='{$round->project_waiting_state}'
        OR state='{$round->project_available_state}'
    ";
}
dpsql_dump_themed_query(
    "
    SELECT
        CONCAT('$comments_url1',projects.projectid,'$comments_url2', nameofwork, '$comments_url3') AS '" . DPDatabase::escape(_("Title")) . "',
        authorsname AS '" . DPDatabase::escape(_("Author")) . "',
        genre AS '" . DPDatabase::escape(_("Genre")) . "',
        language AS '" . DPDatabase::escape(_("Language")) . "',
        pesgbp.nouste_posted AS '" . DPDatabase::escape(_("Notification Requests")) . "'
    FROM project_event_subscriptions_grouped_by_project AS pesgbp, projects
    WHERE pesgbp.projectid = projects.projectid
        AND ($state_condition)
    ORDER BY 5 DESC
    LIMIT 50
    ",
    1,
    DPSQL_SHOW_RANK
);

echo "<br>\n";
echo "<h2>" . _("Most Requested Books In Post-Processing") . "</h2>\n";

//        $post_url1 = DPDatabase::escape("<a href='$code_url/project.php?id=");

dpsql_dump_themed_query(
    "
    SELECT
        CONCAT('$comments_url1',projects.projectid,'$comments_url2', nameofwork, '$comments_url3') AS '" . DPDatabase::escape(_("Title")) . "',
        authorsname AS '" . DPDatabase::escape(_("Author")) . "',
        genre AS '" . DPDatabase::escape(_("Genre")) . "',
        language AS '" . DPDatabase::escape(_("Language")) . "',
        pesgbp.nouste_posted AS '" . DPDatabase::escape(_("Notification Requests")) . "'
    FROM project_event_subscriptions_grouped_by_project AS pesgbp, projects
    WHERE pesgbp.projectid = projects.projectid
        AND ".SQL_CONDITION_SILVER."
    ORDER BY 5 DESC
    LIMIT 50
    ",
    1,
    DPSQL_SHOW_RANK
);

echo "<br>\n";
echo "<h2>" . _("Most Requested Books Posted to Project Gutenberg") . "</h2>\n";

$pg_url1 = DPDatabase::escape(sprintf("<a href='%s", get_pg_catalog_url_for_etext('')));
dpsql_dump_themed_query(
    "
    SELECT
        CONCAT('$pg_url1', postednum, '$comments_url2', nameofwork, '$comments_url3') AS '" . DPDatabase::escape(_("Title")) . "',
        authorsname AS '" . DPDatabase::escape(_("Author")) . "',
        genre AS '" . DPDatabase::escape(_("Genre")) . "',
        language AS '" . DPDatabase::escape(_("Language")) . "',
        int_level AS '" . DPDatabase::escape(_("Notification Requests")) . "'
    FROM projects
    WHERE ".SQL_CONDITION_GOLD."
        AND int_level !=0
    ORDER BY 5 DESC
    LIMIT 50
    ",
    1,
    DPSQL_SHOW_RANK
);
