<?
//A file full of member statistic functions

function showProjProfile($curProj) {
	global $theme;

        $avatar = $GLOBALS['projects_url']."/".$projectid."/001.png";

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='95%'>";

	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td width='35%' align='center' height='90'><img border='0' src='$avatar' alt='".$curProj['nameofwork']."'></td>";

// Title of Book

	echo "<td rowspan='2' bgcolor='".$theme['color_navbar_bg']."' align='left' valign='top' width='65%'><table border='0' width='100%' cellspacing='0' cellpadding='1'>";
	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='2' align='left' valign='top' bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>".$curProj['nameofwork']."</b></font></center></td></tr>";

// Author of Book

        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Author</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curProj['author']."</font></td></tr>";

// Language of Book

	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Language</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curProj['language']."</font></td></tr>";

// Genre of Book

        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Genre</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curProj['genre']."</font></td></tr>";

// State of Book

        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Current State</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".project_states_text($curProj['state'])."</font></td></tr>";

date("l, F jS, Y",$row['modifieddate'])

// Last Modified

        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Last Modified</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".date("l, F jS, Y",$curProj['modifieddate'])."</font></td></tr>";

	echo "</table></td></tr>";
	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td width='35%' valign='top' align='left'>";
	activityCenter($curProj);
	echo "</td></tr></table><p>";
}



function activityCenter($curProj) {
	global $theme;

	echo "<table border='0' width='100%' cellspacing='0' cellpadding='1'>";

	echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'><a href='".$projects_url."/".$curProj['projectid']."/images.html'>View Page Images</font></td></tr>";

	echo "</table>";
}


?>