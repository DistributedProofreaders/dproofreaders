<?php
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include('statestats.inc');
include_once($relPath.'gettext_setup.inc');
$db_Connection=new dbConnect();


// Not translating this file since it is not used by end-users



// display project count progress - here for the moment, can be moved to stats bar later
$cday = date('d'); $cmonth = date('m'); $cyear = date('Y');
$today = date('Y-m-d');

if ($cday != 1) {
    $start_date = $cyear."-".$cmonth."-01";
    $descrip = "so far this month";
} else {
    $descrip = "since the start of last month";
    if ($cmonth != 1) {
	$temp = $cmonth -1;
	$start_date = $cyear."-".$temp."-01";
    } else {
	$temp = $cyear - 1;
 	$start_date = $temp."-12-01";
    }
}


$created = state_change_since ( "
				state not like 'project_new%'
				",$start_date);



echo "<b>$created</b> projects have been created $descrip<br>";

$FinProof = state_change_since ( "
				(state LIKE 'proj_submit%' 
				OR state LIKE 'proj_correct%' 
				OR state LIKE 'proj_post%')
			",$start_date);



echo "<b>$FinProof</b> projects have finished proofreading $descrip<br>";


$FinPP = state_change_since ( "
				(state LIKE 'proj_submit%' 
				OR state LIKE 'proj_correct%' 
				OR state LIKE 'proj_post_second%')
	",$start_date);



echo "<b>$FinPP</b> projects have finished PPing $descrip<br>";



// ****************************************

echo "<br><br>";
$descrip = "in November";
$created = state_change_between_dates("
				state not like 'project_new%'
				",'2003-11-01','2003-12-01');
echo "<b>$created</b> projects were created $descrip<br>";

$FinProof = state_change_between_dates("
				(state LIKE 'proj_submit%' 
				OR state LIKE 'proj_correct%' 
				OR state LIKE 'proj_post%')
				",'2003-11-01','2003-12-01');
echo "<b>$FinProof</b> projects were proofread $descrip<br>";

$FinPP = state_change_between_dates("
				(state LIKE 'proj_submit%' 
				OR state LIKE 'proj_correct%' 
				OR state LIKE 'proj_post_second%')
				",'2003-11-01','2003-12-01');
echo "<b>$FinPP</b> projects were PPd $descrip<br>";


echo "<br><br>";
$descrip = "in October";
$created = state_change_between_dates("
				state not like 'project_new%'
				",'2003-10-03','2003-11-01');
$created += 19; // historical adjustment for first days of Oct
echo "<b>$created</b> projects were created $descrip<br>";

$FinProof = state_change_between_dates("
				(state LIKE 'proj_submit%' 
				OR state LIKE 'proj_correct%' 
				OR state LIKE 'proj_post%')
				",'2003-10-03','2003-11-01');
$FinProof += 69; // historical adjustment for first days of Oct
echo "<b>$FinProof</b> projects were proofread $descrip<br>";

$FinPP = state_change_between_dates("
				(state LIKE 'proj_submit%' 
				OR state LIKE 'proj_correct%' 
				OR state LIKE 'proj_post_second%'
				)
				",'2003-10-03','2003-11-01');
$FinPP +=   28; // historical adjustment for first days of Oct
echo "<b>$FinPP</b> projects were PPd $descrip<br>";




?>
