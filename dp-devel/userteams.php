<?
$relPath="./pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');

if (!empty($_GET['tid'])) {
	metarefresh(10,"$code_url/stats/teams/tdetail.php?tid=$tid",'Redirecting to new team statistic pages','We have updated the code used for member & team statistics.  You will be redirected in ten seconds...Please update your links once you are there.');
	exit();
}  elseif (!empty($_GET['jtid'])) {
	$otid=isset($otid)?$otid:0;
	if (empty($_GET['otid'])) { $otid = 0; } else { $otid = $_GET['otid']; }
  	metarefresh(10,"$code_url/stats/members/jointeam.php?tid=".$_GET['jtid']."&otid=$otid",'Redirecting to new team statistic pages','We have updated the code used for member & team statistics.  You will be redirected in ten seconds...Please update your links once you are there.');
	exit;
} elseif (!empty($_GET['qtid'])) {
	metarefresh(10,"$code_url/stats/members/quitteam.php?tid=".$_GET['qtid']."",'Redirecting to new team statistic pages','We have updated the code used for member & team statistics.  You will be redirected in ten seconds...Please update your links once you are there.');
	exit();
} elseif (!empty($_GET['etid']) || !empty($_POST['edPreview']) || !empty($_POST['edMake'])) {
	metarefresh(10,"$code_url/stats/teams/tedit.php?tid=".$_GET['etid']."",'Redirecting to new team statistic pages','We have updated the code used for member & team statistics.  You will be redirected in ten seconds...Please update your links once you are there.');
	exit();
} elseif (!empty($_GET['ctid']) || !empty($_POST['mkPreview']) || !empty($_POST['mkMake'])) {
	metarefresh(10,"$code_url/stats/teams/new_team.php",'Redirecting to new team statistic pages','We have updated the code used for member & team statistics.  You will be redirected in ten seconds...Please update your links once you are there.');
	exit();
}

if (empty($_GET['order'])) {
	$order = "id";
	$direction = "asc";
} else {
	$order = $_GET['order'];
	$direction = $_GET['direction'];
}

if (!empty($_GET['tstart'])) { $tstart = $_GET['tstart']; } else { $tstart = 0; }
metarefresh(10,"$code_url/stats/teams/tlist.php?tstart=$tstart&order=$order&direction=$direction",'Redirecting to new team statistic pages','We have updated the code used for member & team statistics.  You will be redirected in ten seconds...Please update your links once you are there.');
?>