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

echo "<table border='0' align='center' width='95%' cellspacing='2' cellpadding='2'>\n";
echo "<tr><td align='left'><form action='$code_url/stats/members/mbr_list.php' method='post'>";
echo "<font color='".$theme['color_headerbar_font']."'><input type='text' name='uname' size='20'>&nbsp;<input type='submit' value='"._("Member Search")."'></font></form></td>\n";
echo "<td align='right'><form action='$code_url/stats/teams/tlist.php' method='post'>";
echo "<font color='".$theme['color_headerbar_font']."'><input type='text' name='tname' size='20'>&nbsp;<input type='submit' value='"._("Team Search")."'></font></form></td>\n";
echo "</tr><tr><td align='center'><a href='$code_url/stats/members/mbr_list.php'>"._("Member List")."</a></td><td align='center'><a href='$code_url/stats/teams/tlist.php'>"._("Team List")."</a></td></tr></table>\n<br>\n";

//General site stats with links to view the queue's

echo "<table border='0' align='center' width='60%' cellspacing='2' cellpadding='2'>\n";
   //get total users active in the last 7 days
    $begin_time = time() - 604800;
# $begin_time = time() - 2592000;
    $users = mysql_query("SELECT count(*) AS numusers FROM users
                          WHERE last_login > $begin_time");
    $totalusers = (mysql_result($users,0,"numusers"));
    echo "<tr><td align ='left'>"._("Proofreaders active in the last 7 days:")."</td><td align ='right'> $totalusers</td>\n";


  //get total books posted  in the last 7 days

    $books = mysql_query("SELECT count(*) AS numbooks FROM projects
                          WHERE modifieddate >= $begin_time AND state = '".PROJ_SUBMIT_PG_POSTED."'");
    $totalbooks = (mysql_result($books,0,"numbooks"));
    echo "<tr><td align ='left'>"._("Books posted in the last 7 days:")."</td><td align ='right'>$totalbooks</td>\n";




    $view_books=_("(View)");
  //get total first round books waiting to be released
    $firstwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects WHERE state = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."'");
    $totalfirstwaiting = (mysql_result($firstwaitingbooks,0,"numbooks"));
    echo "<tr><td align ='left'>"._("Books waiting to be released for first round:")." <a href ='to_be_released.php?order=default'>$view_books</a></td><td align ='right'>$totalfirstwaiting</td>\n";


  //get total non-English books waiting to be released
    $nonwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                    WHERE state = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."' AND language != 'English'");
    $totalnonwaiting = (mysql_result($nonwaitingbooks,0,"numbooks"));
    echo "<tr><td align ='left'>"._("Non-English Books waiting to be released for first round:")."</td><td align ='right'> $totalnonwaiting</td>\n";


  //get total books waiting to be post processed
    $waitingpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_FIRST_AVAILABLE."'");
    $totalwaitingpost = (mysql_result($waitingpost,0,"numbooks"));
    echo "<tr><td align ='left'>"._("Books waiting for post processing:")."</td><td align ='right'>$totalwaitingpost</td>\n";


  //get total books being post processed
    $inpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                           WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."'");
    $totalinpost = (mysql_result($inpost,0,"numbooks"));
    echo "<tr><td align ='left'>"._("Books being post processed:")." <a href ='checkedout.php?state=".PROJ_POST_FIRST_CHECKED_OUT."'>$view_books</a></td><td align ='right'>$totalinpost</td>\n";


  //get total books in verify
    $verifybooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_SECOND_AVAILABLE."'");
    $totalverify = (mysql_result($verifybooks,0,"numbooks"));
    echo "<tr><td align ='left'>"._("Books waiting to be verified:")." <a href ='PPV_avail.php'>$view_books</a></td><td align ='right'>$totalverify</td>\n";


  //get total books in verifying
    $verifyingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                   WHERE state = '".PROJ_POST_SECOND_CHECKED_OUT."'");
    $totalverifying = (mysql_result($verifyingbooks,0,"numbooks"));
    echo "<tr><td align ='left'>"._("Books being verified:")." <a href ='checkedout.php?state=".PROJ_POST_SECOND_CHECKED_OUT."'>$view_books</a></td><td align ='right'>$totalverifying</td>\n";

echo "</table>\n";
echo "<br>\n";


echo "<table border='0' align='center' width='95%' cellspacing='2' cellpadding='2'>\n";

$sub_title = _("See All Waiting Queues");
echo "<tr><td><a href='release_queue.php'>$sub_title</a></td>\n";

$sub_title = _("Most Requested Books");
echo "<td><a href='requested_books.php'>$sub_title</a></td></tr>\n";

$sub_title = _("Top Proofreading Days and Months, etc");
echo "<tr><td><a href='misc_stats1.php'>$sub_title</a></td>\n";

$sub_title = _("User Logon Statistics");
echo "<td><a href='user_logon_stats.php'>$sub_title</a></td></tr>\n";

$sub_title = _("Project Management Statistics");
echo "<tr><td><a href='pm_stats.php'>$sub_title</a></td>\n";

$sub_title = _("Proofreading Statistics");
echo "<td><a href='proof_stats.php'>$sub_title</a></td></tr>\n";

$sub_title = _("Post-Processing Statistics");
echo "<tr><td><a href='pp_stats.php'>$sub_title</a></td>\n";

$sub_title = _("Post-Processing Verification Statistics");
echo "<td><a href='ppv_stats.php'>$sub_title</a></td></tr>\n</table>\n";

echo "<br><br>\n";

echo "<table border='1' align='center' width='95%' cellspacing='2' cellpadding='2'>\n";
echo "<tr><td>&nbsp;</td><td>";
echo _("Track by Project");
echo "</td><td>";
echo _("Track by Pages");
echo "</td></tr>\n<tr><td>";

echo _("Creating");
echo "</td><td>";

$sub_title = _("Projects Created Graphs");
echo "<a href='proj_created_graphs.php'>$sub_title</a><br><br>";
echo "</td><td>"._("Coming soon")."</td></tr>\n";

echo "<tr><td>";
echo _("Proofreading");
echo "</td><td>";

$sub_title = _("Projects Proofread Graphs");
echo "<a href='proj_proofed_graphs.php'>$sub_title</a><br><br>";
echo "</td><td>";
$sub_title = _("Pages Proofread Graphs");
echo "<a href='pages_proofed_graphs.php'>$sub_title</a><br><br>";
echo "</td></tr>\n";


echo "<tr><td>";
echo _("PPing");
echo "</td><td>";

$sub_title = _("Projects PPd Graphs");
echo "<a href='proj_PPd_graphs.php'>$sub_title</a><br><br>";
echo "</td><td>"._("Coming soon")."</td></tr>\n";

echo "<tr><td>";
echo _("Posting");
echo "</td><td>";

$sub_title = _("Projects Posted Graphs");
echo "<a href='proj_posted_graphs.php'>$sub_title</a><br><br>";
echo "</td><td>"._("Coming soon")."</td></tr>\n</table><br>";

echo "<center><img src=\"jpgraph_files/cumulative_total_proj_summary_graph.php\" alt=\""._("Total Projects Created, Proofed, PPd and Posted")."\"></center><br>";
echo "<br><br>\n";


theme('','footer');
?>
