<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'marc_format.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'comment_inclusions.inc');
include_once('edit_common.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'project_events.inc');
include_once($relPath.'wordcheck_engine.inc');

$return = array_get($_REQUEST,"return","$code_url/tools/project_manager/projectmgr.php");

if ( !user_is_PM() )
{
    die('permission denied');
}

$pih = new ProjectInfoHolder;

if (isset($_POST['saveAndQuit']) || isset($_POST['saveAndProject']) || isset($_POST['saveAndPreview']) )
{
    $errors = $pih->set_from_post();
    if (empty($errors))
    {
        $pih->save_to_db();
        if (isset($_POST['saveAndQuit']))
        {
            metarefresh(0, "projectmgr.php", _("Save and Go To PM Page"), "");
            exit;
        }
        elseif (isset($_POST['saveAndProject']))
        {
            metarefresh(0, "$code_url/project.php?id=$pih->projectid", _("Save and Go To Project"), "");
            exit;
        }
        elseif (isset($_POST['saveAndPreview']))
        {
            // No errors, but fall through.
        }
    }
    else
    {
        // Errors.
        // fall through
    }

    if ( isset($pih->projectid) )
    {
        $page_title = _("Edit a Project");
    }
    else
    {
        // we're creating a new project
        check_user_can_load_projects(true); // exit if they can't
        if ( isset($pih->up_projectid) )
        {
            $page_title = _("Create a Project from an Uber Project");
        }
        elseif ( isset($pih->original_marc_array_encd) )
        {
            $page_title = _("Create a Project from a MARC Record");
        }
        else
        {
            $page_title = _("Create a Project");
        }
    }

    $no_stats=1;
    theme($page_title, "header");
    echo "<br><h2 align='center'>$page_title</h2>\n";

    if ($errors != '')
    {
        echo "<br><center><font size='+1' color='#ff0000'><b>$errors</b></font></center>";
    }

    $pih->show_form();

    if ( isset($_POST['saveAndPreview']))
    {
        $pih->preview();
    }

    theme("", "footer");
}
elseif (isset($_POST['quit']))
{
    // if return is empty for whatever reason take them to
    // the PM page
    if(empty($return))
        $return="$code_url/tools/project_manager/projectmgr.php";

    // do the redirect
    metarefresh(0, $return, _("Quit without Saving"), "");
    exit;
}
else
{
    $requested_action = @$_REQUEST['action'];
    if ( $requested_action == 'createnew'
         || $requested_action == 'clone'
         || $requested_action == 'createnewfromuber'
         || $requested_action == 'create_from_marc_record') 
    {
        check_user_can_load_projects();
    }
    if ( $requested_action == 'createnew' )
    {
        $page_title = _("Create a Project");
        $fatal_error = $pih->set_from_nothing();
    }
    elseif ( $requested_action == 'clone' )
    {
        $page_title = _("Clone a Project");
        $fatal_error = $pih->set_from_db(FALSE);
    }
    elseif ( $requested_action == 'createnewfromuber' )
    {
        $page_title = _("Create a Project from an Uber Project");
        $fatal_error = $pih->set_from_uberproject();
    }
    elseif ( $requested_action == 'create_from_marc_record' )
    {
        $page_title = _("Create a Project from a MARC Record");
        $fatal_error = $pih->set_from_marc_record();
    }
    elseif ( $requested_action == 'edit' )
    {
        $page_title = _("Edit a Project");
        $fatal_error = $pih->set_from_db(TRUE);
    }
    else
    {
        $page_title = 'editproject.php';
        $fatal_error = _("parameter 'action' is invalid") . ": '$requested_action'";
    }

    $no_stats=1;
    theme($page_title, "header");
    echo "<br><h2 align='center'>$page_title</h2>\n";

    if ($fatal_error != '')
    {
        $fatal_error = _('site error') . ': ' . $fatal_error;
        echo "<br><center><font size='+1' color='#ff0000'><b>$fatal_error</b></font></center>";
        theme('', 'footer');
        exit;
    }

    $pih->show_form();

    theme("", "footer");
}

// returns an empty string if the possible user exists,
// otherwise an error message
function check_user_exists($possible_user, $description)
{
    $result = '';
    $res = mysql_query("
                SELECT u_id
                FROM users
                WHERE username = BINARY '".addslashes($possible_user)."'
            ");
    if (mysql_num_rows($res) == 0)
    {
        $result = "$description must be an existing user - check case and spelling of username.<br>";
    }
    return $result;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class ProjectInfoHolder
{
    function set_from_nothing()
    {
        global $pguser;

        $this->nameofwork       = '';
        $this->authorsname      = '';
        $this->projectmanager   = $pguser;
        $this->checkedoutby     = '';
        $this->language         = '';
        $this->scannercredit    = '';
        $this->comments         = '';
        $this->good_words       = '';
        $this->bad_words        = '';
        $this->clearance        = '';
        $this->postednum        = '';
        $this->genre            = '';
        $this->difficulty_level = ( $pguser == "BEGIN" ? "beginner" : "average" );
        $this->special_code     = '';
        $this->image_source     = '_internal';
        $this->image_preparer   = $pguser;
        $this->text_preparer    = $pguser;
        $this->extra_credits    = '';
        $this->deletion_reason  = '';
        // $this->year          = '';
    }

    // -------------------------------------------------------------------------

    function set_from_marc_record()
    {
        global $pguser;

        if (!isset($_POST['rec']))
        {
            return _("parameter 'rec' is unset");
        }

        $r1 = $_POST['rec'];
        if ( $r1 == '' )
        {
            return _("parameter 'rec' is empty");
        }

        $r2 = base64_decode($r1);
        if ( $r2 === FALSE )
        {
            return _("parameter 'rec' cannot be decoded");
        }

        $r3 = unserialize($r2);
        if ( $r3 === FALSE )
        {
            return _("parameter 'rec' cannot be unserialized");
        }

        $this->nameofwork  = marc_title($r3);
        $this->authorsname = marc_author($r3);
        $this->projectmanager = $pguser;
        $this->language    = marc_language($r3);
        $this->genre       = marc_literary_form($r3);

        $this->checkedoutby     = '';
        $this->scannercredit    = '';
        $this->comments         = '';
        $this->good_words       = '';
        $this->bad_words        = '';
        $this->clearance        = '';
        $this->postednum        = '';
        $this->difficulty_level = ( $pguser == "BEGIN" ? "beginner" : "average" );
        $this->special_code     = '';
        $this->image_source     = '_internal';
        $this->image_preparer   = $pguser;
        $this->text_preparer    = $pguser;
        $this->extra_credits    = '';
        $this->deletion_reason  = '';

        $this->original_marc_array_encd = $r1;
    }

    // -------------------------------------------------------------------------

    function set_from_uberproject()
    {
        global $pguser;
        if (!isset($_GET['up_projectid']))
        {
            return _("parameter 'up_projectid' is unset");
        }

        $up_projectid = $_GET['up_projectid'];
        if ( $up_projectid == '' )
        {
            return _("parameter 'up_projectid' is empty");
        }

        $result = mysql_query("SELECT * FROM uber_projects WHERE up_projectid = $up_projectid");
        if (mysql_num_rows($result) == 0)
        {
            return _("parameter 'up_projectid' is invalid") . ": '$up_projectid'";
        }

        // TODO: check that user has permission to create a project from this UP

        $up_info = mysql_fetch_assoc($result);

        $this->nameofwork       = $up_info['d_nameofwork'];
        $this->authorsname      = $up_info['d_authorsname'];
        $this->projectmanager   = $pguser;
        $this->checkedoutby     = $up_info['d_checkedoutby'];
        $this->language         = $up_info['d_language'];
        $this->scannercredit    = $up_info['d_scannercredit'];
        $this->comments         = $up_info['d_comments'];
        $this->good_words       = '';
        $this->bad_words        = '';
        $this->clearance        = $up_info['d_clearance'];
        $this->postednum        = $up_info['d_postednum'];
        $this->genre            = $up_info['d_genre'];
        $this->difficulty_level = $up_info['d_difficulty'];
        $this->special_code     = $up_info['d_special'];
        $this->image_source     = $up_info['d_image_source'];
        $this->image_preparer   = $up_info['d_image_preparer'];
        $this->text_preparer    = $up_info['d_text_preparer'];
        $this->extra_credits    = $up_info['d_extra_credits'];
        $this->deletion_reason  = '';

        // $this->year          = $up_info['d_year'];

        $this->up_projectid     = $up_projectid;
    }

    // -------------------------------------------------------------------------
    // edit an existing project, or create a new project by
    // cloning an existing project
    function set_from_db($edit_existing, $projectid='')
    {
        if (!isset($_GET['project']) && $projectid == '')
        {
            return _("parameter 'project' is unset");
        }

        if ( $projectid == '' )
        {
            $projectid = $_GET['project'];
        }
        if ( $projectid == '' )
        {
            return _("parameter 'project' is empty");
        }

        $ucep_result = user_can_edit_project($projectid);
        // we only let people clone projects that they can edit, so this
        // is valid whether they are cloning or editing
        if ( $ucep_result == PROJECT_DOES_NOT_EXIST )
        {
            return _("parameter 'project' is invalid: no such project").": '$projectid'";
        }
        else if ( $ucep_result == USER_CANNOT_EDIT_PROJECT )
        {
            return _("you are not allowed to edit this project").": '$projectid'";
        }
        else if ( $ucep_result == USER_CAN_EDIT_PROJECT )
        {
            // fine
        }
        else
        {
            return _("unexpected return value from user_can_edit_project") . ": '$ucep_result'";
        }

        $res = mysql_query("SELECT * FROM projects WHERE projectid = '$projectid'");
        if (mysql_num_rows($res) == 0)
        {
            return _("parameter 'project' is invalid") . ": '$projectid'";
        }

        $ar = mysql_fetch_array($res);

        $this->nameofwork       = $ar['nameofwork'];
        $this->projectmanager   = $ar['username'];
        $this->authorsname      = $ar['authorsname'];
        $this->checkedoutby     = $ar['checkedoutby'];
        $this->language         = $ar['language'];
        $this->scannercredit    = $ar['scannercredit'];
        $this->comments         = $ar['comments'];
        $this->clearance        = $ar['clearance'];
        $this->genre            = $ar['genre'];
        $this->difficulty_level = $ar['difficulty'];
        $this->special_code     = $ar['special_code'];
        $this->image_source     = $ar['image_source'];
        $this->image_preparer   = $ar['image_preparer'];
        $this->text_preparer    = $ar['text_preparer'];
        $this->extra_credits    = $ar['extra_credits'];
        if ($edit_existing) 
        {
            $this->projectid        = $ar['projectid'];
            $this->deletion_reason  = $ar['deletion_reason'];
            $this->posted           = @$_GET['posted'];        
            $this->postednum        = $ar['postednum'];
        }
        else
        {
            // we're cloning, so leave projectid unset
            $this->postednum        = '';
            $this->deletion_reason  = '';
            $this->clone_projectid = $ar['projectid'];
        }
        $this->up_projectid     = $ar['up_projectid'];

        // load non-db project settings
        // Failure to load isn't a fatal error, according to this code.

        // the word list loading code is needed for cloning purposes
        // not because this page allows us to edit word lists
        if($edit_existing)
        {
            $good_words = load_project_good_words($this->projectid);
            $bad_words=load_project_bad_words($this->projectid);
        }
        else
        {
            // we're cloaning, load the original project's words
            $good_words = load_project_good_words($this->clone_projectid);
            $bad_words=load_project_bad_words($this->clone_projectid);
        }

        if ( is_string($good_words) )
        {
            echo "$good_words<br>\n";
            $this->good_words = '';
        }
        else
        {
            $this->good_words = implode("\n", $good_words);
        }

        if ( is_string($bad_words) )
        {
            echo "$bad_words<br>\n";
            $this->bad_words = '';
        }
        else
        {
            $this->bad_words = implode("\n", $bad_words);
        }
    }

    // -------------------------------------------------------------------------

    function set_from_post()
    {
        if ( get_magic_quotes_gpc() )
        {
            // Values in $_POST come with backslashes added.
            // We want the fields of $this to be unescaped strings,
            // so we strip the slashes.
            $_POST = array_map('stripslashes', $_POST);
        }

        $errors = '';

        if ( isset($_POST['projectid']) )
        {
            $this->projectid = $_POST['projectid'];

            $ucep_result = user_can_edit_project($this->projectid);
            if ( $ucep_result == PROJECT_DOES_NOT_EXIST )
            {
                return _("parameter 'projectid' is invalid: no such project").": '$this->projectid'";
            }
            else if ( $ucep_result == USER_CANNOT_EDIT_PROJECT )
            {
                return _("you are not allowed to edit this project").": '$this->projectid'";
            }
            else if ( $ucep_result == USER_CAN_EDIT_PROJECT )
            {
                // fine
            }
            else
            {
                return _("unexpected return value from user_can_edit_project") . ": '$ucep_result'";
            }
        }
        else if ( isset($_POST['clone_projectid']) )
        {
            // we're creating a clone
            $this->clone_projectid = $_POST['clone_projectid'];
        }

        $this->nameofwork = @$_POST['nameofwork'];
        if ( $this->nameofwork == '' ) { $errors .= "Name of work is required.<br>"; }

        $this->authorsname = @$_POST['authorsname'];
        if ( $this->authorsname == '' ) { $errors .= "Author is required.<br>"; }

        if ( user_is_a_sitemanager() )  // only SAs can change PM
        {
            $this->projectmanager = @$_POST['username'];
            if ( $this->projectmanager == '' )
            {
                $errors .= "Project manager is required.<br>";
            }
            else
            {
                $errors .= check_user_exists($this->projectmanager, 'Project manager');
            }
            if ( empty($errors) && !that_user_is_PM($this->projectmanager) )
            {
                $errors .= "{$this->projectmanager} is not a PM.<br>";
            }
        }
        else // it'll be set when we save the info to the db
        {
            $this->projectmanager = '';
        }

        $pri_language = @$_POST['pri_language'];
        if ( $pri_language == '' ) { $errors .= "Primary Language is required.<br>"; }

        $sec_language = @$_POST['sec_language'];

        $this->language = (
            $sec_language != ''
            ? "$pri_language with $sec_language"
            : $pri_language );

        $this->genre = @$_POST['genre'];
        if ( $this->genre == '' ) { $errors .= "Genre is required.<br>"; }

        $this->image_source = @$_POST['image_source'];
        if ($this->image_source == '')
        {
            $errors .= "Image Source is required. If the one you want isn't in list, you can propose to add it.<br>";
            $this->image_source = '_internal';
        }

	/*
        else
        {
            if ($this->image_source == 'OTHER')
            {
                if (empty($_POST['imso_other']))
                {
                    $errors .= "When Image Source is OTHER, details must be supplied.<br>";
                }
                else
                {
                    $imso_other = $_POST['imso_other'];
                    $this->image_source = "O:".$imso_other;
                }
            }
        }

	*/


        $this->special_code = @$_POST['special_code'];
        if ($this->special_code != '')
        {
            if ( startswith($this->special_code, 'Birthday') ||
                 startswith($this->special_code, 'Otherday')
            )
            {
                if (empty($_POST['bdayday']) or empty($_POST['bdaymonth']))
                {
                    $errors .= "Month and Day are required for Birthday or Otherday Specials.<br>";
                }
                else
                {
                    $bdaymonth = $_POST['bdaymonth'];
                    $bdayday = $_POST['bdayday'];
                    if (!checkdate ( $bdaymonth, $bdayday, 2000))
                    {
                        $errors .= "Invalid date supplied for Birthday or Otherday Special.<br>";
                    }
                    else
                    {
                        if (strlen($this->special_code) == 8) { $this->special_code .= " ".$bdaymonth.$bdayday; }
                    }
                }
            }
        }

        $this->checkedoutby = @$_POST['checkedoutby'];
        if ($this->checkedoutby != '')
        {
            // make sure the named PPer/PPVer actually exists
            $errors .= check_user_exists($this->checkedoutby, 'PPer/PPVer');
        }
        else if ( isset($this->projectid) )
        {
            // don't allow an empty PPer/PPVer if the project is checked out
            // Somewhat kludgey to have to do this query here.
            $res = mysql_query("
                SELECT state, checkedoutby, username
                FROM projects
                WHERE projectid='{$this->projectid}'
            ") or die(mysql_error());
            list($state, $PPer, $PM) = mysql_fetch_row($res);
            if ( $state == PROJ_POST_FIRST_CHECKED_OUT ||
                 $state == PROJ_POST_SECOND_CHECKED_OUT ) 
            {
                $errors .= "This project is checked out: you must specify a PPer/PPVer";
                $this->checkedoutby = $PPer;
            }
            if ( $this->projectmanager == '' )
            {
                $this->projectmanager = $PM;
            }
        }
        $this->image_preparer = @$_POST['image_preparer'];
        if ($this->image_preparer != '')
        {
            $errors .= check_user_exists($this->image_preparer,'Image Preparer') ;
        }

        $this->text_preparer = @$_POST['text_preparer'];
        if ($this->text_preparer != '')
        {
            $errors .= check_user_exists($this->text_preparer,'Text Preparer') ;
        }

        $this->posted    = @$_POST['posted'];
        $this->postednum = @$_POST['postednum'];
        if ( $this->posted )
        {
            // We are in the process of marking this project as posted.
            if ( $this->postednum == '' )
            {
                $errors .= "Posted Number is required.<br>";
            }
            else if ( ! preg_match('/^[1-9][0-9]*$/', $this->postednum ) )
            {
                $errors .= "Posted Number \"$this->postednum\" is not of the correct format.<br>";
            }
        }

        $this->scannercredit    = @$_POST['scannercredit'];
        $this->comments         = @$_POST['comments'];
        $this->good_words       = @$_POST['good_words'];
        $this->bad_words        = @$_POST['bad_words'];
        $this->clearance        = @$_POST['clearance'];
        $this->difficulty_level = @$_POST['difficulty_level'];
        $this->up_projectid     = @$_POST['up_projectid'];
        $this->original_marc_array_encd = @$_POST['rec'];
        $this->extra_credits    = @$_POST['extra_credits'];
        $this->deletion_reason  = @$_POST['deletion_reason'];

        if ($this->difficulty_level == '')
        {
            global $pguser;
            $this->difficulty_level = ( $pguser == "BEGIN" ? "beginner" : "average" );
        }
        return $errors;
    }

    // -------------------------------------------------------------------------

    function save_to_db()
    {
        global $projects_dir, $uploads_dir, $pguser;

        $postednum_str = ($this->postednum == "") ? "NULL" : "'$this->postednum'";

        // Call addslashes() on any members of $this that might contain 
        // single-quotes/apostrophes (because they are unescaped, and
        // would otherwise break the query).

        $common_project_settings = "
            t_last_edit    = UNIX_TIMESTAMP(),
            up_projectid   = '{$this->up_projectid}',
            nameofwork     = '".addslashes($this->nameofwork)."',
            authorsname    = '".addslashes($this->authorsname)."',
            language       = '{$this->language}',
            genre          = '{$this->genre}',
            difficulty     = '{$this->difficulty_level}',
            special_code   = '{$this->special_code}',
            clearance      = '".addslashes($this->clearance)."',
            comments       = '".addslashes($this->comments)."',
            image_source   = '{$this->image_source}',
            scannercredit  = '".addslashes($this->scannercredit)."',
            checkedoutby   = '{$this->checkedoutby}',
            postednum      = $postednum_str,
            image_preparer = '{$this->image_preparer}',
            text_preparer  = '{$this->text_preparer}',
            extra_credits  = '".addslashes($this->extra_credits)."',
            deletion_reason= '".addslashes($this->deletion_reason)."'
        ";
        $pm_setter = '';
        if ( user_is_a_sitemanager() )
        {
            // can change PM
            $pm_setter = " username = '{$this->projectmanager}',";
        }
        else if ( isset($this->clone_projectid) )
        {
            // cloning a project. The PM should be the same as 
            // that of the project being cloned, if the user
            // isn't an SA
            $res = mysql_query("
                SELECT username
                FROM projects
                WHERE projectid='{$this->clone_projectid}'
            ") or die(mysql_error());
            list($projectmanager) = mysql_fetch_row($res);

            $pm_setter = " username = '$projectmanager',";
        }

        if (isset($this->projectid))
        {
            // We are updating an already-existing project.

            // needn't change $pm_setter, as there is no change if the user
            // isn't an SA

            // find out what we are changing from
            $old_pih = new ProjectInfoHolder;
            $fatal_error = $old_pih->set_from_db(TRUE, $this->projectid);
            if ($fatal_error != '')
            {
                $fatal_error = _('site error') . ': ' . $fatal_error;
                echo "<br><center><font size='+1' color='#ff0000'><b>$fatal_error</b></font></center>";
                theme('', 'footer');
                exit;
            }
            $changed_fields = get_changed_fields($this, $old_pih);

            // We're particularly interested in knowing
            // when the project comments change.
            if ( strpos($changed_fields, 'Project Comments') == FALSE )
            {
                // no change
                $tlcc_setter = '';
            }
            else
            {
                // changed!
                $tlcc_setter = 't_last_change_comments = UNIX_TIMESTAMP(),';
            }

            // Update the projects database with the updated info
            mysql_query("
                UPDATE projects SET
                    $pm_setter
                    $tlcc_setter
                    $common_project_settings
                WHERE projectid='{$this->projectid}'
            ") or die(mysql_error());

            $e = log_project_event( $this->projectid, $GLOBALS['pguser'], 'edit', $changed_fields );
            if ( !empty($e) ) die($e);

            $result = mysql_query("
                SELECT updated_array
                FROM marc_records
                WHERE projectid = '{$this->projectid}'
            ");
            $current_marc_array_encd = mysql_result($result,0,"updated_array");
            $current_marc_array = unserialize(base64_decode($current_marc_array_encd));

            // Update the MARC array with any info we've received.
            $updated_marc_array = update_marc_array($current_marc_array);
            $updated_marc_str = convert_marc_array_to_str($updated_marc_array);

            mysql_query("
                UPDATE marc_records
                SET
                    updated_array = '".base64_encode(serialize($updated_marc_array))."',
                    updated_marc  = '".base64_encode(serialize($updated_marc_str))."'
                WHERE projectid = '$this->projectid'
            ");
        }
        else
        {
            // We are creating a new project
            $this->projectid = uniqid("projectID"); // The project ID

            if ( '' == $pm_setter) {
                $pm_setter = "username = '$pguser',";
            }

            // Insert a new row into the projects table
            mysql_query("
                INSERT INTO projects
                SET
                    projectid    = '{$this->projectid}',
                    $pm_setter
                    state        = '".PROJ_NEW."',
                    modifieddate = UNIX_TIMESTAMP(),
                    t_last_change_comments = UNIX_TIMESTAMP(),
                    $common_project_settings
            ") or die(mysql_error());

            $e = log_project_event( $this->projectid, $GLOBALS['pguser'], 'creation' );
            if ( !empty($e) ) die($e);

            $e = project_allow_pages( $this->projectid );
            if ( !empty($e) ) die($e);

            // Make a directory in the projects_dir for this project
            mkdir("$projects_dir/$this->projectid", 0777) or die("System error: unable to mkdir '$projects_dir/$this->projectid'");
            chmod("$projects_dir/$this->projectid", 0777);

            $original_marc_array = unserialize(base64_decode($this->original_marc_array_encd));
            $original_marc_str = convert_marc_array_to_str($original_marc_array);

            // Update the MARC array with any info we've received.
            $updated_marc_array = update_marc_array($original_marc_array);
            $updated_marc_str = convert_marc_array_to_str($updated_marc_array);

            mysql_query("
                INSERT INTO marc_records
                SET
                    projectid      = '$this->projectid',
                    original_array = '".base64_encode(serialize($original_marc_array))."',
                    original_marc  = '".base64_encode(serialize($original_marc_str))."',
                    updated_array  = '".base64_encode(serialize($updated_marc_array))."',
                    updated_marc   = '".base64_encode(serialize($updated_marc_str))."'
            ");
        }

        // save non-database information, like the custom dictonaries

        // this code is needed to support project cloning, not because
        // this page allows editing of the word lists

        // explode the strings into an array
        $good_words = explode("[lf]",str_replace(array("\r","\n"),array('',"[lf]"),$this->good_words));
        $bad_words = explode("[lf]",str_replace(array("\r","\n"),array('',"[lf]"),$this->bad_words));

        save_project_good_words($this->projectid, $good_words);
        save_project_bad_words($this->projectid, $bad_words);

// TODO not scannercredit below!

        // Create/update the Dublin Core file for the project.
        create_dc_xml_oai(
            $this->projectid,
            $this->scannercredit,
            $this->genre,
            $this->language,
            $this->authorsname,
            $this->nameofwork,
            $updated_marc_array );

        // If the project has been posted to PG, make the appropriate transition.
        if ($this->posted)
        {
            $err = project_transition( $this->projectid, PROJ_SUBMIT_PG_POSTED, $pguser );
            if ( $err != '' )
            {
                echo "$err<br>\n";
                exit;
            }
        }
    }

    // =========================================================================

    function show_form()
    {
        global $theme;

        echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>";

        $this->show_hidden_controls();

        echo "<br>";
        echo "<center>";
        echo "<table cellspacing='0' cellpadding='5' border='1' width='90%' bordercolor='#000000' style='border-collapse:collapse'>";

        $this->show_visible_controls();

        echo "<tr>";
        echo   "<td bgcolor='#CCCCCC' colspan='2' align='center'>";
        echo     "<input type='submit' name='saveAndQuit' value='"._("Save and Go To PM Page")."'>";
        echo     "<input type='submit' name='saveAndProject' value='"._("Save and Go To Project")."'>";
        echo     "<input type='submit' name='saveAndPreview' value='"._("Save and Preview")."'>";
        echo     "<input type='submit' name='quit' value='"._("Quit Without Saving")."'>";
        echo   "</td>";
        echo "</tr>\n";

        echo "</table>";
        echo "</center>";
        echo "</form>";
        echo "\n";
    }

    // -------------------------------------------------------------------------

    function show_hidden_controls()
    {
        global $return;

        if (!empty($this->original_marc_array_encd))
        {
            echo "<input type='hidden' name='rec' value='$this->original_marc_array_encd'>";
        }
        if (!empty($this->posted))
        {
            echo "<input type='hidden' name='posted' value='1'>";
        }
        if (!empty($this->projectid))
        {
            echo "<input type='hidden' name='projectid' value='$this->projectid'>";
        }
        if (!empty($this->up_projectid))
        {
            echo "<input type='hidden' name='up_projectid' value='$this->up_projectid'>";
        }
        if (!empty($this->clone_projectid))
        {
            echo "<input type='hidden' name='clone_projectid' value='$this->clone_projectid'>";
        }
        if (!empty($this->good_words))
        {
            echo "<input type='hidden' name='good_words' value='" . htmlentities($this->good_words,ENT_QUOTES) . "'>";
        }
        if (!empty($this->bad_words))
        {
            echo "<input type='hidden' name='bad_words' value='" . htmlentities($this->bad_words,ENT_QUOTES) . "'>";
        }
        echo "<input type='hidden' name='return' value='$return'>";
    }

    // -------------------------------------------------------------------------

    function show_visible_controls()
    {
        global $site_abbreviation;
        $can_edit_PPer = TRUE;
        $is_checked_out = FALSE;
        if (!empty($this->projectid))
        {
            $this->row( _("Project ID"), 'just_echo', $this->projectid );

            // do some things that depend on the project state

            // Somewhat kludgey to have to do this query here.
            $res = mysql_query("
                SELECT state
                FROM projects
                WHERE projectid='{$this->projectid}'
            ") or die(mysql_error());
            list($state) = mysql_fetch_row($res);
            if ($state == PROJ_DELETE)
            {
                $this->row( _("Reason for Deletion"),         'text_field',          $this->deletion_reason, 'deletion_reason' );
            }
            else if ( $state == PROJ_POST_FIRST_CHECKED_OUT )
            {
                // once the project is in PP, PPer can only be changed by an SA, PF, 
                // or if it's checked out to the PM
                $is_checked_out = TRUE;
                $can_edit_PPer = ( ($this->projectmanager == $this->checkedoutby) || 
                                   user_is_a_sitemanager() ||
                                   user_is_proj_facilitator());
            }
            else if ( $state == PROJ_POST_SECOND_CHECKED_OUT )
            {
                $is_checked_out = TRUE;
                $can_edit_PPer = user_is_a_sitemanager();
            }
        }
        if (!empty($this->up_projectid))
        {
            $res2 = mysql_query("
                SELECT up_nameofwork
                FROM uber_projects
                WHERE up_projectid = '$this->up_projectid'
            ");
            $up_nameofwork = mysql_result($res2, 0, "up_nameofwork");

            $this->row( _("Related Uber Project"), 'just_echo', $up_nameofwork );
        }
        $this->row( _("Name of Work"),                'text_field',          $this->nameofwork,      'nameofwork' );
        $this->row( _("Author's Name"),               'text_field',          $this->authorsname,     'authorsname' );
        if ( user_is_a_sitemanager() )
        {
            // SAs are the only ones who can change this
            $this->row( _("Project Manager"),         'DP_user_field',       $this->projectmanager,  'username', sprintf(_("%s username only."),$site_abbreviation));
        }
        $this->row( _("Language"),                    'language_list',       $this->language         );
        $this->row( _("Genre"),                       'genre_list',          $this->genre            );
        $this->row( _("Difficulty Level"),            'difficulty_list',     $this->difficulty_level );
        $this->row( _("Special Day (optional)"),      'special_list',        $this->special_code     );
        if ( $can_edit_PPer )
        {
            $this->row( _("PPer/PPVer"),                  'DP_user_field',       $this->checkedoutby,    'checkedoutby' , sprintf(_("Optionally reserve for a PPer. %s username only."),$site_abbreviation));
        }
        else
        {
            $this->row( _("PPer/PPVer"),                  'just_echo',       $this->checkedoutby);
            echo "<input type='hidden' name='checkedoutby' value='$this->projectid'>";
        }
        $this->row( _("Original Image Source"),       'image_source_list',   $this->image_source     );
        $this->row( _("Image Preparer"),              'DP_user_field',       $this->image_preparer,  'image_preparer', sprintf(_("%s user who scanned or harvested the images."),$site_abbreviation));
        $this->row( _("Text Preparer"),               'DP_user_field',       $this->text_preparer,   'text_preparer', sprintf(_("%s user who prepared the text files."),$site_abbreviation) );
        $this->row( _("Extra Credits<br>(to be included in list of names)"),   
                                               'extra_credits_field', $this->extra_credits);
        if ($this->scannercredit != '') {
            $this->row( _("Scanner Credit (deprecated)"), 'text_field',      $this->scannercredit,   'scannercredit' );
        }
        $this->row( _("Clearance Information"),       'text_field',          $this->clearance,       'clearance' );
        $this->row( _("Posted Number"),               'text_field',          $this->postednum,       'postednum' );
        $this->row( _("Project Comments"),            'proj_comments_field', $this->comments         );
        // don't show the word list line if we're in the process of cloning
        if(!empty($this->projectid)) {
            $this->row( _("Project Dictionary"),  'word_lists',  null,  null,  '', $this->projectid);
        }
    }

    function row( $label, $display_function, $field_value, $field_name=NULL, $explan='', $args='' )
    {
        echo "<tr>";
        echo   "<td bgcolor='#CCCCCC'>";
        echo     "<b>$label</b>";
        echo   "</td>";
        echo   "<td>";
        $display_function( $field_value, $field_name, $args );
        echo   "  ";
        echo   $explan;
        echo   "</td>";
        echo "</tr>";
        echo "\n";
    }

    // =========================================================================

    function preview()
    {
        global $pguser;

        // insert e.g. templates and biographies
        $comments = parse_project_comments($this->comments);

        $a = _("The Guidelines give detailed instructions for working in this round.");
        $b = _('The instructions below are particular to this project, and <b>take precedence over those guidelines</b>.');

        $now = strftime(_("%A, %B %e, %Y at %X"));

        echo "<br><table width='90%' border=1>";
        echo "<tr><td align='middle' bgcolor='#cccccc'><h3>Preview<br>Project</h3></td>";
        echo "<td bgcolor='#cccccc'><b>This is a preview of your project and roughly how it will look to the proofreaders.</b></td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Title</b></td><td>$this->nameofwork</td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Author</b></td><td>$this->authorsname</td></tr>\n";
        if (user_is_a_sitemanager())
        {
            // SAs are the only ones who can change this.
            echo "<tr><td align='middle' bgcolor='#cccccc'><b>Project Manager</b></td><td>$this->projectmanager</td></tr>\n";
        }
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Last Proofread</b></td><td>$now</td></tr>\n";
        echo "<tr><td align='middle' bgcolor='#cccccc'><b>Forum</b></td><td>Start a discussion about this project</td></tr>\n";

        echo "<tr><td colspan='2' bgcolor='#cccccc' align='center'>";
        echo "<font size='+1'><b>Project Comments</b></font>";
        echo "<br>$a<br>$b";
        echo "</td></tr>\n";
        echo "<tr><td colspan='2'>";
        echo $comments;
        echo "</td></tr>\n";

        echo "</table><br><br>";
    }
}

// get a list of the fields that have changed, using the names they are given
// on the edit page. 
function get_changed_fields($new_pih, $old_pih)
{
    $changed_fields = 'Changed fields: ';
    $found_change = FALSE;
    if ($new_pih->deletion_reason != $old_pih->deletion_reason)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Reason for Deletion";
        $found_change = TRUE;
    }
    if ($new_pih->nameofwork != $old_pih->nameofwork)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) . 'Name of work';
        $found_change = TRUE;
    }
    if ($new_pih->authorsname != $old_pih->authorsname)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Author's Name";
        $found_change = TRUE;
    }
    if ($new_pih->username != $old_pih->username)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Project Manager";
        $found_change = TRUE;
    }
    if ($new_pih->language != $old_pih->language)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Language";
        $found_change = TRUE;
    }
    if ($new_pih->genre != $old_pih->genre)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Genre";
        $found_change = TRUE;
    }
    if ($new_pih->difficulty_level != $old_pih->difficulty_level)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Difficulty Level";
        $found_change = TRUE;
    }
    if ($new_pih->special_code != $old_pih->special_code)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Special Day";
        $found_change = TRUE;
    }
    if ($new_pih->checkedoutby != $old_pih->checkedoutby)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."PPer/PPVer";
        $found_change = TRUE;
    }
    if ($new_pih->image_source != $old_pih->image_source)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Original Image Source";
        $found_change = TRUE;
    }
    if ($new_pih->image_preparer != $old_pih->image_preparer)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Image Preparer";
        $found_change = TRUE;
    }
    if ($new_pih->text_preparer != $old_pih->text_preparer)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Text Preparer";
        $found_change = TRUE;
    }
    if ($new_pih->extra_credits != $old_pih->extra_credits)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Extra Credits";
        $found_change = TRUE;
    }
    if ($new_pih->scannercredit != $old_pih->scannercredit)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Scanner Credit";
        $found_change = TRUE;
    }
    if ($new_pih->clearance != $old_pih->clearance)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Clearance Information";
        $found_change = TRUE;
    }
    if ($new_pih->postednum != $old_pih->postednum)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Posted Number";
        $found_change = TRUE;
    }
    if ($new_pih->comments != $old_pih->comments)
    { 
        $changed_fields .= ($found_change ? ', ' : '' ) ."Project Comments";
        $found_change = TRUE;
    }
    if ( ! $found_change )
    { 
        $changed_fields .= "none";
    }
    return $changed_fields;
}

// vim: sw=4 ts=4 expandtab
?>
