<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'MARCRecord.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'comment_inclusions.inc');
include_once('edit_common.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'js_newpophelp.inc');

require_login();

$theme_args['js_data'] = get_newHelpWin_javascript("$code_url/faq/pophelp/project_manager/");

$return = array_get($_REQUEST, "return", "$code_url/tools/project_manager/projectmgr.php");

if (!user_is_PM()) {
    die('permission denied');
}

$pih = new ProjectInfoHolder();

if (isset($_POST['saveAndQuit']) || isset($_POST['saveAndProject']) || isset($_POST['preview']) || isset($_POST['save'])) {
    $errors = $pih->set_from_post();
    $pih->normalize_spaces();
    if (empty($errors)) {
        if (!isset($_POST['preview'])) {
            $pih->save_to_db();
        }
        if (isset($_POST['saveAndQuit'])) {
            // TRANSLATORS: PM = project manager
            metarefresh(0, "projectmgr.php", _("Save and Go To PM Page"), "");
        } elseif (isset($_POST['saveAndProject'])) {
            metarefresh(0, "$code_url/project.php?id={$pih->project->projectid}", _("Save and Go To Project"), "");
        }
    }

    if (isset($pih->project->projectid)) {
        $page_title = _("Edit a Project");
    } else {
        // we're creating a new project
        check_user_can_load_projects(true); // exit if they can't
        if (isset($pih->original_marc_array_encd)) {
            $page_title = _("Create a Project from a MARC Record");
        } else {
            $page_title = _("Create a Project");
        }
    }

    output_header($page_title, NO_STATSBAR, $theme_args);
    echo "<h1>$page_title</h1>\n";

    if ($errors) {
        echo "<p class='error'>" . join("<br>", $errors) . "</p>";
    }

    $pih->show_form();

    if (isset($_POST['preview'])) {
        $pih->preview();
    }
} elseif (isset($_POST['quit'])) {
    // if return is empty for whatever reason take them to
    // the PM page
    if (empty($return)) {
        $return = "$code_url/tools/project_manager/projectmgr.php";
    }

    // do the redirect
    metarefresh(0, $return, _("Quit Without Saving"), "");
} else {
    $requested_action = get_enumerated_param($_REQUEST, 'action', null, ['createnew', 'clone', 'create_from_marc_record', 'edit']);

    if (in_array($requested_action, ['createnew', 'clone', 'create_from_marc_record'])) {
        check_user_can_load_projects(true); // exit if they can't
    }

    switch ($requested_action) {
        case 'createnew':
            $page_title = _("Create a Project");
            $fatal_error = $pih->set_from_nothing();
            break;

        case 'clone':
            $page_title = _("Clone a Project");
            $fatal_error = $pih->set_from_clone();
            break;

        case 'create_from_marc_record':
            $page_title = _("Create a Project from a MARC Record");
            $fatal_error = $pih->set_from_marc_record();
            break;

        case 'edit':
            $page_title = _("Edit a Project");
            $fatal_error = $pih->set_from_db();
            break;

        default:
            $page_title = 'editproject.php';
            $fatal_error = sprintf(_("parameter '%s' is invalid"), 'action') . ": '$requested_action'";
    }

    output_header($page_title, NO_STATSBAR, $theme_args);
    echo "<h1>$page_title</h1>\n";

    if ($fatal_error != '') {
        $fatal_error = _('Error') . ': ' . $fatal_error;
        echo "<p class='error'>$fatal_error</p>";
        exit;
    }

    $pih->normalize_spaces();
    $pih->show_form();
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class ProjectInfoHolder
{
    public function set_from_nothing()
    {
        global $pguser, $default_project_char_suites;

        $this->project = new Project();
        $this->project->username = $pguser;
        $this->project->image_preparer = $pguser;
        $this->project->text_preparer = $pguser;
        $this->project->difficulty = ($pguser == "BEGIN" ? "beginner" : "average");
        $this->charsuites = $default_project_char_suites;
    }

    // -------------------------------------------------------------------------

    public function set_from_marc_record()
    {
        $encoded_marc_array = array_get($_POST, "rec", "");
        if (!$encoded_marc_array) {
            return sprintf(_("No record selected. If no results are suitable, select '%s' to create the project manually."), _("No Matches"));
        }

        $this->set_from_nothing();
        $this->project->populate_from_marc_record($encoded_marc_array);

        $this->original_marc_array_encd = $encoded_marc_array;
    }

    // -------------------------------------------------------------------------

    public function set_from_clone()
    {
        $projectid = $_GET['project'];
        $this->project = new Project($projectid);
        $this->clone_projectid = $this->project->projectid;

        // pull the original project's character suites out first
        $this->charsuites = [];
        foreach ($this->project->get_charsuites(false) as $project_charsuite) {
            array_push($this->charsuites, $project_charsuite->name);
        }

        // reset project values that should not be cloned
        $this->project->projectid = null;
        $this->project->postednum = '';
        $this->project->deletion_reason = '';
        $this->project->state = '';
    }

    // edit an existing project
    public function set_from_db()
    {
        $projectid = $_GET['project'];
        if ($projectid == '') {
            return sprintf(_("parameter '%s' is empty"), 'project');
        }
        validate_projectID($projectid);

        try {
            $this->project = new Project($projectid);
        } catch (NonexistentProjectException $exception) {
            return $exception->getMessage();
        }

        if (!$this->project->can_be_managed_by_current_user) {
            return _("You are not authorized to manage this project.").": '$projectid'";
        }

        // ProjectTransition routes users here when a project is posted to PG
        $this->posted = @$_GET['posted'];

        $this->charsuites = [];
        foreach ($this->project->get_charsuites(false) as $project_charsuite) {
            array_push($this->charsuites, $project_charsuite->name);
        }
    }

    // -------------------------------------------------------------------------

    public function set_from_post()
    {
        global $pguser;

        $errors = [];

        if (isset($_POST['projectid'])) {
            $projectid = get_projectID_param($_POST, 'projectid');

            try {
                $this->project = new Project($projectid);
            } catch (NonexistentProjectException $exception) {
                return $exception->getMessage();
            }

            if (!$this->project->can_be_managed_by_current_user) {
                return _("You are not authorized to manage this project.").": '$this->projectid'";
            }
        } elseif (isset($_POST['clone_projectid'])) {
            // we're creating a clone
            $clone_projectid = get_projectID_param($_POST, 'clone_projectid');
            $this->clone_projectid = $clone_projectid;

            $this->set_from_clone($clone_projectid);
        } else {
            $this->project = new Project();
        }

        $fields_to_set = [
            // username (PM) is handled below
            "nameofwork",
            "authorsname",
            // language is handled below
            "genre",
            "difficulty",
            "special_code",
            "clearance",
            "comments",
            "comment_format",
            "image_source",
            "scannercredit",  // deprecated but may exist for older projects
            "checkedoutby",
            "postednum",
            "image_preparer",
            "text_preparer",
            "extra_credits",
            "deletion_reason",
            "custom_chars",
        ];
        foreach ($fields_to_set as $field) {
            if (isset($_POST[$field])) {
                $this->project->$field = $_POST[$field];
            }
        }

        // only SAs can change PM
        if (user_is_a_sitemanager()) {
            $this->project->username = @$_POST['username'];
        } else {
            // When cloning a project, the PM should be the same as that of the
            // project being cloned, if the user isn't an SA
            if (isset($this->clone_projectid)) {
                $orig_project = new Project($this->clone_projectid);
                $this->project->username = $orig_project->username;
            } else {
                $this->project->username = $pguser;
            }
        }

        $this->project->languages = [
            @$_POST['pri_language'], @$_POST['sec_language'],
        ];

        // some special days are ... specialer
        if (startswith($this->project->special_code, "Otherday") ||
            startswith($this->project->special_code, "Birthday")) {
            $this->project->special_code .= " {$_POST['bdaymonth']}{$_POST['bdayday']}";
        }

        // validate fields managed by the Project class
        $errors = array_merge($errors, $this->project->validate());

        // set & validate meta fields
        $this->charsuites = [];
        foreach ($_POST['charsuites'] ?? [] as $charsuite) {
            array_push($this->charsuites, $charsuite);
        }
        if (sizeof($this->charsuites) == 0) {
            $errors[] = _("At least one Character Suite is required.");
        }

        $this->posted = @$_POST['posted'];
        if ($this->posted) {
            // We are in the process of marking this project as posted.
            if ($this->project->postednum == '') {
                $errors[] = _("PG etext number is required.");
            }
        }

        $this->original_marc_array_encd = @$_POST['rec'];

        return $errors;
    }

    // -------------------------------------------------------------------------

    public function save_to_db()
    {
        global $pguser;

        // The project should have already passed validation, but confirm
        // that here just before save.
        $this->project->validate(true);

        if (isset($this->project->projectid)) {
            // We are updating an already-existing project.
            $this->project->save();
        } else {
            // We're creating a new project
            $this->project->save();

            // Save original MARC record, if provided
            $yaz_array = unserialize(base64_decode($this->original_marc_array_encd));
            if ($yaz_array !== false) {
                $marc_record = new MARCRecord();
                $marc_record->load_yaz_array($yaz_array);
                $this->project->init_marc_record($marc_record);
                $this->project->update_marc_record();
            }

            // Create the project's 'good word list' and 'bad word list'.
            if (isset($this->clone_projectid)) {
                // We're creating a project via cloning.
                // Copy the original project's word-lists.

                $good_words = load_project_good_words($this->clone_projectid);
                if (is_string($good_words)) {
                    // It's an error message.
                    echo "$good_words<br>\n";
                    $good_words = [];
                }

                $bad_words = load_project_bad_words($this->clone_projectid);
                if (is_string($bad_words)) {
                    // It's an error message.
                    echo "$bad_words<br>\n";
                    $bad_words = [];
                }

                save_project_good_words($this->project->projectid, $good_words);
                save_project_bad_words($this->project->projectid, $bad_words);
            }
        }

        $this->project->set_charsuites($this->charsuites);

        // If the project has been posted to PG, make the appropriate transition.
        if ($this->posted) {
            $err = project_transition($this->project->projectid, PROJ_SUBMIT_PG_POSTED, $pguser);
            if ($err != '') {
                echo "$err<br>\n";
                exit;
            }
        }
    }

    // =========================================================================

    public function show_form()
    {
        echo "<form method='post' enctype='multipart/form-data' action='#preview'>";

        $this->show_hidden_controls();

        echo "<table class='basic' style='width: 90%; margin: auto;'>";

        $this->show_visible_controls();

        echo "<tr>";
        echo   "<th colspan='2'>";
        // TRANSLATORS: PM = project manager
        echo     "<input type='submit' name='preview' value='".attr_safe(_("Preview"))."'>";
        echo     "<input type='submit' name='save' value='".attr_safe(_("Save"))."'>";
        echo     "<input type='submit' name='saveAndQuit' value='".attr_safe(_("Save and Go To PM Page"))."'>";
        echo     "<input type='submit' name='saveAndProject' value='".attr_safe(_("Save and Go To Project"))."'>";
        echo     "<input type='submit' name='quit' formnovalidate value='".attr_safe(_("Quit Without Saving"))."'>";
        echo   "</th>";
        echo "</tr>\n";

        echo "</table>";
        echo "</form>";
        echo "\n";
    }

    // -------------------------------------------------------------------------

    public function show_hidden_controls()
    {
        global $return;

        if (!empty($this->original_marc_array_encd)) {
            echo "<input type='hidden' name='rec' value='$this->original_marc_array_encd'>";
        }
        if (!empty($this->posted)) {
            echo "<input type='hidden' name='posted' value='1'>";
        }
        if (!empty($this->project->projectid)) {
            echo "<input type='hidden' name='projectid' value='{$this->project->projectid}'>";
        }
        if (!empty($this->clone_projectid)) {
            echo "<input type='hidden' name='clone_projectid' value='$this->clone_projectid'>";
        }
        echo "<input type='hidden' name='comment_format' value='{$this->project->comment_format}'>";
        echo "<input type='hidden' name='return' value='$return'>";
    }

    // -------------------------------------------------------------------------

    public function show_visible_controls()
    {
        global $site_abbreviation, $pguser;

        $can_set_difficulty_tofrom_beginner = ($pguser == "BEGIN") || user_is_a_sitemanager();

        $can_edit_PPer = true;
        $is_checked_out = false;
        if (!empty($this->project->projectid)) {
            $this->row(_("Project ID"), 'just_echo', $this->project->projectid);

            // do some things that depend on the project state
            if ($this->project->state == PROJ_DELETE) {
                $this->row(_("Reason for Deletion"), 'text_field', $this->project->deletion_reason, 'deletion_reason');
            } elseif ($this->project->state == PROJ_POST_FIRST_CHECKED_OUT) {
                // once the project is in PP, PPer can only be changed by an SA, PF,
                // or if it's checked out to the PM
                $is_checked_out = true;
                $can_edit_PPer = (($this->project->username == $this->project->checkedoutby) ||
                                   user_is_a_sitemanager() ||
                                   user_is_proj_facilitator());
            } elseif ($this->project->state == PROJ_POST_SECOND_CHECKED_OUT) {
                $is_checked_out = true;
                $can_edit_PPer = user_is_a_sitemanager();
            }
        }
        $this->row(_("Title"), 'text_field', $this->project->nameofwork, 'nameofwork', '', ["maxlength" => 255, "required" => true]);
        $this->row(_("Author"), 'text_field', $this->project->authorsname, 'authorsname', '', ["maxlength" => 255, "required" => true]);
        if (user_is_a_sitemanager()) {
            // SAs are the only ones who can change this
            $this->row(_("Project Manager"), 'DP_user_field', $this->project->username, 'username', sprintf(_("%s username only."), $site_abbreviation), ["required" => true]);
        }
        $this->row(_("Language"), 'language_list', $this->project->language);

        $project_charsuites = [];
        if (isset($this->project->projectid)) {
            $project_charsuites = $this->project->get_charsuites(false);
        }
        $this->row(_("Character Suites"), 'charsuite_list', $this->charsuites, $project_charsuites);
        $this->row(_("Custom Characters"), 'text_field', $this->project->custom_chars, 'custom_chars');

        $this->row(_("Genre"), 'genre_list', $this->project->genre);

        if ($this->project->difficulty == "beginner" && !$can_set_difficulty_tofrom_beginner) {
            // allow PF to edit a BEGIN project, but without altering the difficulty
            $this->row(_("Difficulty"), 'just_echo', _("Beginner"));
            echo "<input type='hidden' name='difficulty' value='$this->project->difficulty'>";
        } else {
            $this->row(_("Difficulty"), 'difficulty_list', $this->project->difficulty);
        }
        $this->row(_("Special Day"), 'special_list', $this->project->special_code);
        if ($can_edit_PPer) {
            $this->row(_("PPer/PPVer"), 'DP_user_field', $this->project->checkedoutby, 'checkedoutby', sprintf(_("Optionally reserve for a PPer. %s username only."), $site_abbreviation));
        } else {
            $this->row(_("PPer/PPVer"), 'just_echo', $this->project->checkedoutby);
            echo "<input type='hidden' name='checkedoutby' value='$this->checkedoutby'>";
        }
        $this->row(_("Image Source"), 'image_source_list', $this->project->image_source);
        $this->row(_("Image Preparer"), 'DP_user_field', $this->project->image_preparer, 'image_preparer', sprintf(_("%s user who scanned or harvested the images."), $site_abbreviation));
        $this->row(_("Text Preparer"), 'DP_user_field', $this->project->text_preparer, 'text_preparer', sprintf(_("%s user who prepared the text files."), $site_abbreviation));
        $this->row(_("Extra Credits<br>(to be included in list of names--no URLs)"),
                                               'extra_credits_field', $this->project->extra_credits, null, '', '', true);
        if ($this->project->scannercredit != '') {
            $this->row(_("Scanner Credit (deprecated)"), 'text_field', $this->project->scannercredit, 'scannercredit');
        }
        $this->row(_("Clearance Line"), 'text_field', $this->project->clearance, 'clearance');
        $this->row(_("PG etext number"), 'text_field', $this->project->postednum, 'postednum', '', ["type" => "number"]);
        $this->row(_("Project Comments Format"), 'proj_comments_format', $this->project->comment_format);
        $this->row(_("Project Comments"), 'proj_comments_field', $this->project->comments);

        // don't show the word list line if we're in the process of cloning
        if (!empty($this->project->projectid)) {
            $this->row(_("Project Dictionary"), 'word_lists', null, null, '', $this->project->projectid);
        }
    }

    public function row($label, $display_function, $field_value, $field_name = null, $explain = '', $args = '', $html_label = false)
    {
        echo "<tr>";
        echo   "<th class='label'>";
        echo     $html_label ? $label : html_safe($label);
        echo   "</th>";
        echo   "<td>";
        $display_function($field_value, $field_name, $args);
        echo   "  ";
        echo   html_safe($explain);
        echo   "</td>";
        echo "</tr>";
        echo "\n";
    }

    // =========================================================================

    public function preview()
    {
        // insert e.g. templates and biographies
        $comments = parse_project_comments($this->project);

        $a = _("The Guidelines give detailed instructions for working in this round.");
        $b = _('The instructions below are particular to this project, and <b>take precedence over those guidelines</b>.');

        // TRANSLATORS: This is a strftime-formatted string for the date with year and time
        $now = strftime(_("%A, %B %e, %Y at %X"));

        echo "<h2 id='preview'>", _("Preview Project"), "</h2>";
        echo "<p>", _("This is a preview of your project and roughly how it will look to the proofreaders."), "</p>\n";
        echo "<table class='basic'>";
        echo "<tr><th>", _("Title"), "</th><td>", html_safe($this->project->nameofwork), "</td></tr>\n";
        echo "<tr><th>", _("Author"), "</th><td>", html_safe($this->project->authorsname), "</td></tr>\n";
        if (user_is_a_sitemanager()) {
            // SAs are the only ones who can change this.
            echo "<tr><th>", _("Project Manager"), "</th><td>", $this->project->username, "</td></tr>\n";
        }
        echo "<tr><th>", _("Last Proofread"), "</th><td>$now</td></tr>\n";
        echo "<tr><th>", _("Forum"), "</th><td>", _("Start a discussion about this project"), "</td></tr>\n";

        echo "<tr><th colspan='2'>";
        echo "<p class='large'>", _("Project Comments"), "</p>";
        echo "<br>$a<br>$b";
        echo "</th></tr>\n";
        echo "<tr><td colspan='2'>";
        echo $comments;
        echo "</td></tr>\n";

        echo "</table><br>";
    }

    // -------------------------------------------------------------------------

    public function normalize_spaces()
    // In the project's text fields, replace sequences of space characters
    // with a unique space, and trim beginning and end space
    {
        $this->project->nameofwork = preg_replace('/\s+/', ' ', trim($this->project->nameofwork));
        $this->project->authorsname = preg_replace('/\s+/', ' ', trim($this->project->authorsname));
        $this->project->clearance = preg_replace('/\s+/', ' ', trim($this->project->clearance));
        $this->project->extra_credits = preg_replace('/\s+/', ' ', trim($this->project->extra_credits));
    }
}
