<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'f_project_states.inc');

// -----------------------------------------------------------------------------

function page_delete( $projectid, $fileid )
{
    global $writeBIGtable;

    if ($fileid == '')
    {
        $page_selector = '1';
    }
    else
    {
        $page_selector = "fileid = '$fileid'";
    }

    if ($writeBIGtable) {
        $sql = "DELETE FROM project_pages WHERE projectid = '$projectid' AND $page_selector";
        mysql_query($sql);
    }

    $sql = "DELETE FROM $projectid WHERE $page_selector";
    echo "$sql<br>\n";
    mysql_query($sql);
}

// -----------------------------------------------------------------------------

function page_clear( $projectid, $fileid )
{
    global $writeBIGtable;

    // echo "page_clear( $projectid, $fileid )<br>\n";

    $sql = mysql_query("SELECT state FROM projects WHERE projectid = '$projectid'");
    $projstate = mysql_result($sql, 0, "state");

    $inRound = projectStateRound($projstate);

    if ($inRound == 'NEW' || $inRound == 'PR' || $inRound == 'FIRST')
    {
        $round_number = 1;
        $text_field_name = 'round1_text';
        $user_field_name = 'round1_user';
        $time_field_name = 'round1_time';
        $saved_page_state = SAVE_FIRST;
        $avail_page_state = AVAIL_FIRST;
    }
    else if ($inRound == 'SECOND')
    {
        $round_number = 2;
        $text_field_name = 'round2_text';
        $user_field_name = 'round2_user';
        $time_field_name = 'round2_time';
        $saved_page_state = SAVE_SECOND;
        $avail_page_state = AVAIL_SECOND;
    }
    else
    {
        return _("The project is not in a state that allows pages to be cleared.");
    }

    $result = mysql_query("
        SELECT state
        FROM $projectid
        WHERE fileid = '$fileid'
    ");
    if ( mysql_num_rows($result) == 0 )
    {
        return _("There is no page with that fileid.");
    }
    $data = mysql_fetch_array($result);
    $page_state = $data[0];
    if ( $page_state != $saved_page_state )
    {
        return _("The page is not in a state that allows it to be cleared.");
    }

    // ------------------------------------------------
    // if page is cleared, decrement user's page count

    $result = mysql_query("
        SELECT $user_field_name
        FROM $projectid
        WHERE fileid = '$fileid'
    ");
    $data = mysql_fetch_array($result);
    $proofer = $data[0];

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

    if ($writeBIGtable) {
        $result = mysql_query("
            UPDATE project_pages
            SET $text_field_name='',
                $user_field_name='',
                $time_field_name='',
                state = '$avail_page_state'
            WHERE projectid = '$projectid' AND fileid = '$fileid'
        ");
    }

    $result = mysql_query("
        UPDATE $projectid
        SET $text_field_name='',
            $user_field_name='',
            $time_field_name='',
            state = '$avail_page_state'
        WHERE fileid = '$fileid'
    ");
}

?>
