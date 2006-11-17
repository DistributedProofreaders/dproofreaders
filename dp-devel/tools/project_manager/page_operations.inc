<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'page_tally.inc');

// -----------------------------------------------------------------------------

function page_del( $projectid, $image )
{
    $result = mysql_query("
        SELECT image
        FROM $projectid
        WHERE image = '$image'
    ");
    if ( mysql_num_rows($result) == 0 )
    {
        return _("There is no page with that imagename.");
    }

    global $pguser;
    Page_delete( $projectid, $image, $pguser );

    // Delete the image file from the project directory.
    global $projects_dir;
    $image_path = "$projects_dir/$projectid/$image";
    if ( ! unlink($image_path) )
    {
        return sprintf( _('Unable to remove file %s'), $image_path );
    }
}

// -----------------------------------------------------------------------------

function page_clear( $projectid, $image )
{
    // echo "page_clear( $projectid, $image )<br>\n";

    $sql = mysql_query("SELECT state FROM projects WHERE projectid = '$projectid'");
    $projstate = mysql_result($sql, 0, "state");

    $round = get_Round_for_project_state($projstate);

    if (is_null($round))
    {
        return _("The project is not in a state that allows pages to be cleared.");
    }

    $result = mysql_query("
        SELECT state, {$round->user_column_name}
        FROM $projectid
        WHERE image = '$image'
    ");
    if ( mysql_num_rows($result) == 0 )
    {
        return _("There is no page with that imagename.");
    }
    list($page_state, $proofer) = mysql_fetch_row($result);
    if ( $page_state != $round->page_save_state )
    {
        return _("The page is not in a state that allows it to be cleared.");
    }

    // ------------------------------------------------
    // page will be cleared, so decrement page tallies for user & teams

    page_tallies_add( $round->id, $proofer, -1 );

    // ------------------------------------------------
    // now clear the page

    global $pguser;
    Page_clearRound( $projectid, $image, $round, $pguser );
}

?>
