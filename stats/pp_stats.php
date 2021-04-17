<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Post-Processing Statistics");
output_header($title);

echo "<h1>$title</h1>\n";

echo "<p>";
echo "<a href='projects_Xed_graphs.php?which=PPd'>" . _("Projects PPd Graphs") . "</a><br>";
echo "<a href='PP_unknown.php'>" . _("Books with Mystery PPers") . "</a>";
echo "</p>";

echo "<h2>" . _("Total Projects Post-Processed Since Statistics were Kept") . "</h2>\n";

$psd = get_project_status_descriptor('PPd');
dpsql_dump_themed_query("
    SELECT
        SUM(num_projects) AS '" . DPDatabase::escape(_("Total Projects Post-Processed So Far")) . "'
    FROM project_state_stats WHERE $psd->state_selector
    GROUP BY date ORDER BY date DESC LIMIT 1
");

echo "<br>\n";

echo "<h2>" . _("Number of Distinct Post-Processors") . "</h2>\n";

dpsql_dump_themed_query("
    SELECT
        COUNT(DISTINCT postproofer) AS '" . DPDatabase::escape(_("Different PPers")) . "'
    FROM projects
");

echo "<br>\n";

echo "<h2>" . _("Most Prolific Post-Processors") . "</h2>\n";
echo "<h3>" . _("Number of Projects Finished PPing") . "</h3>\n";

$psd = get_project_status_descriptor('PPd');
dpsql_dump_themed_query("
    SELECT
        postproofer AS '" . DPDatabase::escape(_("PPer")) . "',
        COUNT(*) AS '" . DPDatabase::escape(_("Projects Finished PPing")) . "',
        CAST(SUM(n_pages) AS unsigned) AS '" . DPDatabase::escape(_("Pages Finished PPing")) . "'
    FROM projects
    WHERE $psd->state_selector
        AND postproofer is not null
    GROUP BY postproofer
    ORDER BY 2 DESC
", 1, DPSQL_SHOW_RANK);

echo "<br>\n";

echo "<h3>" . _("Number of Projects Posted to PG") . "</h3>\n";

$psd = get_project_status_descriptor('posted');
dpsql_dump_themed_query("
    SELECT
        postproofer AS '" . DPDatabase::escape(_("PPer")) . "',
        COUNT(*) AS '" . DPDatabase::escape(_("Projects Posted to PG")) . "',
        cast(sum(n_pages) AS unsigned) AS '" . DPDatabase::escape(_("Pages Posted to PG")) . "'
    FROM projects
    WHERE $psd->state_selector
    AND postproofer is not null
    GROUP BY postproofer
    ORDER BY 2 DESC
", 1, DPSQL_SHOW_RANK);

echo "<br>\n";
