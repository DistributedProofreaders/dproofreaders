<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();

$title = _("Statistics Central");
theme($title,'header');
//Member/team stats

echo "<table border='0' align='center' width='95%' cellspacing='2' cellpadding='2'>";
echo "<tr><form action='$code_url/stats/members/mbr_list.php' method='post'>";
echo "<td align='left'><font color='".$theme['color_headerbar_font']."'><input type='text' name='uname' size='20'>&nbsp;<input type='submit' value='Member Search'></td></form>";
echo "<form action='$code_url/stats/teams/tlist.php' method='post'>";
echo "<td align='right'><font color='".$theme['color_headerbar_font']."'><input type='text' name='tname' size='20'>&nbsp;<input type='submit' value='Team Search'></td></form>";
echo "</tr><tr><td align='center'><a href='$code_url/stats/members/mbr_list.php'>Member List</a></td><td align='center'><a href='$code_url/stats/teams/tlist.php'>Team List</a></td></tr></table><br>";

//General site stats with links to view the queue's

echo "<table border='0' align='center' width='60%' cellspacing='2' cellpadding='2'>";
   //get total users active in the last 7 days
    $begin_time = time() - 604800;
# $begin_time = time() - 2592000;
    $users = mysql_query("SELECT count(*) AS numusers FROM users
                          WHERE last_login > $begin_time");
    $totalusers = (mysql_result($users,0,"numusers"));
    echo "<tr><td align ='left'>Proofers active in the last 7 days:</td><td align ='right'> $totalusers</td><tr>";


  //get total books posted  in the last 7 days

    $books = mysql_query("SELECT count(*) AS numbooks FROM projects
                          WHERE modifieddate >= $begin_time AND state = '".PROJ_SUBMIT_PG_POSTED."'");
    $totalbooks = (mysql_result($books,0,"numbooks"));
    echo "<td align ='left'>Books posted in the last 7 days:</td><td align ='right'>$totalbooks</td><tr>";





  //get total first round books waiting to be released
    $firstwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects WHERE state = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."'");
    $totalfirstwaiting = (mysql_result($firstwaitingbooks,0,"numbooks"));
    echo "<td align ='left'>Books waiting to be released for first round:   <a href ='to_be_released.php?order=default'>(View)</a></td><td align ='right'>$totalfirstwaiting</td><tr>";


  //get total non-English books waiting to be released
    $nonwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                    WHERE state = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."' AND language != 'English'");
    $totalnonwaiting = (mysql_result($nonwaitingbooks,0,"numbooks"));
    echo "<td align ='left'>Non-English Books waiting to be released for first round:</td><td align ='right'> $totalnonwaiting</td><tr>";


  //get total books waiting to be post processed
    $waitingpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_FIRST_AVAILABLE."'");
    $totalwaitingpost = (mysql_result($waitingpost,0,"numbooks"));
    echo "<td align ='left'>Books waiting for post processing:</td><td align ='right'>$totalwaitingpost</td><tr>";


  //get total books being post processed
    $inpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                           WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."'");
    $totalinpost = (mysql_result($inpost,0,"numbooks"));
    echo "<td align ='left'>Books being post processed:  <a href ='checkedout.php?state=".PROJ_POST_FIRST_CHECKED_OUT."&order=default'>(View)</a></td><td align ='right'>$totalinpost</td><tr>";


  //get total books in verify
    $verifybooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_SECOND_AVAILABLE."'");
    $totalverify = (mysql_result($verifybooks,0,"numbooks"));
    echo "<td align ='left'>Books waiting to be verified:</td><td align ='right'>$totalverify</td><tr>";


  //get total books in verifying
    $verifyingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                   WHERE state = '".PROJ_POST_SECOND_CHECKED_OUT."'");
    $totalverifying = (mysql_result($verifyingbooks,0,"numbooks"));
    echo "<td align ='left'>Books being verified: <a href ='checkedout.php?state=".PROJ_POST_SECOND_CHECKED_OUT."&order=default'>(View)</a></td><td align ='right'>$totalverifying</td><tr>";

echo "</table>";
echo "<br>";


$sub_title = _("See All Waiting Queues");
echo "<p align='center'><a href='release_queue.php'>$sub_title</a></p>";

echo "<table border='0' align='center' width='80%' cellspacing='2' cellpadding='2'>";

$sub_title = _("Top Proofreading Days and Months, etc");
echo "<tr><td><a href='misc_stats1.php'>$sub_title</a></td>";

$sub_title = _("User Logon Statistics");
echo "<td><a href='user_logon_stats.php'>$sub_title</a></td></tr>";

$sub_title = _("Project Management Statistics");
echo "<tr><td><a href='pm_stats.php'>$sub_title</a></td>";

$sub_title = _("Post-Processing Statistics");
echo "<td><a href='pp_stats.php'>$sub_title</a></td></tr></table>";

$sub_title = _("Post-Processing Verification Statistics");
echo "<p align='center'><a href='ppv_stats.php'>$sub_title</a></p><br>";


echo "<table border='1' align='center' width='95%' cellspacing='2' cellpadding='2'>";
echo "<tr><td>&nbsp;</td><td>";
echo _("Track by Project");
echo "</td><td>";
echo _("Track by Pages");
echo "</td></tr><tr><td>";

echo _("Creating");
echo "</td><td>";

$sub_title = _("Projects Created Graphs");
echo "<a href='proj_created_graphs.php'>$sub_title</a><br><br>";
echo "</td><td>Coming soon</td></tr>";

echo "<tr><td>";
echo _("Proofreading");
echo "</td><td>";

$sub_title = _("Projects Proofed Graphs");
echo "<a href='proj_proofed_graphs.php'>$sub_title</a><br><br>";
echo "</td><td>";
$sub_title = _("Pages Proofed Graphs");
echo "<a href='pages_proofed_graphs.php'>$sub_title</a><br><br>";
echo "</td></tr>";


echo "<tr><td>";
echo _("PPing");
echo "</td><td>";

$sub_title = _("Projects PPd Graphs");
echo "<a href='proj_PPd_graphs.php'>$sub_title</a><br><br>";
echo "</td><td>Coming soon</td></tr>";

echo "<tr><td>";
echo _("Posting");
echo "</td><td>";

$sub_title = _("Projects Posted Graphs");
echo "<a href='proj_posted_graphs.php'>$sub_title</a><br><br>";
echo "</td><td>Coming soon</td></tr></table><br>";

echo "<center><img src=\"jpgraph_files/cumulative_total_proj_summary_graph.php\"></center><br>";
echo "<br><br>";


theme('','footer');
?>
