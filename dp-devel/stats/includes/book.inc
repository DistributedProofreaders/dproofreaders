<?
include_once($relPath.'stages.inc');

//A file full of book statistic functions



function showProjProfile($curProj) {
	global $theme;

	$relPath="../../pinc/";

	include_once($relPath.'project_states.inc');

        $avatar = $GLOBALS['projects_url']."/".$curProj['projectid']."/001.png";

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='95%'>";

	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td width='35%' align='center' height='90'><img border='0' src='$avatar' alt='".$curProj['nameofwork']."' width = '100' height = '150'></td>";

// Title of Book

	echo "<td rowspan='2' bgcolor='".$theme['color_navbar_bg']."' align='left' valign='top' width='65%'><table border='0' width='100%' cellspacing='0' cellpadding='1'>";
	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='2' align='left' valign='top' bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>".$curProj['nameofwork']."</b></font></center></td></tr>";

// Author of Book

        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Author</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curProj['authorsname']."</font></td></tr>";

// Language of Book

	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Language</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curProj['language']."</font></td></tr>";

// Genre of Book

        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Genre</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curProj['genre']."</font></td></tr>";

// State of Book

        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Current State</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".project_states_text($curProj['state'])."</font></td></tr>";

// Last Modified

        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Last Modified</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".date("l, F jS, Y",$curProj['modifieddate'])."</font></td></tr>";

	echo "</table></td></tr>";
	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td width='35%' valign='top' align='left'>";
	activityCenter($curProj);
	echo "</td></tr></table><p>";
}



function activityCenter($curProj) {
	global $theme, $code_url, $projects_url;

	$relPath="../../pinc/";

	include_once($relPath.'project_states.inc');

	$projectid = $curProj['projectid'];

	$project_phase = get_phase_containing_project_state($curProj['state']);

	echo "<table border='0' width='100%' cellspacing='0' cellpadding='1'>";

// Discuss project in forums

	echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'><a href='$code_url/tools/proofers/project_topic.php?project=$projectid'>Discuss</a></font></td></tr>";

// View Page Images

	echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'><a href='$code_url/tools/proofers/images_index.php?project=$projectid'>View Page Images</a></font></td></tr>";

// Download Page Images

	echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'><a href='$projects_url/$projectid/images.zip'>Download Page Images</a></font></td></tr>";

// Start Proofing

	if ( $project_phase == 'PAGE_EDITING' )
	{
		$round = get_Round_for_project_state($curProj['state']);
		echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'><a href='$code_url/tools/proofers/projects.php?project=$projectid&proofstate={$round->project_available_state}'>Start Proofreading!</a></font></td></tr>\n";
	}

//	Book is Posted

	if ($project_phase = 'GB') {

		echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'>";
		echo get_pg_catalog_link_for_etext( $curProj['postednum'] );
		echo "</font></td></tr>";
	}

	echo "</table>";
}


?>
