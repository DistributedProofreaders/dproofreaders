<?
$relPath='pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
$no_stats=1;
theme('Task Center','header');

$tasks_array = array(1 => "Bug Report", 2 => "Feature Request", 3 => "Support Request", 4 => "Site Administrator Request");
$severity_array = array(1 => "1 - Critical", 2 => "2 - High", 3 => "3 - Medium", 4 => "4 - Low", 5 => "5 - Very Low");
$developers_array = array(); //Somehow find a way to fill with sql result of where siteadmin = 1
$categories_array = array(1 => "None", 2 => "Documentation", 3 => "Entrance", 4 => "Log in/out", 5 => "New Member", 6 => "Page Proofing", 7 => "Personal Page", 8 => "Post-Processing", 9 => "Preferences", 10 => "Pre-Processing", 11 => "Project Comments", 12 => "Project Listing Interface", 13 => "Project Manager", 14 => "Site wide", 15 => "Statistics", 16 => "Translation", 99 => "Other");
$tasks_status_array = array(1 => "New", 2 => "Accepted", 3 => "Duplicate", 4 => "Fixed", 5 => "Invalid", 6 => "Later", 7 => "None", 8 => "Out of Date", 9 => "Postponed", 10 => "Rejected", 11 => "Remind", 12 => "Won't Fix", 13 => "Works for Me", 14 => "Closed", 15 => "Reopened");
$search_results_array = array("20", "40", "60", "80", "100");
$os_array = array(0 => "All", 1 => "Windows 3.1", 2 => "Windows 95", 3 => "Windows 98", 4 => "Windows ME", 5 => "Windows 2000", 6 => "Windows NT", 7 => "Windows XP", 8 => "Mac System 7", 9 => "Mac System 7.5", 10 => "Mac System 7.6.1", 11 => "Mac System 8.0", 12 => "Mac System 8.5", 13 => "Mac System 8.6", 14 => "Mac System 9.x", 15 => "MacOS X", 16 => "Linux", 17 => "BSDI", 18 => "FreeBSD", 19 => "NetBSD", 20 => "OpenBSD", 21 => "BeOS", 22 => "HP-UX", 23 => "IRIX", 24 => "Neutrino", 25 => "OpenVMS", 26 => "OS/2", 27 => "OSF/1", 28 => "Solaris", 29 => "SunOS", 99 => "Other");
$browser_array = array(0 => "All", 1 => "Internet Explorer");
$versions_array = array(1 => "pgdp.net (Live)", 2 => "texts01 (Beta)", 3 => "CVS");
$tasks_close_array = array(1 => "Not a Bug", 2 => "Won't Fix", 3 => "Won't Implement", 4 => "Works for Me", 5 => "Duplicate", 6 => "Deferred", 7 => "Fixed", 8 => "Implemented");
$percent_complete_array = array(0 => "0%", 10 => "10%", 20 => "20%", 30 => "30%", 40 => "40%", 50 => "50%", 60 => "60%", 70 => "70%", 80 => "80%", 90 => "90%", 100 => "100%");

echo "<br><div align='center'><table border='0' cellpadding='0' cellspacing='0' width='98%'><tr><td>\n";
TaskHeader();

