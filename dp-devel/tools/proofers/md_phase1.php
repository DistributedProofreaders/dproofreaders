<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
#include_once($relPath.'projectinfo.inc');
#include_once($relPath.'project_edit.inc');
#$projectinfo = new projectinfo();
#include_once('projectmgr.inc');
#include_once('page_table.inc');

$no_stats=1;
theme("Image Metadata Phase1", "header");
echo "tst";

//$projectid = $_GET['project'];
$projectid ='projectID3f370ab725580';
abort_if_cant_edit_project( $projectid );

//echo_manager_header( 'project_detail_page' );


$result = mysql_query("SELECT nameofwork, authorsname, language, username, state FROM projects WHERE projectid = '$projectid'");

$manager = mysql_result($result, 0, "username");
$state = mysql_result($result, 0, "state");
$name = mysql_result($result, 0, "nameofwork");
$author = mysql_result($result, 0, "authorsname");
$language = mysql_result($result, 0, "language");

$projectinfo->update($projectid, $state);

echo "<center><table border=1>";
echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan=4><b><font color='".$theme['color_headerbar_font']."' size=+1>Project Name: $name </b>(</font><a href='editproject.php?project=$projectid'><font color='".$theme['color_headerbar_font']."'>$projectid</a>)</font></td></tr>";
echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'>Author:</td><td>$author</td><td bgcolor='".$theme['color_navbar_bg']."'>Total Number of Master Pages:</td><td>$projectinfo->total_pages</td></tr>";
echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'>Language:</td><td>$language</td><td bgcolor='".$theme['color_navbar_bg']."'>Pages Remaining to be Proofed:</td><td>$projectinfo->availablepages</td></tr>";
echo "</table>";

//---------------------------------------------------------------------------------------------------


echo "<h3>Per-Page Info</h3>\n";

echo "<table border=1>\n";


	// Top header row
//	{
		echo "<tr>\n";
		echo "    <td align='center' colspan='1'>&nbsp;</td>\n";
		echo "    <td align='center' colspan='1'>Image Name</td>\n";
		echo "    <td align='center' colspan='1'>Original Page #</td>\n";
		echo "    <td align='center' colspan='4'>Page Metadata</td>\n";
		echo "    <td align='center' colspan='4'>Thumbnail</td>\n";
		echo "</tr>\n";
//	}

// Image rows
	$path = "$projects_dir/$projectid/";

	$fields_to_get = '
		fileid, image, length(master_text),
		state,
		round1_time, round1_user, length(round1_text),
		round2_time, round2_user, length(round2_text)';

	$res = mysql_query( "SELECT $fields_to_get FROM $projectid ORDER BY image ASC" );
	$num_rows = mysql_num_rows($res);

	for ( $rownum=0; $rownum < $num_rows; $rownum++ )
	{
		$page_res = mysql_fetch_array( $res, MYSQL_ASSOC );

		$fileid = $page_res['fileid'];

		if ($rownum % 2 ) {
			$row_color = $theme['color_mainbody_bg'];
		} else {
			$row_color = $theme['color_navbar_bg'];
		}
		echo "<tr bgcolor='$row_color'>";

		// --------------------------------------------
		// Index
		$index = $rownum+1;
		echo "<td align='right'>$index</td>\n";

		// --------------------------------------------
		// Upload Block

		// Image
		$imagename = $page_res['image'];
		if (file_exists($path.$imagename)) {
			$bgcolor = $row_color;
			if ($show_image_size) $imagesize = filesize(realpath($path.$imagename));
		} else {
			$bgcolor = "#FF0000";
			if ($show_image_size) $imagesize = 0;
		}
		echo "<td bgcolor='$bgcolor'><a href=displayimage.php?project=$projectid&imagefile=$imagename>$imagename</a></td>\n";

		// Image Size
		if ($show_image_size)
		{
			echo "<td bgcolor='$bgcolor' align='right'>$imagesize</td>";
		}

		// Master Text
		$master_text_length = $page_res['length(master_text)'];
		echo "<td align='right'><a href=downloadproofed.php?project=$projectid&fileid=$fileid&state=".UNAVAIL_FIRST.">$master_text_length&nbsp;b</a></td>\n";

		// --------------------------------------------

		// Page State
		$page_state = $page_res['state'];
		echo "<td>$page_state</td>\n";

		// --------------------------------------------

		echo_cells_for_round(1, $page_res, $projectid, $inRound );

		echo_cells_for_round(2, $page_res, $projectid, $inRound );

		// (It's annoying that we have to pass all those args,
		// or else make them global, which I don't want to do.)

		// --------------------------------------------

		// Bad Page
		echo "<td>";
		if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
			echo "<center><a href='badpage.php?projectid=$projectid&fileid=$fileid'>X</a></center>";
		} else {
			echo "&nbsp;";
		}
		echo "</td>\n";

		// Delete
		if ($show_delete)
		{
			echo "<td><a href=deletefile.php?project=$projectid&fileid=$fileid>Delete</a></td>\n";
		}

		echo "</tr>";
	}
	echo "</table>";
//}




echo "</center>";

echo "<br>";
theme("","footer");
?>
