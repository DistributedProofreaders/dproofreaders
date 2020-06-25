<?php
$relPath='../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');

require_login();

if (!(user_is_a_sitemanager() || user_is_an_access_request_reviewer())) die("permission denied");

$title = _('Pending Access Requests');

output_header($title);

echo "<h1>$title</h1>\n";

foreach ( $Activity_for_id_ as $activity )
{
    if ( $activity->after_satisfying_minima == 'REQ-HUMAN' )
    {
        $activity_ids[] = $activity->id;
    }
}

// Look for unexpected activity_ids
$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT DISTINCT REPLACE(setting,'.access', '')
    FROM usersettings
    WHERE setting LIKE '%.access' AND value='requested'
") or die(DPDatabase::log_error());
while ( list($activity_id) = mysqli_fetch_row($res) )
{
    if ( !in_array( $activity_id, $activity_ids ) )
    {
        $activity_ids[] = $activity_id;
    }
}

// ----------------------------------

mysqli_query(DPDatabase::get_connection(), "
    CREATE TEMPORARY TABLE access_log_summary
    SELECT 
        activity,
        subject_username,
        MAX( timestamp * (action='request'         ) ) AS t_latest_request,
        MAX( timestamp * (action='deny_request_for') ) AS t_latest_deny
    FROM access_log
    GROUP BY activity, subject_username
") or die(DPDatabase::log_error());

foreach ( $activity_ids as $activity_id )
{
    echo "<h3>";
    echo sprintf( _('Users requesting access to %s'), $activity_id );
    echo "</h3>\n";

    $access_name = "$activity_id.access";

    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT
            usersettings.username,
            users.u_id,
            access_log_summary.t_latest_request,
            access_log_summary.t_latest_deny,
            users.t_last_activity
        FROM usersettings
            LEFT OUTER JOIN users USING (username)
            LEFT OUTER JOIN access_log_summary ON (
                access_log_summary.subject_username = usersettings.username
                AND
                access_log_summary.activity = '$activity_id'
            )
        WHERE setting = '$access_name' AND value='requested'
        ORDER BY username
    ") or die(DPDatabase::log_error());

    if ( mysqli_num_rows($res) == 0 )
    {
        $word = pgettext("no user", "none");
        echo "<i>$word</i>";
    }
    else
    {
        $review_round = get_Round_for_round_id($activity_id);
        if ( $review_round && $review_round->after_satisfying_minima == 'REQ-HUMAN' )
        {
            $can_review_work = TRUE;
            // These users are all requesting access to round Y.  For each, we will
            // provide a link to allow the requestor to review their round X work,
            // by considering each page they worked on in X, and comparing
            // their X result to the subsequent Y result (if it exists yet).
            //
            // (We assume that X is the round immediately preceding Y.)
            $work_round = get_Round_for_round_number($review_round->round_number-1);

            $round_params = "work_round_id={$work_round->id}&amp;review_round_id={$review_round->id}";
        }
        else
        {
            $can_review_work = FALSE;
        }

        echo "<table class='basic striped'>\n";

        {
            echo "<tr>";
            echo "<th>"._("Username")."</th>";
            if ( $can_review_work )
            {
                echo "<th>"._("Review Work")."</th>";
            }
            echo "<th>"._("Requested")."</th>";
            echo "<th>"._("Last denied")."</th>";
            echo "<th>"._("User Last on Site")."</th>";
            echo "</tr>";
            echo "\n";
        }
        $seconds = 60 * 60 * 24;
        $now = time();
        $tformat = '%Y-%m-%d';
        while ( list($username, $u_id, $t_latest_request, $t_latest_deny, $t_last_on_site) = mysqli_fetch_row($res) )
        {
            $member_stats_url = "$code_url/stats/members/mdetail.php?id=$u_id";
            $t_latest_request_f = strftime($tformat, $t_latest_request);
            $t_latest_request_d = round(($now - $t_latest_request) / $seconds);
            $t_latest_deny_f = '';
            $t_latest_deny_d = -1;
            if ($t_latest_deny != 0) 
            {
                $t_latest_deny_f = strftime($tformat, $t_latest_deny);
                $t_latest_deny_d = round(($now - $t_latest_deny) / $seconds);
            }
            $t_last_on_site_f = strftime($tformat, $t_last_on_site);
            $t_last_on_site_d = round(($now - $t_last_on_site) / $seconds);

            echo "<tr>";
            echo   "<td>";
            echo     "<a href='$member_stats_url'>$username</a>";
            echo   "</td>";
            if ( $can_review_work )
            {
                $review_work_url = "$code_url/tools/proofers/review_work.php?username=$username&amp;$round_params";
                echo   "<td>";
                echo     "<a href='$review_work_url'>", _("Review Work"), "</a>";
                echo   "</td>";
            }
            echo   "<td>";
            echo     $t_latest_request_f;
            echo " <span style='white-space: nowrap'>(",
                sprintf(_("%s days"), $t_latest_request_d), ")</span>";
            echo   "</td>";
            echo   "<td>";
            echo     $t_latest_deny_f;
            if ($t_latest_deny_d >= 0) 
            {
                echo " <span style='white-space: nowrap'>(",
                    sprintf(_("%s days"), $t_latest_deny_d), ")</span>";
            }
            echo   "</td>";
            echo   "<td>";
            echo     $t_last_on_site_f;
            echo " <span style='white-space: nowrap'>(",
                sprintf(_("%s days"), $t_last_on_site_d), ")</span>";
            echo   "</td>";
            echo "</tr>";
            echo "\n";
        }
        echo "</table>\n";
    }
}

echo '<br>';

// vim: sw=4 ts=4 expandtab