if (isset($_GET['f']) && $_GET['f'] == "newtask") {
	TaskForm("");
} elseif (isset($_POST['edit_task'])) {
	TaskForm($_POST['edit_task']);
} elseif (isset($_POST['reopen_task'])) {
	$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
	$u_id = mysql_result($result, 0, "u_id");
	$result = mysql_query("UPDATE tasks SET task_status = 15, edited_by = $u_id, date_edited = ".time().", date_closed = 0, closed_by = 0, closed_reason = 0 WHERE task_id = ".$_POST['reopen_task']."");
	$result = mysql_query("SELECT * FROM tasks WHERE task_id = ".$_POST['reopen_task']."");
	ShowTasks($result);
} elseif (isset($_POST['newtask'])) {
	if (!isset($_POST['task_id'])) {
		$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
		$u_id = mysql_result($result, 0, "u_id");
		$result = mysql_query("INSERT INTO tasks (task_id, task_summary, task_type, task_category, task_status, task_assignee, task_severity, task_os, task_browser, task_version, task_details, date_opened, opened_by, date_closed, closed_by, date_edited, edited_by, percent_complete) VALUES ('', '".addslashes($_POST['task_summary'])."', ".$_POST['task_type'].", ".$_POST['task_category'].", ".$_POST['task_status'].", ".$_POST['task_assignee'].", ".$_POST['task_severity'].", ".$_POST['task_os'].", ".$_POST['task_browser'].", ".$_POST['task_version'].", '".addslashes($_POST['task_details'])."', ".time().", $u_id, '', '', ".time().", $u_id, 0)") OR die(mysql_error());
		$result = mysql_query("SELECT * FROM tasks WHERE task_id = ".mysql_insert_id()."");
		ShowTasks($result);
	} else {
		$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
		$u_id = mysql_result($result, 0, "u_id");
		$result = mysql_query("UPDATE tasks SET task_summary = '".addslashes($_POST['task_summary'])."', task_type = ".$_POST['task_type'].", task_category = ".$_POST['task_category'].", task_status = ".$_POST['task_status'].", task_assignee = ".$_POST['task_assignee'].", task_severity = ".$_POST['task_severity'].", task_os = ".$_POST['task_os'].", task_browser = ".$_POST['task_browser'].", task_version = ".$_POST['task_version'].", task_details = '".addslashes($_POST['task_details'])."', date_edited = ".time().", edited_by = $u_id, percent_complete = ".$_POST['percent_complete']." WHERE task_id = ".$_POST['task_id']."") or die(mysql_error());
		$result = mysql_query("SELECT * FROM tasks WHERE task_id = ".$_POST['task_id']."");
		ShowTasks($result);
	}
} elseif (isset($_POST['search_task'])) {
	if ($_POST['task_type'] == 999) { $task_type = "task_type >= 0"; } else { $task_type = "task_type = ".$_POST['task_type']; }
	if ($_POST['task_severity'] == 999) { $task_severity = "task_severity >= 0"; } else { $task_severity = "task_severity = ".$_POST['task_severity']; }
	if ($_POST['task_assignee'] == 999) { $task_assignee = "task_assignee >= 0"; } else { $task_assignee = "task_assignee = ".$_POST['task_assignee']; }
	if ($_POST['task_category'] == 999) { $task_category = "task_category >= 0"; } else { $task_category = "task_category = ".$_POST['task_category']; }
	if ($_POST['task_status'] == 999) { $task_status = "task_status >= 0 AND date_closed = 0"; } else { $task_status = "task_status = ".$_POST['task_status']; }
	if ($_POST['task_version'] == 999) { $task_version = "task_version >= 0"; } else { $task_version = "task_version = ".$_POST['task_version']; }
	$sql_query = "SELECT * FROM tasks WHERE (task_summary LIKE '%".$_POST['search_text']."%' OR task_details LIKE '%".$_POST['search_text']."%') AND $task_type AND $task_severity AND $task_assignee AND $task_category AND $task_status AND $task_version ORDER BY task_severity ASC, date_opened ASC, task_type ASC";

	$result = mysql_query($sql_query) or die(mysql_error());
	ShowTasks($result);
} elseif ((isset($_GET['f']) && $_GET['f'] == "detail") || isset($_POST['search_tid'])) {
	if (is_numeric($_REQUEST['tid'])) {
		TaskDetails($_REQUEST['tid']);
	} else {
		ShowTasks("");
	}
} elseif (isset($_POST['close_task'])) {
	$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
	$u_id = mysql_result($result, 0, "u_id");
	$result = mysql_query("UPDATE tasks SET task_status = 14, date_closed = ".time().", closed_by = $u_id, closed_reason = ".$_POST['task_close_reason'].", date_edited = ".time().", edited_by = $u_id WHERE task_id = ".$_POST['task_id']."");
	$result = mysql_query("SELECT * FROM tasks WHERE date_closed = 0 ORDER BY task_severity ASC, date_opened ASC, task_type ASC");
	ShowTasks($result);
} elseif (isset($_POST['new_comment'])) {
	$result = mysql_query("SELECT u_id FROM users WHERE username = '$pguser'");
	$u_id = mysql_result($result, 0, "u_id");
	$result = mysql_query("INSERT INTO tasks_comments (task_id, u_id, comment_date, comment) VALUES (".$_POST['new_comment'].", $u_id, ".time().", '".htmlentities($_POST['task_comment'], ENT_QUOTES)."')");
	TaskDetails($_POST['new_comment']);
} else {
	$result = mysql_query("SELECT * FROM tasks WHERE date_closed = 0 ORDER BY task_severity ASC, date_opened ASC, task_type ASC");
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
	global $tasks_array, $severity_array, $developers_array, $categories_array, $tasks_status_array;
	global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;

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
	echo "<td width='80%' align='left' valign='top'><input type='text' name='search_text' size='50' style='font-family: Verdana; font-size: 10; border: 1px solid #000000; padding: 0; background-color: #EEF7FF'>\n";
	echo "<select size='1' name='task_type' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Task Types</option>\n";
                                while (list($key, $val) = each($tasks_array)) { echo "<option value='$key'>$val</option>\n"; }
		echo "</select>\n";
	echo "<select size='1' name='task_severity' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Severities</option>\n";
                                while (list($key, $val) = each($severity_array)) { echo "<option value='$key'>$val</option>\n"; }
		echo "</select><br>\n";
	echo "<select size='1' name='task_assignee' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Developers</option>\n";
		echo "<option value='0'>Unassigned</option>";
		$result = mysql_query("SELECT username, u_id FROM users WHERE sitemanager = 'yes'");
		while ($row = mysql_fetch_assoc($result)) { echo "<option value='".$row['u_id']."'>".$row['username']."</option>"; }
		echo "</select>\n";
	echo "<select size='1' name='task_category' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Categories</option>\n";
                                while (list($key, $val) = each($categories_array)) { echo "<option value='$key'>$val</option>\n"; }
		echo "</select>\n";
	echo "<select size='1' name='task_status' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Open Tasks</option>\n";
                                while (list($key, $val) = each($tasks_status_array)) { echo "<option value='$key'>$val</option>\n"; }
		echo "</select>\n";
	echo "<select size='1' name='task_version' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='999'>All Versions</option>\n";
                                while (list($key, $val) = each($versions_array)) { echo "<option value='$key'>$val</option>\n"; }
		echo "</select>\n";
	echo "<input type='submit' value='Search' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'></td>\n";
	echo "<td width='20%' align='right' valign='bottom'><font face='Verdana' size='1' color='#03008F'><a href='tasks.php?f=newtask'>Add a New Task</a></font></tr>\n";
	echo "</table></form><br>\n";
}

