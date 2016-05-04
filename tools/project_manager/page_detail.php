<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once('page_table.inc');

require_login();

$projectid = validate_projectID('project', @$_GET['project']);
$show_image_size = get_integer_param($_GET,'show_image_size',0,0,1);

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

// this only has any effect if the user is set too.
$round_for_page_selection = NULL;
if ( isset($_GET['select_by_round']) )
{
    $sbr = $_GET['select_by_round'];
    if ( !empty($sbr) && $sbr != 'ALL' )
    {
        $round_for_page_selection = $sbr;
    }
}

$state = $project->state;
$title = $project->nameofwork;
$page_details_str = _('Page Detail');

output_header( "$page_details_str: $title", NO_STATSBAR);

echo "<h1>$title</h1>\n";
echo "<h2>$page_details_str</h2>\n";

$url = "$code_url/project.php?id=$projectid&amp;expected_state=$state";
$label = _("Return to Project Page");

echo "<p><a href='$url'>$label</a></p>\n";

if ($project->pages_table_exists)
{
    include_once('detail_legend.inc');

    echo "<p>" . _("It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab.") . "</p>";

    echo "<p>";
    if ( !is_null($username_for_page_selection) )
    {
        if (is_null($round_for_page_selection) )
        {
            echo sprintf( _("Showing only the pages of user '%s'."), 
                          $username_for_page_selection );
        }
        else
        {
            echo sprintf( _("Showing only the pages of user '%s' in round %s."), 
                          $username_for_page_selection, 
                          $round_for_page_selection );
        }
        $blurb = _("Show all pages instead.");
        echo "&nbsp;&nbsp;";
        echo "<a href='?project=$projectid&show_image_size=$show_image_size'>$blurb</a>";
    }
    else
    {
       $blurb = _("Show just my pages.");
       echo "<a href='?project=$projectid&show_image_size=$show_image_size&select_by_user'>$blurb</a>";
    }
    echo "</p>";

    echo_page_table( $project, $show_image_size, FALSE, $username_for_page_selection, $round_for_page_selection );
} else {
    echo "<p>";
    if ($project->archived != 0) {
        echo _("The project has been archived, so page details are not available.");
    } elseif ($project->state == PROJ_DELETE) {
        echo _("The project has been deleted, so page details are not available.");
    } else {
        echo _("Page details are not available for this project.");
    }
    echo "</p>";
}
echo "<br>";

// vim: sw=4 ts=4 expandtab
?>

