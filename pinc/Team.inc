<?php

// This is an incomplete abstration around the Teams

class Team
{
    // static functions

    public static function log_recent_join($tid, $uid)
    {
        $sql = sprintf(
            "
            UPDATE user_teams
            SET latestUser = %d, member_count = member_count+1
            WHERE id = %d
            ",
            $uid,
            $tid
        );

        DPDatabase::query($sql);
    }

    public static function active_member_count($tid)
    {
        $sql = sprintf(
            "
            SELECT count(*)
            FROM user_teams_membership
            WHERE t_id = %d
            ",
            $tid
        );

        $result = DPDatabase::query($sql);
        [$count] = mysqli_fetch_row($result);
        mysqli_free_result($result);
        return $count;
    }
}
