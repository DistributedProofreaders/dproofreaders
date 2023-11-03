<?php
include_once($relPath.'pg.inc'); // get_pg_catalog_url_for_etext
include_once($relPath.'send_mail.inc');
include_once($relPath.'forum_interface.inc'); // get_forum_email_address()

/*
The user_project_info table captures information that pertains specifically
to the (username, projectid) tuple. This includes things like project
subscriptions in addition to two timestamp fields that the site uses in
various places:

* t_latest_home_visit - timestamp of the last page visit (see project.php).
                        As of 2023-06 we only update this for users who have
                        otherwise interacted with the project, except for
                        PP/PPVers as it is used for PP/PPV notifications
                        (see post_processing.inc).
* t_latest_page_event - timestamp of the last page saved in the project
                        (see DPage.inc). Originally this recorded the last
                        proofer-initiated page event and was later changed
                        to only be updated on page save events and the
                        database updated to reflect that.
*/

function upi_set_t_latest_home_visit($username, $projectid, $timestamp, $ensure_row = false)
{
    if ($ensure_row) {
        $sql = sprintf(
            "
            INSERT INTO user_project_info
            SET
                username            = '%s',
                projectid           = '%s',
                t_latest_home_visit = %d
            ON DUPLICATE KEY UPDATE
                t_latest_home_visit = %d
            ",
            DPDatabase::escape($username),
            DPDatabase::escape($projectid),
            $timestamp,
            $timestamp
        );
    } else {
        $sql = sprintf(
            "
            UPDATE user_project_info
            SET t_latest_home_visit = %d
            WHERE username = '%s' AND projectid = '%s'
            ",
            $timestamp,
            DPDatabase::escape($username),
            DPDatabase::escape($projectid)
        );
    }
    DPDatabase::query($sql);
}

