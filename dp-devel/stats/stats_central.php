<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'project_states.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
include_once($relPath.'site_news.inc');
new dbConnect();

$title = _("Statistics Central");
theme($title,'header');

$begin_themed_row = "<tr bgcolor='".$theme['color_navbar_bg']."'>";

echo "<br><h2>" . _("Statistics Central") . "</h2>";

show_site_news_for_page("stats_central.php");

//Member/team stats searches and listings

echo "<table border='0' align='center' width='95%' cellspacing='2' cellpadding='2'>\n";
echo "<tr><td align='left'><form action='$code_url/stats/members/mbr_list.php' method='post'>";
echo "<font color='".$theme['color_headerbar_font']."'><input type='text' name='uname' size='20'>&nbsp;<input type='submit' value='"._("Member Search")."'></font></form></td>\n";
echo "<td align='right'><form action='$code_url/stats/teams/tlist.php' method='post'>";
echo "<font color='".$theme['color_headerbar_font']."'><input type='text' name='tname' size='20'>&nbsp;<input type='submit' value='"._("Team Search")."'></font></form></td>\n";
echo "</tr><tr><td align='center'><a href='$code_url/stats/members/mbr_list.php'>"._("Member List")."</a></td><td align='center'><a href='$code_url/stats/teams/tlist.php'>"._("Team List")."</a></td></tr></table>\n<br>\n";

//General site stats with links to view the queue's

echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'><tr><td>\n";
echo "<tr><td bgcolor='" . $theme['color_headerbar_bg'] . "'><center><font color='" . $theme['color_headerbar_font'] . "'><b>" . _("General Site Statistics") . "</b></font></center></td></tr>";
echo "<tr><td bgcolor=" . $theme['color_navbar_bg'] . ">";
echo "<table border='0' cellspacing='0' cellpadding='2' width='60%' align='center'>";

   //get total users active in the last 7 days
    $begin_time = time() - 604800; // in seconds
    $users = mysql_query("SELECT count(*) AS numusers FROM users
                          WHERE last_login > $begin_time");
    $totalusers = (mysql_result($users,0,"numusers"));
    echo $begin_themed_row;
    echo "<td align ='left'>"._("Proofreaders active in the last 7 days:")."</td><td align ='right'> $totalusers</td></tr>\n";

  //get total books posted  in the last 7 days

    $books = mysql_query("SELECT count(*) AS numbooks FROM projects
                          WHERE modifieddate >= $begin_time AND state = '".PROJ_SUBMIT_PG_POSTED."'");
    $totalbooks = (mysql_result($books,0,"numbooks"));
    echo $begin_themed_row;
    echo "<td align ='left'>"._("Books posted in the last 7 days:")."</td><td align ='right'>$totalbooks</td></tr>\n";

    $view_books=_("(View)");
  //get total first round books waiting to be released
    $firstwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."'");
    $totalfirstwaiting = (mysql_result($firstwaitingbooks,0,"numbooks"));
    echo $begin_themed_row;
    echo "<td align ='left'>"._("Books waiting to be released for first round:")." <a href ='to_be_released.php?order=default'>$view_books</a></td><td align ='right'>$totalfirstwaiting</td></tr>\n";

  //get total non-English books waiting to be released
    $nonwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                    WHERE state = '".PROJ_P1_WAITING_FOR_RELEASE."' AND language != 'English'");
    $totalnonwaiting = (mysql_result($nonwaitingbooks,0,"numbooks"));
    echo $begin_themed_row;
    echo "<td align ='left'>"._("Non-English Books waiting to be released for first round:")."</td><td align ='right'> $totalnonwaiting</td></tr>\n";

  //get total books waiting to be post processed
    $waitingpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_FIRST_AVAILABLE."'");
    $totalwaitingpost = (mysql_result($waitingpost,0,"numbooks"));
    echo $begin_themed_row;
    echo "<td align ='left'>"._("Books waiting for post processing:")."</td><td align ='right'>$totalwaitingpost</td></tr>\n";

  //get total books being post processed
    $inpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                           WHERE state = '".PROJ_POST_FIRST_CHECKED_OUT."'");
    $totalinpost = (mysql_result($inpost,0,"numbooks"));
    echo $begin_themed_row;
    echo "<td align ='left'>"._("Books being post processed:")." <a href ='checkedout.php?state=".PROJ_POST_FIRST_CHECKED_OUT."'>$view_books</a></td><td align ='right'>$totalinpost</td></tr>\n";

  //get total books in verify
    $verifybooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = '".PROJ_POST_SECOND_AVAILABLE."'");
    $totalverify = (mysql_result($verifybooks,0,"numbooks"));
    echo $begin_themed_row;
    echo "<td align ='left'>"._("Books waiting to be verified:")." <a href ='PPV_avail.php'>$view_books</a></td><td align ='right'>$totalverify</td></tr>\n";

  //get total books in verifying
    $verifyingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                   WHERE state = '".PROJ_POST_SECOND_CHECKED_OUT."'");
    $totalverifying = (mysql_result($verifyingbooks,0,"numbooks"));
    echo $begin_themed_row;
    echo "<td align ='left'>"._("Books being verified:")." <a href ='checkedout.php?state=".PROJ_POST_SECOND_CHECKED_OUT."'>$view_books</a></td><td align ='right'>$totalverifying</td></tr>\n";

