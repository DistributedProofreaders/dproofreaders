<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_states.inc');
//include_once($relPath.'project_edit.inc');
//$projectinfo = new projectinfo();
//include_once('projectmgr.inc');
//include_once('page_table.inc');
include_once($relPath.'bookpages.inc');

theme("Image Metadata Collection", "header");

if (!$metadata)
{
	echo 'md_available.php: $metadata is false, so exiting.';
	exit();
}

//Phase 1 table----------------------------------------------------------------------------------

echo "<table border=1>\n";
      // Header row
		echo "<tr>\n";
		echo "    <td align='center' colspan='4'><b>Books Waiting for Phase 1 Review</b></td><tr></tr>\n";
            echo "    <td align='center' colspan='4'>In this phase we determine that the book has all of its pages and annotate what the original page number was in the printed version.</td><tr></tr>\n";
		echo "    <td align='center' colspan='1'><b>Title</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Author</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Total Pages</b></td>\n";
//		echo "    <td align='center' colspan='1'><b>Remaining Pages</b></td>\n";
		echo "</tr>\n";

      $result = mysql_query("SELECT projectid, nameofwork, authorsname, language, state FROM projects
                WHERE state = 'project_md_first' AND thumbs = 'yes'");

	$numrows = mysql_num_rows($result);
	$rownum = 0;

      while ($rownum < $numrows) {
           $projectid = mysql_result($result, $rownum, "projectid");
           $state = mysql_result($result, $rownum, "state");
           $name = mysql_result($result, $rownum, "nameofwork");
           $author = mysql_result($result, $rownum, "authorsname");
           $language = mysql_result($result, $rownum, "language");

           $res = mysql_query("SELECT count(fileid) AS totalpages FROM $projectid");
           $numpages = mysql_result($res,0,"totalpages");

	if ($rownum % 2 ) {
			$row_color = $theme['color_mainbody_bg'];
		} else {
			$row_color = $theme['color_navbar_bg'];
		}
		
      echo "<tr bgcolor='$row_color'>";
      echo "<td align='right'><a href = \"md_phase1.php?projectid=$projectid\">$name</a></td>\n";
      echo "<td align='right'>$author</td>\n";
      echo "<td align='right'>$numpages</td>\n";
//      echo "<td align='right'>#pages</td>\n";

      $rownum++;
      echo "</tr>";
	}

//echo "</table>";
echo "<br>";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";
		echo "<tr></tr>\n";


//Phase 2 table----------------------------------------------------------------------------------
//echo "<table border=1>\n";
      // Header row
		echo "<tr>\n";
		echo "    <td align='center' colspan='4'><b>Books Waiting for Phase 2 Review</b></td><tr></tr>\n";
            echo "    <td align='center' colspan='4'>In this phase we go through each page and annotate which pages contain footnotes, chapter headings, etc.</td><tr></tr>\n";
		echo "    <td align='center' colspan='1'><b>Title</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Author</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Total Pages</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Remaining Pages</b></td>\n";
		echo "</tr>\n";

      $result = mysql_query("SELECT projectid, nameofwork, authorsname, language, username, state FROM projects
                WHERE state = 'project_md_second'");
	$numrows = mysql_num_rows($result);
	$rownum = 0;

      while ($rownum < $numrows) {
           $projectid = mysql_result($result, $rownum, "projectid");
           $state = mysql_result($result, $rownum, "state");
           $name = mysql_result($result, $rownum, "nameofwork");
           $author = mysql_result($result, $rownum, "authorsname");
           $language = mysql_result($result, $rownum, "language");

           $res = mysql_query("SELECT count(fileid) AS totalpages FROM $projectid");
           $numpages = mysql_result($res,0,"totalpages");

           $res = mysql_query("SELECT count(fileid) AS totalpages FROM $projectid WHERE state = 'avail_md_second'");
           $availpages = mysql_result($res,0,"totalpages");



	if ($rownum % 2 ) {
			$row_color = $theme['color_mainbody_bg'];
		} else {
			$row_color = $theme['color_navbar_bg'];
		}
		
      echo "<tr bgcolor='$row_color'>";
      echo "<td align='right'><a href = \"md_phase2.php?projectid=$projectid\">$name</a></td>\n";
      echo "<td align='right'>$author</td>\n";
      echo "<td align='right'>$numpages</td>\n";
      echo "<td align='right'>$availpages</td>\n";

      $rownum++;
      echo "</tr>";
	}

echo "</table>";

echo "</center>";
echo "<br>";
theme("","footer");
?>
