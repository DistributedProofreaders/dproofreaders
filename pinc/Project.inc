<?php
include_once($relPath.'user_is.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'misc.inc'); // html_safe(), xmlencode(), startswith()
include_once($relPath.'forum_interface.inc'); // topic_create
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'User.inc');
include_once($relPath.'MARCRecord.inc');
include_once($relPath.'stages.inc'); // is_formatting_round()
include_once($relPath.'CharSuites.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'wordcheck_engine.inc'); // delete_project_wordcheck_events()
include_once($relPath.'DPage.inc'); // project_allow_pages()

class UTF8ConversionException extends Exception
{
}

class ProjectException extends Exception
{
}
class NonexistentProjectException extends ProjectException
{
}
class InvalidProjectIDException extends ProjectException
{
}
class InvalidPageException extends ProjectException
{
}
class NoProjectPageTable extends ProjectException
{
}
class NoProjectDirectory extends ProjectException
{
}
class ProjectPageException extends ProjectException
{
}

class Project
{
    use CharSuiteSet;

    private $_create_source = null;

    public function __construct($arg = null)
    {
        if (is_string($arg)) {
            // $arg is the projectid.
            $this->_load_from_db($arg);
            $this->_create_source = "db";
        } elseif (is_array($arg)) {
            // $arg is assumed to be an associative array, such
            // as would be returned by mysqli_fetch_assoc().
            $this->_init_fields_to_defaults();
            foreach ($arg as $key => $value) {
                $this->$key = $value;
            }
            $this->_create_source = "array";
        } elseif ($arg === null) {
            $this->_init_fields_to_defaults();
            $this->_create_source = "empty";
        } else {
            $arg_type = gettype($arg);
            die("Project::Project(): 'arg' has unexpected type $arg_type");
        }
    }

    // -------------------------------------------------------------------------
    // Load & Validate

    private function _init_fields_to_defaults()
    {
        // Initialize Project with core fields and default values.
        // These alone aren't sufficient to validate() and persist the project.
        $fields = [
            "projectid" => null,
            "nameofwork" => "",
            "authorsname" => "",
            "username" => "",
            "checkedoutby" => "",
            "language" => "",
            "scannercredit" => "",
            "comments" => "",
            "comment_format" => "markdown",
            "postproofer" => "",
            "ppverifier" => "",
            "postcomments" => "",
            "clearance" => "",
            "postednum" => "",
            "genre" => "",
            "difficulty" => "",
            "special_code" => "",
            "image_source" => "",
            "image_preparer" => "",
            "text_preparer" => "",
            "extra_credits" => "",
            "deletion_reason" => "",
            "custom_chars" => "",
            "state" => "",
        ];
        foreach ($fields as $key => $value) {
            $this->$key = $value;
        }
    }

    private function _load_from_db($projectid)
    {
        $sql = sprintf("
            SELECT *
            FROM projects
            WHERE projectid = '%s'",
            DPDatabase::escape($projectid)
        );
        $res = DPDatabase::query($sql);
        $row = mysqli_fetch_assoc($res);
        if (!$row) {
            throw new NonexistentProjectException(sprintf(_("There is no project with projectid '%s'"), $projectid));
        }
        $row['t_retrieved'] = time();
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
    }

    public function populate_from_marc_record($encoded_marc_array)
    {
        $yaz_array = unserialize(base64_decode($encoded_marc_array));
        if (!$yaz_array) {
            throw new InvalidMARCRecord();
        }

        $marc_record = new MARCRecord();
        $marc_record->load_yaz_array($yaz_array);

        $this->nameofwork = $marc_record->title;
        $this->authorsname = $marc_record->author;
        $this->language = $marc_record->language;
        $this->genre = $marc_record->literary_form;
    }

    public function validate($throw_on_error = false)
    {
        $labels = self::get_field_labels();

        $errors = [];

        if ($this->projectid) {
            try {
                validate_projectID($this->projectid);
            } catch (InvalidProjectIDException $exception) {
                $errors[] = $exception->getMessage();
            }
        }

        $required_fields = [
            "nameofwork",
            "authorsname",
            "username",
            "language",
            "genre",
            "image_source",
            "difficulty",
        ];

        foreach ($required_fields as $field) {
            if (preg_match('/^\s*$/', $this->$field)) {
                $errors[] = sprintf(_("%s is required."), $labels[$field]);
            }
        }

        $user_fields = [
            "username",
            "checkedoutby",
            "image_preparer",
            "text_preparer",
            "postproofer",
            "ppverifier",
        ];

        foreach ($user_fields as $field) {
            // only check non-empty fields
            if (!preg_match('/^\s*$/', $this->$field) && !User::is_valid_user($this->$field)) {
                $errors[] = sprintf(_("%s must be an existing user - check case and spelling and ensure there is no trailing whitespace."),
                    $labels[$field]);
            }
        }

        if ($this->username && !that_user_is_PM($this->username)) {
            // TRANSLATORS: PM = project manager
            $errors[] = sprintf(_("%s is not a PM."), $this->username);
        }

        if ($this->languages[0]) {
            foreach ($this->languages as $language) {
                if (langcode2_for_langname($language) === null) {
                    $errors[] = sprintf(_("%s is not a valid language."), $language);
                }
            }
        }

        if ($this->genre && !in_array($this->genre, array_keys(load_genre_translation_array()))) {
            $errors[] = sprintf(_("%s is not a valid genre."), $this->genre);
        }

        if ($this->image_source && !in_array($this->image_source, array_keys(load_image_sources()))) {
            $errors[] = sprintf(_("%s is not a valid image source."), $this->image_source);
        }

        if ($this->difficulty && !in_array($this->difficulty, array_keys(get_project_difficulties()))) {
            $errors[] = sprintf(_("%s is not a valid difficulty."), $this->difficulty);
        }

        if ($this->special_code) {
            if (startswith($this->special_code, 'Birthday') ||
                 startswith($this->special_code, 'Otherday')
            ) {
                if (preg_match("/\w+ (\d{2})(\d{2})/", $this->special_code, $matches)) {
                    if (!checkdate($matches[1], $matches[2], 2000)) {
                        $errors[] = _("Invalid date supplied for Birthday or Otherday Special.");
                    }
                } else {
                    $errors[] = _("Month and Day are required for Birthday or Otherday Specials.");
                }
            } else {
                if (!in_array($this->special_code, array_keys(load_special_days()))) {
                    $errors[] = sprintf(_("%s is not a valid special day."), $this->special_code);
                }
            }
        }

        // don't allow an empty PPer/PPVer if the project is checked out
        if (($this->state == PROJ_POST_FIRST_CHECKED_OUT ||
             $this->state == PROJ_POST_SECOND_CHECKED_OUT) &&
             $this->checkedoutby == '') {
            $errors[] = _("This project is checked out and must specify a PPer/PPVer");
        }

        if ($this->postednum) {
            if (! preg_match('/^[1-9][0-9]*$/', $this->postednum)) {
                $errors[] = sprintf(
                    _("Posted Number \"%s\" is not of the correct format."),
                    $this->postednum);
                // Occasionally, there will be a PG ebook that is still
                // under U.S. copyright. This is indicated in their system
                // by appending a 'C' to the etext number. The link to
                // the etext, however, does not include the 'C', nor should
                // the DP link. If this changes, update the pattern here.
            }
        }

        $custom_chars = utf8_normalize($this->custom_chars);
        if ($custom_chars) {
            $codepoints = utf8_codepoints_combining($custom_chars);

            // all characters must be unique
            if ($codepoints != array_unique($codepoints)) {
                $errors[] = _("The set of custom characters must be unique.");
            }

            // only allow 32 characters
            if (count($codepoints) > 32) {
                $errors[] = _("A maximum of 32 custom characters are allowed.");
            }

            // prevent disallowed characters from being added
            $disallowed_codepoints = array_intersect(get_disallowed_codepoints(), $codepoints);
            if ($disallowed_codepoints != []) {
                $errors[] = sprintf(
                    _("The following custom characters are not allowed: %s"),
                    implode(", ", array_map('voku\helper\UTF8::hex_to_chr', $disallowed_codepoints))
                );
            }
        }

        if ($errors and $throw_on_error) {
            throw new ValueError(join("\n", $errors));
        }

        return $errors;
    }

    public function save()
    {
        // Disallow save if the object was created from an array. There's too
        // great a chance that there might be missing fields.
        if ($this->_create_source == "array") {
            throw new ProjectException("Saving a Project object from an array source is disallowed.");
        }

        $postednum_str = ($this->postednum == "") ? "NULL" : sprintf("%d", $this->postednum);
        $project_settings = "
            username       = '".DPDatabase::escape($this->username)."',
            nameofwork     = '".DPDatabase::escape(utf8_normalize($this->nameofwork))."',
            authorsname    = '".DPDatabase::escape(utf8_normalize($this->authorsname))."',
            language       = '".DPDatabase::escape($this->language)."',
            genre          = '".DPDatabase::escape($this->genre)."',
            difficulty     = '".DPDatabase::escape($this->difficulty)."',
            special_code   = '".DPDatabase::escape($this->special_code)."',
            clearance      = '".DPDatabase::escape($this->clearance)."',
            comments       = '".DPDatabase::escape(utf8_normalize($this->comments))."',
            comment_format = '".DPDatabase::escape($this->comment_format)."',
            postcomments   = '".DPDatabase::escape(utf8_normalize($this->postcomments))."',
            postproofer    = '".DPDatabase::escape($this->postproofer)."',
            ppverifier     = '".DPDatabase::escape($this->ppverifier)."',
            image_source   = '".DPDatabase::escape($this->image_source)."',
            scannercredit  = '".DPDatabase::escape($this->scannercredit)."',
            checkedoutby   = '".DPDatabase::escape($this->checkedoutby)."',
            postednum      = $postednum_str,
            image_preparer = '".DPDatabase::escape($this->image_preparer)."',
            text_preparer  = '".DPDatabase::escape($this->text_preparer)."',
            extra_credits  = '".DPDatabase::escape(utf8_normalize($this->extra_credits))."',
            deletion_reason= '".DPDatabase::escape($this->deletion_reason)."',
            custom_chars   = '".DPDatabase::escape(utf8_normalize($this->custom_chars))."',
        ";

        // are we creating a new project or updating an existing one?
        if ($this->projectid) {
            // Updating an existing project

            // find out what is changing so we can log it
            $current_values = new Project($this->projectid);
            $changed_fields = get_changed_fields_for_objects($this, $current_values);

            // If the comments have changed, update t_last_change_comments
            if (in_array('comments', $changed_fields)) {
                $project_settings .= "t_last_change_comments = UNIX_TIMESTAMP(),";
            }

            // We also want to know if the edit is resulting in the project
            // effectively being checked out to a new PPer. Store the value
            // for logging after we do the update.
            if (in_array('checkedoutby', $changed_fields) &&
                $current_values->state == PROJ_POST_FIRST_CHECKED_OUT) {
                $project_settings .= "modifieddate = UNIX_TIMESTAMP(),";
                $PPer_checkout = true;
            } else {
                $PPer_checkout = false;
            }

            $where = sprintf(
                "WHERE projectid='%s'",
                DPDatabase::escape($this->projectid)
            );
            $sql = "
                UPDATE projects SET
                    $project_settings
                    t_last_edit = UNIX_TIMESTAMP()
                    $where
            ";
            DPDatabase::query($sql);

            // Log the changes
            $details1 = implode(' ', $changed_fields);
            if ($details1 == '') {
                // There are no changed fields.

                // Don't just save '' for the details1 column, because then
                // do_history() won't be able to distinguish this case (no
                // changed fields) from old cases (edit occurred before we
                // started recording changed fields). Instead, use a special value.
                $details1 = 'NONE';
            }
            $this->log_project_event(User::current_username(), 'edit', $details1);
            if ($PPer_checkout) {
                // we fake the project transition...
                $this->project->log_project_event(
                    User::current_username(),
                    'transition',
                    PROJ_POST_FIRST_CHECKED_OUT,
                    PROJ_POST_FIRST_CHECKED_OUT,
                    $this->checkedoutby
                );
            }

            // Update the MARC record
            $this->update_marc_record();
        } else {
            // Creating a new project
            $this->projectid = uniqid("projectID"); // The project ID

            // Insert a new row into the projects table
            $pid_setter = sprintf(
                "projectid = '%s',",
                DPDatabase::escape($this->projectid)
            );
            $state_setter = sprintf(
                "state = '%s',",
                DPDatabase::escape(PROJ_NEW)
            );
            $sql = "
                INSERT INTO projects
                SET
                    $pid_setter
                    $state_setter
                    $project_settings
                    t_last_edit = UNIX_TIMESTAMP(),
                    modifieddate = UNIX_TIMESTAMP(),
                    t_last_change_comments = UNIX_TIMESTAMP()
            ";
            DPDatabase::query($sql);

            // Log that we've created the project
            $this->log_project_event(User::current_username(), 'creation');

            // Make the project directory
            if (!mkdir($this->dir, 0777, true)) {
                throw new RuntimeException("Unable to mkdir '$this->dir'");
            }

            // Make the project table
            project_allow_pages($this->projectid);

            // reload the object from the database to pick up initial
            // values created or assigned from the database
            $this->_load_from_db($this->projectid);
        }
    }

    public function delete()
    {
        // validate the projectID to ensure we are pointing to a valid
        // project directory
        validate_projectID($this->projectid);

        if (is_dir($this->dir)) {
            exec("rm -rf " . escapeshellarg($this->dir));
        }

        if ($this->pages_table_exists) {
            project_drop_pages($this->projectid);
        }

        delete_project_wordcheck_events($this->projectid);

        // Formerly, if the project was in the 'New' state, we would here
        // delete it from the projects table and log a 'deletion' event.
        // However, it's somewhat problematic to delete the entry from the
        // 'projects' table. Instead, we now treat such cases like all
        // other project deletions: we keep the entry in the projects table,
        // and have the code in ProjectTransition, which calls this function,
        // change its state to 'deleted'.
    }

    // -------------------------------------------------------------------------
    // Property getters

    public function __get($name)
    {
        global $pguser, $projects_url, $projects_dir, $code_url;

        static $project_page_table_exists = null;

        switch ($name) {
            case "url":
                return "$projects_url/$this->projectid";
            case "dir":
                return "$projects_dir/$this->projectid";
            case "page_url":
                return "$code_url/project.php?id={$this->projectid}";
            case "is_utf8":
                return DPDatabase::is_table_utf8($this->projectid);
            case "dir_exists":
                return is_dir($this->dir);
            case "pages_table_exists":
                if ($project_page_table_exists === null) {
                    $project_page_table_exists = does_project_page_table_exist($this->projectid);
                }
                return $project_page_table_exists;
            case "credits_line":
                return $this->_get_credits_line();
            case "languages":
                return self::decode_language($this->language);
            case "image_source_name":
                $this->_load_image_source();
                return $this->image_source_name;
            case "can_be_managed_by_current_user":
                return $this->can_be_managed_by_user($pguser);
            case "names_can_be_seen_by_current_user":
                return $this->names_can_be_seen_by_user($pguser);
            case "PPer":
                return $this->_get_PPer();
            case "PPVer":
                return $this->_get_PPVer();
            case "PPer_is_current_user":
                return is_null($pguser) ? false : $this->PPer == $pguser;
            case "PPVer_is_current_user":
                return is_null($pguser) ? false : $this->PPVer == $pguser;
        }

        // If we don't recognize the property, raise a notice and return null
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case "languages":
                $this->language = self::encode_languages($value);
                break;
            default:
                $this->$name = $value;
                break;
        }
    }

    private function _load_image_source()
    {
        $this->image_source_name = '';
        $this->image_source_credit = '';

        if (!isset($this->image_source)) {
            return;
        }

        $image_sources = load_image_sources();
        $imso_res = array_get($image_sources, (string)$this->image_source, null);
        if ($imso_res) {
            $this->image_source_name = $imso_res['full_name'];
            $this->image_source_credit = $imso_res['credit'];
        }
    }

    private function _get_credits_line()
    {
        $credits_line = $this->_create_credit_line();

        // if $this->image_source = _internal, the images were done by or for a DP User,
        // whose username should be recorded in image_preparer otherwise, we need to
        // look up info on the specific "external" image source
        if ($this->image_source != "_internal") {
            $this->_load_image_source();
            // Can't put a . at the end of the output of _create_credit_line, since it ends
            // with a url and the . breaks the url in some browsers; yet the image source credit,
            // grammatically and aestehtically, needs some sort of separation from the
            // "main" credit line - so we enclose it in ( )
            $credits_line = "$credits_line ({$this->image_source_credit})";
        }
        return $credits_line;
    }

    // -------------------------------------------------------------------------

    // Return an associative array of project fields with localized labels
    // for use in the UI.
    public static function get_field_labels()
    {
        return [
            "projectid" => _("Project ID"),
            "nameofwork" => _("Title"),
            "authorsname" => _("Author"),
            "username" => _("Project Manager"),
            "comments" => _("Project Comments"),
            "comment_format" => _("Project Comments Format"),
            "special_code" => _("Special Day"),
            "checkedoutby" => _("PPer/PPVer"),
            "scannercredit" => _("Scanner Credit"),
            "postednum" => _("PG etext number"),
            "clearance" => _("Clearance Line"),
            "language" => _("Language"),
            "genre" => _("Genre"),
            "difficulty" => _("Difficulty"),
            "postproofer" => _("Post Processor"),
            "ppverifier" => _("Post Processor Verifier"),
            "image_source" => _("Image Source"),
            "image_preparer" => _("Image Preparer"),
            "text_preparer" => _("Text Preparer"),
            "extra_credits" => _("Extra Credits"),
            "deletion_reason" => _("Reason for Deletion"),
            "custom_chars" => _("Custom Characters"),
            // these keys are stored in project_events details1 fields
            // and map to fields with different names
            "difficulty_level" => _("Difficulty"),
            "projectmanager" => _("Project Manager"),
        ];
    }

    // -------------------------------------------------------------------------

    // Multiple project languages are stored in a single "language" column
    // by joining them with the string " with ". These functions provide a
    // common place for encoding / decoding them.

    public static function encode_languages($languages)
    {
        // handle the case where the second language is empty
        if (count($languages) == 2 && $languages[1] == '') {
            return $languages[0];
        }

        return join(" with ", $languages);
    }

    public static function decode_language($language)
    {
        return explode(" with ", $language);
    }

    // -------------------------------------------------------------------------

    public function convert_to_utf8()
    {
        // nothing to do if the project table doesn't exist
        // this covers archived projects which are moved into a different DB
        if (!$this->check_pages_table_exists($message)) {
            return false;
        }

        // and nothing to do if it is already UTF-8
        if ($this->is_utf8) {
            return false;
        }
        validate_projectID($this->projectid);
        $sql = "
            ALTER TABLE $this->projectid CONVERT TO CHARACTER SET utf8mb4;
        ";
        $result = DPDatabase::query($sql, false);
        if (!$result) {
            throw new UTF8ConversionException(
                "Database error converting $this->projectid to utf8mb4"
            );
        }

        return true;
    }

    // -------------------------------------------------------------------------

    public static function projects_using_charsuite($charsuite)
    {
        $charsuite = CharSuites::resolve($charsuite);

        $sql = sprintf("
            SELECT projectid
            FROM project_charsuites
            WHERE charsuite_name='%s'",
            DPDatabase::escape($charsuite->name)
        );

        $projectids = [];
        $result = DPDatabase::query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $projectids[] = $row['projectid'];
        }
        mysqli_free_result($result);
        return $projectids;
    }

    public function set_charsuites($charsuites)
    {
        // We want to allow the project to keep any character suites
        // it already had, even if the character suite was disabled
        // after it was created.

        // Resolve the passed-in charsuites to objects
        $desired_charsuites = [];
        foreach ($charsuites as $charsuite) {
            $desired_charsuites[] = CharSuites::resolve($charsuite);
        }

        $existing_charsuites = $this->get_charsuites(false);

        // add suites that are in the desired, but not current, set
        foreach ($desired_charsuites as $charsuite) {
            if (!in_array($charsuite, $existing_charsuites)) {
                $this->add_charsuite($charsuite);
            }
        }

        // remove suites that aren't in the desired set
        foreach ($existing_charsuites as $charsuite) {
            if (!in_array($charsuite, $desired_charsuites)) {
                $this->remove_charsuite($charsuite);
            }
        }
    }

    public function add_charsuite($charsuite)
    {
        $charsuite = CharSuites::resolve($charsuite);

        $existing_charsuites = $this->get_charsuites(false);
        if (in_array($charsuite, $existing_charsuites)) {
            return;
        }

        // ensure that the charsuite is enabled, otherwise it can't be added
        if (!$charsuite->is_enabled()) {
            throw new CharSuiteNotEnabledException(sprintf(
                _("CharSuite '%s' is not enabled and cannot be added to a project"),
                $charsuite->title
            ));
        }
        $sql = sprintf("
            INSERT INTO project_charsuites
            SET projectid='%s', charsuite_name='%s'",
            DPDatabase::escape($this->projectid),
            DPDatabase::escape($charsuite->name)
        );
        DPDatabase::query($sql);
    }

    public function remove_charsuite($charsuite)
    {
        $charsuite = CharSuites::resolve($charsuite);

        $existing_charsuites = $this->get_charsuites(false);
        if (!in_array($charsuite, $existing_charsuites)) {
            return;
        }
        $sql = sprintf("
            DELETE FROM project_charsuites
            WHERE projectid='%s' AND charsuite_name='%s'",
            DPDatabase::escape($this->projectid),
            DPDatabase::escape($charsuite->name)
        );
        DPDatabase::query($sql);
    }

    public function get_charsuites($include_custom = true)
    {
        $charsuites = [];

        // create a custom suite for this project
        if ($include_custom) {
            $proj_suite = $this->get_custom_charsuite();
            if ($proj_suite) {
                $charsuites[] = $proj_suite;
            }
        }

        // load regular character suites
        $sql = sprintf("
            SELECT charsuite_name
            FROM project_charsuites
            WHERE projectid='%s'",
            DPDatabase::escape($this->projectid)
        );
        $result = DPDatabase::query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $charsuites[] = CharSuites::get($row["charsuite_name"]);
        }
        mysqli_free_result($result);
        return $charsuites;
    }

    public function get_custom_charsuite()
    {
        if (!$this->custom_chars) {
            return null;
        }

        $codepoints = utf8_codepoints_combining($this->custom_chars);
        $proj_suite = new CharSuite($this->projectid, _("Custom"), $codepoints, true);

        $pickerset = new PickerSet();
        $row1 = array_slice($codepoints, 0, round(count($codepoints) / 2));
        $row2 = array_slice($codepoints, count($row1));
        $pickerset->add_subset("Proj", [$row1, $row2]);
        $proj_suite->pickerset = $pickerset;
        return $proj_suite;
    }

    // -------------------------------------------------------------------------

    private function _get_PPer()
    {
        // The logic to determine who is a PPer is a bit convoluted
        // as it depends on the postproofer column, the checkedoutby
        // column, and the state of the project.

        $checkedout_states = [
            PROJ_POST_SECOND_CHECKED_OUT,
            PROJ_POST_COMPLETE,
            PROJ_SUBMIT_PG_POSTED,
        ];

        $PPer = "";
        if (!empty($this->postproofer)) {
            $PPer = $this->postproofer;
        } elseif (!empty($this->checkedoutby) &&
            !in_array($this->state, $checkedout_states)
        ) {
            $PPer = $this->checkedoutby;
        }

        return $PPer;
    }

    private function _get_PPVer()
    {
        // The logic to determine who is a PPVer is a bit convoluted
        // as it depends on the ppverifier column, the checkedoutby
        // column, and the state of the project.

        $checkedout_states = [
            PROJ_POST_SECOND_CHECKED_OUT,
            PROJ_POST_COMPLETE,
            PROJ_SUBMIT_PG_POSTED,
        ];

        $PPVer = "";
        if (!empty($this->ppverifier)) {
            $PPVer = $this->ppverifier;
        } elseif (!empty($this->checkedoutby) &&
            in_array($this->state, $checkedout_states)
            // You might think that we should only allow
            //     $this->state == 'proj_post_second_checked_out'
            // But if someone with PPV-ability is PPing a project,
            // they can (and probably will) directly post to PG,
            // with no explicit PPV phase. Thus, they are effectively
            // both the PPer and PPVer of the project.
        ) {
            $PPVer = $this->checkedoutby;
        }

        return $PPVer;
    }

    // -------------------------------------------------------------------------

    public function can_be_managed_by_user($username)
    {
        if (is_null($username)) {
            return false;
        }
        return
            ($username == $this->username
            || that_user_is_a_sitemanager($username)
            || that_user_is_proj_facilitator($username));
    }

    // -------------------------------------------------------------------------

    public function names_can_be_seen_by_user($username)
    {
        global $public_page_details;
        if (is_null($username)) {
            return false;
        }
        if ($public_page_details) {
            return true;
        }
        return
            ($this->can_be_managed_by_user($username)
             || $this->PPer == $username
             || $this->PPVer == $username);
    }

    // -------------------------------------------------------------------------

    public function clearance_line_can_be_seen_by_current_user()
    {
        // The clearance line normally contains the email address of the
        // person who submitted the clearance request. Since this is
        // private information, we restrict who can see it.
        // Also, the clearance line is necessary to uploading a project to PG,
        // so we restrict its visibility to prevent unauthorized uploads.

        return (
            $this->can_be_managed_by_current_user
            || $this->PPVer_is_current_user
            || ($this->PPer_is_current_user && user_has_DU_access())
        );
    }

    // -------------------------------------------------------------------------

    // These should be treated as constants.
    // (PHP doesn't appear to allow constants as class members.)
    public $CBP_OKAY = 0;
    public $CBP_PROJECT_NOT_IN_ROUND = 1;
    public $CBP_PROJECT_NOT_AVAILABLE = 2;
    public $CBP_REQUESTOR_NOT_LOGGED_IN = 3;
    public $CBP_USER_NOT_QUALIFIED_FOR_ROUND = 4;
    public $CBP_PROJECT_REQUIRES_MAINTENANCE = 5;

    public function can_be_proofed_by_current_user()
    // (where "proofed" means "worked on in a round, right now".)
    // Returns an array consisting of:
    // -- one of the above codes, and
    // -- a suggested error message.
    {
        global $code_url, $pguser;

        $state = $this->state;
        $round = get_Round_for_project_state($state);

        if (is_null($round)) {
            // The project is not in any round.
            return [
                $this->CBP_PROJECT_NOT_IN_ROUND,
                _('The project is not in a round.'),
            ];
        }

        if (!$this->is_utf8) {
            // Project has not been converted to UTF-8 yet
            return [
                $this->CBP_PROJECT_REQUIRES_MAINTENANCE,
                _('The project requires maintenance.') . ' ' . _("Project table is not UTF-8."),
            ];
        }

        if ($state != $round->project_available_state) {
            // The project is in a round, but isn't available.
            return [
                $this->CBP_PROJECT_NOT_AVAILABLE,
                _('Users are not allowed to work on the project in its current state.'),
            ];
        }

        if (is_null($pguser)) {
            return [
                $this->CBP_REQUESTOR_NOT_LOGGED_IN,
                _('Registered users can work on the project, but you are not logged in.'),
            ];
        }

        $uao = $round->user_access($pguser);
        if (!$uao->can_access) {
            // The project is available, but the current user isn't
            // allowed to work in the round that the project is in.
            return [
                $this->CBP_USER_NOT_QUALIFIED_FOR_ROUND,
                sprintf(
                    // TRANSLATORS: %1$s is the round name, %2$s is the round ID (eg: P1)
                    _('You have not yet been cleared to work on projects in %1$s (%2$s).'),
                    $round->name,
                    $round->id
                )
                . "<br>"
                . sprintf(
                    // TRANSLATORS: %2$s is the round ID (eg: P1)
                    _('Please visit <a href="%1$s">the %2$s home</a> to find out what happens in this round and how you can qualify to work in it.'),
                    "$code_url/tools/proofers/round.php?round_id=$round->id",
                    $round->id
                ),
            ];
        }

        return [
            $this->CBP_OKAY,
            "You may work on this project.",
        ];
    }

    public function user_can_do_quick_check()
    {
        // used in PQC itself and to enable links to PQC
        // in project.php and ProjectSearchResults.inc
        return $this->can_be_managed_by_current_user || $this->PPer_is_current_user;
    }

    public function is_bad_from_pages($round)
    {
        // If it has at least 10 bad pages, reported by at least 3
        // different users, it's bad.
        $n_bad_pages = $this->get_num_pages_in_state($round->page_bad_state);
        $n_unique_reporters = $this->get_num_pages_in_state(
            $round->page_bad_state,
            "DISTINCT(b_user)"
        );

        if ($n_bad_pages >= 10 && $n_unique_reporters >= 3) {
            return true;
        }

        return false;
    }

    public function get_page_states()
    {
        $states = [];

        validate_projectID($this->projectid);
        $sql = sprintf("
            SELECT DISTINCT state
            FROM {$this->projectid}
            ORDER BY %s
        ", sql_collater_for_page_state("state"));
        $res = DPDatabase::query($sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $states[] = $row["state"];
        }
        mysqli_free_result($res);

        return $states;
    }

    public function get_num_pages_in_state($state = null, $counter_sql = "*")
    {
        if (!$this->pages_table_exists) {
            throw new NoProjectPageTable(_("Project page table does not exist."));
        }

        // if state is null, get all pages
        $where = $state ? sprintf("state = '%s'", DPDatabase::escape($state)) : "1";

        validate_projectID($this->projectid);
        $sql = "
            SELECT count($counter_sql)
            FROM {$this->projectid}
            WHERE $where
            ";
        $res = DPDatabase::query($sql);
        [$num_pages] = mysqli_fetch_row($res);

        return $num_pages;
    }

    public function get_num_pages()
    {
        return $this->get_num_pages_in_state(null);
    }

    public function get_page_names_from_db()
    {
        if (!$this->pages_table_exists) {
            throw new NoProjectPageTable(_("Project page table does not exist."));
        }

        validate_projectID($this->projectid);
        $page_image_names = [];
        $sql = "
            SELECT image
            FROM {$this->projectid}
            ORDER BY image
        ";
        $res = DPDatabase::query($sql);
        while ([$page_image] = mysqli_fetch_row($res)) {
            $page_image_names[] = $page_image;
        }

        return $page_image_names;
    }

    public function get_page_names_from_dir()
    {
        if (!$this->dir_exists) {
            throw new NoProjectDirectory(_("Project directory does not exist."));
        }

        // Because the project directory has both page images as well as
        // illustrations, the page images are defined as the subset of files in
        // the directory that also exist in the database.

        $db_names = $this->get_page_names_from_db();

        chdir($this->dir);
        $disk_names = glob("*.{png,jpg}", GLOB_BRACE);
        sort($disk_names);
        return array_intersect($db_names, $disk_names);
    }

    // Given a project image, return the file size in bytes. If the image
    // doesn't exist on disk, return NULL.
    // TODO: This function would be better in a ProjectPage object, but we
    // don't have one of those.
    public function get_image_file_size($image)
    {
        $image_path = realpath("{$this->dir}/$image");
        if (file_exists($image_path)) {
            return filesize($image_path);
        } else {
            return null;
        }
    }

    public function get_illustrations()
    {
        // Illustrations are the set of files in the project directory that
        // do not match existing image names in the database.

        $page_image_names = $this->get_page_names_from_db();

        chdir($this->dir);
        $existing_image_names = glob("*.{png,jpg}", GLOB_BRACE);
        sort($existing_image_names);
        return array_diff($existing_image_names, $page_image_names);
    }

    // -------------------------------------------------------------------------

    private function _create_credit_line()
    // The string will not be localized, since it should be ready
    // to be included with the finished project.
    {
        global $site_url;

        $credits = [];

        $creditables = [
            'pm' => $this->username,        // username
            'pp' => $this->postproofer,     // username
            'ip' => $this->image_preparer,  // username
            'tp' => $this->text_preparer,   // username
            'ec' => $this->extra_credits,   // arbitrary text
            'cp' => $this->scannercredit,    // username or arbitrary text
        ];

        foreach ($creditables as $role => $name) {
            if ($name == '') {
                continue;
            }

            $credit = null;

            if ($role == 'cp') {
                // $name ($project->scannercredit) could be
                // a username or just a typed-in string
                try {
                    $user = new User($name);
                } catch (NonexistentUserException $exception) {
                    // $name is not a username.
                    // So use it as the credit.
                    $credit = $name;
                }
            }

            if ($role == 'ec') {
                // $name ($project->extra_credits) should not be a username;
                // It is just a typed-in string, and will be presented as is,
                // as part of the list.
                $credit = $name;
            }

            if (is_null($credit)) {
                // $name is a username.
                $username = $name;

                if (!wants_anonymity($username, $role)) {
                    $credit = get_credit_name($username);

                    // If the user has specified a Credit Name of "other" but used
                    // an empty string we should treat it as no credit wanted.
                    if ($credit === "") {
                        $credit = null;
                    }
                }
            }

            if (!is_null($credit)) {
                if (!in_array($credit, $credits)) {
                    array_push($credits, $credit);
                }
            }
        }

        if (count($credits) > 0) {
            $credits_line = join(', ', $credits) . " and the Online Distributed Proofreading Team at $site_url";
        } else {
            $credits_line = "The Online Distributed Proofreading Team at $site_url";
        }

        return $credits_line;
    }

    // -------------------------------------------------------------------------

    public function ensure_topic()
    {
        if (!empty($this->topic_id)) {
            return $this->topic_id;
        }

        // Find out PM's preference about being signed up for notifications of replies to this topic.
        $userSettings = & Settings::get_Settings($this->username);
        $sign_PM_up = $userSettings->get_boolean("auto_proj_thread");

        // determine appropriate forum to create thread in
        $forum_id = get_forum_id_for_project_state($this->state);

        $post_subject = "\"{$this->nameofwork}\" by {$this->authorsname}";

        validate_projectID($this->projectid);
        global $code_url;
        $post_body = <<<EOS
            This thread is for discussion specific to "$this->nameofwork" by $this->authorsname.

            If you have a question about this project, please review the [url=$code_url/project.php?id={$this->projectid}&detail_level=2]Project Comments[/url], as well as any posts below, as your question may already be answered there.

            If you haven't found the answer to your question, or want to make a comment about this project, click on the [b]Post Reply[/b] button to post your question or comment in this thread.

            (This post is automatically generated.)
            EOS;

        $topic_id = topic_create(
            $forum_id,
            $post_subject,
            $post_body,
            $this->username,
            true,
            $sign_PM_up);

        // if topic_id is NULL, something went wrong when creating the topic
        if ($topic_id === null) {
            return $topic_id;
        }

        // Save $topic_id in db and in $this.
        $sql = sprintf("
            UPDATE projects
            SET topic_id=%d
            WHERE projectid='%s'",
            $topic_id,
            DPDatabase::escape($this->projectid)
        );
        $update_project = DPDatabase::query($sql);
        $this->topic_id = $topic_id;

        return $topic_id;
    }

    // -------------------------------------------------------------------------

    public function email_introduction()
    // Return a string that can be included in the body of an email message,
    // introducing this project as the focus of the message.
    {
        global $site_name, $code_url;

        $projectid = $this->projectid;
        $title = $this->nameofwork;

        return implode("\n", [
            // Should really be localized according to the preferences of the *recipient*.
            _("This message is regarding the following project:"),
            "",
            "\"$title\"",
            "    $code_url/project.php?id=$projectid",
            "",
        ]);
    }

    // -------------------------------------------------------------------------

    public function log_project_event($who, $event_type, $details1 = '', $details2 = '', $details3 = '')
    {
        validate_projectID($this->projectid);
        $sql = sprintf("
            INSERT INTO project_events
            SET
                timestamp  = UNIX_TIMESTAMP(),
                projectid  = '%s',
                who        = '%s',
                event_type = '%s',
                details1   = '%s',
                details2   = '%s',
                details3   = '%s'
        ", DPDatabase::escape($this->projectid),
            DPDatabase::escape($who),
            DPDatabase::escape($event_type),
            DPDatabase::escape($details1),
            DPDatabase::escape($details2),
            DPDatabase::escape($details3));
        DPDatabase::query($sql);
    }

    public static function get_holdable_states()
    {
        global $Round_for_round_id_;

        $states = [];
        foreach ($Round_for_round_id_ as $round) {
            foreach (['project_waiting_state', 'project_available_state'] as $s) {
                $states[] = $round->$s;
            }
        }

        return $states;
    }

    public function get_hold_states()
    // Return an array containing the states for which this project has holds.
    {
        $hold_states = [];
        $sql = sprintf("
            SELECT state
            FROM project_holds
            WHERE projectid='%s'",
            DPDatabase::escape($this->projectid)
        );
        $res = DPDatabase::query($sql);
        while ([$state] = mysqli_fetch_row($res)) {
            $hold_states[] = $state;
        }
        return $hold_states;
    }

    public function add_holds($states)
    {
        global $pguser;

        $values = [];
        foreach ($states as $state) {
            $values[] = sprintf("('%s', '%s', 0)",
                DPDatabase::escape($this->projectid),
                DPDatabase::escape($state)
            );
        }
        $values = join(', ', $values);
        $sql = "
            INSERT INTO project_holds
            VALUES $values
        ";
        DPDatabase::query($sql);
        $this->log_project_event($pguser, 'add_holds', join(' ', $states));
    }

    public function remove_holds($states)
    {
        global $pguser;

        // We need to pre-escape the projectID because states can have
        // %'s in them which mess up sprintf() if used with our pre-built
        // $state_values
        $projectid_clause = sprintf(
            "projectid='%s'",
            DPDatabase::escape($this->projectid)
        );

        $state_values = [];
        foreach ($states as $state) {
            $state_values[] = sprintf("'%s'", DPDatabase::escape($state));
        }
        $state_values = join(', ', $state_values);
        $sql = "
            DELETE FROM project_holds
            WHERE
                $projectid_clause
                AND state in ($state_values)
        ";
        DPDatabase::query($sql);
        $this->log_project_event($pguser, 'remove_holds', join(' ', $states));
    }

    public function get_hold_state_notify_time($state)
    {
        $sql = sprintf("
            SELECT notify_time
            FROM project_holds
            WHERE projectid = '%s'
                AND state = '%s'
        ", DPDatabase::escape($this->projectid), DPDatabase::escape($state));
        $res = DPDatabase::query($sql);
        [$notify_time] = mysqli_fetch_row($res);
        return $notify_time;
    }

    public function update_hold_state_notify_time($state, $timestamp = null)
    {
        $notify_time = $timestamp === null ? time() : sprintf("%d", $timestamp);

        $sql = sprintf("
            UPDATE project_holds
            SET notify_time = $notify_time
            WHERE
                projectid = '%s'
                AND state = '%s'
        ", DPDatabase::escape($this->projectid), DPDatabase::escape($state));
        DPDatabase::query($sql);
    }

    public function send_hold_state_notification($state)
    {
        configure_gettext_for_user($this->username);
        maybe_mail_project_manager(
            $this,
            sprintf(_("This project is unable to transition out of %s because it is held. Please review the project and remove the hold. You will receive this notification only once unless you remove and re-add the hold or another page is proofread."), $state),
            _("Project Held"));
        configure_gettext_for_user();

        $this->update_hold_state_notify_time($state);
    }

    public function is_hold_notification_required($state)
    {
        // if there is no project hold on this state, no notification is required
        if (!in_array($state, $this->get_hold_states())) {
            return false;
        }

        // if the PM has not been notified, notification is required
        $notify_time = $this->get_hold_state_notify_time($state);
        if (!$notify_time) {
            return true;
        }

        // if the PM has been notified, but that was before the last page
        // was saved, reset the notification and send them another one
        $round = get_Round_for_project_state($state);
        if ($notify_time < $this->get_last_proofread_time($round)) {
            $this->update_hold_state_notify_time($state, 0);
            return true;
        }

        return false;
    }

    // -------------------------------------------------------------------------

    // Given a MARCRecord, write a dc XML document for this project
    public function create_dc_xml_oai($marc_record)
    {
        global $charset, $site_name, $code_url;

        $dirname = $this->dir;
        $filename = "$dirname/dc.xml";

        if (!is_dir($dirname)) {
            // If the project directory doesn't exist, the project was likely
            // deleted or posted/archived.
            return;
        }

        if (!file_exists($filename)) {
            touch($filename);
        }

        // Encode fields for XML heredoc
        $title = xmlencode($this->nameofwork);
        $creator = xmlencode($this->authorsname);
        $subject = xmlencode($marc_record->subject);
        $description = xmlencode($marc_record->description);
        $publisher = xmlencode($site_name);
        $contributor = xmlencode($this->credits_line);
        $type = xmlencode($this->genre);
        $language = xmlencode($this->language);

        $xmlpage = <<<XML
            <?xml version="1.0" encoding="$charset" ?>
                <dc xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://purl.org/dc/elements/1.1/ http://www.openarchives.org/OAI/dc.xsd">
                  <title>$title</title>
                  <creator>$creator</creator>
                  <subject>$subject</subject>
                  <description>$description</description>
                  <publisher>$publisher</publisher>
                  <contributor>$contributor</contributor>
                  <date>$marc_record->date</date>
                  <type>$type</type>
                  <format>XML</format>
                  <identifier>$code_url/project.php?id=$this->projectid</identifier>
                  <source>LCCN: $marc_record->lccn</source>
                  <language>$language</language>
                </dc>
            XML;

        $fp = fopen($filename, "w");
        if ($fp) {
            fwrite($fp, $xmlpage);
            fclose($fp);
        }
    }

    // MARC records for a project are saved in marc_records, one per projectid.
    // The original_* columns hold the values returned from the YAZ search
    // upon project creation. The updated_* columns hold updated YAZ records
    // based on edits to the project record.

    // Populate the original_* columns for this project's MARC record
    public function init_marc_record($marc_record)
    {
        $original_marc_array = $marc_record->get_yaz_array();

        $sql = sprintf("
            INSERT INTO marc_records
            SET
                projectid      = '%s',
                original_array = '%s'",
            DPDatabase::escape($this->projectid),
            base64_encode(serialize($original_marc_array))
        );
    }

    // Update the updated_* columns for this project's MARC record
    public function save_marc_record($marc_record)
    {
        $updated_marc_array = $marc_record->get_yaz_array();
        $sql = sprintf("
            UPDATE marc_records
            SET
                updated_array = '%s'
            WHERE projectid = '%s'",
            base64_encode(serialize($updated_marc_array)),
            DPDatabase::escape($this->projectid)
        );
        DPDatabase::query($sql);

        // Update project's Dublin Core file
        $this->create_dc_xml_oai($marc_record);
    }

    // Load the updated MARC record for this project
    public function load_marc_record()
    {
        $updated_record = new MARCRecord();
        $sql = sprintf("
            SELECT updated_array
            FROM marc_records
            WHERE projectid = '%s'",
            DPDatabase::escape($this->projectid)
        );
        $result = DPDatabase::query($sql);

        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        if (!$row) {
            return $updated_record;
        }

        $updated_record->load_yaz_array(unserialize(base64_decode($row["updated_array"])));

        return $updated_record;
    }

    // Update the project's MARC record from the current Project values
    public function update_marc_record()
    {
        $marc_record = $this->load_marc_record();

        // Mapping of Project field names to MARC field names
        $marc_field_map = [
            "nameofwork" => "title",
            "authorsname" => "author",
            "genre" => "literary_form",
        ];

        foreach ($marc_field_map as $project_field => $marc_field) {
            if ($this->$project_field) {
                $marc_record->$marc_field = $this->$project_field;
            }
        }

        // Only pull out the primary language
        if ($this->languages) {
            $marc_record->language = langcode3_for_langname($this->languages[0]);
        }

        $this->save_marc_record($marc_record);
    }

    // check if project has entered a formatting round
    public function has_entered_formatting_round()
    {
        global $PROJECT_STATES_IN_ORDER;
        $state_index = array_search($this->state, $PROJECT_STATES_IN_ORDER);
        for ($i = $state_index; $i >= 0; $i--) {
            $round = get_Round_for_project_state($PROJECT_STATES_IN_ORDER[$i]);
            if ($round && is_formatting_round($round)) {
                return true;
            }
        }
        return false;
    }

    // get time for last page proofread in round
    public function get_last_proofread_time($round)
    {
        validate_projectID($this->projectid);
        $sql = sprintf("
            SELECT max({$round->time_column_name})
            FROM $this->projectid
            WHERE state = '%s'
        ", DPDatabase::escape($round->page_save_state));
        $res = DPDatabase::query($sql);
        [$last_proofread] = mysqli_fetch_row($res);
        return $last_proofread;
    }

    public function check_pages_table_exists(&$message)
    {
        if (!$this->pages_table_exists) {
            if ($this->archived != 0) {
                $message = _("The project has been archived, so page details are not available.");
            } elseif ($this->state == PROJ_DELETE) {
                $message = _("The project has been deleted, so page details are not available.");
            } else {
                $message = _("Page details are not available for this project.");
            }
            return false;
        }
        $message = "";
        return true;
    }

    // Used for checking whether a book is currently in the Smooth Reading Pool
    public function is_available_for_smoothreading()
    {
        return $this->state == PROJ_POST_FIRST_CHECKED_OUT &&
               $this->smoothread_deadline > time();
    }

    private function delete_file($path)
    {
        // ensure $path is inside $this->dir
        if (!startswith(realpath($path), realpath($this->dir))) {
            throw new UnexpectedValueException("$path is not in $this->dir");
        }
        if (is_dir($path)) {
            // find also hidden files
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $sub_file) {
                $this->delete_file("$path/$sub_file");
            }
            rmdir($path);
        } else {
            unlink($path);
        }
    }

    public function delete_smoothreading_dir()
    {
        $dir = "$this->dir/smooth";
        if (file_exists($this->dir) && file_exists($dir)) {
            $this->delete_file($dir);
        }
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_has_a_hold_in_state($projectid, $state)
{
    $sql = sprintf("
        SELECT *
        FROM project_holds
        WHERE projectid='%s'
            AND state='%s'",
        DPDatabase::escape($projectid),
        DPDatabase::escape($state)
    );
    $res = DPDatabase::query($sql);
    return (mysqli_num_rows($res) > 0);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_get_auto_PPer($projectid)
// Return the username of the user to whom the project will be
// automatically checked out for PPing when it reaches the PP stage,
// or NULL if the project will merely go into the available-for-PP state.
{
    $project = new Project($projectid);
    $checkedoutby = $project->checkedoutby;
    $username = $project->username;
    $possible_PPer = '';
    if ($checkedoutby != '') {
        // The project is reserved for a PPer, so it will be checked out to him/her.
        $possible_PPer = $checkedoutby;
    } else {
        // The project does not have a reserved PPer.

        $settings = & Settings::get_Settings($username);
        if ($settings->get_boolean('send_to_post')) {
            // The PM has send_to_post=yes, so his/her projects go straight to PP.avail.
            return null;
        } else {
            // Otherwise, his/her projects are auto-checked-out to him/her.
            $possible_PPer = $username;
        }
    }
    // now see if the possible PPer is allowed to check projects out
    // when the relevant bit is uncommented,
    // note that it will mean that the send_to_post setting has no effect
    // if the PM does not have PPer status
    if ($possible_PPer == ''
         || ! user_can_work_in_stage($possible_PPer, 'PP')
         || that_user_is_over_PP_checked_out_limit($possible_PPer)) {
        return null;
    } else {
        return $possible_PPer;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// The following two functions don't particularly belong here, as they aren't
// project-specific. However, nobody else uses them yet.

// $activity should be one of 'cp', 'pm', 'pp', 'ip' and 'tp'.
function wants_anonymity($login_name, $activity)
{
    $settings = & Settings::get_Settings($login_name);
    return $settings->get_boolean($activity . '_anonymous');
}

// -----------------------------------------------------------------------------

// Returns the real name OR the username OR a user-specified 'other'.
// (If the user hasn't specified anything in the preferences, the
// real name will be returned.
function get_credit_name($login_name)
{
    if ($login_name == '') {
        return '(no name)';
    }

    $settings = & Settings::get_Settings($login_name);
    $credit = $settings->get_value('credit_name', 'real_name');

    if ($credit == 'username') {
        $name = $login_name;
    } elseif ($credit == 'other') {
        $name = trim($settings->get_value('credit_other'));
    } else { // default: real_name
        try {
            $user = new User($login_name);
            $name = $user->real_name;
        } catch (NonexistentUserException $exception) {
            $name = $login_name;
        }
    }
    return $name;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_formatted_postcomments($projectid)
// Return the PP-ers' comments for a certain project
// They will be HTML-encoded and with line breaks
// converted to <br> .
{
    $project = new Project($projectid);

    return nl2br(html_safe($project->postcomments));
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Determines if the project's pages table exists
function does_project_page_table_exist($projectid)
{
    return DPDatabase::does_table_exist($projectid);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Confirm that $projectid is a well-formed project ID or raise an exception.
function validate_projectID($projectid)
{
    if (1 != preg_match('/^projectID[0-9a-f]{13}$/', $projectid)) {
        throw new InvalidProjectIDException(sprintf(
            _("%s is not a valid project ID."),
            $projectid
        ));
    }
}

// Get a projectid from an array (usually $_GET or $_POST) and validate that
// it is well-formed. This function mirrors the other get_*() functions in
// misc.inc.
function get_projectID_param($arr, $key, $allownull = false)
{
    $projectid = @$arr[$key];

    if (isset($projectid) && !is_string($projectid)) {
        throw new InvalidArgumentException(sprintf(
            _("Parameter '%1\$s' is not a valid type"),
            $key));
    }

    if ($projectid == null && $allownull) {
        return null;
    }

    try {
        validate_projectID($projectid);
    } catch (InvalidProjectIDException $exception) {
        throw new InvalidProjectIDException(sprintf(
            _("The value of parameter '%1\$s' ('%2\$s') is not a valid projectID."),
            $key,
            $projectid
        ));
    }
    return $projectid;
}

// Confirm that $page_name is a well-formed page filename or raise an exception.
function validate_page_image($page_name)
{
    if (1 != preg_match('/^[a-zA-Z0-9_.-]{5,16}$/', $page_name)) {
        throw new InvalidPageException(sprintf(
            _("%s is not a valid page image filename."),
            $page_name
        ));
    }
}

// Get a page name from an array (usually $_GET or $_POST) and validate that
// it is well-formed. This function mirrors the other get_*() functions in
// misc.inc.
function get_page_image_param($arr, $key, $allownull = false)
{
    $page_name = @$arr[$key];

    if (isset($page_name) && !is_string($page_name)) {
        throw new InvalidArgumentException(sprintf(
            _("Parameter '%1\$s' is not a valid type"),
            $key));
    }

    if ($page_name == null && $allownull) {
        return null;
    }

    try {
        validate_page_image($page_name);
    } catch (InvalidPageException $exception) {
        throw new InvalidPageException(sprintf(
            _("The value of parameter '%1\$s' ('%2\$s') is not a valid page name."),
            $key,
            $page_name
        ));
    }
    return $page_name;
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Image source functions

function load_image_sources()
{
    static $image_sources = [];
    if ($image_sources) {
        return $image_sources;
    }

    $sql = "
        SELECT *
        FROM image_sources
        ORDER BY display_name
    ";
    $result = DPDatabase::query($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $image_sources[(string)$row['code_name']] = $row;
    }
    mysqli_free_result($result);
    return $image_sources;
}

// Can the current user see this image source based on the source's settings
function can_user_see_image_source($image_source)
{
    // info page visibility
    //  0 = Image Source Managers and SAs
    //  1 = also any PM
    //  2 = also any logged-in user
    //  3 = anyone

    if (user_is_image_sources_manager()) {
        $visibility = 0;
    } elseif (user_is_PM()) {
        $visibility = 1;
    } else {
        $visibility = 2;
    }

    return $visibility <= $image_source["info_page_visibility"];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Project difficulty

function get_project_difficulties()
{
    return [
        'beginner' => _('Beginner'),
        'easy' => _('Easy'),
        'average' => _('Average'),
        'hard' => _('Hard'),
    ];
}
