<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'marc_format.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'page_ops.inc');
include_once($relPath.'comment_inclusions.inc');

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

   if (!empty($_POST['checkedoutby'])) {
        $checkedoutby = $_POST['checkedoutby'];
        $result = mysql_query("SELECT u_id FROM users WHERE BINARY username = '$checkedoutby'");
        if (mysql_num_rows($result) == 0) {
             $errormsg .= "PPer/PPVer must be an existing user - check case and spelling of username.<br>";
        }
   }
   if (!empty($_FILES['projectfiles']['name'])) {
         if (substr($_FILES['projectfiles']['name'], -4) != ".zip") {
             $errormsg .= "File type must be ZIP.<br>";
         }
   }
   if (!empty($_FILES['projectfiles']['name'])) {
        $dir_name = substr($_FILES['projectfiles']['name'], 0, strpos($_FILES['projectfiles']['name'], ".zip"));
        if (file_exists("$uploads_dir/$pguser/$dir_name")) {
            $errormsg .= "The name of the zip file ($uploads_dir/$pguser/$dir_name) must be unique.<br>";
        }
   }
   if (!empty($_POST['special'])) {
      $special = $_POST['special'];
      if (    (strncmp($special, 'Birthday', 8) == 0)
           or (strncmp($special, 'Otherday', 8) == 0)) {
           if (empty($_POST['bdayday']) or empty($_POST['bdaymonth'])) {
              $errormsg .= "Month and Day are required for Birthday or Otherday Specials.<br>";
          } else {
             $bdaymonth = $_POST['bdaymonth'];
             $bdayday = $_POST['bdayday'];
             if (!checkdate ( $bdaymonth, $bdayday, 2000)) {
                 $errormsg .= "Invalid date supplied for Birthday or Otherday Special.<br>";
             } else {
                 if (strlen($special) == 8) { $special = $special." ".$bdaymonth.$bdayday; }
             }
          }
      }
   }
   if (!empty($_POST['image_provider'])) {
      $image_provider = $_POST['image_provider'];
      if (strcmp($image_provider, 'OTHER') == 0) {
         if (empty($_POST['imp_other'])) {
              $errormsg .= "When Image Provider is OTHER, details must be supplied.<br>";
          } else {
             $imp_other = $_POST['imp_other'];
             $image_provider = "O:".$imp_other;
          }
      }
   } else {
      $errormsg .= "Image Provider is required.<br>";
   }

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
                                checkedoutby='{$_POST['checkedoutby']}',
                language='$language',
                genre='{$_POST['genre']}',
                difficulty='{$_POST['difficulty_level']}',
                comments='{$_POST['comments']}',
                scannercredit='{$_POST['scannercredit']}',
                postednum='{$_POST['postednum']}',
                clearance='{$_POST['clearance']}',
                special='$special',
                image_provider = '$image_provider',
                up_projectid ='{$_POST['up_projectid']}'

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
                (nameofwork, authorsname, checkedoutby, language, genre, difficulty, username, comments, projectid, modifieddate, scannercredit, state, clearance, special, image_provider, up_projectid)
            VALUES (
                '{$_POST['nameofwork']}',
                '{$_POST['authorsname']}',
                '{$_POST['checkedoutby']}',
                '$language',
                '{$_POST['genre']}',
                '{$_POST['difficulty_level']}',
                '{$GLOBALS['pguser']}',
                '{$_POST['comments']}',
                '$projectid',
                UNIX_TIMESTAMP(),
                '{$_POST['scannercredit']}',
                '".PROJ_NEW."',
                '{$_POST['clearance']}',
                '$special',
                '$image_provider',
                '{$_POST['up_projectid']}'

            )
        ");

        project_allow_pages( $projectid );

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

        //Update the marc database with any changes we've received
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

