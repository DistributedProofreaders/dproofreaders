<?
$relPath='pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'maybe_mail.inc');
$no_stats=1;
theme('Task Center','header');
?><script language='javascript'><!--
function showSpan(id) {
	document.getElementById(id).style.display="";
}
function hideSpan(id) {
	document.getElementById(id).style.display="none";
}
// --></script><?

$tasks_array = array(1 => "Bug Report",
    2 => "Feature Request",
    3 => "Support Request",
    4 => "Site Administrator Request");
$severity_array = array(1 => "Catastrophic",
    2 => "Critical",
    3 => "Major",
    4 => "Normal",
    5 => "Minor",
    6 => "Trivial",
    7 => "Enhancement");
$priority_array = array(1 => "Very High",
    2 => "High",
    3 => "Medium",
    4 => "Low",
    5 => "Very Low");
$categories_array = array(1 => "None",
    2 => "Documentation",
    3 => "Entrance",
    4 => "Log in/out",
    5 => "New Member",
    6 => "Page Proofreading",
    7 => "Personal Page",
    8 => "Post-Processing",
    9 => "Preferences",
    10 => "Pre-Processing",
    11 => "Project Comments",
    12 => "Project Listing Interface",
    13 => "Project Manager",
    14 => "Site wide",
    15 => "Statistics",
    16 => "Translation",
    17 => "Task Center",
    99 => "Other");
$tasks_status_array = array(1 => "New",
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
    18 => "In Progress");
$search_results_array = array("20",
    "40",
    "60",
    "80",
    "100");
$os_array = array(0 => "All",
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
    15 => "MacOS X",
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
    99 => "Other");
$browser_array = array(0 => "All",
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
    99 => "Other");
$versions_array = array(1 => "pgdp.net (Live)",
    4 => "dp.rastko.net (Live)",
    2 => "texts01 (Beta)",
    3 => "CVS");
$tasks_close_array = array(1 => "Not a Bug",
    2 => "Won't Fix",
    3 => "Won't Implement",
    4 => "Works for Me",
    5 => "Duplicate",
    6 => "Deferred",
    7 => "Fixed",
    8 => "Implemented");
$percent_complete_array = array(0 => "0%",
    10 => "10%",
    20 => "20%",
    30 => "30%",
    40 => "40%",
    50 => "50%",
    60 => "60%",
    70 => "70%",
    80 => "80%",
    90 => "90%",
    100 => "100%");

$task_assignees_array = array();
$order_by = "ORDER BY date_edited DESC, task_severity ASC, task_type ASC";

echo "<br><div align='center'><table border='0' cellpadding='0' cellspacing='0' width='98%'><tr><td>\n";
TaskHeader();

