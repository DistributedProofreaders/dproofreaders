<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
if (!isset($_COOKIE['pguser'])) { include($relPath.'connect.inc'); } else { include($relPath.'dp_main.inc'); }
include($relPath.'theme.inc');
new dbConnect();

theme('Stats Central','header');
echo "<center><h1><i>Stats Central</i></h1></center>";

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
                          WHERE modifieddate >= $begin_time AND state = 'proj_submit_pgposted'");
    $totalbooks = (mysql_result($books,0,"numbooks"));
    echo "<td align ='left'>Books posted in the last 7 days:</td><td align ='right'>$totalbooks</td><tr>";


  //get total first round books waiting to be released
    $firstwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects WHERE state = 'waiting_1'");
    $totalfirstwaiting = (mysql_result($firstwaitingbooks,0,"numbooks"));
    echo "<td align ='left'>Books waiting to be released for first round:   <a href ='to_be_released.php?order=default'>(View)</a></td><td align ='right'>$totalfirstwaiting</td><tr>";


  //get total non-English books waiting to be released
    $nonwaitingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                    WHERE state = 'waiting_1' AND language != 'English'");
    $totalnonwaiting = (mysql_result($nonwaitingbooks,0,"numbooks"));
    echo "<td align ='left'>Non-English Books waiting to be released for first round:</td><td align ='right'> $totalnonwaiting</td><tr>";


  //get total books waiting to be post processed
    $waitingpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = 'proj_post_available'");
    $totalwaitingpost = (mysql_result($waitingpost,0,"numbooks"));
    echo "<td align ='left'>Books waiting for post processing:</td><td align ='right'>$totalwaitingpost</td><tr>";


  //get total books being post processed
    $inpost = mysql_query("SELECT count(*) AS numbooks FROM projects
                           WHERE state = 'proj_post_checkedout'");
    $totalinpost = (mysql_result($inpost,0,"numbooks"));
    echo "<td align ='left'>Books being post processed:  <a href ='pp_checkedout.php?order=default'>(View)</a></td><td align ='right'>$totalinpost</td><tr>";


  //get total books in verify
    $verifybooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                WHERE state = 'proj_post_verify'");
    $totalverify = (mysql_result($verifybooks,0,"numbooks"));
    echo "<td align ='left'>Books waiting to be verified:</td><td align ='right'>$totalverify</td><tr>";


  //get total books in verifying
    $verifyingbooks = mysql_query("SELECT count(*) AS numbooks FROM projects
                                   WHERE state = 'proj_post_verifying'");
    $totalverifying = (mysql_result($verifyingbooks,0,"numbooks"));
    echo "<td align ='left'>Books being verified:</td><td align ='right'>$totalverifying</td><tr>";


echo "</table>";


echo "<center><img src=\"jpgraph_files/curr_month_pages_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/total_pages_graph.php\"></center><br>";
echo "<center><img src=\"jpgraph_files/cumulative_month_pages.php\"></center><br>";

theme('','footer');
?>