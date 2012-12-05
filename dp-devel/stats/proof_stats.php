<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.inc');

require_login();

$valid_tally_names = array_keys($page_tally_names);
$tally_name   = get_enumerated_param($_GET, 'tally_name', null, $valid_tally_names);

$title = sprintf( _('Top 100 Proofreaders for Round %s'), $tally_name );

theme($title, 'header');

echo "<br><h2>$title</h2>\n";

echo "<br>\n";
echo "<br>\n";

// the 
$sql_anonymous = mysql_real_escape_string(_("Anonymous"));

if (isset($GLOBALS['pguser'])) 
// if user logged on
{

    // hide names of users who don't want even logged on people to see their names
    $proofreader_expr = "IF(u_privacy = ".PRIVACY_ANONYMOUS.",'$sql_anonymous', username)";
} 
else
{

    // hide names of users who don't want unlogged on people to see their names
    $proofreader_expr = "IF(u_privacy != ".PRIVACY_PUBLIC.",'$sql_anonymous', username)";
}

{
    $subtitle = sprintf( _('Users with the Highest Number of Pages Saved-as-Done in Round %s'), $tally_name );

    echo "<h3>$subtitle</h3>\n";

    $users_tallyboard = new TallyBoard( $tally_name, 'U' );

    list($joined_with_user_page_tallies,$user_page_tally_column) =
        $users_tallyboard->get_sql_joinery_for_current_tallies('users.u_id');

    $sql_upt_column_name = mysql_real_escape_string(
        // TRANSLATORS: %s is a page tally name (i.e. 'P1' or 'F2' or 'R*')
        sprintf(_("%s Pages Completed"), $tally_name));
    dpsql_dump_themed_ranked_query("
        SELECT
            $proofreader_expr AS '" . mysql_real_escape_string(_("Proofreader")) . "',
            $user_page_tally_column AS '$sql_upt_column_name'
        FROM users $joined_with_user_page_tallies
        WHERE $user_page_tally_column > 0
        ORDER BY 2 DESC, 1 ASC
        LIMIT 100
    ");

    echo "<br>\n";
    echo "<br>\n";
}

theme("","footer");

// vim: sw=4 ts=4 expandtab
