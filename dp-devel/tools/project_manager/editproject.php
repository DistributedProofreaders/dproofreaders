<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'maybe_mail.inc');

// Encodes a form parameter to allow it to contain double quotes.
function encodeFormValue($value) {
  return stripslashes(htmlentities($value));
}

function saveProject() {
  global $project, $clearance, $NameofWork, $AuthorsName, $comments, $Language;
  global $scannercredit, $txtlink, $ziplink, $htmllink, $pguser, $postednum, $genre; 
  global $projects_dir;

  $errormsg;

  if (strlen(trim($NameofWork)) == 0) {
    $errormsg .= "Name of work is required.<br>";
  }

  if (strlen(trim($AuthorsName)) == 0) {
    $errormsg .= "Author is required.<br>";
  }

  if (strlen(trim($Language)) == 0) {
    $errormsg .= "Language is required.<br>";
  }

  if (isset($errormsg)) {
    return $errormsg;
  }

  if (isset($project) && strlen(trim($project)) > 0) {
    $sql = "UPDATE projects 
            SET clearance = '$clearance', NameofWork = '$NameofWork', 
                AuthorsName = '$AuthorsName', postednum = '$postednum', 
                comments = '$comments', Language = '$Language', 
                scannercredit = '$scannercredit', txtlink = '$txtlink', 
                ziplink = '$ziplink', htmllink = '$htmllink',
		genre = '$genre'
            WHERE projectid = '$project'"; 

    mysql_query($sql);
  } else {
    $project = uniqid("projectID");

    $sql = "CREATE TABLE $project (
              fileid varchar(20) NOT NULL default '', UNIQUE (fileid),
              image varchar(8) NOT NULL default '',   UNIQUE (image),
              master_text longtext NOT NULL,
              round1_text longtext NOT NULL,
              round2_text longtext NOT NULL,
              round1_user varchar(25) NOT NULL default '',
              round2_user varchar(25) NOT NULL default '',
              round1_time int(20) NOT NULL default '0',
              round2_time int(20) NOT NULL default '0',
              state VARCHAR(50) NOT NULL default '',
	      b_user VARCHAR(25) NOT NULL default '',
	      b_code INT(1) NOT NULL default ''
            )";

    mysql_query($sql);

    mkdir ("$projects_dir/$project", 0777);
    chmod ("$projects_dir/$project", 0777);

    //update main projects table with new project info
    $sql = "INSERT INTO projects (NameofWork, AuthorsName, Language, username, 
                                  comments, projectid, scannercredit, state, 
                                  modifieddate, clearance, genre) 
                        VALUES ('$NameofWork', '$AuthorsName', '$Language', 
                                '$pguser', '$comments', '$project', 
                                '$scannercredit', '".PROJ_NEW."', UNIX_TIMESTAMP(),
                                '$clearance', '$genre')";
    mysql_query($sql);
  }

  return ""; // An empty string indicates no error 
}


