<?PHP
$relPath='../../pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'stages.inc');

$qs_username = '';
if ( user_is_a_sitemanager() || user_is_proj_facilitator() )
{
    $username = array_get( $_GET, 'username', $pguser );
    if ( $username != $pguser )
    {
        $qs_username = "username=" . urlencode($username) . '&amp;';
    }
}
else
{
    $username = $pguser;
}

$out_title = _("My Projects");
$in_title = sprintf( _("Projects that user '%s' has worked on"), $username );

$no_stats = 1;
theme( $out_title, 'header' );

echo "<h1>$in_title</h1>";

// ---------------

$colspecs = array(
    'title' =>
        array(
            'label' => _('Title'),
            'sql'   => 'projects.nameofwork',
        ),
    'state' =>
        array(
            'label' => _('Current State'),
            'sql'   => sql_collater_for_project_state('projects.state'),
        ),
    'round' =>
        array(
            'label' => _('Round Worked In'),
            'sql'   => sql_collater_for_round_id('page_events.round_id'),
        ),
    'time' =>
        array(
            'label' => _('Time of Last Activity'),
            'sql'   => 'max_timestamp',
        ),
);

// By default, order by time of last activity, descending.
$default_order_col = 'time';
$default_order_dir = 'D';

$order_col = array_get( $_GET, 'order_col', $default_order_col );
$order_dir = array_get( $_GET, 'order_dir', $default_order_dir );

if ( !isset( $colspecs[$order_col] ) )
{
    echo "Invalid order_col parameter: '$order_col'. Assuming '$default_order_col'.<br>\n";
    $order_col = $default_order_col;
}

if ( $order_dir != 'A' && $order_dir != 'D' )
{
    echo "Invalid order_dir parameter: '$order_dir'. Assuming '$default_order_dir'.<br>\n";
    $order_dir = $default_order_dir;
}

// ------------------------

function sql_order_spec( $order_col, $order_dir )
{
    global $colspecs;
    return
        $colspecs[$order_col]['sql']
        . ' '
        . ( $order_dir == 'A' ? 'ASC' : 'DESC' );
}

$sql_order = sql_order_spec( $order_col, $order_dir );

if ( $order_col != $default_order_col )
{
    // Add the default ordering as a secondary ordering.
    $sql_order .= ", " . sql_order_spec( $default_order_col, $default_order_dir );
}

$res = dpsql_query("
    SELECT
        page_events.projectid,
        page_events.round_id,
        MAX(page_events.timestamp) AS max_timestamp,
        projects.nameofwork,
        projects.state
    FROM page_events LEFT OUTER JOIN projects USING (projectid)
    WHERE page_events.username='$username'
        AND page_events.event_type IN ('saveAsDone','saveAsInProgress', 'markAsBad')
        AND projects.archived = 0
    GROUP BY page_events.projectid, page_events.round_id
    ORDER BY $sql_order
") or die('Aborting');

echo "<table border='1'>";

echo "<tr>\n";
foreach ( $colspecs as $col_id => $colspec )
{
    if ( $col_id == $order_col )
    {
        // This is the column on which the table is being sorted.
        // If the user clicks on this column-header, the result should be
        // the table, sorted on this column, but in the opposite direction.
        $link_dir = ( $order_dir == 'A' ? 'D' : 'A' );
    }
    else
    {
        // This is not the column on which the table is being sorted.
        // If the user clicks on this column-header, the result should be
        // the table, sorted on this column, in ascending order.
        $link_dir = 'A';
    }
    echo "<th>";
    echo "<a href='?{$qs_username}order_col=$col_id&amp;order_dir=$link_dir'>";
    echo $colspec['label'];
    echo "</a>";
    echo "</th>";
}
echo "</tr>\n";

while ( $row = mysql_fetch_object($res) )
{
    echo "<tr>\n";

    echo "<td>";
    echo "<a href='$code_url/project.php?id=$row->projectid'>$row->nameofwork</a>";
    echo "</td>\n";

    echo "<td nowrap>";
    echo project_states_text( $row->state );
    echo "</td>\n";

    echo "<td align='center'>";
    echo $row->round_id;
    echo "</td>\n";

    echo "<td nowrap>";
    echo strftime( '%Y-%m-%d %H:%M:%S', $row->max_timestamp );
    echo "</td>\n";

    echo "</tr>\n";
}

echo "</table>\n";

echo "<br>\n";
theme( '', 'footer' );

// vim: sw=4 ts=4 expandtab
?>