if (isset($_GET['f']) && $_GET['f'] == "newtask") {
	TaskForm("");
} elseif (isset($_POST['edit_task'])) {
	TaskForm($_POST['edit_task']);
} elseif (isset($_POST['reopen_task'])) {
	NotificationMail($_POST['reopen_task'], "This task was reopened by $pguser on ".date("l, F jS, Y", time())." at ".date("g:i a", time()).".\n");
	$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
	$u_id = mysql_result($result, 0, "u_id");
	$result = mysql_query("UPDATE tasks SET task_status = 15, edited_by = $u_id, date_edited = ".time().", date_closed = 0, closed_by = 0, closed_reason = 0 WHERE task_id = ".$_POST['reopen_task']."");
	$result = mysql_query("SELECT * FROM tasks WHERE task_id = ".$_POST['reopen_task']."");
	TaskDetails($_POST['reopen_task']);
} elseif (isset($_POST['newtask'])) {
	if (empty($_POST['task_summary']) || empty($_POST['task_details'])) {
		echo "<center><b><font face='Verdana' color='#ff0000' style='font-size: 11px'>You must supply a Task Summary and Task Details.  Please go <a href='javascript:history.back()'>back</a> and correct this.</font></b></center>";
	} else {
		if (!isset($_POST['task_id'])) {
			$relatedtasks_array = array();
			$relatedtasks_array = base64_encode(serialize($relatedtasks_array));
			$relatedpostings_array = array();
			$relatedpostings_array = base64_encode(serialize($relatedpostings_array));
			$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
			$u_id = mysql_result($result, 0, "u_id");
			$result = mysql_query("INSERT INTO tasks (task_id, task_summary, task_type, task_category, task_status, task_assignee, task_severity, task_priority, task_os, task_browser, task_version, task_details, date_opened, opened_by, date_closed, closed_by, date_edited, edited_by, percent_complete, related_tasks, related_postings) VALUES ('', '".addslashes(htmlspecialchars($_POST['task_summary']))."', ".$_POST['task_type'].", ".$_POST['task_category'].", ".$_POST['task_status'].", ".$_POST['task_assignee'].", ".$_POST['task_severity'].", ".$_POST['task_priority'].", ".$_POST['task_os'].", ".$_POST['task_browser'].", ".$_POST['task_version'].", '".addslashes(htmlspecialchars($_POST['task_details'], ENT_QUOTES))."', ".time().", $u_id, '', '', ".time().", $u_id, 0, '$relatedtasks_array', '$relatedpostings_array')");
			$result = mysql_query("SELECT email, username FROM users WHERE u_id = ".$_POST['task_assignee']."");
			if (!empty($_POST['task_assignee'])) { maybe_mail(mysql_result($result, 0, "email"), "DP Task Center: Task #".mysql_insert_id()." has been assigned to you", mysql_result($result, 0, "username").", you have been assigned task #".mysql_insert_id().".  Please visit this task at $code_url/tasks.php?f=detail&tid=".mysql_insert_id().".\n\nIf you do not want to accept this task please edit the task and change the assignee to 'Unassigned'.\n\n--\nDistributed Proofreaders\n$code_url\n\nThis is an automated message that you had requested please do not respond directly to this e-mail.\r\n", "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n"); }
			$result = mysql_query("INSERT INTO usersettings (username, setting, value) VALUES ('$pguser', 'taskctr_notice', ".mysql_insert_id().")");
			$result = mysql_query("SELECT * FROM tasks WHERE date_closed = 0 $order_by");
			ShowTasks($result);
		} else {
			NotificationMail($_POST['task_id'], "There has been an edit made to this task by $pguser on ".date("l, F jS, Y", time())." at ".date("g:i a", time()).".\n");
			$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
			$u_id = mysql_result($result, 0, "u_id");
			$result = mysql_query("UPDATE tasks SET task_summary = '".addslashes($_POST['task_summary'])."', task_type = ".$_POST['task_type'].", task_category = ".$_POST['task_category'].", task_status = ".$_POST['task_status'].", task_assignee = ".$_POST['task_assignee'].", task_severity = ".$_POST['task_severity'].", task_priority = ".$_POST['task_priority'].", task_os = ".$_POST['task_os'].", task_browser = ".$_POST['task_browser'].", task_version = ".$_POST['task_version'].", task_details = '".addslashes(htmlspecialchars($_POST['task_details'], ENT_QUOTES))."', date_edited = ".time().", edited_by = $u_id, percent_complete = ".$_POST['percent_complete']." WHERE task_id = ".$_POST['task_id']."");
			$result = mysql_query("SELECT * FROM tasks WHERE date_closed = 0 $order_by");
			ShowTasks($result);
		}
	}
} elseif (isset($_POST['search_task'])) {
	if ($_POST['task_type'] == 999) { $task_type = "task_type >= 0"; } else { $task_type = "task_type = ".$_POST['task_type']; }
	if ($_POST['task_severity'] == 999) { $task_severity = "task_severity >= 0"; } else { $task_severity = "task_severity = ".$_POST['task_severity']; }
	if ($_POST['task_priority'] == 999) { $task_priority = "task_priority >= 0"; } else { $task_priority = "task_priority = ".$_POST['task_priority']; }
	if ($_POST['task_assignee'] == 999) { $task_assignee = "task_assignee >= 0"; } else { $task_assignee = "task_assignee = ".$_POST['task_assignee']; }
	if ($_POST['task_category'] == 999) { $task_category = "task_category >= 0"; } else { $task_category = "task_category = ".$_POST['task_category']; }	if ($_POST['task_status'] == 999) { $task_status = "task_status >= 0 AND date_closed = 0"; } elseif ($_POST['task_status'] == 998) { $task_status = "task_status >= 0"; } else { $task_status = "task_status = ".$_POST['task_status']; }
	if ($_POST['task_version'] == 999) { $task_version = "task_version >= 0"; } else { $task_version = "task_version = ".$_POST['task_version']; }
	$sql_query = "SELECT * FROM tasks WHERE (task_summary LIKE '%".$_POST['search_text']."%' OR task_details LIKE '%".$_POST['search_text']."%') AND $task_type AND $task_severity AND $task_priority AND $task_assignee AND $task_category AND $task_status AND $task_version $order_by";

	$result = mysql_query($sql_query);
	ShowTasks($result);
} elseif ((isset($_GET['f']) && $_GET['f'] == "detail") || isset($_POST['search_tid'])) {
	if (is_numeric($_REQUEST['tid'])) {
		TaskDetails($_REQUEST['tid']);
	} else {
		ShowTasks("");
	}
} elseif (isset($_POST['close_task'])) {
	NotificationMail($_POST['task_id'], "This task was closed by $pguser on ".date("l, F jS, Y", time())." at ".date("g:i a", time()).".\n\nThe reason for closing was: ".$tasks_close_array[$_POST['task_close_reason']].".\n");
	$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
	$u_id = mysql_result($result, 0, "u_id");
	$result = mysql_query("UPDATE tasks SET percent_complete = 100, task_status = 14, date_closed = ".time().", closed_by = $u_id, closed_reason = ".$_POST['task_close_reason'].", date_edited = ".time().", edited_by = $u_id WHERE task_id = ".$_POST['task_id']."");
	$result = mysql_query("SELECT * FROM tasks WHERE date_closed = 0 $order_by");
	ShowTasks($result);
} elseif (isset($_POST['new_comment'])) {
	if (!empty($_POST['task_comment'])) {
		NotificationMail($_POST['new_comment'], "There has been a comment added to this task by $pguser on ".date("l, F jS, Y", time())." at ".date("g:i a", time()).".\n");
		$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
		$u_id = mysql_result($result, 0, "u_id");
		$result = mysql_query("INSERT INTO tasks_comments (task_id, u_id, comment_date, comment) VALUES (".$_POST['new_comment'].", $u_id, ".time().", '".addslashes(htmlspecialchars($_POST['task_comment'], ENT_QUOTES))."')");
		$result = mysql_query("UPDATE tasks SET date_edited = ".time().", edited_by = $u_id WHERE task_id = ".$_POST['new_comment']);
		TaskDetails($_POST['new_comment']);
	} else {
		echo "<center><b><font color='red'>You must supply a comment before clicking Add Comment</font></b></center>";
		TaskDetails($_POST['new_comment']);
	}
} elseif (isset($_GET['f']) && $_GET['f'] == "notifyme") {
	$result = mysql_query("INSERT INTO usersettings (username, setting, value) VALUES ('$pguser', 'taskctr_notice', ".$_GET['tid'].")");
	TaskDetails($_GET['tid']);
} elseif (isset($_GET['f']) && $_GET['f'] == "unnotifyme") {
	$result = mysql_query("DELETE FROM usersettings WHERE username = '$pguser' && setting = 'taskctr_notice' && value = ".$_GET['tid']."");
	TaskDetails($_GET['tid']);
} elseif (isset($_POST['new_relatedtask'])) {
	$checkTaskExists = mysql_query("SELECT task_id FROM tasks WHERE task_id = ".$_POST['related_task']."");
	$result = mysql_query("SELECT related_tasks FROM tasks WHERE task_id = ".$_POST['new_relatedtask']."");
	$relatedtasks_array = unserialize(base64_decode(mysql_result($result, 0, "related_tasks")));
	if (is_numeric($_POST['related_task']) && mysql_num_rows($checkTaskExists) >= 1 && $_POST['related_task'] != $_POST['new_relatedtask'] && !in_array($_POST['related_task'], $relatedtasks_array)) {
		array_push($relatedtasks_array, $_POST['related_task']);
		$relatedtasks_array = base64_encode(serialize($relatedtasks_array));
		$result = mysql_query("UPDATE tasks SET related_tasks = '$relatedtasks_array' WHERE task_id = ".$_POST['new_relatedtask']."");
		NotificationMail($_POST['new_relatedtask'], "This task had a related task added to it by $pguser on ".date("l, F jS, Y", time())." at ".date("g:i a", time()).".\n");
		$result = mysql_query("SELECT * FROM tasks WHERE date_closed = 0 $order_by");
		ShowTasks($result);
	} else {
		echo "<center><b><font face='Verdana' color='#ff0000' style='font-size: 11px'>You must supply a valid related task id number.  Please go <a href='javascript:history.back()'>back</a> and correct this.</font></b></center>";
	}
} elseif (isset($_POST['new_relatedposting'])) {
	$checkTopicExists = mysql_query("SELECT 1 FROM phpbb_topics WHERE topic_id = ".$_POST['related_posting']."");
	$result = mysql_query("SELECT related_postings FROM tasks WHERE task_id = ".$_POST['new_relatedposting']."");
	$relatedpostings_array = unserialize(base64_decode(mysql_result($result, 0, "related_postings")));
	if (!is_array($relatedpostings_array)) { $relatedpostings_array = array(); }
	if (is_numeric($_POST['related_posting']) && mysql_num_rows($checkTopicExists) >= 1 && !in_array($_POST['related_posting'], $relatedpostings_array)) {
		array_push($relatedpostings_array, $_POST['related_posting']);
		$relatedpostings_array = base64_encode(serialize($relatedpostings_array));
		$result = mysql_query("UPDATE tasks SET related_postings = '$relatedpostings_array' WHERE task_id = ".$_POST['new_relatedposting']."");
		NotificationMail($_POST['new_relatedposting'], "This task had a related posting added to it by $pguser on ".date("l, F jS, Y", time())." at ".date("g:i a", time()).".\n");
		$result = mysql_query("SELECT * FROM tasks WHERE date_closed = 0 $order_by");
		ShowTasks($result);
	} else {
		echo "<center><b><font face='Verdana' color='#ff0000' style='font-size: 11px'>You must supply a valid related topic id number.  Please go <a href='javascript:history.back()'>back</a> and correct this.</font></b></center>";
	}
} elseif (isset($_POST['meToo'])) {
	if ($_POST['sameOS'] == 1) { $vote_os = $_POST['task_os']; } else { $vote_os = $_POST['metoo_os']; }
	if ($_POST['sameBrowser'] == 1) { $vote_browser = $_POST['task_browser']; } else { $vote_browser = $_POST['metoo_browser']; }
	
	$result = mysql_query("INSERT INTO tasks_votes (task_id, u_id, vote_os, vote_browser) VALUES (".$_POST['meToo'].", ".$userP['u_id'].", ".$vote_os.", ".$vote_browser.")");
	
	echo "<center><font face='Verdana' color='#000000' style='font-size: 11px'><b>Thank you for your report!  It has been recorded below.</b></font></center><br>";
	TaskDetails($_POST['meToo']);
} else {
	if (isset($_GET['orderby']) && isset($_GET['direction'])) {
		$order_by = "ORDER BY ".$_GET['orderby']." ".$_GET['direction'];
	}

	if (isset($_GET['search_text'])) {
		if ($_GET['task_type'] == 999) { $task_type = "task_type >= 0"; } else { $task_type = "task_type = ".$_GET['task_type']; }
		if ($_GET['task_severity'] == 999) { $task_severity = "task_severity >= 0"; } else { $task_severity = "task_severity = ".$_GET['task_severity']; }
		if ($_GET['task_assignee'] == 999) { $task_assignee = "task_assignee >= 0"; } else { $task_assignee = "task_assignee = ".$_GET['task_assignee']; }
		if ($_GET['task_category'] == 999) { $task_category = "task_category >= 0"; } else { $task_category = "task_category = ".$_GET['task_category']; }
		if ($_GET['task_status'] == 999) { $task_status = "task_status >= 0 AND date_closed = 0"; } elseif ($_GET['task_status'] == 998) { $task_status = "task_status >= 0"; } else { $task_status = "task_status = ".$_GET['task_status']; }
		if ($_GET['task_version'] == 999) { $task_version = "task_version >= 0"; } else { $task_version = "task_version = ".$_GET['task_version']; }
		$criteria = "$task_type AND $task_severity AND $task_assignee AND $task_category AND $task_status AND $task_version";
		if (!empty($_GET['search_text'])) { $criteria = "task_summary LIKE '%".$_GET['search_text']."%' OR task_details LIKE '%".$_GET['search_text']."%' AND $criteria"; }
	} else {
		$criteria = "date_closed = 0";
	}

	$result = mysql_query("SELECT * FROM tasks WHERE $criteria $order_by");
	ShowTasks($result);
}
echo "</td></tr></table></div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>\n";
theme("", "footer");

