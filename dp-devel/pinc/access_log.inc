<?
include_once($relPath."connect.inc");
include_once($relPath."f_dpsql.inc");


function log_access_change( $subject_username, $modifier_username, $activity_id, $grant_or_revoke )
{
  $result = dpsql_query(
    "INSERT INTO access_log
       SET
         timestamp = '" . time() . "',
         subject_username = '$subject_username',
         modifier_username = '$modifier_username',
         action = '$grant_or_revoke',
         stage = '$activity_id'");

  return $result;
}

function get_first_granted_date($username, $stage)
{
  $result = dpsql_query(
             "SELECT timestamp
              FROM access_log
              WHERE subject_username = '$username'
              AND stage = '$stage'
              AND action = 'grant'
              ORDER BY timestamp ASC
              LIMIT 1");

  $date = @mysql_result($result,0,"timestamp");

  return $date;
}
?>
