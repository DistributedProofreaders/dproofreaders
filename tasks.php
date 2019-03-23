<?php
$relPath='pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe(), array_get()
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'User.inc');
include_once($relPath.'links.inc'); // private_message_link()
include_once($relPath.'misc.inc'); // get_enumerated_param(), str_contains(), echo_html_comment()
include_once($relPath.'metarefresh.inc');

require_login();

$tasks_url = $code_url . "/" . basename(__FILE__);

$requester_u_id = $userP['u_id'];

$now_sse = time();
// The current time, expressed as Seconds Since the (Unix) Epoch.

$date_str = date("l, F jS, Y", $now_sse);
$time_of_day_str = date("g:i a", $now_sse);

// ---------------------------------------------------------
// Convert old-style GET requests into new-style,
// in case people have them in bookmarks/links.

if (isset($_GET['f']) && !isset($_GET['action']))
{
    $f_map = array(
        'newtask'    => 'show_creation_form',
        'detail'     => 'show',
        'notifyme'   => 'notify_me',
        'unnotifyme' => 'unnotify_me',
    );
    $f = get_enumerated_param($_GET, 'f', null, array_keys($f_map));
    $_REQUEST['action'] = $_GET['action'] = $f_map[$f];
    unset($_GET['f']);
    unset($_REQUEST['f']);
}

if (isset($_GET['tid']) && !isset($_GET['task_id']))
{
    $_REQUEST['task_id'] = $_GET['task_id'] = $_GET['tid'];
    unset($_GET['tid']);
    unset($_REQUEST['tid']);
}

if (isset($_GET['search_text']) && !isset($_GET['action']))
{
    $_REQUEST['action'] = $_GET['action'] = 'search';
}

// ---------------------------------------------------------

