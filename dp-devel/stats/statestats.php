<?php
$relPath='./../pinc/';
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include('statestats.inc');
$db_Connection=new dbConnect();



// display project count progress - here for the moment, can be moved to stats bar later
$cday = date('d'); $cmonth = date('m'); $cyear = date('Y');
$today = date('Y-m-d');


$start_date = date('Y-m-d',mktime(0,0,1,0,$cmonth,$cyear));
$descrip = _("so far this month");

echo "Today is $today, start is $start_date, descrip is $descrip  <br><br>";

	

$created = state_change_since ( "
				state not like 'project_new%'
				",$start_date);



echo "<b>$created</b> "._("projects have been created")." $descrip<br>";

$FinProof = state_change_since ( "
				(state LIKE 'proj_submit%' 
				OR state LIKE 'proj_correct%' 
				OR state LIKE 'proj_post%')
			",$start_date);



echo "<b>$FinProof</b> "._("projects have finished proofing")." $descrip<br>";


$FinPP = state_change_since ( "
				(state LIKE 'proj_submit%' 
				OR state LIKE 'proj_correct%' 
				OR state LIKE 'proj_post_second%')
	",$start_date);



echo "<b>$FinPP</b>"._("projects have finished PPing")." $descrip<br>";


?>
