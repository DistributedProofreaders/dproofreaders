<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'links.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'image_check.inc');

require_login();

$extra_args = [
    'css_data' => '
        .mono {
            font-family: ' . get_proofreading_font_family_fallback() . ';
        }
    ',
    'js_data' => '
        window.addEventListener("DOMContentLoaded", function() {
            if (document.getElementById("execute")) {
                document.getElementById("execute").addEventListener("submit", function (e) {
                    // disable the submit button
                    document.getElementById("submit").disabled = true;
                    return true;
                });
            }
        });
    ',
];

$title = _("Add files");
output_header($title, NO_STATSBAR, $extra_args);

$projectid = get_projectID_param($_REQUEST, 'project');
$rel_source = array_get($_REQUEST, "rel_source", "");

abort_if_cant_edit_project($projectid);

echo "<h1>$title</h1>";

$project = new Project($projectid);
if (!$project->is_utf8) {
    echo "<p>"
        . _("Pages cannot be added to the project in its current state.")
        . " "
        . _("Project table is not UTF-8.")
        . "</p>";
    exit();
}

if (!user_can_add_project_pages($projectid)) {
    // abort if a load_disabled user is trying to load normal pages into an empty project
    check_user_can_load_projects(true); // exit if they can't

    // otherwise the state must have been wrong
    echo  "<p>"
        . _("Pages cannot be added to the project in its current state.")
        . "</p>";
    exit();
}

if ($rel_source == '') {
    die('rel_source parameter is empty or unset');
} else {
    // Prevent sneaky parent-link tricks.
    if (str_contains($rel_source, "..")) {
        echo "<p class='error'>";
        echo sprintf(_("Source directory '%s' is not acceptable."), html_safe($rel_source));
        echo "</p>";
        echo "<hr>\n";
        echo return_to_project_page_link($projectid) . "\n";
        return;
    }
}

$abs_source = "$uploads_dir/$rel_source";

if (!file_exists($abs_source)) {
    die("source does not exist: $abs_source");
}

if (substr($abs_source, -4) == ".zip") {
    // $abs_source is a zip file containing the project files

    // We will unpack it to a sibling directory.
    $source_project_dir = substr($abs_source, 0, -4);
    if (!file_exists($source_project_dir)) {
        mkdir($source_project_dir, 0777);
        chmod($source_project_dir, 0777);
    }

    extract_zip_to($abs_source, $source_project_dir);
    flatten_directory($source_project_dir);

    // (Assuming the unzip worked), remove the zip file.
    unlink($abs_source);

    $rel_source = substr($rel_source, 0, -4);
} else {
    $source_project_dir = $abs_source;
}

// Rather than performing commands from an arbitrary location,
// we chdir into $source_project_dir and do *local* commands from there.
// That way, we don't have to worry about any shell-special or
// glob-special characters in $source_project_dir.
$r = chdir($source_project_dir);
if (!$r) {
    echo "<p class='error'>";
    echo sprintf(_("Directory '%s' does not exist, or is inaccessible."), html_safe($source_project_dir));
    echo "</p>";
    echo "<hr>\n";
    echo return_to_project_page_link($projectid) . "\n";
    return;
}

$loader = new Loader($source_project_dir, $project->dir, $projectid);
$loader->analyze();

if (!@$_POST['confirmed']) {
    $loader->display();

    if ($loader->has_warnings()) {
        echo "<p class='warning'>";
        echo _("Some warnings issued, please check them before continuing.");
        echo "</p>";
    }

    echo "<p>";
    if ($loader->has_errors()) {
        echo "<span class='error'>" .  _("Please fix errors and try again.") . "</span>";
    } elseif ($loader->would_do_nothing()) {
        echo _("Nothing to do.");
    } else {
        echo "<form id='execute' method='POST' action='add_files.php'>";
        echo "<input type='hidden' name='confirmed' value='1'>";
        echo "<input type='hidden' name='project' value='" . attr_safe($projectid) . "'>";
        echo "<input type='hidden' name='rel_source' value='" . attr_safe($rel_source) . "'>";
        echo "<input id='submit' type='submit' value='" . attr_safe(_('Proceed with load')) . "'>";
        echo "</form>";
    }
    echo "</p>";
} else {
    if ($loader->has_errors()) {
        $loader->display();
        echo "<p>";
        echo _("Please fix errors and try again.");
        echo "</p>";
    } elseif ($loader->would_do_nothing()) {
        $loader->display();
        echo "<p>";
        echo _("Nothing to do.");
        echo "</p>";
    } else {
        $loader->execute();
        echo "<p>";
        echo _("Done.");
        echo "</p>";
    }
}

