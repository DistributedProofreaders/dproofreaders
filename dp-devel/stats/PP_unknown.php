<?
$relPath="../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

theme("Post-Processing Mysteries", "header");

if ($order == 'default') {
    $order ='nameofwork';
}


$ok_orders = array("nameofwork","authorsname","username","projectid","modifieddate");

if (! in_array ($order, $ok_orders)) {
	echo "Uh oh!" & exit();
}

echo "<br><br><h2>Post-Processing Mysteries</h2><br>\n";

echo "<br>\n";

echo _("We don't know for sure who PPd these books; if you do know, or if you did, please send email
	e-mail: ")."<a href='mailto:$general_help_email_addr'>$general_help_email_addr</a> "
	._("quoting the other information in the row, including the project ID. Thanks!")."<br><br>";

	
//get projects that have been PPd but we don't know by whom
$result = mysql_query("	SELECT nameofwork, authorsname, username, 
			projectid , from_unixtime(modifieddate) as 'LMDate'
			FROM projects WHERE state in ('"
				.PROJ_POST_SECOND_AVAILABLE."','"
				.PROJ_POST_SECOND_CHECKED_OUT."','"
				.PROJ_SUBMIT_PG_POSTED."','"
				.PROJ_CORRECT_AVAILABLE."','"
				.PROJ_CORRECT_CHECKED_OUT."')
			AND postproofer = 'No Known PPer' 
			ORDER BY '$order' ASC");

$numrows = mysql_numrows($result);
$rownum = 0;

echo "<table cols = \"6\" border =\"1\">";
echo "<td><b>Number</b></td>
      <td><b><a href =\"PP_unknown.php?order=nameofwork\">Title</b></td>
      <td><b><a href =\"PP_unknown.php?order=authorsname\">Author</b></td>
      <td><b><a href =\"PP_unknown.php?order=username\">Project Manager</b></td>
      <td><b><a href =\"PP_unknown.php?order=projectid\">Project ID</b></td>
      <td><b><a href =\"PP_unknown.php?order=modifieddate\">Date Last Modified</a></b></td><tr>";

$index = 0;
while ($rownum < $numrows) {
    $nameofwork = mysql_result($result, $rownum, "nameofwork");
    $author = mysql_result($result, $rownum, "authorsname");
    $username = mysql_result($result, $rownum, "username");
    $projectID = mysql_result($result, $rownum, "projectid");
    $modifieddate = mysql_result($result, $rownum, "LMDate");

    $rownum++;

    echo "<td>$rownum</td>
          <td width=\"200\">$nameofwork</td><td>$author</td><td>$username</td><td>$projectID</td><td>$modifieddate</td><tr>";
}

echo "</table>";
theme("","footer");
?>