function upi_set_t_latest_page_event($username, $projectid, $timestamp)
{
    $sql = sprintf(
        "
        INSERT INTO user_project_info
        SET
            username            = '%s',
            projectid           = '%s',
            t_latest_page_event = %d
        ON DUPLICATE KEY UPDATE
            t_latest_page_event = %d
        ",
        DPDatabase::escape($username),
        DPDatabase::escape($projectid),
        $timestamp,
        $timestamp
    );
    DPDatabase::query($sql);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


function get_subscribable_project_events()
{
    $subscribable_project_events = [
        'round_available' => _("Project becomes available in a round"),
        'round_complete' => _("Project finishes a round"),
        'pp_enter' => _("Project enters Post-Processing stage"),
        'sr_available' => _("Project becomes available for Smooth Reading"),
        'sr_reported' => _("User has uploaded a Smooth Reading report"),
        'sr_complete' => _("Project finishes Smooth Reading"),
        'ppv_enter' => _("Project enters Post-Processing Verification stage"),
        'posted' => _("Project posted to Project Gutenberg"),
    ];
    return $subscribable_project_events;
}

function get_label_for_event($event)
{
    $subscribable_project_events = get_subscribable_project_events();
    $label = @$subscribable_project_events[$event];
    if (is_null($label)) {
        die("Unknown event: '$event'");
    }
    return $label;
}

// -----------------------------------------------------------------------------
// "iste" = "is subscribed to event"

function subscribe_user_to_project_event($username, $projectid, $event)
{
    set_user_project_event_subscription($username, $projectid, $event, 1);
}

function unsubscribe_user_from_project_event($username, $projectid, $event)
{
    set_user_project_event_subscription($username, $projectid, $event, 0);
}

function set_user_project_event_subscription($username, $projectid, $event, $bit)
{
    assert($bit === 0 || $bit === 1);

    $event_label = get_label_for_event($event);
    // Just to ensure that $event is valid; we don't use $event_label.

    // Now that we're sure the row exists, we can update it.
    $sql = sprintf(
        "
        INSERT INTO user_project_info
        SET
            username    = '%s',
            projectid   = '%s',
            iste_$event = %d
        ON DUPLICATE KEY UPDATE
            iste_$event = %d
        ",
        DPDatabase::escape($username),
        DPDatabase::escape($projectid),
        $bit,
        $bit
    );
    $res = DPDatabase::query($sql);
}

function user_is_subscribed_to_project_event($username, $projectid, $event)
{
    $event_label = get_label_for_event($event);
    // Just to ensure that $event is valid; we don't use $event_label.

    $sql = sprintf(
        "
        SELECT iste_$event
        FROM user_project_info
        WHERE username    = '%s'
            AND projectid = '%s'
        ",
        DPDatabase::escape($username),
        DPDatabase::escape($projectid)
    );
    $res = DPDatabase::query($sql);
    $row = mysqli_fetch_row($res);
    if (!$row) {
        // ($username,$projectid) pair does not appear in table.
        return 0;
    } else {
        return $row[0];
    }
}

function can_user_subscribe_to_project_event($username, $projectid, $event)
{
    // Disallow subscribing to SR report posted event for anyone who is not the PP for the project
    if ($event == 'sr_reported') {
        $project = new Project($projectid);
        if ($project->PPer != $username) {
            return false;
        }
    }

    return true;
}

// -----------------------------------------------------------------------------

function notify_project_event_subscribers($project, $event, $extras = [])
{
    $projectid = $project->projectid;
    $title = $project->nameofwork;

    $sql1 = sprintf(
        "
        SELECT username
        FROM user_project_info
        WHERE projectid = '%s'
            AND iste_$event = 1
        ",
        DPDatabase::escape($projectid)
    );
    $res1 = DPDatabase::query($sql1);
    $n_subscribers = mysqli_num_rows($res1);

    // Loop contains everything that needs localizing,
    // so it can be localized for each recipient
    while ([$username] = mysqli_fetch_row($res1)) {
        configure_gettext_for_user($username);
        $event_label = get_label_for_event($event);
        $msg_subject = "$event_label: \"$title\"";
        $msg_body = $project->email_introduction() . "\n";
        switch ($event) {
            case 'posted':
                $pg_url = get_pg_catalog_url_for_etext($project->postednum);
                $msg_body .=
                    _("This project has been sent to Project Gutenberg and will soon be available for reading.")
                    . " "
                    . _("Usually, the files will be ready by the time you receive this mail; sometimes there may be a delay of a day or so.")
                    . " "
                    . sprintf(_("You can download the files via PG's online catalog at <%s>."), $pg_url)
                    . "\n";
                break;

            case 'round_available':
                $msg_body .=
                    sprintf(_("This project has become available for work in %s."), $extras['round']->id);
                break;

            case 'round_complete':
                $msg_body .=
                    sprintf(_("This project has completed work in %s."), $extras['round']->id);
                break;

            case 'pp_enter':
                $msg_body .=
                    _("This project has entered post-processing.");
                break;

            case 'sr_available':
                $msg_body .=
                    _("This project has become available for Smooth Reading.");
                break;

            case 'sr_reported':
                $msg_body .=
                    _("A new Smooth Reading report has been uploaded.");
                break;

            case 'sr_complete':
                $msg_body .=
                    _("This project has finished Smooth Reading.");
                break;

            case 'ppv_enter':
                $msg_body .=
                    _("This project has entered post-processing verification.");
                break;

            default:
                assert(0);
        }
        $email = get_forum_email_address($username);
        send_mail(
            $email,
            $msg_subject,
            sprintf(_("Hello %s,"), $username) . "\n\n" . $msg_body
        );
    }
    configure_gettext_for_user();

    if ($event == 'posted') {
        // Take the number of users subscribed to the event
        // and record that as the project's "interest level".
        $sql4 = sprintf(
            "
            UPDATE projects
            SET int_level = %d
            WHERE projectid = '%s'
            ",
            $n_subscribers,
            DPDatabase::escape($projectid)
        );
        $res4 = DPDatabase::query($sql4);
    }
}

// -----------------------------------------------------------------------------

/**
 * Return an array: keys are the same as array returned by get_subscribable_project_events(),
 * each value is the number of subscriptions to that event for the given project.
 */
function get_n_users_subscribed_to_events_for_project($projectid)
{
    $subscribable_project_events = get_subscribable_project_events();
    $items = [];
    foreach ($subscribable_project_events as $event => $label) {
        $items[] = "SUM(iste_$event) AS $event";
    }
    $selects = implode(',', $items);

    $sql = sprintf(
        "
        SELECT $selects
        FROM user_project_info
        WHERE projectid='%s'
        ",
        DPDatabase::escape($projectid)
    );
    $res = DPDatabase::query($sql);
    $r = mysqli_fetch_assoc($res);

    // If there are no rows for this project, the SUMs will all return NULL;
    // change those to zeroes.
    $r = array_map('intval', $r);

    return $r;
}

/**
 * Create a temp table named 'project_event_subscriptions_grouped_by_project'
 * with two columns: 'projectid' and 'nouste_posted', where the latter gives the
 * current number of users that are subscribed to the project's 'posted' event.
 * ("nouste" = "number of users subscribed to event")
 */
function create_temporary_project_event_subscription_summary_table()
{
    $sql = "
        CREATE TEMPORARY TABLE project_event_subscriptions_grouped_by_project
        (
            PRIMARY KEY (projectid)
        )
        SELECT
            projectid,
            COUNT(*) AS nouste_posted
        FROM user_project_info
        WHERE iste_posted = 1
        GROUP BY projectid
    ";
    DPDatabase::query($sql);
}

// -----------------------------------------------------------------------------

/*
A better (or at least, more flexible) way to implement event subscriptions
would be something like the following...

Currently we have various scattered calls to notify_project_event_subscribers().
Instead, push them all down into log_project_event() (thus subjecting *all*
events to an automatic subscription-check).

Currently we predefine a set of subscribable event-codes and a corresponding
set of columns in the user_project_info table, and each call to
notify_project_event_subscribers() specifies one of those event-codes,
which (along with the projectid) determines which subscriptions are triggered.
Instead, give notify_project_event_subscribers() access to all data passed to
log_project_event(), and have each subscription specify "patterns" in that
data that trigger that subscription.

For example, rather than having a 'round_available' event-code, a comparable
subscription would look for project-events in which
    event_type was 'transition',
    details1 was the 'proj_waiting' state for some round, and
    details2 was the 'proj_avail' state for the same round.

Rather than forcing each subscription to be attached to a particular project,
it would be possible to allow pattern-matching on the project. E.g. a user could
be notified when any book of a specified genre is available for P1 proofreading
(Task 16) or smooth-reading (Task 677).

The main problem with this scheme is specifying and implementing the event
pattern-matching mechanism, and efficiently finding all the subscriptions that
match a given event.
*/
