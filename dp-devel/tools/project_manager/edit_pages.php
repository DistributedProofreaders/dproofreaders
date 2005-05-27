<?PHP
$relPath = '../../pinc/';
include_once($relPath.'theme.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'Project.inc');
include_once('page_table.inc');
include_once('page_operations.inc');

$no_stats=1;
theme( _("Edit Pages Confirmation"), "header");
echo "<br>\n";

// -----------------------------------------------------------------------------
// Check for required parameters.

$do_exit = 0;
foreach ( array('projectid','selected_pages','operation') as $required_param )
{
    if ( !isset($_REQUEST[$required_param]) )
    {
        echo "Error: edit_pages.php: '$required_param' parameter is not set.<br>";
        $do_exit = 1;
    }
}
if ( $do_exit )
{
    echo "<br>\n";
    theme("","footer");
    exit;
}

$projectid      = $_REQUEST['projectid'];
$selected_pages = $_REQUEST['selected_pages'];
$operation      = $_REQUEST['operation'];

$project = new Project( $projectid );

abort_if_cant_edit_project( $projectid );

// -----------------------------------------------------------------------------
// Check the set of selected pages.

if ( count($selected_pages) == 0 )
{
    echo _("You did not select any pages.") . "<br>\n";
    echo "<br>\n";
    theme("","footer");
    exit;
}

// -----------------------------------------------------------------------------
// Check the requested operation.

switch ( $operation )
{
    /*
    Not implemented.
    Marking a page bad requires a reason.
    case 'bad':
        $your_request = _('You requested that each page be marked bad.');
    break;
    */

    case 'clear':
        $your_request = _('You requested that each page have the effects of its current round be cleared.');
        $page_func = 'page_clear';
    break;

    case 'delete':
        $your_request = _('You requested that each page be deleted.');
        $page_func = 'page_del';
    break;

    default:
        echo _("Error: unexpected 'operation' value:") . " '$operation'.<br>\n";
        echo "<br>\n";
        theme("","footer");
        exit;
    break;
}

// -----------------------------------------------------------------------------

if ( isset($_REQUEST['confirmed']) and $_REQUEST['confirmed'] == 'yes' )
{
    // Perform the operation.

    foreach ( $selected_pages as $fileid => $setting )
    {
        // Ignore $setting, it's always 'on'.
        echo "fileid=$fileid:<br>\n";
        $err = $page_func( $projectid, $fileid );
        echo ( $err ? $err : "success" );
        echo "<br>\n";
        echo "<br>\n";
    }
    echo "<a href='$code_url/project.php?id=$projectid&amp;detail_level=4'>Project Page</a><br>\n";
}
else
{
    // Obtain confirmation

    echo _("You selected the following page(s):") . "<br>\n";
    echo "<br>\n";
    echo_page_table( $project, 0, TRUE, $selected_pages );
    echo "<br>\n";
    echo "$your_request<br>\n";
    echo "<br>\n";

    echo "<form method='post' action='edit_pages.php'>\n";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<input type='hidden' name='operation' value='$operation'>\n";
    foreach ( $selected_pages as $fileid => $setting )
    {
        echo "<input type='hidden' name='selected_pages[$fileid]' value='$setting'>\n";
    }
    echo "<input type='hidden' name='confirmed' value='yes'>\n";
    echo "<input type='submit' value='" . _("Do it") . "'>\n";
    echo "<br>\n";
}

echo "<br>\n";
theme("","footer");

// vim: sw=4 ts=4 expandtab
?>