function dropdown_select($db_name, $db_value, $array, $sort_type) {
	echo "<select size='1' name='$db_name' ID='$db_name' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'>";
	if (empty($sort_type)) { asort($array); } else { ksort($array); }
	while (list($key, $val) = each($array)) {
		echo "<option value='$key'";
		if ($db_value == $key) { echo " SELECTED"; }
		echo ">$val</option>";
	}

	echo "</select>";
}

function TaskHeader() {
	global $tasks_array, $severity_array, $priority_array, $developers_array, $categories_array, $tasks_status_array;
	global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array;
	global $percent_complete_array, $code_url;

	if (isset($_REQUEST['search_text']) && !empty($_REQUEST['search_text'])) { $search_text = $_REQUEST['search_text']; } else { $search_text = ""; }
	if (isset($_REQUEST['search_text'])) { $t = "search_text=".$_REQUEST['search_text']."&task_type=".$_REQUEST['task_type']."&task_severity=".$_REQUEST['task_severity']."&task_priority=".$_REQUEST['task_priority']."&task_assignee=".$_REQUEST['task_assignee']."&task_category=".$_REQUEST['task_category']."&task_status=".$_REQUEST['task_status']."&task_version=".$_REQUEST['task_version']."&"; } else { $t = ""; }
	if (isset($_GET['orderby'])) { $t .= "orderby=".$_GET['orderby']."&direction=".$_GET['direction']."&"; }


	echo "<form action='tasks.php' method='post'><input type='hidden' name='search_tid'>";
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
	echo "<tr><td width='50%'>&nbsp;</td>\n";
	echo "<td width='50%' align='right'><b><font face='Verdana' size='1'>Show Task #</font></b>&nbsp;\n";
	echo "<input type='text' name='tid' size='12' style='font-family: Verdana; font-size: 10; border: 1px solid #000000; padding: 0; background-color: #EEF7FF'>&nbsp;\n";
	echo "<input type='submit' value='Go!' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>\n";
	echo "</td></tr></table></form><br>\n";

	echo "<form action='tasks.php' method='post'><input type='hidden' name='search_task'>";
	echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
	echo "<tr><td width='10%' align='left' valign='top'><b><font face='Verdana' size='-1'>Search:</font></b></td>\n";
	echo "<td width='70%' align='left' valign='top'><input type='text' value='$search_text' name='search_text' size='50' style='font-family: Verdana; font-size: 10; border: 1px solid #000000; padding: 0; background-color: #EEF7FF'>\n";
	echo "<select size='1' name='task_type' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Task Types</option>\n";
		while (list($key, $val) = each($tasks_array)) {
			echo "<option value='$key'";
			if (isset($_POST['task_type']) && $_POST['task_type'] == $key) { echo " SELECTED"; }
			echo ">$val</option>\n";
		}
	echo "</select>\n";
	echo "<select size='1' name='task_severity' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Severities</option>\n";
		while (list($key, $val) = each($severity_array)) {
			echo "<option value='$key'";
			if (isset($_POST['task_severity']) && $_POST['task_severity'] == $key) { echo " SELECTED"; }
			echo ">$val</option>\n";
		}
	echo "</select>\n";
	echo "<select size='1' name='task_priority' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Priorities</option>\n";
                while (list($key, $val) = each($priority_array)) {
                        echo "<option value='$key'";
                        if (isset($_POST['task_priority']) && $_POST['task_priority'] == $key) { echo " SELECTED"; }
                        echo ">$val</option>\n";
                }
        echo "</select><br>\n";
	echo "<select size='1' name='task_assignee' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Developers</option>\n";
	echo "<option value='0'";
	 if (isset($_REQUEST['task_assignee']) && $_REQUEST['task_assignee'] == 0) { echo " SELECTED"; }
	echo ">Unassigned</option>";
	$result = mysql_query("SELECT username, u_id FROM users WHERE sitemanager = 'yes'");
	while ($row = mysql_fetch_assoc($result)) { $task_assignees_array[$row['u_id']] = $row['username']; }
		$result = mysql_query("SELECT username FROM usersettings WHERE setting = 'task_center_mgr' AND value = 'yes'");
		while ($row = mysql_fetch_assoc($result)) {
				$u_idQuery = mysql_query("SELECT u_id FROM users WHERE username = '".$row['username']."'");
				$u_id = mysql_result($u_idQuery, 0, "u_id");
				$task_assignees_array[$u_id] = $row['username'];
		}

		natcasesort($task_assignees_array);
		while (list($key, $val) = each($task_assignees_array)) {
			echo "<option value='$key'";
			if (isset($_POST['task_assignee']) && $_POST['task_assignee'] == $key) { echo " SELECTED"; }
			echo ">$val</option>\n";
		}
	echo "</select>\n";

	echo "<select size='1' name='task_category' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Categories</option>\n";
		asort($categories_array);
		while (list($key, $val) = each($categories_array)) {
			echo "<option value='$key'";
			if (isset($_POST['task_category']) && $_POST['task_category'] == $key) { echo " SELECTED"; }
			echo ">$val</option>\n";
		}
	echo "</select>\n";
	echo "<select size='1' name='task_status' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='998'>All Tasks</option><option value='999'";
	 if (isset($_REQUEST['task_status']) && $_REQUEST['task_status'] == 999) { echo " SELECTED"; }
	echo ">All Open Tasks</option>\n";
		asort($tasks_status_array);
		while (list($key, $val) = each($tasks_status_array)) {
			echo "<option value='$key'";
			if (isset($_POST['task_status']) && $_POST['task_status'] == $key) { echo " SELECTED"; }
			echo ">$val</option>\n";
		}
	echo "</select>\n";
	echo "<select size='1' name='task_version' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Versions</option>\n";
		while (list($key, $val) = each($versions_array)) {
			echo "<option value='$key'";
			if (isset($_POST['task_version']) && $_POST['task_version'] == $key) { echo " SELECTED"; }
			echo ">$val</option>\n";
		}
	echo "</select>\n";
	echo "<input type='submit' value='Search' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'></td>\n";
	echo "<td width='30%' align='right' valign='top'><font face='Verdana' size='1' color='#03008F'><a href='$code_url/tasks.php'>Home</a> | <a href='$code_url/tasks.php?f=newtask'>New Task</a></font></td></tr>\n";
	echo "</table></form><br>\n";
}

