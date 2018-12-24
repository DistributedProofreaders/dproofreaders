<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'User.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'js_newwin.inc');

require_login();

$username = $pguser;
if ( user_is_a_sitemanager() || user_is_proj_facilitator() )
{
    $username = array_get( $_GET, 'username', $pguser );
}

if(!User::is_valid_user($username))
{
    die("Incorrect parameter.");
}

if ( $username == $pguser )
{
    $out_title = _("My Projects");
    $proof_text = sprintf( _("%s, here's a list of the projects you've helped format and/or proof"), $username );
    $reserved_text = sprintf( _("%s, these projects are reserved for you to post-process"), $username );
}
else
{
    $out_title = sprintf(_("%s's Projects"), $username);
    $proof_text = sprintf( _("Projects that '%s' has worked on"), $username );
    $reserved_text = sprintf( _("These projects are reserved for '%s' to post-process"), $username );
}

$sorting = array_get($_GET, 'sort', '');

output_header($out_title, NO_STATSBAR);

if ( user_is_a_sitemanager() || user_is_proj_facilitator() )
{
    echo "<form action='#' method='get'><p>";
    echo _("See projects that another user has worked on") . ": ";
    echo "<input type='text' name='username' value='$username'>";
    echo "<input type='submit' value='" . attr_safe(_("Refresh")) . "'>";
    echo "</p></form>\n";
}

$proof_heading = _("Proofreading &amp; Formatting Projects");
echo "<h2 id='proof'>$proof_heading</h2>";
echo "<p>$proof_text</p>";

// ---------------

// $colspecs = array ( 
//     $id => array ( 'label' => $label, 'sql' => $sql ) 
// );
// $id is the column name as passed by GET argument.
// $label is the translatable label displayed in the column header
// $sql is the SQL collater expression, or NULL for unsortable columns.

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
    /*
    'round' =>
        array(
            'label' => _('Round Worked In'),
            'sql'   => sql_collater_for_round_id('user_project_info.round_id'),
        ),
    */
    'time' =>
        array(
            'label' => _('Time of Last Activity'),
            'sql'   => 'max_timestamp',
        ),
);

// By default, order by time of last activity, descending.
$default_order_col = $order_col = 'time';
$default_order_dir = $order_dir = 'D';