if (isset($posted) && (isset($saveAndQuit) || isset($quit))) {

        $result = mysql_query("SELECT username FROM usersettings WHERE value = '$project' AND setting = 'posted_notice'");
        $numrows = mysql_numrows($result);
        $rownum = 0;

        while ($rownum < $numrows) {
            $username = mysql_result($result, $rownum, "username");
            $temp = mysql_query("SELECT user_email FROM phpbb_users WHERE username = '$username'");
            $email = mysql_result($temp, 0, "user_email");

            maybe_mail($email, "$NameofWork Posted to Project Gutenberg",
"You had requested to be let known once $NameofWork was ready to be available for reading and it is now available. Download the file at $ziplink and enjoy!\n\n
--\n
Distributed Proofreaders\n$code_url/\n\nThis is an automated message that you had requested, please do not respond directly to this e-mail",
             "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
            $rownum++;
        }
        $del = mysql_query("DELETE FROM usersettings WHERE value = '$project' AND setting = 'posted_notice'");
        $ins = mysql_query("UPDATE projects SET int_level = '$numrows' WHERE project = '$projectid'");

}


if (isset($saveAndQuit)) {
  $errormsg = saveProject();

  if (strlen($errormsg) == 0) {
    metarefresh(0, "projectmgr.php", "Save and Quit", "");
    exit();
  }
} else if (isset($quit)) {
  metarefresh(0, "projectmgr.php", "Quit without Saving", "");
  exit();
} 

if (isset($saveAndPreview)) {
  $errormsg = saveProject();
}

if ((!isset($errormsg) || strlen($errormsg) == 0) 
    && isset($project) && strlen($project) > 0) {
  $sql = "SELECT nameofwork, authorsname, language, scannercredit, txtlink,
                 htmllink, ziplink, comments, postednum, clearance, genre
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
  $genre = mysql_result($result, 0, "genre");
}

if ($txtlink == "") $txtlink = "http://ibiblio.unc.edu/pub/docs/books/gutenberg/etext05/XXXXX10.txt";
if ($ziplink == "") $ziplink = "http://ibiblio.unc.edu/pub/docs/books/gutenberg/etext05/XXXXX10.zip";
if ($Language == "") $Language = "English";
if ($comments == "" ) $comments = "<p>Refer to the <a href=\"$code_url/faq/document.php\">Proofing Guidelines</a>.</p>";

theme("Create a Project", "header");
?>
<br>
<form method="POST" action="<? echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="project" value="<? echo $project ?>">

<? if (isset($errormsg) && strlen($errormsg) > 0) { ?>
  <font color="red"><? echo $errormsg ?></font>
<? } ?>

<table border="1">
<? if(isset($project)) { ?>
<tr>
<td bgcolor="#CCCCCC"><b>Project ID</b></td>
<td><? print $project; ?></td>
</tr>
<? } ?>
<? if(isset($posted)) { ?><input type="hidden" name=posted value=1><? } ?>
<tr>
<td bgcolor="#CCCCCC"><b>Name of Work</b></td>
<td><input type ="text" size="67" name="NameofWork" value="<? echo encodeFormValue($NameofWork) ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Author's Name</b></td>
<td><input type="text" size="67" name="AuthorsName" value="<? echo encodeFormValue($AuthorsName) ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Language</b></td>
<td><input type="text" size="67" name="Language" value="<? echo encodeFormValue($Language) ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Genre</b></td>
<td><input type="text" size="67" name="genre" value="<? echo encodeFormValue($genre) ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Image Scanner Credit</b></td>
<td><input type="text" size="67" name="scannercredit" value="<? echo encodeFormValue($scannercredit) ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Clearance Information</b></td>
<td><textarea cols="67" rows="2" name="clearance"><? echo encodeFormValue($clearance) ?></textarea></td>
</tr>
<tr>
<td bgcolor="#CCCCCC"><b>Text File URL</b></td>
<td><input type="text" size="67" name="txtlink" value="<? echo encodeFormValue($txtlink) ?>"></td>
</tr>
<tr><td bgcolor="#CCCCCC"><b>Zip File URL</b></td>
<td><input type="text" size="67" name="ziplink" value="<? echo encodeFormValue($ziplink) ?>"></td>
</tr>
<tr><td bgcolor="#CCCCCC" width="200"><b>HTML File URL</b></td>
<td><input type="text" size="67" name="htmllink" value="<? echo encodeFormValue($htmllink) ?>"></td>
</tr>
<tr><td bgcolor="#CCCCCC" width="200"><b>Posted Number</b></td>
<td><input type="text" size="67" name="postednum" value="<? echo encodeFormValue($postednum) ?>"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" colspan="2"><B>Comments</b></td>
</tr>
<tr><td colspan="2"><textarea name="comments" cols="74" rows="16"><? echo encodeFormValue($comments) ?></textarea></td>
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
<td><? echo stripslashes($comments); ?></td>
</tr>
</table>
<br>
<?
theme("", "footer");
?>