$request_method = $_SERVER['REQUEST_METHOD'];
if ($request_method == 'GET')
{
    $valid_actions = array(
        'show_creation_form',
        'show',
        'notify_me',
        'unnotify_me',
        'search',
        'list_open',
        'notify_new',
        'unnotify_new'
    );
    $action = get_enumerated_param($_GET, 'action', null, $valid_actions, true);
}
elseif ($request_method == 'POST')
{
    $valid_actions = array(
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
    );
    $action = get_enumerated_param($_POST, 'action', null, $valid_actions);
}
else
{
    die("unexpected REQUEST_METHOD: '$request_method'");
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// This section sets up all the "pick from a list" properties of a task.

$tasks_array = array(
    1 => "Bug Report",
    2 => "Feature Request",
    3 => "Support Request",
    4 => "Site Administrator Request"
);
$severity_array = array(
    1 => "Catastrophic",
    2 => "Critical",
    3 => "Major",
    4 => "Normal",
    5 => "Minor",
    6 => "Trivial",
    7 => "Enhancement"
);
$priority_array = array(
    1 => "Very High",
    2 => "High",
    3 => "Medium",
    4 => "Low",
    5 => "Very Low"
);
$categories_array = array(
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
    99 => "Other"
);
asort($categories_array);
$tasks_status_array = array(
    1 => "New",
    2 => "Accepted",
    3 => "Duplicate",
    4 => "Fixed",
    5 => "Invalid",
    6 => "Later",
    7 => "None",
    8 => "Out of Date",
    9 => "Postponed",
    10 => "Rejected",
    11 => "Remind",
    12 => "Won't Fix",
    13 => "Works for Me",
    14 => "Closed",
    15 => "Reopened",
    16 => "Researching",
    17 => "Implemented",
    18 => "In Progress"
);
asort($tasks_status_array);
$os_array = array(
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
    15 => "Mac OS X",
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
    99 => "Other"
);
asort($os_array);
$browser_array = array(
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
    99 => "Other"
);
asort($browser_array);
$versions_array = array(
    1 => "pgdp.net (Live)",
    4 => "dp.rastko.net (Live)",
    5 => "pgdpcanada.net (Live)",
    2 => "pgdp.org (Test Server)",
    3 => "CVS"
);
$tasks_close_array = array(
    1 => "Not a Bug",
    2 => "Won't Fix",
    3 => "Won't Implement",
    4 => "Works for Me",
    5 => "Duplicate",
    6 => "Deferred",
    7 => "Fixed",
    8 => "Implemented",
    9 => "Resolved"
);
asort($tasks_close_array);
$percent_complete_array = array(
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
    100 => "100%"
);

$task_assignees_array = array();
$taskcenter_managers = array_unique(array_merge(
    Settings::get_users_with_setting('sitemanager', 'yes'),
    Settings::get_users_with_setting('task_center_mgr', 'yes')
));
foreach($taskcenter_managers as $taskcenter_manager) {
    $user = new User($taskcenter_manager);
    $task_assignees_array[$user->u_id] = $taskcenter_manager;
}
natcasesort($task_assignees_array);
$task_assignees_array = array(0 => 'Unassigned') + $task_assignees_array;

// -----------------------------------------------------------------------------

$SearchParams_choices = array(
    'task_type'     => array(999 => 'All Task Types') + $tasks_array,
    'task_severity' => array(999 => 'All Severities') + $severity_array,
    'task_priority' => array(999 => 'All Priorities') + $priority_array,
    'task_assignee' => array(999 => 'All Developers') + $task_assignees_array,
    'task_category' => array(999 => 'All Categories') + $categories_array,
    'task_status'   => array(998 => 'All Tasks', 999 => 'All Open Tasks') + $tasks_status_array,
    'task_version'  => array(999 => 'All Versions') + $versions_array,
);

// XXX Re task_assignee, there's a long-standing bug involving
// a sitemanager/task_center_mgr whose u_id happens to be 999.
// However, there's a fairly low chance of it ever being triggered.

function SearchParams_echo_controls()
// For each of the search parameters, echo its control (HTML markup),
// initializing it with any (valid) value that the current request
// has supplied for the parameter.
{
    global $SearchParams_choices;

    if (isset($_REQUEST['search_text']) && !empty($_REQUEST['search_text'])) {
        $st = $_REQUEST['search_text'];
        $search_text = attr_safe($st);
    }
    else $search_text = "";

    echo "<input type='hidden' name='action' value='search'>\n";
    echo "<input type='text' value='$search_text' name='search_text' size='50'>\n";

    foreach ($SearchParams_choices as $param_name => $choices)
    {
        $value = (int) get_enumerated_param($_REQUEST, $param_name, '999', array_keys($choices));
        dropdown_select($param_name, $value, $choices);
    }
}

function SearchParams_get_sql_condition($request_params)
// Return a SQL condition that expresses the restriction on tasks
// implied by the values (if any) supplied for the search parameters
// by the current request.
{
    global $testing, $SearchParams_choices;

    $condition = "1";
    if(isset($request_params['search_text']))
    {
        $search_text = normalize_whitespace($request_params['search_text']);
        if ($testing)
            echo_html_comment("\$request_params['search_text'] = $search_text");

        $condition .= sprintf(" AND
            (
                POSITION('%1\$s' IN task_summary)
                OR
                POSITION('%1\$s' IN task_details)
            )
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $search_text));
    }

    // ------

    foreach ($SearchParams_choices as $param_name => $choices)
    {
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

function SearchParams_get_url_query_string()
// Return a string (suitable for use in the 'query string' portion of a URL)
// that represents (and possibly just reiterates) the values (if any)
// supplied for the search parameters by the current request.
{
    global $SearchParams_choices;

    if (isset($_REQUEST['search_text'])) {
        $t = "action=search&search_text=" . urlencode($_REQUEST['search_text']);
        foreach ($SearchParams_choices as $param_name => $choices)
        {
            $value = get_enumerated_param($_REQUEST, $param_name, '999', array_keys($choices));
            $t .= "&{$param_name}={$value}";
        }
        $t .= "&";
    }
    else {
        $t = "";
    }
    return $t;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function make_default_task_object()
{
    $task = new stdClass;
    $task->task_version     = 1;
    $task->task_severity    = 4;
    $task->task_priority    = 3;
    $task->task_type        = 1;
    $task->task_category    = 1;
    $task->task_status      = 1;
    $task->task_os          = 0;
    $task->task_browser     = 0;
    $task->task_assignee    = 0;
    $task->task_summary     = "";
    $task->task_details     = "";
    $task->percent_complete = 0;
    $task->opened_by        = "";
    $task->task_id          = "";
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
    global $versions_array;
    global $now_sse;
    global $requester_u_id;
    global $site_abbreviation;

    $task_summary = trim(array_get($formsub, 'task_summary', ''));
    $task_details = trim(array_get($formsub, 'task_details', ''));

    if (empty($task_summary) || empty($task_details)) {
        return "You must supply a Task Summary and Task Details.";
    }

    assert (!isset($formsub['task_id']));
    // Create a new task.
    $relatedtasks_array = array();
    $relatedtasks_array = base64_encode(serialize($relatedtasks_array));
    $relatedpostings_array = array();
    $relatedpostings_array = base64_encode(serialize($relatedpostings_array));
    $newt_type     = (int) get_enumerated_param($formsub, 'task_type', null, array_keys($tasks_array));
    $newt_category = (int) get_enumerated_param($formsub, 'task_category', null, array_keys($categories_array));
    $newt_status   = (int) get_enumerated_param($formsub, 'task_status', null, array_keys($tasks_status_array));
    $newt_assignee = (int) get_enumerated_param($formsub, 'task_assignee', null, array_keys($task_assignees_array));
    $newt_severity = (int) get_enumerated_param($formsub, 'task_severity', null, array_keys($severity_array));
    $newt_priority = (int) get_enumerated_param($formsub, 'task_priority', null, array_keys($priority_array));
    $newt_os       = (int) get_enumerated_param($formsub, 'task_os', null, array_keys($os_array));
    $newt_browser  = (int) get_enumerated_param($formsub, 'task_browser', null, array_keys($browser_array));
    $newt_version  = (int) get_enumerated_param($formsub, 'task_version', null, array_keys($versions_array));

    // Validate the assignee, skipping the case where it is 0 (Unassigned).
    if($newt_assignee != 0)
    {
        $task_assignee_user = User::load_from_uid($newt_assignee);
    }

    $sql_query = sprintf("
        INSERT INTO tasks
        SET
            task_summary     = '%s',
            task_type        = $newt_type,
            task_category    = $newt_category,
            task_status      = $newt_status,
            task_assignee    = $newt_assignee,
            task_severity    = $newt_severity,
            task_priority    = $newt_priority,
            task_os          = $newt_os,
            task_browser     = $newt_browser,
            task_version     = $newt_version,
            task_details     = '%s',
            date_opened      = $now_sse,
            opened_by        = $requester_u_id,
            date_edited      = $now_sse,
            edited_by        = $requester_u_id,
            percent_complete = 0,
            related_tasks    = '$relatedtasks_array',
            related_postings = '$relatedpostings_array'
    ",
        mysqli_real_escape_string(DPDatabase::get_connection(), $task_summary),
        mysqli_real_escape_string(DPDatabase::get_connection(), $task_details)
    );
    wrapped_mysql_query($sql_query);
    $task_id = mysqli_insert_id(DPDatabase::get_connection());

    global $pguser;
    NotificationMail($task_id, "", true);
    // Nobody could have subscribed to this particular task yet,
    // so the msg will only go to those with taskctr_notice = 'all' or 'notify_new'.

    // If $newt_assignee is 0, there is no user assigned so no notification
    // to send out.
    if($newt_assignee != 0)
    {
        global $tasks_url, $code_url;
        maybe_mail(
            $task_assignee_user->email,
            "$site_abbreviation Task Center: Task #$task_id has been assigned to you",
            $task_assignee_user->username . ", you have been assigned task #$task_id.  Please visit this task at $tasks_url?action=show&task_id=$task_id.\n\nIf you do not want to accept this task please edit the task and change the assignee to 'Unassigned'.\n\n--\nDistributed Proofreaders\n$code_url\n\nThis is an automated message that you had requested please do not respond directly to this e-mail.\r\n"
        );
    }

    // Subscribe the current user to this task for notification
    $userSettings =& Settings::get_Settings($pguser);
    $userSettings->add_value('taskctr_notice', $task_id);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// This is the point at which the script starts to produce output.

if (!isset($_REQUEST['task_id'])) {

    // Default 'action' when no task is specified:
    if (is_null($action)) $action = 'list_open';

    switch ( $action )
    {
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
            $header = "Task Search";
            if (!empty($_POST['search_text'])) {
                $header .= ": " . $_POST['search_text'];
            }
            TaskHeader($header);

            search_and_list_tasks($_REQUEST);
            break;

        case 'list_open':
            // The user just entered the Task Center
            // (e.g., by clicking the "Report a Bug" link).
            TaskHeader("All Open Tasks", true);
            list_all_open_tasks();
            break;

        case 'create':
            // The user is supplying values for the properties of a new task.
            $errmsg = create_task_from_form_submission($_POST);
            if ($errmsg)
            {
                ShowNotification($errmsg, true);
                break;
            }
            else
            {
                // If we successfully create the task, we should reload
                //   the page to clear the POST data and make sure that
                //   reloading does not lead to duplicated tasks.
                metarefresh(0, $tasks_url);
                break;
            }

        case 'notify_new':
            $userSettings =& Settings::get_Settings($pguser);
            $userSettings->add_value('taskctr_notice', 'notify_new');
            metarefresh(0, $tasks_url);
            break;

        case 'unnotify_new':
            $userSettings =& Settings::get_Settings($pguser);
            $userSettings->remove_value('taskctr_notice', 'notify_new');
            metarefresh(0, $tasks_url);
            break;
    }
}
else {
    handle_action_on_a_specified_task();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function handle_action_on_a_specified_task()
{
    global $pguser, $requester_u_id;
    global $now_sse, $date_str, $time_of_day_str;
    global $action, $tasks_url;

    // Default 'action' when a task is specified:
    if (is_null($action)) $action = 'show';

    $task_id = (int)get_float_param($_REQUEST, 'task_id', null, 1, null);

    // Fetch the state of the specified task
    // before any requested changes.
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM tasks WHERE task_id = $task_id");
    $pre_task = mysqli_fetch_object($result);
    if (!$pre_task)
    {
        TaskHeader("Task #$task_id does not exist");
        ShowNotification("Task #$task_id was not found!");
        return;
    }

    if ($action == 'notify_me') {
        $userSettings =& Settings::get_Settings($pguser);
        $userSettings->add_value('taskctr_notice', $task_id);
        // metarefresh with default action (=show) so that reloading page will not repeat action
        metarefresh(0, "$tasks_url?task_id=$task_id");
    }
    elseif ($action == 'unnotify_me') {
        $userSettings =& Settings::get_Settings($pguser);
        $userSettings->remove_value('taskctr_notice', $task_id);
        metarefresh(0, "$tasks_url?task_id=$task_id");
    }
    // We don't want a TaskHeader for add_comment or add_metoo
    //   because then we wouldn't be able to redirect
    //   the user.
    elseif ($action != 'add_comment' &&
            $action != 'add_metoo')
    {
        TaskHeader(title_string_for_task($pre_task));
    }

    if ($action == 'show') {
        TaskDetails($task_id);
    }
    elseif ($action == 'show_editing_form') {
        if (user_is_a_sitemanager() || user_is_taskcenter_mgr() || $pre_task->opened_by == $requester_u_id && empty($pre_task->closed_reason)) {
            // The user wants to edit an existing task.
            // Initialize the form with the current values of the task's properties.
            TaskForm($pre_task);
        }
        else {
            ShowNotification("The user $pguser does not have permission to edit this task.");
            TaskDetails($task_id);
        }
    }
    elseif ($action == 'reopen') {
        NotificationMail($task_id,
            "This task was reopened by $pguser on $date_str at $time_of_day_str.\n");
        wrapped_mysql_query("
            UPDATE tasks
            SET
                task_status = 15,
                edited_by = $requester_u_id,
                date_edited = $now_sse,
                date_closed = 0,
                closed_by = 0,
                closed_reason = 0
            WHERE task_id = $task_id
        ");
        TaskDetails($task_id);
    }
    elseif ($action == 'edit') {
        $task_summary = trim(array_get($_POST, 'task_summary', ''));
        $task_details = trim(array_get($_POST, 'task_details', ''));
        // The user is supplying values for the properties of a pre-existing task.
        if (empty($task_summary) || empty($task_details)) {
            ShowNotification("You must supply a Task Summary and Task Details.", true);
        }
        else {
            // Update a pre-existing task.
            NotificationMail($task_id,
                "There has been an edit made to this task by $pguser on $date_str at $time_of_day_str.\n");

            global $tasks_array;
            global $categories_array;
            global $tasks_status_array;
            global $task_assignees_array;
            global $severity_array;
            global $priority_array;
            global $os_array;
            global $browser_array;
            global $versions_array;
            global $percent_complete_array;

            $edit_type     = (int) get_enumerated_param($_POST, 'task_type', null, array_keys($tasks_array));
            $edit_category = (int) get_enumerated_param($_POST, 'task_category', null, array_keys($categories_array));
            $edit_status   = (int) get_enumerated_param($_POST, 'task_status', null, array_keys($tasks_status_array));
            $edit_assignee = (int) get_enumerated_param($_POST, 'task_assignee', null, array_keys($task_assignees_array));
            $edit_severity = (int) get_enumerated_param($_POST, 'task_severity', null, array_keys($severity_array));
            $edit_priority = (int) get_enumerated_param($_POST, 'task_priority', null, array_keys($priority_array));
            $edit_os       = (int) get_enumerated_param($_POST, 'task_os', null, array_keys($os_array));
            $edit_browser  = (int) get_enumerated_param($_POST, 'task_browser', null, array_keys($browser_array));
            $edit_version  = (int) get_enumerated_param($_POST, 'task_version', null, array_keys($versions_array));
            $edit_percent  = (int) get_enumerated_param($_POST, 'percent_complete', null, array_keys($percent_complete_array));

            $sql_query = sprintf("
                UPDATE tasks
                SET
                    task_summary     = '%s',
                    task_type        = $edit_type,
                    task_category    = $edit_category,
                    task_status      = $edit_status,
                    task_assignee    = $edit_assignee,
                    task_severity    = $edit_severity,
                    task_priority    = $edit_priority,
                    task_os          = $edit_os,
                    task_browser     = $edit_browser,
                    task_version     = $edit_version,
                    task_details     = '%s',
                    date_edited      = $now_sse,
                    edited_by        = $requester_u_id,
                    percent_complete = $edit_percent
                WHERE task_id = $task_id
            ",
                mysqli_real_escape_string(DPDatabase::get_connection(), $task_summary),
                mysqli_real_escape_string(DPDatabase::get_connection(), $task_details)
            );
            wrapped_mysql_query($sql_query);

            set_window_title("All Open Tasks");
            list_all_open_tasks();
        }
    }
    elseif ($action == 'close') {
        global $tasks_close_array;
        if (user_is_a_sitemanager() || user_is_taskcenter_mgr()) {
            $tc_reason = (int) get_enumerated_param($_POST, 'closed_reason', null, array_keys($tasks_close_array));
            NotificationMail($task_id,
                "This task was closed by $pguser on $date_str at $time_of_day_str.\n\nThe reason for closing was: " . $tasks_close_array[$tc_reason] . ".\n");
            wrapped_mysql_query("
                UPDATE tasks
                SET
                    percent_complete = 100,
                    task_status = 14,
                    date_closed = $now_sse,
                    closed_by = $requester_u_id,
                    closed_reason = $tc_reason,
                    date_edited = $now_sse,
                    edited_by = $requester_u_id
                WHERE task_id = $task_id
            ");

            set_window_title("All Open Tasks");
            list_all_open_tasks();
        }
        else {
            ShowNotification("The user $pguser does not have permission to close tasks.");
        }
    }
    elseif ($action == 'add_comment') {
        $comment = trim(array_get($_POST, 'task_comment', ''));
        if ($comment) {
            NotificationMail($task_id,
                "There has been a comment added to this task by $pguser on $date_str at $time_of_day_str.\n");
            wrapped_mysql_query(sprintf("
                INSERT INTO tasks_comments (task_id, u_id, comment_date, comment)
                VALUES ($task_id, $requester_u_id, $now_sse, '%s')",
                    mysqli_real_escape_string(DPDatabase::get_connection(), $comment)
            ));
            wrapped_mysql_query("
                UPDATE tasks
                SET date_edited = $now_sse, edited_by = $requester_u_id
                WHERE task_id = $task_id
            ");

            // After posting the comment, we should reload as to clear POST data
            //   and avoid comments being posted multiple times.
            metarefresh(0, "$tasks_url?action=show&task_id=$task_id");
        }
        else {
            // We skipped outputting TaskHeader in the success case so we can
            // metarefresh back to the task. In the error case we need to build
            // the page out however.
            TaskHeader(title_string_for_task($pre_task));
            ShowNotification("You must supply a comment before clicking Add Comment.");
            TaskDetails($task_id);
        }
    }
    elseif ($action == 'add_related_task') {
        $related_task_id = (int)get_float_param($_POST, 'related_task', null, 1, null);
        process_related_task($pre_task, 'add', $related_task_id);
    }
    elseif ($action == 'remove_related_task') {
        $related_task_id = (int)get_float_param($_POST, 'related_task', null, 1, null);
        process_related_task($pre_task, 'remove', $related_task_id);
    }
    elseif ($action == 'add_related_topic') {
        $related_posting_topic = (int)get_float_param($_POST, 'related_posting', null, 1, null);
        process_related_topic($pre_task, 'add', $related_posting_topic);
    }
    elseif ($action == 'remove_related_topic') {
        $related_posting_topic = (int)get_float_param($_POST, 'related_posting', null, 1, null);
        process_related_topic($pre_task, 'remove', $related_posting_topic);
    }
    elseif ($action == 'add_metoo') {
        global $os_array, $browser_array;
        $sameOS        = get_integer_param($_REQUEST, 'sameOS', null, 0, 1);
        $sameBrowser   = get_integer_param($_REQUEST, 'sameBrowser', null, 0, 1);
        $os_param_name      = ( $sameOS      ? 'task_os'      : 'metoo_os' );
        $browser_param_name = ( $sameBrowser ? 'task_browser' : 'metoo_browser' );
        $vote_os       = (int) get_enumerated_param($_POST, $os_param_name, null, array_keys($os_array));
        $vote_browser  = (int) get_enumerated_param($_POST, $browser_param_name, null, array_keys($browser_array));

        // Do not insert twice the same vote if the user refreshes the browser
        $meTooCheck = mysqli_query(DPDatabase::get_connection(), "
            SELECT 1 FROM tasks_votes WHERE task_id = $task_id and u_id = $requester_u_id LIMIT 1
        ");
        if (mysqli_num_rows($meTooCheck) == 0)
        {
            wrapped_mysql_query("
                INSERT INTO tasks_votes 
                (task_id, u_id, vote_os, vote_browser) 
                VALUES ($task_id, $requester_u_id, $vote_os, $vote_browser)
            ");
        }
        mysqli_free_result($meTooCheck);

        // Redirect back to show task page to clear POST data
        metarefresh(0, "$tasks_url?action=show&task_id=$task_id");
    }
    else {
        die("shouldn't be able to reach here");
    }
}

// Add or remove a related task to the curent task.
function process_related_task($pre_task, $action, $related_task_id)
{
    global $pguser, $date_str, $time_of_day_str;
    assert($action == 'add' || $action == 'remove');

    // Validate task_id. It must be an integer >= 1
    $related_task_id = trim($related_task_id);
    if (!is_numeric($related_task_id) || $related_task_id < 1) {
        ShowNotification("You must supply a related task ID.", true);
        return;
    }

    $adding               = ($action == 'add');
    $pre_task_id          = $pre_task->task_id;
    $related_task_exists  = mysqli_num_rows(mysqli_query(DPDatabase::get_connection(), "SELECT task_id FROM tasks WHERE task_id = $related_task_id")) == 1;
    $related_tasks        = decode_array($pre_task->related_tasks);
    $task_already_present = in_array($related_task_id, $related_tasks);

    if (!$related_task_exists || $related_task_id == $pre_task_id || $task_already_present == $adding) {
        ShowNotification("You must supply a valid related task id number.");
        return;
    }

    if ($adding) {
        array_push($related_tasks, $related_task_id);
    } else {
        unset($related_tasks[array_search($related_task_id, $related_tasks)]);
    }

    $related_tasks = base64_encode(serialize($related_tasks));

    wrapped_mysql_query("
        UPDATE tasks
        SET related_tasks = '$related_tasks'
        WHERE task_id = $pre_task_id
    ");

    if ($adding) {
        NotificationMail($pre_task_id, "This task had a related task added to it by $pguser on $date_str at $time_of_day_str.\n");
    } else {
        NotificationMail($pre_task_id, "This task had a related task removed from it by $pguser on $date_str at $time_of_day_str.\n");
    }
    TaskDetails($pre_task_id);
}

// Add or remove a related topic (forum thread) to the curent task.
function process_related_topic($pre_task, $action, $related_topic_id)
{
    global $pguser, $date_str, $time_of_day_str;
    assert($action == 'add' || $action == 'remove');

    // Validate related_topic_id. It must be an integer >= 1
    $related_topic_id = trim($related_topic_id);
    if (!is_numeric($related_topic_id) || $related_topic_id < 1) {
        ShowNotification("You must supply a related topic ID.", true);
        return;
    }

    $adding                = ($action == 'add');
    $pre_task_id           = $pre_task->task_id;
    $related_topics        = decode_array($pre_task->related_postings);
    $topic_already_present = in_array($related_topic_id, $related_topics);

    if (!does_topic_exist($related_topic_id) || $topic_already_present == $adding) {
        ShowNotification("You must supply a valid related topic id number.", true);
        return;
    }

    if ($adding) {
        array_push($related_topics, $related_topic_id);
    } else {
        unset($related_topics[array_search($related_topic_id, $related_topics)]);
    }

    $related_topics = base64_encode(serialize($related_topics));

    wrapped_mysql_query("
        UPDATE tasks
        SET related_postings = '$related_topics'
        WHERE task_id = $pre_task_id
    ");

    if ($adding) {
        NotificationMail($pre_task_id, "This task had a related posting added to it by $pguser on $date_str at $time_of_day_str.\n");
    } else {
        NotificationMail($pre_task_id, "his task had a related posting removed from it by $pguser on $date_str at $time_of_day_str.\n");
    }
    TaskDetails($pre_task_id);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function dropdown_select($field_name, $current_value, $array)
{
    echo "<select size='1' name='$field_name' ID='$field_name' class='taskselect'>\n";
    foreach($array as $key => $val)
    {
        echo "<option value='$key'";
        if ($current_value == $key) {
            echo " SELECTED";
        }
        echo ">$val</option>\n";
    }
    echo "</select>\n";
}

function TaskHeader($header, $show_new_alert = false)
{
    global $tasks_url, $pguser;
    $js_data = <<<EOS
function showSpan(id) { document.getElementById(id).style.display=""; }
function hideSpan(id) { document.getElementById(id).style.display="none"; }
EOS;

    $css_data = <<<EOS
table.tasks,
table.taskslist,
table.taskplain    { width: 100%; border-collapse: collapse; border: 1px solid #CCCCCC; background-color: #E6EEF6; font-family: Verdana; color: #000000; font-size: small; }

table.tasks td,
table.tasks th,
table.taskslist td,
table.taskplain td { vertical-align: top; text-align: left; }

table.tasks form   { margin: 0; }
table.tasks td     { font-size: small; padding: 2px!important; }
table.tasks th     { font-weight: bold; padding: 5px; }

table.taskslist td { padding: 5px!important; white-space: nowrap; background-color: #FFFFFF; }
table.taskslist th { font-weight: bold; text-align:left; padding: 5px; vertical-align: top; }

table.taskplain    { border: none; background-color: inherit; color: inherit; }
table.taskplain td { font-size: small; padding: 2px; }
td.taskproperty    { width: 40%; font-weight: bold; }
td.taskvalue       { width: 60%; border-bottom: #CCCCCC 1px solid; }

select.taskselect  { font-size: small; color:#03008F; background-color:#EEF7FF; }
input[type="number"],
input[type="text"] { font-size: small; border:1px solid #000000; margin:2px; padding:0px; background-color:#EEF7FF; }
input[type="number"] { width: 4em; }
input[type="button"],
input[type="submit"] { font-size: small; color:#FFFFFF; font-weight:bold; border:1px ridge #000000; margin:2px; padding: 0px 5px; background-color:#838AB5; }
input[type="button"]:disabled,
input[type="submit"]:disabled { background-color: #AAAAAA; }

legend.task        { font-weight:bold; }
fieldset.task      { width:35em; border:#2266AA solid 1px; }
small.task         { font-family:Verdana; font-size: small; }
center.taskwarn    { color:#FF0000; font-weight:bold; font-family:Verdana; padding:2em; }
center.taskinfo    { color:#00CC00; font-weight:bold; font-family:Verdana; padding:2em; }
.wrap              { white-space: normal!important; }
EOS;

    output_header($header, NO_STATSBAR,
        array('js_data' => $js_data, 'css_data' => $css_data));

    $userSettings =& Settings::get_Settings($pguser);
    $notification_settings = $userSettings->get_values('taskctr_notice');
    $notified_for_new = in_array('notify_new', $notification_settings);

    echo "<table class='taskplain'>\n";
    echo "<tr><td width='50%'>";
    echo "<a href='$tasks_url'>Task Center Home</a> | <a href='$tasks_url?action=show_creation_form'>New Task</a>";
    echo "<form method='get' style='display: inline;'>";
    if($show_new_alert)
    {
        if($notified_for_new)
        {
            echo "<input type='hidden' name='action' value='unnotify_new'>";
            echo " | <input type='submit' value='Stop New Task Alerts'>";
        }
        else
        {
            echo "<input type='hidden' name='action' value='notify_new'>";
            echo " | <input type='submit' value='Receive New Task Alerts'>";
        }
    }
    echo "</form>";
    echo "</td>\n";
    echo "<td width='50%' style='text-align:right;'>";
    echo "<form action='$tasks_url' method='get'><input type='hidden' name='action' value='show'>";
    echo "<b><small class='task'>Show Task #</small></b>";
    echo "&nbsp;\n";
    echo "<input type='number' name='task_id' min='1' required>&nbsp;\n";
    echo "<input type='submit' value='Go!'>\n";
    echo "</form>";
    echo "</td></tr></table><br>\n";
    echo "<form action='$tasks_url' method='post'>";
    echo "<table class='tasks'>\n";
    echo "<tr><td><b><small class='task'>Search:</small></b></td>\n";
    echo "<td>";

    SearchParams_echo_controls();

    echo "<input type='submit' value='Search'></td>\n";
    echo "</tr>\n";
    echo "</table></form><br>\n";
}

function decode_array($str)
{
    // Decode and deserialize a base64 encoded serialised array. Usually a
    // field from the tasks table in the DB. This should return an array,
    // but if $s is empty, unserialize("") === bool(false). In that case
    // we explicitly return an empty array.
    $a = unserialize(base64_decode($str));
    if (is_array($a)) return $a;
    return array();
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

    $columns = array(
        'task_id'          => " style='text-align: center;'",
        'task_type'        => "",
        'task_severity'    => "",
        'task_summary'     => " class='wrap'",
        'task_priority'    => "",
        'date_edited'      => " style='text-align: center;'",
        'task_status'      => "",
        'votes'            => "",
        'percent_complete' => "",
    );

    $curr_sort_dir = get_enumerated_param($_GET, 'direction', 'desc', array('asc', 'desc'));
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
          CASE WHEN
             vote_os IS NULL THEN NULL
             ELSE COUNT(*) END AS votes
        FROM tasks
          LEFT OUTER JOIN tasks_votes USING (task_id)
        WHERE $sql_condition
        GROUP BY task_id
        ORDER BY $curr_sort_col $curr_sort_dir
    ";
    $sql_result = wrapped_mysql_query($sql_query);

    $t = SearchParams_get_url_query_string();

    echo "<table class='taskslist'><tr>\n";
    foreach ( $columns as $property_id => $attrs )
    {
        // Each column-header is a link; clicking on it will cause
        // the resulting listing to be sorted on that column.
        $orderby_for_link = $property_id;

        // But sorted in which direction?
        if ($property_id == $curr_sort_col) {
            // This column is the one that the current listing is sorted on.
            // A header-click will just reverse the direction of the sort.
            if ($curr_sort_dir == "asc") {
                $direction_for_link = "desc";
            } else {
                $direction_for_link = "asc";
            }
        } else {
            // This column is not the current sort-column.
            // A header-click will sort by that column in descending order.
            // (Might be better for each column to have its own default direction.)
            $direction_for_link = "desc";
        }

        $url = "$tasks_url?{$t}orderby=$orderby_for_link&direction=$direction_for_link";
        $label = property_get_label($property_id, TRUE);
        echo "<th$attrs><a href='$url'>$label</a></th>\n";
    }
    echo "</tr>\n";
    if (@mysqli_num_rows($sql_result) >= 1) {
        while ($row = mysqli_fetch_assoc($sql_result)) {
            echo "<tr>\n";
            foreach ( $columns as $property_id => $attrs )
            {
                $formatted_value = property_format_value($property_id, $row, TRUE);
                echo "<td$attrs>$formatted_value</td>\n";
            }
            echo "</tr>\n";
        }
    }
    else {
        echo "<tr><td colspan='9'><center>No tasks found!</center></td></tr>";
    }
    echo "</table><br>\n";
    // if 2 tasks or more found, display the number of reported tasks
    if (@mysqli_num_rows($sql_result) > 1) {
        echo "<small class='task'>" . @mysqli_num_rows($sql_result) . " tasks listed.</small>";
    }
}

function TaskForm($task)
{
    global $requester_u_id, $tasks_array, $severity_array, $categories_array, $tasks_status_array;
    global $os_array, $browser_array, $versions_array, $percent_complete_array;
    global $task_assignees_array;
    global $priority_array, $tasks_url;

    // Non-managers can only set the task status to New.
    if (!user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
        $tasks_status_array = array(1 => "New");
    }

    $task_summary_enc = attr_safe($task->task_summary);
    $task_details_enc = html_safe($task->task_details);

    echo "<form action='$tasks_url' method='post'>";
    if (empty($task->task_id)) {
        echo "<input type='hidden' name='action' value='create'>\n";
    } else {
        echo "<input type='hidden' name='action' value='edit'>\n";
        echo "<input type='hidden' name='task_id' value='$task->task_id'>";
    }
    echo "<table class='tasks'>\n";
    echo "<tr>";
    echo "<td colspan='2'>";
    echo "<b>" . property_get_label('task_summary', FALSE) . "&nbsp;</b>";
    echo "&nbsp;&nbsp;";
    echo "<input type='text' name='task_summary' value=\"$task_summary_enc\" size='60' maxlength='80' required>";
    echo "</td>";
    echo "</tr>\n";
    echo "<tr><td width='50%'><table class='taskplain'>\n";
    property_echo_select_tr('task_type', $task->task_type, $tasks_array);
    property_echo_select_tr('task_category', $task->task_category, $categories_array);
    property_echo_select_tr('task_status', $task->task_status, $tasks_status_array);
    property_echo_select_tr('task_assignee', $task->task_assignee, $task_assignees_array);
    property_echo_select_tr('task_os', $task->task_os, $os_array);
    echo "</table></td><td width='50%'><table class='taskplain'>\n";
    property_echo_select_tr('task_browser', $task->task_browser, $browser_array);
    property_echo_select_tr('task_severity', $task->task_severity, $severity_array);
    property_echo_select_tr('task_priority', $task->task_priority, $priority_array);
    property_echo_select_tr('task_version', $task->task_version, $versions_array);
    if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && !empty($task->task_id)) {
        property_echo_select_tr('percent_complete', $task->percent_complete, $percent_complete_array);
    }
    elseif ($task->opened_by == $requester_u_id && !user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
        echo "<input type='hidden' name='percent_complete' value='$task->percent_complete'>";
    }
    echo "</table></td></tr><tr><td>\n";
    echo "<table class='taskplain'><tr><td width='5%'><b>Details</b>&nbsp;&nbsp;</td>\n";
    echo "<td width='95%'>";
    echo "<textarea name='task_details' cols='60' rows='5' required>$task_details_enc</textarea>";
    echo "</td>";
    echo "</tr>";
    echo "</table>\n";
    echo "</td></tr><tr><td colspan='2'><center>\n";
    echo "<input type='submit' value='";
    if (empty($task->task_id)) {
        echo "Add Task";
    }
    else {
        echo "Submit Edit";
    }
    echo "'>\n";
    echo "</center></td></tr></table></form><br>\n";
}

function property_echo_select_tr($property_id, $current_value, $options)
// Echo a <tr> element containing a label and a <select> for the given property.
{
    $label = property_get_label($property_id, FALSE);
    echo "<tr><td width='40%'><b>$label</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select($property_id, $current_value, $options);
    echo "</td></tr>\n";
}

function TaskDetails($tid)
{
    global $requester_u_id, $tasks_url;
    global $os_array, $browser_array, $tasks_close_array;
    global $pguser;
    if (!is_numeric($tid)) {
        ShowNotification("Error: task identifier '$tid' is not numeric.");
        return;
    }
    $res = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM tasks WHERE task_id = $tid LIMIT 1");
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_assoc($res)) {
            $userSettings =& Settings::get_Settings($pguser);
            $notification_settings = $userSettings->get_values('taskctr_notice');
            if(in_array($tid, $notification_settings) ||
                in_array('all', $notification_settings))
            {
                $already_notified = 1;
            }
            else
            {
                $already_notified = 0;
            }
            $opened_by_link = property_format_value('opened_by', $row, FALSE);
            $edited_by_link = property_format_value('edited_by', $row, FALSE);

            // Task id, summary, and possible Edit/Re-Open Task buttons.
            echo "<table class='tasks'>\n";
            echo "<tr>";
            echo "<th width='90%' valign='middle'>";
            echo "Task #$tid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . property_format_value('task_summary', $row, FALSE);
            echo "</th>";
            echo "<td width='10%' valign='middle' style='text-align:right;'>";
            echo "<form action='$tasks_url' method='post'>\n";
            if ((user_is_a_sitemanager() || user_is_taskcenter_mgr() || $row['opened_by'] == $requester_u_id) && empty($row['closed_reason'])) {
                echo "<input type='hidden' name='action' value='show_editing_form'>\n";
                echo "<input type='hidden' name='task_id' value='$tid'>\n";
                echo "<input type='submit' value='Edit Task'>\n";
            }
            elseif (!empty($row['closed_reason'])) {
                echo "<input type='hidden' name='action' value='reopen'>\n";
                echo "<input type='hidden' name='task_id' value='$tid'>\n";
                echo "<input type='submit' value='Re-Open Task'>\n";
            }
            else {
                echo "&nbsp;\n";
            }
            echo "</form>";
            echo "</td>";
            echo "</tr>";
            echo "</table>\n";

            echo "<table class='tasks'>\n";

            // Row 1: Opened & Last edited. Link to toggle task notifications.
            echo "<tr>";
            echo "<td width='50%'>";
            echo "<small class='task'>";
            echo "Opened by $opened_by_link - " . property_format_value('date_opened', $row, FALSE);
            echo "<br>";
            echo "Last edited by $edited_by_link - " . property_format_value('date_edited', $row, FALSE);
            echo "</small>";
            echo "</td>";
            echo "<td width='50%' style='text-align:right;'>\n";
            echo "<form method='get' style='display: inline;'>\n";
            echo "<input type='hidden' name='task_id' value='$tid'>\n";
            if (empty($already_notified))
            {
                echo "<input type='hidden' name='action' value='notify_me'>\n";
                echo "<input type='submit' value='Signup for task notifications'>\n";
            }
            else
            {
                echo "<input type='hidden' name='action' value='unnotify_me'>\n";
                echo "<input type='submit' value='Remove me from task notifications'>\n";
            }
            echo "</form>";
            echo "</td>\n";
            echo "</tr>\n";

            // Row 2: most of the task's simple properties
            echo "<tr>";
            echo "<td width='40%'>";
            echo "<table class='taskplain'>\n";
            property_echo_value_tr('task_type',        $row);
            property_echo_value_tr('task_category',    $row);
            property_echo_value_tr('task_status',      $row);
            property_echo_value_tr('task_assignee',    $row);
            property_echo_value_tr('task_os',          $row);
            echo "</table>";
            echo "</td>";
            echo "<td width='50%'>";
            echo "<table class='taskplain'>\n";
            property_echo_value_tr('task_browser',     $row);
            property_echo_value_tr('task_severity',    $row);
            property_echo_value_tr('task_priority',    $row);
            property_echo_value_tr('task_version',     $row);
            property_echo_value_tr('percent_complete', $row);
            echo "</table>";
            echo "</td>";
            echo "</tr>\n";

            // Row 3: summary of votes/metoos
            $voteInfo = mysqli_query(DPDatabase::get_connection(), "SELECT id FROM tasks_votes WHERE task_id = $tid");
            $osInfo = mysqli_query(DPDatabase::get_connection(), "SELECT DISTINCT vote_os FROM tasks_votes WHERE task_id = $tid");
            $browserInfo = mysqli_query(DPDatabase::get_connection(), "SELECT DISTINCT vote_browser FROM tasks_votes WHERE task_id = $tid");
            if (mysqli_num_rows($voteInfo) > 0) {
                $reportedOS = "";
                $reportedBrowser = "";
                echo "<tr>";
                echo "<td colspan='2'>";
                echo "<table class='taskplain'>";
                echo "<tr>";
                echo "<td width='25%'>";
                echo "<b>Votes&nbsp;&nbsp;</b>";
                echo "</td>\n";
                echo "<td width='75%'>";
                echo mysqli_num_rows($voteInfo);
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td width='25%'>";
                echo "<b>Reported Operating Systems&nbsp;&nbsp;</b>";
                echo "</td>\n";
                echo "<td width='75%'>";
                while ($rowOS = mysqli_fetch_assoc($osInfo)) {
                    $reportedOS.= $os_array[$rowOS['vote_os']] . ", ";
                }
                echo substr($reportedOS, 0, -2);
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td width='25%'>";
                echo "<b>Reported Browsers&nbsp;&nbsp;</b>";
                echo "</td>\n";
                echo "<td width='75%'>";
                while ($rowBrowser = mysqli_fetch_assoc($browserInfo)) {
                    $reportedBrowser.= $browser_array[$rowBrowser['vote_browser']] . ", ";
                }
                echo substr($reportedBrowser, 0, -2);
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</td>";
                echo "</tr>";
            }

            // Row 4: details
            echo "<tr>";
            echo "<td>";
            echo "<br>";
            echo "<table class='taskplain'>";
            echo "<tr>";
            echo "<td width='5%'>";
            echo "<b>Details&nbsp;&nbsp;</b>";
            echo "</td>\n";
            echo "<td width='95%' class='taskvalue'>";
            echo property_format_value('task_details', $row, FALSE);
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            echo "</td>";
            echo "</tr>\n";

            // Row 5: Close Task. Me Too!
            echo "<tr>\n";
            if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && empty($row['closed_reason'])) {
                echo "
                    <td>
                      <br>
                      <form action='$tasks_url' method='post'>
                        <input type='hidden' name='action' value='close'>
                        <input type='hidden' name='task_id' value='$tid'>
                        <table class='taskplain'>
                          <tr>
                            <td width='20%' valign='bottom'>
                              <b>Close Task&nbsp;&nbsp;</b>
                            </td>
                            <td valign='bottom' width='80%'>
                ";
                dropdown_select('closed_reason', "", $tasks_close_array);
                echo "
                              <input type='submit' value='Close Task'>
                            </td>
                          </tr>
                        </table>
                      </form>
                    </td>
                ";
            }
            elseif (!empty($row['closed_reason'])) {
                $closed_by   = property_format_value('closed_by',     $row, FALSE);
                $date_closed = property_format_value('date_closed',   $row, FALSE);
                $reason      = property_format_value('closed_reason', $row, FALSE);
                echo "
                    <td>
                      <br>
                      <small class='task'>
                        Closed by: $closed_by
                        <br>
                        Date Closed: $date_closed
                        <br>
                        Reason: $reason
                      </small>
                    </td>
                ";
            }
            echo "<td>";
            echo "<br>";
            $meTooCheckResult = mysqli_query(DPDatabase::get_connection(), "
                SELECT id
                FROM tasks_votes
                WHERE task_id = $tid and u_id = $requester_u_id
            ");
            $meTooAllowed = (mysqli_num_rows($meTooCheckResult) == 0);
            mysqli_free_result($meTooCheckResult);
            if ($meTooAllowed) {
                echo "<input type='button' value='Me Too!' onClick=\"showSpan('MeTooMain');\">";
            }
            else {
                echo "<input type='button' value='Already submitted \"Me Too!\"' disabled>";
            }
            echo "</td>";
            echo "</tr>";

            echo "</table>";
            echo "<br>\n";

            if ($meTooAllowed) {
                MeToo($tid, $row['task_os'], $row['task_browser']);
            }
            TaskComments($tid);
            RelatedTasks($tid);
            echo "<br>\n";
            RelatedPostings($tid);
        }
    }
    else {
        ShowNotification("Task #$tid was not found!");
    }
}

function property_echo_value_tr( $property_id, $row )
{
    $label = property_get_label($property_id, FALSE);
    $formatted_value = property_format_value($property_id, $row, FALSE);

    echo "<tr>";
    echo "<td class='taskproperty'>{$label}&nbsp;&nbsp;</td>";
    echo "<td class='taskvalue'>$formatted_value</td>";
    echo "</tr>";
    echo "\n";
}

// Guess what OS the user is running based on the User Agent.
// Not very sophisticated, but catches the common cases.
// Returns a string that can be looked up in $os_array.
// Note that any future detection strings added to this function
// MUST match an entry in $os_array.
function guess_OS_from_UA()
{
    $ua = @$_SERVER['HTTP_USER_AGENT'];
    if (str_contains($ua, "Linux")) {
        $os = "Linux";
    } elseif (str_contains($ua, "Windows NT 6.1")) {
        $os = "Windows 7";
    } elseif (str_contains($ua, "Windows NT 6.0")) {
        $os = "Windows Vista";
    } elseif (str_contains($ua, "Windows NT 5.1")) {
        $os = "Windows XP";
    } elseif (str_contains($ua, "Windows NT 5.0")) {
        $os = "Windows 2000";
    } elseif (str_contains($ua, "Mac OS X")) {
        $os = "Mac OS X";
    } else {
        $os = "Other";
    }
    return $os;
}

// Guess what browser the user is running based on the User Agent.
// Not very sophisticated, but catches the common cases.
// Returns a string that can be looked up in $browser_array.
// Note that any future detection strings added to this function
// MUST match an entry in $browser_array.
function guess_browser_from_UA()
{
    $ua = @$_SERVER['HTTP_USER_AGENT'];
    if (str_contains($ua, "MSIE 8.0")) {
        $browser = "Internet Explorer 8.x";
    } elseif (str_contains($ua, "MSIE 7.0")) {
        $browser = "Internet Explorer 7.x";
    } elseif (str_contains($ua, "MSIE 6.0")) {
        $browser = "Internet Explorer 6.x";
    } elseif (str_contains($ua, "Firefox/3.")) {
        $browser = "Mozilla Firefox 3.x";
    } elseif (str_contains($ua, "Firefox/2.")) {
        $browser = "Mozilla Firefox 2.x";
    } elseif (str_contains($ua, "Opera/10.")) {
        $browser = "Opera 10.x";
    } elseif (str_contains($ua, "Opera/9.")) {
        $browser = "Opera 9.x";
    } elseif (str_contains($ua, "Safari/") and str_contains($ua, "Version/4.")) {
        $browser = "Safari 4.x";
    } elseif (str_contains($ua, "Safari/") and str_contains($ua, "Version/3.")) {
        $browser = "Safari 3.x";
    } elseif (str_contains($ua, "Safari/") and str_contains($ua, "Version/2.")) {
        $browser = "Safari 2.x";
    } else {
        $browser = "Other";
    }
    return $browser;
}

function MeToo($tid, $os, $browser)
{
    global $tasks_url, $browser_array, $os_array;
    echo "<div id='MeTooMain' style='display: none;'>";
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='action' value='add_metoo'>";
    echo "<input type='hidden' name='task_id' value='$tid'>";
    echo "<input type='hidden' name='task_os' value='$os'>";
    echo "<input type='hidden' name='task_browser' value='$browser'>";
    echo "<table class='tasks'><tr><td>\n";
    echo "<fieldset class='task'>";
    echo "<legend class='task'>Are you using the same operating system?</legend>";
    echo "&nbsp;";
    echo "<input onClick=\"hideSpan('OS');\" type='radio' name='sameOS' value='1' CHECKED>yes";
    echo "<input onClick=\"showSpan('OS');\" type='radio' name='sameOS' value='0'>no";
    echo "<span id='OS' style='display: none;'>";
    echo "<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<b>Operating System</b>";
    echo "&nbsp;";
    dropdown_select('metoo_os', array_search(guess_OS_from_UA(), $os_array), $os_array);
    echo "</span></fieldset>\n";
    echo "<br>";
    echo "<fieldset class='task'>";
    echo "<legend class='task'>Are you using the same browser?</legend>";
    echo "&nbsp;";
    echo "<input onClick=\"hideSpan('Browser');\" type='radio' name='sameBrowser' value='1' CHECKED>yes";
    echo "<input onClick=\"showSpan('Browser');\" type='radio' name='sameBrowser' value='0'>no";
    echo "<span id='Browser' style='display: none;'>";
    echo "<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<b>Browser</b>";
    echo "&nbsp;";
    dropdown_select('metoo_browser', array_search(guess_browser_from_UA(), $browser_array), $browser_array);
    echo "</span></fieldset>\n";
    echo "<center>";
    echo "<input type='submit' value='Send Report'>";
    echo "&nbsp;";
    echo "<input type='reset' value='Reset' onClick=\"hideSpan('MeTooMain');\">";
    echo "</center>";
    echo "</td></tr></table></form></div>";
}

function ShowNotification($warn, $goback = false, $type = "warn")
{
    if ($type == "info") {
        echo "<center class='taskinfo'>";
    }
    else {
        echo "<center class='taskwarn'>";
    }
    if ($goback) $warn.= "  Please go <a href='javascript:history.back()'>back</a> and correct this.";
    echo "$warn</center>\n";
}

function TaskComments($tid)
{
    global $tasks_url;
    $result = mysqli_query(DPDatabase::get_connection(), "
        SELECT *
        FROM tasks_comments
        WHERE task_id = $tid
        ORDER BY comment_date ASC
    ");
    if (mysqli_num_rows($result) >= 1) {
        echo "<table class='tasks'><tr><td width='100%'>\n";
        while ($row = mysqli_fetch_assoc($result)) {
            $comment_username_link = private_message_link_for_uid($row['u_id']);
            echo "<b>Comment by $comment_username_link - " . date("l, d M Y, g:ia", $row['comment_date']) . "</b><br>";
            echo "<br>" . nl2br(html_safe($row['comment'])) . "<br><br><hr width='80%' align='center'>";
        }
        echo "</td></tr></table>";
    }
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='action' value='add_comment'>";
    echo "<input type='hidden' name='task_id' value='$tid'>";
    echo "<table class='tasks'><tr><td>\n";
    echo "<tr><td width='10%'><b>Add comment</b></td>";
    echo "<td width='90%'><textarea name='task_comment' cols='60' rows='5'></textarea></td></tr>";
    echo "<tr>";
    echo "<td width='100%' align='center' colspan='2'>";
    echo "<input type='submit' value='Add Comment'>\n";
    echo "</td></tr></table></form>";
}

function NotificationMail($tid, $message, $new_task = false)
{
    global $site_abbreviation, $code_url, $tasks_url, $pguser, $date_str, $time_of_day_str;

    $result = mysqli_query(DPDatabase::get_connection(), "SELECT task_summary, task_details FROM tasks WHERE task_id = $tid LIMIT 1");
    if(!$result)
        return;
    $row = mysqli_fetch_assoc($result);
    $task_summary = $row['task_summary'];

    $subject = "$site_abbreviation Task #$tid: $task_summary";
    $message = $message
        . "\nYou can see task #$tid by visiting $tasks_url?task_id=$tid\n\n"
        . "--\n"
        . "Distributed Proofreaders\n$code_url\n\n"
        . "This is an automated message, please do not respond directly to this e-mail.";

    $notify_setting_all = Settings::get_users_with_setting('taskctr_notice', 'all');
    if($new_task)
    {
        $notify_setting_this = Settings::get_users_with_setting('taskctr_notice', 'notify_new');
        $message =
            "You have requested notification of new tasks.\n"
            . "Task #$tid: '$task_summary' was created by $pguser on $date_str at $time_of_day_str.\n\n"
            . $row['task_details'] . "\n"
            . $message;
    }
    else
    {
        $notify_setting_this = Settings::get_users_with_setting('taskctr_notice', $tid);
        $message =
            "You have requested notification of updates to task #$tid: $task_summary\n"
            . $message;
    }
    $users_to_notify = array_unique(array_merge($notify_setting_all, $notify_setting_this));
    foreach($users_to_notify as $username) {
        if ($username != $pguser) {
            $user = new User($username);
            maybe_mail($user->email, $subject, $message);
        }
    }
}

function RelatedTasks($tid)
{
    global $tasks_url, $tasks_status_array;
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT related_tasks FROM tasks WHERE task_id = $tid");
    $row = mysqli_fetch_assoc($result);
    $related_tasks = $row["related_tasks"];
    echo "<table class='tasks'>\n";
    echo "<tr><td width='100%'><b>Related Tasks&nbsp;&nbsp;</b>";
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='action' value='add_related_task'>";
    echo "<input type='hidden' name='task_id' value='$tid'>";
    echo "<input type='number' name='related_task' min='1' required>&nbsp;&nbsp;";
    echo "<input type='submit' value='Add'>\n";
    echo " (Add the number of an existing, related task. This is optional.)";
    echo "</form>";
    $related_tasks = decode_array($related_tasks);
    asort($related_tasks);
    foreach($related_tasks as $val)
    {
        $result = mysqli_query(DPDatabase::get_connection(), "
            SELECT task_status, task_summary FROM tasks WHERE task_id = $val
        ") or die(mysqli_error(DPDatabase::get_connection()));
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            // The task must have been deleted from the table manually.
            $related_task_summary = "[not found]";
        }
        else {
            $related_task_summary = html_safe($row["task_summary"]);
            $related_task_status  = $tasks_status_array[$row["task_status"]];
        }

        if ($row)
            echo "<form action='$tasks_url' method='post'>";
        echo "<a href='$tasks_url?action=show&task_id=$val'>Task #$val</a> ($related_task_status) - $related_task_summary";
        if ($row) {
            echo " <input type='hidden' name='action' value='remove_related_task'>";
            echo "<input type='hidden' name='task_id' value='$tid'>";
            echo "<input type='hidden' name='related_task' value='$val'>";
            echo "<input type='submit' value='Remove'>";
            echo "</form>";
        }
    }
    echo "</td></tr></table>";
}

function RelatedPostings($tid)
{
    global $tasks_url;
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT related_postings FROM tasks WHERE task_id = $tid");
    $row = mysqli_fetch_assoc($result);
    $related_postings = $row["related_postings"];
    echo "<table class='tasks'>\n";
    echo "<tr><td width='100%'><b>Related Topic ID&nbsp;&nbsp;</b>";
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='action' value='add_related_topic'>";
    echo "<input type='hidden' name='task_id' value='$tid'>";
    echo "<input type='number' name='related_posting' min='1' required>&nbsp;&nbsp;";
    echo "<input type='submit' value='Add'>\n";
    echo " (Optional)";
    echo "</form>";
    $related_postings = decode_array($related_postings);
    asort($related_postings);
    foreach($related_postings as $val)
    {
        $row = get_topic_details($val);
        $forum_url = get_url_to_view_forum($row["forum_id"]);
        $topic_url = get_url_to_view_topic($row["topic_id"]);
        echo "<form action='$tasks_url' method='post'>";
        echo "<input type='hidden' name='action' value='remove_related_topic'>";
        echo "<input type='hidden' name='task_id' value='$tid'>";
        echo "<input type='hidden' name='related_posting' value='" .$row["topic_id"] . "'>";
        echo "<a href='$forum_url'>" . $row['forum_name'] . "</a>";
        echo "&nbsp;&raquo;&nbsp;";
        echo "<a href='$topic_url'>" . $row['title'] . "</a>";
        echo " (Posted by: " . $row['creator_username'] . " - " . $row['num_replies'] . " replies)\n";
        echo "<input type='submit' value='Remove'>\n";
        echo "</form>";
    }
    echo "</td></tr></table>";
}

function property_get_label( $property_id, $for_list_of_tasks )
{
    switch ( $property_id )
    {
        case 'date_edited'   : return 'Date Edited';
        case 'task_assignee' : return 'Assigned To';
        case 'task_browser'  : return 'Browser';
        case 'task_category' : return 'Category';
        case 'task_id'       : return 'ID';
        case 'task_os'       : return 'Operating System';
        case 'task_priority' : return 'Priority';
        case 'task_severity' : return 'Severity';
        case 'task_status'   : return 'Status';
        case 'task_summary'  : return 'Summary';
        case 'task_type'     : return 'Task Type';
        case 'task_version'  : return 'Reported Version';
        case 'votes'         : return 'Votes';

        case 'percent_complete':
            return ( $for_list_of_tasks ? "Progress" : "Percent Complete" );
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
    global $versions_array;

    $raw_value = $task_a[$property_id];
    switch ($property_id)
    {
        // The raw value is used directly:
        case 'votes': return $raw_value;
        case 'task_id': $fv = $raw_value; break; // maybe wrap in <a>

        // The raw value is an index into an array.
        case 'closed_reason' : return $tasks_close_array[$raw_value];
        case 'task_browser'  : return $browser_array[$raw_value];
        case 'task_category' : return $categories_array[$raw_value];
        case 'task_os'       : return $os_array[$raw_value];
        case 'task_priority' : return $priority_array[$raw_value];
        case 'task_severity' : return $severity_array[$raw_value];
        case 'task_status'   : return $tasks_status_array[$raw_value];
        case 'task_type'     : return $tasks_array[$raw_value];
        case 'task_version'  : return $versions_array[$raw_value];

        // The raw value is an integer denoting seconds-since-epoch.
        case 'date_edited' : return date("d-M-Y", $raw_value);
        case 'date_opened' : return date("d-M-Y", $raw_value);
        case 'date_closed' : return date("d-M-Y", $raw_value);

        // The raw value is a user's u_id:
        case 'opened_by' : return private_message_link_for_uid($raw_value);
        case 'edited_by' : return private_message_link_for_uid($raw_value);
        case 'closed_by' : return get_username_for_uid($raw_value);
        case 'task_assignee':
            return (
                empty($raw_value)
                ? "Unassigned"
                : private_message_link_for_uid($raw_value)
            );

        // The raw value is some text typed in by a user:
        case 'task_summary':
            $fv = html_safe($raw_value); break; // maybe wrap in <a>

        case 'task_details':
            return nl2br(html_safe($raw_value));

        // The raw value is an integer denoting state of progress:
        case 'percent_complete':
            if ($for_list_of_tasks) {
                $s = 'small'; $w = '50'; $h = '8'; $b = "";
            } else {
                $s = 'large'; $w = '150'; $h = '10'; $b = " border='0'";
            }
            $url = "$code_url/graphics/task_percentages/{$s}_{$raw_value}.png";
            $alt = "{$raw_value}% Complete";
            return "<img src='$url' width='$w' height='$h'$b alt='$alt'>";

        default:
            assert(FALSE);
    }

    // Cases that don't return directly,
    // but instead set $fv and then break,
    // fall through to here.
    // If appropriate, wrap $fv in an <a> element
    // that links to the task's details page.

    assert( isset($fv) );
    if ($for_list_of_tasks)
    {
        $url = "$tasks_url?action=show&task_id=" . $task_a['task_id'];
        $fv = "<a href='$url'>$fv</a>";
    }
    return $fv;
}

function private_message_link_for_uid($u_id)
// Return a 'private message link' for the user specified by $u_id.
{
    $username = get_username_for_uid($u_id);
    $link = private_message_link($username, NULL);
    return $link;
}

function get_username_for_uid($u_id)
{
    $user = User::load_from_uid($u_id);
    return $user->username;
}

function wrapped_mysql_query($sql_query)
{
    global $testing;
    if ($testing) echo_html_comment($sql_query);
    $res = mysqli_query(DPDatabase::get_connection(), $sql_query);
    if ($res === FALSE) die(mysqli_error(DPDatabase::get_connection()));
    return $res;
}

function set_window_title($title)
{
    $title_esc = addslashes($title);
    echo "<script>top.document.title = '$title_esc';</script>\n";
}

// Given a task row from the DB, produce an unescaped string representing
// the task and suitable for display in a page title or similar.
function title_string_for_task($pre_task)
{
    return sprintf("Task #%d: %s", $pre_task->task_id, $pre_task->task_summary);
}

// vim: sw=4 ts=4 expandtab