function saveUberProject() {
   global $pguser;

   //Let's check to make sure everything is correct & there are no errors
   if (empty($_POST['up_nameofwork'])) { $errormsg .= "Overall Name of Uber Project is required.<br>"; }

   if (!empty($_POST['checkedoutby'])) {
        $checkedoutby = $_POST['checkedoutby'];
        $result = mysql_query("SELECT u_id FROM users WHERE BINARY username = '$checkedoutby'");
        if (mysql_num_rows($result) == 0) {
             $errormsg .= "Default Post Processor must be an existing user - check case and spelling of username.<br>";
        }
   }

/*
   if (!empty($_POST['up_topic_id'])) {
        $up_topic_id = $_POST['up_topic_id'];
        $result = mysql_query("SELECT forum_id FROM phpbb_topics WHERE topic_id = '$up_topic_id'");
        if (mysql_num_rows($result) == 0) {
             $errormsg .= "Uber Project Topic must already exist - check topic id.<br>";
        }
   }

`up_topic_id` int(10) default NULL,
  `up_contents_post_id` int(10) default NULL,

*/

   if (!empty($_POST['special'])) {
      $special = $_POST['special'];
      if (    (strncmp($special, 'Birthday', 8) == 0)
           or (strncmp($special, 'Otherday', 8) == 0)) {
           if (empty($_POST['bdayday']) or empty($_POST['bdaymonth'])) {
              $errormsg .= "Month and Day are required for Default Special of Birthday or Otherday.<br>";
          } else {
             $bdaymonth = $_POST['bdaymonth'];
             $bdayday = $_POST['bdayday'];
             if (!checkdate ( $bdaymonth, $bdayday, 2000)) {
                 $errormsg .= "Invalid date supplied for Default Special of Birthday or Otherday.<br>";
             } else {
                 if (strlen($special) == 8) { $special = $special." ".$bdaymonth.$bdayday; }
             }
          }
      }
   }

   if (!empty($_POST['image_provider'])) {
      $image_provider = $_POST['image_provider'];
      if (strcmp($image_provider, 'OTHER') == 0) {
         if (empty($_POST['imp_other'])) {
              $errormsg .= "When Default Image Provider is OTHER, details must be supplied.<br>";
          } else {
             $imp_other = $_POST['imp_other'];
             $image_provider = "O:".$imp_other;
          }
      }
   }

   if (isset($errormsg)) {
        return $errormsg;
        exit();
   }

   //Format the language as pri_language with sec_language if pri_language is set
   //Otherwise set just the pri_language
   if (!empty($_POST['sec_language'])) { $language = $_POST['pri_language']." with ".$_POST['sec_language']; } else { $language = $_POST['pri_language']; }

   //If we are just updating an already existing uber project
   if (isset($_POST['up_projectid'])) {
        //Update the uber project database table with the updated info
        mysql_query("
            UPDATE uber_projects SET
                up_nameofwork='{$_POST['up_nameofwork']}',
                up_topic_id='{$_POST['up_topic_id']}',
                up_contents_post_id='{$_POST['up_contents_post_id']}',
                up_modifieddate=UNIX_TIMESTAMP(),
                up_description='{$_POST['up_description']}',
                d_nameofwork='{$_POST['nameofwork']}',
                d_authorsname='{$_POST['authorsname']}',
                d_checkedoutby='{$_POST['checkedoutby']}',
                d_language='$language',
                d_genre='{$_POST['genre']}',
                d_year='{$_POST['year']}',
                d_difficulty='{$_POST['difficulty_level']}',
                d_comments='{$_POST['comments']}',
                d_scannercredit='{$_POST['scannercredit']}',
                d_clearance='{$_POST['clearance']}',
                d_special='$special',
                d_image_provider = '$image_provider'
            WHERE up_projectid='{$_POST['up_projectid']}'
        ");

   } else {
        global $up_projectid;

        //Insert a new row into the uber projects table
        mysql_query("
            INSERT INTO uber_projects
                (up_nameofwork, up_topic_id, up_contents_post_id, up_modifieddate, up_enabled, up_description,
                 d_nameofwork, d_authorsname, d_language, d_comments, d_special, d_checkedoutby, d_scannercredit,
                 d_clearance, d_year, d_genre, d_difficulty, d_image_provider)
            VALUES (
                '{$_POST['up_nameofwork']}',
                '{$_POST['up_topic_id']}',
                '{$_POST['up_comments_post_id']}',
                UNIX_TIMESTAMP(),
                1,
                '{$_POST['up_description']}',
                '{$_POST['nameofwork']}',
                '{$_POST['authorsname']}',
                '$language',
                '{$_POST['comments']}',
                '$special',
                '{$_POST['checkedoutby']}',
                '{$_POST['scannercredit']}',
                '{$_POST['clearance']}',
                '{$_POST['year']}',
                '{$_POST['genre']}',
                '{$_POST['difficulty_level']}',
                '$image_provider'
            )
        ");

         $up_projectid = mysql_insert_id();

//  '{$GLOBALS['pguser']}'

        // if topic / post IDs are blank :
           // create the auto uber post and/or the auto contents post ?
           // update uber_projects table with topic and/or post IDs

   }

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
   exec("unzip -o -j $local_zipfile -d '$uploads_dir/$pguser/$dir_name'");
   // Put target dir in quotes because $pguser might contain a space char.

   # echo "insertTextFiles($dir_name, $projectid) ...<br>\n";
   insertTextFiles($dir_name, $projectid);

   $error_msg = project_transition( $projectid, PROJ_P1_UNAVAILABLE );
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
        $image_file_name = "$file_base.png";
        $txt_file_path = "$uploads_dir/$pguser/$dir_name/$txt_file_name";

        $errs = project_add_page( $projectid, $file_base, $image_file_name, $txt_file_path, $now );
        if ($errs) die($errs);
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

   $result = mysql_query("SELECT nameofwork, postednum FROM projects WHERE projectid = '$projectid'");
   $NameofWork = mysql_result($result, 0, "nameofwork");
   $postednum = mysql_result($result, 0, "postednum");

   $url = get_pg_catalog_url_for_etext( $postednum );

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
            ." You can download the files via PG's online catalog at <$url>."
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

   // insert e.g. templates and biographies
   $comments = parse_project_comments($comments);

        $a = _("Follow the current")." <a href='$code_url/faq/document.php'>"._("Proofreading Guidelines")."</a> "._("for detailed project formatting directions.");
        $b = _("Instructions below take precedence over the guidelines");

  echo "<br><table width='90%' border=1>";
  echo "<tr><td align='middle' bgcolor='#cccccc'><h3>Preview<br>Project</h3></td>";
  echo "<td bgcolor='#cccccc'><b>This is a preview of your project and exactly how it will look to the proofreaders.</b></td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Title</b></td><td>$nameofwork</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Author</b></td><td>$authorsname</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Project Manager</b></td><td>".$GLOBALS['pguser']."</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Last Proofread</b></td><td>".strftime(_("%A, %B %e, %Y at %X"))."</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Forum</b></td><td>Start a discussion about this project</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Book Completed</b></td><td>Yes, I would like to be notified when this has been posted to Project Gutenberg.</td></tr>\n";
  echo "<tr><td colspan=2>$a <b>$b: </b><P>$comments</td></tr>\n</table><br><br>";
}

function language_list($language, $label = 'Language', $bgcol = '#CCCCCC') {
   include_once($GLOBALS['relPath'].'iso_lang_list.inc');

   if (strpos($language, "with") > 0) {
        $pri_language = trim(substr($language, 0, strpos($language, "with")));
        $sec_language = trim(substr($language, (strpos($language, "with")+5)));
   } else {
        $pri_language = $language;
        $sec_language = '';
   }

   $array_list = $GLOBALS['lang_list'];

   echo "<tr><td bgcolor='$bgcol'><b>$label</b></td><td><select name='pri_language'>";
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
   echo "</select></td></tr>\n";
}

function genre_list($genre, $label = 'Genre', $bgcol = '#CCCCCC') {
   $array_list = array(
                'Other'=>_('Other'),
                'Adventure'=>_('Adventure'),
                'Agriculture'=>_('Agriculture'),
                'Archaeology'=>_('Archaeology'),
                'Art'=>_('Art'),
                'Animals'=>_('Animals'),
                'Anthropology'=>_('Anthropology'),
                'Architecture'=>_('Architecture'),
                'Astronomy'=>_('Astronomy'),
                'Autobiography'=>_('Autobiography'),
                'Bibliography'=>_('Bibliography'),
                'Biography'=>_('Biography'),
                'Biology'=>_('Biology'),
                'Business'=>_('Business'),
                'Chemistry'=>_('Chemistry'),
                'Collection'=>_('Collection'),
                'Comics'=>_('Comics'),
                'Cooking'=>_('Cooking'),
                'Correspondence'=>_('Correspondence'),
                'Crafts'=>_('Crafts'),
                'Diary'=>_('Diary'),
                'Dictionary'=>_('Dictionary'),
                'Drama'=>_('Drama'),
                'Economics'=>_('Economics'),
                'Education'=>_('Education'),
                'Encyclopedia'=>_('Encyclopedia'),
                'Essay'=>_('Essay'),
                'Folklore'=>_('Folklore'),
                'General Fiction'=>_('General Fiction'),
                'Geology'=>_('Geology'),
                'Grammar'=>_('Grammar'),
                'Health'=>_('Health'),
                'History'=>_('History'),
                'Historical Fiction'=>_('Historical Fiction'),
                'Horror'=>_('Horror'),
                'Horticulture'=>_('Horticulture'),
                'Humor'=>_('Humor'),
                'Instructional'=>_('Instructional'),
                'Juvenile'=>_('Juvenile'),
                'Law'=>_('Law'),
                'Linguistics'=>_('Linguistics'),
                'Literature'=>_('Literature'),
                'Mathematics'=>_('Mathematics'),
                'Medicine'=>_('Medicine'),
                'Military'=>_('Military'),
                'Mixed Form'=>_('Mixed Form'),
                'Music'=>_('Music'),
                'Musicology'=>_('Musicology'),
                'Mystery'=>_('Mystery'),
                'Mythology'=>_('Mythology'),
                'Nature'=>_('Nature'),
                'Natural Science'=>_('Natural Science'),
                'Non-Fiction'=>_('Non-Fiction'),
                'Periodical'=>_('Periodical'),
                'Philosophy'=>_('Philosophy'),
                'Physics'=>_('Physics'),
                'Poetry'=>_('Poetry'),
                'Political Science'=>_('Political Science'),
                'Psychology'=>_('Psychology'),
                'Recreation'=>_('Recreation'),
                'Religious'=>_('Religious'),
                'Reference'=>_('Reference'),
                'Romance'=>_('Romance'),
                'Satire'=>_('Satire'),
                'Science'=>_('Science'),
                'Science Fiction'=>_('Science Fiction'),
                'Short Story'=>_('Short Story'),
                'Sociology'=>_('Sociology'),
                'Speech'=>_('Speech'),
                'Spirituality'=>_('Spirituality'),
                'Sports'=>_('Sports'),
                'Technology'=>_('Technology'),
                'Travel'=>_('Travel'),
                'Veterinary'=>_('Veterinary'),
                'Western'=>_('Western'),
                'Zoology'=>_('Zoology'),
            );
   echo "<tr><td bgcolor='$bgcol'><b>$label</b></td><td><select name='genre'>";
   foreach($array_list as $k=>$v) {
        echo "<option value='".encodeFormValue($k)."'";
        if ($genre == $k) { echo " SELECTED"; }
        echo ">$v</option>";
        }
   echo "</select></td></tr>\n";
}

function difficulty_list($difficulty_level, $label = 'Difficulty Level', $bgcol = '#CCCCCC') {
        global $pguser;
   $array_list = array('Beginner', 'Easy', 'Average', 'Hard');
   echo "<tr><td bgcolor='$bgcol'><b>$label</b></td><td>";
        $result = mysql_query("SELECT * FROM users WHERE username = '$pguser'");
        if (mysql_result($result,0,"sitemanager") == "yes") $sa = 1; else $sa = 0;
        // only show the beginner level to the BEGIN PM or SiteAdmins
   // don't let garvint create EASY projects
   for ($i=0;$i<count($array_list);$i++)  {
                if (($i > 0) || ($pguser == "BEGIN") || ($sa)) {
           if (!($pguser == "garvint" && $i == 1)) {
              echo "<input type='radio' name='difficulty_level' value='".encodeFormValue(strtolower($array_list[$i]))."'";
              if (strtolower($difficulty_level) == strtolower($array_list[$i])) { echo " CHECKED"; }
              echo ">$array_list[$i]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
           }
                }
   }
   echo "</td></tr>\n";
}

function special_list($special, $label = 'Special Day (optional)', $bgcol = '#CCCCCC') {

    // get info on special days
    $specs_result = mysql_query("
            SELECT      spec_code,
                        display_name,
                        DATE_FORMAT(concat('2000-',open_month,'-',open_day),'%e %b') as 'Start Date'
            FROM special_days
            WHERE enable = 1
            ORDER BY open_month, open_day
        ");

    // it'd be nice to make this static, or something, so it only was loaded once
    $specials_array = array();

    // put list into array
    while ($s_row = mysql_fetch_assoc($specs_result)) {
        $show = $s_row['display_name']." (".$s_row['Start Date'].")";
        $code = $s_row['spec_code'];
        $specials_array["$code"] = $show;
    }

   // drop down select box for which special day
   echo "<tr><td bgcolor='$bgcol'><b>$label</b></td><td><select name='special'>";

   // add special case values first
   echo "<option value=''>NONE</option>";

   echo "<option value='Birthday'";
   if (strncmp ( $special, 'Birthday', 8) == 0) {
         echo " SELECTED";
         $bdaymonth = substr($special, 9, 2);
         $bdayday = substr($special, 11, 2);
   }
   echo ">Birthday</option>";

   echo "<option value='Otherday'";
   if (strncmp ( $special, 'Otherday', 8) == 0) {
         echo " SELECTED";
         $bdaymonth = substr($special, 9, 2);
         $bdayday = substr($special, 11, 2);
   }
   echo ">Otherday</option>";


   // add the rest of the special days (the "ordinary" special days ;) )
   foreach($specials_array as $k=>$v) {
        echo "<option value='".encodeFormValue($k)."'";
        if ($special == $k) { echo " SELECTED"; }
        echo ">$v</option>";
        }
   echo "</select>";

   echo " <a href='show_specials.php'>Special Days Info</a><br>";

   // drop down selects for month and date, used for Birthday and Otherday specials
   echo " Birthday/Otherday: (month) <select name='bdaymonth'>";
   echo "<option value=''></option>";
   $i = 1;
   while ($i <= 12) {
      $v = sprintf("%02d", $i);
      echo "<option value='$v'";
      if ($v == $bdaymonth) { echo " SELECTED"; }
      echo ">$v</option>";
      $i++;
   }
   echo "</select>";

   echo " (day) <select name='bdayday'>";
   echo "<option value=''></option>";
   $i = 1;
   while ($i <= 31) {
      $v = sprintf("%02d", $i);
      echo "<option value='$v'";
      if ($v == $bdayday) { echo " SELECTED"; }
      echo ">$v</option>";
      $i++;
   }
   echo "</select>";

   echo "</td></tr>\n";
}


function image_provider_list($image_provider, $label = 'Image Provider', $bgcol = '#CCCCCC') {

    // get info on image_providers
    $imp_result = mysql_query("
            SELECT      image_provider,
                        display_name
            FROM image_providers
            WHERE enable = 1
            ORDER BY display_name
        ");

    // it'd be nice to make this static, or something, so it only was loaded once
    $imp_array = array();

    // put list into array
    while ($i_row = mysql_fetch_assoc($imp_result)) {
        $show = $i_row['display_name'];
        $code = $i_row['image_provider'];
        $imp_array["$code"] = $show;
    }

   // drop down select box for which image provider
   echo "<tr><td bgcolor='$bgcol'><b>$label</b></td><td><select name='image_provider'>";

   // add special case value "DP User"
   echo "<option value='DP User' ";
   if (strcmp ( $image_provider, 'DP User') == 0) {
         echo " SELECTED";
   }
   echo ">"._("DP User")."</option>";

   // add the pre-defined image_providers
   foreach($imp_array as $k=>$v) {
        echo "<option value='".encodeFormValue($k)."'";
        if ($image_provider == $k) { echo " SELECTED"; }
        echo ">$v</option>";
   }

   // add special case value "Other"
   echo "<option value='OTHER' ";
   if (strncmp ( $image_provider, 'O:',2) == 0) {
         echo " SELECTED";
         $imp_other_val = substr($image_provider,2);
   }
   echo ">"._("OTHER")."</option>";

   echo "</select>";

   echo " "._("Details for OTHER: ").
          "<input type='text' size='18' name='imp_other' value='"
          .encodeFormValue($imp_other_val)."'>";

   echo " <a href='show_image_providers.php'>Details of Image Providers</a><br>";

   echo "</td></tr>\n";
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
        echo "<br><center><b>".yaz_hits($id)." results returned. Note that some non-book results may not be displayed.<br>Please pick a result from below:</b></center>";
   }

       echo "<br><form method='post' action='".$_SERVER['PHP_SELF']."'>";
       echo "<input type='hidden' name='action' value='submit_marcsearch'>";
       echo "<table border='0 width='100%' cellpadding='0' cellspacing='0'>";

       $hits_per_page = 20; // Perhaps later this can be a PM preference or an option on the form.
       $i = 1;
       while (($start <= yaz_hits($id) && $i <= $hits_per_page)) {
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
                echo "<tr><td width='20%' align='left' valign='top'><b>Title</b>:</td><td align='left' valign='top'>$title</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>Author</b>:</td><td align='left' valign='top'>$author</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>Publisher</b>:</td><td align='left' valign='top'>$publisher</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>Language</b>:&nbsp;</td><td align='left' valign='top'>$language</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>LCCN</b>:</td><td align='left' valign='top'>$lccn</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>ISBN</b>:</td><td align='left' valign='top'>$isbn</td></tr>\n";
                echo "</table><p></td>";
            } else {
                echo "<td width='5%' align='center'><input type='radio' name='rec' value='".base64_encode(serialize($rec))."'></td>";
                echo "<td width='45%' align='left' valign='top'>";
                echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
                echo "<tr><td width='20%' align='left' valign='top'><b>Title</b>:</td><td align='left' valign='top'>$title</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>Author</b>:</td><td align='left' valign='top'>$author</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>Publisher</b>:</td><td align='left' valign='top'>$publisher</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>Language</b>:&nbsp;</td><td align='left' valign='top'>$language</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>LCCN</b>:</td><td align='left' valign='top'>$lccn</td></tr>\n";
                echo "<tr><td width='20%' align='left' valign='top'><b>ISBN</b>:</td><td align='left' valign='top'>$isbn</td></tr>\n";
                echo "</table><p></td></tr>\n";
                }

            $i++;
            $start++;
        }
        if ($i % 2 != 1) { echo "</tr>\n"; }

        if (isset($_GET['start']) && ($_GET['start']-$hits_per_page) > 0) { echo "<tr><td colspan='2' width='50%' align='left' valign='top'><a href='editproject.php?action=marc_search&start=".($_GET['start']-$hits_per_page)."&fq=".base64_encode(serialize($fullquery))."'>Previous</a></td>"; } else { echo "<tr><td colspan='2' width='50%'>&nbsp;</td>"; }
        if (($start+$hits_per_page) <= yaz_hits($id)) { echo "<td colspan='2' width='50%' align='right' valign='top'><a href='editproject.php?action=marc_search&start=$start&fq=".base64_encode(serialize($fullquery))."'>Next</a></td></tr>\n"; } else { echo "<td colspan='2' width='50%'>&nbsp;</td></tr>\n"; }

        echo "</table><br><center>";
        if (yaz_hits($id) != 0) { echo "<input type='submit' value='Create the Project'>&nbsp;"; }
        echo "<input type='button' value='Search Again' onclick='javascript:location.href=\"editproject.php\";'>&nbsp;<input type='button' value='No Matches' onclick='javascript:location.href=\"editproject.php?action=createnew\";'>&nbsp;<input type='button' value='Quit' onclick='javascript:location.href=\"projectmgr.php\";'></form></center>";
        yaz_close($id);
        theme("", "footer");
}

// -----------------------------------------------------------------------------

elseif ((isset( $_REQUEST['action']) &&
              ( $_REQUEST['action'] == "submit_marcsearch" ||
                $_REQUEST['action'] == "createnew"  ||
                $_REQUEST['action'] == 'createnewfromuber')) ||
        (isset( $_REQUEST['project']) || isset($_REQUEST['saveAndPreview']))) {

    if(isset($_POST['saveAndPreview'])) { $errorMsg = saveProject($_POST); }
    if (!empty($_POST['rec'])) { $rec = unserialize(base64_decode($_POST['rec'])); }

    if (((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'createnewfromuber')) ||
        (isset($_POST['up_projectid']) && !isset($_POST['projectid'])))) {

        // here we are either maknig our first attempt at creating a new project from an UP, OR
        // saving and previewing a project that was meant to be created from an UP (up_projectid is set)
        // but the creation attempt failed during saveProject (projectid is not set)

        // if we have an error message, there was an attempt to save that went wrong,
        // so we leave the values as they were submitted, for less editing

        // otherwise, we populate the values with the default values of the given UP from the db

        if (!strlen($errorMsg)) {
            if (isset($_REQUEST['up_projectid']) && strlen($_REQUEST['up_projectid'])  ) {
                $up_projectid = $_REQUEST['up_projectid'];
                $result = mysql_query("SELECT * FROM uber_projects WHERE up_projectid = $up_projectid");
                if (mysql_num_rows($result)) {

                    // check that user has permission to create a project from this UP

                    $up_info = mysql_fetch_assoc($result);
                    $up_nameofwork = $up_info['up_nameofwork'];
                    $up_topic_id =  $up_info['up_topic_id'];
                    $nameofwork = $up_info['d_nameofwork'];
                    $authorsname = $up_info['d_authorsname'];
                    $checkedoutby = $up_info['d_checkedoutby'];
                    $language = $up_info['d_language'];
                    $scannercredit = $up_info['d_scannercredit'];
                    $comments = $up_info['d_comments'];
                    $clearance = $up_info['d_clearance'];
                    $postednum = $up_info['d_postednum'];
                    $genre = $up_info['d_genre'];
                    $difficulty_level = $up_info['d_difficulty'];
                    $special = $up_info['d_special'];
                    $image_provider = $up_info['d_image_provider'];
                    // $year = $up_info['d_year'];

                } else {

                    // invalid UP_ID supplied
                    $errorMsg .= _("Invalid Uber Project ID supplied for Create New Project From Uber Project");
                }
            } else {

                // no UP_ID supplied
                $errorMsg .= _("No Uber Project ID supplied for Create New Project From Uber Project");
           }
        }

        theme(_("Create a Project from an Uber Project"), "header");
        $header_shown = true;
    }

    if(isset($_REQUEST['project']) || isset($_REQUEST['saveAndPreview']) || isset($GLOBALS['projectid'])) {
        if (empty($_GET['project']) && empty($GLOBALS['projectid'])) {
            $projectid = $_POST['projectid'];
        } elseif(empty($_POST['projectid']) && empty($GLOBALS['projectid'])) {
            $projectid = $_GET['project'];
        } else {
            $projectid = $GLOBALS['projectid'];
        }

        $result = mysql_query("SELECT * FROM projects WHERE projectid = '$projectid'");

        // if the project has been created and saved, use those values

        if (mysql_num_rows($result) == 1) {

            $projectid = mysql_result($result, 0, "projectid");
            $nameofwork = mysql_result($result, 0, "nameofwork");
            $authorsname = mysql_result($result, 0, "authorsname");
            $checkedoutby = mysql_result($result, 0, "checkedoutby");
            $language = mysql_result($result, 0, "language");
            $scannercredit = mysql_result($result, 0, "scannercredit");
            $comments = mysql_result($result, 0, "comments");
            $clearance = mysql_result($result, 0, "clearance");
            $postednum = mysql_result($result, 0, "postednum");
            $genre = mysql_result($result, 0, "genre");
            $difficulty_level = mysql_result($result, 0, "difficulty");
            $special = mysql_result($result, 0, "special");
            $image_provider = mysql_result($result, 0, "image_provider");
            $up_projectid = mysql_result($result, 0, "up_projectid");

            // if there's an associated UP, get info on it 

            if (empty($up_projectid)) { $up_projectid =  $_POST['up_projectid'];}

            if (!empty($up_projectid)) {
                $result = mysql_query("SELECT up_nameofwork, up_topic_id FROM uber_projects WHERE up_projectid = '$up_projectid'");
                $up_nameofwork = mysql_result($result, 0, "up_nameofwork");
                $up_topic_id = mysql_result($result, 0, "up_nameofwork");
            }

        }

        if (!$header_shown) { theme(_("Create a Project"), "header");}
    }

    if (empty($nameofwork) && isset($_POST['rec'])) { $nameofwork = marc_title($rec); }
    if (empty($authorsname) && isset($_POST['rec'])) {  $authorsname = marc_author($rec); }
    if (empty($language) && isset($_POST['rec'])) { $language = marc_language($rec); }
    if (empty($genre) && isset($_POST['rec'])) { $genre = marc_literary_form($rec); }
    if (empty($checkedoutby)) { $checkedoutby = ""; }
    if (empty($comments)) { $comments = "<p>".sprintf(_("Refer to the %sProofreading Guidelines%s."),"<a href=\"$code_url/faq/document.php\">","</a>")."</p>"; }
    if (empty($scannercredit)) { $scannercredit = ""; }
    if (empty($clearance)) { $clearance = ""; }
    // Do not display db default value(s).
    if ($postednum == 6000 || $postednum == 0) { $postednum = ""; }
    if (empty($special)) { $special = ""; }
    if (empty($image_provider)) { $image_provider = "DP User"; }
    if (empty($difficulty_level)) { if ($pguser == "BEGIN") $difficulty_level = "beginner"; else $difficulty_level = "average"; }

    if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'submit_marcsearch')) {
        theme(_("Create a Project"), "header");
    }

    echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>";
    if (!empty($rec)) { echo "<input type='hidden' name='rec' value='".base64_encode(serialize($rec))."'>"; }
    if (isset($posted)) { echo "<input type='hidden' name='posted' value='1'>"; }
    if (isset($up_projectid)) { echo "<input type='hidden' name='up_projectid' value='$up_projectid'>"; }
    if (isset($errorMsg)) { echo "<br><center><font size='+1' color='#ff0000'><b>$errorMsg</b></font></center>"; }
    echo "<br><center><table cellspacing='0' cellpadding='5' border='1' width='90%' bordercolor='#000000' style='border-collapse:collapse'>";
    echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><b><font color='".$theme['color_headerbar_font']."'>"._("Create a New Project")."</font></b></center></td></tr>\n";
    if (!empty($projectid)) { echo "<tr><td bgcolor='#CCCCCC'><b>"._("Project ID")."</b></td><td>$projectid<input type='hidden' name='projectid' value='".encodeFormValue($projectid)."'></td></tr>\n"; }
    if (!empty($up_nameofwork)) { echo "<tr><td bgcolor='#CCCCCC'><b>"._("Related Uber Project")."</b></td><td>".encodeFormValue($up_nameofwork)."</td></tr>\n"; }
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("Name of Work")."</b></td><td><input type='text' size='67' name='nameofwork' value='".encodeFormValue($nameofwork)."'></td></tr>\n";
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("Author's Name")."</b></td><td><input type='text' size='67' name='authorsname' value='".encodeFormValue($authorsname)."'></td></tr>\n";
    echo language_list($language);
    echo genre_list($genre);
    echo difficulty_list($difficulty_level);
    echo special_list($special);
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("PPer/PPVer")."</b></td><td><input type='text' size='67' name='checkedoutby' value='".encodeFormValue($checkedoutby)."'></td></tr>\n";
    echo image_provider_list($image_provider);
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("Image Scanner Credit")."</b></td><td><input type='text' size='67' name='scannercredit' value='".encodeFormValue($scannercredit)."'></td></tr>\n";
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("Clearance Information")."</b></td><td><input type='text' size='67' name='clearance' value='".encodeFormValue($clearance)."'></td></tr>\n";
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("Text File URL")."</b></td><td><input type='text' size='67' name='txtlink' value='".encodeFormValue($txtlink)."'></td></tr>\n";
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("Zip File URL")."</b></td><td><input type='text' size='67' name='ziplink' value='".encodeFormValue($ziplink)."'></td></tr>\n";
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("HTML File URL")."</b></td><td><input type='text' size='67' name='htmllink' value='".encodeFormValue($htmllink)."'></td></tr>\n";
    echo "<tr><td bgcolor='#CCCCCC'><b>"._("Posted Number")."</b></td><td><input type='text' size='67' name='postednum' value='".encodeFormValue($postednum)."'></td></tr>\n";
    if (empty($projectid) || checkProjectDirEmpty()) { echo "<tr><td bgcolor='#CCCCCC'><b>"._("Project Files")."</b></td><td><input type='file' name='projectfiles' size='67'></td></tr>\n"; }
    echo "<tr><td colspan='2'><center><textarea name='comments' cols='74' rows='16'>".encodeFormValue($comments)."</textarea><br><b>[<a href=\"JavaScript:newHelpWin('template');\">"._("How To Use A Template")."</a>]</center></td></tr>\n";
    echo "<tr><td bgcolor='#CCCCCC' colspan='2' align='center'><input type='submit' name='saveAndQuit' value='"._("Save and Quit")."'><input type='submit' name='saveAndProject' value='"._("Save and Go To Project")."'><input type='submit' name='saveAndPreview' value='"._("Save and Preview")."'><input type='button' value='"._("Quit Without Saving")."' onclick='javascript:location.href=\"projectmgr.php\";'></td></tr>\n</form>";
    echo "</table>";

    if(isset($_POST['saveAndPreview'])) {
        previewProject($nameofwork, $authorsname, $comments);
    }
        theme("", "footer");
}

