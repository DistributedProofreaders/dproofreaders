<?php
$relPath='../pinc/';
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

$title = sprintf( _('Top 100 Proofreaders for Round %s'), $tally_name );

output_header($title);

echo "<h1>$title</h1>\n";

$sql_anonymous = mysqli_real_escape_string(DPDatabase::get_connection(), _("Anonymous"));

// if user logged on
if($pguser)
{
    // hide names of users who don't want even logged on people to see their names
    $proofreader_expr = "IF(u_privacy = ".PRIVACY_ANONYMOUS.",'$sql_anonymous', username)";
} 
else
{
    // hide names of users who don't want unlogged on people to see their names
    $proofreader_expr = "IF(u_privacy != ".PRIVACY_PUBLIC.",'$sql_anonymous', username)";
}

$subtitle = sprintf( _('Users with the Highest Number of Pages Saved-as-Done in Round %s'), $tally_name );

echo "<h2>$subtitle</h2>\n";

$users_tallyboard = new TallyBoard( $tally_name, 'U' );

list($joined_with_user_page_tallies,$user_page_tally_column) =
    $users_tallyboard->get_sql_joinery_for_current_tallies('users.u_id');

$sql_upt_column_name = mysqli_real_escape_string(DPDatabase::get_connection(),
    // TRANSLATORS: %s is a page tally name (i.e. 'P1' or 'F2' or 'R*')
    sprintf(_("%s Pages Completed"), $tally_name));
dpsql_dump_themed_query("
    SELECT
        $proofreader_expr AS '" . mysqli_real_escape_string(DPDatabase::get_connection(), _("Proofreader")) . "',
        $user_page_tally_column AS '$sql_upt_column_name'
    FROM users $joined_with_user_page_tallies
    WHERE $user_page_tally_column > 0
    ORDER BY 2 DESC, 1 ASC
    LIMIT 100
", 1, DPSQL_SHOW_RANK);

echo "<br>\n";

// vim: sw=4 ts=4 expandtab
