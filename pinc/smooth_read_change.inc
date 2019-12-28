<?php
include_once($relPath.'user_project_info.inc'); // notify_project_event_subscribers()

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
        WHERE projectid = '$projectid'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $postcomments));
    mysqli_query(DPDatabase::get_connection(), $qstring);

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
