<?php
include_once($relPath.'v_site.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');

echo "<center>";

// display project count progress - here for the moment, can be moved to stats bar later
$cday = date('d'); $cmonth = date('m'); $cyear = date('Y');
$today = date('Y-m-d');

if ($cday != 1) {
    $start_date = $cyear."-".$cmonth."-01";
    $descrip = _("this month");
} else {
    $descrip = _("last month");
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

$FinProof = state_change_since ( "
				(state LIKE 'proj_submit%'
				OR state LIKE 'proj_correct%'
				OR state LIKE 'proj_post%')
			",$start_date);

$FinPP = state_change_since ( "
				(state LIKE 'proj_submit%'
				OR state LIKE 'proj_correct%'
				OR state LIKE 'proj_post_second%')
			",$start_date);

$Posted = state_change_since ( "
				(state LIKE 'proj_submit%'
				OR state LIKE 'proj_correct%')
			",$start_date);



echo "
	<table width='75%' border='4' rules='all' align=center>
	  <tbody>
	  <tr bgcolor='".$theme['color_navbar_bg']."'>
	      <th colspan=04><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."'>"
	. _("Projects this month") .
		"</font></th>
	  <tr><td align=center bgcolor='".$theme['color_mainbody_bg']."'>"._("Created").":        <b>$created</b></td>
	      <td align=center bgcolor='".$theme['color_mainbody_bg']."'>"._("Proofread").":      <b>$FinProof</b></td>
	      <td align=center bgcolor='".$theme['color_mainbody_bg']."'>"._("Post-Processed").": <b>$FinPP</b></td>
	      <td align=center bgcolor='".$theme['color_mainbody_bg']."'>"._("Posted to PG").": <b>$Posted</b></td>
	  </tr>
	  </tbody>
	</table>
";

echo "<br><hr width='75%'></center><br>";

?>