function ShowTasks($sql_result) {
	global $code_url, $tasks_array, $severity_array, $developers_array, $categories_array, $tasks_status_array;
	global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;

	echo "<table cellpadding='5' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC'><tr>\n";
	echo "<td><center><b><font face='Verdana' color='#03008f' style='font-size: 11px'>ID</font></b></center></td>\n";
	echo "<td><b><font face='Verdana' color='#03008f' style='font-size: 11px'>Task Type</font></b></td>\n";
	echo "<td><b><font face='Verdana' color='#03008f' style='font-size: 11px'>Severity</font></b></td>\n";
	echo "<td width='50%'><b><font face='Verdana' color='#03008f' style='font-size: 11px'>Summary</font></b></td>\n";
	echo "<td><center><b><font face='Verdana' color='#03008f' style='font-size: 11px'>Date Opened</font></b></center></td>\n";
	echo "<td><b><font face='Verdana' color='#03008f' style='font-size: 11px'>Status</font></b></td>\n";
	echo "<td><b><font face='Verdana' color='#03008f' style='font-size: 11px'>Progress</font></b></td></tr>\n";

	if (mysql_num_rows($sql_result) >= 1) {
		while ($row = mysql_fetch_assoc($sql_result)) {
			echo "<tr bgcolor='#ffffff' onmouseover='javascript:style.cursor=\"hand\"' onmouseout='javascript:this.style.cursor=\"default\"' onclick='javascript:window.location=\"tasks.php?f=detail&tid=".$row['task_id']."\"'>";
			echo "<td><center><font face='Verdana' color='#000000' style='font-size: 11px'><a href='tasks.php?f=detail&tid=".$row['task_id']."'>".$row['task_id']."</a></font></center></td>\n";
			echo "<td><font face='Verdana' color='#000000' style='font-size: 11px'>".$tasks_array[$row['task_type']]."</font></td>\n";
			echo "<td><font face='Verdana' color='#000000' style='font-size: 11px'>".$severity_array[$row['task_severity']]."</font></td>\n";
			echo "<td width='50%'><font face='Verdana' color='#000000' style='font-size: 11px'>".stripslashes($row['task_summary'])."</font></td>\n";
			echo "<td><center><font face='Verdana' color='#000000' style='font-size: 11px'>".date("d-M-Y", $row['date_opened'])."</font></center></td>\n";
			echo "<td><font face='Verdana' color='#000000' style='font-size: 11px'>".$tasks_status_array[$row['task_status']]."</font></td>\n";
			echo "<td><font face='Verdana' color='#000000' style='font-size: 11px'><img src='$code_url/graphics/task_percentages/small_".$row['percent_complete'].".png' width='50' height='8' alt='".$row['percent_complete']."% Complete'></font></td></tr>\n";
		}
	} else {
		echo "<tr bgcolor='#ffffff'><td colspan='7'><center><font face='Verdana' color='#000000' style='font-size: 11px'>No tasks found!</font></center></td></tr>";
	}

	echo "</table><br>\n";
}

