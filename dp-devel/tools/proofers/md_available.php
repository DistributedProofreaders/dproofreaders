<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
//include_once($relPath.'project_edit.inc');
//$projectinfo = new projectinfo();
//include_once('projectmgr.inc');
//include_once('page_table.inc');

$no_stats=1;
theme("Image Metadata Collection", "header");


//---------------------------------------------------------------------------------------------------

echo "<table border=1>\n";


	// Top header row
		echo "<tr>\n";
		echo "    <td align='center' colspan='4'><b>Books Waiting for Image Review</b></td><tr></tr>\n";
		echo "    <td align='center' colspan='1'><b>Title</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Author</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Total Pages</b></td>\n";
		echo "    <td align='center' colspan='1'><b>Remaining Pages</b></td>\n";
		echo "</tr>\n";

$result = mysql_query("SELECT nameofwork, authorsname, language, username, state FROM projects");

	$numrows = mysql_num_rows($result);
	$rownum = 0;

    while ($rownum2 < $numrows) {
$manager = mysql_result($result, $rownum, "username");
$state = mysql_result($result, $rownum, "state");
$name = mysql_result($result, $rownum, "nameofwork");
$author = mysql_result($result, $rownum, "authorsname");
$language = mysql_result($result, $rownum, "language");

	if ($rownum % 2 ) {
			$row_color = $theme['color_mainbody_bg'];
		} else {
			$row_color = $theme['color_navbar_bg'];
		}
		echo "<tr bgcolor='$row_color'>";




echo "<td align='right'>$name</td>\n";
echo "<td align='right'>$author</td>\n";
echo "<td align='right'>#pages</td>\n";
echo "<td align='right'>#pages</td>\n";

    $rownum++;
    $rownum2++;
    echo "</tr>";
	}
	echo "</table>";


echo "</center>";

echo "<br>";
theme("","footer");
?>
