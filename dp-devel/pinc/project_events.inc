<?PHP

function log_project_event( $projectid, $who, $event_type, $details1='', $details2='', $details3='' )
{
    $esc_details1 = mysql_escape_string($details1);
    $esc_details2 = mysql_escape_string($details2);
    $esc_details3 = mysql_escape_string($details3);

    $res = mysql_query("
        INSERT INTO project_events
        SET
            timestamp  = UNIX_TIMESTAMP(),
            projectid  = '$projectid',
            who        = '$who',
            event_type = '$event_type',
            details1   = '$esc_details1',
            details2   = '$esc_details2',
            details3   = '$esc_details3'
    ");
    if ( !$res )
    {
        return mysql_error();
    }
    else
    {
        return '';
    }
}

// vim: sw=4 ts=4 expandtab
?>