function TaskForm($tid) {
	global $tasks_array, $severity_array, $developers_array, $categories_array, $tasks_status_array;
	global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;

	if (!empty($tid)) {
		$result = mysql_query("SELECT * FROM tasks WHERE task_id = $tid");
	}

	if (empty($tid)) { $task_version = 1; } else { $task_version = mysql_result($result, 0, "task_version"); }
	if (empty($tid)) { $task_severity = 3; } else { $task_severity = mysql_result($result, 0, "task_severity"); }
	if (empty($tid)) { $task_type = 1; } else { $task_type = mysql_result($result, 0, "task_type"); }
	if (empty($tid)) { $task_category = 1; } else { $task_category = mysql_result($result, 0, "task_category"); }
	if (empty($tid)) { $task_status = 1; } else { $task_status = mysql_result($result, 0, "task_status"); }
	if (empty($tid)) { $task_os = 0; } else { $task_os = mysql_result($result, 0, "task_os"); }
	if (empty($tid)) { $task_browser = 0; } else { $task_browser = mysql_result($result, 0, "task_browser"); }
	if (empty($tid)) { $task_assignee = 0; } else { $task_assignee = mysql_result($result, 0, "task_assignee"); }
	if (empty($tid)) { $task_summary = ""; } else { $task_summary = stripslashes(mysql_result($result, 0, "task_summary")); }
	if (empty($tid)) { $task_details = ""; } else { $task_details = stripslashes(mysql_result($result, 0, "task_details")); }
	if (empty($tid)) { $percent_complete = 0; } else { $percent_complete = mysql_result($result, 0, "percent_complete"); }

	echo "<form action='tasks.php' method='post'><input type='hidden' name='newtask'>\n";
	if (!empty($tid)) { echo "<input type='hidden' name='task_id' value='$tid'>"; }
	echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
	echo "<tr><td><b><font face='Verdana' color='#000000' style='font-size: 11px'>Summary</b>&nbsp;&nbsp;<input type='text' name='task_summary' value='$task_summary' size='60' maxlength='80' style='font-family: Verdana; font-size: 10; border: 1px solid #000000; padding: 0; background-color: #EEF7FF'></td></tr>\n";
	echo "<tr><td width='50%' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0'>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Task Type</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_type', $task_type, $tasks_array, "");  echo "<font face='Verdana' color='#000000' style='font-size: 11px'></td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Category</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_category', $task_category, $categories_array, "");  echo "</td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Status</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		if (user_is_a_sitemanager() || user_is_taskcenter_mgr()) { dropdown_select('task_status', $task_status, $tasks_status_array, ""); } else { $tasks_status_array = array(1 => "New"); dropdown_select('task_status', $task_status, $tasks_status_array, ""); }  echo "</td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Assigned To</font></b>&nbsp;</td><td width='60%' align='left' valign='top'><select size='1' name='task_assignee' style='font-family: Verdana; font-size: 11; color: #03008F; background-color: #EEF7FF'><option value='0'>Unassigned</option>\n";
		$result = mysql_query("SELECT username, u_id FROM users WHERE sitemanager = 'yes' ORDER BY username");
		while ($row = mysql_fetch_assoc($result)) { echo "<option value='".$row['u_id']."'";
		if ($task_assignee == $row['u_id']) { echo " SELECTED"; }
		echo ">".$row['username']."</option>\n"; }
		echo "</td></tr></select>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Operating System</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_os', $task_os, $os_array, "");  echo "</td></tr>\n";
	echo "</table></td><td width='50%' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0'>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Browser</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_browser', $task_browser, $browser_array, "");  echo "</td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Severity</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_severity', $task_severity, $severity_array, "");  echo "</td></tr>\n";
	echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Reported Version</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('task_version', $task_version, $versions_array, "");  echo "</td></tr>\n";
	if ((user_is_a_sitemanager() || user_is_taskcenter_mgr())&& !empty($tid)) {
		echo "<tr><td width='40%' align='right' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Percent Complete</font></b>&nbsp;</td><td width='60%' align='left' valign='top'>\n";
		dropdown_select('percent_complete', $percent_complete, $percent_complete_array, 1);  echo "</td></tr>\n";
	}
	echo "</table></td></tr><tr><td align='left' valign='top'>\n";
	echo "<table border='0' cellspacing='0' cellpadding='0' width='100%'><tr><td width='5%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Details</font></b>&nbsp;&nbsp;</td>\n";
	echo "<td align='left' width='95%'><textarea name='task_details' cols='60' rows='5'>$task_details</textarea></td></tr></table>\n";
	echo "</td></tr><tr><td colspan='2'><center>\n";
	echo "<input type='submit' value='";
	if (empty($tid)) { echo "Add Task"; } else { echo "Edit Task"; }
	echo "' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>\n";
	echo "</center></td></tr></table><br>\n";
}

