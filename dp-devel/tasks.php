<?php
$relPath='pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'links.inc'); // private_message_link()

$tasks_url = $code_url . "/" . basename(__FILE__);

$requester_u_id = $userP['u_id'];

$now_sse = time();
// The current time, expressed as Seconds Since the (Unix) Epoch.

$date_str = date("l, F jS, Y", $now_sse);
$time_of_day_str = date("g:i a", $now_sse);

$valid_f = get_enumerated_param($_GET, 'f', null, array('newtask', 'detail', 'notifyme', 'unnotifyme'), true);

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
    11 => "Project Comments",
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
$result = mysql_query("
        SELECT username, u_id
        FROM users
        WHERE sitemanager = 'yes'
    ");
while ($row = mysql_fetch_assoc($result)) {
    $task_assignees_array[$row['u_id']] = $row['username'];
}
$result = mysql_query("
        SELECT username
        FROM usersettings
        WHERE setting = 'task_center_mgr' AND value = 'yes'
    ");
while ($row = mysql_fetch_assoc($result)) {
    $u_idQuery = mysql_query("
            SELECT u_id
            FROM users
            WHERE username = '{$row['username']}'
        ");
    $u_id = mysql_result($u_idQuery, 0, "u_id");
    $task_assignees_array[$u_id] = $row['username'];
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
        if (get_magic_quotes_gpc()) $_REQUEST['search_text'] = stripslashes($_REQUEST['search_text']);
        $search_text = htmlspecialchars($_REQUEST['search_text'], ENT_QUOTES);
    }
    else $search_text = "";

    echo "<input type='text' value='$search_text' name='search_text' size='50' class='taskinp1'>\n";

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

    // Note that, although TaskHeader has already run stripslashes()
    // on $_REQUEST['search_text'], $_REQUEST is a distinct variable
    // from $_GET and $_POST (and thus $request_params), so
    // $request_params['search_text'] is still "slashed".
    if ($testing) echo_html_comment("\$request_params['search_text'] = {$request_params['search_text']}");

    // we're converting $searchtext using addslashes(htmlspecialchars(...))
    // because that's how the text summary and text details happen to be
    // stored in the database.

    // TODO: The 'right' way would be to change how the data is stored in
    // the database using mysql_real_escape_string(), have an upgrade
    // script in c/SETUP/upgrade/08 that would fix any existing data
    // before the updated code was deployed, and then use
    // mysql_real_escape_string() when doing the query.

    $search_text_summary = addslashes(htmlspecialchars($request_params['search_text']));
    $search_text_details = addslashes(htmlspecialchars($request_params['search_text'], ENT_QUOTES));

    $condition = "
            (
                POSITION('$search_text_summary' IN task_summary)
                OR
                POSITION('$search_text_details' IN task_details)
            )";

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
        $t = "search_text=" . urlencode($_REQUEST['search_text']);
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

// This is the point at which the script starts to produce output.

$no_stats = 1;
theme('Task Center', 'header');
?>
<script language='javascript'><!--
function showSpan(id) {
    document.getElementById(id).style.display="";
}
function hideSpan(id) {
    document.getElementById(id).style.display="none";
}
// --></script>
<style type="text/css">
table.tasks        { width:98%; border-collapse:collapse; border:1px solid #CCCCCC; background-color:#E6EEF6; font-family:Verdana; color:#000000; font-size:11px; }
table.tasks td     { font-size:11px; padding:2px!important; vertical-align:top; text-align:left; }
table.tasks th     { font-weight:bold; text-align:left; padding:5px; vertical-align:top; }
table.taskslist    { width:98%; border-collapse:collapse; border:1px solid #CCCCCC; background-color:#E6EEF6; font-family:Verdana; color:#000000; font-size:11px; }
table.taskslist td { padding:5px!important; }
table.taskslist th { font-weight:bold; text-align:left; padding:5px; vertical-align:top; padding:5px!important; }
table.taskplain    { width:98%; border:none; border-collapse:collapse; }
table.taskplain td { font-size: 11px; padding:2px; vertical-align:top; text-align:left; }
td.taskproperty    { width:40%; font-weight: bold; }
td.taskvalue       { width:60%; border-bottom:#CCCCCC 1px solid; }
select.taskselect  { font-size:12px; color:#03008F; background-color:#EEF7FF; }
input.taskinp1     { font-size:12px; border:1px solid #000000; margin:2px; padding:0px; background-color:#EEF7FF; }
input.taskinp2     { font-size:12px; color:#FFFFFF; font-weight:bold; border:1px ridge #000000; margin:2px; padding:0px; background-color:#838AB5; }
legend.task        { font-weight:bold; }
fieldset.task      { width:35em; border:#2266AA solid 1px; }
small.task         { font-family:Verdana; font-size:10px; }
center.taskwarn    { color:#FF0000; font-weight:bold; font-size: 12pt; font-family:Verdana; padding:2em; }
center.taskinfo    { color:#00CC00; font-weight:bold; font-size: 12pt; font-family:Verdana; padding:2em; }
p                  { font-family:Verdana; font-size:11px; }
</style>

<?php

echo "<br /><div align='center'><table class='taskplain' width='98%'><tr><td>\n";
TaskHeader();

if (isset($valid_f)) {
    switch ( $valid_f )
    {
        case 'newtask':
            // Open a form to specify the properties of a new task.
            TaskForm("");
            break;

        case 'detail':
            $task_id = get_integer_param($_REQUEST, 'tid', null, 1, null);
            TaskDetails($task_id);
            break;

        case 'notifyme':
            $task_id = get_integer_param($_REQUEST, 'tid', null, 1, null);
            $result = wrapped_mysql_query("
                INSERT INTO usersettings (username, setting, value)
                VALUES ('$pguser', 'taskctr_notice', $task_id)
            ");
            TaskDetails($task_id);
            break;

        case 'unnotifyme':
            $task_id = get_integer_param($_REQUEST, 'tid', null, 1, null);
            $result = wrapped_mysql_query("
                DELETE FROM usersettings
                WHERE username = '$pguser' and setting = 'taskctr_notice' and value = $task_id
            ");
            TaskDetails($task_id);
            break;
    }
}
elseif (isset($_POST['edit_task'])) {
    $task_id = get_integer_param($_POST, 'edit_task', null, 1, null);
    $result = mysql_query("SELECT * FROM tasks WHERE task_id = $task_id");
    $opened_by = mysql_result($result, 0, "opened_by");
    $closed_reason = mysql_result($result, 0, "closed_reason");
    if (user_is_a_sitemanager() || user_is_taskcenter_mgr() || $opened_by == $requester_u_id && empty($closed_reason)) {
        TaskForm($task_id);
    }
    else {
        ShowNotification("The user $pguser does not have permission to edit this task.");
        TaskDetails($task_id);
    }
}
elseif (isset($_POST['reopen_task'])) {
    $task_id = get_integer_param($_POST, 'reopen_task', null, 1, null);
    NotificationMail($task_id,
        "This task was reopened by $pguser on $date_str at $time_of_day_str.\n");
    $result = wrapped_mysql_query("
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
    $result = mysql_query("SELECT * FROM tasks WHERE task_id = $task_id");
    TaskDetails($task_id);
}
elseif (isset($_POST['newtask'])) {
    // The user is supplying values for the properties of a new OR pre-existing task.
    if (empty($_POST['task_summary']) || empty($_POST['task_details'])) {
        ShowNotification("You must supply a Task Summary and Task Details.", true);
    }
    else {
        if (!isset($_POST['task_id'])) {
            // Create a new task.
            $relatedtasks_array = array();
            $relatedtasks_array = base64_encode(serialize($relatedtasks_array));
            $relatedpostings_array = array();
            $relatedpostings_array = base64_encode(serialize($relatedpostings_array));
            $newt_type     = (int) get_enumerated_param($_POST, 'task_type', null, array_keys($tasks_array));
            $newt_category = (int) get_enumerated_param($_POST, 'task_category', null, array_keys($categories_array));
            $newt_status   = (int) get_enumerated_param($_POST, 'task_status', null, array_keys($tasks_status_array));
            $newt_assignee = (int) get_enumerated_param($_POST, 'task_assignee', null, array_keys($task_assignees_array));
            $newt_severity = (int) get_enumerated_param($_POST, 'task_severity', null, array_keys($severity_array));
            $newt_priority = (int) get_enumerated_param($_POST, 'task_priority', null, array_keys($priority_array));
            $newt_os       = (int) get_enumerated_param($_POST, 'task_os', null, array_keys($os_array));
            $newt_browser  = (int) get_enumerated_param($_POST, 'task_browser', null, array_keys($browser_array));
            $newt_version  = (int) get_enumerated_param($_POST, 'task_version', null, array_keys($versions_array));

            $sql_query = "
                INSERT INTO tasks
                SET
                    task_id          = '',
                    task_summary     = '" . addslashes(htmlspecialchars($_POST['task_summary'])) . "',
                    task_type        = $newt_type,
                    task_category    = $newt_category,
                    task_status      = $newt_status,
                    task_assignee    = $newt_assignee,
                    task_severity    = $newt_severity,
                    task_priority    = $newt_priority,
                    task_os          = $newt_os,
                    task_browser     = $newt_browser,
                    task_version     = $newt_version,
                    task_details     = '" . addslashes(htmlspecialchars($_POST['task_details'], ENT_QUOTES)) . "',
                    date_opened      = $now_sse,
                    opened_by        = $requester_u_id,
                    date_closed      = '',
                    closed_by        = '',
                    date_edited      = $now_sse,
                    edited_by        = $requester_u_id,
                    percent_complete = 0,
                    related_tasks    = '$relatedtasks_array',
                    related_postings = '$relatedpostings_array'
            ";
            $result = wrapped_mysql_query($sql_query);
            $task_id = mysql_insert_id();

            $result = mysql_query("SELECT email, username FROM users WHERE u_id = $newt_assignee");
            if ($newt_assignee != 0) {
                maybe_mail(
                    mysql_result($result, 0, "email"),
                    "DP Task Center: Task #$task_id has been assigned to you",
                    mysql_result($result, 0, "username") . ", you have been assigned task #$task_id.  Please visit this task at $tasks_url?f=detail&tid=$task_id.\n\nIf you do not want to accept this task please edit the task and change the assignee to 'Unassigned'.\n\n--\nDistributed Proofreaders\n$code_url\n\nThis is an automated message that you had requested please do not respond directly to this e-mail.\r\n",
                    "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n"
                );
            }
            $result = wrapped_mysql_query("
                INSERT INTO usersettings (username, setting, value)
                VALUES ('$pguser', 'taskctr_notice', $task_id)
            ");
            list_all_open_tasks();
        }
        else {
            // Update a pre-existing task.
            $task_id = get_integer_param($_POST, 'task_id', null, 1, null);
            NotificationMail($task_id,
                "There has been an edit made to this task by $pguser on $date_str at $time_of_day_str.\n");
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

            $sql_query = "
                UPDATE tasks
                SET
                    task_summary     = '" . addslashes(htmlspecialchars($_POST['task_summary'])) . "',
                    task_type        = $edit_type,
                    task_category    = $edit_category,
                    task_status      = $edit_status,
                    task_assignee    = $edit_assignee,
                    task_severity    = $edit_severity,
                    task_priority    = $edit_priority,
                    task_os          = $edit_os,
                    task_browser     = $edit_browser,
                    task_version     = $edit_version,
                    task_details     = '" . addslashes(htmlspecialchars($_POST['task_details'], ENT_QUOTES)) . "',
                    date_edited      = $now_sse,
                    edited_by        = $requester_u_id,
                    percent_complete = $edit_percent
                WHERE task_id = $task_id
            ";
            $result = wrapped_mysql_query($sql_query);
            list_all_open_tasks();
        }
    }
}
elseif (isset($_POST['search_task'])) {
    search_and_list_tasks($_POST);
}
elseif (isset($_POST['close_task'])) {
    if (user_is_a_sitemanager() || user_is_taskcenter_mgr()) {
        $task_id   = get_integer_param($_POST, 'task_id', null, 1, null);
        $tc_reason = (int) get_enumerated_param($_POST, 'task_close_reason', null, array_keys($tasks_close_array));
        NotificationMail($task_id,
            "This task was closed by $pguser on $date_str at $time_of_day_str.\n\nThe reason for closing was: " . $tasks_close_array[$tc_reason] . ".\n");
        $result = wrapped_mysql_query("
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
        list_all_open_tasks();
    }
    else {
        ShowNotification("The user $pguser does not have permission to close tasks.");
    }
}
elseif (isset($_POST['new_comment'])) {
    $task_id = get_integer_param($_POST, 'new_comment', null, 1, null);
    if (!empty($_POST['task_comment'])) {
        NotificationMail($task_id,
            "There has been a comment added to this task by $pguser on $date_str at $time_of_day_str.\n");
        $result = wrapped_mysql_query("
            INSERT INTO tasks_comments (task_id, u_id, comment_date, comment)
            VALUES ($task_id, $requester_u_id, $now_sse, '" . addslashes(htmlspecialchars($_POST['task_comment'], ENT_QUOTES)) . "')
        ");
        $result = wrapped_mysql_query("
            UPDATE tasks
            SET date_edited = $now_sse, edited_by = $requester_u_id
            WHERE task_id = $task_id
        ");
        TaskDetails($task_id);
    }
    else {
        ShowNotification("You must supply a comment before clicking Add Comment.");
        TaskDetails($task_id);
    }
}
elseif (isset($_POST['new_relatedtask'])) {
    if (empty($_POST['related_task'])) {
        ShowNotification("You must supply a related task ID.", true);
    } else {
        $this_task_id    = get_integer_param($_POST, 'new_relatedtask', null, 1, null);
        $related_task_id = get_integer_param($_POST, 'related_task', null, 1, null);
        $checkTaskExists = mysql_query("SELECT task_id FROM tasks WHERE task_id = $related_task_id");
        $result = mysql_query("SELECT related_tasks FROM tasks WHERE task_id = $this_task_id");
        $relatedtasks_array = decode_array(mysql_result($result, 0, "related_tasks"));
        if (mysql_num_rows($checkTaskExists) >= 1 && $related_task_id != $this_task_id && !in_array($related_task_id, $relatedtasks_array)) {
            array_push($relatedtasks_array, $related_task_id);
            $relatedtasks_array = base64_encode(serialize($relatedtasks_array));
            $result = wrapped_mysql_query("
                UPDATE tasks
                SET related_tasks = '$relatedtasks_array'
                WHERE task_id = $this_task_id
            ");
            NotificationMail($this_task_id,
                "This task had a related task added to it by $pguser on $date_str at $time_of_day_str.\n");
            list_all_open_tasks();
        }
        else {
            ShowNotification("You must supply a valid related task id number.");
        }
    }
}
elseif (isset($_POST['new_relatedposting'])) {
    if (empty($_POST['related_posting'])) {
        ShowNotification("You must supply a related topic ID.", true);
    } else {
        $nrp_task_id = get_integer_param($_POST, 'new_relatedposting', null, 1, null);
        $r_posting = get_integer_param($_POST, 'related_posting', null, 1, null);
        $result = mysql_query("SELECT related_postings FROM tasks WHERE task_id = $nrp_task_id");
        $relatedpostings_array = decode_array(mysql_result($result, 0, "related_postings"));
        if (does_topic_exist($r_posting) && !in_array($r_posting, $relatedpostings_array)) {
            array_push($relatedpostings_array, $r_posting);
            $relatedpostings_array = base64_encode(serialize($relatedpostings_array));
            $result = wrapped_mysql_query("
                UPDATE tasks
                SET related_postings = '$relatedpostings_array'
                WHERE task_id = $nrp_task_id
            ");
            NotificationMail($nrp_task_id,
                "This task had a related posting added to it by $pguser on $date_str at $time_of_day_str.\n");
            list_all_open_tasks();
        }
        else {
            ShowNotification("You must supply a valid related topic id number.", true);
        }
    }
}
elseif (isset($_POST['meToo'])) {
    $task_id       = get_integer_param($_REQUEST, 'meToo', null, 1, null);
    $sameOS        = get_integer_param($_REQUEST, 'sameOS', null, 0, 1);
    $sameBrowser   = get_integer_param($_REQUEST, 'sameBrowser', null, 0, 1);
    $os_param_name      = ( $sameOS      ? 'task_os'      : 'metoo_os' );
    $browser_param_name = ( $sameBrowser ? 'task_browser' : 'metoo_browser' );
    $vote_os       = (int) get_enumerated_param($_POST, $os_param_name, null, array_keys($os_array));
    $vote_browser  = (int) get_enumerated_param($_POST, $browser_param_name, null, array_keys($browser_array));

    // Do not insert twice the same vote if the user refreshes the browser
    $meTooCheck = mysql_query("
        SELECT 1 FROM tasks_votes WHERE task_id = $task_id and u_id = $requester_u_id LIMIT 1
    ");
    if (mysql_num_rows($meTooCheck) == 0) wrapped_mysql_query("
            INSERT INTO tasks_votes 
            (task_id, u_id, vote_os, vote_browser) 
            VALUES ($task_id, $requester_u_id, $vote_os, $vote_browser)
        ");
    mysql_free_result($meTooCheck);

    // No need to display a different error message if the user was refreshing
    ShowNotification("Thank you for your report!  It has been recorded below.", false, "info");
    TaskDetails($task_id);
}
else {
    // Either they just entered the Task Center
    // (e.g., by clicking the "Report a Bug" link)
    // or they clicked a column-header-link in a listing of tasks.
    // (Or they followed a bookmark of one of those.)

    if (isset($_GET['search_text'])) {
        search_and_list_tasks($_GET);
    }
    else {
        list_all_open_tasks();
    }
}
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</div>";
echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />\n";
theme("", "footer");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function dropdown_select($field_name, $current_value, $array)
{
    echo "<select size='1' name='$field_name' ID='$field_name' class='taskselect'>\n";
    while (list($key, $val) = each($array)) {
        echo "<option value='$key'";
        if ($current_value == $key) {
            echo " SELECTED";
        }
        echo ">$val</option>\n";
    }
    echo "</select>\n";
}

function TaskHeader()
{
    global $tasks_url;

    echo "<form action='$tasks_url' method='get'><input type='hidden' name='f' value='detail'>";
    echo "<table class='taskplain'>\n";
    echo "<tr><td width='50%'>&nbsp;</td>\n";
    echo "<td width='50%' style='text-align:right;'>";
    echo "<b><small class='task'>Show Task #</small></b>";
    echo "&nbsp;\n";
    echo "<input type='text' name='tid' size='12' class='taskinp1'>&nbsp;\n";
    echo "<input type='submit' value='Go!' class='taskinp2'>\n";
    echo "</td></tr></table></form><br />\n";
    echo "<form action='$tasks_url' method='post'><input type='hidden' name='search_task'>";
    echo "<table class='tasks'>\n";
    echo "<tr><td width='10%'><b><small class='task'>Search:</small></b></td>\n";
    echo "<td width='70%'>";

    SearchParams_echo_controls();

    echo "<input type='submit' value='Search' class='taskinp2'></td>\n";
    echo "<td width='30%' style='text-align: right;'>";
    echo "<small class='task'>";
    echo "<a href='$tasks_url'>Task Center Home</a> | <a href='$tasks_url?f=newtask'>New Task</a>";
    echo "</small>";
    echo "</td>";
    echo "</tr>\n";
    echo "</table></form><br />\n";
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
        'task_summary'     => " style='width: 50%;'",
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
    if (@mysql_num_rows($sql_result) >= 1) {
        while ($row = mysql_fetch_assoc($sql_result)) {
            echo "<tr bgcolor='#ffffff'>\n";
            foreach ( $columns as $property_id => $attrs )
            {
                $formatted_value = property_format_value($property_id, $row, TRUE);
                echo "<td$attrs>$formatted_value</td>\n";
            }
            echo "</tr>\n";
        }
    }
    else {
        echo "<tr bgcolor='#ffffff'><td colspan='7'><center>No tasks found!</center></td></tr>";
    }
    echo "</table><br />\n";
    // if 2 tasks or more found, display the number of reported tasks
    if (@mysql_num_rows($sql_result) > 1) {
        echo "<p>" . @mysql_num_rows($sql_result) . " tasks listed.</p>";
    }
}

function TaskForm($tid)
{
    global $requester_u_id, $tasks_array, $severity_array, $categories_array, $tasks_status_array;
    global $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;
    global $task_assignees_array;
    global $priority_array, $tasks_url;
    if (!empty($tid)) {
        $result = mysql_query("SELECT * FROM tasks WHERE task_id = $tid");
    }
    if (empty($tid)) {
        // The user wants to create a task.
        // Initialize the form with default values.
        $task_version     = 1;
        $task_severity    = 4;
        $task_priority    = 3;
        $task_type        = 1;
        $task_category    = 1;
        $task_status      = 1;
        $task_os          = 0;
        $task_browser     = 0;
        $task_assignee    = 0;
        $task_summary     = "";
        $task_details     = "";
        $percent_complete = 0;
        $opened_by        = "";
    }
    else {
        // The user wants to edit an existing task.
        // Initialize the form with the current values of the task's properties.
        $task_version     = mysql_result($result, 0, "task_version");
        $task_severity    = mysql_result($result, 0, "task_severity");
        $task_priority    = mysql_result($result, 0, "task_priority");
        $task_type        = mysql_result($result, 0, "task_type");
        $task_category    = mysql_result($result, 0, "task_category");
        $task_status      = mysql_result($result, 0, "task_status");
        $task_os          = mysql_result($result, 0, "task_os");
        $task_browser     = mysql_result($result, 0, "task_browser");
        $task_assignee    = mysql_result($result, 0, "task_assignee");
        $task_summary     = stripslashes(mysql_result($result, 0, "task_summary"));
        $task_details     = stripslashes(mysql_result($result, 0, "task_details"));
        $percent_complete = mysql_result($result, 0, "percent_complete");
        $opened_by        = mysql_result($result, 0, "opened_by");
    }

    // Non-managers can only set the task status to New.
    if (!user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
        $tasks_status_array = array(1 => "New");
    }

    echo "<form action='$tasks_url' method='post'><input type='hidden' name='newtask'>\n";
    if (!empty($tid)) {
        echo "<input type='hidden' name='task_id' value='$tid'>";
    }
    echo "<table class='tasks'>\n";
    echo "<tr>";
    echo "<td colspan='2'>";
    echo "<b>" . property_get_label('task_summary', FALSE) . "&nbsp;</b>";
    echo "&nbsp;&nbsp;";
    echo "<input type='text' name='task_summary' value=\"$task_summary\" size='60' maxlength='80' class='taskinp1'>";
    echo "</td>";
    echo "</tr>\n";
    echo "<tr><td width='50%'><table class='taskplain'>\n";
    property_echo_select_tr('task_type', $task_type, $tasks_array);
    property_echo_select_tr('task_category', $task_category, $categories_array);
    property_echo_select_tr('task_status', $task_status, $tasks_status_array);
    property_echo_select_tr('task_assignee', $task_assignee, $task_assignees_array);
    property_echo_select_tr('task_os', $task_os, $os_array);
    echo "</table></td><td width='50%'><table class='taskplain'>\n";
    property_echo_select_tr('task_browser', $task_browser, $browser_array);
    property_echo_select_tr('task_severity', $task_severity, $severity_array);
    property_echo_select_tr('task_priority', $task_priority, $priority_array);
    property_echo_select_tr('task_version', $task_version, $versions_array);
    if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && !empty($tid)) {
        property_echo_select_tr('percent_complete', $percent_complete, $percent_complete_array);
    }
    elseif ($opened_by == $requester_u_id && !user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
        echo "<input type='hidden' name='percent_complete' value='$percent_complete'>";
    }
    echo "</table></td></tr><tr><td>\n";
    echo "<table class='taskplain'><tr><td width='5%'><b>Details</b>&nbsp;&nbsp;</td>\n";
    echo "<td width='95%'>";
    echo "<textarea name='task_details' cols='60' rows='5'>$task_details</textarea>";
    echo "</td>";
    echo "</tr>";
    echo "</table>\n";
    echo "</td></tr><tr><td colspan='2'><center>\n";
    echo "<input type='submit' value='";
    if (empty($tid)) {
        echo "Add Task";
    }
    else {
        echo "Submit Edit";
    }
    echo "' class='taskinp2'>\n";
    echo "</center></td></tr></table><br />\n";
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
    $res = mysql_query("SELECT * FROM tasks WHERE task_id = $tid LIMIT 1");
    if (mysql_num_rows($res) >= 1) {
        while ($row = mysql_fetch_assoc($res)) {
            $result = mysql_query("
                SELECT *
                FROM usersettings
                WHERE setting = 'taskctr_notice' and (value = $tid or value = 'all') and username = '$pguser'
            ");
            if (mysql_num_rows($result) >= 1) {
                $already_notified = 1;
            }
            else {
                $already_notified = 0;
            }
            $opened_by_link = property_format_value('opened_by', $row, FALSE);
            $edited_by_link = property_format_value('edited_by', $row, FALSE);

            // Task id, summary, and possible Edit/Re-Open Task buttons.
            echo "<table class='tasks'>\n";
            echo "<tr bgcolor='#ecdbb7'>";
            echo "<td width='90%' valign='center'>";
            echo "Task #$tid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . property_format_value('task_summary', $row, FALSE);
            echo "</td>";
            echo "<td width='10%' valign='center' style='text-align:right;'>";
            echo "<form action='$tasks_url' method='post'>\n";
            if ((user_is_a_sitemanager() || user_is_taskcenter_mgr() || $row['opened_by'] == $requester_u_id) && empty($row['closed_reason'])) {
                echo "<input type='hidden' name='edit_task' value='$tid'>";
                echo "<input type='submit' value='Edit Task' class='taskinp2'>";
                echo "</td>";
                echo "</tr>";
                echo "</form>";
                echo "</table>\n";
            }
            elseif (!empty($row['closed_reason'])) {
                echo "<input type='hidden' name='reopen_task' value='$tid'>";
                echo "<input type='submit' value='Re-Open Task' class='taskinp2'>";
                echo "</td>";
                echo "</tr>";
                echo "</form>";
                echo "</table>\n";
            }
            else {
                echo "&nbsp;";
                echo "</td>";
                echo "</tr>";
                echo "</form>";
                echo "</table>";
            }
            echo "<table class='tasks'>\n";

            // Row 1: Opened & Last edited. Link to toggle task notifications.
            echo "<tr>";
            echo "<td width='50%'>";
            echo "<small class='task'>";
            echo "Opened by $opened_by_link - " . property_format_value('date_opened', $row, FALSE);
            echo "<br />";
            echo "Last edited by $edited_by_link - " . property_format_value('date_edited', $row, FALSE);
            echo "</small>";
            echo "</td>";
            echo "<td width='50%' style='text-align:right;'>";
            if (empty($already_notified)) {
                echo "<a href='$tasks_url?f=notifyme&tid=$tid'>Signup for task notifications</a>";
            }
            else {
                echo "<a href='$tasks_url?f=unnotifyme&tid=$tid'>Remove me from task notifications</a>";
            }
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
            $voteInfo = mysql_query("SELECT id FROM tasks_votes WHERE task_id = $tid");
            $osInfo = mysql_query("SELECT DISTINCT vote_os FROM tasks_votes WHERE task_id = $tid");
            $browserInfo = mysql_query("SELECT DISTINCT vote_browser FROM tasks_votes WHERE task_id = $tid");
            if (mysql_num_rows($voteInfo) > 0) {
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
                echo mysql_num_rows($voteInfo);
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td width='25%'>";
                echo "<b>Reported Operating Systems&nbsp;&nbsp;</b>";
                echo "</td>\n";
                echo "<td width='75%'>";
                while ($rowOS = mysql_fetch_assoc($osInfo)) {
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
                while ($rowBrowser = mysql_fetch_assoc($browserInfo)) {
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
            echo "<br />";
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
                      <br />
                      <form action='$tasks_url' method='post'>
                        <input type='hidden' name='close_task'>
                        <input type='hidden' name='task_id' value='$tid'>
                        <table class='taskplain'>
                          <tr>
                            <td width='20%' valign='bottom'>
                              <b>Close Task&nbsp;&nbsp;</b>
                            </td>
                            <td valign='bottom' width='80%'>
                ";
                dropdown_select('task_close_reason', "", $tasks_close_array);
                echo "
                              <input type='submit' value='Close Task' class='taskinp2'>
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
                      <br />
                      <small class='task'>
                        Closed by: $closed_by
                        <br />
                        Date Closed: $date_closed
                        <br />
                        Reason: $reason
                      </small>
                    </td>
                ";
            }
            echo "<td>";
            echo "<br />";
            $meTooCheckResult = mysql_query("
                SELECT id
                FROM tasks_votes
                WHERE task_id = $tid and u_id = $requester_u_id
            ");
            $meTooAllowed = (mysql_num_rows($meTooCheckResult) == 0);
            mysql_free_result($meTooCheckResult);
            if ($meTooAllowed) {
                echo "<input type='button' value='Me Too!' class='taskinp2' onClick=\"showSpan('MeTooMain');\">";
            }
            else {
                echo "&nbsp;";
            }
            echo "</td>";
            echo "</tr>";

            echo "</table>";
            echo "<br />\n";

            if ($meTooAllowed) {
                MeToo($tid, $row['task_os'], $row['task_browser']);
            }
            TaskComments($tid);
            RelatedTasks($tid);
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
    echo "<span id='MeTooMain' style='display: none;'>";
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='meToo' value='$tid'>";
    echo "<input type='hidden' name='task_os' value='$os'>";
    echo "<input type='hidden' name='task_browser' value='$browser'>";
    echo "<table class='tasks'><tr><td>\n";
    echo "<fieldset class='task'>";
    echo "<legend class='task'>Are you using the same operating system?</legend>";
    echo "&nbsp;";
    echo "<input onClick=\"hideSpan('OS');\" type='radio' name='sameOS' value='1' CHECKED>yes";
    echo "<input onClick=\"showSpan('OS');\" type='radio' name='sameOS' value='0'>no";
    echo "<span id='OS' style='display: none;'>";
    echo "<br />";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<b>Operating System</b>";
    echo "&nbsp;";
    dropdown_select('metoo_os', array_search(guess_OS_from_UA(), $os_array), $os_array);
    echo "</select>\n</span></fieldset>\n";
    echo "<br />";
    echo "<fieldset class='task'>";
    echo "<legend class='task'>Are you using the same browser?</legend>";
    echo "&nbsp;";
    echo "<input onClick=\"hideSpan('Browser');\" type='radio' name='sameBrowser' value='1' CHECKED>yes";
    echo "<input onClick=\"showSpan('Browser');\" type='radio' name='sameBrowser' value='0'>no";
    echo "<span id='Browser' style='display: none;'>";
    echo "<br />";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<b>Browser</b>";
    echo "&nbsp;";
    dropdown_select('metoo_browser', array_search(guess_browser_from_UA(), $browser_array), $browser_array);
    echo "</span></fieldset>\n";
    echo "<center>";
    echo "<input type='submit' value='Send Report' class='taskinp2'>";
    echo "&nbsp;";
    echo "<input type='reset' value='Reset' class='taskinp2' onClick=\"hideSpan('MeTooMain');\">";
    echo "</center>";
    echo "</td></tr></table></form></span>";
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
    $result = mysql_query("
        SELECT *
        FROM tasks_comments
        WHERE task_id = $tid
        ORDER BY comment_date ASC
    ");
    if (mysql_num_rows($result) >= 1) {
        echo "<table class='tasks'><tr><td width='100%'>\n";
        while ($row = mysql_fetch_assoc($result)) {
            $comment_username_link = private_message_link_for_uid($row['u_id']);
            echo "<b>Comment by $comment_username_link - " . date("l, d M Y, g:ia", $row['comment_date']) . "</b><br />";
            echo "<br />" . nl2br(stripslashes($row['comment'])) . "<br /><br /><hr width='80%' align='center'>";
        }
        echo "</td></tr></table>";
    }
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='new_comment' value='$tid'>";
    echo "<table class='tasks'><tr><td>\n";
    echo "<tr><td width='10%'><b>Add comment</b></td>";
    echo "<td width='90%'><textarea name='task_comment' cols='60' rows='5'></textarea></td></tr>";
    echo "<tr>";
    echo "<td width='100%' align='center' colspan='2'>";
    echo "<input type='submit' value='Add Comment' class='taskinp2'>\n";
    echo "</td></tr></table></form>";
}

function NotificationMail($tid, $message)
{
    global $code_url, $tasks_url, $auto_email_addr, $pguser;
    $result = mysql_query("
        SELECT username
        FROM usersettings
        WHERE setting = 'taskctr_notice' and (value = $tid or value = 'all')
    ");
    while ($row = mysql_fetch_assoc($result)) {
        if ($row['username'] != $pguser) {
            $temp = mysql_query("SELECT email FROM users WHERE username = '" . $row['username'] . "'");
            $email = mysql_result($temp, 0, "email");
            maybe_mail($email, "DP Task Center: Task #$tid has been updated",
                "You have requested notification of updates to task #$tid. "
              . "$message" . "\n"
              . "You can see task #$tid by visiting $tasks_url?f=detail&tid=$tid" . "\n\n"
              . "--" . "\n"
              . "Distributed Proofreaders" . "\n" . "$code_url" . "\n\n"
              . "This is an automated message that you had requested, please do not respond directly to this e-mail.",
                "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
        }
    }
}

function RelatedTasks($tid)
{
    global $tasks_url, $tasks_status_array;
    $result = mysql_query("SELECT related_tasks FROM tasks WHERE task_id = $tid");
    $related_tasks = mysql_result($result, 0, "related_tasks");
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='new_relatedtask' value='$tid'>";
    echo "<table class='tasks'>\n";
    echo "<tr><td width='100%'><b>Related Tasks&nbsp;&nbsp;</b>";
    echo "<input type='text' name='related_task' size='30' class='taskinp1'>&nbsp;&nbsp;";
    echo "<input type='submit' value='Add' class='taskinp2'>\n";
    echo " (Add the number of an existing, related task. This is optional.)";
    $related_tasks = decode_array($related_tasks);
    asort($related_tasks);
    while (list($key, $val) = each($related_tasks)) {
        $result = mysql_query("
            SELECT task_status, task_summary FROM tasks WHERE task_id = $val
        ") or die(mysql_error());
        if (mysql_num_rows($result) == 0) {
            // The task must have been deleted from the table manually.
            $task_summary = "[not found]";
        }
        else {
            // summary is stored in the database as addslashes(htmlspecialchars(...)),
            // so we need to use stripslashes() to display it in HTML.
            $task_summary = stripslashes(mysql_result($result, 0, "task_summary"));
            $task_status  = $tasks_status_array[mysql_result($result, 0, "task_status")];
        }
        echo "<br /><a href='$tasks_url?f=detail&tid=$val'>Task #$val</a> ($task_status) - $task_summary\n";
    }
    echo "</td></tr></table></form>";
}

function RelatedPostings($tid)
{
    global $forums_url, $tasks_url;
    $result = mysql_query("SELECT related_postings FROM tasks WHERE task_id = $tid");
    $related_postings = mysql_result($result, 0, "related_postings");
    echo "<form action='$tasks_url' method='post'>";
    echo "<input type='hidden' name='new_relatedposting' value='$tid'>";
    echo "<table class='tasks'>\n";
    echo "<tr><td width='100%'><b>Related Topic ID&nbsp;&nbsp;</b>";
    echo "<input type='text' name='related_posting' size='30' class='taskinp1'>&nbsp;&nbsp;";
    echo "<input type='submit' value='Add' class='taskinp2'>\n";
    echo " (Optional)";
    $related_postings = decode_array($related_postings);
    asort($related_postings);
    while (list($key, $val) = each($related_postings)) {
        $row = get_topic_details($val);
        $forum_url = get_url_to_view_forum($row["forum_id"]);
        $topic_url = get_url_to_view_topic($row["topic_id"]);
        echo "<br />";
        echo "<a href='$forum_url'>" . $row['forum_name'] . "</a>";
        echo "&nbsp;&raquo;&nbsp;";
        echo "<a href='$topic_url'>" . $row['title'] . "</a>";
        echo " (Posted by: " . $row['creator_username'] . " - " . $row['num_replies'] . " replies)\n";
    }
    echo "</td></tr></table></form>";
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
            $fv = stripslashes($raw_value); break; // maybe wrap in <a>

        case 'task_details':
            return nl2br(stripslashes($raw_value));

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
        $url = "$tasks_url?f=detail&tid=" . $task_a['task_id'];
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
    $res = mysql_query("SELECT username FROM users WHERE u_id = $u_id");
    $username = mysql_result($res, 0, "username");
    return $username;
}

function wrapped_mysql_query($sql_query)
{
    global $testing;
    if ($testing) echo_html_comment($sql_query);
    $res = mysql_query($sql_query);
    if ($res === FALSE) die(mysql_error());
    return $res;
}

// vim: sw=4 ts=4 expandtab
?>
