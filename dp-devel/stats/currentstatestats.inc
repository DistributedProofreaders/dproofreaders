<?php
include('./../../stats/statestats.inc');

echo "<center>";

// display project count progress - here for the moment, can be moved to stats bar later
$cday = date('d'); $cmonth = date('m'); $cyear = date('Y');
$today = date('Y-m-d');

if ($cday != 1) {
    $start_date = $cyear."-".$cmonth."-01";
    $descrip = _("so far this month");
} else {
    $descrip = _("since the start of last month");
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



echo "<b>$FinPP</b> "._("projects have finished PPing")." $descrip<br>";

echo "</center>";

?>