function OrderBy($orderby_var) {
	if (isset($_GET['orderby']) && $_GET['orderby'] == $orderby_var) {
		if ($_GET['direction'] == "asc") { $direction = "desc"; } else { $direction = "asc"; }
	} else {
		$direction = "desc";
	}

	$p = "orderby=$orderby_var&direction=$direction";
	return $p;
}

function ShowTasks($sql_result) {
	global $code_url, $tasks_array, $severity_array, $developers_array, $categories_array, $tasks_status_array;
	global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;

	if (isset($_REQUEST['search_text'])) { $t = "search_text=".$_REQUEST['search_text']."&task_type=".$_REQUEST['task_type']."&task_severity=".$_REQUEST['task_severity']."&task_priority=".$_REQUEST['task_priority']."&task_assignee=".$_REQUEST['task_assignee']."&task_category=".$_REQUEST['task_category']."&task_status=".$_REQUEST['task_status']."&task_version=".$_REQUEST['task_version']."&"; } else { $t = ""; }

	echo "<table cellpadding='5' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC'><tr>\n";
	echo "<td><center><b><font face='Verdana' color='#03008f' style='font-size: 11px'><a href='tasks.php?$t".OrderBy("task_id")."'>ID</a></font></b></center></td>\n";
	echo "<td><b><font face='Verdana' color='#03008f' style='font-size: 11px'><a href='tasks.php?$t".OrderBy("task_type")."'>Task Type</a></font></b></td>\n";
	echo "<td><b><font face='Verdana' color='#03008f' style='font-size: 11px'><a href='tasks.php?$t".OrderBy("task_severity")."'>Severity</a></font></b></td>\n";
	echo "<td width='50%'><b><font face='Verdana' color='#03008f' style='font-size: 11px'><a href='tasks.php?$t".OrderBy("task_summary")."'>Summary</a></font></b></td>\n";
	echo "<td><center><b><font face='Verdana' color='#03008f' style='font-size: 11px'><a href='tasks.php?$t".OrderBy("date_edited")."'>Date Edited</a></font></b></center></td>\n";
	echo "<td><b><font face='Verdana' color='#03008f' style='font-size: 11px'><a href='tasks.php?$t".OrderBy("task_status")."'>Status</a></font></b></td>\n";
	echo "<td><b><font face='Verdana' color='#03008f' style='font-size: 11px'><a href='tasks.php?$t".OrderBy("percent_complete")."'>Progress</a></font></b></td></tr>\n";

	if (@mysql_num_rows($sql_result) >= 1) {
		while ($row = mysql_fetch_assoc($sql_result)) {
			echo "<tr bgcolor='#ffffff'>";
			echo "<td><center><font face='Verdana' color='#000000' style='font-size: 11px'><a href='tasks.php?f=detail&tid=".$row['task_id']."'>".$row['task_id']."</a></font></center></td>\n";
			echo "<td><font face='Verdana' color='#000000' style='font-size: 11px'>".$tasks_array[$row['task_type']]."</font></td>\n";
			echo "<td><font face='Verdana' color='#000000' style='font-size: 11px'>".$severity_array[$row['task_severity']]."</font></td>\n";
			echo "<td width='50%'><font face='Verdana' color='#000000' style='font-size: 11px'><a href='tasks.php?f=detail&tid=".$row['task_id']."'>".stripslashes($row['task_summary'])."</a></font></td>\n";
			echo "<td><center><font face='Verdana' color='#000000' style='font-size: 11px'>".date("d-M-Y", $row['date_edited'])."</font></center></td>\n";
			echo "<td><font face='Verdana' color='#000000' style='font-size: 11px'>".$tasks_status_array[$row['task_status']]."</font></td>\n";
			echo "<td><font face='Verdana' color='#000000' style='font-size: 11px'><img src='$code_url/graphics/task_percentages/small_".$row['percent_complete'].".png' width='50' height='8' alt='".$row['percent_complete']."% Complete'></font></td></tr>\n";
		}
	} else {
		echo "<tr bgcolor='#ffffff'><td colspan='7'><center><font face='Verdana' color='#000000' style='font-size: 11px'>No tasks found!</font></center></td></tr>";
	}

	echo "</table><br>\n";
}