// -----------------------------------------------------------------------------

elseif (isset($_REQUEST['action']) &&
           ( ($_REQUEST['action'] == 'createnewuber') || ($_REQUEST['action'] == 'edituber')  ) ||
        isset($_REQUEST['saveUberAndReturn'])) {

    if (isset($_POST['saveUberAndReturn'])) {
        $errorMsg = saveUberProject($_POST);
    }

    if ( isset($_REQUEST['saveUberAndReturn']) || isset($GLOBALS['up_projectid']) ||
        (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'edituber'))) {

        if ( empty($GLOBALS['up_projectid'])) {
            $up_projectid = $_POST['up_projectid'];
        } else {
            $up_projectid = $GLOBALS['up_projectid'];
        }

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edituber' && strlen($up_projectid) == 0) {
            $errorMsg = _("No Uber Project Id supplied to Edit - Bad Link" . $up_projectid);
        } else {
            $result = mysql_query("SELECT * FROM uber_projects WHERE up_projectid = '$up_projectid'");
            if (mysql_num_rows($result)) {

                // check that user has permission to edit this UP

                $up_info = mysql_fetch_assoc($result);

                $up_nameofwork = $up_info['up_nameofwork'];
                $up_description = $up_info['up_description'];
                $nameofwork = $up_info['d_nameofwork'];
                $authorsname = $up_info['d_authorsname'];
                $checkedoutby = $up_info['d_checkedoutby'];
                $language = $up_info['d_language'];
                $scannercredit = $up_info['d_scannercredit'];
                $comments = $up_info['d_comments'];
                $clearance = $up_info['d_clearance'];
                $genre = $up_info['d_genre'];
                $difficulty_level = $up_info['d_difficulty'];
                $special = $up_info['d_special'];
                $image_provider = $up_info['d_image_provider'];
                // $year = $up_info['d_year'];

            } else {

                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edituber') {
                    // invalid UP_ID supplied
                    $errorMsg .= _("Invalid Uber Project ID supplied");
                }
            }
       }
    } else {

         // no UP_ID supplied, so we're creating a brand new uberproject...
         // or possibly there was just a failed attempt to create one, so
         // we will reclaim any of the values that may have been set by not resetting them(!)

         // check that user is allowed to create new uberprojects

        $errorMsg = $errorMsg;

    }

    theme(_("Create an Uber Project"), "header");

    // want the "Create an Uber Project" version of this page to look a little different
    // from the normal "Create a Project" version, so we'll use a theme colour
    // instead of a grey for the left hand column

    $bgcol = $theme['color_navbar_bg'];

    echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>";
    if (!empty($rec)) { echo "<input type='hidden' name='rec' value='".base64_encode(serialize($rec))."'>"; }
    if (isset($up_projectid)) { echo "<input type='hidden' name='up_projectid' value='$up_projectid'>"; }
    if (isset($errorMsg)) { echo "<br><center><font size='+1' color='#ff0000'><b>$errorMsg</b></font></center>"; }
    echo "<br><center><table cellspacing='0' cellpadding='5' border='1' width='90%' bordercolor='#000000' style='border-collapse:collapse'>";
    echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><b><font color='".$theme['color_headerbar_font']."'>"._("Uber Project Settings")."</font></b></center></td></tr>\n";

    if (!empty($up_projectid)) { echo "<tr><td bgcolor='$bgcol'><b>"._("Uber Project ID")."</b></td><td>$up_projectid<input type='hidden' name='up_projectid' value='".encodeFormValue($up_projectid)."'></td></tr>\n"; }

    echo "<tr><td bgcolor='$bgcol'><b>"._("Overall Name of Uber Project")."</b></td><td><input type='text' size='67' name='up_nameofwork' value='".encodeFormValue($up_nameofwork)."'></td></tr>\n";
    echo "<tr><td colspan='2'><center><b>"._("Brief Description of Uber Project")."</b><br><textarea name='up_description' cols='74' rows='6'>".encodeFormValue($up_description)."</textarea></center></td></tr>\n";
    echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><b><font color='".$theme['color_headerbar_font']."'>"._("Default Values for Projects to be Created from this Uber Project")."</font></b></center></td></tr>\n";
    echo "<tr><td bgcolor='$bgcol'><b>"._("Default Name of Work")."</b></td><td><input type='text' size='67' name='nameofwork' value='".encodeFormValue($nameofwork)."'></td></tr>\n";
    echo "<tr><td bgcolor='$bgcol'><b>"._("Default Author's Name")."</b></td><td><input type='text' size='67' name='authorsname' value='".encodeFormValue($authorsname)."'></td></tr>\n";
    echo language_list($language, 'Default Language', $bgcol);
    echo genre_list($genre, 'Default Genre', $bgcol);
    echo difficulty_list($difficulty_level, 'Default Difficulty Level', $bgcol);
    echo special_list($special, 'Default Special Day', $bgcol);
    echo "<tr><td bgcolor='$bgcol'><b>"._("Default PPer")."</b></td><td><input type='text' size='67' name='checkedoutby' value='".encodeFormValue($checkedoutby)."'></td></tr>\n";
    echo image_provider_list($image_provider, 'Default Image Provider', $bgcol);
    echo "<tr><td bgcolor='$bgcol'><b>"._("Default Image Scanner Credit")."</b></td><td><input type='text' size='67' name='scannercredit' value='".encodeFormValue($scannercredit)."'></td></tr>\n";
    echo "<tr><td bgcolor='$bgcol'><b>"._("Default Clearance Information")."</b></td><td><input type='text' size='67' name='clearance' value='".encodeFormValue($clearance)."'></td></tr>\n";
    echo "<tr><td colspan='2'><center><b>"._("Default Project Comments")."</b><br><textarea name='comments' cols='74' rows='16'>".encodeFormValue($comments)."</textarea><br><b>[<a href=\"JavaScript:newHelpWin('template');\">"._("How To Use A Template")."</a>]</center></td></tr>\n";
    echo "<tr><td bgcolor='$bgcol' colspan='2' align='center'><input type='submit' name='saveUberAndQuit' value='"._("Save Uber Project and Quit")."'><input type='submit' name='saveUberAndNewProject' value='"._("Save Uber Project and Create \na New Project from this Uber Project")."'><input type='submit' name='saveUberAndReturn' value='"._("Save Uber Project\n and Refresh")."'><input type='button' value='"._("Quit Without Saving")."' onclick='javascript:location.href=\"projectmgr.php\";'></td></tr>\n</form>";
    echo "</table>";

    theme("", "footer");
}

