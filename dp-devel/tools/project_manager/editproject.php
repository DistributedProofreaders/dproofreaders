<?

$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

function saveProject() {
  global $project, $clearance, $NameofWork, $AuthorsName, $comments, $Language;
  global $scannercredit, $txtlink, $ziplink, $htmllink, $pguser, $postednum; 

  if (isset($project) && strlen(trim($project)) > 0) {
    $sql = "UPDATE projects 
            SET clearance = '$clearance', NameofWork = '$NameofWork', 
                AuthorsName = '$AuthorsName', postednum = '$postednum', 
                comments = '$comments', Language = '$Language', 
                scannercredit = '$scannercredit', txtlink = '$txtlink', 
                ziplink = '$ziplink', htmllink = '$htmllink' 
            WHERE projectid = '$project'"; 

    mysql_query($sql);
  } else {
    $project = uniqid("projectID");

    $sql = "CREATE TABLE $project (
              fileid varchar(20) NOT NULL default '',
              image varchar(8) NOT NULL default '',
              master_text longtext NOT NULL,
              round1_text longtext NOT NULL,
              round2_text longtext NOT NULL,
              round1_user varchar(25) NOT NULL default '',
              round2_user varchar(25) NOT NULL default '',
              round1_time int(20) NOT NULL default '0',
              round2_time int(20) NOT NULL default '0',
              state tinyint(3) unsigned NOT NULL default '0'
            )";

    mysql_query($sql);

    mkdir ("../../projects/$project", 0777);
    chmod ("../../projects/$project", 0777);

    //update main projects table with new project info
    $sql = "INSERT INTO projects (NameofWork, AuthorsName, Language, username, 
                                  comments, projectid, scannercredit, state, 
                                  modifieddate, clearance) 
                        VALUES ('$NameofWork', '$AuthorsName', '$Language', 
                                '$pguser', '$comments', '$project', 
                                '$scannercredit', '0', UNIX_TIMESTAMP(),
                                '$clearance')";
    mysql_query($sql);
  }
}

if (isset($saveAndQuit)) {
  saveProject();
  echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php\">";
} else if (isset($quit)) {
  echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php\">";
} else {
  if (isset($saveAndPreview)) {
    saveProject();
  }

  if (isset($project)) {
    $sql = "SELECT nameofwork, authorsname, language, scannercredit, txtlink,
                   htmllink, ziplink, comments, postednum, clearance
            FROM projects 
            WHERE projectid = '$project'";

    $result = mysql_query($sql);

    $NameofWork = mysql_result($result, 0, "nameofwork");
    $AuthorsName = mysql_result($result, 0, "authorsname");
    $Language = mysql_result($result, 0, "language");
    $scannercredit = mysql_result($result, 0, "scannercredit");
    $txtlink = mysql_result($result, 0, "txtlink");
    $htmllink = mysql_result($result, 0, "htmllink");
    $ziplink = mysql_result($result, 0, "ziplink");
    $comments = mysql_result($result, 0, "comments");
    $clearance = mysql_result($result, 0, "clearance");
    $postednum = mysql_result($result, 0, "postednum");
  }

  if ($txtlink == "") $txtlink = "http://ibiblio.unc.edu/pub/docs/books/gutenberg/etext04/XXXXX10.txt";
  if ($ziplink == "") $ziplink = "http://ibiblio.unc.edu/pub/docs/books/gutenberg/etext04/XXXXX10.zip";
  if ($Language == "") $Language = "English";
?>

<html>
<head><title>Project Information</title></head>
<body>

<form method="POST" action="<? echo $_SERVER['PHP_SELF'] ?>">

<input type="hidden" name="project" value="<? echo $project ?>">

<table border="1">
<tr>
<td bgcolor="#CCCCCC"><b>Name of Work</b></td>
<td><input type ="text" size="67" name="NameofWork" value="<? echo $NameofWork ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Authors Name</b></td>
<td><input type="text" size="67" name="AuthorsName" value="<? echo $AuthorsName ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Language</b></td>
<td><input type="text" size="67" name="Language" value="<? echo $Language ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Image Scanner Credit</b></td>
<td><input type="text" size="67" name="scannercredit" value="<? echo $scannercredit ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Clearance Line</b></td>
<td><input type="text" size="67" name="clearance" value="<? echo $clearance ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Text File URL</b></td>
<td><input type="text" size="67" name="txtlink" value="<? echo $txtlink ?>"></td>
</tr>
<tr><td bgcolor="#CCCCCC"><b>Zip File URL</b></td>
<td><input type="text" size="67" name="ziplink" value="<? echo $ziplink ?>"></td>
</tr>
<tr><td bgcolor="#CCCCCC" width="200"><b>HTML File URL</b></td>
<td><input type="text" size="67" name="htmllink" value="<? echo $htmllink ?>"></td>
</tr>
<tr><td bgcolor="#CCCCCC" width="200"><b>Posted Number</b></td>
<td><input type="text" size="67" name="postednum" value="<? echo $postednum ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" colspan="2"><B>Comments</b></td>
</tr>
<tr><td colspan="2"><textarea name="comments" cols="74" rows="16"><? echo stripslashes($comments) ?></textarea></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" colspan="2" align="center">
<input type="submit" name="saveAndQuit" value="Save and Quit">
<input type="submit" name="saveAndPreview" value="Save and Preview">
<input type="submit" name="quit" value="Quit Without Saving">
</td>
</tr>
</table>

<p><b>Note:</b> The Image Scanner Credit is used when someone else provided you
with the page images/scans. This will allow the post-processors to include the
scanners name in the credits line if you send the project to Post-Processing.
For text and zip file links, replace the XXXXX with the proper characters sent
in the posted message.

<p>
</form>

<p>
<table border="1" width = "630">
<td bgcolor="#CCCCCC" align="center">&nbsp;</td>
<td bgcolor="#CCCCCC">
  <b>This is what the project comments will look like. After you make a change 
  and hit "Save and Preview" this will page refresh to display your changes.
  </b>
</td>
<tr>
<tr>
<td bgcolor="#CCCCCC"><h3>Project Comments</h3></td>
<td><? echo stripslashes($comments) ?></td>
</tr>
</table>

<p>
<table border="1" width="630">
<tr>
<td width="126" align="center" bgcolor ="#CCCCCC"><a href="projectmgr.php">Back</a></td>
<td width="126" bgcolor="#CCCCCC" align="center">Create Project</td>
<td width="126" bgcolor="#CCCCCC" align="center"><a href ="../proofers/proof_per.php">Proofread Project</a></td>
<td width="126" bgcolor="#CCCCCC" align="center"><a href ="deleteproject.php">Delete Project</a></td>
<td width="126" bgcolor="#CCCCCC" align="center"><a href ="../logout.php">Logout</a></td>
</tr>
</table>
</body>
</html>

<? } ?>
