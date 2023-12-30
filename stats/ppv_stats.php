<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Post-Processing Verification Statistics");
output_header($title);

echo "<h1>$title</h1>\n";

echo "<h2>" . _("Post-Processing Verifiers") . "</h2>\n";
echo "<h3>" . _("Number of Projects Posted to PG") . "</h3>\n";

$psd = get_project_status_descriptor('posted');
dpsql_dump_themed_query("
    SELECT checkedoutby as '" . DPDatabase::escape(_("PPVer")) . "',
        count(*) as '" . DPDatabase::escape(_("Projects PPVd")) . "'
    FROM  `projects`
    WHERE 1  AND checkedoutby != postproofer AND $psd->state_selector
        and checkedoutby != ''
    GROUP  BY 1 
    ORDER  BY 2  DESC ", 1, DPSQL_SHOW_RANK);

echo "<br>\n";

echo _("Note that the above figures are as accurate as possible within the bounds of the current database structure");

echo "<br>\n";
