<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include_once('./pages_proofed.inc');
$db_Connection=new dbConnect();

$stats_inc_path="$dynstats_dir/stats.inc";


//define lock file name
$filename = "$dynstats_dir/stats.php.lock";

//test for lock
if (file_exists($filename)) {
    //lock file exists, exit
    print "stats.php is already running! lock file
$dynstats_dir/stats.php.lock exists\n";
    mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
                       VALUES ('stats.php', ".time().", 'FAIL', 'didn\'t run, lock file found')");
    exit;
} else { //no lock so proceed with stats run
$handle = fopen ($filename, "w");
fclose($handle);

if (!file_exists($stats_inc_path)) {
        touch($stats_inc_path);
        $statsfile = fopen($stats_inc_path, "w");
        fputs($statsfile, "<?\n");
        fputs($statsfile, "\$sitestats['monthly'] = \"0\";\n");
        fputs($statsfile, "\$sitestats['daily'] = \"0\";\n");
        fputs($statsfile, "\$sitestats['goal'] = \"0\";\n");
        fputs($statsfile, "?>\n");
        }

$today = getdate();
$midnight = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);

$tracetime = time();
mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('stats.php', $tracetime, 'BEGIN', 'ok to run, no lock file')");

$dailyPages = get_n_pages_proofed( $midnight, null, $numProjects );

//echo result so we know cron job is working and to avoid timeout
echo "Daily pages: ".number_format($dailyPages);


$tracetimea = time();
$tooktime = $tracetimea - $tracetime;
$qrytext = "INSERT INTO job_logs (filename, tracetime, event, comments)
                VALUES ('stats.php', $tracetimea, 'MIDDLE', 'started $tracetime, $tooktime sec, $numProjects projects')";
$resultA = mysql_query($qrytext);




$result = mysql_query("SELECT SUM(pages) AS monthlypages FROM pagestats WHERE month=".$today['mon']." AND year=".$today['year']."");
$monthlyPages = mysql_result($result, 0, "monthlypages");

//Let's only update the monthly goal if it is the first day of the month and it is
//between 0000 hours and 0300 hours (to allow for cron job delays).  Always update the monthly
//goal if override_goal has been set
if (($today['mday'] == 1 && ($today['hours'] >= 0 && $today['hours'] <= 3)) || $_GET['override_goal'] == 1) {
$result = mysql_query("SELECT SUM(dailygoal) AS monthlygoal FROM pagestats WHERE year=".$today['year']." AND month=".$today['mon']."");
$monthlyGoal = mysql_result($result, 0, "monthlygoal");
$updateMonthlyGoal = 1;
}

//Read the entire stats file into the $lines array
$i=0;
$lines = file($stats_inc_path);
$statsfile = fopen($stats_inc_path, "w");
while ($i < count($lines)) {
        if (substr($lines[$i], 12, 7) == "monthly") { fputs($statsfile, "\$sitestats['monthly'] = \"$monthlyPages\";\n"); }
        elseif (substr($lines[$i], 12, 5) == "daily") { fputs($statsfile, "\$sitestats['daily'] = \"$dailyPages\";\n");  }
        elseif (substr($lines[$i], 12, 4) == "goal" && $updateMonthlyGoal == 1) { fputs($statsfile, "\$sitestats['goal'] = \"$monthlyGoal\";\n"); }
        else { fputs($statsfile, trim($lines[$i])."\n"); }
        $i++;
        }
        fclose($statsfile);
//delete lock file
unlink ($filename);

$tracetimea = time();
$tooktime = $tracetimea - $tracetime;
$qrytext = "INSERT INTO job_logs (filename, tracetime, event, comments)
                VALUES ('stats.php', $tracetimea, 'END', 'started at $tracetime, took $tooktime seconds total')";
$resultA = mysql_query($qrytext);




}

?>
