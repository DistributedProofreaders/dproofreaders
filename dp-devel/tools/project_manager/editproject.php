<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

    $todaysdate = time();

    $projectid = $_POST['project'];
    $NameofWork = $_POST['NameofWork'];
    $AuthorsName = $_POST['AuthorsName'];
    $Language = $_POST['Language'];
    $scannercredit = $_POST['scannercredit'];
    $txtlink = $_POST['txtlink'];
    $htmllink = $_POST['htmllink'];
    $ziplink = $_POST['ziplink'];
    $comments = $_POST['comments'];
    $clearance = $_POST['clearance'];
    $postednum = $_POST['postednum'];
    $action = $_POST['action'];
    $button1 = $_POST['button1'];
    $button2 = $_POST['button2'];
    $button3 = $_POST['button3'];

    if (($button3 != "") && ($action == "new")) {
        $projectid = uniqid("projectID");

        $sql = mysql_query("CREATE TABLE $projectid (
  fileid varchar(20) NOT NULL default '',
  image varchar(8) NOT NULL default '',
  master_text longtext NOT NULL,
  round1_text longtext NOT NULL,
  round2_text longtext NOT NULL,
  round1_user varchar(25) NOT NULL default '',
  round2_user varchar(25) NOT NULL default '',
  round1_time int(20) NOT NULL default '0',
  round2_time int(20) NOT NULL default '0',
  state tinyint(3) unsigned NOT NULL default '0')");

        mkdir ("../../projects/$projectid", 0777);
        chmod ("../../projects/$projectid", 0777);

        //update main projects table with new project info
        $sql = "INSERT INTO projects (NameofWork, AuthorsName, Language, username, comments, projectid, scannercredit, state, 
modifieddate, clearance) VALUES ('$NameofWork','$AuthorsName','$Language','$pguser','$comments','$projectid', 
'$scannercredit', '0', '$todaysdate','$clearance')";


        $result = mysql_query($sql);
        echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"30 ;URL=projectmgr.php\">";

    } else if (($button3 != "") && ($action == "update")) {
        $sql = mysql_query("UPDATE projects SET clearance = '$clearance', NameofWork = '$NameofWork', AuthorsName = '$AuthorsName', postednum = '$postednum', comments = '$comments', Language = '$Language', scannercredit = '$scannercredit', txtlink = '$txtlink', ziplink = '$ziplink', htmllink = '$htmllink' WHERE projectid = '$projectid'"); 
        echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php\">";
    } else if ($button1 != "") {
        echo "You chose to not save.";
        echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php\">";
    } else {
        $temp = $projectid;
        if ($button2 != "") {
            $sql = mysql_query("UPDATE projects SET clearance = '$clearance', NameofWork = '$NameofWork', 
                              AuthorsName = '$AuthorsName', postednum = '$postednum', comments = '$comments', 
                              Language = '$Language', scannercredit = '$scannercredit', txtlink = '$txtlink', 
                              ziplink = '$ziplink', htmllink = '$htmllink', modifieddate = '$todaysdate' 
                              WHERE projectid = '$projectid'");
        }
        $projectid = $_GET['project'];
        if ($projectid != "") { 
            $sql = mysql_query("SELECT nameofwork, authorsname, language, scannercredit, txtlink, htmllink, ziplink, comments, postednum, clearance FROM projects WHERE projectid = '$projectid'");
            $NameofWork = mysql_result($sql, 0, "nameofwork");
            $AuthorsName = mysql_result($sql, 0, "authorsname");
            $Language = mysql_result($sql, 0, "language");
            $scannercredit = mysql_result($sql, 0, "scannercredit");
            $txtlink = mysql_result($sql, 0, "txtlink");
            $htmllink = mysql_result($sql, 0, "htmllink");
            $ziplink = mysql_result($sql, 0, "ziplink");
            $comments = mysql_result($sql, 0, "comments");
            $clearance = mysql_result($sql, 0, "clearance");
            $postednum = mysql_result($sql, 0, "postednum");
        }
        if ($txtlink == "") $txtlink = "http://ibiblio.unc.edu/pub/docs/books/gutenberg/etext04/XXXXX10.txt";
        if ($ziplink == "") $ziplink = "http://ibiblio.unc.edu/pub/docs/books/gutenberg/etext04/XXXXX10.zip";
        if ($Language == "") $Language = "English";
?>
<html>


<head><title>Project Information</title></head>
<body>

<FORM METHOD="POST" ACTION="editproject.php">
<table border=1>
<?
echo "<tr><td bgcolor=CCCCCC><b>Name of Work</b></td><td><input type =\"text\" size=67 name =\"NameofWork\" value=\"$NameofWork\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC><b>Authors Name</b></td><td><input type = \"text\" size=67 name =\"AuthorsName\" value=\"$AuthorsName\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC><b>Language</b></td><td><input type = \"text\" size=67 name =\"Language\" value=\"$Language\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC><b>Image Scanner Credit</b></td><td><input type = \"text\" size=67 name =\"scannercredit\" value=\"$scannercredit\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC><b>Clearance Line</b></td><td><input type = \"text\" size=67 name =\"clearance\" value=\"$clearance\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC><b>Text File URL</b></td><td><input type = \"text\" size=67 name =\"txtlink\" value=\"$txtlink\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC><b>Zip File URL</b></td><td><input type = \"text\" size=67 name=\"ziplink\" value=\"$ziplink\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC width=200><b>HTML File URL</b></td><td><input type = \"text\" size=67 name=\"htmllink\" value=\"$htmllink\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC width=200><b>Posted Number</b></td><td><input type = \"text\" size=67 name=\"postednum\" value=\"$postednum\"></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC colspan=2><B>Comments</b></td></tr>\n";
echo "<tr><td colspan=2><textarea name=\"comments\" COLS=74 ROWS=16>$comments</textarea></td></tr>\n";
echo "<tr><td bgcolor=CCCCCC colspan=2 align=center>";
echo "<INPUT TYPE=\"Submit\" VALUE=\"Save and Quit\" name=\"button3\">";
echo "<INPUT TYPE=\"Submit\" VALUE=\"Save and Preview\" name=\"button2\">";
echo "<INPUT TYPE=\"Submit\" VALUE=\"Quit Without Saving\" name=\"button1\">";
echo "</td></tr>\n";
?>
</table>

<p><b>Note:</b> The Image Scanner Credit is used when someone else provided you with the page images/scans. This will allow the post-processors to include the scanners name in the credits line if you send the project to 
Post-Processing. For text and zip file links, replace the XXXXX with the proper characters sent in the posted message.
<P>
<?
if ($projectid == "") {
?>
<input type="hidden" name="action" value="new">
<?
} else {
echo "<input type=\"hidden\" name=\"project\" value=\"$projectid\">";
echo "<input type=\"hidden\" name=\"action\" value=\"update\">";
}
?>
</FORM>
<?
            // display what the current comments look like.
            echo "<p>";
            echo "<table border=1 width = \"630\">\n";
            echo "<td bgcolor = \"CCCCCC\" align = \"center\">&nbsp;</td><td bgcolor = \"CCCCCC\"><b>This is what the project comments will look like. After you make
a change and hit \"Save and Preview\" this will page refresh to display your changes.</b></td><tr>";
            echo "<tr><td bgcolor = \"CCCCCC\"><h3>Project Comments</h3></td><td>$comments</td>";
            echo "</table>\n";
?>
<P>
    <table border=1 width=630><tr>
    <td width=126 align=center bgcolor ="CCCCCC"><a href="projectmgr.php">Back</a></td>
    <td width=126 bgcolor = "CCCCCC" align=center>Create Project</td>
    <td width=126 bgcolor = "CCCCCC" align=center><a href ="../proofers/proof_per.php">Proofread Project</a></td>
    <td width=126 bgcolor = "CCCCCC" align=center><a href ="deleteproject.php">Delete Project</a></td>
    <td width=126 bgcolor = "CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
    </tr></table>
</body></html>
<?
    }
?>