echo "<hr>\n";
echo return_to_project_page_link($projectid) . "\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class Loader
{
    private string $source_project_dir;
    private string $dest_project_dir;
    private string $projectid;
    private bool $adding_pages;
    private int $image_field_len;
    /** @var array<string, string> */
    private array $ignored_files;
    /** @var string[] */
    /** @var array<string, array{'error_msgs': string[], 'warning_msgs': string[]}> */
    private array $non_page_files;
    /** @var array<string, array{'text': array{'src'?: string[], 'db'?: string[], 'action': string}, 'image': array{'src'?: string[], 'db'?: string[], 'action': string}, 'error_msgs': string[], 'warning_msgs': string[]}> */
    private array $page_file_table;
    /** @var array<string, string> */
    private array $db_text_for_base;
    private int $n_ops;
    private int $n_warnings;
    private int $n_errors;
    private ImageUtils $checker;

    public function __construct(string $source_project_dir, string $dest_project_dir, string $projectid)
    {
        $this->source_project_dir = $source_project_dir;
        $this->dest_project_dir = $dest_project_dir;
        $this->projectid = $projectid;
        $this->checker = new ImageUtils();
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function analyze(): void
    {
        $this->n_errors = 0;
        $this->n_warnings = 0;
        $this->n_ops = 0;
        $this->adding_pages = false; // is this load adding any pages to the project?

        // Get the set of all files in the source (current) directory.
        $source_files = glob('*');

        // Get the set of all 'image' fields in the page-table.
        $db_entries = [];
        validate_projectID($this->projectid);
        $sql = "SELECT image,master_text FROM $this->projectid";
        $res = DPDatabase::query($sql);
        while ([$image, $text] = mysqli_fetch_row($res)) {
            $db_entries[$image] = $text;
        }

        // ---------------------------------------------------------------------

        // First, divide the source files into those that can be loaded
        // and those that will be ignored.

        $this->ignored_files = [];
        $loadable_files = [];
        foreach ($source_files as $source_file) {
            $reason_to_ignore = $this->_check_file($source_file);
            if ($reason_to_ignore) {
                $this->ignored_files[$source_file] = $reason_to_ignore;
            } else {
                $loadable_files[] = $source_file;
            }
        }

        // What about checking for case-insensitive collisions?

        // ---------------------------------------------------------------------

        // Next, divide the "loadable" files into those that are page-related
        // and those that aren't.
        // A "page-related" file is one that supplies either the text or the
        // image for a row in the project's page-table.
        // A non-page-related file is normally an illustration.

        // So how do we tell whether or not a file is page-related?
        //
        // One way to do it would be via a filename convention
        // (e.g., non-page-related files begin with 'illus-').
        // However, we don't have such a convention in place.
        //
        // Instead, we'll say that a file is page-related iff its 'base'
        // (i.e. the portion of its name preceding the final dot) equals
        // the base of either:
        // -- a loadable .txt file in the source directory, or
        // -- an 'image' field in the page-table.

        // So we need a list of all the page-related bases.
        $page_related_bases = [];

        foreach ($loadable_files as $source_file) {
            [$base, $ext] = split_filename($source_file);
            if ($ext == '.txt') {
                $page_related_bases[$base] = 1;
            }
        }

        foreach (array_keys($db_entries) as $db_image_file) {
            [$base, $ext] = split_filename($db_image_file);
            $page_related_bases[$base] = 1;
        }

        ksort($page_related_bases, SORT_STRING);

        // echo "<pre>"; var_dump($page_related_bases); echo "</pre>";

        // -----------------------------

        // Okay, now we can separate page-related files from non-.

        $page_files = [];
        $this->non_page_files = [];
        foreach ($loadable_files as $loadable_file) {
            [$base, $ext] = split_filename($loadable_file);
            if (array_key_exists($base, $page_related_bases)) {
                $page_files[] = $loadable_file;
            } else {
                $error_msgs = [];
                $warning_msgs = [];

                [$check_status, $check_message] = $this->checker->validate_integrity($loadable_file);
                if ($check_status == ImageUtils::IMAGE_CORRUPT) {
                    $this->n_errors += 1;
                    $error_msgs[] = $check_message;
                } elseif ($check_status == ImageUtils::IMAGE_WARNING) {
                    $this->n_warnings += 1;
                    $warning_msgs[] = $check_message;
                }

                $this->non_page_files[$loadable_file] = [
                    "error_msgs" => $error_msgs,
                    "warning_msgs" => $warning_msgs,
                ];
            }
        }

        $this->n_ops += count($this->non_page_files);

        // ---------------------------------------------------------------------

        // We're down to just the page-related files ($loadable_files).

        // Group these filenames by base.

        $this->page_file_table = [];
        $this->db_text_for_base = [];

        foreach ($page_files as $page_file) {
            [$base, $ext] = split_filename($page_file);
            if ($ext == '.txt') {
                $toi = 'text';
            } else {
                $toi = 'image';
            }
            $this->page_file_table[$base][$toi]['src'][] = $ext;
        }

        foreach ($db_entries as $db_image_file => $text) {
            [$base, $ext] = split_filename($db_image_file);
            if (array_key_exists($base, $this->page_file_table)) {
                $this->page_file_table[$base]['text']['db'][] = '.txt';
                $this->page_file_table[$base]['image']['db'][] = $ext;
                $this->db_text_for_base[$base] = $text;
            }
        }

        ksort($this->page_file_table, SORT_STRING);

        // echo "<pre>"; var_dump($this->page_file_table); echo "</pre>";

        // --------------------------------

        // Find out how long the 'image' field is.
        validate_projectID($this->projectid);
        $sql = "
            SELECT image
            FROM $this->projectid
            LIMIT 0
        ";
        $res = DPDatabase::query($sql);
        $field_data = mysqli_fetch_field_direct($res, 0);
        if (!$field_data) {
            throw new ServerError(DPDatabase::log_error());
        }
        $this->image_field_len = $field_data->length;

        // -----------

        // Now go through, looking for problems, deciding what to do.

        foreach (array_keys($this->page_file_table) as $base) {
            $row = & $this->page_file_table[$base];

            $error_msgs = [];
            $warning_msgs = [];

            foreach (['text', 'image'] as $toi) {
                $db_exts = $row[$toi]['db'] ?? [];
                $src_exts = $row[$toi]['src'] ?? [];

                [$action, $error_msg] =
                    $this->_get_action($base, $toi, $db_exts, $src_exts);

                $row[$toi]['action'] = $action;
                if ($action == 'error') {
                    $this->n_errors++;
                    array_push($error_msgs, $error_msg);
                }
            }

            // check new text for valid codepoints
            if (isset($row['text']['src'][0])) {
                $text_filename = $base . $row['text']['src'][0];
                $warnings = get_load_page_from_file_changes($text_filename, $this->projectid);
                $this->n_warnings += count($warnings);
                $warning_msgs = array_merge($warning_msgs, $warnings);
            }

            if (isset($row['image']['src'][0])) {
                $image_filename = $base . $row['image']['src'][0];
                $warning_msg = get_image_size_error(filesize($image_filename));
                if (isset($warning_msg)) {
                    $this->n_warnings += 1;
                    array_push($warning_msgs, $warning_msg);
                }

                $size = getimagesize($image_filename);
                if ($size !== false) {
                    $warning_msg = get_image_small_dimension_error($size[0], $size[1]);
                    if (isset($warning_msg)) {
                        $this->n_warnings += 1;
                        array_push($warning_msgs, $warning_msg);
                    }
                }

                [$check_status, $check_message] = $this->checker->validate_integrity($image_filename);
                if ($check_status == ImageUtils::IMAGE_CORRUPT) {
                    $this->n_errors += 1;
                    $error_msgs[] = $check_message;
                } elseif ($check_status == ImageUtils::IMAGE_WARNING) {
                    $this->n_warnings += 1;
                    $warning_msgs[] = $check_message;
                }
            }

            if ($row['text']['action'] == 'error' ||
                $row['image']['action'] == 'error') {
                // okay
            } else {
                // '', 'add', 'replace', 'same'
                $combo = $row['text']['action'] . '|' . $row['image']['action'];

                switch ($combo) {
                    case 'add|add':
                        // Add both text and image (the normal case).
                        $this->n_ops += 1;
                        $this->adding_pages = true;
                        break;

                    case 'replace|':
                    case 'replace|same':
                        // Replacing just the text.
                        // (The original image is either there or removed.)
                        $this->n_ops += 1;
                        break;

                    case '|replace':
                    case 'same|replace':
                        // Replacing just the image.
                        // (The original text is either there or removed.)
                        $this->n_ops += 1;
                        break;

                    case 'replace|replace':
                        // Replacing both text and image.
                        $this->n_ops += 2;
                        break;

                    case 'same|same':
                    case 'same|':
                    case '|same':
                        // Innocent bystander when something else is being
                        // replaced.
                        // (Original is there or removed.)
                        break;

                    case 'add|':
                        $this->n_errors++;
                        $row['image']['action'] = 'error';
                        array_push($error_msgs, _('Adding text without image'));
                        break;

                    case '|':
                        die("base=$base: combo='$combo' impossible: "
                            . "this row wouldn't exist");

                    case '|add':
                        die("base=$base: combo='$combo' impossible "
                            . "due to the way we define 'page-related'");

                    case 'add|replace':
                    case 'add|same':
                    case 'replace|add':
                    case 'same|add':
                        die("base=$base: combo='$combo' impossible: "
                            . "no db image <=> no db text");

                    default:
                        // No other combo
                        throw new UnexpectedValueException("Unexpected combo $combo");
                }
            }

            $row['error_msgs'] = $error_msgs;
            $row['warning_msgs'] = $warning_msgs;
        }
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    /**
     * Check if there is any reason to exclude the file from the load and
     * return it as a string or the empty string if not.
     */
    public function _check_file(string $filename): string
    {
        if (!is_file($filename)) {
            return _('not a regular file');
        }

        if (!is_readable($filename)) {
            return _('file is unreadable by server process');
        }

        // Note: putting \w instead of explicitly a-zA-Z0-9 may cause
        // trouble since it will allow extra characters depending on
        // the user's locale.
        if (!preg_match('/^[-.\w]+$/', $filename)) {
            return _('filename has disallowed characters');
        }

        // Filenames starting with a hyphen are excluded because
        // they may be mistaken with command line options of
        // shell utilities.
        if (preg_match('/^-/', $filename)) {
            return _('filename starts with a hyphen');
        }

        if (substr_count($filename, '.') == 0) {
            return _('filename does not contain a dot');
        }

        $ALLOWED_EXTENSIONS = ['.txt', '.png', '.jpg'];

        [$base, $ext] = split_filename($filename);
        if (!in_array($ext, $ALLOWED_EXTENSIONS)) {
            return _('filename has unrecognized suffix');
        }
        // Task 851, complain if image file is under 100 bytes
        else {
            if ($ext != ".txt" && (filesize($filename) < 100)) {
                return _('image file is small and probably bad');
            }
        }

        return '';
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    /**
     * Return the action to take for a specific base file
     *
     * This function returns an array containing 2 values:
     * - The first one is the action (either error, add, replace, same).
     * - The second one is only meaningful when action == 'error'.
     *   It is a longer string describing the actual error.
     *
     * @param string[] $db_exts
     * @param string[] $src_exts
     * @return string[]
     */
    public function _get_action(string $base, string $toi, array $db_exts, array $src_exts): array
    {
        // First, consider error conditions.

        if (count($db_exts) > 1) {
            // It would take some work to get this to occur.
            // I'm not even sure it could be done without
            // hand-tweaking the page-table.
            return [
                'error',
                ($toi == 'text')
                ? _("Multiple text in db!")
                : _("Multiple image in db!"),
            ];
        }

        if (count($src_exts) > 1) {
            // This, on the other hand, is easy.
            // e.g. src dir contains 001.txt 001.png 001.jpg
            return [
                'error',
                ($toi == 'text')
                ? _("Multiple text in source dir.")
                : _("Multiple image in source dir."),
            ];
        }

        if (count($src_exts) == 0) {
            // No file in source dir.
            // Nothing to do
            return ['', ''];
        }

        // There's a file in source dir.
        assert(count($src_exts) == 1);
        [$src_ext] = $src_exts;
        $src_file = $base . $src_ext;

        if ($toi == 'text') {
            if (!is_readable($src_file)) {
                return [
                    'error',
                    sprintf(
                        _('%s is not readable by the server process.'),
                        $src_file
                    ),
                ];
            }
        } elseif ($toi == 'image') {
            // Check that image filename will fit in db
            if (strlen($src_file) > $this->image_field_len) {
                return [
                    'error',
                    sprintf(
                        _('filename longer than %d characters'),
                        $this->image_field_len
                    ),
                ];
            }
        } else {
            throw new UnexpectedValueException("Unexpected toi $toi");
        }

        if (count($db_exts) == 0) {
            // No info in db.
            return ['add', ''];
        }

        // There's a file in the source dir *and* info in the db.
        assert(count($db_exts) == 1);
        [$db_ext] = $db_exts;
        $db_file = $base . $db_ext;

        // Check if it's the same content.
        if ($toi == 'text') {
            $same = (
                load_page_from_file($src_file, $this->projectid)
                ==
                $this->db_text_for_base[$base]
            );
        } elseif ($toi == 'image') {
            $p_file = "$this->dest_project_dir/$db_file";
            $same = (
                is_file($p_file)
                &&
                md5_file($src_file) == md5_file($p_file)
            );
        }
        if ($same) {
            // content is same
            return ['same', ''];
        } else {
            return ['replace', ''];
        }
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function has_errors(): bool
    {
        return ($this->n_errors > 0);
    }

    public function has_warnings(): bool
    {
        return ($this->n_warnings > 0);
    }

    public function would_do_nothing(): bool
    {
        return ($this->n_ops == 0);
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function display(): void
    {
        global $code_url, $projectid;
        echo "<p><b>";
        echo sprintf(
            _('Loading files from %1$s into project %2$s'),
            $this->source_project_dir,
            $this->projectid
        );
        echo "</b></p>\n";
        echo "<p>\n";
        echo _("Here's a list of the files that have been found, and what will happen to them.");
        echo "<br>\n";
        echo _("Please review the information, and use the link at the bottom of the page to proceed with the load.");
        echo "<br>\n";
        $project_url = project_page_link_url($projectid);
        echo sprintf(_("If there's a problem, return to the <a %s>project page</a> without loading the files."), "href='$project_url'");
        echo "</p>\n";

        // --------------

        echo "<h2>" . _("Ignored Files") . "</h2>\n";
        if (count($this->ignored_files) == 0) {
            echo "<i>" . pgettext("no files", "none") . "</i>";
        } else {
            echo "<table class='basic striped'>";

            echo "<tr>";
            echo "<th>" . _('Filename') . "</th>";
            echo "<th>" . _('Reason') . "</th>";
            echo "</tr>\n";

            foreach ($this->ignored_files as $ignored_file => $reason) {
                echo "<tr><td>$ignored_file</td><td>$reason</td></tr>\n";
            }
            echo "</table>\n";
        }

        // --------------

        echo "<h2>" . _("Non-page files") . "</h2>\n";
        if (count($this->non_page_files) == 0) {
            echo "<i>" . pgettext("no files", "none") . "</i>";
        } else {
            echo "<p>";
            echo "(", _("Usually these are illustrations."), ")\n";
            echo _("They will simply be copied into the project directory."),
            "</p>\n";
            echo "<table class='basic striped'>";
            echo "<tr>";
            echo "<th>", _("Image"), "</th>";
            echo "<th>", _("Warnings"), "</th>";
            echo "<th>", _("Errors"), "</th>";
            echo "</tr>";
            foreach ($this->non_page_files as $filename => $details) {
                echo "<tr>";
                echo "<td>$filename</td>";

                $warning_msgs = nl2br(implode("\n", $details['warning_msgs']));
                echo "<td>$warning_msgs</td>";

                $error_msgs = nl2br(implode("\n", $details['error_msgs']));
                echo "<td>$error_msgs</td>";

                echo "</tr>\n";
            }
            echo "</table>\n";
        }

        // --------------

        echo "<h2>", _("Page files"), "</h2>\n";
        if (count($this->page_file_table) == 0) {
            echo "<i>" . pgettext("no files", "none") . "</i>";
        } else {
            echo "<table id='addfiles' class='basic striped'>\n";
            {
                echo "<tr>";
                echo "<th rowspan='2'>", _("Base"), "</th>";
                echo "<th colspan='3'>", _("Text"), "</th>";
                echo "<th colspan='3'>", _("Image"), "</th>";
                echo "<th rowspan='2'>", _("Warnings"), "</th>";
                echo "<th rowspan='2'>", _("Errors"), "</th>";
                echo "</tr>";
            }
            {
                echo "<tr>";
                echo "<th>", _("Pre-existing"), "</th>";
                echo "<th>", _("New"), "</th>";
                echo "<th>", _("Action"), "</th>";
                echo "<th>", _("Pre-existing"), "</th>";
                echo "<th>", _("New"), "</th>";
                echo "<th>", _("Action"), "</th>";
                echo "</tr>";
            }
            foreach ($this->page_file_table as $base => $row) {
                echo "<tr>";
                echo "<td>$base</td>";

                foreach (['text', 'image'] as $toi) {
                    $db_exts = @$row[$toi]['db'];
                    $src_exts = @$row[$toi]['src'];

                    $action = $row[$toi]['action'];

                    // pre-existing
                    echo "<td class='center-align'>";
                    if ($db_exts) {
                        echo implode(' ', $db_exts);
                    }
                    echo "</td>";

                    // new
                    echo "<td class='center-align'>";
                    if ($src_exts) {
                        echo implode(' ', $src_exts);
                    }
                    echo "</td>";

                    // action
                    $class = [
                        '' => '',
                        'add' => 'load-add',
                        'replace' => 'load-replace',
                        'same' => '',
                        'error' => 'load-error',
                    ];
                    $action_labels = [
                        '' => '',
                        'add' => _('Add'),
                        'replace' => _('Replace'),
                        'same' => _('Same'),
                        'error' => _('Error'),
                    ];
                    $action_class = $class[$action];
                    echo "<td class='center-align $action_class'>";
                    echo $action_labels[$action];
                    echo "</td>";
                }
                $warning_msgs = nl2br(implode("\n", $row['warning_msgs']));
                echo "<td>$warning_msgs</td>";

                $error_msgs = nl2br(implode("\n", $row['error_msgs']));
                echo "<td>$error_msgs</td>";

                echo "</tr>\n";
            }
            echo "</table>";
        }
        if ($this->adding_pages && count($this->non_page_files) == 0) {
            // adding page files but no illos
            // (we don't want to display this warning if they are only
            // replacing files)
            echo("<p>\n");
            echo _("<b>Reminder</b>: If there are any illustration files for this project you should upload them <b>before</b> releasing the project into the rounds.");
            echo("</p>\n");
        }
        echo "<br>";
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function execute(): void
    {
        global $pguser;

        assert($this->n_errors == 0);

        // Non-page files
        foreach ($this->non_page_files as $filename => $details) {
            if (!copy($filename, "$this->dest_project_dir/$filename")) {
                $this->error("Error copying $filename");
            }
        }

        // Page files
        $now = time();

        foreach ($this->page_file_table as $base => $row) {
            $text_a = $row['text']['action'];
            $image_a = $row['image']['action'];

            $src_text_ext = @$row['text']['src'][0];
            $src_image_ext = @$row['image']['src'][0];
            $db_image_ext = @$row['image']['db'][0];

            $src_text_file_name = $base . $src_text_ext;
            $src_image_file_name = $base . $src_image_ext;
            $db_image_file_name = $base . $db_image_ext;

            $src_text_file_path = "$this->source_project_dir/$src_text_file_name";

            if ($text_a == 'add' && $image_a == 'add') {
                try {
                    project_add_page(
                        $this->projectid,
                        $base,
                        $src_image_file_name,
                        $src_text_file_path,
                        $pguser,
                        $now
                    );
                } catch (DBQueryError $error) {
                    $this->error("for base=$base, project_add_page raised a DB error");
                }

                if (!copy($src_image_file_name, "$this->dest_project_dir/$src_image_file_name")) {
                    $this->error("Error copying $src_image_file_name");
                }
            } else {
                if ($text_a == 'replace') {
                    Page_replaceText(
                        $this->projectid,
                        $db_image_file_name,
                        $src_text_file_path,
                        $pguser
                    );
                }

                if ($image_a == 'replace') {
                    if ($src_image_file_name != $db_image_file_name) {
                        // e.g., replacing 001.png with 001.jpg

                        Page_replaceImage(
                            $this->projectid,
                            $db_image_file_name,
                            $src_image_file_name,
                            $pguser
                        );

                        if (!unlink("$this->dest_project_dir/$db_image_file_name")) {
                            $this->error("Error deleting $db_image_file_name");
                        }
                    }

                    if (!copy($src_image_file_name, "$this->dest_project_dir/$src_image_file_name")) {
                        $this->error("Error copying $src_image_file_name");
                    }
                }
            }
        }
    }

    public function error(string $message): void
    {
        echo "<p class='error'>" . html_safe($message) . "</p>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/**
 * Split a filename into its base and extension.
 *
 * This function returns an array of two strings:
 *  - the part of $filename before the rightmost dot, and
 *  - the part from the rightmost dot to the end.
 *
 * @return string[]
 */
function split_filename(string $filename): array
{
    $p = strrpos($filename, '.');
    if ($p === false) {
        // No '.' in $filename.
        return [$filename, ''];
    } else {
        return [
            substr($filename, 0, $p),
            substr($filename, $p),
        ];
    }
}