function TaskForm($tid) {
	global $userP, $tasks_array, $severity_array, $developers_array, $categories_array, $tasks_status_array;
	global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;
	global $priority_array;

	if (!empty($tid)) {
		$result = mysql_query("SELECT * FROM tasks WHERE task_id = $tid");
	}

	if (empty($tid)) { $task_version = 1; } else { $task_version = mysql_result($result, 0, "task_version"); }
	if (empty($tid)) { $task_severity = 4; } else { $task_severity = mysql_result($result, 0, "task_severity"); }
	if (empty($tid)) { $task_priority = 3; } else { $task_priority = mysql_result($result, 0, "task_priority"); }
	if (empty($tid)) { $task_type = 1; } else { $task_type = mysql_result($result, 0, "task_type"); }
	if (empty($tid)) { $task_category = 1; } else { $task_category = mysql_result($result, 0, "task_category"); }
	if (empty($tid)) { $task_status = 1; } else { $task_status = mysql_result($result, 0, "task_status"); }
	if (empty($tid)) { $task_os = 0; } else { $task_os = mysql_result($result, 0, "task_os"); }
	if (empty($tid)) { $task_browser = 0; } else { $task_browser = mysql_result($result, 0, "task_browser"); }
	if (empty($tid)) { $task_assignee = 0; } else { $task_assignee = mysql_result($result, 0, "task_assignee"); }
	if (empty($tid)) { $task_summary = ""; } else { $task_summary = stripslashes(mysql_result($result, 0, "task_summary")); }
	if (empty($tid)) { $task_details = ""; } else { $task_details = stripslashes(mysql_result($result, 0, "task_details")); }
	if (empty($tid)) { $percent_complete = 0; } else { $percent_complete = mysql_result($result, 0, "percent_complete"); }
	if (empty($tid)) { $opened_by = ""; } else { $opened_by = mysql_result($result, 0, "opened_by"); }

	echo "<form action='tasks.php' method='post'><input type='hidden' name='newtask'>\n";
	if (!empty($tid)) { echo "<input type='hidden' name='task_id' value='$tid'>"; }
	echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
	echo "<tr><td><b><font face='Verdana' color='#000000' style='font-size: 11px'>Summary&nbsp;</b>&nbsp;&nbsp;<input type='text' name='task_summary' value='$task_summary' size='60' maxlength='80' style='font-family: Verdana; font-size: 10; border: 1px solid #000000; padding: 0; background-color: #EEF7FF'></td></tr>\n";
	echo "<tr><td width='50%' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0'>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Task Type</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_type', $task_type, $tasks_array, "");  echo "<font face='Verdana' color='#000000' style='font-size: 11px'></td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Category</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_category', $task_category, $categories_array, "");  echo "</td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Status</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		if (user_is_a_sitemanager() || user_is_taskcenter_mgr()) { dropdown_select('task_status', $task_status, $tasks_status_array, ""); } else { $tasks_status_array = array(1 => "New"); dropdown_select('task_status', $task_status, $tasks_status_array, ""); }  echo "</td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Assigned To</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		$result = mysql_query("SELECT username, u_id FROM users WHERE sitemanager = 'yes'");
		while ($row = mysql_fetch_assoc($result)) { $task_assignees_array[$row['u_id']] = $row['username']; }
		$result = mysql_query("SELECT username FROM usersettings WHERE setting = 'task_center_mgr' AND value = 'yes'");
		while ($row = mysql_fetch_assoc($result)) {
			$u_idQuery = mysql_query("SELECT u_id FROM users WHERE username = '".$row['username']."'");
			$u_id = mysql_result($u_idQuery, 0, "u_id");
			$task_assignees_array[$u_id] = $row['username'];
		}
			natcasesort($task_assignees_array);
			echo "<select size='1' name='task_assignee' ID='task_assignee' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'>";
			echo "<option value='0'";
			 if ($task_assignees = 0) { echo " SELECTED"; }
			echo ">Unassigned</option>";
			while (list($key, $val) = each($task_assignees_array)) {
			echo "<option value='$key'";
			if ($task_assignee == $key) { echo " SELECTED"; }
			echo ">$val</option>";
			}

		echo "</select></td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Operating System</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_os', $task_os, $os_array, "");  echo "</td></tr>\n";
	echo "</table></td><td width='50%' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0'>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Browser</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_browser', $task_browser, $browser_array, "");  echo "</td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Severity</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_severity', $task_severity, $severity_array, 1);  echo "</td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Priority</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_priority', $task_priority, $priority_array, 1);  echo "</td></tr>\n";
		echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Reported Version</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_version', $task_version, $versions_array, "");  echo "</td></tr>\n";
	if ((user_is_a_sitemanager() || user_is_taskcenter_mgr())&& !empty($tid)) {
		echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Percent Complete</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('percent_complete', $percent_complete, $percent_complete_array, 1);  echo "</td></tr>\n";
	} elseif ($opened_by == $userP['u_id'] && !user_is_a_sitemanager() && !user_is_taskcenter_mgr()) {
		echo "<input type='hidden' name='percent_complete' value='$percent_complete'>";
	}
	echo "</table></td></tr><tr><td align='left' valign='top'>\n";
	echo "<table border='0' cellspacing='0' cellpadding='0' width='100%'><tr><td width='5%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Details</font></b>&nbsp;&nbsp;</td>\n";
	echo "<td align='left' width='95%'><textarea name='task_details' cols='60' rows='5'>".$task_details."</textarea></td></tr></table>\n";
	echo "</td></tr><tr><td colspan='2'><center>\n";
	echo "<input type='submit' value='";
	if (empty($tid)) { echo "Add Task"; } else { echo "Submit Edit"; }
	echo "' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>\n";
	echo "</center></td></tr></table><br>\n";
}

