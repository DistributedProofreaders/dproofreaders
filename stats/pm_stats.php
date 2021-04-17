<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Project Manager Statistics");
output_header($title);

echo "<h1>$title</h1>\n";

echo "<h2>" . _("Number of Distinct Project Managers") . "</h2>\n";

dpsql_dump_themed_query("
    SELECT
        count(distinct username) as '"
            // TRANSLATORS: PMs = project managers
            . mysqli_real_escape_string(DPDatabase::get_connection(), _("Different PMs")) . "'
    FROM projects
", 0, DPSQL_NO_RANK, DPSQL_NO_RIGHT_ALIGN_INTS);

echo "<br>\n";

echo "<h2>" . _("Most Prolific Project Managers") . "</h2>\n";
echo "<h3>" . _("Number of Projects Created") . "</h3>\n";

$psd = get_project_status_descriptor('created');
dpsql_dump_themed_query("
    SELECT
        username as '" . mysqli_real_escape_string(DPDatabase::get_connection(), pgettext("project manager", "PM")) . "',
        count(*) as '" . mysqli_real_escape_string(DPDatabase::get_connection(), _("Projects Created")) . "'
    FROM projects
    WHERE $psd->state_selector
    GROUP BY username
    ORDER BY 2 DESC
", 1, DPSQL_SHOW_RANK);

echo "<br>\n";

echo "<h3>" . _("Number of Projects Posted to PG") . "</h3>\n";

$psd = get_project_status_descriptor('posted');
dpsql_dump_themed_query("
    SELECT
        username as '" . mysqli_real_escape_string(DPDatabase::get_connection(), pgettext("project manager", "PM")) . "',
        count(*) as '" . mysqli_real_escape_string(DPDatabase::get_connection(), _("Projects Posted to PG")) . "'
    FROM projects
    WHERE $psd->state_selector
    GROUP BY username
    ORDER BY 2 DESC
", 1, DPSQL_SHOW_RANK);

echo "<br>\n";
