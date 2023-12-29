<?php
$relPath = 'pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'send_mail.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'Settings.inc');
include_once($relPath.'User.inc');
include_once($relPath.'links.inc'); // private_message_link()
include_once($relPath.'metarefresh.inc');

require_login();

$tasks_url = $code_url . "/" . basename(__FILE__);

$user = User::load_current();
$requester_u_id = $user->u_id;

$now_sse = time();
// The current time, expressed as Seconds Since the (Unix) Epoch.

// ---------------------------------------------------------
// Convert old-style GET requests into new-style,
// in case people have them in bookmarks/links.

if (isset($_GET['f']) && !isset($_GET['action'])) {
    $f_map = [
        'newtask' => 'show_creation_form',
        'detail' => 'show',
        'notifyme' => 'notify_me',
        'unnotifyme' => 'unnotify_me',
    ];
    $f = get_enumerated_param($_GET, 'f', null, array_keys($f_map));
    $_REQUEST['action'] = $_GET['action'] = $f_map[$f];
    unset($_GET['f']);
    unset($_REQUEST['f']);
}

if (isset($_GET['tid']) && !isset($_GET['task_id'])) {
    $_REQUEST['task_id'] = $_GET['task_id'] = $_GET['tid'];
    unset($_GET['tid']);
    unset($_REQUEST['tid']);
}

if (isset($_GET['search_text']) && !isset($_GET['action'])) {
    $_REQUEST['action'] = $_GET['action'] = 'search';
}

// ---------------------------------------------------------

