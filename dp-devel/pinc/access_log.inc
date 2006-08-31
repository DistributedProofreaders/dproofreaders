<?
include_once($relPath."connect.inc");
include_once($relPath."dpsql.inc");


function log_access_change( $subject_username, $modifier_username, $activity_id, $action_type )
{
  $result = dpsql_query(
    "INSERT INTO access_log
       SET
         timestamp = '" . time() . "',
         subject_username = '$subject_username',
         modifier_username = '$modifier_username',
         action = '$action_type',
         activity = '$activity_id'");

  return $result;
}

function get_first_granted_date($username, $stage)
{
  $result = dpsql_query(
             "SELECT timestamp
              FROM access_log
              WHERE subject_username = '$username'
              AND activity = '$stage'
              AND action = 'grant'
              ORDER BY timestamp ASC
              LIMIT 1");

  $date = @mysql_result($result,0,"timestamp");

  return $date;
}
?>