if ($sorting == 'proof')
{
    list( $order_col, $order_dir ) = get_sort_col_and_dir();
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
        user_project_info.projectid,
        -- user_project_info.round_id,
        user_project_info.t_latest_page_event AS max_timestamp,
        projects.nameofwork,
        projects.state,
        projects.deletion_reason
    FROM user_project_info LEFT OUTER JOIN projects USING (projectid)
    WHERE user_project_info.username='$username'
        AND user_project_info.t_latest_page_event > 0
        AND projects.archived = 0
    ORDER BY $sql_order
") or die('Aborting');

echo "<table class='themed theme_striped' style='width: auto;'>";

show_headings($colspecs, 'proof', $username);

$n_rows_displayed = 0;
while ( $row = mysqli_fetch_object($res) )
{
    if ( $row->state == PROJ_DELETE)
    {
        // it's been deleted. see if it's been merged into another one.
        if (str_contains($row->deletion_reason, 'merged') &&
            (1 == preg_match('/\b(projectID[0-9a-f]{13})\b/', 
                             $row->deletion_reason, $matches)))
        {
            // get the dope from the project it was merged into
            $projectid = $matches[0];
            $project = new Project($projectid);
            $state = $project->state;
            $nameofwork = $project->nameofwork;
            if ($project->archived == '1')
            {
                // The project it was merged into has been archived.
                // So skip it.
                continue;
            }
            $orig_nameofwork = $row->nameofwork;
        }
        else
        {
            // deleted but not merged. We are not interested.
            continue;
        }
    }
    else
    {
        // nothing special. Just the straight dope.
        $projectid = $row->projectid;
        $state = $row->state;
        $nameofwork = $row->nameofwork;
        $orig_nameofwork = '';
    }

    echo "<tr>\n";

    echo "<td>";
    if ($orig_nameofwork != '') {
        // say where this information came from
        echo $orig_nameofwork . " <i>" .  _("merged into") . "</i> ";
    }
    $url = "$code_url/project.php?id=$projectid";
    $onclick_attr = get_onclick_attr_for_link_to_project_page($url);
    echo "<a href='$url' $onclick_attr>$nameofwork</a>";
    echo "</td>\n";

    echo "<td nowrap>";
    echo get_medium_label_for_project_state( $state );
    echo "</td>\n";

    echo "<td nowrap>";
    echo strftime( '%Y-%m-%d %H:%M:%S', $row->max_timestamp );
    echo "</td>\n";

    echo "</tr>\n";

    $n_rows_displayed++;
}

echo "</table>\n";

echo sprintf(_("(%d projects)"), $n_rows_displayed );
echo "<br>\n";

// -----------------------------------------------------------------------------

unset($colspecs);

$colspecs = array(
    'title' =>
        array(
            'label' => _('Title'),
            'sql'   => 'nameofwork',
        ),
    'manager' =>
        array(
            'label' => _('Project Manager'),
            'sql'   => 'username',
        ),
    'state' =>
        array(
            'label' => _('Current State'),
            'sql'   => sql_collater_for_project_state('state'),
        )
);

// By default, order by state, descending.
$default_order_col = $order_col = 'state';
$default_order_dir = $order_dir = 'D';

if ($sorting == 'reserved')
{
    list( $order_col, $order_dir ) = get_sort_col_and_dir();
}

$sql_order = sql_order_spec( $order_col, $order_dir );

if ( $order_col != $default_order_col )
{
    // Add the default ordering as a secondary ordering.
    $sql_order .= ", " . sql_order_spec( $default_order_col, $default_order_dir );
}

// We're interested in projects that have been created, but haven't *finished*
// being proofread.
$psd = get_project_status_descriptor('created');
$antipsd = get_project_status_descriptor('proofed');

$query = "
	SELECT
         projectid,
         nameofwork,
         username,
		 state
	FROM projects
	WHERE checkedoutby='$username'
		AND $psd->state_selector
		AND NOT $antipsd->state_selector
	ORDER BY $sql_order";

$result = dpsql_query($query);

$num_projects = mysqli_num_rows($result);
if($num_projects > 0)
{
    $reserved_heading = _("Projects Reserved for Post-Processing");
    echo "<h2 id='reserved'>$reserved_heading</h2>\n";
    echo "<p>$reserved_text</p>";

    echo "<table class='themed theme_striped' style='width: auto;'>";

    show_headings($colspecs, 'reserved', $username);

    while ( $row = mysqli_fetch_object($result) )
    {
        echo "<tr>\n";

        echo "<td>";
        echo "<a href='$code_url/project.php?id=$row->projectid'>$row->nameofwork</a>";
        echo "</td>\n";

        echo "<td class='center-align'>";
        echo $row->username;
        echo "</td>\n";

        echo "<td nowrap>";
        echo get_medium_label_for_project_state( $row->state );
        echo "</td>\n";

        echo "</tr>\n";
    }

    echo "</table>\n";

    echo sprintf("(%d projects)", $num_projects);
    echo "<br>\n";
}

// -----------------
function get_sort_col_and_dir()
{
    global $colspecs,$default_order_col, $default_order_dir;
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
    return array($order_col,$order_dir);
}

function show_headings($colspecs, $sort_type, $username)
{
    global $order_dir, $order_col, $pguser;
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
        $qs_username = "";
        if($username != $pguser)
            $qs_username = "username=" . urlencode($username) . '&amp;';
        echo "<a href='?{$qs_username}order_col=$col_id&amp;order_dir=$link_dir&amp;sort=$sort_type#$sort_type'>";
        echo $colspec['label'];
        echo "</a>";
        echo "</th>";
    }
    echo "</tr>\n";
}
// vim: sw=4 ts=4 expandtab
