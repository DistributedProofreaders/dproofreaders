<?PHP
$relPath = '../../pinc/';
include_once($relPath.'theme.inc');
include_once($relPath.'project_edit.inc');
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
    if ( !isset($_GET[$required_param]) )
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

$projectid      = $_GET['projectid'];
$selected_pages = $_GET['selected_pages'];
$operation      = $_GET['operation'];


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
        $page_func = 'page_delete';
    break;

    default:
        echo _("Error: unexpected 'operation' value:") . " '$operation'.<br>\n";
        echo "<br>\n";
        theme("","footer");
        exit;
    break;
}

// -----------------------------------------------------------------------------

if ( isset($_GET['confirmed']) and $_GET['confirmed'] == 'yes' )
{
    // Perform the operation.
    if ( $selected_pages == 'ALL' )
    {
        $page_func( $projectid, NULL );
    }
    else
    {
        foreach ( $selected_pages as $fileid => $setting )
        {
            // Ignore $setting, it's always 'on'.
            echo "fileid=$fileid:<br>\n";
            $err = $page_func( $projectid, $fileid );
            if ( $err )
            {
                echo $err;
            }
            else
            {
                echo "success";
            }
            echo "<br>\n";
            echo "<br>\n";
        }
    }
    echo "<a href='project_detail.php?project=$projectid&type=Full'>Project Details Page</a><br>\n";
}
else
{
    // Obtain confirmation

    if ( $selected_pages == 'ALL' )
    {
        echo _("You selected all pages of the project.") . "<br>\n";
    }
    else
    {
        echo _("You selected the following page(s):") . "<br>\n";
        echo "<br>\n";
        echo_page_table( $projectid, 0, TRUE, $selected_pages );
    }
    echo "<br>\n";
    echo "$your_request<br>\n";
    echo "<br>\n";
    $request_uri = $_SERVER['REQUEST_URI'];
    echo "<a href='$request_uri&confirmed=yes'>" . _("Do it.") . "</a><br>\n";
}

echo "<br>\n";
theme("","footer");

?>