// -----------------------------------------------------------------------------

elseif (isset($_POST['saveUberAndQuit']) || isset($_POST['saveUberAndNewProject']) ) {
   $errorMsg = saveUberProject($_POST);

   if (empty($errorMsg)) {
       if (isset($_POST['saveUberAndQuit'])) {
           metarefresh(0, "projectmgr.php", _("Save Uber Project and Quit"), "");
       } else {
           metarefresh(0, "editproject.php?action=createnewfromuber&up_projectid=".$up_projectid, _("Save Uber Project and Create New Project"), "");
       }
   } else {
        theme(_("Uber Project Error!"), "header");
        echo "<br><center><h3><font color='#ff0000'>$errorMsg<br><br>";
        echo _("Press browser Back button to return, edit, and try again");
        echo "</font></h3></center>";
        theme("", "footer");
   }
}




// -----------------------------------------------------------------------------

elseif (isset($_POST['saveAndQuit']) || isset($_POST['saveAndProject'])) {
   $errorMsg = saveProject($_POST);
   if (empty($errorMsg)) {
        if (isset($_POST['saveAndQuit'])) { metarefresh(0, "projectmgr.php", _("Save and Quit"), ""); }
        if (isset($_POST['saveAndProject'])) { metarefresh(0, "$code_url/project.php?id=$projectid", _("Save and Go To Project"), ""); }
   } else {
        theme(_("Project Error!"), "header");
        echo "<br><center><h3><font color='#ff0000'>$errorMsg<br><br>";
        echo _("Press browser Back button to return, edit, and try again");
        echo "</font></h3></center>";

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
        echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><b><font color='".$theme['color_headerbar_font']."'>Create a Project</font></b></center></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' colspan='2'><center><font color='".$theme['color_navbar_font']."'>Please put in as much information as possible to search for your project.  The more information the better but if not accurate enough may rule out results.</font></center></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>Title</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='title'></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>Author</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='author'></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>Publisher</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='publisher'></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>Publication Year (eg: 1912)</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='pubdate'></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>ISBN</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='isbn'></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>ISSN</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='issn'></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' width='35%'><b><font color='".$theme['color_navbar_font']."'>LCCN</font></b></td><td bgcolor='#FFFFFF'><input type='text' size='30' name='lccn'></td></tr>\n";
        echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><input type='submit' value='Search'></center></td></tr>\n</form>";
        echo "</table></center>";
   }
   theme("", "footer");
}

?>
