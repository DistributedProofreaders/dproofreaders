<?
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$stats_inc_path="$dynstats_dir/stats.inc";

if (!file_exists($stats_inc_path)) { 
	touch($stats_inc_path); 
	$statsfile = fopen($stats_inc_path, "w");
	fputs($statsfile, "<?\n");
	fputs($statsfile, "\$sitestats['monthly'] = \"0\";\n");
	fputs($statsfile, "\$sitestats['daily'] = \"0\";\n");
	fputs($statsfile, "\$sitestats['goal'] = \"150,000\";\n");
	fputs($statsfile, "?>\n");
	}

$today = getdate();
$midnight = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);

//limit to looking at projects modified
//after 1 Jan 2003 as older projects have a
//different table structure
$allProjects = mysql_query("SELECT projectid FROM projects WHERE archived ='0'");
$numProjects = mysql_num_rows($allProjects);

while ($i < $numProjects) {
	$projectID = mysql_result($allProjects, $i, "projectid");
	$result = mysql_query("SELECT COUNT(*) FROM $projectID WHERE state='save_first' AND round1_time >= $midnight OR state='save_second' AND round2_time >= $midnight");
	$row = mysql_fetch_row($result);
	$dailyPages = $dailyPages+$row[0];
	$i++;
}
$dailyPages = number_format($dailyPages);

$monthlyPages = mysql_query("SELECT SUM(pages) FROM pagestats WHERE month=".$today['mon']." AND year=".$today['year']."");
$row = mysql_fetch_row($monthlyPages);
$monthlyPages = $row[0];
$monthlyPages = number_format($monthlyPages);

//Read the entire stats file into the $lines array
$i=0;
$lines = file($stats_inc_path);
$statsfile = fopen($stats_inc_path, "w");
while ($i < count($lines)) {
	if (substr($lines[$i], 12, 7) == "monthly") { fputs($statsfile, "\$sitestats['monthly'] = \"$monthlyPages\";\n"); }
	elseif (substr($lines[$i], 12, 5) == "daily") {	fputs($statsfile, "\$sitestats['daily'] = \"$dailyPages\";\n");	}
	else { fputs($statsfile, trim($lines[$i])."\n"); }
	$i++;
	}
	fclose($statsfile);
				
?>

