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




echo "
	<table width='75%' border='4' rules='all' align=center>
	  <tbody>
	  <tr bgcolor='".$theme['color_navbar_bg']."'>
	      <th colspan=04><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."'>"
	._("Projects this month").
		"</font></th>
	  <tr>
";

foreach ( array('created','proofed','PPd','posted') as $which )
{
	$graphs_url = "$code_url/stats/projects_Xed_graphs.php?which=$which";

	$psd = get_project_status_descriptor($which);
	$n = state_change_since($psd->state_selector, $start_date);

	echo "<td align=center bgcolor='".$theme['color_mainbody_bg']."'>";
	echo "<a href='$graphs_url'>$psd->Xed_title</a>: <b>$n</b>";
	echo "</td>";
}

echo "
	  </tr>
	  </tbody>
	</table>
";

echo "<br><hr width='75%'></center><br>";

?>
