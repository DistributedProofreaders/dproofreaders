<?php
include_once($relPath.'user_is.inc');
include_once($relPath.'forum_interface.inc'); // topic_create
include_once($relPath.'Settings.inc');
include_once($relPath.'User.inc');
include_once($relPath.'MARCRecord.inc');
include_once($relPath.'CharSuites.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'wordcheck_engine.inc'); // delete_project_wordcheck_events()
include_once($relPath.'DPage.inc'); // project_allow_pages()
include_once($relPath.'send_mail.inc');

use Symfony\Component\Process\Process;

class UTF8ConversionException extends Exception
{
}

/**
 * Base exception that all Project exceptions should derive from.
 *
 * These exception codes range from 100 to 199
 */
class ProjectException extends Exception
{
    public function __construct(string $message, int $code = 100)
    {
        parent::__construct($message, $code);
    }
}
class NonexistentProjectException extends ProjectException
{
    public function __construct(string $message, int $code = 101)
    {
        parent::__construct($message, $code);
    }
}
class InvalidProjectIDException extends ProjectException
{
    public function __construct(string $message, int $code = 102)
    {
        parent::__construct($message, $code);
    }
}
// 103 is unused, was InvalidRoundException extends ProjectException
class NonexistentPageException extends ProjectException
{
    public function __construct(string $message, int $code = 104)
    {
        parent::__construct($message, $code);
    }
}
class InvalidPageRoundException extends ProjectException
{
    public function __construct(string $message, int $code = 105)
    {
        parent::__construct($message, $code);
    }
}
class InvalidPageException extends ProjectException
{
    public function __construct(string $message, int $code = 106)
    {
        parent::__construct($message, $code);
    }
}
class NoProjectPageTable extends ProjectException
{
    public function __construct(string $message, int $code = 107)
    {
        parent::__construct($message, $code);
    }
}
class NoProjectDirectory extends ProjectException
{
    public function __construct(string $message, int $code = 108)
    {
        parent::__construct($message, $code);
    }
}
class ProjectPageException extends ProjectException
{
    public function __construct(string $message, int $code = 109)
    {
        parent::__construct($message, $code);
    }
}
class ProjectNotInRoundException extends ProjectException
{
    public function __construct(string $message, int $code = 110)
    {
        parent::__construct($message, $code);
    }
}

class ProjectRequiresMaintenanceException extends ProjectException
{
    public function __construct(string $message, int $code = 111)
    {
        parent::__construct($message, $code);
    }
}

class ProjectNotAvailableException extends ProjectException
{
    public function __construct(string $message, int $code = 112)
    {
        parent::__construct($message, $code);
    }
}

class ProjectNoMorePagesException extends ProjectException
{
    public function __construct(string $message, int $code = 113)
    {
        parent::__construct($message, $code);
    }
}

class ProjectPageStateException extends ProjectException
{
    public function __construct(string $message, int $code = 115)
    {
        parent::__construct($message, $code);
    }
}

class ProjectPageInconsistentStateException extends ProjectException
{
    public function __construct(string $message, int $code = 119)
    {
        parent::__construct($message, $code);
    }
}

class ProjectNotInRequiredStateException extends ProjectException
{
    public function __construct(string $message, int $code = 120)
    {
        parent::__construct($message, $code);
    }
}

class ProjectInvalidTextException extends ProjectException
{
    public function __construct(string $message, int $code = 125)
    {
        parent::__construct($message, $code);
    }
}

class ProjectTransitionException extends ProjectException
{
    public function __construct(string $message, int $code = 126)
    {
        parent::__construct($message, $code);
    }
}

class ProjectPage
{
    public $page_name;
    public $page_state;

    public function __construct(string $pagename, string $pagestate)
    {
        $this->page_name = $pagename;
        $this->page_state = $pagestate;
    }
}

/**
 * @property-read string $url
 * @property-read string $dir
 * @property-read string $page_url
 * @property-read bool $is_utf8
 * @property-read bool $dir_exists
 * @property-read bool $pages_table_exists
 * @property-read string $credits_line
 * @property string[] $languages
 * @property-read string $image_source_name
 * @property-read bool $can_be_managed_by_current_user
 * @property-read bool $user_can_delete_nonpage_images
 * @property-read bool $names_can_be_seen_by_current_user
 * @property-read string $PPer
 * @property-read string $PPVer
 * @property-read bool $PPer_is_current_user
 * @property-read bool $PPVer_is_current_user
 * @property-read bool $forum_url
 * @property-read string $round_type
 */
class Project
{
    use CharSuiteSet;

    private $_create_source = null;
    public string $nameofwork;
    public ?string $state;
    public string $special_code;
    public int $smoothread_deadline;
    public ?string $projectid;
    public string $custom_chars;
    public bool $archived;
    public string $authorsname;
    public string $genre;
    public string $checkedoutby;
    public string $difficulty;
    public string $username;
    public ?int $postednum;
    public ?int $topic_id;
    public float $days_checkedout;
    public int $n_available_pages;
    public int $n_pages;
    public int $modifieddate;
    public int $t_last_edit;
    public int $t_last_change_comments;
    public int $t_last_page_done;
    public string $image_source;
    public ?string $image_source_credit;
    public string $_image_source_name;
    public string $image_preparer;
    public string $text_preparer;
    public string $clearance;
    public string $language;
    public string $title;
    public string $postproofer;
    public ?string $ppverifier;
    public string $deletion_reason;
    public string $postcomments;
    public string $comment_format;
    public string $comments;
    public string $scannercredit;
    public string $extra_credits;
    public int $int_level;

    /** @param string|array<string, mixed>|null $arg */
    public function __construct(mixed $arg = null)
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
            throw new InvalidArgumentException("Project::Project(): 'arg' has unexpected type $arg_type");
        }
    }

    // -------------------------------------------------------------------------
    // Load & Validate

    /**
     * Return a list of default fields with their values.
     *
     * Note that the value types given here are enforced in validate() except
     * for nulls.
     *
     * @return array<string, ?string>
     */
    private function _get_field_defaults(): array
    {
        return [
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
            "ppverifier" => null,
            "postcomments" => "",
            "clearance" => "",
            "postednum" => null,
            "genre" => "",
            "difficulty" => "",
            "special_code" => "",
            "image_source" => "",
            "image_preparer" => "",
            "text_preparer" => "",
            "extra_credits" => "",
            "deletion_reason" => "",
            "custom_chars" => "",
            "state" => null,
        ];
    }

    /**
     * Initialize Project with core fields and default values.
     *
     * These alone aren't sufficient to validate() and persist the project.
     */
    private function _init_fields_to_defaults(): void
    {
        foreach ($this->_get_field_defaults() as $key => $value) {
            $this->$key = $value;
        }
    }

    private function _load_from_db(string $projectid): void
    {
        $sql = sprintf(
            "
            SELECT *
            FROM projects
            WHERE projectid = '%s'
            ",
            DPDatabase::escape($projectid)
        );
        $res = DPDatabase::query($sql);
        $row = mysqli_fetch_assoc($res);
        if (!$row) {
            throw new NonexistentProjectException(sprintf(_("There is no project with projectid '%s'"), $projectid));
        }
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
    }

    public function populate_from_marc_record(string $encoded_marc_array): void
    {
        $yaz_array = unserialize(base64_decode($encoded_marc_array));
        if (!$yaz_array) {
            throw new InvalidMARCRecord();
        }

        $marc_record = new MARCRecord();
        $marc_record->load_yaz_array($yaz_array);

        $this->nameofwork = $marc_record->title;
        $this->authorsname = $marc_record->author;
        $this->languages = array_slice(explode(", ", $marc_record->language), 0, 2);
        $this->genre = $marc_record->literary_form;
    }

    /** @return string[] */
    public function validate(bool $throw_on_error = false): array
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

        // validate types
        foreach ($this->_get_field_defaults() as $field => $value) {
            if ($value !== null && gettype($value) != gettype($this->$field)) {
                $errors[] = "Field $field should be of type " . gettype($value);
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
            "comment_format",
        ];

        foreach ($required_fields as $field) {
            if (!$this->$field || preg_match('/^\s*$/', $this->$field)) {
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
            if (!preg_match('/^\s*$/', $this->$field ?? '') && !User::is_valid_user($this->$field)) {
                $errors[] = sprintf(
                    _("%s must be an existing user - check case and spelling and ensure there is no trailing whitespace."),
                    $labels[$field]
                );
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

            if (sizeof(array_unique($this->languages)) != sizeof($this->languages)) {
                $errors[] = _("Languages must be unique.");
            }
        }

        if ($this->genre && !array_key_exists($this->genre, load_genre_translation_array())) {
            $errors[] = sprintf(_("%s is not a valid genre."), $this->genre);
        }

        if ($this->image_source && !in_array($this->image_source, array_extract_field(load_image_sources(), "code_name"))) {
            $errors[] = sprintf(_("%s is not a valid image source."), $this->image_source);
        }

        if ($this->difficulty && !array_key_exists($this->difficulty, get_project_difficulties())) {
            $errors[] = sprintf(_("%s is not a valid difficulty."), $this->difficulty);
        }

        if ($this->special_code) {
            if (str_starts_with($this->special_code, 'Birthday') ||
                 str_starts_with($this->special_code, 'Otherday')
            ) {
                if (preg_match("/\w+ (\d{2})(\d{2})/", $this->special_code, $matches)) {
                    if (!checkdate((int)$matches[1], (int)$matches[2], 2000)) {
                        $errors[] = _("Invalid date supplied for Birthday or Otherday Special.");
                    }
                } else {
                    $errors[] = _("Month and Day are required for Birthday or Otherday Specials.");
                }
            } else {
                if (!array_key_exists($this->special_code, load_special_days())) {
                    $errors[] = sprintf(_("%s is not a valid special day."), $this->special_code);
                }
            }
        }

        if ($this->comment_format && !in_array($this->comment_format, ["markdown", "html"])) {
            $errors[] = sprintf(_("%s is not a valid comment format."), $this->comment_format);
        }

        // don't allow an empty PPer/PPVer if the project is checked out
        if (($this->state == PROJ_POST_FIRST_CHECKED_OUT ||
             $this->state == PROJ_POST_SECOND_CHECKED_OUT) &&
             $this->checkedoutby == '') {
            $errors[] = _("This project is checked out and must specify a PPer/PPVer");
        }

        // $this->postednum is either null or an integer by type definition.
        // Occasionally, there will be a PG ebook that is still
        // under U.S. copyright. This is indicated in their system
        // by appending a 'C' to the etext number. The link to
        // the etext, however, does not include the 'C', nor should
        // the DP link.

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
            throw new ProjectException(join("\n", $errors));
        }

        return $errors;
    }

    public function save(): void
    {
        // Disallow save if the object was created from an array. There's too
        // great a chance that there might be missing fields.
        if ($this->_create_source == "array") {
            throw new ProjectException("Saving a Project object from an array source is disallowed.");
        }

        // handle possibly-null fields
        $postednum_str = ($this->postednum == null) ? "NULL" : $this->postednum;
        $ppverifier_str = ($this->ppverifier == null) ? "NULL" : "'" . DPDatabase::escape($this->ppverifier) . "'";

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
            ppverifier     = $ppverifier_str,
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
                UPDATE projects
                SET
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
                $this->log_project_event(
                    User::current_username(),
                    'transition',
                    PROJ_POST_FIRST_CHECKED_OUT,
                    PROJ_POST_FIRST_CHECKED_OUT,
                    $this->checkedoutby
                );
            }
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

        // Create or update the metadata file
        try {
            $this->generate_metadata_json();
        } catch (NoProjectDirectory $exception) {
            // this is likely because the project has been deleted, either way
            // continue instead of failing
        }
    }

    public function delete(): void
    {
        // validate the projectID to ensure we are pointing to a valid
        // project directory
        validate_projectID($this->projectid);

        if (is_dir($this->dir)) {
            $process = new Process(["rm", "-rf", $this->dir]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new RuntimeException(sprintf(_("Unable to delete %s"), $this->dir));
            }
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

    public function __get(string $name): mixed
    {
        global $pguser;

        static $project_page_table_exists = null;

        switch ($name) {
            case "url":
                return SiteConfig::get()->projects_url . "/$this->projectid";
            case "dir":
                return SiteConfig::get()->projects_dir . "/$this->projectid";
            case "page_url":
                return SiteConfig::get()->code_url . "/project.php?id={$this->projectid}";
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
                return $this->_image_source_name;
            case "can_be_managed_by_current_user":
                return $this->can_be_managed_by_user($pguser);
            case "user_can_delete_nonpage_images":
                return $this->user_can_delete_nonpage_images($pguser);
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
            case "forum_url":
                return $this->topic_id ? get_url_to_view_topic($this->topic_id) : "";
            case "round_type":
                return $this->_get_round_type();
        }

        // If we don't recognize the property, raise a notice and return null
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
        return null;
    }

    public function __set(string $name, mixed $value): void
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

    private function _load_image_source(): void
    {
        $this->_image_source_name = '';
        $this->image_source_credit = '';

        if (!isset($this->image_source)) {
            return;
        }

        $image_sources = load_image_sources();
        $imso_res = $image_sources[(string)$this->image_source] ?? null;
        if ($imso_res) {
            $this->_image_source_name = $imso_res['full_name'];
            $this->image_source_credit = $imso_res['credit'];
        }
    }

    private function _get_credits_line(): string
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

    /**
     * Return an associative array of project fields with localized labels
     * for use in the UI.
     *
     * @return array<string, string>
     */
    public static function get_field_labels(): array
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

    /** @param array{0: string, 1?: string} $languages */
    public static function encode_languages(array $languages): string
    {
        // handle the case where the second language is empty
        if (count($languages) == 2 && $languages[1] == '') {
            return $languages[0];
        }

        return join(" with ", $languages);
    }

    /** @return string[] */
    public static function decode_language(string $language): array
    {
        return explode(" with ", $language);
    }

    // -------------------------------------------------------------------------

    public function convert_to_utf8(): bool
    {
        // nothing to do if the project table doesn't exist
        // this covers archived projects which are moved into a different DB
        if (!$this->pages_table_exists) {
            return false;
        }

        // and nothing to do if it is already UTF-8
        if ($this->is_utf8) {
            return false;
        }

        // get the default database collation
        $collation = DPDatabase::get_default_db_collation();

        validate_projectID($this->projectid);
        $sql = "
            ALTER TABLE $this->projectid CONVERT TO CHARACTER SET utf8mb4 COLLATE $collation;
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

    /**
     * @param string|CharSuite $charsuite
     * @return string[]
     */
    public static function projects_using_charsuite(mixed $charsuite): array
    {
        $charsuite = CharSuites::resolve($charsuite);

        $sql = sprintf(
            "
            SELECT projectid
            FROM project_charsuites
            WHERE charsuite_name='%s'
            ",
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

    /** @param string[]|CharSuite[] $charsuites */
    public function set_charsuites(mixed $charsuites): void
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

    /** @param string|CharSuite $charsuite */
    public function add_charsuite(mixed $charsuite): void
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
        $sql = sprintf(
            "
            INSERT INTO project_charsuites
            SET projectid='%s', charsuite_name='%s'
            ",
            DPDatabase::escape($this->projectid),
            DPDatabase::escape($charsuite->name)
        );
        DPDatabase::query($sql);
    }

    /** @param string|CharSuite $charsuite */
    public function remove_charsuite($charsuite): void
    {
        $charsuite = CharSuites::resolve($charsuite);

        $existing_charsuites = $this->get_charsuites(false);
        if (!in_array($charsuite, $existing_charsuites)) {
            return;
        }
        $sql = sprintf(
            "
            DELETE FROM project_charsuites
            WHERE projectid='%s' AND charsuite_name='%s'
            ",
            DPDatabase::escape($this->projectid),
            DPDatabase::escape($charsuite->name)
        );
        DPDatabase::query($sql);
    }

    /** @return CharSuite[] */
    public function get_charsuites(bool $include_custom = true): array
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
        $sql = sprintf(
            "
            SELECT charsuite_name
            FROM project_charsuites
            WHERE projectid='%s'
            ",
            DPDatabase::escape($this->projectid)
        );
        $result = DPDatabase::query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $charsuites[] = CharSuites::get($row["charsuite_name"]);
        }
        mysqli_free_result($result);
        return $charsuites;
    }

    public function get_custom_charsuite(): ?CharSuite
    {
        if (!$this->custom_chars) {
            return null;
        }

        $codepoints = utf8_codepoints_combining($this->custom_chars);
        $proj_suite = new CharSuite($this->projectid, _("Custom"), $codepoints, true);

        $pickerset = new PickerSet();
        $row1 = array_slice($codepoints, 0, (int)round(count($codepoints) / 2));
        $row2 = array_slice($codepoints, count($row1));
        $pickerset->add_subset("Proj", [$row1, $row2]);
        $proj_suite->pickerset = $pickerset;
        return $proj_suite;
    }

    // -------------------------------------------------------------------------

    private function _get_PPer(): string
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

    private function _get_PPVer(): string
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

    private function _get_round_type(): string
    {
        $round = get_Round_for_project_state($this->state);
        if (!$round) {
            return "none";
        }
        return is_formatting_round($round) ? "formatting" : "proofreading";
    }

    // -------------------------------------------------------------------------

    public function can_be_managed_by_user(?string $username): bool
    {
        if (is_null($username)) {
            return false;
        }
        return
            ($username == $this->username
            || that_user_is_a_sitemanager($username)
            || that_user_is_proj_facilitator($username));
    }

    public function user_can_delete_nonpage_images(?string $username): bool
    {
        if (is_null($username)) {
            return false;
        }
        // SAs can always delete nonpage images
        if (that_user_is_a_sitemanager($username)) {
            return true;
        }

        // PMs/PFs can only delete nonpage images in New
        // or P1 Unavailable stages
        if ($this->can_be_managed_by_user($username) &&
            in_array($this->state, [PROJ_NEW, PROJ_P1_UNAVAILABLE])) {
            return true;
        }

        // Nobody else can delete images
        return false;
    }

    // -------------------------------------------------------------------------

    public function names_can_be_seen_by_user(?string $username): bool
    {
        if (is_null($username)) {
            return false;
        }
        if (SiteConfig::get()->public_page_details) {
            return true;
        }
        return
            ($this->can_be_managed_by_user($username)
             || $this->PPer == $username
             || $this->PPVer == $username);
    }

    // -------------------------------------------------------------------------

    public function clearance_line_can_be_seen_by_current_user(): bool
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

    /**
     * Validate this project can be proofed by the current user
     *
     * Where "proofed" means "worked on in a round, right now".
     */
    public function validate_can_be_proofed_by_current_user(): void
    {
        global $pguser;

        $project_round = $this->get_project_available_round();

        if (is_null($pguser)) {
            throw new UserNotLoggedInException(_('Registered users can work on the project, but you are not logged in.'));
        }

        $project_round->validate_user_can_access($pguser);
    }

    public function get_project_available_round(): Round
    {
        $state = $this->state;
        $project_round = get_Round_for_project_state($state);
        if (is_null($project_round)) {
            // The project is not in any round.
            throw new ProjectNotInRoundException(_("The project is not in a round"));
        }
        if (!$this->is_utf8) {
            // Project has not been converted to UTF-8 yet
            throw new ProjectRequiresMaintenanceException(_('The project requires maintenance. Project table is not UTF-8.'));
        }
        if ($state != $project_round->project_available_state) {
            // The project is in a round, but isn't available.
            throw new ProjectNotAvailableException(_('Users are not allowed to work on the project in its current state.'));
        }
        return $project_round;
    }

    public function user_can_do_quick_check(): bool
    {
        // used in PQC itself and to enable links to PQC
        // in project.php and ProjectSearchResults.inc
        return $this->can_be_managed_by_current_user || $this->PPer_is_current_user;
    }

    public function is_bad_from_pages(Round $round): bool
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

    /** @return string[] */
    public function get_page_states(): array
    {
        $states = [];

        validate_projectID($this->projectid);
        $sql = sprintf(
            "
            SELECT DISTINCT state
            FROM {$this->projectid}
            ORDER BY %s
            ",
            sql_collator_for_page_state("state")
        );
        $res = DPDatabase::query($sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $states[] = $row["state"];
        }
        mysqli_free_result($res);

        return $states;
    }

    public function get_num_pages_in_state(?string $state = null, string $counter_sql = "*"): int
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

    public function get_num_pages(): int
    {
        return $this->get_num_pages_in_state(null);
    }

    /** @return string[] */
    public function get_page_names_from_db(): array
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

    /** @return string[] */
    public function get_page_names_from_dir(): array
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

    /**
     * Given a project image, return the file size in bytes.
     *
     * If the image doesn't exist on disk, return NULL.
     */
    public function get_image_file_size(string $image): ?int
    {
        // TODO: This function would be better in a ProjectPage object, but we
        // don't have one of those.
        $image_path = realpath("{$this->dir}/$image");
        if (file_exists($image_path)) {
            return filesize($image_path);
        } else {
            return null;
        }
    }

    /** @return string[] */
    public function get_illustrations(): array
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

    /**
     * Return the current credit line for a project
     *
     * The string will not be localized, since it should be ready
     * to be included with the finished project.
     */
    private function _create_credit_line(): string
    {
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
            $credits_line = join(', ', $credits) . " and the Online Distributed Proofreading Team at " . SiteConfig::get()->site_url;
        } else {
            $credits_line = "The Online Distributed Proofreading Team at " . SiteConfig::get()->site_url;
        }

        return $credits_line;
    }

    // -------------------------------------------------------------------------

    public function ensure_topic(): ?int
    {
        if (!empty($this->topic_id)) {
            return $this->topic_id;
        }

        // Find out PM's preference about being signed up for notifications of replies to this topic.
        $userSettings = & Settings::get_Settings($this->username);
        $sign_PM_up = $userSettings->get_boolean("auto_proj_thread");

        // determine appropriate forum to create thread in
        $forum_id = ProjectStates::get_forum($this->state);

        $post_subject = "\"{$this->nameofwork}\" by {$this->authorsname}";

        validate_projectID($this->projectid);
        $post_body = <<<EOS
            This thread is for discussion specific to "$this->nameofwork" by $this->authorsname.

            If you have a question about this project, please review the [url={$this->page_url}]Project Comments[/url], as well as any posts below, as your question may already be answered there.

            If you haven't found the answer to your question, or want to make a comment about this project, click on the [b]Post Reply[/b] button to post your question or comment in this thread.

            (This post is automatically generated.)
            EOS;

        $topic_id = topic_create(
            $forum_id,
            $post_subject,
            $post_body,
            $this->username,
            true,
            $sign_PM_up
        );

        // if topic_id is NULL, something went wrong when creating the topic
        if ($topic_id === null) {
            return $topic_id;
        }

        // Save $topic_id in db and in $this.
        $sql = sprintf(
            "
            UPDATE projects
            SET topic_id=%d
            WHERE projectid='%s'
            ",
            $topic_id,
            DPDatabase::escape($this->projectid)
        );
        $update_project = DPDatabase::query($sql);
        $this->topic_id = $topic_id;

        return $topic_id;
    }

    // -------------------------------------------------------------------------

    /**
     * Return a string that can be included in the body of an email message,
     * introducing this project as the focus of the message.
     */
    public function email_introduction(): string
    {
        $projectid = $this->projectid;
        $title = $this->nameofwork;

        return implode("\n", [
            // Should really be localized according to the preferences of the *recipient*.
            _("This message is regarding the following project:"),
            "",
            mdmail_append_linebreak("\"$title\""),
            mdmail_indent_line($this->page_url),
            "",
        ]);
    }

    // -------------------------------------------------------------------------

    public function log_project_event(string $who, string $event_type, string $details1 = '', string $details2 = '', string $details3 = ''): void
    {
        validate_projectID($this->projectid);
        $sql = sprintf(
            "
            INSERT INTO project_events
            SET
                timestamp  = UNIX_TIMESTAMP(),
                projectid  = '%s',
                who        = '%s',
                event_type = '%s',
                details1   = '%s',
                details2   = '%s',
                details3   = '%s'
            ",
            DPDatabase::escape($this->projectid),
            DPDatabase::escape($who),
            DPDatabase::escape($event_type),
            DPDatabase::escape($details1),
            DPDatabase::escape($details2),
            DPDatabase::escape($details3)
        );
        DPDatabase::query($sql);
    }

    /** @return string[] */
    public static function get_holdable_states(): array
    {
        $states = [];
        foreach (Rounds::get_all() as $round) {
            foreach (['project_waiting_state', 'project_available_state'] as $s) {
                $states[] = $round->$s;
            }
        }

        return $states;
    }

    /**
     * Return an array containing the states for which this project has holds.
     *
     * @return string[]
     */
    public function get_hold_states(): array
    {
        $hold_states = [];
        $sql = sprintf(
            "
            SELECT state
            FROM project_holds
            WHERE projectid='%s'
            ",
            DPDatabase::escape($this->projectid)
        );
        $res = DPDatabase::query($sql);
        while ([$state] = mysqli_fetch_row($res)) {
            $hold_states[] = $state;
        }
        return $hold_states;
    }

    /** @param string[] $states */
    public function add_holds(array $states): void
    {
        global $pguser;

        $values = [];
        foreach ($states as $state) {
            $values[] = sprintf(
                "('%s', '%s', 0)",
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

    /** @param string[] $states */
    public function remove_holds(array $states): void
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

    public function get_hold_state_notify_time(string $state): ?int
    {
        $sql = sprintf(
            "
            SELECT notify_time
            FROM project_holds
            WHERE projectid = '%s'
                AND state = '%s'
            ",
            DPDatabase::escape($this->projectid),
            DPDatabase::escape($state)
        );
        $res = DPDatabase::query($sql);
        [$notify_time] = mysqli_fetch_row($res);
        return $notify_time;
    }

    public function update_hold_state_notify_time(string $state, ?int $timestamp = null): void
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

    public function send_hold_state_notification(string $state): void
    {
        configure_gettext_for_user($this->username);
        send_mail_project_manager(
            $this,
            sprintf(_("This project is unable to transition out of %s because it is held. Please review the project and remove the hold. You will receive this notification only once unless you remove and re-add the hold or another page is proofread."), $state),
            _("Project Held")
        );
        configure_gettext_for_user();

        $this->update_hold_state_notify_time($state);
    }

    public function is_hold_notification_required(string $state): bool
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
        $last_proofread_time = $this->get_last_proofread_time($round);
        if ($last_proofread_time != null && $notify_time < $last_proofread_time) {
            $this->update_hold_state_notify_time($state, 0);
            return true;
        }

        return false;
    }

    // -------------------------------------------------------------------------

    /**
     * Write out project's metadata to a JSON file
     */
    public function generate_metadata_json(): void
    {
        if (!$this->dir_exists) {
            throw new NoProjectDirectory(_("Project directory does not exist."));
        }

        $metadata = [
            "projectid" => $this->projectid,
            "nameofwork" => $this->nameofwork,
            "authorsname" => $this->authorsname,
            "language" => $this->language,
            "genre" => $this->genre,
            "postednum" => $this->postednum,
            "image_source" => $this->image_source,
        ];

        file_put_contents("{$this->dir}/metadata.json", json_encode(
            $metadata,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
        ));
    }

    /**
     * Check if project has entered a formatting round
     */
    public function has_entered_formatting_round(): bool
    {
        $project_states = ProjectStates::get_states();
        $completed_states = array_slice($project_states, 0, array_search($this->state, $project_states) + 1);
        foreach (array_reverse($completed_states) as $state) {
            $round = get_Round_for_project_state($state);
            if ($round && is_formatting_round($round)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get time for last page proofread in round, or null if it hasn't
     * been proofread in the round at all.
     */
    public function get_last_proofread_time(Round $round): ?int
    {
        validate_projectID($this->projectid);
        $sql = sprintf(
            "
            SELECT max({$round->time_column_name})
            FROM $this->projectid
            WHERE state = '%s'
            ",
            DPDatabase::escape($round->page_save_state)
        );
        $res = DPDatabase::query($sql);
        [$last_proofread] = mysqli_fetch_row($res);
        return $last_proofread;
    }

    public function pages_table_missing_reason(): string
    {
        $message = "";

        if (!$this->pages_table_exists) {
            if ($this->archived) {
                $message = _("The project has been archived, so page details are not available.");
            } elseif ($this->state == PROJ_DELETE) {
                $message = _("The project has been deleted, so page details are not available.");
            } else {
                $message = _("Page details are not available for this project.");
            }
        }

        return $message;
    }

    /**
     * Returns if the project is available for smoothreading
     *
     * Used for checking whether a book is currently in the Smooth Reading Pool.
     *
     * @return bool
     */
    public function is_available_for_smoothreading(): bool
    {
        return $this->state == PROJ_POST_FIRST_CHECKED_OUT &&
               $this->smoothread_deadline > time();
    }

    private function delete_file(string $path): void
    {
        // ensure $path is inside $this->dir
        if (!str_starts_with(realpath($path), realpath($this->dir))) {
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

    public function delete_smoothreading_dir(): void
    {
        $dir = "$this->dir/smooth";
        if (file_exists($this->dir) && file_exists($dir)) {
            $this->delete_file($dir);
        }
    }

    public function get_project_page(?string $pagename): ProjectPage
    {
        if (!$this->pages_table_exists) {
            throw new NoProjectPageTable(_("Project page table does not exist."));
        }

        $sql = sprintf(
            "
            SELECT state
            FROM $this->projectid
            WHERE image='%s'
            ",
            DPDatabase::escape($pagename)
        );
        $res = DPDatabase::query($sql);
        $row = mysqli_fetch_row($res);
        if (!$row) {
            throw new NonexistentPageException("No such page in project");
        }
        [$pagestate] = $row;

        return new ProjectPage($pagename, $pagestate);
    }

    public function validate_text(string $text): void
    {
        $pattern_string = build_character_regex_filter($this->get_valid_codepoints());
        foreach (split_graphemes($text) as $grapheme) {
            if (1 != preg_match("/$pattern_string/u", $grapheme)) {
                throw new ProjectInvalidTextException(_("The text contains characters which are not allowed for this project"));
            }
        }
    }

    /** @return array<string, string> */
    public function find_invalid_characters(string $text): array
    {
        $pattern_string = build_character_regex_filter($this->get_valid_codepoints());
        $invalid_characters = [];
        foreach (split_graphemes($text) as $grapheme) {
            if (1 !== preg_match("/$pattern_string/u", $grapheme)) {
                $invalid_characters[$grapheme] = utf8_character_name($grapheme);
            }
        }
        return $invalid_characters;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_has_a_hold_in_state(string $projectid, string $state): bool
{
    $sql = sprintf(
        "
        SELECT *
        FROM project_holds
        WHERE projectid='%s'
            AND state='%s'
        ",
        DPDatabase::escape($projectid),
        DPDatabase::escape($state)
    );
    $res = DPDatabase::query($sql);
    return (mysqli_num_rows($res) > 0);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Return the username of the user to whom the project will be
 * automatically checked out for PPing when it reaches the PP stage,
 * or NULL if the project will merely go into the available-for-PP state.
 */
function project_get_auto_PPer(string $projectid): ?string
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

/**
 * Does the user want anonymity for a given activity.
 *
 * @param 'cp'|'pm'|'pp'|'ip'|'tp' $activity
 */
function wants_anonymity(string $login_name, string $activity): bool
{
    $settings = & Settings::get_Settings($login_name);
    return $settings->get_boolean($activity . '_anonymous');
}

// -----------------------------------------------------------------------------

/**
 * Returns the real name OR the username OR a user-specified 'other'.
 *
 * If the user hasn't specified anything in the preferences, the
 * real name will be returned.
 */
function get_credit_name(string $login_name): string
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

/**
 * Return the PP-ers' comments for a certain project.
 *
 * They will be HTML-encoded and with line breaks
 * converted to `<br>`.
 */
function get_formatted_postcomments(string $projectid): string
{
    $project = new Project($projectid);

    return nl2br(html_safe($project->postcomments));
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Determine if the project's pages table exists
 */
function does_project_page_table_exist(string $projectid): bool
{
    return DPDatabase::does_table_exist($projectid);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Confirm that $projectid is a well-formed project ID or raise an exception.
 */
function validate_projectID(?string $projectid): void
{
    if (!$projectid || 1 != preg_match('/^projectID[0-9a-f]{13}$/', $projectid)) {
        throw new InvalidProjectIDException(sprintf(
            _("%s is not a valid project ID."),
            $projectid ?? ""
        ));
    }
}

/**
 * Get a projectid from an array (usually $_GET or $_POST) and validate that
 * it is well-formed.
 *
 * This function mirrors the other get_*() functions in `misc.inc`.
 *
 * @param array<string, mixed> $arr
 */
function get_projectID_param(array $arr, string $key, bool $allownull = false): ?string
{
    $projectid = @$arr[$key];

    if (isset($projectid) && !is_string($projectid)) {
        throw new InvalidArgumentException(sprintf(
            _("Parameter '%1\$s' is not a valid type"),
            $key
        ));
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

/**
 * Validate that $page_name is a well-formed page filename or raise an exception.
 */
function validate_page_image(?string $page_name): void
{
    if (!$page_name || 1 != preg_match('/^[a-zA-Z0-9_.-]{5,16}$/', $page_name)) {
        throw new InvalidPageException(sprintf(
            _("%s is not a valid page image filename."),
            $page_name ?? ""
        ));
    }
}

/**
 * Get a page name from an array (usually $_GET or $_POST) and validate that
 * it is well-formed.
 *
 * This function mirrors the other get_*() functions in `misc.inc`.
 *
 * @param array<string, mixed> $arr
 */
function get_page_image_param(array $arr, string $key, bool $allownull = false): ?string
{
    $page_name = @$arr[$key];

    if (isset($page_name) && !is_string($page_name)) {
        throw new InvalidArgumentException(sprintf(
            _("Parameter '%1\$s' is not a valid type"),
            $key
        ));
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

/** @return array<string, mixed> */
function load_image_sources(): array
{
    static $image_sources = [];
    if ($image_sources) {
        return $image_sources;
    }

    // TRANSLATORS: %s is the site abbreviation
    $internal_name = sprintf(_("%s Internal"), SiteConfig::get()->site_abbreviation);

    $image_sources = [
        "_internal" => [
            "code_name" => "_internal",
            "display_name" => $internal_name,
            "full_name" => $internal_name,
            "info_page_visibility" => 3,
            "is_active" => 1,
            "url" => SiteConfig::get()->code_url,
            "credit" => "",
            "ok_show_images" => -1,
            "ok_keep_images" => -1,
            "public_comment" => "",
            "internal_comment" => "",
        ],
    ];

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

/**
 * Can the current user see this image source based on the source's settings
 *
 * @param array<string, mixed> $image_source
 */
function can_user_see_image_source(array $image_source): bool
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

/** @return array<string, string> */
function get_project_difficulties(): array
{
    return [
        'beginner' => _('Beginner'),
        'easy' => _('Easy'),
        'average' => _('Average'),
        'hard' => _('Hard'),
    ];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Project page detail level

/** @return array<int, string> */
function get_project_detail_levels(): array
{
    return [
        1 => _("Minimal"),
        2 => _("Basic"),
        3 => _("Advanced"),
        4 => _("Everything"),
    ];
}