function TaskDetails($tid) {
	global $userP, $code_url, $tasks_array, $severity_array, $developers_array, $categories_array, $tasks_status_array;
	global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;
	global $priority_array, $pguser;

	$result = mysql_query("SELECT * FROM tasks WHERE task_id = $tid LIMIT 1");

	if (mysql_num_rows($result) >= 1) {
		while ($row = mysql_fetch_assoc($result)) {
			$result = mysql_query("SELECT * FROM usersettings WHERE setting = 'taskctr_notice' && value = $tid && username = '$pguser'");
			if (mysql_num_rows($result) >= 1) { $already_notified = 1; } else { $already_notified = 0; }

			$result = mysql_query("SELECT username FROM users WHERE u_id = ".$row['opened_by']."");
			$opened_by = mysql_result($result, 0, "username");

			$result = mysql_query("SELECT username FROM users WHERE u_id = ".$row['edited_by']."");
			$edited_by = mysql_result($result, 0, "username");

			if (empty($row['task_assignee'])) {
				$task_assignee_username = "Unassigned";
			} else {
				$result = mysql_query("SELECT username FROM users WHERE u_id = ".$row['task_assignee']."");
				$task_assignee_username = mysql_result($result, 0, "username");
			}

			echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
			echo "<tr bgcolor='#ecdbb7'><td width='90%' align='left' valign='center'><font face='Verdana' color='#000000' style='font-size: 11px'>Task #$tid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($row['task_summary'])."</font></td><td width='10%' align='right' valign='center'><form action='tasks.php' method='post'>\n";
			if ((user_is_a_sitemanager() || user_is_taskcenter_mgr() || $row['opened_by'] == $userP['u_id']) && empty($row['closed_reason'])) {
				echo "<input type='hidden' name='edit_task' value='".$row['task_id']."'><input type='submit' value='Edit Task' style='font-family: Verdana; font-size: 11px; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'></td></tr></form></table>\n";
			} elseif (!empty($row['closed_reason'])) {
				echo "<input type='hidden' name='reopen_task' value='".$row['task_id']."'><input type='submit' value='Re-Open Task' style='font-family: Verdana; font-size: 11px; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'></td></tr></form></table>\n";
			} else {
				echo "&nbsp;</td></tr></form></table>";
			}
			echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
			echo "<tr><td width='50%'><font face='Verdana' color='#000000' style='font-size: 9px'>Opened by $opened_by - ".date("d-M-Y", $row['date_opened'])."<br>Last edited by $edited_by - ".date("d-M-Y", $row['date_edited'])."</td><td width='50%' align='right' valign='top'><font face='Verdana' color='#000000' style='font-size: 11px'>";
			if (empty($already_notified)) { echo "<a href='tasks.php?f=notifyme&tid=$tid'>Signup for task notifications</a>"; } else { echo "<a href='tasks.php?f=unnotifyme&tid=$tid'>Remove me from task notifications</a>"; }
			echo "</font></tr>\n";
			echo "<tr><td width='40%' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0'>\n";
			EchoTaskProperty( "Task Type",        $tasks_array[$row['task_type']] );
			EchoTaskProperty( "Category",         $categories_array[$row['task_category']] );
			EchoTaskProperty( "Status",           $tasks_status_array[$row['task_status']] );
			EchoTaskProperty( "Assigned To",      $task_assignee_username );
			EchoTaskProperty( "Operating System", $os_array[$row['task_os']] );
			echo "</table></td><td width='50%' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0'>\n";
			EchoTaskProperty( "Browser",          $browser_array[$row['task_browser']] );
			EchoTaskProperty( "Severity",         $severity_array[$row['task_severity']] );
			EchoTaskProperty( "Priority",         $priority_array[$row['task_priority']] );
			EchoTaskProperty( "Reported Version", $versions_array[$row['task_version']] );
			EchoTaskProperty( "Percent Complete", "<img src='$code_url/graphics/task_percentages/large_".$row['percent_complete'].".png' width='150' height='10' border='0' alt='".$row['percent_complete']."% Complete'>" );
			echo "</table></td></tr>\n";

			$voteInfo = mysql_query("SELECT id FROM tasks_votes WHERE task_id = ".$tid."");
			$osInfo = mysql_query("SELECT DISTINCT vote_os FROM tasks_votes WHERE task_id = ".$tid."");
			$browserInfo = mysql_query("SELECT DISTINCT vote_browser FROM tasks_votes WHERE task_id = ".$tid."");
			if (mysql_num_rows($voteInfo) > 0) {
				$reportedOS = "";
				$reportedBrowser = "";
				echo "<tr><td colspan='2' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0' width='100%'>";
				echo "<tr><td width='25%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Votes&nbsp;&nbsp;</font></b></td>\n";
				echo "<td align='left' width='75%'><font face='Verdana' color='#000000' style='font-size: 11px'>".mysql_num_rows($voteInfo)."</font></td></tr>";
				echo "<tr><td width='25%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Reported Operating Systems&nbsp;&nbsp;</font></b></td>\n";
				echo "<td align='left' width='75%'><font face='Verdana' color='#000000' style='font-size: 11px'>";
				while ($rowOS = mysql_fetch_assoc($osInfo)) { $reportedOS .= $os_array[$rowOS['vote_os']].", "; }
				echo substr($reportedOS, 0, -2)."</font></td></tr>";
				echo "<tr><td width='25%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Reported Browsers&nbsp;&nbsp;</font></b></td>\n";
				echo "<td align='left' width='75%'><font face='Verdana' color='#000000' style='font-size: 11px'>";
				while ($rowBrowser = mysql_fetch_assoc($browserInfo)) { $reportedBrowser .= $browser_array[$rowBrowser['vote_browser']].", "; }
				echo substr($reportedBrowser, 0, -2)."</font></td></tr></table></td></tr>";
			}
			
			echo "<tr><td align='left' valign='top'><br><table border='0' cellspacing='2' cellpadding='0' width='100%'><tr><td width='5%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Details&nbsp;&nbsp;</font></b></td>\n";
			echo "<td align='left' width='95%' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".nl2br(stripslashes($row['task_details']))."</font></td></tr></table></td></tr>\n";
			if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && empty($row['closed_reason'])) {
				echo "<form action='tasks.php' method='post'><input type='hidden' name='close_task'><input type='hidden' name='task_id' value='".$row['task_id']."'>\n";
				echo "<tr><td align='left'><br><table border='0' cellspacing='2' cellpadding='0' width='100%'><tr><td width='20%' align='left' valign='bottom'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Close Task&nbsp;&nbsp;</font></b></td><td align='left' valign='bottom' width='80%'>";
				dropdown_select('task_close_reason', "", $tasks_close_array, "");
				echo "&nbsp;<input type='submit' value='Close Task' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>\n";
				echo "</td></tr></form></table>\n";
			} elseif (!empty($row['closed_reason'])) {
				$result = mysql_query("SELECT username FROM users WHERE u_id = ".$row['closed_by']."");
				$closed_by = mysql_result($result, 0, "username");
				echo "<tr><td align='left'><br><font face='Verdana' color='#000000' style='font-size: 9px'>Closed by: $closed_by<br>Date Closed: ".date("d-M-Y", $row['date_closed'])."<br>Reason: ".$tasks_close_array[$row['closed_reason']]."";
			}
			echo "</td><td align='right'><br>";
			
			$meTooCheck = mysql_query("SELECT id FROM tasks_votes WHERE task_id = ".$tid." && u_id = ".$userP['u_id']."");
			if (mysql_num_rows($meTooCheck) > 0) { echo "&nbsp;"; } else { echo "<input type='button' value='Me Too!' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5' onClick=\"showSpan('MeTooMain');\">"; }
			
			echo "</td></tr></table><br>\n";
			
			MeToo($tid, $row['task_os'], $row['task_browser']);
			TaskComments($tid);
			RelatedTasks($tid);
			RelatedPostings($tid);
		}
	} else {
		echo "<tr bgcolor='#ffffff'><td colspan='7'><center><font face='Verdana' color='#000000' style='font-size: 11px'>Task #$tid was not found!</font></center></td></tr>";
	}
}

