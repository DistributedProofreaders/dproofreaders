<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Project Manager Statistics");
output_header($title);

echo "<br><h2>$title</h2>\n";

echo "<br>\n";

echo "<h3>" . _("Number of Distinct Project Managers") . "</h3>\n";

dpsql_dump_themed_query("
    SELECT
        count(distinct username) as '" 
            // TRANSLATORS: PMs = project managers
            . mysqli_real_escape_string(DPDatabase::get_connection(), _("Different PMs")) . "'
    FROM projects
",  0, DPSQL_NO_RANK, DPSQL_NO_RIGHT_ALIGN_INTS);

echo "<br>\n";

echo "<h3>" . _("Most Prolific Project Managers") . "</h3>\n";
echo "<h4>" . _("(Number of Projects Created)") . "</h4>\n";

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


echo "<h3>" . _("Most Prolific Project Managers") . "</h3>\n";
echo "<h4>" . _("(Number of Projects Posted to PG)") . "</h4>\n";

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

echo "<br>\n";

// vim: sw=4 ts=4 expandtab
