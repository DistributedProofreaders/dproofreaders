<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

require_localhost_request();

// Delete teams created more than 90 days that have no members

$old_date = time() - 7776000; // 90 days

$sql = sprintf(
    "
    SELECT id, teamname
    FROM user_teams
    WHERE
        created <= %d AND
        (
            SELECT count(*)
            FROM user_teams_membership
            WHERE t_id = user_teams.id
        ) = 0
    ",
    $old_date
);
$result = DPDatabase::query($sql);

while ([$id, $teamname] = mysqli_fetch_row($result)) {
    echo "Deleting team $teamname as it has no members after 90 days\n";
    DPDatabase::query("DELETE FROM user_teams WHERE id = $id");
}
