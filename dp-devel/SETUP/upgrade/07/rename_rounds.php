<?PHP
$relPath = '../../../pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

mysql_query("SELECT round_id FROM queue_defns");
if ( mysql_errno() == 1054 ) // unknown column
{
    echo "
    ERROR: The 'queue_defns' table does not have a 'round_id' column.
    This is probably because you have not yet run alter_queue_defns.php.
    You must run that script before this one.
    ";
    exit;
}
else if ( mysql_errno() != 0 )
{
    echo mysql_error(), "\n";
    exit;
}

// -----------------------------------------------------------------------------

$src_round_id = 'P2';
$dst_round_id = 'P3';

echo "\n";
echo "In the database, changing occurrences of '$src_round_id' to '$dst_round_id'...\n";
echo "\n";

define( 'WHOLE_COL',   1 );
define( 'PART_OF_COL', 2 );

$already_appears = array();

function update_table( $how, $table_name, $column_name, $allow_nonexistent_table=FALSE )
{
    global $src_round_id, $dst_round_id;
    global $already_appears;

    $sql = array();
    if ( $how == WHOLE_COL )
    {
        // When a round-id appears in the column,
        // it's the only thing in the column.
        $sql['SELECT'] = "
            SELECT COUNT(*)
            FROM $table_name
            WHERE $column_name = '$dst_round_id'
        ";
        $sql['UPDATE'] = "
            UPDATE $table_name
            SET   $column_name = '$dst_round_id'
            WHERE $column_name = '$src_round_id'
        ";
    }
    else if ( $how == PART_OF_COL )
    {
        // When a round-id appears in the column,
        // there may be other stuff in the column.
        // Note that 'BINARY' forces a case-sensitive search.
        $sql['SELECT'] = "
            SELECT COUNT(*)
            FROM $table_name
            WHERE INSTR($column_name, BINARY '$dst_round_id')
        ";
        $sql['UPDATE'] = "
            UPDATE $table_name
            SET $column_name = REPLACE($column_name,'$src_round_id','$dst_round_id')
            WHERE INSTR($column_name, BINARY '$src_round_id')
                AND $column_name != '{$src_round_id}_mentor'
        ";
        // The '_mentor' thing is a kludge:
        // We don't want P2_mentor changed to P3_mentor
        // in usersettings.setting.
    }
    else
    {
        die( "bad how: '$how'" );
    }

    // First, look to see if the dst id already appears in any of the tables.
    // If so, it either means you already ran this script on that table
    // (in which case you don't need to do the update again)
    // or else the dst id appears of its own accord,
    // (in which case it might be dangerous to do the update).
    foreach ( array('SELECT', 'UPDATE') as $pass )
    {
        $res = mysql_query( $sql[$pass] );
        if ( !$res )
        {
            if ( $allow_nonexistent_table && mysql_errno() == 1146 )
            {
                // skip it
                return;
            }
            else
            {
                echo "\n";
                echo $table_name, "\n";
                echo mysql_error(), "\n";
                exit;
            }
        }
        else
        {
            if ( $pass == 'SELECT' )
            {
                list($count) = mysql_fetch_row($res);
                if ($count > 0)
                {
                    echo str_pad($table_name, 23), "$dst_round_id already appears!!!\n";
                    $already_appears[] = "$table_name.$column_name";
                    // skip UPDATE pass
                    return;
                }
            }
            else
            {
                echo str_pad($table_name, 23), mysql_info(), "\n";
            }
        }
    }
}

// -----------------------------------------------------------------------------

update_table( WHOLE_COL, 'page_events', 'round_id' );

update_table( WHOLE_COL, 'access_log', 'activity' );

update_table( WHOLE_COL, 'past_tallies',     'tally_name' );
update_table( WHOLE_COL, 'best_tally_rank',  'tally_name' );
update_table( WHOLE_COL, 'current_tallies',  'tally_name' );
update_table( WHOLE_COL, 'site_tally_goals', 'tally_name' );

update_table( WHOLE_COL, 'news_items',  'news_page_id' );
update_table( WHOLE_COL, 'queue_defns', 'round_id' );

// --------------------------------------------

update_table( PART_OF_COL, 'job_logs',     'comments' );
update_table( PART_OF_COL, 'user_filters', 'filtertype' );
update_table( PART_OF_COL, 'usersettings', 'setting' );

update_table( PART_OF_COL, 'project_state_stats', 'state' );
update_table( PART_OF_COL, 'projects',            'state' );
update_table( PART_OF_COL, 'project_pages',       'state', TRUE );

$project_res = mysql_query("
    SELECT projectid
    FROM projects
    ORDER BY projectid
") or die(mysql_error());

while ( list($projectid) = mysql_fetch_row($project_res) )
{
    update_table( PART_OF_COL, $projectid, 'state', TRUE );
}

// ---------------------------------------------------------

if ( count($already_appears) > 0 )
{
    echo "
The following cases were skipped because '$dst_round_id' was
found in the column.  This may just be because you've already
run this script, and these cases have already been handled.
But if not, you should determine whether the pre-existing
occurrence(s) of '$dst_round_id' will cause an ambiguity
if/when the occurences of '$src_round_id' are changed.
";
    foreach( $already_appears as $case )
    {
        echo "    $case\n";
    }
}

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
