<?PHP
$relPath='../../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once('edit_common.inc');

// -----------------------------------------------------------------------------

if ( isset($_REQUEST['action']) &&
    ( ($_REQUEST['action'] == 'createnewuber') || ($_REQUEST['action'] == 'edituber')  )
    ||
    isset($_REQUEST['saveUberAndReturn']))
{
    if (isset($_POST['saveUberAndReturn']))
    {
        $errorMsg = saveUberProject($_POST);
    }

    if ( isset($_REQUEST['saveUberAndReturn']) || isset($GLOBALS['up_projectid']) ||
        (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'edituber')))
    {

        if ( empty($GLOBALS['up_projectid']))
        {
            $up_projectid = $_POST['up_projectid'];
        }
        else
        {
            $up_projectid = $GLOBALS['up_projectid'];
        }

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edituber' && strlen($up_projectid) == 0)
        {
            $errorMsg = _("No Uber Project Id supplied to Edit - Bad Link" . $up_projectid);
        }
        else
        {
            $result = mysql_query("SELECT * FROM uber_projects WHERE up_projectid = '$up_projectid'") or die(mysql_error());
            if (mysql_num_rows($result))
            {
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
            }
            else
            {
                if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edituber')
                {
                    // invalid UP_ID supplied
                    $errorMsg .= _("Invalid Uber Project ID supplied");
                }
            }
        }
    }
    else
    {
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

    if (!empty($up_projectid))
    {
        echo "<tr><td bgcolor='$bgcol'><b>"._("Uber Project ID")."</b></td><td>$up_projectid<input type='hidden' name='up_projectid' value='".encodeFormValue($up_projectid)."'></td></tr>\n";
    }

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

elseif (isset($_POST['saveUberAndQuit']) || isset($_POST['saveUberAndNewProject']) )
{
    $errorMsg = saveUberProject($_POST);

    if (empty($errorMsg))
    {
        if (isset($_POST['saveUberAndQuit']))
        {
            metarefresh(0, "projectmgr.php", _("Save Uber Project and Quit"), "");
        }
        else
        {
            metarefresh(0, "editproject.php?action=createnewfromuber&up_projectid=".$up_projectid, _("Save Uber Project and Create New Project"), "");
        }
    }
    else
    {
        theme(_("Uber Project Error!"), "header");
        echo "<br><center><h3><font color='#ff0000'>$errorMsg<br><br>";
        echo _("Press browser Back button to return, edit, and try again");
        echo "</font></h3></center>";
        theme("", "footer");
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function saveUberProject()
{
    global $pguser;

    //Let's check to make sure everything is correct & there are no errors
    if (empty($_POST['up_nameofwork'])) { $errormsg .= "Overall Name of Uber Project is required.<br>"; }

    if (!empty($_POST['checkedoutby']))
    {
        $checkedoutby = $_POST['checkedoutby'];
        $result = mysql_query("SELECT u_id FROM users WHERE BINARY username = '$checkedoutby'");
        if (mysql_num_rows($result) == 0)
        {
            $errormsg .= "Default Post Processor must be an existing user - check case and spelling of username.<br>";
        }
    }

/*
    if (!empty($_POST['up_topic_id']))
    {
        $up_topic_id = $_POST['up_topic_id'];
        $result = mysql_query("SELECT forum_id FROM phpbb_topics WHERE topic_id = '$up_topic_id'");
        if (mysql_num_rows($result) == 0)
        {
            $errormsg .= "Uber Project Topic must already exist - check topic id.<br>";
        }
    }

`up_topic_id` int(10) default NULL,
`up_contents_post_id` int(10) default NULL,

*/

    if (!empty($_POST['special']))
    {
        $special = $_POST['special'];
        if ( (strncmp($special, 'Birthday', 8) == 0)
            or (strncmp($special, 'Otherday', 8) == 0))
        {
            if (empty($_POST['bdayday']) or empty($_POST['bdaymonth']))
            {
                $errormsg .= "Month and Day are required for Default Special of Birthday or Otherday.<br>";
            }
            else
            {
                $bdaymonth = $_POST['bdaymonth'];
                $bdayday = $_POST['bdayday'];
                if (!checkdate ( $bdaymonth, $bdayday, 2000))
                {
                    $errormsg .= "Invalid date supplied for Default Special of Birthday or Otherday.<br>";
                }
                else
                {
                    if (strlen($special) == 8)
                    {
                        $special = $special." ".$bdaymonth.$bdayday;
                    }
                }
            }
        }
    }

    if (!empty($_POST['image_provider']))
    {
        $image_provider = $_POST['image_provider'];
        if (strcmp($image_provider, 'OTHER') == 0)
        {
            if (empty($_POST['imp_other']))
            {
                $errormsg .= "When Default Image Provider is OTHER, details must be supplied.<br>";
            }
            else
            {
                $imp_other = $_POST['imp_other'];
                $image_provider = "O:".$imp_other;
            }
        }
    }

    if (isset($errormsg))
    {
        return $errormsg;
        exit();
    }

    //Format the language as pri_language with sec_language if pri_language is set
    //Otherwise set just the pri_language
    if (!empty($_POST['sec_language']))
    {
        $language = $_POST['pri_language']." with ".$_POST['sec_language'];
    }
    else
    {
        $language = $_POST['pri_language'];
    }

    //If we are just updating an already existing uber project
    if (isset($_POST['up_projectid']))
    {
        //Update the uber project database table with the updated info
        mysql_query("
            UPDATE uber_projects
            SET
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

    }
    else
    {
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

        // '{$GLOBALS['pguser']}'

        // if topic / post IDs are blank :
        // create the auto uber post and/or the auto contents post ?
        // update uber_projects table with topic and/or post IDs
    }
}

// vim: sw=4 ts=4 expandtab
?>
