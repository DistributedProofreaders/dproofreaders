<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'page_ops.inc');

// -----------------------------------------------------------------------------

function page_del( $projectid, $fileid )
{
    // Delete the image file from the project directory.
    global $projects_dir;
    $result = mysql_query("
        SELECT image
        FROM $projectid
        WHERE fileid = '$fileid'
    ");
    if ( mysql_num_rows($result) == 0 )
    {
        return _("There is no page with that fileid.");
    }
    list($image) = mysql_fetch_row($result);
    $image_path = "$projects_dir/$projectid/$image";
    if ( ! unlink($image_path) )
    {
        return sprintf( _('Unable to remove file %s'), $image_path );
    }

    Page_delete( $projectid, $fileid );
}

// -----------------------------------------------------------------------------

function page_clear( $projectid, $fileid )
{
    // echo "page_clear( $projectid, $fileid )<br>\n";

    $sql = mysql_query("SELECT state FROM projects WHERE projectid = '$projectid'");
    $projstate = mysql_result($sql, 0, "state");

    $prd = get_PRD_for_project_state($projstate);

    if (is_null($prd))
    {
        return _("The project is not in a state that allows pages to be cleared.");
    }

    $result = mysql_query("
        SELECT state, {$prd->user_column_name}
        FROM $projectid
        WHERE fileid = '$fileid'
    ");
    if ( mysql_num_rows($result) == 0 )
    {
        return _("There is no page with that fileid.");
    }
    list($page_state, $proofer) = mysql_fetch_row($result);
    if ( $page_state != $prd->page_save_state )
    {
        return _("The page is not in a state that allows it to be cleared.");
    }

    // ------------------------------------------------
    // if page is cleared, decrement user's page count

    $sql = "
        UPDATE users
        SET pagescompleted = pagescompleted-1
        WHERE username = '$proofer'
    ";
    $result = mysql_query($sql);

    // ------------------------------------------------
    // also decrement the counts of their teams    

    $result = mysql_query("
        SELECT team_1, team_2, team_3
        FROM users
        WHERE username = '$proofer'
    ");

    $data = mysql_fetch_array($result);
    $team1 = $data['team_1'];
    $team2 = $data['team_2'];
    $team3 = $data['team_3'];

    $sql = "
        UPDATE user_teams
        SET page_count = page_count-1
        WHERE id=1 OR id=$team1 OR id=$team2 OR id=$team3
    ";
    $result = mysql_query($sql);

    // ------------------------------------------------
    // now clear the page

    Page_clearRound( $projectid, $fileid, $prd->round_number );
}

?>
