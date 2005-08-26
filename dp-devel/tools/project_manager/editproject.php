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
include_once('edit_common.inc');
include_once($relPath.'project_edit.inc');

$popHelpDir="$code_url/faq/pophelp/project_manager/";
include_once($relPath.'js_newpophelp.inc');

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
   if (!empty($_POST['special_code'])) {
      $special_code = $_POST['special_code'];
      if (    (strncmp($special_code, 'Birthday', 8) == 0)
           or (strncmp($special_code, 'Otherday', 8) == 0)) {
           if (empty($_POST['bdayday']) or empty($_POST['bdaymonth'])) {
              $errormsg .= "Month and Day are required for Birthday or Otherday Specials.<br>";
          } else {
             $bdaymonth = $_POST['bdaymonth'];
             $bdayday = $_POST['bdayday'];
             if (!checkdate ( $bdaymonth, $bdayday, 2000)) {
                 $errormsg .= "Invalid date supplied for Birthday or Otherday Special.<br>";
             } else {
                 if (strlen($special_code) == 8) { $special_code = $special_code." ".$bdaymonth.$bdayday; }
             }
          }
      }
   }
   if (!empty($_POST['image_source'])) {
      $image_source = $_POST['image_source'];
      if (strcmp($image_source, 'OTHER') == 0) {
         if (empty($_POST['imso_other'])) {
              $errormsg .= "When Image Source is OTHER, details must be supplied.<br>";
          } else {
             $imso_other = $_POST['imso_other'];
             $image_source = "O:".$imso_other;
          }
      }
   } else {
      $errormsg .= "Image Source is required.<br>";
   }

   if (isset($errormsg)) {
        return $errormsg;
        exit();
   }

   //Format the language as pri_language with sec_language if pri_language is set
   //Otherwise set just the pri_language
   if (!empty($_POST['sec_language'])) { $language = $_POST['pri_language']." with ".$_POST['sec_language']; } else { $language = $_POST['pri_language']; }

   $postednum = ($_POST['postednum'] == "") ? "NULL" : "'$_POST[postednum]'";

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
                postednum=$postednum,
                clearance='{$_POST['clearance']}',
                special_code='$special_code',
                image_source = '$image_source',
                up_projectid ='{$_POST['up_projectid']}'

            WHERE projectid='{$_POST['projectid']}'
        ");

        $projectid = $_POST['projectid'];

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
            SET
                nameofwork     = '{$_POST['nameofwork']}',
                authorsname    = '{$_POST['authorsname']}',
                checkedoutby   = '{$_POST['checkedoutby']}',
                language       = '$language',
                genre          = '{$_POST['genre']}',
                difficulty     = '{$_POST['difficulty_level']}',
                username       = '{$GLOBALS['pguser']}',
                comments       = '{$_POST['comments']}',
                projectid      = '$projectid',
                modifieddate   = UNIX_TIMESTAMP(),
                scannercredit  = '{$_POST['scannercredit']}',
                state          = '".PROJ_NEW."',
                clearance      = '{$_POST['clearance']}',
                special_code   = '$special_code',
                image_source   = '$image_source',
                up_projectid   = '{$_POST['up_projectid']}'
        ");

        project_allow_pages( $projectid );

        //Make a directory in the projects_dir for this project
        mkdir("$projects_dir/$projectid", 0777);
        chmod("$projects_dir/$projectid", 0777);

        //Add the original marc record to the database
        $original_marc = convert_standard_marc($rec);
        mysql_query("
            INSERT INTO marc_records
            SET
                projectid      = '$projectid',
                original_marc  = '".base64_encode(serialize($original_marc))."',
                original_array = '".base64_encode(serialize($rec))."'
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


function posted_pg($projectid) {
   global $site_url, $auto_email_addr, $auto_email_addr;

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
            ."$site_url"
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

  $a = _("The Guidelines give detailed instructions for working in this round.");
  $b = _('The instructions below are particular to this project, and <b>take precedence over those guidelines</b>.');

  echo "<br><table width='90%' border=1>";
  echo "<tr><td align='middle' bgcolor='#cccccc'><h3>Preview<br>Project</h3></td>";
  echo "<td bgcolor='#cccccc'><b>This is a preview of your project and roughly how it will look to the proofreaders.</b></td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Title</b></td><td>$nameofwork</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Author</b></td><td>$authorsname</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Project Manager</b></td><td>".$GLOBALS['pguser']."</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Last Proofread</b></td><td>".strftime(_("%A, %B %e, %Y at %X"))."</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Forum</b></td><td>Start a discussion about this project</td></tr>\n";
  echo "<tr><td align='middle' bgcolor='#cccccc'><b>Book Completed</b></td><td>Yes, I would like to be notified when this has been posted to Project Gutenberg.</td></tr>\n";

  echo "<tr><td colspan='2' bgcolor='#cccccc' align='center'>";
  echo "<font size='+1'><b>Project Comments</b></font>";
  echo "<br>$a<br>$b";
  echo "</td></tr>\n";
  echo "<tr><td colspan='2'>";
  echo $comments;
  echo "</td></tr>\n";

  echo "</table><br><br>";
}




// End of function definitions

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$requested_action = @$_REQUEST['action'];

if (isset($_POST['saveAndQuit']) || isset($_POST['saveAndProject'])) {
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

elseif ( $requested_action == "submit_marcsearch"
    || $requested_action == "createnew"
    || $requested_action == 'createnewfromuber'
    || $requested_action == 'edit'
    || isset($_REQUEST['saveAndPreview'])
) {

    // Within this if-clause, $header_shown is used to indicate whether or not
    // theme(..., 'header') has been run.
    $header_shown = false;


    if(isset($_POST['saveAndPreview'])) { $errorMsg = saveProject($_POST); }

    if (( ($requested_action == 'createnewfromuber') ||
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
                    $special_code = $up_info['d_special'];
                    $image_source = $up_info['d_image_source'];
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

    if($requested_action == 'edit' || isset($_REQUEST['saveAndPreview']) || isset($GLOBALS['projectid'])) {
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
            $special_code = mysql_result($result, 0, "special_code");
            $image_source = mysql_result($result, 0, "image_source");
            $up_projectid = mysql_result($result, 0, "up_projectid");

            // if there's an associated UP, get info on it 

            if (empty($up_projectid)) { $up_projectid =  $_POST['up_projectid'];}

            if (!empty($up_projectid)) {
                $result = mysql_query("SELECT up_nameofwork FROM uber_projects WHERE up_projectid = '$up_projectid'");
                $up_nameofwork = mysql_result($result, 0, "up_nameofwork");
            }

        }

        if (!$header_shown) { theme(_("Create a Project"), "header"); $header_shown = true;}
    }

    if (isset($_POST['rec']))
    {
        $rec = unserialize(base64_decode($_POST['rec']));
        if (empty($nameofwork)) { $nameofwork = marc_title($rec); }
        if (empty($authorsname)) { $authorsname = marc_author($rec); }
        if (empty($language)) { $language = marc_language($rec); }
        if (empty($genre)) { $genre = marc_literary_form($rec); }
    }
    if (empty($checkedoutby)) { $checkedoutby = ""; }
    if (empty($comments)) { $comments = ""; }
    if (empty($scannercredit)) { $scannercredit = ""; }
    if (empty($clearance)) { $clearance = ""; }
    if (empty($postednum)) { $postednum = ""; }
    if (empty($special_code)) { $special_code = ""; }
    if (empty($image_source)) { $image_source = "DP User"; }
    if (empty($difficulty_level)) { if ($pguser == "BEGIN") $difficulty_level = "beginner"; else $difficulty_level = "average"; }

    if ($requested_action == 'submit_marcsearch') {
        theme(_("Create a Project"), "header");
        $header_shown = true;
    }

    if (!$header_shown) {
        theme(_("Create a Project"), "header");
        $header_shown = true;
    }
    
    // Check if they're allowed to edit the info.
    // Don't run the check when $projectid is empty & the user is PM, because
    // they're creating a new project (but abort_etc will fail.)
    if( !empty( $projectid ) || !user_is_PM() )
    abort_if_cant_edit_project( $projectid );

    echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>";
    if (!empty($rec)) { echo "<input type='hidden' name='rec' value='".base64_encode(serialize($rec))."'>"; }
    if (isset($posted)) { echo "<input type='hidden' name='posted' value='1'>"; }
    if (!empty($projectid)) { echo "<input type='hidden' name='projectid' value='$projectid'>"; }
    if (isset($up_projectid)) { echo "<input type='hidden' name='up_projectid' value='$up_projectid'>"; }

    if (isset($errorMsg)) { echo "<br><center><font size='+1' color='#ff0000'><b>$errorMsg</b></font></center>"; }

    echo "<br><center><table cellspacing='0' cellpadding='5' border='1' width='90%' bordercolor='#000000' style='border-collapse:collapse'>";
    echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2'><center><b><font color='".$theme['color_headerbar_font']."'>"._("Create a New Project")."</font></b></center></td></tr>\n";

    function row( $label, $display_function, $field_value, $field_name=NULL )
    {
        echo "<tr>";
        echo   "<td bgcolor='#CCCCCC'>";
        echo     "<b>$label</b>";
        echo   "</td>";
        echo   "<td>";
        $display_function( $field_value, $field_name );
        echo   "</td>";
        echo "</tr>";
        echo "\n";
    }

    if (!empty($projectid))
    {
        row( _("Project ID"), 'just_echo', $projectid );
    }
    if (!empty($up_nameofwork))
    {
        row( _("Related Uber Project"), 'just_echo', $up_nameofwork );
    }
    row( _("Name of Work"),          'text_field',          $nameofwork,      'nameofwork' );
    row( _("Author's Name"),         'text_field',          $authorsname,     'authorsname' );
    row( _("Language"),              'language_list',       $language         );
    row( _("Genre"),                 'genre_list',          $genre            );
    row( _("Difficulty Level"),      'difficulty_list',     $difficulty_level );
    row( _("Special Day (optional)"),'special_list',        $special_code     );
    row( _("PPer/PPVer"),            'text_field',          $checkedoutby,    'checkedoutby' );
    row( _("Image Source"),          'image_source_list',   $image_source     );
    row( _("Image Scanner Credit"),  'text_field',          $scannercredit,   'scannercredit' );
    row( _("Clearance Information"), 'text_field',          $clearance,       'clearance' );
    row( _("Posted Number"),         'text_field',          $postednum,       'postednum' );
    row( _("Project Comments"),      'proj_comments_field', $comments         );

    echo "<tr><td bgcolor='#CCCCCC' colspan='2' align='center'><input type='submit' name='saveAndQuit' value='"._("Save and Quit")."'><input type='submit' name='saveAndProject' value='"._("Save and Go To Project")."'><input type='submit' name='saveAndPreview' value='"._("Save and Preview")."'><input type='button' value='"._("Quit Without Saving")."' onclick='javascript:location.href=\"projectmgr.php\";'></td></tr>\n</form>";
    echo "</table>";

    if(isset($_POST['saveAndPreview'])) {
        previewProject($nameofwork, $authorsname, $comments);
    }
        theme("", "footer");
}

// -----------------------------------------------------------------------------

else {
    die( "script invoked without necessary parameters" );
}

// vim: sw=4 ts=4 expandtab
?>
