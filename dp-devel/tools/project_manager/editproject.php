<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'marc_format.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'maybe_mail.inc');

$popHelpDir="$code_url/faq/pophelp/project_manager/";
include_once($relPath.'js_newpophelp.inc');

function encodeFormValue($value) {
  return htmlspecialchars($value,ENT_QUOTES);
}

// Because we have PHP configured with magic_quotes_gpc On,
// values from $_POST come with backslashes added. So don't pass
// them to addslashes(), or the result will have double-backslashes.

function saveProject() {
	global $projects_dir, $uploads_dir, $pguser;

	//Let's check to make sure everything is correct & there are no errors
	if (empty($_POST['nameofwork'])) { $errormsg .= "Name of work is required.<br>"; }
	if (empty($_POST['authorsname'])) { $errormsg .= "Author is required.<br>"; }
	if (empty($_POST['pri_language'])) { $errormsg .= "Primary Language is required.<br>"; }
	if (empty($_POST['genre'])) { $errormsg .= "Genre is required.<br>"; }
        if (!empty($_FILES['projectfiles']['name'])) { if(substr($_FILES['projectfiles']['name'], -4) != ".zip") { $errormsg .= "File type must be ZIP.<br."; } }
	if (!empty($_FILES['projectfiles']['name'])) { $dir_name = substr($_FILES['projectfiles']['name'], 0, strpos($_FILES['projectfiles']['name'], ".zip")); if (file_exists("$uploads_dir/$pguser/$dir_name")) { $errormsg .= "The name of the zip file must be unique.<br>"; } }
	if (isset($errormsg)) {
		return $errormsg;
		exit();
	}

	//Format the language as pri_language with sec_language if pri_language is set
	//Otherwise set just the pri_language
	if (!empty($_POST['sec_language'])) { $language = $_POST['pri_language']." with ".$_POST['sec_language']; } else { $language = $_POST['pri_language']; }

	//If we are just updated an already existing project
	if (isset($_POST['projectid'])) {
		//Update the projects database with the updated info
		mysql_query("
			UPDATE projects SET
				nameofwork='{$_POST['nameofwork']}',
				authorsname='{$_POST['authorsname']}',
				language='$language',
				genre='{$_POST['genre']}',
				difficulty='{$_POST['difficulty_level']}',
				comments='{$_POST['comments']}',
				scannercredit='{$_POST['scannercredit']}',
				txtlink='{$_POST['txtlink']}',
				ziplink='{$_POST['ziplink']}',
				htmllink='{$_POST['htmllink']}',
				postednum='{$_POST['postednum']}',
				clearance='{$_POST['clearance']}'
			WHERE projectid='{$_POST['projectid']}'
		");

		$projectid = $_POST['projectid'];

		handle_projectfiles($projectid);

		//Update the MARC record in the database
		$result = mysql_query("
			SELECT updated_array
			FROM marc_records
			WHERE projectid = '{$_POST['projectid']}'
		"); //Pull the current MARC record array from the database
		$rec = unserialize(base64_decode(mysql_result($result,0,"updated_array"))); //Get the updated_marc array field
		$updated_array = update_marc_db($rec); //Update the MARC record array in the database
		$updated_marc = convert_standard_marc($updated_array); //Convert the updated array to a marc
		mysql_query("
			UPDATE marc_records
			SET updated_marc = '".base64_encode(serialize($updated_marc))."'
			WHERE projectid = '{$_POST['projectid']}'
		"); //Update the database with the updated marc

		//Lastly, let's update the Dublin Core file
		create_dc_xml_oai($_POST['projectid'], $_POST['scannercredit'], $_POST['genre'], $language, $_POST['authorsname'], $_POST['nameofwork'], $updated_array);
	} else {
		global $projectid;

		$projectid = uniqid("projectID"); //The project ID
		$rec = unserialize(base64_decode($_POST['rec'])); //Decode the marc record

		//Insert a new row into the projects table
		mysql_query("
			INSERT INTO projects
				(nameofwork, authorsname, language, genre, difficulty, username, comments, projectid, modifieddate, scannercredit, state, clearance)
			VALUES (
				'{$_POST['nameofwork']}',
				'{$_POST['authorsname']}',
				'$language',
				'{$_POST['genre']}',
				'{$_POST['difficulty_level']}',
				'{$GLOBALS['pguser']}',
				'{$_POST['comments']}',
				'$projectid',
				UNIX_TIMESTAMP(),
				'{$_POST['scannercredit']}',
				'".PROJ_NEW."',
				'{$_POST['clearance']}'
			)
		");

		//Create a table for this project
		mysql_query("
			CREATE TABLE $projectid (
				fileid varchar(20) NOT NULL default '',
				UNIQUE (fileid), image varchar(8) NOT NULL default '',
				UNIQUE (image),
				master_text longtext NOT NULL,
				round1_text longtext NOT NULL,
				round2_text longtext NOT NULL,
				round1_user varchar(25) NOT NULL default '',
				round2_user varchar(25) NOT NULL default '',
				round1_time int(20) NOT NULL default '0',
				round2_time int(20) NOT NULL default '0',
				state VARCHAR(50) NOT NULL default '',
				INDEX(state),
				b_user VARCHAR(25) NOT NULL default '',
				b_code INT(1) NOT NULL default '',
				 metadata SET('blank','missing','badscan','outofseq','acknowledge','dedication','ednotes','foreword',
				'abbreviation','intro','loi','preface','prologue','toc',
				'titlepage','division','epigraph','footnote',
				'illustration','letter','list','math','poetry',
				'sidenote','verse','table','appendix','afterword',
				'biblio','colophon','endnote','epilogue','index')
				NOT NULL default '',
				orig_page_num VARCHAR(6) NOT NULL default ''
			)
		");

		//Make a directory in the projects_dir for this project
		mkdir("$projects_dir/$projectid", 0777);
		chmod("$projects_dir/$projectid", 0777);

		handle_projectfiles( $projectid );

		//Add the original marc record to the database
		$original_marc = convert_standard_marc($rec);
		mysql_query("
			INSERT INTO marc_records
				(projectid, original_marc, original_array)
			VALUES (
				'$projectid',
				'".base64_encode(serialize($original_marc))."',
				'".base64_encode(serialize($rec))."'
			)
		");

		//Update the marc database with any changes we've recieved
		$updated_array = update_marc_db($rec);

		//Add the update marc record to the database
		$updated_marc = convert_standard_marc($updated_array);
		mysql_query("
			UPDATE marc_records
			SET updated_marc = '".base64_encode(serialize($updated_marc))."'
			WHERE projectid = '$projectid'
		");

		//Create a Dublin Core file in the projects_dir directory
		create_dc_xml_oai($projectid, $_POST['scannercredit'], $_POST['genre'], $language, $_POST['authorsname'], $_POST['nameofwork'], $updated_array);
	}

	//If the project has been posted to PG let the users know
	if(isset($_POST['posted'])) { posted_pg($_POST['projectid']); }
}

function handle_projectfiles($projectid) {
	// If the PM uploaded a zip file, unzip it and put the files in the uploads and projects directories.
	global $uploads_dir, $pguser, $projects_dir;

	$original_filename = $_FILES['projectfiles']['name'];
	if (empty($original_filename)) {
		return; // No file uploaded.
	}

	$dir_name = substr($original_filename, 0, strpos($original_filename, ".zip"));

	if (!file_exists("$uploads_dir/$pguser")) {
		# echo "creating $uploads_dir/$pguser ...<br>\n";
		mkdir("$uploads_dir/$pguser", 0777);
		chmod("$uploads_dir/$pguser", 0777);
	}

	# echo "creating $uploads_dir/$pguser/$dir_name ...<br>\n";
	mkdir("$uploads_dir/$pguser/$dir_name", 0777);
	chmod("$uploads_dir/$pguser/$dir_name", 0777);

	$local_zipfile = $_FILES['projectfiles']['tmp_name'];

	# echo "unzipping to $projects_dir/$projectid ...<br>\n";
	exec("unzip -o -j $local_zipfile -d $projects_dir/$projectid");

	# echo "unzipping to $uploads_dir/$pguser/$dir_name ...<br>\n";
	exec("unzip -o -j $local_zipfile -d $uploads_dir/$pguser/$dir_name");

	# echo "insertTextFiles($dir_name, $projectid) ...<br>\n";
	insertTextFiles($dir_name, $projectid);

	$error_msg = project_transition( $projectid, PROJ_PROOF_FIRST_UNAVAILABLE );
	if ($error_msg)
	{
		echo "$error_msg<br>\n";
	}
}

function insertTextFiles($dir_name, $projectid) {
	global $uploads_dir, $projects_dir, $pguser;
	$r = chdir("$uploads_dir/$pguser/$dir_name");
	$now = time();

	foreach (glob("*.txt") as $txt_file_name) {
		$file_base = basename(strval($txt_file_name),'.txt');
		$image_file_name = addslashes("$file_base.png");
		$txt_file_path = addslashes("$uploads_dir/$pguser/$dir_name/$txt_file_name");
		$sql_command = "
			INSERT INTO $projectid
			SET
				fileid      = '$file_base',
				image       = '$image_file_name',
				master_text = LOAD_FILE('$txt_file_path'),
				round1_time = $now,
				state       = '".AVAIL_FIRST."'
			";
		$res = mysql_query($sql_command) or die(mysql_error());
	}

	$r = chdir("$projects_dir/$projectid");
	foreach (glob("*.txt") as $txt_file_name) {
		unlink($txt_file_name);
	}
}

function checkProjectDirEmpty() {
	global $projectid, $projects_dir;
	$i = 0;

	$dir = opendir("$projects_dir/$projectid");
	while (false !== ($file = readdir($dir))) {
		if ($i > 0) { break; }
        	if ($file != "." && $file != ".." && $file != "dc.xml") {
        		$i++;
		}
	}
	closedir($dir);

	if ($i > 0) { return false; } else { return true; }
}

function posted_pg($projectid) {
	global $code_url, $auto_email_addr, $auto_email_addr;

	$result = mysql_query("SELECT nameofwork, ziplink FROM projects WHERE projectid = '$projectid'");
	$NameofWork = mysql_result($result, 0, "nameofwork");
	$ziplink = mysql_result($result, 0, "ziplink");

	if(substr($ziplink, -7, 1) == "X") { $ziplink = "http://www.pgdp.net/c/list_etexts.php?x=g"; }

	$result = mysql_query("SELECT username FROM usersettings WHERE value = '$projectid' AND setting = 'posted_notice'");
        $numrows = mysql_numrows($result);
        $rownum = 0;

        while ($rownum < $numrows) {
		$username = mysql_result($result, $rownum, "username");
		$temp = mysql_query("SELECT user_email FROM phpbb_users WHERE username = '$username'");
		$email = mysql_result($temp, 0, "user_email");
		maybe_mail(
			$email,
			"$NameofWork Posted to Project Gutenberg",
			"You had requested to be let known once $NameofWork was ready to be available for reading."
			." It has been sent to Project Gutenberg and will soon be available for reading."
			." Most files will be ready by the time you receive this mail;"
			." sometimes there may be a delay of a day or so."
			." Download the file at $ziplink and enjoy!"
			."\n"
			."\n"
			."--"
			."\n"
			."Distributed Proofreaders"
			."\n"
			."$code_url"
			."\n"
			."\n"
			."This is an automated message that you had requested,"
			." please do not respond directly to this e-mail.",
			"From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n"
		);
            	$rownum++;
        }

	$del = mysql_query("DELETE FROM usersettings WHERE value = '$projectid' AND setting = 'posted_notice'");
        $ins = mysql_query("UPDATE projects SET int_level = '$numrows' WHERE projectid = '$projectid'");
}

function previewProject($nameofwork, $authorsname, $comments) {
	global $relPath;

	$template_count = substr_count($comments, "[template=");
	if (!empty($template_count)) {
		$i = 1;
		while ($i <= $template_count) {
			$comments_backup = $comments;
			$comments = substr($comments_backup, 0, strpos($comments_backup, "[template="))."<br>";
			$comments .= file_get_contents($relPath."templates/comment_files/".substr($comments_backup, (strpos($comments_backup, "[template=")+10), 8));
			$comments .= "<br>".substr($comments_backup, (strpos($comments_backup, ".txt]")+5));
			$i++;
		}
	}

	echo "<br><table width='90%' border=1>";
	echo "<tr><td align='middle' bgcolor='#cccccc'><h3>Preview<br>Project</h3></td>";
	echo "<td bgcolor='#cccccc'><b>This is a preview of your project and exactly how it will look to the proofreaders.</b></td></tr>";
	echo "<tr><td align='middle' bgcolor='#cccccc'><b>Title</b></td><td>$nameofwork</td></tr>";
  	echo "<tr><td align='middle' bgcolor='#cccccc'><b>Author</b></td><td>$authorsname</td></tr>";
  	echo "<tr><td align='middle' bgcolor='#cccccc'><b>Project Manager</b></td><td>".$GLOBALS['pguser']."</td></tr>";
  	echo "<tr><td align='middle' bgcolor='#cccccc'><b>Last Proofread</b></td><td>".date("l, F jS, Y")." at ".date("g:i:sA")."</td></tr>";
  	echo "<tr><td align='middle' bgcolor='#cccccc'><b>Forum</b></td><td>Start a discussion about this project</td></tr>";
  	echo "<tr><td align='middle' bgcolor='#cccccc'><b>Book Completed</b></td><td>Yes, I would like to be notified when this has been posted to Project Gutenberg.</td></tr>";
	echo "<tr><td colspan=2>$comments</td></tr></table><br><br>";
}

function language_list($language) {
	include_once($GLOBALS['relPath'].'iso_lang_list.inc');

	if (strpos($language, "with") > 0) {
		$pri_language = trim(substr($language, 0, strpos($language, "with")));
		$sec_language = trim(substr($language, (strpos($language, "with")+5)));
	} else {
		$pri_language = $language;
		$sec_language = '';
	}

	$array_list = $GLOBALS['lang_list'];

	echo "<tr><td bgcolor='#CCCCCC'><b>Language</b></td><td><select name='pri_language'>";
	echo "<option value=''>Primary Language</option>";
	for ($i=0;$i<count($array_list);$i++)  {
		echo "<option value='".encodeFormValue($array_list[$i]['lang_name'])."'";
		if ($pri_language == $array_list[$i]['lang_name']) { echo " SELECTED"; }
		echo ">".$array_list[$i]['lang_name']."</option>";
		}
	echo "</select>&nbsp;&nbsp;<select name='sec_language'>";
	echo "<option value=''>Secondary Language</option>";
	for ($i=0;$i<count($array_list);$i++)  {
		echo "<option value='".encodeFormValue($array_list[$i]['lang_name'])."'";
		if ($sec_language == $array_list[$i]['lang_name']) { echo " SELECTED"; }
		echo ">".$array_list[$i]['lang_name']."</option>";
	}
	echo "</select></td></tr>";
}

function genre_list($genre) {
	$array_list = array('Art', 'Autobiography', 'Biography', 'Comedy', 'Comic Strip', 'Cooking', 'Drama', 'Essay', 'Fiction', 'Geography', 'Grammar', 'Historical', 'History', 'Humor', 'Letter', 'Linguistics', 'Math', 'Medicine', 'Mixed Form', 'Music', 'Non Fiction', 'Novel', 'Periodical', 'Philosophy', 'Poetry', 'Religious', 'Romance', 'Science', 'Satire', 'Short Story', 'Speech', 'Travel', 'Unknown');
	echo "<tr><td bgcolor='#CCCCCC'><b>Genre</b></td><td><select name='genre'>";
	for ($i=0;$i<count($array_list);$i++)  {
		echo "<option value='".encodeFormValue($array_list[$i])."'";
		if ($genre == $array_list[$i]) { echo " SELECTED"; }
		echo ">$array_list[$i]</option>";
		}
	echo "</select></td></tr>";
}

function difficulty_list($difficulty_level) {
        global $pguser;
	$array_list = array('Beginner', 'Easy', 'Average', 'Hard');
	echo "<tr><td bgcolor='#CCCCCC'><b>Difficulty Level</b></td><td>";
        $result = mysql_query("SELECT * FROM users WHERE username = '$pguser'");
        if (mysql_result($result,0,"sitemanager") == "yes") $sa = 1; else $sa = 0;
        // only show the beginner level to the BEGIN PM or SiteAdmins
	// don't let garvint create EASY projects
	for ($i=0;$i<count($array_list);$i++)  {
                if (($i > 0) || ($pguser == "BEGIN") || ($sa)) {
		   if (!($pguser == "garvint" && $i == 0)) {
	 		  echo "<input type='radio' name='difficulty_level' value='".encodeFormValue(strtolower($array_list[$i]))."'";
			  if (strtolower($difficulty_level) == strtolower($array_list[$i])) { echo " CHECKED"; }
			  echo ">$array_list[$i]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		   }
                }
	}
	echo "</td></tr>";
}

function query_format() {
	$attr_set = 0;
	$fullquery = "";

	if ($_POST['title']) {
		$fullquery = $fullquery.' @attr 1=4 "'.$_POST['title'].'"';
		$attr_set++;
	}
	if ($_POST['author']) {
		$author = $_POST['author'];
		if (stristr($_POST['author'], ",")) {
			$author = $_POST['author'];
		} else {
			if (stristr($_POST['author'], " ")) { $author = substr($_POST['author'], strrpos($_POST['author'], " ")).", ".substr($_POST['author'], 0, strrpos($_POST['author'], " ")); }
		}
		$fullquery = $fullquery.' @attr 1=1003 "'.trim($author).'"';
		$attr_set++;
	}
	if ($_POST['isbn']) {
		$fullquery = $fullquery.' @attr 2=3 @attr 1=7 '.str_replace("-", "", $_POST['isbn']).'';
		$attr_set++;
	}
	if ($_POST['issn']) {
		$fullquery = $fullquery.' @attr 2=3 @attr 1=8 '.$_POST['issn'].'';
		$attr_set++;
	}
	if ($_POST['lccn']) {
		$fullquery = $fullquery.' @attr 2=3 @attr 1=9 '.$_POST['lccn'].'';
		$attr_set++;
	}
	if ($_POST['pubdate']) {
		$fullquery = $fullquery.' @attr 2=3 @attr 1=31 '.$_POST['pubdate'].'';
		$attr_set++;
	}
	if ($_POST['publisher']) {
		$fullquery = $fullquery.' @attr 1=1018 "'.$_POST['publisher'].'"';
		$attr_set++;
	}
	for ($i = 1; $i <= ($attr_set - 1); $i++) {
		$fullquery = "@and ".$fullquery;
	}
	return $fullquery;
}

// End of function definitions

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "marc_search") {
	theme("Search Results", "header");
	if (empty($_GET['start'])) { $start = 1; } else { $start = $_GET['start']; }
	if (!empty($_GET['fq'])) { $fullquery = unserialize(base64_decode($_GET['fq'])); } else { $fullquery = query_format(); }

	$id = yaz_connect("z3950.loc.gov:7090/Voyager");
	yaz_syntax($id, "usmarc");
	yaz_element($id, "F");
	yaz_search($id, "rpn", trim($fullquery));
	yaz_wait(array("timeout" => 60));
	$errorMsg = yaz_error($id);

	if (!empty($errorMsg)) {
		echo "<br><center>The following error has occured.  Please try again:<br><br><b><i>$errorMsg</i></b>";
		echo "<p>If this problem occurs again please create your project manually by following this <a href='editproject.php?action=createnew'>link</a>.</center>";
		theme("", "footer");
		exit();
                }
	if (yaz_hits($id) == 0) {
		echo "<br><center><b>There were no results returned.</b><br>Please search again or click 'No Matches' to create the project manually.</center><br>";
	} else {
		echo "<br><center><b>Please pick a result from below:</b></center>";
	}

       echo "<br><form method='post' action='".$_SERVER['PHP_SELF']."'>";
       echo "<input type='hidden' name='action' value='submit_marcsearch'>";
       echo "<table border='0 width='100%' cellpadding='0' cellspacing='0'>";

       $i = 1;
       while (($start <= yaz_hits($id) && $i <= 10)) {
        	$rec = yaz_record($id, $start, "array");
	       	//if it's not a book don't display it.  we might want to uncomment in the future if there are too many records being returned - if (substr(yaz_record($id, $start, "raw"), 6, 1) != "a") { $start++; continue; }
        	$title = marc_title($rec);
		$author = marc_author($rec);
		$publisher = marc_publisher($rec);
        	$language = marc_language($rec);
        	$lccn = marc_lccn($rec);
        	$isbn = marc_isbn($rec);

        	if ($i % 2 == 1) {
        		echo "<tr><td width='5%' align='center'><input type='radio' name='rec' value='".base64_encode(serialize($rec))."'></td>";
        		echo "<td width='45%' align='left' valign='top'>";
			echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>Title</b>:</td><td align='left' valign='top'>$title</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>Author</b>:</td><td align='left' valign='top'>$author</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>Publisher</b>:</td><td align='left' valign='top'>$publisher</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>Language</b>:&nbsp;</td><td align='left' valign='top'>$language</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>LCCN</b>:</td><td align='left' valign='top'>$lccn</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>ISBN</b>:</td><td align='left' valign='top'>$isbn</td></tr>";
        		echo "</table><p></td>";
        	} else {
        		echo "<td width='5%' align='center'><input type='radio' name='rec' value='".base64_encode(serialize($rec))."'></td>";
        		echo "<td width='45%' align='left' valign='top'>";
       			echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>Title</b>:</td><td align='left' valign='top'>$title</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>Author</b>:</td><td align='left' valign='top'>$author</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>Publisher</b>:</td><td align='left' valign='top'>$publisher</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>Language</b>:&nbsp;</td><td align='left' valign='top'>$language</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>LCCN</b>:</td><td align='left' valign='top'>$lccn</td></tr>";
        		echo "<tr><td width='20%' align='left' valign='top'><b>ISBN</b>:</td><td align='left' valign='top'>$isbn</td></tr>";
        		echo "</table><p></td></tr>";
        		}

        	$i++;
        	$start++;
        }
        if ($i % 2 != 1) { echo "</tr>"; }

        if (isset($_GET['start']) && ($_GET['start']-10) > 0) { echo "<tr><td colspan='2' width='50%' align='left' valign='top'><a href='editproject.php?action=marc_search&start=".($_GET['start']-10)."&fq=".base64_encode(serialize($fullquery))."'>Previous</a></td>"; } else { echo "<tr><td colspan='2' width='50%'>&nbsp;</td>"; }
        if (($start+10) <= yaz_hits($id)) { echo "<td colspan='2' width='50%' align='right' valign='top'><a href='editproject.php?action=marc_search&start=$start&fq=".base64_encode(serialize($fullquery))."'>Next</a></td></tr>"; } else { echo "<td colspan='2' width='50%'>&nbsp;</td></tr>"; }

        echo "</table><br><center>";
        if (yaz_hits($id) != 0) { echo "<input type='submit' value='Create the Project'>&nbsp;"; }
        echo "<input type='button' value='Search Again' onclick='javascript:location.href=\"editproject.php\";'>&nbsp;<input type='button' value='No Matches' onclick='javascript:location.href=\"editproject.php?action=createnew\";'>&nbsp;<input type='button' value='Quit' onclick='javascript:location.href=\"projectmgr.php\";'></form></center>";
        yaz_close($id);
        theme("", "footer");
}

// -----------------------------------------------------------------------------

elseif ((isset($_REQUEST['action']) && ($_REQUEST['action'] == "submit_marcsearch" || $_REQUEST['action'] == "createnew")) || (isset($_REQUEST['project']) || isset($_REQUEST['saveAndPreview']))) {
	if(isset($_POST['saveAndPreview'])) { $errorMsg = saveProject($_POST); }
	if (!empty($_POST['rec'])) { $rec = unserialize(base64_decode($_POST['rec'])); }

	if(isset($_REQUEST['project']) || isset($_REQUEST['saveAndPreview']) || isset($GLOBALS['projectid'])) {
		if (empty($_GET['project']) && empty($GLOBALS['projectid'])) {
			$projectid = $_POST['projectid'];
		} elseif(empty($_POST['projectid']) && empty($GLOBALS['projectid'])) {
			$projectid = $_GET['project'];
		} else {
			$projectid = $GLOBALS['projectid'];
		}

		$result = mysql_query("SELECT * FROM projects WHERE projectid = '$projectid'");
		$projectid = mysql_result($result, 0, "projectid");
		$nameofwork = mysql_result($result, 0, "nameofwork");
		$authorsname = mysql_result($result, 0, "authorsname");
  		$language = mysql_result($result, 0, "language");
  		$scannercredit = mysql_result($result, 0, "scannercredit");
  		$txtlink = mysql_result($result, 0, "txtlink");
  		$htmllink = mysql_result($result, 0, "htmllink");
  		$ziplink = mysql_result($result, 0, "ziplink");
  		$comments = mysql_result($result, 0, "comments");
  		$clearance = mysql_result($result, 0, "clearance");
  		$postednum = mysql_result($result, 0, "postednum");
  		$genre = mysql_result($result, 0, "genre");
  		$difficulty_level = mysql_result($result, 0, "difficulty");
  	}

	if (empty($nameofwork) && isset($_POST['rec'])) { $nameofwork = marc_title($rec); }
	if (empty($authorsname) && isset($_POST['rec'])) {  $authorsname = marc_author($rec); }
	if (empty($language) && isset($_POST['rec'])) { $language = marc_language($rec); }
	if (empty($genre) && isset($_POST['rec'])) { $genre = marc_literary_form($rec); }
	if (empty($txtlink)) { $txtlink = ""; }
	if (empty($ziplink)) { $ziplink = ""; }
	if (empty($comments)) { $comments = "<p>Refer to the <a href=\"$code_url/faq/document.php\">Proofreading Guidelines</a>.</p>"; }
	if (empty($scannercredit)) { $scannercredit = ""; }
	if (empty($clearance)) { $clearance = ""; }
	if (empty($htmllink)) { $htmllink = ""; }
	if (empty($postednum)) { $postednum = ""; }
	if (empty($difficulty_level)) { if ($pguser == "BEGIN") $difficulty_level = "beginner"; else $difficulty_level = "average"; }

	theme("Create a Project", "header");
	echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>";
	if (!empty($rec)) { echo "<input type='hidden' name='rec' value='".base64_encode(serialize($rec))."'>"; }
	if (isset($posted)) { echo "<input type='hidden' name='posted' value='1'>"; }
	if (isset($errorMsg)) { echo "<br><center><font size='+1' color='#ff0000'><b>$errorMsg</b></font></center>"; }
	echo "<br><center><table cellspacing='0' cellpadding='5' border='1' width='90%' bordercolor='#000000' style='border-collapse:collapse'>";
	echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><b><font color='".$theme['color_headerbar_font']."'>Create a New Project</font></b></center></td></tr>";
	if (!empty($projectid)) { echo "<tr><td bgcolor='#CCCCCC'><b>Project ID</b></td><td>$projectid<input type='hidden' name='projectid' value='".encodeFormValue($projectid)."'></td></tr>"; }
        echo "<tr><td bgcolor='#CCCCCC'><b>Name of Work</b></td><td><input type='text' size='67' name='nameofwork' value='".encodeFormValue($nameofwork)."'></td></tr>";
        echo "<tr><td bgcolor='#CCCCCC'><b>Author's Name</b></td><td><input type='text' size='67' name='authorsname' value='".encodeFormValue($authorsname)."'></td></tr>";
        echo language_list($language);
        echo genre_list($genre);
        echo difficulty_list($difficulty_level);
        echo "<tr><td bgcolor='#CCCCCC'><b>Image Scanner Credit</b></td><td><input type='text' size='67' name='scannercredit' value='".encodeFormValue($scannercredit)."'></td></tr>";
        echo "<tr><td bgcolor='#CCCCCC'><b>Clearance Information</b></td><td><input type='text' size='67' name='clearance' value='".strip_tags($clearance)."'></td></tr>";
        echo "<tr><td bgcolor='#CCCCCC'><b>Text File URL</b></td><td><input type='text' size='67' name='txtlink' value='".encodeFormValue($txtlink)."'></td></tr>";
        echo "<tr><td bgcolor='#CCCCCC'><b>Zip File URL</b></td><td><input type='text' size='67' name='ziplink' value='".encodeFormValue($ziplink)."'></td></tr>";
        echo "<tr><td bgcolor='#CCCCCC'><b>HTML File URL</b></td><td><input type='text' size='67' name='htmllink' value='".encodeFormValue($htmllink)."'></td></tr>";
        echo "<tr><td bgcolor='#CCCCCC'><b>Posted Number</b></td><td><input type='text' size='67' name='postednum' value='".encodeFormValue($postednum)."'></td></tr>";
        if (empty($projectid) || checkProjectDirEmpty()) { echo "<tr><td bgcolor='#CCCCCC'><b>Project Files</b></td><td><input type='file' name='projectfiles' size='67'></td></tr>"; }
        echo "<tr><td colspan='2'><center><textarea name='comments' cols='74' rows='16'>".encodeFormValue($comments)."</textarea><br><b>[<a href=\"JavaScript:newHelpWin('template');\">How To Use A Template</a>]</center></td></tr>";
        echo "<tr><td bgcolor='#CCCCCC' colspan='2' align='center'><input type='submit' name='saveAndQuit' value='Save and Quit'><input type='submit' name='saveAndProject' value='Save and Go To Project'><input type='submit' name='saveAndPreview' value='Save and Preview'><input type='button' value='Quit Without Saving' onclick='javascript:location.href=\"projectmgr.php\";'></td></tr></form>";
	echo "</table>";

	if(isset($_POST['saveAndPreview'])) {
		previewProject($nameofwork, $authorsname, $comments);
	}
        theme("", "footer");
}

// -----------------------------------------------------------------------------

elseif (isset($_POST['saveAndQuit']) || isset($_POST['saveAndProject'])) {
	$errorMsg = saveProject($_POST);
	if (empty($errorMsg)) {
		if (isset($_POST['saveAndQuit'])) { metarefresh(0, "projectmgr.php", "Save and Quit", ""); }
		if (isset($_POST['saveAndProject'])) { metarefresh(0, "project_detail.php?project=$projectid", "Save and Go To Project", ""); }
	} else {
		theme("Project Error!", "header");
		echo "<br><center><h3><font color='#ff0000'>$errorMsg</font></h3></center>";
		theme("", "footer");
	}
}

// -----------------------------------------------------------------------------

else {
	theme("Create a Project", "header");
	if (!function_exists('yaz_connect')) {
		echo "<br><center><b>PHP is not compiled with YAZ support.  Please do so and try again.</b></center><br>";
		echo "<center>Until you do so, click <a href='editproject.php?action=createnew'>here</a> for creating a new project.</center><br>";
		echo "<center>If you believe you should be seeing the Create Project page please contact a <a href='mailto:".$GLOBALS['site_manager_email_addr']."'>Site Administrator</a></center>";
	} else {
		echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
		echo "<input type='hidden' name='action' value='marc_search'>";
		echo "<br><center><table cellspacing='0' cellpadding='5' border='1' width='75%' bordercolor='#000000' style='border-collapse: collapse'>";
		echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><b><font color='".$theme['color_headerbar_font']."'>Create a Project</font></b></center></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' colspan='2'><center><font color='".$theme['color_navbar_font']."'>Please put in as much information as possible to search for your project.  The more information the better but if not accurate enough may rule out results.</font></center></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>Title</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='title'></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>Author</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='author'></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>Publisher</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='publisher'></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>Publication Year (eg: 1912)</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='pubdate'></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>ISBN</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='isbn'></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>ISSN</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='issn'></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>LCCN</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='lccn'></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><input type='submit' value='Search'></center></td></tr></form>";
		echo "</table></center>";
	}
	theme("", "footer");
}

?>