echo "</table>\n";
echo "</td></tr></table>\n";
echo "<br>\n";


echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'><tr><td>\n";
echo "<table border='0' cellspacing='0' cellpadding='2' width='100%'>";
echo "<tr><td colspan='2' bgcolor='" . $theme['color_headerbar_bg'] . "'><center><font color='" . $theme['color_headerbar_font'] . "'><b>" . _("Miscellaneous Statistics") . "</b></font></center></td></tr>";

$sub_title = _("See All Waiting Queues");
echo $begin_themed_row;
echo "<td><a href='release_queue.php'>$sub_title</a></td>\n";

$sub_title = _("Most Requested Books");
echo "<td><a href='requested_books.php'>$sub_title</a></td></tr>\n";

$sub_title = _("Top Proofreading Days and Months, etc");
echo $begin_themed_row;
echo "<td><a href='misc_stats1.php'>$sub_title</a></td>\n";

$sub_title = _("User Logon Statistics");
echo "<td><a href='user_logon_stats.php'>$sub_title</a></td></tr>\n";

$sub_title = _("Project Management Statistics");
echo $begin_themed_row;
echo "<td><a href='pm_stats.php'>$sub_title</a></td>\n";

$sub_title = _("Proofreading Statistics");
echo "<td><a href='proof_stats.php'>$sub_title</a></td></tr>\n";

$sub_title = _("Post-Processing Statistics");
echo $begin_themed_row;
echo "<td><a href='pp_stats.php'>$sub_title</a></td>\n";

$sub_title = _("Post-Processing Verification Statistics");
echo "<td><a href='ppv_stats.php'>$sub_title</a></td></tr>\n";

echo "</table>\n";
echo "</td></tr></table>\n";
echo "<br>\n";

echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'><tr><td>\n";
echo "<table border='0' cellspacing='0' cellpadding='2' width='100%'>";
echo "<tr><td colspan='3' bgcolor='" . $theme['color_headerbar_bg'] . "'><center><font color='" . $theme['color_headerbar_font'] . "'><b>" . _("Project Graphs by Category") . "</b></font></center></td></tr>";

echo $begin_themed_row;
echo "<td>&nbsp;</td><td><b><i><u>";
echo _("Track by Project");
echo "</u></i></b></td><td><b><i><u>";
echo _("Track by Pages");
echo "</u></i></b></td></tr>\n";

echo $begin_themed_row;
echo "<td>";
echo _("Creating");
echo "</td><td>";

$sub_title = _("Projects Created Graphs");
echo "<a href='proj_created_graphs.php'>$sub_title</a>";
echo "</td><td>"._("Coming Soon")."</td></tr>\n";

echo $begin_themed_row;
echo "<td>";
echo _("Proofreading");
echo "</td><td>";

$sub_title = _("Projects Proofread Graphs");
echo "<a href='proj_proofed_graphs.php'>$sub_title</a>";
echo "</td><td>";
$sub_title = _("Pages Proofread Graphs");
echo "<a href='pages_proofed_graphs.php'>$sub_title</a>";
echo "</td></tr>\n";

echo $begin_themed_row;
echo "<td>";
echo _("PPing");
echo "</td><td>";

$sub_title = _("Projects PPd Graphs");
echo "<a href='proj_PPd_graphs.php'>$sub_title</a>";
echo "</td><td>"._("Coming Soon")."</td></tr>\n";

echo $begin_themed_row;
echo "<td>";
echo _("Posting");
echo "</td><td>";

$sub_title = _("Projects Posted Graphs");
echo "<a href='proj_posted_graphs.php'>$sub_title</a>";
echo "</td><td>"._("Coming Soon")."</td></tr>\n";

echo "</table>\n";
echo "</td></tr></table>\n";
echo "<br>\n";

echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'>";
echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font color='".$theme['color_headerbar_font']."'><b>"._("Total Projects Created, Proofread, Post-Processed and Posted")."</b></font></center></td></tr>";
echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'><center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_total_proj_summary_graph.php\" alt=\""._("Total Projects Created, Proofed, PPd and Posted")."\"></center><br>";
echo "</td></tr>\n";

echo "</table>\n";
echo "<br>\n";


theme('','footer');
?>