function MeToo($tid, $os, $browser) {
	global $browser_array, $os_array, $userP;

	echo "<span id='MeTooMain' style='display: none;'>";
	echo "<form action='tasks.php' method='post'><input type='hidden' name='meToo' value='$tid'><input type='hidden' name='task_os' value='$os'><input type='hidden' name='task_browser' value='$browser'>";
	echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'><tr><td><font face='Verdana' color='#000000' style='font-size: 11px'>\n"; 
  echo "<fieldset style='width: 35em; border: #26a solid 1px;'><legend><b>Are you using the same operating system?</b></legend>";
  echo "&nbsp;<input onClick=\"hideSpan('OS');\" type='radio' name='sameOS' value='1' CHECKED>yes<input onClick=\"showSpan('OS');\" type='radio' name='sameOS' value='0'>no";
  echo "<span id='OS' style='display: none;'><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Operating System</b>&nbsp;";
  dropdown_select('metoo_os', "1", $os_array, ""); echo "</select></span></fieldset>\n";
  
  echo "<br><fieldset style='width: 35em; border: #26a solid 1px;'><legend><b>Are you using the same browser?</b></legend>";
  echo "&nbsp;<input onClick=\"hideSpan('Browser');\" type='radio' name='sameBrowser' value='1' CHECKED>yes<input onClick=\"showSpan('Browser');\" type='radio' name='sameBrowser' value='0'>no";
  echo "<span id='Browser' style='display: none;'><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Browser</b>&nbsp;";
  dropdown_select('metoo_browser', "1", $browser_array, "");  echo "</span></fieldset>\n";
  
  echo "<center><input type='submit' value='Send Report' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>&nbsp;<input type='reset' value='Reset' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5' onClick=\"hideSpan('MeTooMain');\"></center>";
  echo "</td></tr></table></font></form></span>";
  
}

