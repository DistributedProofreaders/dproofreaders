<?php
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($code_dir.'/stats/statestats.inc');
include_once($relPath.'gettext_setup.inc');

echo "<center>";


// display project count progress - here for the moment, can be moved to stats bar later
$cday = date('d'); $cmonth = date('m'); $cyear = date('Y');
$today = date('Y-m-d');

if ($cday != 1) {
    $start_date = $cyear."-".$cmonth."-01";
    $descrip = "this month";
} else {
    $descrip = "last month";
    if ($cmonth != 1) {
	$temp = $cmonth -1;
	$start_date = $cyear."-".$temp."-01";
    } else {
	$temp = $cyear - 1;
 	$start_date = $temp."-12-01";
    }
}


$psd = get_project_status_descriptor('created');
$created = state_change_since($psd->state_selector, $start_date);

$psd = get_project_status_descriptor('proofed');
$FinProof = state_change_since($psd->state_selector, $start_date);

$psd = get_project_status_descriptor('PPd');
$FinPP = state_change_since($psd->state_selector, $start_date);

$psd = get_project_status_descriptor('posted');
$Posted = state_change_since($psd->state_selector, $start_date);



echo "
	<table width='75%' border='4' rules='all' align=center>
	  <tbody>
	  <tr bgcolor='".$theme['color_navbar_bg']."'>
	      <th colspan=04><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."'>"
	._("Projects this month").
		"</font></th>
	  <tr><td align=center bgcolor='".$theme['color_mainbody_bg']."'><a href='$code_url/stats/projects_Xed_graphs.php?which=created'>"._("Created")."</a>:        <b>$created</b></td>
	      <td align=center bgcolor='".$theme['color_mainbody_bg']."'><a href='$code_url/stats/projects_Xed_graphs.php?which=proofed'>"._("Proofread")."</a>:      <b>$FinProof</b></td>
	      <td align=center bgcolor='".$theme['color_mainbody_bg']."'><a href='$code_url/stats/projects_Xed_graphs.php?which=PPd'>"._("Post-Processed")."</a>: <b>$FinPP</b></td>
	      <td align=center bgcolor='".$theme['color_mainbody_bg']."'><a href='$code_url/stats/projects_Xed_graphs.php?which=posted'>"._("Posted to PG")."</a>: <b>$Posted</b></td>
	  </tr>
	  </tbody>
	</table>
";

echo "<br><hr width='75%'></center><br>";

?>
