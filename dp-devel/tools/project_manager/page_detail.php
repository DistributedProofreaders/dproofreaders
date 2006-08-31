<?
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once('page_table.inc');

$projectid = @$_GET['project'];
$show_image_size = array_get( $_GET, 'show_image_size', 0 );

$project = new Project( $projectid );

if ( isset($_GET['select_by_user']) )
{
    $sbu = $_GET['select_by_user'];
    if ( empty($sbu) )
    {
        // Show just the current user's pages.
        $username_for_page_selection = $pguser;
    }
    else
    {
        // Explicitly specify a particular user.
        // This is only available to PFs & SAs.
        // (Yes, even though it merely filters
        // information that is available to all.)
        if ( user_is_a_sitemanager() || user_is_proj_facilitator() )
        {
            $username_for_page_selection = $sbu;
        }
        else
        {
            // Just show the current user's pages.
            $username_for_page_selection = $pguser;
        }
    }
}
else
{
    // No 'select_by_user' parameter, so show all pages.
    $username_for_page_selection = NULL;
}

$state = $project->state;
$title = $project->nameofwork;
$page_details_str = _('Page Details');

$no_stats = 1;
theme( "$page_details_str: $title", 'header' );

echo "<h1>$title</h1>\n";
echo "<h2>$page_details_str</h2>\n";

$url = "$code_url/project.php?id=$projectid&amp;expected_state=$state";
$label = _("Return to Project Page");

echo "<a href='$url'>$label</a>";
echo "<br>\n";

include_once('detail_legend.inc');

echo "N.B. It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab."."<br>\n";

if ( !is_null($username_for_page_selection) )
{
    echo "<br>\n";
    echo sprintf( _("Showing only the pages of user '%s'."), $username_for_page_selection );
    $blurb = _("Show all pages instead.");
    echo "&nbsp;&nbsp;";
    echo "<a href='?project=$projectid&show_image_size=$show_image_size'>$blurb</a>";
}

echo_page_table( $project, $show_image_size, FALSE, $username_for_page_selection );

echo "<br>";
theme( '', 'footer' );

// vim: sw=4 ts=4 expandtab
?>

