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

$valid_orderbys = array('task_id','task_type','task_severity','votes','task_summary','date_edited','task_status','percent_complete');

$valid_f = get_enumerated_param($_GET, 'f', null, array('newtask', 'detail', 'notifyme', 'unnotifyme'), true);

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
$search_results_array = array(
    "20",
    "40",
    "60",
    "80",
    "100"
);
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

// Make the 'all' versions of each array used by search.
$tasks_all_array          = array(999 => 'All Task Types') + $tasks_array;
$categories_all_array     = array(999 => 'All Categories') +$categories_array;
$tasks_status_all_array   = array(998 => 'All Tasks', 999 => 'All Open Tasks') + $tasks_status_array;
$task_assignees_all_array = array(999 => 'All Developers') + $task_assignees_array;
$severity_all_array       = array(999 => 'All Severities') + $severity_array;
$priority_all_array       = array(999 => 'All Priorities') + $priority_array;
$versions_all_array       = array(999 => 'All Versions') + $versions_array;

$order_by = "ORDER BY date_edited DESC, task_severity ASC, task_type ASC";

echo "<br /><div align='center'><table class='taskplain' width='98%'><tr><td>\n";
TaskHeader();

if (isset($valid_f)) {
    switch ( $valid_f )
    {
        case 'newtask':
            TaskForm("");
            break;

        case 'detail':
            $task_id = get_integer_param($_REQUEST, 'tid', null, 1, null);
            TaskDetails($task_id);
            break;

        case 'notifyme':
            $task_id = get_integer_param($_REQUEST, 'tid', null, 1, null);
            $result = mysql_query("INSERT INTO usersettings (username, setting, value) VALUES ('$pguser', 'taskctr_notice', " . $task_id . ")");
            TaskDetails($task_id);
            break;

        case 'unnotifyme':
            $task_id = get_integer_param($_REQUEST, 'tid', null, 1, null);
            $result = mysql_query("DELETE FROM usersettings WHERE username = '$pguser' and setting = 'taskctr_notice' and value = " . $task_id . "");
            TaskDetails($task_id);
            break;
    }
}
elseif (isset($_POST['edit_task'])) {
    $task_id = get_integer_param($_POST, 'edit_task', null, 1, null);
    $result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
    $u_id = mysql_result($result, 0, "u_id");
    $result = mysql_query("SELECT * FROM tasks WHERE task_id = " . $task_id . "");
    $opened_by = mysql_result($result, 0, "opened_by");
    $closed_reason = mysql_result($result, 0, "closed_reason");
    if (user_is_a_sitemanager() || user_is_taskcenter_mgr() || $opened_by == $u_id && empty($closed_reason)) {
        TaskForm($task_id);
    }
    else {
        ShowNotification("The user $pguser does not have permission to edit this task.");
        TaskDetails($task_id);
    }
}
elseif (isset($_POST['reopen_task'])) {
    $task_id = get_integer_param($_POST, 'reopen_task', null, 1, null);
    NotificationMail($task_id, "This task was reopened by $pguser on " . date("l, F jS, Y", time()) . " at " . date("g:i a", time()) . ".\n");
    $result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
    $u_id = mysql_result($result, 0, "u_id");
    $result = mysql_query("UPDATE tasks SET task_status = 15, edited_by = $u_id, date_edited = " . time() . ", date_closed = 0, closed_by = 0, closed_reason = 0 WHERE task_id = " . $task_id . "");
    $result = mysql_query("SELECT * FROM tasks WHERE task_id = " . $task_id . "");
    TaskDetails($task_id);
}
elseif (isset($_POST['newtask'])) {
    if (empty($_POST['task_summary']) || empty($_POST['task_details'])) {
        ShowNotification("You must supply a Task Summary and Task Details.", true);
    }
    else {
        if (!isset($_POST['task_id'])) {
            $relatedtasks_array = array();
            $relatedtasks_array = base64_encode(serialize($relatedtasks_array));
            $relatedpostings_array = array();
            $relatedpostings_array = base64_encode(serialize($relatedpostings_array));
            $result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
            $u_id = mysql_result($result, 0, "u_id");
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
                INSERT INTO tasks (
                    task_id,
                    task_summary,
                    task_type,
                    task_category,
                    task_status,
                    task_assignee,
                    task_severity,
                    task_priority,
                    task_os,
                    task_browser,
                    task_version,
                    task_details,
                    date_opened,
                    opened_by,
                    date_closed,
                    closed_by,
                    date_edited,
                    edited_by,
                    percent_complete,
                    related_tasks,
                    related_postings
                ) VALUES (
                    '',
                    '" . addslashes(htmlspecialchars($_POST['task_summary'])) . "',
                    "  . $newt_type . ",
                    "  . $newt_category . ",
                    "  . $newt_status . ",
                    "  . $newt_assignee . ",
                    "  . $newt_severity . ",
                    "  . $newt_priority . ",
                    "  . $newt_os . ",
                    "  . $newt_browser . ",
                    "  . $newt_version . ",
                    '" . addslashes(htmlspecialchars($_POST['task_details'], ENT_QUOTES)) . "',
                    "  . time() . ",
                    $u_id,
                    '',
                    '',
                    "  . time() . ",
                    $u_id,
                    0,
                    '$relatedtasks_array',
                    '$relatedpostings_array'
                )
            ";
            if ($testing) echo_html_comment($sql_query);
            $result = mysql_query($sql_query);
            $result = mysql_query("SELECT email, username FROM users WHERE u_id = " . $newt_assignee . "");
            if (!empty($newt_assignee)) {
                maybe_mail(mysql_result($result, 0, "email") , "DP Task Center: Task #" . mysql_insert_id() . " has been assigned to you", mysql_result($result, 0, "username") . ", you have been assigned task #" . mysql_insert_id() . ".  Please visit this task at $tasks_url?f=detail&tid=" . mysql_insert_id() . ".\n\nIf you do not want to accept this task please edit the task and change the assignee to 'Unassigned'.\n\n--\nDistributed Proofreaders\n$code_url\n\nThis is an automated message that you had requested please do not respond directly to this e-mail.\r\n", "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
            }
            $result = mysql_query("INSERT INTO usersettings (username, setting, value) VALUES ('$pguser', 'taskctr_notice', " . mysql_insert_id() . ")");
            list_all_open_tasks($order_by);
        }
        else {
            $task_id = get_integer_param($_POST, 'task_id', null, 1, null);
            NotificationMail($task_id, "There has been an edit made to this task by $pguser on " . date("l, F jS, Y", time()) . " at " . date("g:i a", time()) . ".\n");
            $result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
            $u_id = mysql_result($result, 0, "u_id");
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
                    task_type        = "  . $edit_type . ",
                    task_category    = "  . $edit_category . ",
                    task_status      = "  . $edit_status . ",
                    task_assignee    = "  . $edit_assignee . ",
                    task_severity    = "  . $edit_severity . ",
                    task_priority    = "  . $edit_priority . ",
                    task_os          = "  . $edit_os . ",
                    task_browser     = "  . $edit_browser . ",
                    task_version     = "  . $edit_version . ",
                    task_details     = '" . addslashes(htmlspecialchars($_POST['task_details'], ENT_QUOTES)) . "',
                    date_edited      = "  . time() . ",
                    edited_by        = $u_id,
                    percent_complete = "  . $edit_percent . "
                WHERE task_id = " . $task_id . "
            ";
            if ($testing) echo_html_comment($sql_query);
            $result = mysql_query($sql_query);
            list_all_open_tasks($order_by);
        }
    }
}
elseif (isset($_POST['search_task'])) {
    search_and_list_tasks($_POST, $order_by);
}
elseif (isset($_POST['close_task'])) {
    if (user_is_a_sitemanager() || user_is_taskcenter_mgr()) {
        $task_id   = get_integer_param($_POST, 'task_id', null, 1, null);
        $tc_reason = (int) get_enumerated_param($_POST, 'task_close_reason', null, array_keys($tasks_close_array));
        NotificationMail($task_id, "This task was closed by $pguser on " . date("l, F jS, Y", time()) . " at " . date("g:i a", time()) . ".\n\nThe reason for closing was: " . $tc_reason . ".\n");
        $result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
        $u_id = mysql_result($result, 0, "u_id");
        $result = mysql_query("UPDATE tasks SET percent_complete = 100, task_status = 14, date_closed = " . time() . ", closed_by = $u_id, closed_reason = " . $tc_reason . ", date_edited = " . time() . ", edited_by = $u_id WHERE task_id = " . $task_id . "");
        list_all_open_tasks($order_by);
    }
    else {
        ShowNotification("The user $pguser does not have permission to close tasks.");
    }
}
elseif (isset($_POST['new_comment'])) {
    $task_id = get_integer_param($_POST, 'new_comment', null, 1, null);
    if (!empty($_POST['task_comment'])) {
        NotificationMail($task_id, "There has been a comment added to this task by $pguser on " . date("l, F jS, Y", time()) . " at " . date("g:i a", time()) . ".\n");
        $result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
        $u_id = mysql_result($result, 0, "u_id");
        $result = mysql_query("INSERT INTO tasks_comments (task_id, u_id, comment_date, comment) VALUES ($task_id, $u_id, " . time() . ", '" . addslashes(htmlspecialchars($_POST['task_comment'], ENT_QUOTES)) . "')");
        $result = mysql_query("UPDATE tasks SET date_edited = " . time() . ", edited_by = $u_id WHERE task_id = $task_id");
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
        $nr_task_id = get_integer_param($_POST, 'new_relatedtask', null, 1, null);
        $r_task_id  = get_integer_param($_POST, 'related_task', null, 1, null);
        $checkTaskExists = mysql_query("SELECT task_id FROM tasks WHERE task_id = " . $nr_task_id . "");
        $result = mysql_query("SELECT related_tasks FROM tasks WHERE task_id = " . $nr_task_id . "");
        $relatedtasks_array = decode_array(mysql_result($result, 0, "related_tasks"));
        if (mysql_num_rows($checkTaskExists) >= 1 && $r_task_id != $nr_task_id && !in_array($r_task_id, $relatedtasks_array)) {
            array_push($relatedtasks_array, $r_task_id);
            $relatedtasks_array = base64_encode(serialize($relatedtasks_array));
            $result = mysql_query("UPDATE tasks SET related_tasks = '$relatedtasks_array' WHERE task_id = " . $nr_task_id . "");
            NotificationMail($nr_task_id, "This task had a related task added to it by $pguser on " . date("l, F jS, Y", time()) . " at " . date("g:i a", time()) . ".\n");
            list_all_open_tasks($order_by);
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
        $result = mysql_query("SELECT related_postings FROM tasks WHERE task_id = " . $nrp_task_id . "");
        $relatedpostings_array = decode_array(mysql_result($result, 0, "related_postings"));
        if (does_topic_exist($r_posting) && !in_array($r_posting, $relatedpostings_array)) {
            array_push($relatedpostings_array, $r_posting);
            $relatedpostings_array = base64_encode(serialize($relatedpostings_array));
            $result = mysql_query("UPDATE tasks SET related_postings = '$relatedpostings_array' WHERE task_id = " . $nrp_task_id . "");
            NotificationMail($nrp_task_id, "This task had a related posting added to it by $pguser on " . date("l, F jS, Y", time()) . " at " . date("g:i a", time()) . ".\n");
            list_all_open_tasks($order_by);
        }
        else {
            ShowNotification("You must supply a valid related topic id number.", true);
        }
    }
}
elseif (isset($_POST['meToo'])) {
    $metoo_or_task = array('metoo', 'task');
    $task_id       = get_integer_param($_REQUEST, 'meToo', null, 1, null);
    $sameOS        = get_integer_param($_REQUEST, 'sameOS', null, 0, 1);
    $sameBrowser   = get_integer_param($_REQUEST, 'sameBrowser', null, 0, 1);
    $vote_os       = (int) get_enumerated_param($_POST, $metoo_or_task[$sameOS] . '_os', null, array_keys($os_array));
    $vote_browser  = (int) get_enumerated_param($_POST, $metoo_or_task[$sameBrowser] . '_browser', null, array_keys($browser_array));

    $user_id = $userP['u_id'];

    // Do not insert twice the same vote if the user refreshes the browser
    $meTooCheck = mysql_query("SELECT 1 FROM tasks_votes 
        WHERE task_id = $task_id and u_id = $user_id LIMIT 1");
    if (mysql_num_rows($meTooCheck) == 0) mysql_query("INSERT INTO tasks_votes 
            (task_id, u_id, vote_os, vote_browser) 
            VALUES ($task_id, $user_id, $vote_os, $vote_browser)");
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
    $req_direction = get_enumerated_param($_GET, 'direction', 'desc', array('asc', 'desc'));
    $req_order = get_enumerated_param($_GET, 'orderby', 'date_edited', $valid_orderbys);
    if (isset($req_order) && isset($req_direction)) {
        $order_by = "ORDER BY " . $req_order . " " . $req_direction;
    }
    if (isset($_GET['search_text'])) {
        search_and_list_tasks($_GET, $order_by);
    }
    else {
        list_all_open_tasks($order_by);
    }
}
echo "</td></tr></table></div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />\n";
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
    global $tasks_all_array, $severity_all_array, $priority_all_array, $task_assignees_all_array;
    global $categories_all_array, $tasks_status_all_array, $versions_all_array;
    global $search_results_array, $os_array, $browser_array, $tasks_close_array;
    global $percent_complete_array, $tasks_url;
    if (isset($_REQUEST['search_text']) && !empty($_REQUEST['search_text'])) {
        if (get_magic_quotes_gpc()) $_REQUEST['search_text'] = stripslashes($_REQUEST['search_text']);
        $search_text = htmlspecialchars($_REQUEST['search_text'], ENT_QUOTES);
    }
    else $search_text = "";
    echo "<form action='$tasks_url' method='get'><input type='hidden' name='f' value='detail'>";
    echo "<table class='taskplain'>\n";
    echo "<tr><td width='50%'>&nbsp;</td>\n";
    echo "<td width='50%' style='text-align:right;'><b><small class='task'>Show Task #</small></b>&nbsp;\n";
    echo "<input type='text' name='tid' size='12' class='taskinp1'>&nbsp;\n";
    echo "<input type='submit' value='Go!' class='taskinp2'>\n";
    echo "</td></tr></table></form><br />\n";
    echo "<form action='$tasks_url' method='post'><input type='hidden' name='search_task'>";
    echo "<table class='tasks'>\n";
    echo "<tr><td width='10%'><b><small class='task'>Search:</small></b></td>\n";
    echo "<td width='70%'><input type='text' value='$search_text' name='search_text' size='50' class='taskinp1'>\n";

    $task_type     = (int) get_enumerated_param($_REQUEST, 'task_type',     '999', array_keys($tasks_all_array));
    $task_severity = (int) get_enumerated_param($_REQUEST, 'task_severity', '999', array_keys($severity_all_array));
    $task_priority = (int) get_enumerated_param($_REQUEST, 'task_priority', '999', array_keys($priority_all_array));
    $task_assignee = (int) get_enumerated_param($_REQUEST, 'task_assignee', '999', array_keys($task_assignees_all_array));
    $task_category = (int) get_enumerated_param($_REQUEST, 'task_category', '999', array_keys($categories_all_array));
    $task_status   = (int) get_enumerated_param($_REQUEST, 'task_status',   '999', array_keys($tasks_status_all_array));
    $task_version  = (int) get_enumerated_param($_REQUEST, 'task_version',  '999', array_keys($versions_all_array));

    dropdown_select('task_type',     $task_type,     $tasks_all_array);
    dropdown_select('task_severity', $task_severity, $severity_all_array);
    dropdown_select('task_priority', $task_priority, $priority_all_array);
    dropdown_select('task_assignee', $task_assignee, $task_assignees_all_array);
    dropdown_select('task_category', $task_category, $categories_all_array);
    dropdown_select('task_status',   $task_status,   $tasks_status_all_array);
    dropdown_select('task_version',  $task_version,  $versions_all_array);
    
    echo "<input type='submit' value='Search' class='taskinp2'></td>\n";
    echo "<td width='30%' style='text-align: right;'><small class='task'><a href='$tasks_url'>Task Center Home</a> | <a href='$tasks_url?f=newtask'>New Task</a></small></td></tr>\n";
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

function list_all_open_tasks($order_by)
{
    global $testing;
    $sql_query = sql_query_for_tasks("WHERE date_closed = 0", $order_by);
    if ($testing) echo_html_comment($sql_query);
    $result = mysql_query($sql_query) or die(mysql_error());
    ShowTasks($result);
}

function sql_query_for_tasks($where_clause, $order_by)
{
    return "
        SELECT tasks.task_id,
          task_type,
          task_severity,
          task_summary,
          date_edited,
          task_status,
          percent_complete,
          CASE WHEN
             vote_os IS NULL THEN NULL
             ELSE COUNT(*) END AS votes
        FROM tasks
          LEFT OUTER JOIN tasks_votes USING (task_id)
        $where_clause
        GROUP BY task_id
        $order_by
    ";
}

// -----------------------------------------------------------------------------

function clause_all_or_match($request_params, $key)
{
    if ($request_params[$key] == 999) return $key . " >= 0";
    return $key . " = " . $request_params[$key];
}

function search_and_list_tasks($request_params, $order_by)
{
    global $testing;
    $task_type     = clause_all_or_match($request_params, 'task_type');
    $task_severity = clause_all_or_match($request_params, 'task_severity');
    $task_priority = clause_all_or_match($request_params, 'task_priority');
    $task_assignee = clause_all_or_match($request_params, 'task_assignee');
    $task_category = clause_all_or_match($request_params, 'task_category');
    $task_version  = clause_all_or_match($request_params, 'task_version');

    if ($request_params['task_status'] == 999) {
        $task_status = "task_status >= 0 AND date_closed = 0";
    }
    elseif ($request_params['task_status'] == 998) {
        $task_status = "task_status >= 0";
    }
    else {
        $task_status = "task_status = " . $request_params['task_status'];
    }
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
    $where_clause = "
        WHERE
            (
                POSITION('$search_text_summary' IN task_summary)
                OR
                POSITION('$search_text_details' IN task_details)
            )
            AND $task_type
            AND $task_severity
            AND $task_priority
            AND $task_assignee
            AND $task_category
            AND $task_status
            AND $task_version";
    $sql_query = sql_query_for_tasks($where_clause, $order_by);
    if ($testing) echo_html_comment($sql_query);
    $result = mysql_query($sql_query);

    ShowTasks($result);
}

// -----------------------------------------------------------------------------

function OrderBy($orderby_var)
{
    global $valid_orderbys;
    $direction = get_enumerated_param($_GET, 'direction', 'asc', array('asc', 'desc'));
    $orderby   = get_enumerated_param($_GET, 'orderby', null, $valid_orderbys, true);

    if ($orderby == $orderby_var) {
        if ($direction == "asc") {
            $direction = "desc";
        } else {
            $direction = "asc";
        }
    }
    return "orderby=$orderby_var&direction=$direction";
}

function ShowTasks($sql_result)
{
    global $code_url, $tasks_url;
    global $tasks_all_array, $severity_all_array, $task_assignees_all_array, $categories_all_array;
    global $tasks_status_all_array, $priority_all_array, $versions_all_array;
    global $tasks_array, $severity_array, $task_assignees_array, $categories_array;
    global $tasks_status_array, $priority_array, $versions_array;
    global $search_results_array, $os_array, $browser_array, $tasks_close_array, $percent_complete_array;
    if (isset($_REQUEST['search_text'])) {
        $t = "search_text="     . urlencode($_REQUEST['search_text'])
            . "&task_type="     . get_enumerated_param($_REQUEST,'task_type'    ,   '1',          array_keys($tasks_all_array))
            . "&task_severity=" . get_enumerated_param($_REQUEST,'task_severity',   '4',       array_keys($severity_all_array))
            . "&task_priority=" . get_enumerated_param($_REQUEST,'task_priority',   '3',       array_keys($priority_all_array))
            . "&task_assignee=" . get_enumerated_param($_REQUEST,'task_assignee', '999', array_keys($task_assignees_all_array))
            . "&task_category=" . get_enumerated_param($_REQUEST,'task_category',   '1',     array_keys($categories_all_array))
            . "&task_status="   . get_enumerated_param($_REQUEST,'task_status'  ,   '1',   array_keys($tasks_status_all_array))
            . "&task_version="  . get_enumerated_param($_REQUEST,'task_version' ,   '1',       array_keys($versions_all_array)) 
            . "&";
    }
    else {
        $t = "";
    }
    echo "<table class='taskslist'><tr>\n";
    echo "<th style='text-align: center;'><a href='$tasks_url?$t" . OrderBy("task_id") . "'>ID</a></th>\n";
    echo "<th><a href='$tasks_url?$t" . OrderBy("task_type") . "'>Task Type</a></th>\n";
    echo "<th><a href='$tasks_url?$t" . OrderBy("task_severity") . "'>Severity</a></th>\n";
    echo "<th style='width: 50%;'><a href='$tasks_url?$t" . OrderBy("task_summary") . "'>Summary</a></th>\n";
    echo "<th style='text-align: center;'><a href='$tasks_url?$t" . OrderBy("date_edited") . "'>Date Edited</a></th>\n";
    echo "<th><a href='$tasks_url?$t" . OrderBy("task_status") . "'>Status</a></th>\n";
    echo "<th><a href='$tasks_url?$t" . OrderBy("votes") . "'>Votes</a></th>\n";
    echo "<th><a href='$tasks_url?$t" . OrderBy("percent_complete") . "'>Progress</a></th>\n";
    echo "</tr>\n";
    if (@mysql_num_rows($sql_result) >= 1) {
        while ($row = mysql_fetch_assoc($sql_result)) {
            echo "<tr bgcolor='#ffffff'>\n";
            echo "<td style='text-align: center;'><a href='$tasks_url?f=detail&tid=" . $row['task_id'] . "'>" . $row['task_id'] . "</a></td>\n";
            echo "<td>" . $tasks_array[$row['task_type']] . "</td>\n";
            echo "<td>" . $severity_array[$row['task_severity']] . "</td>\n";
            echo "<td style='width: 50%;'><a href='$tasks_url?f=detail&tid=" . $row['task_id'] . "'>" . stripslashes($row['task_summary']) . "</a></td>\n";
            echo "<td style='text-align: center;'>" . date("d-M-Y", $row['date_edited']) . "</td>\n";
            echo "<td>" . $tasks_status_array[$row['task_status']] . "</td>\n";
            echo "<td>" . $row['votes'] . "</td>\n";
            echo "<td><img src='$code_url/graphics/task_percentages/small_" . $row['percent_complete'] . ".png' width='50' height='8' alt='" . $row['percent_complete'] . "% Complete'></td>\n";
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
    global $userP, $tasks_array, $severity_array, $categories_array, $tasks_status_array;
    global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;
    global $task_assignees_array;
    global $priority_array, $tasks_url;
    if (!empty($tid)) {
        $result = mysql_query("SELECT * FROM tasks WHERE task_id = $tid");
    }
    if (empty($tid)) {
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

    echo "<form action='$tasks_url' method='post'><input type='hidden' name='newtask'>\n";
    if (!empty($tid)) {
        echo "<input type='hidden' name='task_id' value='$tid'>";
    }
    echo "<table class='tasks'>\n";
    echo "<tr><td colspan='2'><b>Summary&nbsp;</b>&nbsp;&nbsp;<input type='text' name='task_summary' value=\"$task_summary\" size='60' maxlength='80' class='taskinp1'></td></tr>\n";
    echo "<tr><td width='50%'><table class='taskplain'>\n";
    echo "<tr><td width='40%'><b>Task Type</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select('task_type', $task_type, $tasks_array);
    echo "</td></tr>\n";
    echo "<tr><td width='40%'><b>Category</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select('task_category', $task_category, $categories_array);
    echo "</td></tr>\n";
    echo "<tr><td width='40%'><b>Status</b>&nbsp;</td><td width='60%'>\n";
    // Non-managers can only set the task status to New.
    if (!user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
        $tasks_status_array = array(1 => "New");
    }
    dropdown_select('task_status', $task_status, $tasks_status_array);
    echo "</td></tr>\n";
    echo "<tr><td width='40%'><b>Assigned To</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select('task_assignee', $task_assignee, $task_assignees_array);
    echo "</td></tr>\n";
    echo "<tr><td width='40%'><b>Operating System</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select('task_os', $task_os, $os_array);
    echo "</td></tr>\n";
    echo "</table></td><td width='50%'><table class='taskplain'>\n";
    echo "<tr><td width='40%'><b>Browser</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select('task_browser', $task_browser, $browser_array);
    echo "</td></tr>\n";
    echo "<tr><td width='40%'><b>Severity</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select('task_severity', $task_severity, $severity_array);
    echo "</td></tr>\n";
    echo "<tr><td width='40%'><b>Priority</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select('task_priority', $task_priority, $priority_array);
    echo "</td></tr>\n";
    echo "<tr><td width='40%'><b>Reported Version</b>&nbsp;</td><td width='60%'>\n";
    dropdown_select('task_version', $task_version, $versions_array);
    echo "</td></tr>\n";
    if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && !empty($tid)) {
        echo "<tr><td width='40%'><b>Percent Complete</b>&nbsp;</td><td width='60%'>\n";
        dropdown_select('percent_complete', $percent_complete, $percent_complete_array);
        echo "</td></tr>\n";
    }
    elseif ($opened_by == $userP['u_id'] && !user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
        echo "<input type='hidden' name='percent_complete' value='$percent_complete'>";
    }
    echo "</table></td></tr><tr><td>\n";
    echo "<table class='taskplain'><tr><td width='5%'><b>Details</b>&nbsp;&nbsp;</td>\n";
    echo "<td width='95%'><textarea name='task_details' cols='60' rows='5'>" . $task_details . "</textarea></td></tr></table>\n";
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

function TaskDetails($tid)
{
    global $userP, $code_url, $tasks_url, $tasks_array, $severity_array, $categories_array, $tasks_status_array;
    global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;
    global $priority_array, $pguser;
    if (!is_numeric($tid)) {
        ShowNotification("Error: task identifier '$tid' is not numeric.");
        return;
    }
    $result = mysql_query("SELECT * FROM tasks WHERE task_id = $tid LIMIT 1");
    if (mysql_num_rows($result) >= 1) {
        while ($row = mysql_fetch_assoc($result)) {
            $result = mysql_query("SELECT * FROM usersettings WHERE setting = 'taskctr_notice' and (value = $tid or value = 'all') and username = '$pguser'");
            if (mysql_num_rows($result) >= 1) {
                $already_notified = 1;
            }
            else {
                $already_notified = 0;
            }
            $result = mysql_query("SELECT username FROM users WHERE u_id = " . $row['opened_by'] . "");
            $opened_by = mysql_result($result, 0, "username");
            $opened_by_link = private_message_link($opened_by, NULL);
            $result = mysql_query("SELECT username FROM users WHERE u_id = " . $row['edited_by'] . "");
            $edited_by = mysql_result($result, 0, "username");
            $edited_by_link = private_message_link($edited_by, NULL);
            if (empty($row['task_assignee'])) {
                $task_assignee_username = "Unassigned";
                $task_assignee_username_link = $task_assignee_username;
            }
            else {
                $result = mysql_query("SELECT username FROM users WHERE u_id = " . $row['task_assignee'] . "");
                $task_assignee_username = mysql_result($result, 0, "username");
                $task_assignee_username_link = private_message_link($task_assignee_username, NULL);
            }
            echo "<table class='tasks'>\n";
            echo "<tr bgcolor='#ecdbb7'>";
            echo "<td width='90%' valign='center'>";
            echo "Task #$tid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . stripslashes($row['task_summary']);
            echo "</td>";
            echo "<td width='10%' valign='center' style='text-align:right;'>";
            echo "<form action='$tasks_url' method='post'>\n";
            if ((user_is_a_sitemanager() || user_is_taskcenter_mgr() || $row['opened_by'] == $userP['u_id']) && empty($row['closed_reason'])) {
                echo "<input type='hidden' name='edit_task' value='" . $row['task_id'] . "'>";
                echo "<input type='submit' value='Edit Task' class='taskinp2'>";
                echo "</td>";
                echo "</tr>";
                echo "</form>";
                echo "</table>\n";
            }
            elseif (!empty($row['closed_reason'])) {
                echo "<input type='hidden' name='reopen_task' value='" . $row['task_id'] . "'>";
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
            echo "<tr>";
            echo "<td width='50%'>";
            echo "<small class='task'>";
            echo "Opened by $opened_by_link - " . date("d-M-Y", $row['date_opened']);
            echo "<br />";
            echo "Last edited by $edited_by_link - " . date("d-M-Y", $row['date_edited']);
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
            echo "<tr>";
            echo "<td width='40%'>";
            echo "<table class='taskplain'>\n";
            EchoTaskProperty("Task Type",        $tasks_array[$row['task_type']]);
            EchoTaskProperty("Category",         $categories_array[$row['task_category']]);
            EchoTaskProperty("Status",           $tasks_status_array[$row['task_status']]);
            EchoTaskProperty("Assigned To",      $task_assignee_username_link);
            EchoTaskProperty("Operating System", $os_array[$row['task_os']]);
            echo "</table>";
            echo "</td>";
            echo "<td width='50%'>";
            echo "<table class='taskplain'>\n";
            EchoTaskProperty("Browser",          $browser_array[$row['task_browser']]);
            EchoTaskProperty("Severity",         $severity_array[$row['task_severity']]);
            EchoTaskProperty("Priority",         $priority_array[$row['task_priority']]);
            EchoTaskProperty("Reported Version", $versions_array[$row['task_version']]);
            EchoTaskProperty("Percent Complete", "<img src='$code_url/graphics/task_percentages/large_" . $row['percent_complete'] . ".png' width='150' height='10' border='0' alt='" . $row['percent_complete'] . "% Complete'>");
            echo "</table>";
            echo "</td>";
            echo "</tr>\n";
            $voteInfo = mysql_query("SELECT id FROM tasks_votes WHERE task_id = " . $tid . "");
            $osInfo = mysql_query("SELECT DISTINCT vote_os FROM tasks_votes WHERE task_id = " . $tid . "");
            $browserInfo = mysql_query("SELECT DISTINCT vote_browser FROM tasks_votes WHERE task_id = " . $tid . "");
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
            echo "<tr>";
            echo "<td>";
            echo "<br />";
            echo "<table class='taskplain'>";
            echo "<tr>";
            echo "<td width='5%'>";
            echo "<b>Details&nbsp;&nbsp;</b>";
            echo "</td>\n";
            echo "<td width='95%' class='taskvalue'>";
            echo nl2br(stripslashes($row['task_details']));
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            echo "</td>";
            echo "</tr>\n";
            if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && empty($row['closed_reason'])) {
                echo "<form action='$tasks_url' method='post'>";
                echo "<input type='hidden' name='close_task'>";
                echo "<input type='hidden' name='task_id' value='" . $row['task_id'] . "'>\n";
                echo "<tr>";
                echo "<td>";
                echo "<br />";
                echo "<table class='taskplain'>";
                echo "<tr>";
                echo "<td width='20%' valign='bottom'>";
                echo "<b>Close Task&nbsp;&nbsp;</b>";
                echo "</td>";
                echo "<td valign='bottom' width='80%'>";
                dropdown_select('task_close_reason', "", $tasks_close_array);
                echo "&nbsp;";
                echo "<input type='submit' value='Close Task' class='taskinp2'>\n";
                echo "</td>";
                echo "</tr>";
                echo "</form>";
                echo "</table>\n";
            }
            elseif (!empty($row['closed_reason'])) {
                $result = mysql_query("SELECT username FROM users WHERE u_id = " . $row['closed_by'] . "");
                $closed_by = mysql_result($result, 0, "username");
                echo "<tr>";
                echo "<td>";
                echo "<br />";
                echo "<small class='task'>";
                echo "Closed by: $closed_by";
                echo "<br />";
                echo "Date Closed: " . date("d-M-Y", $row['date_closed']);
                echo "<br />";
                echo "Reason: " . $tasks_close_array[$row['closed_reason']] . "";
            }
            echo "</small>";
            echo "</td>";
            echo "<td>";
            echo "<br />";
            $meTooCheckResult = mysql_query("SELECT id FROM tasks_votes WHERE task_id = " . $tid . " and u_id = " . $userP['u_id'] . "");
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
    global $tasks_url, $browser_array, $os_array, $userP;
    echo "<span id='MeTooMain' style='display: none;'>";
    echo "<form action='$tasks_url' method='post'><input type='hidden' name='meToo' value='$tid'><input type='hidden' name='task_os' value='$os'><input type='hidden' name='task_browser' value='$browser'>";
    echo "<table class='tasks'><tr><td>\n";
    echo "<fieldset class='task'><legend class='task'>Are you using the same operating system?</legend>";
    echo "&nbsp;<input onClick=\"hideSpan('OS');\" type='radio' name='sameOS' value='1' CHECKED>yes<input onClick=\"showSpan('OS');\" type='radio' name='sameOS' value='0'>no";
    echo "<span id='OS' style='display: none;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Operating System</b>&nbsp;";
    dropdown_select('metoo_os', array_search(guess_OS_from_UA(), $os_array), $os_array);
    echo "</select>\n</span></fieldset>\n";
    echo "<br /><fieldset class='task'><legend class='task'>Are you using the same browser?</legend>";
    echo "&nbsp;<input onClick=\"hideSpan('Browser');\" type='radio' name='sameBrowser' value='1' CHECKED>yes<input onClick=\"showSpan('Browser');\" type='radio' name='sameBrowser' value='0'>no";
    echo "<span id='Browser' style='display: none;'><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Browser</b>&nbsp;";
    dropdown_select('metoo_browser', array_search(guess_browser_from_UA(), $browser_array), $browser_array);
    echo "</span></fieldset>\n";
    echo "<center><input type='submit' value='Send Report' class='taskinp2'>&nbsp;<input type='reset' value='Reset' class='taskinp2' onClick=\"hideSpan('MeTooMain');\"></center>";
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

function EchoTaskProperty($name, $value)
{
    echo "<tr>";
    echo "<td class='taskproperty'>";
    echo $name;
    echo "&nbsp;&nbsp;";
    echo "</td>";
    echo "<td class='taskvalue'>";
    echo $value;
    echo "</td>";
    echo "</tr>";
    echo "\n";
}

function TaskComments($tid)
{
    global $tasks_url;
    $result = mysql_query("SELECT * FROM tasks_comments WHERE task_id = $tid ORDER BY comment_date ASC");
    if (mysql_num_rows($result) >= 1) {
        echo "<table class='tasks'><tr><td width='100%'>\n";
        while ($row = mysql_fetch_assoc($result)) {
            $usernameQuery = mysql_query("SELECT username FROM users WHERE u_id = " . $row['u_id'] . "");
            $comment_username = mysql_result($usernameQuery, 0, "username");
            $comment_username_link = private_message_link($comment_username, NULL);
            echo "<b>Comment by $comment_username_link - " . date("l, d M Y, g:ia", $row['comment_date']) . "</b><br />";
            echo "<br />" . nl2br(stripslashes($row['comment'])) . "<br /><br /><hr width='80%' align='center'>";
        }
        echo "</td></tr></table>";
    }
    echo "<form action='$tasks_url' method='post'><input type='hidden' name='new_comment' value='$tid'>";
    echo "<table class='tasks'><tr><td>\n";
    echo "<tr><td width='10%'><b>Add comment</b></td>";
    echo "<td width='90%'><textarea name='task_comment' cols='60' rows='5'></textarea></td></tr>";
    echo "<tr><td width='100%' align='center' colspan='2'><input type='submit' value='Add Comment' class='taskinp2'>\n";
    echo "</td></tr></table></form>";
}

function NotificationMail($tid, $message)
{
    global $code_url, $tasks_url, $auto_email_addr, $pguser;
    $result = mysql_query("SELECT username FROM usersettings WHERE setting = 'taskctr_notice' and (value = $tid or value = 'all')");
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
    echo "<form action='$tasks_url' method='post'><input type='hidden' name='new_relatedtask' value='$tid'>";
    echo "<table class='tasks'>\n";
    echo "<tr><td width='100%'><b>Related Tasks&nbsp;&nbsp;</b>";
    echo "<input type='text' name='related_task' size='30' class='taskinp1'>&nbsp;&nbsp;";
    echo "<input type='submit' value='Add' class='taskinp2'>\n";
    echo " (Add the number of an existing, related task. This is optional.)";
    $related_tasks = decode_array($related_tasks);
    asort($related_tasks);
    while (list($key, $val) = each($related_tasks)) {
        $result = mysql_query("SELECT task_status, task_summary FROM tasks WHERE task_id = $val") or die(mysql_error());
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
    echo "<form action='$tasks_url' method='post'><input type='hidden' name='new_relatedposting' value='$tid'>";
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
        echo "<br /><a href='$forum_url'>" . $row['forum_name'] . "</a>&nbsp;&raquo;&nbsp;<a href='$topic_url'>" . $row['title'] . "</a> (Posted by: " . $row['creator_username'] . " - " . $row['num_replies'] . " replies)\n";
    }
    echo "</td></tr></table></form>";
}
// vim: sw=4 ts=4 expandtab
?>