function TaskDetails($tid) {
	global $code_url, $tasks_array, $severity_array, $developers_array, $categories_array, $tasks_status_array;
	global $search_results_array, $os_array, $browser_array, $versions_array, $tasks_close_array, $percent_complete_array;

	$result = mysql_query("SELECT * FROM tasks WHERE task_id = $tid LIMIT 1");

	if (mysql_num_rows($result) >= 1) {
		while ($row = mysql_fetch_assoc($result)) {
			$result = mysql_query("SELECT username FROM users WHERE u_id = ".$row['opened_by']."");
			$opened_by = mysql_result($result, 0, "username");

			$result = mysql_query("SELECT username FROM users WHERE u_id = ".$row['edited_by']."");
			$edited_by = mysql_result($result, 0, "username");

			echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
			echo "<tr bgcolor='#ecdbb7'><td width='90%' align='left' valign='center'><font face='Verdana' color='#000000' style='font-size: 11px'>Task #$tid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".stripslashes($row['task_summary'])."</font></td><td width='10%' align='right' valign='center'><form action='tasks.php' method='post'>\n";
			if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && empty($row['closed_reason'])) {
				echo "<input type='hidden' name='edit_task' value='".$row['task_id']."'><input type='submit' value='Edit Task' style='font-family: Verdana; font-size: 11px; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'></td></tr></form></table>\n";
			} elseif (!empty($row['closed_reason'])) {
				echo "<input type='hidden' name='reopen_task' value='".$row['task_id']."'><input type='submit' value='Re-Open Task' style='font-family: Verdana; font-size: 11px; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'></td></tr></form></table>\n";
			} else {
				echo "&nbsp;</td></tr></form></table>";
			}
			echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'>\n";
			echo "<tr><td><font face='Verdana' color='#000000' style='font-size: 9px'>Opened by $opened_by - ".date("d-M-Y", $row['date_opened'])."<br>Last edited by $edited_by - ".date("d-M-Y", $row['date_edited'])."</td></tr>\n";
			echo "<tr><td width='40%' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0'>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Task Type&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".$tasks_array[$row['task_type']]."</font></td></tr>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Category&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".$categories_array[$row['task_category']]."</font></td></tr>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Status&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".$tasks_status_array[$row['task_status']]."</font></td></tr>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Assigned To&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>USFJoseph</font></td></tr>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Operating System&nbsp;&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".$os_array[$row['task_os']]."</font></td></tr>\n";
			echo "</table></td><td width='50%' align='left' valign='top'><table border='0' cellspacing='2' cellpadding='0'>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Browser&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".$browser_array[$row['task_browser']]."</font></td></tr>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Severity&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".$severity_array[$row['task_severity']]."</font></td></tr>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Reported Version&nbsp;&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".$versions_array[$row['task_version']]."</font></td></tr>\n";
			echo "<tr><td width='40%' align='left'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Percent Complete&nbsp;&nbsp;</font></b></td><td width='60%' align='left' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><img src='$code_url/graphics/task_percentages/large_".$row['percent_complete'].".png' width='150' height='10' border='0' alt='".$row['percent_complete']."% Complete'></td></tr>\n";
			echo "</table></td></tr><tr><td align='left' valign='top'><br>\n";
			echo "<table border='0' cellspacing='2' cellpadding='0' width='100%'><tr><td width='5%' align='left' valign='top'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Details&nbsp;&nbsp;</font></b></td>\n";
			echo "<td align='left' width='95%' style='BORDER-RIGHT: #cccccc 1px; BORDER-TOP: #cccccc 1px; BORDER-LEFT: #cccccc 1px; BORDER-BOTTOM: #cccccc 1px solid'><font face='Verdana' color='#000000' style='font-size: 11px'>".stripslashes($row['task_details'])."</font></td></tr></table></td></tr>\n";
			if ((user_is_a_sitemanager() || user_is_taskcenter_mgr()) && empty($row['closed_reason'])) {
				echo "<form action='tasks.php' method='post'><input type='hidden' name='close_task'><input type='hidden' name='task_id' value='".$row['task_id']."'>\n";
				echo "<tr><td align='left'><br><table border='0' cellspacing='2' cellpadding='0' width='100%'><tr><td width='20%' align='left' valign='bottom'><b><font face='Verdana' color='#000000' style='font-size: 11px'>Close Task&nbsp;&nbsp;</font></b></td><td align='left' valign='bottom' width='80%'>";
				dropdown_select('task_close_reason', "", $tasks_close_array, "");
				echo "&nbsp;<input type='submit' value='Close Task' style='font-family: Verdana; font-size: 11; color: #FFFFFF; font-weight: bold; border: 1px ridge #000000; padding: 0; background-color: #838AB5'>\n";
				echo "</td></tr></form></table>\n";
			} elseif (!empty($row['closed_reason'])) {
				//$result = mysql_query("SELECT username FROM users WHERE u_id = ".$row['closed_by']."");
				//$closed_by = mysql_result($result, 0, "username");
				//echo "<tr><td align='left'><br><font face='Verdana' color='#000000' style='font-size: 9px'>Closed by: $closed_by<br>Date Closed: ".date("d-M-Y", $row['date_closed'])."<br>Reason: ".$tasks_close_array[$row['closed_reason']]."";
			}
			echo "</td></tr></table><br>\n";
			TaskComments($tid);
		}
	} else {
		echo "<tr bgcolor='#ffffff'><td colspan='7'><center><font face='Verdana' color='#000000' style='font-size: 11px'>Task #$tid was not found!</font></center></td></tr>";
	}
}

function TaskComments($tid) {
	$result = mysql_query("SELECT * FROM tasks_comments WHERE task_id = $tid");
	if (mysql_num_rows($result) >= 1) {
		echo "<table cellpadding='2' cellspacing='0' width='100%' bgcolor='#e6eef6' style='border-collapse: collapse; border: 1px solid #CCCCCC; padding: 0'><tr><td width='100%' align='left'>\n";
		while ($row = mysql_fetch_assoc($result)) {
			$usernameQuery = mysql_query("SELECT username FROM users WHERE u_id = ".$row['u_id']."");
			$comment_username = mysql_result($usernameQuery, 0, "username");
			echo "<b><font face='Verdana' color='#000000' style='font-size: 11px'>Comment by $comment_username - ".date("l, d M Y, g:ia", $row['comment_date'])."</font></b><br>";
			echo "<br><font face='Verdana' color='#000000' style='font-size: 11px'>".$row['comment']."</font><br><br><hr width='80%' align='center'>";
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


?>