$request_method = $_SERVER['REQUEST_METHOD'];
if ($request_method == 'GET') {
    $valid_actions = [
        'show_creation_form',
        'show',
        'notify_me',
        'unnotify_me',
        'search',
        'list_open',
        'notify_new',
        'unnotify_new',
        'edit_comment',
    ];
    $action = get_enumerated_param($_GET, 'action', null, $valid_actions, true);
} elseif ($request_method == 'POST') {
    $valid_actions = [
        'create',
        'show_editing_form',
        'edit',
        'add_comment',
        'add_related_task',
        'add_related_topic',
        'add_metoo',
        'remove_related_task',
        'remove_related_topic',
        'close',
        'reopen',
        'search',
        'save_edit_comment',
    ];
    $action = get_enumerated_param($_POST, 'action', null, $valid_actions);
} else {
    die("unexpected REQUEST_METHOD: '$request_method'");
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// This section sets up all the "pick from a list" properties of a task.

$tasks_array = [
    1 => "Bug Report",
    2 => "Feature Request",
    3 => "Support Request",
    4 => "Site Administrator Request",
];
$severity_array = [
    1 => "Catastrophic",
    2 => "Critical",
    3 => "Major",
    4 => "Normal",
    5 => "Minor",
    6 => "Trivial",
    7 => "Enhancement",
];
$priority_array = [
    1 => "Very High",
    2 => "High",
    3 => "Medium",
    4 => "Low",
    5 => "Very Low",
];
$categories_array = [
    1 => "None",
    2 => "Documentation",
    3 => "Entrance",
    4 => "Log in/out",
    5 => "New Member",
    6 => "Proofreading Interface",
    7 => "Activity Hub",
    8 => "Post-Processing",
    9 => "Preferences",
    10 => "Pre-Processing",
    11 => "Project Page",
    12 => "Project Lists",
    13 => "Project Manager",
    14 => "Site wide",
    15 => "Statistics, Page Counts",
    16 => "Translation",
    17 => "Task Center",
    18 => "Smooth Reading",
    19 => "OCR Pool",
    20 => "HTML Pool",
    21 => "Forums, Private Messages",
    22 => "WordCheck",
    23 => "Project Search",
    24 => "Mentoring",
    25 => "My Projects",
    26 => "Page Details, Diffs",
    27 => "Quizzes",
    28 => "Rounds",
    29 => "Release Queues",
    30 => "Teams",
    31 => "Project Notifications",
    32 => "Format Preview",
    99 => "Other",
];
asort($categories_array);
$tasks_status_array = [
    1 => "New",
    2 => "Accepted",
    14 => "Closed",
    15 => "Reopened",
    16 => "Researching",
    18 => "In Progress",
];
asort($tasks_status_array);
$os_array = [
    0 => "All",
    1 => "Windows 3.1",
    2 => "Windows 95",
    3 => "Windows 98",
    4 => "Windows ME",
    5 => "Windows 2000",
    6 => "Windows NT",
    7 => "Windows XP",
    8 => "Mac System 7",
    9 => "Mac System 7.5",
    10 => "Mac System 7.6.1",
    11 => "Mac System 8.0",
    12 => "Mac System 8.5",
    13 => "Mac System 8.6",
    14 => "Mac System 9.x",
    15 => "Mac OS X / macOS",
    16 => "Linux",
    17 => "BSDI",
    18 => "FreeBSD",
    19 => "NetBSD",
    20 => "OpenBSD",
    21 => "BeOS",
    22 => "HP-UX",
    23 => "IRIX",
    24 => "Neutrino",
    25 => "OpenVMS",
    26 => "OS/2",
    27 => "OSF/1",
    28 => "Solaris",
    29 => "SunOS",
    30 => "Windows 2003",
    31 => "Windows Vista",
    32 => "Windows 7",
    33 => "Windows 8",
    34 => "Windows 10",
    35 => "iOS",
    36 => "iPadOS",
    37 => "Android",
    99 => "Other",
];
natcasesort($os_array);
$browser_array = [
    0 => "All",
    1 => "Internet Explorer 6.x",
    2 => "Netscape 6.x",
    3 => "Internet Explorer 5.x",
    4 => "Netscape 7.x",
    5 => "Netscape 3.x",
    6 => "Netscape 4.x",
    7 => "Opera",
    8 => "Netscape 5.x",
    9 => "Internet Explorer 4.x",
    10 => "Lynx",
    11 => "Avant Browser",
    12 => "Netscape 2.x",
    13 => "Slimbrowser",
    14 => "Interarchy",
    15 => "Straw",
    16 => "MSN TV",
    17 => "Mozilla 1.4",
    18 => "Mozilla 1.5",
    19 => "Mozilla 1.6",
    20 => "Mozilla Firefox 0.6",
    22 => "Mozilla Firefox 0.7",
    23 => "Mozilla 1.1",
    24 => "Mozilla 1.2",
    25 => "Mozilla 1.3",
    26 => "Safari",
    27 => "Galeon",
    28 => "Konquerer",
    29 => "Internet Explorer 3.x",
    30 => "Mozilla 1.7",
    31 => "Mozilla 1.8",
    32 => "Mozilla Firefox 0.8",
    33 => "Mozilla Firefox 0.9",
    34 => "Opera 6.x",
    35 => "Opera 7.x",
    36 => "Mozilla Firefox 1.0",
    37 => "Mozilla Camino 0.7",
    38 => "Mozilla Camino 0.8.x",
    39 => "Opera 8.x",
    40 => "Opera 9.x",
    41 => "Mozilla Firefox 1.5.x",
    42 => "Internet Explorer 7.x",
    43 => "Mozilla Camino 1.x",
    44 => "Safari 2.x",
    45 => "Mozilla Firefox 2.x",
    46 => "Mozilla Firefox 3.x",
    47 => "Safari 3.x",
    48 => "Internet Explorer 8.x",
    49 => "Safari 4.x",
    50 => "Opera 10.x",
    51 => "Microsoft Edge",
    52 => "Chrome / Chromium",
    99 => "Other",
];
asort($browser_array);
$tasks_close_array = [
    1 => "Not a Bug",
    2 => "Won't Fix",
    3 => "Won't Implement",
    4 => "Works for Me",
    5 => "Duplicate",
    6 => "Deferred",
    7 => "Fixed",
    8 => "Implemented",
    9 => "Resolved",
];
asort($tasks_close_array);
$percent_complete_array = [
    0 => "0%",
    10 => "10%",
    20 => "20%",
    30 => "30%",
    40 => "40%",
    50 => "50%",
    60 => "60%",
    70 => "70%",
    80 => "80%",
    90 => "90%",
    100 => "100%",
];

$task_assignees_array = [];
$taskcenter_managers = array_unique(array_merge(
    Settings::get_users_with_setting('sitemanager', 'yes'),
    Settings::get_users_with_setting('task_center_mgr', 'yes')
));
foreach ($taskcenter_managers as $taskcenter_manager) {
    $user = new User($taskcenter_manager);
    $task_assignees_array[$user->u_id] = $taskcenter_manager;
}
natcasesort($task_assignees_array);
$task_assignees_array = [0 => 'Unassigned'] + $task_assignees_array;

// -----------------------------------------------------------------------------

$SearchParams_choices = [
    'task_status' => [998 => _('All Tasks'), 999 => _('All Open Tasks')] + $tasks_status_array,
    'task_type' => [999 => _('All Task Types')] + $tasks_array,
    'task_severity' => [999 => _('All Severities')] + $severity_array,
    'task_priority' => [999 => _('All Priorities')] + $priority_array,
    'task_assignee' => [999 => _('All Developers')] + $task_assignees_array,
    'task_category' => [999 => _('All Categories')] + $categories_array,
];

// XXX Re task_assignee, there's a long-standing bug involving
// a sitemanager/task_center_mgr whose u_id happens to be 999.
// However, there's a fairly low chance of it ever being triggered.

/**
 * For each of the search parameters, echo its control (HTML markup),
 * initializing it with any (valid) value that the current request
 * has supplied for the parameter.
 */
function SearchParams_echo_controls()
{
    global $SearchParams_choices, $tasks_url;

    if (isset($_REQUEST['search_text']) && !empty($_REQUEST['search_text'])) {
        $st = $_REQUEST['search_text'];
        $search_text = attr_safe($st);
    } else {
        $search_text = "";
    }

    echo "<form action='$tasks_url' method='get'>";
    echo "<table class='themed'>\n";
    echo "<tr><th>" . _("Search") . "</th>\n";
    echo "<td>";

    echo "<input type='hidden' name='action' value='search'>\n";
    echo "<input type='text' value='$search_text' name='search_text' style='width: 20em'>\n";

    foreach ($SearchParams_choices as $param_name => $choices) {
        $value = (int) get_enumerated_param($_REQUEST, $param_name, '999', array_keys($choices));
        echo dropdown_select($param_name, $value, $choices);
    }

    echo "<input type='submit' value='" . attr_safe(_("Search")) . "'></td>\n";
    echo "</tr>\n";
    echo "</table></form><br>\n";
}

/**
 * Return a SQL condition that expresses the restriction on tasks
 * implied by the values (if any) supplied for the search parameters
 * by the current request.
 */
function SearchParams_get_sql_condition($request_params)
{
    global $testing, $SearchParams_choices;

    $condition = "1";
    if (isset($request_params['search_text'])) {
        $search_text = normalize_whitespace($request_params['search_text']);
        if ($testing) {
            echo_html_comment("\$request_params['search_text'] = $search_text");
        }

        $condition .= sprintf(" AND
            (
                POSITION('%1\$s' IN task_summary)
                OR
                POSITION('%1\$s' IN task_details)
            )
        ", DPDatabase::escape($search_text));
    }

    // ------

    foreach ($SearchParams_choices as $param_name => $choices) {
        $value = get_enumerated_param($request_params, $param_name, null, array_keys($choices), true);
        if ($param_name == 'task_status') {
            if (is_null($value) || $value == 999) {
                $param_condition = "$param_name >= 0 AND date_closed = 0";
            } elseif ($value == 998) {
                $param_condition = "$param_name >= 0";
            } else {
                $param_condition = "$param_name = $value";
            }
        } else {
            if (is_null($value) || $value == 999) {
                $param_condition = "$param_name >= 0";
            } else {
                $param_condition = "$param_name = $value";
            }
        }
        $condition .= "\n            AND $param_condition";
    }

    return $condition;
}

/**
 * Return a string (suitable for use in the 'query string' portion of a URL)
 * that represents (and possibly just reiterates) the values (if any)
 * supplied for the search parameters by the current request.
 */
function SearchParams_get_url_query_string()
{
    global $SearchParams_choices;

    if (isset($_REQUEST['search_text'])) {
        $t = "action=search&search_text=" . urlencode($_REQUEST['search_text']);
        foreach ($SearchParams_choices as $param_name => $choices) {
            $value = get_enumerated_param($_REQUEST, $param_name, '999', array_keys($choices));
            $t .= "&{$param_name}={$value}";
        }
        $t .= "&";
    } else {
        $t = "";
    }
    return $t;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function make_default_task_object()
{
    global $tasks_array;
    global $categories_array;
    global $tasks_status_array;
    global $severity_array;
    global $priority_array;
    global $os_array;
    global $browser_array;
    global $percent_complete_array;

    $task = new stdClass();
    $task->task_severity = get_property_key("Normal", $severity_array);
    $task->task_priority = get_property_key("Medium", $priority_array);
    $task->task_type = get_property_key("Bug Report", $tasks_array);
    $task->task_category = get_property_key("None", $categories_array);
    $task->task_status = get_property_key("New", $tasks_status_array);
    $task->task_os = get_property_key("All", $os_array);
    $task->task_browser = get_property_key("All", $browser_array);
    $task->task_assignee = 0;
    $task->task_summary = "";
    $task->task_details = "";
    $task->percent_complete = get_property_key("0%", $percent_complete_array);
    $task->opened_by = "";
    $task->task_id = "";
    return $task;
}

function create_task_from_form_submission($formsub)
{
    global $tasks_array;
    global $categories_array;
    global $tasks_status_array;
    global $task_assignees_array;
    global $severity_array;
    global $priority_array;
    global $os_array;
    global $browser_array;
    global $now_sse;
    global $requester_u_id;
    global $site_abbreviation;

    $task_summary = trim(array_get($formsub, 'task_summary', ''));
    $task_details = trim(array_get($formsub, 'task_details', ''));

    if (empty($task_summary) || empty($task_details)) {
        return _("You must supply a Task Summary and Task Details.");
    }

    assert(!isset($formsub['task_id']));
    // Create a new task.
    $newt_type = (int) get_enumerated_param($formsub, 'task_type', null, array_keys($tasks_array));
    $newt_category = (int) get_enumerated_param($formsub, 'task_category', null, array_keys($categories_array));
    $newt_status = (int) get_enumerated_param($formsub, 'task_status', null, array_keys($tasks_status_array));
    $newt_assignee = (int) get_enumerated_param($formsub, 'task_assignee', null, array_keys($task_assignees_array));
    $newt_severity = (int) get_enumerated_param($formsub, 'task_severity', null, array_keys($severity_array));
    $newt_priority = (int) get_enumerated_param($formsub, 'task_priority', null, array_keys($priority_array));
    $newt_os = (int) get_enumerated_param($formsub, 'task_os', null, array_keys($os_array));
    $newt_browser = (int) get_enumerated_param($formsub, 'task_browser', null, array_keys($browser_array));

    // Validate the assignee, skipping the case where it is 0 (Unassigned).
    if ($newt_assignee != 0) {
        $task_assignee_user = User::load_from_uid($newt_assignee);
    }

    $sql_query = sprintf(
        "
        INSERT INTO tasks
        SET
            task_summary     = '%s',
            task_type        = %d,
            task_category    = %d,
            task_status      = %d,
            task_assignee    = %d,
            task_severity    = %d,
            task_priority    = %d,
            task_os          = %d,
            task_browser     = %d,
            task_details     = '%s',
            date_opened      = %d,
            opened_by        = %d,
            date_edited      = %d,
            edited_by        = %d,
            percent_complete = 0,
            related_postings = '%s'
        ",
        DPDatabase::escape($task_summary),
        $newt_type,
        $newt_category,
        $newt_status,
        $newt_assignee,
        $newt_severity,
        $newt_priority,
        $newt_os,
        $newt_browser,
        DPDatabase::escape($task_details),
        $now_sse,
        $requester_u_id,
        $now_sse,
        $requester_u_id,
        DPDatabase::escape(encode_array([]))
    );
    DPDatabase::query($sql_query);
    $task_id = mysqli_insert_id(DPDatabase::get_connection());

    global $pguser;
    NotificationMail($task_id, "", true);
    // Nobody could have subscribed to this particular task yet,
    // so the msg will only go to those with taskctr_notice = 'all' or 'notify_new'.

    // If $newt_assignee is 0, there is no user assigned so no notification
    // to send out.
    if ($newt_assignee != 0) {
        global $tasks_url, $code_url;
        send_mail(
            $task_assignee_user->email,
            "$site_abbreviation Task Center: Task #$task_id has been assigned to you",
            $task_assignee_user->username . ", you have been assigned task #$task_id.  Please visit this task at $tasks_url?action=show&task_id=$task_id.\n\nIf you do not want to accept this task please edit the task and change the assignee to 'Unassigned'."
        );
    }

    // Subscribe the current user to this task for notification
    $userSettings = & Settings::get_Settings($pguser);
    $userSettings->add_value('taskctr_notice', $task_id);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// This is the point at which the script starts to produce output.

if (!isset($_REQUEST['task_id'])) {

    // Default 'action' when no task is specified:
    if (is_null($action)) {
        $action = 'list_open';
    }

    switch ($action) {
        case 'show_creation_form':
            // Open a form to specify the properties of a new task.
            TaskHeader("New Task");

            // The user wants to create a task.
            // Initialize the form with default values.
            $task = make_default_task_object();
            TaskForm($task);
            break;

        case 'search':
            // The user clicked a column-header-link in a listing of tasks
            // (or followed a bookmark of such a link).
            $header = _("Task Search");
            if (!empty($_REQUEST['search_text'])) {
                $header .= ": " . $_REQUEST['search_text'];
            }
            TaskHeader($header);
            echo "<h1 style='margin-top: 0;'>" . _("Task Center") . "</h1>";
            SearchParams_echo_controls();
            search_and_list_tasks($_REQUEST);
            break;

        case 'list_open':
            // The user just entered the Task Center
            // (e.g., by clicking the "Report a Bug" link).
            TaskHeader(_("All Open Tasks"), true);
            echo "<h1 style='margin-top: 0;'>" . _("Task Center") . "</h1>";
            SearchParams_echo_controls();
            list_all_open_tasks();
            break;

        case 'create':
            // The user is supplying values for the properties of a new task.
            $errmsg = create_task_from_form_submission($_POST);
            if ($errmsg) {
                ShowError($errmsg, true);
                break;
            } else {
                // If we successfully create the task, we should reload
                //   the page to clear the POST data and make sure that
                //   reloading does not lead to duplicated tasks.
                metarefresh(0, $tasks_url);
                break;
            }

            // no break
        case 'notify_new':
            $userSettings = & Settings::get_Settings($pguser);
            $userSettings->add_value('taskctr_notice', 'notify_new');
            metarefresh(0, $tasks_url);
            break;

        case 'unnotify_new':
            $userSettings = & Settings::get_Settings($pguser);
            $userSettings->remove_value('taskctr_notice', 'notify_new');
            metarefresh(0, $tasks_url);
            break;
    }
} else {
    handle_action_on_a_specified_task();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function handle_action_on_a_specified_task()
{
    global $pguser, $requester_u_id;
    global $now_sse;
    global $action, $tasks_url;

    // data array
    global $tasks_status_array;
    global $tasks_array;
    global $categories_array;
    global $task_assignees_array;
    global $severity_array;
    global $priority_array;
    global $os_array;
    global $browser_array;
    global $percent_complete_array;
    global $tasks_close_array;

    // Default 'action' when a task is specified:
    if (is_null($action)) {
        $action = 'show';
    }

    $task_id = (int)get_float_param($_REQUEST, 'task_id', null, 1, null);

    // Fetch the state of the specified task
    // before any requested changes.
    $pre_task = load_task($task_id, false);
    if (!$pre_task) {
        ShowError(sprintf(_("Task #%d was not found."), $task_id));
        return;
    }

    if ($action == 'notify_me') {
        $userSettings = & Settings::get_Settings($pguser);
        $userSettings->add_value('taskctr_notice', $task_id);
        // metarefresh with default action (=show) so that reloading page will not repeat action
        metarefresh(0, "$tasks_url?task_id=$task_id");
    } elseif ($action == 'unnotify_me') {
        $userSettings = & Settings::get_Settings($pguser);
        $userSettings->remove_value('taskctr_notice', $task_id);
        metarefresh(0, "$tasks_url?task_id=$task_id");
    }

    if ($action == 'show' || $action == 'edit_comment') {
        TaskHeader(title_string_for_task($pre_task));
        TaskDetails($task_id, $action);
    } elseif ($action == 'show_editing_form') {
        TaskHeader(title_string_for_task($pre_task));
        if (user_is_a_sitemanager() || user_is_taskcenter_mgr() || $pre_task->opened_by == $requester_u_id && empty($pre_task->closed_reason)) {
            // The user wants to edit an existing task.
            // Initialize the form with the current values of the task's properties.
            TaskForm($pre_task);
        } else {
            ShowError(_("You do not have permission to edit this task."), true);
        }
    } elseif ($action == 'reopen') {
        NotificationMail($task_id, "$pguser reopened this task.");
        $sql = sprintf(
            "
            UPDATE tasks
            SET
                task_status = %d,
                edited_by = %d,
                date_edited = %d,
                date_closed = 0,
                closed_by = 0,
                closed_reason = 0
            WHERE task_id = %d
            ",
            get_property_key("Reopened", $tasks_status_array),
            $requester_u_id,
            $now_sse,
            $task_id
        );
        DPDatabase::query($sql);
        metarefresh(0, "$tasks_url?task_id=$task_id");
    } elseif ($action == 'edit') {
        $task_summary = trim(array_get($_POST, 'task_summary', ''));
        $task_details = trim(array_get($_POST, 'task_details', ''));
        // The user is supplying values for the properties of a pre-existing task.
        if (empty($task_summary) || empty($task_details)) {
            ShowError(_("You must supply a Task Summary and Task Details."), true);
        } else {
            // Update a pre-existing task.
            NotificationMail($task_id, "$pguser edited this task.");

            $edit_type = (int) get_enumerated_param($_POST, 'task_type', null, array_keys($tasks_array));
            $edit_category = (int) get_enumerated_param($_POST, 'task_category', null, array_keys($categories_array));
            $edit_status = (int) get_enumerated_param($_POST, 'task_status', null, array_keys($tasks_status_array));
            $edit_assignee = (int) get_enumerated_param($_POST, 'task_assignee', null, array_keys($task_assignees_array));
            $edit_severity = (int) get_enumerated_param($_POST, 'task_severity', null, array_keys($severity_array));
            $edit_priority = (int) get_enumerated_param($_POST, 'task_priority', null, array_keys($priority_array));
            $edit_os = (int) get_enumerated_param($_POST, 'task_os', null, array_keys($os_array));
            $edit_browser = (int) get_enumerated_param($_POST, 'task_browser', null, array_keys($browser_array));
            $edit_percent = (int) get_enumerated_param($_POST, 'percent_complete', null, array_keys($percent_complete_array));

            $sql = sprintf(
                "
                UPDATE tasks
                SET
                    task_summary     = '%s',
                    task_type        = %d,
                    task_category    = %d,
                    task_status      = %d,
                    task_assignee    = %d,
                    task_severity    = %d,
                    task_priority    = %d,
                    task_os          = %d,
                    task_browser     = %d,
                    task_details     = '%s',
                    date_edited      = %d,
                    edited_by        = %d,
                    percent_complete = %d
                WHERE task_id = %d
                ",
                DPDatabase::escape($task_summary),
                $edit_type,
                $edit_category,
                $edit_status,
                $edit_assignee,
                $edit_severity,
                $edit_priority,
                $edit_os,
                $edit_browser,
                DPDatabase::escape($task_details),
                $now_sse,
                $requester_u_id,
                $edit_percent,
                $task_id
            );
            DPDatabase::query($sql);
            metarefresh(0, "$tasks_url?task_id=$task_id");
        }
    } elseif ($action == 'close') {
        if (user_is_a_sitemanager() || user_is_taskcenter_mgr()) {
            $tc_reason = (int) get_enumerated_param($_POST, 'closed_reason', null, array_keys($tasks_close_array));
            NotificationMail(
                $task_id,
                "$pguser closed this task.\nThe reason for closing was: " . $tasks_close_array[$tc_reason] . "."
            );
            $sql = sprintf(
                "
                UPDATE tasks
                SET
                    percent_complete = %d,
                    task_status = %d,
                    date_closed = %d,
                    closed_by = %d,
                    closed_reason = %d,
                    date_edited = %d,
                    edited_by = %d
                WHERE task_id = %d
                ",
                get_property_key("100%", $percent_complete_array),
                get_property_key("Closed", $tasks_status_array),
                $now_sse,
                $requester_u_id,
                $tc_reason,
                $now_sse,
                $requester_u_id,
                $task_id
            );
            DPDatabase::query($sql);
            metarefresh(0, $tasks_url);
        } else {
            ShowError(_("You do not have permission to close tasks."), true);
            return;
        }
    } elseif ($action == 'add_comment') {
        $comment = trim(array_get($_POST, 'task_comment', ''));
        if ($comment) {
            NotificationMail($task_id, "$pguser commented:\n\n$comment");
            $sql = sprintf(
                "
                INSERT INTO tasks_comments (task_id, u_id, comment_date, comment)
                VALUES (%d, %d, %d, '%s')
                ",
                $task_id,
                $requester_u_id,
                $now_sse,
                DPDatabase::escape($comment)
            );
            DPDatabase::query($sql);

            $sql = sprintf(
                "
                UPDATE tasks
                SET date_edited = %d, edited_by = %d
                WHERE task_id = %d
                ",
                $now_sse,
                $requester_u_id,
                $task_id
            );
            DPDatabase::query($sql);

            // subscribe the user to the task for notifications
            $userSettings = & Settings::get_Settings($pguser);
            $userSettings->add_value('taskctr_notice', $task_id);

            // After posting the comment, we should reload as to clear POST data
            //   and avoid comments being posted multiple times.
            $comment_id = create_anchor_for_comment($requester_u_id, $now_sse);
            metarefresh(0, "$tasks_url?action=show&task_id=$task_id#$comment_id");
        } else {
            ShowError(_("You must supply a comment before clicking Add Comment."), true);
            return;
        }
    } elseif ($action == 'add_related_task') {
        $related_task_id = (int)get_float_param($_POST, 'related_task', null, 1, null);
        process_related_task($pre_task, 'add', $related_task_id);
    } elseif ($action == 'remove_related_task') {
        $related_task_id = (int)get_float_param($_POST, 'related_task', null, 1, null);
        process_related_task($pre_task, 'remove', $related_task_id);
    } elseif ($action == 'add_related_topic') {
        $related_posting_topic = (int)get_float_param($_POST, 'related_posting', null, 1, null);
        process_related_topic($pre_task, 'add', $related_posting_topic);
    } elseif ($action == 'remove_related_topic') {
        $related_posting_topic = (int)get_float_param($_POST, 'related_posting', null, 1, null);
        process_related_topic($pre_task, 'remove', $related_posting_topic);
    } elseif ($action == 'add_metoo') {
        $vote_os = (int) get_enumerated_param($_POST, 'metoo_os', null, array_keys($os_array));
        $vote_browser = (int) get_enumerated_param($_POST, 'metoo_browser', null, array_keys($browser_array));

        // Do not insert two votes for the same user
        $meTooCount = get_me_too_count($task_id, $requester_u_id);
        if ($meTooCount == 0) {
            $sql = sprintf(
                "
                INSERT INTO tasks_votes (task_id, u_id, vote_os, vote_browser)
                VALUES (%d, %d, %d, %d)
                ",
                $task_id,
                $requester_u_id,
                $vote_os,
                $vote_browser
            );
            DPDatabase::query($sql);
        }

        // Redirect back to show task page to clear POST data
        metarefresh(0, "$tasks_url?action=show&task_id=$task_id");
    } elseif ($action == 'save_edit_comment') {
        $comment_id = array_get($_POST, 'comment_id', "");
        $comment = trim(array_get($_POST, 'task_comment', ''));
        [$u_id, $comment_date] = explode('_', $comment_id, 2);
        if (($u_id === $requester_u_id && $now_sse - $comment_date <= 86400) || user_is_a_sitemanager()) {
            $sql = sprintf(
                "
                UPDATE tasks_comments SET comment='%s'
                WHERE task_id = %d AND u_id = %d AND comment_date = %d
                ",
                DPDatabase::escape($comment),
                $task_id,
                $u_id,
                $comment_date
            );
            DPDatabase::query($sql);

            metarefresh(0, "$tasks_url?action=show&task_id=$task_id#$comment_id");
        }
    } else {
        die("shouldn't be able to reach here");
    }
}

/**
 * Add or remove a related task to the current task.
 */
function process_related_task($pre_task, $action, $related_task_id)
{
    global $pguser, $tasks_url;
    assert($action == 'add' || $action == 'remove');

    // Validate task_id. It must be an integer >= 1
    $related_task_id = trim($related_task_id);
    if (!is_numeric($related_task_id) || $related_task_id < 1) {
        ShowError(_("You must supply a valid related task ID."), true);
        return;
    }

    $adding = ($action == 'add');
    $pre_task_id = $pre_task->task_id;
    $related_task_exists = load_task($related_task_id) != null;
    $task_already_present = in_array($related_task_id, load_related_tasks($pre_task_id));

    if (!$related_task_exists || $related_task_id == $pre_task_id || $task_already_present == $adding) {
        ShowError(_("You must supply a valid related task ID."), true);
        return;
    }

    if ($adding) {
        insert_related_task($pre_task_id, $related_task_id);
    } else {
        remove_related_task($pre_task_id, $related_task_id);
    }

    if ($adding) {
        NotificationMail($pre_task_id, "$pguser added related task $related_task_id.");
    } else {
        NotificationMail($pre_task_id, "$pguser removed related task $related_task_id.");
    }
    metarefresh(0, "$tasks_url?task_id=$pre_task_id");
}

/**
 * Add or remove a related topic (forum thread) to the curent task.
 */
function process_related_topic($pre_task, $action, $related_topic_id)
{
    global $pguser, $tasks_url;
    assert($action == 'add' || $action == 'remove');

    // Validate related_topic_id. It must be an integer >= 1
    $related_topic_id = trim($related_topic_id);
    if (!is_numeric($related_topic_id) || $related_topic_id < 1) {
        ShowError(_("You must supply a valid related topic ID."), true);
        return;
    }

    $adding = ($action == 'add');
    $pre_task_id = $pre_task->task_id;
    $related_topics = decode_array($pre_task->related_postings);
    $topic_already_present = in_array($related_topic_id, $related_topics);
    $topic_details = get_topic_details($related_topic_id);

    if ($adding && ($topic_already_present ||
        !does_topic_exist($related_topic_id) ||
        !$topic_details['forum_name'])) {
        ShowError(_("You must supply a valid related topic ID."), true);
        return;
    }


    if ($adding) {
        array_push($related_topics, $related_topic_id);
    } else {
        unset($related_topics[array_search($related_topic_id, $related_topics)]);
    }

    $sql = sprintf(
        "
        UPDATE tasks
        SET related_postings = '%s'
        WHERE task_id = %d
        ",
        DPDatabase::escape(encode_array($related_topics)),
        $pre_task_id
    );
    DPDatabase::query($sql);

    if ($adding) {
        NotificationMail($pre_task_id, "$pguser added related topic $related_topic_id.");
    } else {
        NotificationMail($pre_task_id, "$pguser removed related topic $related_topic_id.");
    }
    metarefresh(0, "$tasks_url?task_id=$pre_task_id");
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function dropdown_select($field_name, $current_value, $array)
{
    $return = "<select size='1' name='$field_name' ID='$field_name'>\n";
    foreach ($array as $key => $val) {
        $return .= "<option value='" . attr_safe($key) . "'";
        if ($current_value == $key) {
            $return .= " SELECTED";
        }
        $return .= ">" . html_safe($val) . "</option>\n";
    }
    $return .= "</select>\n";
    return $return;
}

function TaskHeader($header, $show_new_alert = false)
{
    global $tasks_url, $pguser;

    // Allow this function to be called more than once but only output the
    // header once. This allows ShowError() to call the function to ensure
    // the HTML page has been opened once and only once.
    static $header_output = false;
    if ($header_output) {
        return;
    }
    $header_output = true;

    $js_data = <<<EOS
        function showSpan(id) { document.getElementById(id).style.display=""; }
        function hideSpan(id) { document.getElementById(id).style.display="none"; }
        EOS;

    output_header($header, NO_STATSBAR, ['js_data' => $js_data]);

    $userSettings = & Settings::get_Settings($pguser);
    $notification_settings = $userSettings->get_values('taskctr_notice');
    $notified_for_new = in_array('notify_new', $notification_settings);

    echo "<div class='task-nav' style='float: left'>";
    echo "<a href='$tasks_url'>" . _("Task Center Home") . "</a> | ";
    echo "<a href='$tasks_url?action=show_creation_form'>" . _("New Task") . "</a>";
    echo "<form method='get' style='display: inline;'>";
    if ($show_new_alert) {
        if ($notified_for_new) {
            echo "<input type='hidden' name='action' value='unnotify_new'>";
            echo " | <input type='submit' value='" . attr_safe(_("Stop New Task Alerts")) . "'>";
        } else {
            echo "<input type='hidden' name='action' value='notify_new'>";
            echo " | <input type='submit' value='" . attr_safe(_("Receive New Task Alerts")) . "'>";
        }
    }
    echo "</form>";
    echo "</div>";

    echo "<div class='task-nav' style='float: right'>";
    echo "<form action='$tasks_url' method='get'>";
    echo "<input type='hidden' name='action' value='show'>";
    echo "<b>" . _("Show Task #") . "</b>";
    echo "&nbsp;\n";
    echo "<input type='number' name='task_id' min='1' required style='width: 5em;'>&nbsp;\n";
    echo "<input type='submit' value='" . attr_safe(_("Go!")) . "'>\n";
    echo "</form>";
    echo "</div>\n";

    echo "<div style='clear: both;'></div>";
}

/**
 * Encode an array into text for insertion into the database.
 */
function encode_array($a)
{
    return base64_encode(serialize($a));
}

/**
 * Decode an array from its text form pulled into a PHP array.
 *
 * This should return an array, but if $str is empty,
 * unserialize("") === bool(false). In that case we explicitly return
 * an empty array.
 */
function decode_array($str)
{
    $a = unserialize(base64_decode($str));
    if (is_array($a)) {
        return $a;
    }
    return [];
}

// -----------------------------------------------------------------------------

function list_all_open_tasks()
{
    select_and_list_tasks("date_closed = 0");
}

function search_and_list_tasks($request_params)
{
    $condition = SearchParams_get_sql_condition($request_params);
    select_and_list_tasks($condition);
}

// -----------------------------------------------------------------------------

function select_and_list_tasks($sql_condition)
{
    global $tasks_url;

    $columns = [
        'task_id' => " class='center-align'",
        'task_summary' => "",
        'task_type' => " class='nowrap'",
        'task_severity' => "",
        'task_priority' => "",
        'date_edited' => " class='nowrap center-align'",
        'task_status' => "",
        'votes' => "",
        'percent_complete' => "",
    ];

    $curr_sort_dir = get_enumerated_param($_GET, 'direction', 'desc', ['asc', 'desc']);
    $curr_sort_col = get_enumerated_param($_GET, 'orderby', 'date_edited', array_keys($columns));

    $sql_query = "
        SELECT tasks.task_id,
          task_type,
          task_severity,
          task_summary,
          task_priority,
          date_edited,
          task_status,
          percent_complete,
          COUNT(vote_os) AS votes
        FROM tasks
          LEFT OUTER JOIN tasks_votes USING (task_id)
        WHERE $sql_condition
        GROUP BY task_id
        ORDER BY $curr_sort_col $curr_sort_dir
    ";
    $sql_result = DPDatabase::query($sql_query);
    $num_tasks_returned = mysqli_num_rows($sql_result);

    if ($num_tasks_returned == 0) {
        echo "<p>" . _("No tasks found.") . "</p>";
        return;
    }

    $t = SearchParams_get_url_query_string();

    echo "<table class='themed theme_striped' style='font-size: 0.95em'><tr>\n";
    foreach ($columns as $property_id => $attrs) {
        // Each column-header is a link; clicking on it will cause
        // the resulting listing to be sorted on that column.
        $orderby_for_link = $property_id;

        // But sorted in which direction?
        $caret = "";    // arrow to show sort direction on relevant column
        if ($property_id == $curr_sort_col) {
            // This column is the one that the current listing is sorted on.
            // A header-click will just reverse the direction of the sort.
            if ($curr_sort_dir == "asc") {
                $direction_for_link = "desc";
                $caret = "&nbsp;&#9650;";
            } else {
                $direction_for_link = "asc";
                $caret = "&nbsp;&#9660;";
            }
        } else {
            // This column is not the current sort-column.
            // A header-click will sort by that column in descending order.
            // (Might be better for each column to have its own default direction.)
            $direction_for_link = "desc";
        }

        $url = "$tasks_url?{$t}orderby=$orderby_for_link&direction=$direction_for_link";
        $label = property_get_label($property_id, true);
        echo "<th$attrs><a href='$url'>$label$caret</a></th>\n";
    }
    echo "</tr>\n";

    while ($row = mysqli_fetch_assoc($sql_result)) {
        echo "<tr>\n";
        foreach ($columns as $property_id => $attrs) {
            $formatted_value = property_format_value($property_id, $row, true);
            echo "<td$attrs>$formatted_value</td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";

    // if 2 tasks or more found, display the number of reported tasks
    if ($num_tasks_returned > 1) {
        echo "(" . sprintf(_("%d tasks"), $num_tasks_returned) . ")";
    }
}

function TaskForm($task)
{
    global $requester_u_id, $tasks_array, $severity_array, $categories_array, $tasks_status_array;
    global $os_array, $browser_array, $percent_complete_array;
    global $task_assignees_array;
    global $priority_array, $tasks_url;

    // Non-managers can only set the task status to New.
    if (!user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
        $tasks_status_array = [get_property_key("New", $tasks_status_array) => "New"];
    } else {
        // Don't want to permit setting status to "Closed" or "Reopened" when creating/editing a task
        unset($tasks_status_array[get_property_key("Closed", $tasks_status_array)]);
        unset($tasks_status_array[get_property_key("Reopened", $tasks_status_array)]);
    }

    $task_summary_enc = attr_safe($task->task_summary);
    $task_details_enc = html_safe($task->task_details);

    echo "<form action='$tasks_url' method='post'>";
    if (empty($task->task_id)) {
        echo "<input type='hidden' name='action' value='create'>\n";
        $title = _("New Task");
    } else {
        echo "<input type='hidden' name='action' value='edit'>\n";
        echo "<input type='hidden' name='task_id' value='$task->task_id'>";
        $title = "#$task->task_id: " . html_safe($task->task_summary);
    }
    if ($task->opened_by == $requester_u_id && !user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
        echo "<input type='hidden' name='percent_complete' value='$task->percent_complete'>";
    }
    echo "<h1 style='margin-top: 0;'>$title</h1>";

    echo "<p>";
    echo "<span class='bold'>" . property_get_label('task_summary', false) . "</span>";
    echo "&nbsp;";
    echo "<input type='text' name='task_summary' value=\"$task_summary_enc\" style='width: 50%' maxlength='80' required>";
    echo "</p>";

    echo "<div class='task-detail'>";
    echo "<table class='task-detail-block'>\n";
    property_echo_select_tr('task_severity', $task->task_severity, $severity_array);
    property_echo_select_tr('task_priority', $task->task_priority, $priority_array);
    property_echo_select_tr('task_category', $task->task_category, $categories_array);
    property_echo_select_tr('task_os', $task->task_os, $os_array);
    property_echo_select_tr('task_browser', $task->task_browser, $browser_array);
    echo "</table>";

    echo "<table class='task-detail-block'>\n";
    property_echo_select_tr('task_type', $task->task_type, $tasks_array);
    property_echo_select_tr('task_status', $task->task_status, $tasks_status_array);
    property_echo_select_tr('task_assignee', $task->task_assignee, $task_assignees_array);
    if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && !empty($task->task_id)) {
        property_echo_select_tr('percent_complete', $task->percent_complete, $percent_complete_array);
    }
    echo "</table>";
    echo "</div>";

    echo "<h2 style='clear: both; padding-top: 0.5em;'>" . _("Details") . "</h2>\n";
    echo "<textarea name='task_details' style='width: 99%; height: 8em;' required>$task_details_enc</textarea>";

    echo "<input type='submit' value='";
    if (empty($task->task_id)) {
        echo attr_safe(_("Add Task"));
    } else {
        echo attr_safe(_("Save Task"));
    }
    echo "'>\n";
    echo "</td></tr></table></form>\n";
}

/**
 * Echo a <tr> element containing a label and a <select> for the given property.
 */
function property_echo_select_tr($property_id, $current_value, $options)
{
    $label = property_get_label($property_id, false);
    echo "<tr><th>$label</th><td>\n";
    echo dropdown_select($property_id, $current_value, $options);
    echo "</td></tr>\n";
}

function load_task($tid, $is_assoc = true)
{
    $sql = sprintf(
        "
        SELECT *
        FROM tasks
        WHERE task_id = %d
        ",
        $tid
    );
    $result = DPDatabase::query($sql);
    if ($is_assoc) {
        $task = mysqli_fetch_assoc($result);
    } else {
        $task = mysqli_fetch_object($result);
    }
    mysqli_free_result($result);
    return $task;
}

function TaskDetails($tid, $action)
{
    global $requester_u_id, $tasks_url;
    global $os_array, $browser_array, $tasks_close_array;
    global $pguser;

    $task = load_task($tid);

    if (!$task) {
        ShowError(sprintf(_("Task #%d was not found.", $tid)));
        return;
    }

    $userSettings = & Settings::get_Settings($pguser);
    $notification_settings = $userSettings->get_values('taskctr_notice');
    if (in_array($tid, $notification_settings) ||
        in_array('all', $notification_settings)) {
        $already_notified = 1;
    } else {
        $already_notified = 0;
    }

    // floating right-aligned div for task edit/re-open
    echo "<div style='float: right; padding-top: 1em;'>";
    echo "<form method='get' style='display: inline;'>\n";
    echo "<input type='hidden' name='task_id' value='$tid'>\n";
    if (empty($already_notified)) {
        echo "<input type='hidden' name='action' value='notify_me'>\n";
        echo "<input type='submit' value='" . attr_safe(_("Sign up for task notifications")) . "'>\n";
    } else {
        echo "<input type='hidden' name='action' value='unnotify_me'>\n";
        echo "<input type='submit' value='" . attr_safe(_("Remove me from task notifications")) . "'>\n";
    }
    echo "</form>";

    echo "<form action='$tasks_url' method='post' style='display: inline;'>\n";
    if ((user_is_a_sitemanager() || user_is_taskcenter_mgr() || $task['opened_by'] == $requester_u_id) && empty($task['closed_reason'])) {
        echo "<input type='hidden' name='action' value='show_editing_form'>\n";
        echo "<input type='hidden' name='task_id' value='$tid'>\n";
        echo "<input type='submit' value='" . attr_safe(_("Edit Task")) . "'>\n";
    } elseif (!empty($task['closed_reason'])) {
        echo "<input type='hidden' name='action' value='reopen'>\n";
        echo "<input type='hidden' name='task_id' value='$tid'>\n";
        echo "<input type='submit' value='" . attr_safe(_("Re-Open Task")) . "'>\n";
    }
    echo "</form>";
    echo "</div>";

    echo "<h1 style='margin-top: 0;'>";
    echo "#$tid: " . property_format_value('task_summary', $task, false);
    echo "</h1>";

    echo "<div class='task-detail'>";
    echo "<table class='task-detail-block'>\n";
    property_echo_value_tr('task_severity', $task);
    property_echo_value_tr('task_priority', $task);
    property_echo_value_tr('task_category', $task);
    property_echo_value_tr('task_os', $task);
    property_echo_value_tr('additional_os', $task, false);
    property_echo_value_tr('task_browser', $task);
    property_echo_value_tr('additional_browser', $task, false);
    property_echo_value_tr('votes', $task, false);
    echo "</table>";

    echo "<table class='task-detail-block'>\n";
    property_echo_value_tr('task_type', $task);
    property_echo_value_tr('opened_composite', $task);
    property_echo_value_tr('edited_composite', $task);
    property_echo_value_tr('task_status', $task);
    property_echo_value_tr('closed_composite', $task, false);
    property_echo_value_tr('closed_reason', $task, false);
    property_echo_value_tr('maybe_close_button', $task, false);
    property_echo_value_tr('task_assignee', $task);
    property_echo_value_tr('percent_complete', $task);
    echo "</table>";
    echo "</div>";

    echo "<h2 style='clear: both; padding-top: 0.5em;'>" . _("Details") . "</h2>\n";
    echo "<p>";
    echo property_format_value('task_details', $task, false);
    echo "</p>";

    if (!$task['closed_reason']) {
        MeToo($tid, $task['task_os'], $task['task_browser']);
    }

    TaskComments($tid, $action);
    if ($action != 'edit_comment') {
        RelatedTasks($tid);
        RelatedPostings($tid);
    }
}

function property_echo_value_tr($property_id, $row, $show_if_empty = true)
{
    $label = property_get_label($property_id, false);
    $formatted_value = property_format_value($property_id, $row, false);

    if (!$show_if_empty && !$formatted_value) {
        return;
    }

    echo "<tr>";
    echo "<th>$label</th>";
    echo "<td>$formatted_value</td>";
    echo "</tr>";
    echo "\n";
}

function get_me_too_count($task_id, $requester_u_id)
{
    $sql = sprintf(
        "
        SELECT count(*)
        FROM tasks_votes
        WHERE task_id = %d AND u_id = %d
        ",
        $task_id,
        $requester_u_id
    );
    $result = DPDatabase::query($sql);
    [$meTooCheck] = mysqli_fetch_row($result);
    return $meTooCheck;
}

function MeToo($tid, $os, $browser)
{
    global $tasks_url, $browser_array, $os_array;
    global $requester_u_id;

    $meTooAllowed = get_me_too_count($tid, $requester_u_id) == 0;

    echo "<div id='MeTooButton'>";
    if ($meTooAllowed) {
        echo "<input type='button' value='" . attr_safe(_("Me Too!")) . "' onClick=\"showSpan('MeTooMain'); hideSpan('MeTooButton');\">";
    } else {
        echo "<input type='button' value='" . attr_safe(_('Already submitted "Me Too!"')) . "' disabled>";
    }
    echo "</div>";

    echo "<div id='MeTooMain' style='display: none;'>";
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='action' value='add_metoo'>";
    echo "<input type='hidden' name='task_id' value='$tid'>";
    echo "<table><tr><td>\n";
    echo "<h3>" . _("You too?") . "</h3>";
    echo "<table class='task-detail-block' style='width: auto'>";
    echo "<tr>";
    echo "<th>" . _("Operating System") . "</th>";
    echo "<td>";
    echo dropdown_select('metoo_os', $os, $os_array);
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>" . _("Browser") . "</th>";
    echo "<td>";
    echo dropdown_select('metoo_browser', $browser, $browser_array);
    echo "</td>";
    echo "</th>";
    echo "</tr>";
    echo "</table>";
    echo "<input type='submit' value='" . attr_safe(_("Send Report")) . "'>";
    echo "&nbsp;";
    echo "<input type='reset' value='" . attr_safe(_("Cancel")) . "' onClick=\"hideSpan('MeTooMain'); showSpan('MeTooButton');\">";
    echo "</td></tr></table></form></div>";
}

function ShowError($message, $goback = false)
{
    TaskHeader(_("Task Error"));
    echo "<p class='error'>";
    if ($goback) {
        $message .= "<br>" . sprintf(_("Please go <a %s>back</a> and correct this."), "href='javascript:history.back()'");
    }
    echo "$message</p>\n";
}

function create_anchor_for_comment($u_id, $comment_date)
{
    return "$u_id" . '_' . "$comment_date";
}

function TaskComments($tid, $action)
{
    global $tasks_url, $requester_u_id, $now_sse;
    $sql = sprintf(
        "
        SELECT *
        FROM tasks_comments
        WHERE task_id = %d
        ORDER BY comment_date ASC
        ",
        $tid
    );
    $result = DPDatabase::query($sql);

    echo "<h2>" . _("Comments") . "</h2>";
    $Parsedown = new ParsedownExtra();
    $Parsedown->setSafeMode(true);
    while ($row = mysqli_fetch_assoc($result)) {
        $comment_id = create_anchor_for_comment($row['u_id'], $row['comment_date']);

        // Can edit if comment creator AND created less than 24 hours ago OR User is a Site Admin
        $can_edit_comment = ($requester_u_id == $row['u_id'] && $now_sse - (int)$row['comment_date'] <= 86400) || user_is_a_sitemanager();
        echo "<div class='task-comment' id='$comment_id'>";
        $comment_username_link = private_message_link_for_uid($row['u_id']);
        echo "<b>$comment_username_link - " . date("l d M Y @ g:ia", $row['comment_date']) . "</b>";
        if ($can_edit_comment && $action != 'edit_comment') {
            echo " - <a href='$tasks_url?action=edit_comment&task_id=$tid&comment_id=$comment_id#$comment_id'>" . _("edit") . "</a>";
        }
        echo "<br>\n";
        echo "<div class='task-comment-body'>";
        if ($action == 'edit_comment' && $can_edit_comment && $comment_id == $_GET['comment_id']) {
            echo "<form action='$tasks_url' method='post'>";
            echo "<textarea name='task_comment' style='width: 99%; height: 9em;'>";
            echo html_safe($row['comment']);
            echo "</textarea>";
            echo "<input type='hidden' name='' value=''>";
            echo "<input type='hidden' name='task_id' value='$tid'>";
            echo "<input type='hidden' name='comment_id' value='$comment_id'>";
            echo "<input type='hidden' name='action' value='save_edit_comment'>";
            echo "<input type='submit' value='" . attr_safe(_("Save Comment")) . "'>";
        } else {
            echo $Parsedown->text($row['comment']);
        }
        echo "</div>";
        echo "</div>";
    }

    if ($action != 'edit_comment') {
        echo "<h3>" . _("Add comment") . "</h3>";
        echo "<form action='$tasks_url' method='post'>";
        echo "<input type='hidden' name='action' value='add_comment'>";
        echo "<input type='hidden' name='task_id' value='$tid'>";
        echo "<textarea name='task_comment' style='width: 99%; height: 9em;'></textarea>";
        echo "<input type='submit' value='" . attr_safe(_("Add Comment")) . "'>\n";
        echo "</form>";
    }
}

function NotificationMail($tid, $message, $new_task = false)
{
    global $site_abbreviation, $code_url, $tasks_url, $pguser, $site_name;

    $task = load_task($tid);
    if (!$task) {
        return;
    }
    $task_summary = $task['task_summary'];

    $subject = "$site_abbreviation Task #$tid: $task_summary";
    $footer = "\n\n$tasks_url?task_id=$tid";

    if ($new_task) {
        $notify_setting_this = Settings::get_users_with_setting('taskctr_notice', 'notify_new');
        $body =
            "You have requested notification of new tasks.\n\n"
            . "Task #$tid: '$task_summary' was created by $pguser.\n\n"
            . $task['task_details'] . "\n"
            . $footer;
    } else {
        $notify_setting_this = Settings::get_users_with_setting('taskctr_notice', $tid);
        $body =
            "You have requested notification of updates to task #$tid: $task_summary\n\n"
            . $message
            . $footer;
    }
    $notify_setting_all = Settings::get_users_with_setting('taskctr_notice', 'all');
    $users_to_notify = array_unique(array_merge($notify_setting_all, $notify_setting_this));
    foreach ($users_to_notify as $username) {
        if ($username != $pguser) {
            $user = new User($username);
            send_mail($user->email, $subject, $body);
        }
    }
}

function RelatedTasks($tid)
{
    global $tasks_url, $tasks_status_array;
    echo "<h2>" . _("Related Tasks") . "</h2>";
    echo "<form action='$tasks_url' method='post' style='padding-bottom: 0.5em;'>";
    echo "<input type='hidden' name='action' value='add_related_task'>";
    echo "<input type='hidden' name='task_id' value='$tid'>";
    echo "<input type='number' name='related_task' min='1' style='width: 5em;' required>&nbsp;&nbsp;";
    echo "<input type='submit' value='" . attr_safe(_("Add")) . "'>\n";
    echo " (" . _("Add the number of an existing, related task.") . ")";
    echo "</form>";

    echo "<table class='themed theme_striped'>\n";
    $related_tasks = load_related_tasks($tid);
    foreach ($related_tasks as $val) {
        $task = load_task($val);
        $related_task_summary = html_safe($task["task_summary"]);
        $related_task_status = $tasks_status_array[$task["task_status"]];

        echo "<tr><td>";
        echo "<a href='$tasks_url?action=show&task_id=$val'>" . sprintf(_("Task #%d"), $val) . "</a> ($related_task_status) - $related_task_summary";
        if ($task) {
            echo " ";
            echo "<form action='$tasks_url' method='post' style='display: inline'>";
            echo "<input type='hidden' name='action' value='remove_related_task'>";
            echo "<input type='hidden' name='task_id' value='$tid'>";
            echo "<input type='hidden' name='related_task' value='$val'>";
            echo "<input type='submit' value='" . attr_safe(_("Remove")) . "'>";
            echo "</form>";
        }
        echo "</td></tr>";
    }
    echo "</table>";
}

function load_related_tasks($task_id)
{
    $sql = sprintf(
        "
        SELECT task_id_1, task_id_2
        FROM tasks_related_tasks
        WHERE task_id_1 = %d
            OR task_id_2 = %d
        ",
        $task_id,
        $task_id
    );

    $result = DPDatabase::query($sql);

    $related_tasks = [];
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['task_id_1'] != $task_id) {
            $related_tasks[] = $row['task_id_1'];
        } else {
            $related_tasks[] = $row['task_id_2'];
        }
    }

    sort($related_tasks);
    return $related_tasks;
}

function insert_related_task($task1, $task2)
{
    $task_id_1 = min($task1, $task2);
    $task_id_2 = max($task1, $task2);

    // See if the association already exists
    $sql = sprintf(
        "
        SELECT COUNT(*) AS count
        FROM tasks_related_tasks
        WHERE task_id_1 = %d
            AND task_id_2 = %d
        ",
        $task_id_1,
        $task_id_2
    );

    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] > 0) {
        return;
    }

    // Now do the insertion
    $sql = sprintf(
        "
        INSERT INTO tasks_related_tasks
        SET task_id_1 = %d, task_id_2 = %d
        ",
        $task_id_1,
        $task_id_2
    );
    $result = DPDatabase::query($sql);
}

function remove_related_task($task1, $task2)
{
    $task_id_1 = min($task1, $task2);
    $task_id_2 = max($task1, $task2);

    // Now do the insertion
    $sql = sprintf(
        "
        DELETE FROM tasks_related_tasks
        WHERE task_id_1 = %d
            AND task_id_2 = %d
        ",
        $task_id_1,
        $task_id_2
    );
    $result = DPDatabase::query($sql);
}

function RelatedPostings($tid)
{
    global $tasks_url;
    $task = load_task($tid);
    $related_postings = $task["related_postings"];
    echo "<h2>" . _("Related Topics") . "</h2>";
    echo "<form action='$tasks_url' method='post' style='padding-bottom: 0.5em;'>";
    echo "<input type='hidden' name='action' value='add_related_topic'>";
    echo "<input type='hidden' name='task_id' value='$tid'>";
    echo "<input type='number' name='related_posting' min='1' style='width: 5em;' required>&nbsp;&nbsp;";
    echo "<input type='submit' value='" . attr_safe(_("Add")) . "'>\n";
    echo " (" . _("Add the number of a forum topic.") . ")";
    echo "</form>";

    echo "<table class='themed theme_striped'>\n";
    $related_postings = decode_array($related_postings);
    asort($related_postings);
    foreach ($related_postings as $topic_id) {

        echo "<tr><td>";

        if (does_topic_exist($topic_id)) {
            $row = get_topic_details($topic_id);
            $forum_url = get_url_to_view_forum($row["forum_id"]);
            $topic_url = get_url_to_view_topic($row["topic_id"]);
            echo "<a href='$forum_url'>" . $row['forum_name'] . "</a>";
            echo "&nbsp;&raquo;&nbsp;";
            echo "<a href='$topic_url'>" . $row['title'] . "</a>";
            echo " (" . sprintf(_('Posted by: %1$s - %2$d replies'), $row['creator_username'], $row['num_replies']) . ")\n";
        } else {
            // If the topic doesn't exist, most likely because it was merged
            // into another topic, we should log an error and still give the
            // user a way to remove it.
            error_log("tasks.php - task $tid is linked to non-existent topic $topic_id");
            echo sprintf(_("Topic %d is linked but no longer exists"), $topic_id);
        }

        echo " ";
        echo "<form action='$tasks_url' method='post' style='display: inline;'>";
        echo "<input type='hidden' name='action' value='remove_related_topic'>";
        echo "<input type='hidden' name='task_id' value='$tid'>";
        echo "<input type='hidden' name='related_posting' value='$topic_id'>";
        echo "<input type='submit' value='" . attr_safe(_("Remove")) . "'>\n";
        echo "</form>";
        echo "</td></tr>";
    }
    echo "</table>";
}

function property_get_label($property_id, $for_list_of_tasks)
{
    switch ($property_id) {
        case 'date_edited':
            return _('Date Edited');
        case 'task_assignee':
            return _('Assigned To');
        case 'task_browser':
            return _('Browser');
        case 'task_category':
            return _('Category');
        case 'task_id':
            return _('ID');
        case 'task_os':
            return _('Operating System');
        case 'task_priority':
            return _('Priority');
        case 'task_severity':
            return _('Severity');
        case 'task_status':
            return _('Status');
        case 'task_summary':
            return _('Summary');
        case 'task_type':
            return _('Task Type');
        case 'votes':
            return _('Votes');
        case 'additional_os':
            return '';
        case 'additional_browser':
            return '';
        case 'opened_composite':
            return _("Opened");
        case 'edited_composite':
            return _("Last Edited");
        case 'closed_composite':
            return _("Closed By");
        case 'closed_reason':
            return _("Closed Reason");
        case 'percent_complete':
            return ($for_list_of_tasks ? _("Progress") : _("Percent Complete"));
    }
}

function property_format_value($property_id, $task_a, $for_list_of_tasks)
{
    global $code_url, $tasks_url;
    global $browser_array;
    global $categories_array;
    global $os_array;
    global $priority_array;
    global $severity_array;
    global $tasks_array;
    global $tasks_close_array;
    global $tasks_status_array;

    $raw_value = array_get($task_a, $property_id, null);
    switch ($property_id) {
        // The raw value is used directly:
        case 'task_id':
            $fv = $raw_value;
            break;

            // The raw value is an index into an array.
        case 'closed_reason':
            return array_get($tasks_close_array, $raw_value, "");
        case 'task_browser':
            return $browser_array[$raw_value];
        case 'task_category':
            return $categories_array[$raw_value];
        case 'task_os':
            return $os_array[$raw_value];
        case 'task_priority':
            return $priority_array[$raw_value];
        case 'task_severity':
            return $severity_array[$raw_value];
        case 'task_status':
            return $tasks_status_array[$raw_value];
        case 'task_type':
            return $tasks_array[$raw_value];

            // The raw value is an integer denoting seconds-since-epoch.
        case 'date_edited':
            return date("d-M-Y", $raw_value);
        case 'date_opened':
            return date("d-M-Y", $raw_value);
        case 'date_closed':
            return $raw_value ? date("d-M-Y", $raw_value) : "";

            // Synthetic fields
        case 'opened_composite':
            return sprintf(
                "%s &mdash; %s",
                date("d-M-Y", $task_a["date_opened"]),
                private_message_link_for_uid($task_a['opened_by'])
            );
        case 'edited_composite':
            return sprintf(
                "%s &mdash; %s",
                date("d-M-Y", $task_a["date_edited"]),
                private_message_link_for_uid($task_a['edited_by'])
            );
        case 'closed_composite':
            if (!$task_a["date_closed"]) {
                return "";
            }

            return sprintf(
                "%s &mdash; %s",
                date("d-M-Y", $task_a["date_closed"]),
                private_message_link_for_uid($task_a['closed_by'])
            );

            // The raw value is a user's u_id:
        case 'opened_by':
            return $raw_value ? private_message_link_for_uid($raw_value) : "";
        case 'edited_by':
            return $raw_value ? private_message_link_for_uid($raw_value) : "";
        case 'closed_by':
            return $raw_value ? get_username_for_uid($raw_value) : "";
        case 'task_assignee':
            return (
                empty($raw_value)
                ? "Unassigned"
                : private_message_link_for_uid($raw_value)
            );

            // The raw value is some text typed in by a user:
        case 'task_summary':
            $fv = html_safe($raw_value);
            break;

        case 'task_details':
            $Parsedown = new Parsedown();
            $Parsedown->setSafeMode(true);
            return $Parsedown->text($raw_value);

            // The raw value is an integer denoting state of progress:
        case 'percent_complete':
            // Calculate the width for the container based on if this is a
            // task listing or a task details
            $div_width = $for_list_of_tasks ? '50' : '150';

            [$progress_bar_width, $progress_bar_class] =
                calculate_progress_bar_properties($raw_value, 100, false, [0 => "goal-on-target"]);

            return "
                <div class='default-border' style='width: {$div_width}px;'>
                    <div class='progressbar $progress_bar_class'
                        style='width: $progress_bar_width%; border: 0;'>&nbsp;
                    </div>
                </div>";

        case 'votes':
            // If this is the task listing, $raw_value will be set
            if (!isset($raw_value)) {
                $sql = sprintf(
                    "
                    SELECT count(*) AS count
                    FROM tasks_votes
                    WHERE task_id = %d
                    ",
                    $task_a['task_id']
                );
                $result = DPDatabase::query($sql);
                [$raw_value] = mysqli_fetch_row($result);
            }

            // If votes are zero, return an empty string
            if ($raw_value == 0) {
                return "";
            } else {
                return $raw_value;
            }

            // no break
        case 'additional_os':
            $sql = sprintf(
                "
                SELECT DISTINCT vote_os
                FROM tasks_votes
                WHERE task_id = %d
                ",
                $task_a['task_id']
            );
            $result = DPDatabase::query($sql);
            $list = [];
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['vote_os'] == $task_a['task_os']) {
                    continue;
                }
                $list[] = $os_array[$row['vote_os']];
            }
            array_unique($list);
            if ($list) {
                return implode(", ", $list);
            } else {
                return "";
            }

            // no break
        case 'additional_browser':
            $sql = sprintf(
                "
                SELECT DISTINCT vote_browser
                FROM tasks_votes
                WHERE task_id = %d
                ",
                $task_a['task_id']
            );
            $result = DPDatabase::query($sql);
            $list = [];
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['vote_browser'] == $task_a['task_browser']) {
                    continue;
                }
                $list[] = $browser_array[$row['vote_browser']];
            }
            array_unique($list);
            if ($list) {
                return implode(", ", $list);
            } else {
                return "";
            }

            // no break
        case 'maybe_close_button':
            if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && empty($task_a['closed_reason'])) {
                $dropdown = dropdown_select('closed_reason', "", $tasks_close_array);

                return "
                      <form action='$tasks_url' method='post'>
                        <input type='hidden' name='action' value='close'>
                        <input type='hidden' name='task_id' value='" . $task_a['task_id'] . "'>
                        $dropdown
                        <input type='submit' value='" . attr_safe(_("Close Task")) . "'>
                      </form>
                ";
            } else {
                return "";
            }

            // no break
        default:
            assert(false);
    }

    // Cases that don't return directly,
    // but instead set $fv and then break,
    // fall through to here.
    // If appropriate, wrap $fv in an <a> element
    // that links to the task's details page.

    assert(isset($fv));
    if ($for_list_of_tasks) {
        $url = "$tasks_url?action=show&task_id=" . $task_a['task_id'];
        $fv = "<a href='$url'>$fv</a>";
    }
    return $fv;
}

function private_message_link_for_uid($u_id)
// Return a 'private message link' for the user specified by $u_id.
{
    $username = get_username_for_uid($u_id);
    $link = private_message_link($username, null);
    return $link;
}

function get_username_for_uid($u_id)
{
    $user = User::load_from_uid($u_id);
    return $user->username;
}

/**
 * Given a task row from the DB, produce an unescaped string representing
 * the task and suitable for display in a page title or similar.
 */
function title_string_for_task($pre_task)
{
    return sprintf(_("Task #%d: %s"), $pre_task->task_id, $pre_task->task_summary);
}

/**
 * Get key corresponding to a task properties string
 *
 * Input string must be a valid property value
 */
function get_property_key($value, $array)
{
    if (($key = array_search($value, $array)) === false) {
        throw new ValueError("Bad task property value: $value");
    }
    return $key;
}