function EchoTaskProperty($name, $value) {
	echo "<tr>";

	echo "<td width='40%' align='left'>";
	echo "<b>";
	echo "<font face='Verdana' color='#000000' style='font-size: 11px'>";
	echo $name;
	echo "&nbsp;&nbsp;";
	echo "</font>";
	echo "</b>";
	echo "</td>";

	echo "<td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'>";
	echo "<font face='Verdana' color='#000000' style='font-size: 11px'>";
	echo $value;
	echo "</font>";
	echo "</td>";

	echo "</tr>";
	echo "\n";
}

function TaskComments($tid) {
	$result = mysql_query("SELECT * FROM tasks_comments WHERE task_id = $tid");
	if (mysql_num_rows($result) >= 1) {
		echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'><tr><td width='100%' align='left'>\n";
		while ($row = mysql_fetch_assoc($result)) {
			$usernameQuery = mysql_query("SELECT username FROM users WHERE u_id = ".$row['u_id']."");
			$comment_username = mysql_result($usernameQuery, 0, "username");
			echo "<b><font face='Verdana' color='#000000' style='font-size: 11px'>Comment by $comment_username - ".date("l, d M Y, g:ia", $row['comment_date'])."</font></b><br>";
			echo "<br><font face='Verdana' color='#000000' style='font-size: 11px'>".nl2br(stripslashes($row['comment']))."</font><br><br><hr width='80%' align='center'>";
		}
		echo  "</td></tr></table>";
	}
	echo "<form action='tasks.php' method='post'><input type='hidden' name='new_comment' value='$tid'>";
	echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'><tr><td>\n";
	echo "<tr><td width='10%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Add comment</font></b></td>";
	echo "<td width='90%' align='left' valign='top'><textarea name='task_comment' cols='60' rows='5'></textarea></td></tr>";
	echo "<tr><td width='100%' align='center' valign='top' colspan='2'><input type='submit' value='Add Comment' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>\n";
	echo "</td></tr></table></form>";
}

function NotificationMail($tid, $message) {
	global $code_url, $auto_email_addr, $pguser;

	$result = mysql_query("SELECT username FROM usersettings WHERE setting = 'taskctr_notice' && value = $tid");
	while ($row = mysql_fetch_assoc($result)) {
		if ($row['username'] != $pguser) {
			$temp = mysql_query("SELECT email FROM users WHERE username = '".$row['username']."'");
			$email = mysql_result($temp, 0, "email");
			maybe_mail($email, "DP Task Center: Task #$tid has been updated",
			  "You had requested to be let known when task #$tid was updated.  "
			   ."$message"
			   ."\n"
			   ."You can see task #$tid by visiting $code_url/tasks.php?f=detail&tid=$tid."
			   ."\n"
			   ."\n"
			   ."--"
			   ."\n"
			   ."Distributed Proofreaders"
			   ."\n"
			   ."$code_url"
			   ."\n"
			   ."\n"
			   ."This is an automated message that you had requested,"
			   ." please do not respond directly to this e-mail.",
			  "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n"
			  );
		}
	}
}

function RelatedTasks($tid) {
	global $code_url;

	$result = mysql_query("SELECT related_tasks FROM tasks WHERE task_id = $tid");
	$related_tasks = mysql_result($result, 0, "related_tasks");

	echo "<form action='tasks.php' method='post'><input type='hidden' name='new_relatedtask' value='$tid'>";
	echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
	echo "<tr><td width='100%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Related Tasks&nbsp;&nbsp;</font></b>";
	echo "<input type='text' name='related_task' size='30' style='font-family: Verdana; font-size: 10; border: 1px solid #000000; padding: 0; background-color: #EEF7FF'>&nbsp;&nbsp;";
	echo "<input type='submit' value='Add' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>\n";

	if (!empty($related_tasks)) {
		$related_tasks = unserialize(base64_decode($related_tasks));
		asort($related_tasks);
		while (list($key, $val) = each($related_tasks)) {
			$result = mysql_query("SELECT task_summary FROM tasks WHERE task_id = $val") or die(mysql_error());
			$task_summary = mysql_result($result, 0, "task_summary");
			echo "<br><font face='Verdana' color='#000000' style='font-size: 11px'><a href='$code_url/tasks.php?f=detail&tid=$val'>Task #$val</a> - $task_summary</font>\n"; }
	}

	echo "</td></tr></table></form>";
}

function RelatedPostings($tid) {
	global $forums_url;

	$result = mysql_query("SELECT related_postings FROM tasks WHERE task_id = $tid");
	$related_postings = mysql_result($result, 0, "related_postings");

	echo "<form action='tasks.php' method='post'><input type='hidden' name='new_relatedposting' value='$tid'>";
	echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
	echo "<tr><td width='100%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Related Topic ID&nbsp;&nbsp;</font></b>";
	echo "<input type='text' name='related_posting' size='30' style='font-family: Verdana; font-size: 10; border: 1px solid #000000; padding: 0; background-color: #EEF7FF'>&nbsp;&nbsp;";
	echo "<input type='submit' value='Add' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>\n";

	if (!empty($related_postings)) {
		$related_postings = unserialize(base64_decode($related_postings));
		asort($related_postings);
		while (list($key, $val) = each($related_postings)) {
			$result = mysql_query("SELECT phpbb_topics.topic_title AS Title, phpbb_topics.topic_replies AS Replies, 
														phpbb_forums.forum_name AS Forum, phpbb_forums.forum_id As ForumID, phpbb_users.username AS Username FROM phpbb_topics 
														INNER JOIN phpbb_forums ON phpbb_topics.forum_id = phpbb_forums.forum_id INNER JOIN 
														phpbb_users ON phpbb_topics.topic_poster = phpbb_users.user_id WHERE phpbb_topics.topic_id = ".$val."") OR die(mysql_error());
			while ($row = mysql_fetch_assoc($result)) { echo "<br><font face='Verdana' color='#000000' style='font-size: 11px'><a href='$forums_url/viewforum.php?f=".$row['ForumID']."'>".$row['Forum']."</a>&nbsp;&raquo;&nbsp;<a href='$forums_url/viewtopic.php?t=$val'>".$row['Title']."</a> (Posted by: ".$row['Username']." - ".$row['Replies']." replies)</font>\n"; }
		}
	echo "</td></tr></table></form>";
	}
}
?>
