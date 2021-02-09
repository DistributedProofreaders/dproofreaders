<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'misc.inc');  // attr_safe()
include_once($relPath.'user_project_info.inc'); // notify_project_event_subscribers()

/***************************************************************************************
*
* Functions for Smooth Reading commitment functionality
*
* At this point the functionality does not require the usage of a class
* nor make it more convenient. Future extensions might change this assessment.
*
****************************************************************************************/

/***************************************************************************************
*
* Data access functions
*
****************************************************************************************/

/***************************************************************************************
*
* function sr_users_is_commited: checks the association table for existing commitment
*
* inputs: projectid, username
*
* output: true/false regarding existence of commitment
*
* Remarks:
*
* Currently only a check for existence of record and field being > 0 is done. The latter
* would allow the extension for a timestamp and the resetting to 0 if revoked in order
* to still show users that have once committed but then revoked that commitment.
*
****************************************************************************************/

function sr_user_is_committed($projectid, $username)
{
    $sql = sprintf("
        SELECT count(*)
        FROM smoothread
        WHERE projectid = '%s'
            AND user = '%s'
            AND committed > 0
        ", DPDatabase::escape($projectid), DPDatabase::escape($username));
    $res = DPDatabase::query($sql);
    list($count) = mysqli_fetch_row($res);

    return $count > 0;
}


/***************************************************************************************
*
* function sr_commit: inserts record into the association table for commitment
*
* inputs: projectid, username
*
* output: none
*
* Remarks:
*
* Currently the record is only inserted with the committed field being set to one.
* This allows the future extension for a timestamp and the resetting to 0 if revoked in
* order to still show users that have once committed but then revoked that commitment.
*
****************************************************************************************/

function sr_commit($projectid, $username)
{
    $sql = sprintf("
        INSERT INTO smoothread
        SET projectid='%s', user='%s', committed=1
        ON DUPLICATE KEY UPDATE
            committed=1
        ", DPDatabase::escape($projectid), DPDatabase::escape($username));
    DPDatabase::query($sql);

}

/***************************************************************************************
*
* function sr_withdraw_commitment: deletes record from the association table for commitment
*
* inputs: projectid, username
*
* output: none
*
* Remarks:
*
* Currently the record is of commitment is deleted.
*
****************************************************************************************/

function sr_withdraw_commitment($projectid, $username)
{
    $sql = sprintf("
        DELETE FROM smoothread
        WHERE projectid = '%s'
            AND user = '%s'
        ", DPDatabase::escape($projectid), DPDatabase::escape($username));
    DPDatabase::query($sql);

}

/***************************************************************************************
*
* function sr_get_committed_users: provide list of users with sr-commitment to project
*
* inputs: projectid
*
* output: Array of usernames
*
* Remarks:
*
****************************************************************************************/

function sr_get_committed_users($projectid)
{
    $sql = sprintf("
        SELECT user
        FROM smoothread
        WHERE projectid = '%s'
            AND committed > 0
        ORDER BY user
        ", DPDatabase::escape($projectid));
    $res = DPDatabase::query($sql);

    $list = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $list[] = $row['user'];
    }

    return $list;
}

/***************************************************************************************
*
* function sr_number_users_committed: provide number of users with sr-commitment to project
*
* inputs: projectid
*
* output: number of users
*
* Remarks: 
*
****************************************************************************************/

function sr_number_users_committed($projectid)
{
    return count(sr_get_committed_users($projectid));
}

/***************************************************************************************
*
* User interface functions
*
****************************************************************************************/

/***************************************************************************************
*
* function sr_echo_commitment_form: create button and call page for database access
*
* inputs: projectid
*
* output: none
*
* Remarks:
*
* This calls a transient page executing the database function for inserting commitment 
* and provides the current URI for return to current page.
*
****************************************************************************************/


function sr_echo_commitment_form($projectid)
{
    global $code_url;

    $next_url = $_SERVER['REQUEST_URI'];
    $button_text = _("Volunteer to SR");

    echo "<form method='post' style='display:inline' action='$code_url/tools/change_sr_commitment.php'>";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<input type='hidden' name='action' value='commit'>\n";
    echo "<input type='hidden' name='next_url' value='$next_url'>\n";
    echo "<input type='submit' value='" . attr_safe($button_text) . "'>";
    echo "</form>\n";
}

/***************************************************************************************
*
* function sr_echo_withdrawal_form: create button and call page for database access
*
* inputs: projectid
*
* output: none
*
* Remarks:
*
* This calls a transient page executing the database function for withdrawing a commitment 
* and provides the current URI for return to current page.
*
****************************************************************************************/

function sr_echo_withdrawal_form($projectid)
{
    global $code_url;

    $next_url = $_SERVER['REQUEST_URI'];
    $button_text = _("Withdraw from SR");

    echo "<form method='post' style='display:inline' action='$code_url/tools/change_sr_commitment.php'>";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<input type='hidden' name='action' value='withdraw'>\n";
    echo "<input type='hidden' name='next_url' value='$next_url'>\n";
    echo "<input type='submit' value='" . attr_safe($button_text) . "'>";
    echo "</form>\n";

}

/***************************************************************************************
*
* Record and notify for smooth reading upload and deadline extension
* which do not involve a state change
*
****************************************************************************************/

function handle_smooth_reading_change($project, $postcomments, $days, $extend)
{
    global $pguser, $auto_post_to_project_topic;

    $projectid = $project->projectid;

    if ($days) // will be 0 if parameter not supplied when only replacing text
    {
        $seconds = $days * 60 * 60 * 24;
        if ($extend)
        {
            // extend deadline if not yet passed
            $deadline = $project->smoothread_deadline + $seconds;
        }
        else
        {
            // if starting sr with deadline=0, or if sr ended
            $deadline = time() + $seconds;
        }
        $details1 = $extend ? "deadline extended" : "text available";
        $smoothread_deadline_sql = "smoothread_deadline = $deadline, ";
        log_project_event( $projectid, $pguser, 'smooth-reading', $details1, $deadline );
    }
    else
    {
        $smoothread_deadline_sql = "";
        log_project_event( $projectid, $pguser, 'smooth-reading', 'text replaced' );
    }

    $qstring = sprintf("
        UPDATE projects
        SET $smoothread_deadline_sql
            postcomments = CONCAT(postcomments, '%s')
        WHERE projectid = '%s'
    ", DPDatabase::escape($postcomments), DPDatabase::escape($projectid));
    DPDatabase::query($qstring);

    notify_project_event_subscribers($project, 'sr_available');

    if ($auto_post_to_project_topic)
    {
        // Add an auto-post to the project's discussion topic.
        $project->ensure_topic();
        topic_add_post(
            $project->topic_id,
            "Project made available for Smooth Reading",
            "The project has just been made available for Smooth Reading for $days days."
                . "\n\n"
                . "(This post is automatically generated.)",
            '[Smooth Reading Monitor]',
            FALSE
        );
    }
}
