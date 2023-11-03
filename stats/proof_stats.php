<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.inc'); // get_page_tally_names()
include_once($relPath.'misc.inc'); // get_enumerated_param()

require_login();

$valid_tally_names = array_keys(get_page_tally_names());
$tally_name = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);

$title = sprintf(_('Top 100 Proofreaders for Round %s'), $tally_name);

output_header($title);

echo "<h1>$title</h1>\n";

echo "<p>" . sprintf(_('Users with the Highest Number of Pages Saved-as-Done in Round %s'), $tally_name) . "</p>";

$users_tallyboard = new TallyBoard($tally_name, 'U');

[$joined_with_user_page_tallies, $user_page_tally_column] =
    $users_tallyboard->get_sql_joinery_for_current_tallies('users.u_id');

// TRANSLATORS: %s is a page tally name (i.e. 'P1' or 'F2' or 'R*')
$sql_upt_column_name = sprintf(_("%s Pages Completed"), $tally_name);

$sql = sprintf(
    "
    SELECT
        IF(u_privacy = %d, '%s', username) AS '%s',
        $user_page_tally_column AS '%s'
    FROM users $joined_with_user_page_tallies
    WHERE $user_page_tally_column > 0
    ORDER BY 2 DESC, 1 ASC
    LIMIT 100
    ",
    PRIVACY_ANONYMOUS,
    DPDatabase::escape(_("Anonymous")),
    DPDatabase::escape(_("Proofreader")),
    DPDatabase::escape($sql_upt_column_name)
);

dpsql_dump_themed_query($sql, 1, DPSQL_SHOW_RANK);

echo "<br>\n";
