<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

require_login();

$extra_args = [
    'css_data' => '
        .mono {
            font-family: ' . get_proofreading_font_family_fallback() . ';
        }
    ',
];

$title = _("Add files");
output_header($title, NO_STATSBAR, $extra_args);

$projectid    = validate_projectID('project', @$_GET['project']);
$loading_tpnv = (@$_GET['tpnv'] == '1');

abort_if_cant_edit_project( $projectid );

echo "<h1>$title</h1>";

if(!user_can_add_project_pages($projectid, $loading_tpnv == 1 ? "tp&v" : "normal"))
{
    // abort if a load_disabled user is trying to load normal pages into an empty project 
    check_user_can_load_projects(true); // exit if they can't

    // otherwise the state must have been wrong
    echo  "<p>"
        . _("Pages cannot be added to the project in its current state.")
        . "</p>";
    exit();
}

if ( $_GET['rel_source'] == '' )
{
    die('rel_source parameter is empty or unset');
}
else
{
    $rel_source = $_GET['rel_source'];
    // Prevent sneaky parent-link tricks.
    if (str_contains($rel_source, ".."))
    {
        echo "Source directory '$rel_source' is not acceptable.";
        echo "<hr>\n";
        echo "Return to <a href='$code_url/project.php?id=$projectid'>Project Page</a>.\n";
        return;
    }
}

$abs_source = "$uploads_dir/$rel_source";

if ( !file_exists($abs_source) )
{
    die( "source does not exist: $abs_source" );
}

if ( substr($abs_source, -4) == ".zip" )
{
    // $abs_source is a zip file containing the project files

    // We will unpack it to a sibling directory.
    $source_project_dir = substr($abs_source, 0, -4);
    if (!file_exists($source_project_dir))
    {
        mkdir($source_project_dir, 0777);
        chmod($source_project_dir, 0777);
    }

    extract_zip_to($abs_source, $source_project_dir);
    flatten_directory($source_project_dir);

    // (Assuming the unzip worked), remove the zip file.
    unlink($abs_source);

    $rel_source = substr($rel_source, 0, -4);
}
else
{
    $source_project_dir = $abs_source;
}

// Attempt to make everything all-readable.
// (This will probably only succeed if we have just upacked
// a zip file [above], but no harm in trying in all cases.)
exec("chmod -R a+r " . escapeshellarg($source_project_dir));


//if they are uploading tpnv files then put them in /tpnv 
if ( $loading_tpnv )
{
    $dest_project_dir = "$projects_dir/$projectid/tpnv";
    if (!file_exists($dest_project_dir)) { 
        mkdir("$dest_project_dir", 0777);
        chmod("$dest_project_dir", 0777);
    }
}
else
{
    $dest_project_dir   = "$projects_dir/$projectid";
}


// Rather than performing commands from an arbitrary location,
// using absolute paths, e.g.
//     system("cp $source_project_dir/*.png $dest_project_dir");
//     glob("$source_project_dir/*.txt"),
// we chdir into $source_project_dir and do *local* commands from there.
// That way, we don't have to worry about any shell-special or
// glob-special characters in $source_project_dir.
// (There don't appear to be any chdir-special characters.)
$r = chdir($source_project_dir);
if ( !$r )
{
    echo "Directory '$source_project_dir' does not exist, or is inaccessible.\n";
    echo "<hr>\n";
    echo "Return to <a href='$code_url/project.php?id=$projectid'>Project Page</a>.\n";
    return;
}



if ( $loading_tpnv )
{
    echo "<pre>\n";
    echo "copying page-images from\n";
    echo "    $source_project_dir\n";
    echo "to\n";
    echo "    $dest_project_dir\n";
    // NOTE about file names: since here we are not doing any check over the
    // file names, it is possible to find filenames with a space or other
    // strange characters in the /tpnv directory.
    system("cp *.png " . escapeshellarg($dest_project_dir));
    system("cp *.jpg " . escapeshellarg($dest_project_dir));
    echo "</pre>\n";

    $result = mysqli_query(DPDatabase::get_connection(), "UPDATE projects SET state = 'project_new_waiting_app' WHERE projectid = '$projectid'");
}
else
{
    $loader = new Loader( $source_project_dir, $dest_project_dir, $projectid );
    $loader->analyze();

    if ( !@$_GET['confirmed'] )
    {
        $loader->display();

        if ( $loader->has_warnings() )
        {
            echo "<p class='warning'>";
            echo _("Some warnings issued, please check them before continuing.");
            echo "</p>";
        }

        echo "<p>";
        if ( $loader->has_errors() )
        {
            echo _("Please fix errors and try again.");
        }
        elseif ( $loader->would_do_nothing() )
        {
            echo _("Nothing to do.");
        }
        else
        {
            $url = "?project=$projectid&amp;rel_source=$rel_source&amp;confirmed=1";
            echo "<a href='$url'>" . _('Proceed with load') . "</a>";
        }
        echo "</p>";
    }
    else
    {
        if ( $loader->has_errors() )
        {
            $loader->display();
            echo "<p>";
            echo _("Please fix errors and try again.");
            echo "</p>";
        }
        elseif ( $loader->would_do_nothing() )
        {
            $loader->display();
            echo "<p>";
            echo _("Nothing to do.");
            echo "</p>";
        }
        else
        {
            $loader->execute();
            echo "<p>";
            echo _("Done.");
            echo "</p>";
        }
    }
}

echo "<hr>\n";
echo "Return to <a href='$code_url/project.php?id=$projectid&detail_level=4'>Project Page</a>.\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class Loader
{
    function __construct( $source_project_dir, $dest_project_dir, $projectid )
    {
        $this->source_project_dir = $source_project_dir;
        $this->dest_project_dir = $dest_project_dir;
        $this->projectid = $projectid;
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function analyze()
    {
        $this->n_errors = 0;
        $this->n_warnings = 0;
        $this->n_ops = 0;
        $this->adding_pages = FALSE; // is this load adding any pages to the project?

        // Get the set of all files in the source (current) directory.
        $source_files = glob('*');

        // Get the set of all 'image' fields in the page-table.
        $db_entries = array();
        $res = mysqli_query(DPDatabase::get_connection(), "SELECT image,master_text FROM $this->projectid");
        while( list($image,$text) = mysqli_fetch_row($res) )
        {
            $db_entries[$image] = $text;
        }

        // ---------------------------------------------------------------------

        // First, divide the source files into those that can be loaded
        // and those that will be ignored.

        $this->ignored_files = array();
        $loadable_files = array();
        foreach ( $source_files as $source_file )
        {
            $reason_to_ignore = $this->_check_file( $source_file );
            if ( $reason_to_ignore )
            {
                $this->ignored_files[$source_file] = $reason_to_ignore;
            }
            else
            {
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
        $page_related_bases = array();

        foreach ( $loadable_files as $source_file )
        {
            list($base,$ext) = split_filename($source_file);
            if ( $ext == '.txt' )
            {
                $page_related_bases[$base] = 1;
            }
        }

        foreach ( array_keys($db_entries) as $db_image_file )
        {
            list($base,$ext) = split_filename($db_image_file);
            $page_related_bases[$base] = 1;
        }

        ksort($page_related_bases, SORT_STRING);

        // echo "<pre>"; var_dump($page_related_bases); echo "</pre>";

        // -----------------------------

        // Okay, now we can separate page-related files from non-.

        $page_files = array();
        $this->non_page_files = array();
        foreach ( $loadable_files as $loadable_file )
        {
            list($base,$ext) = split_filename($loadable_file);
            if ( array_key_exists( $base, $page_related_bases ) )
            {
                $page_files[] = $loadable_file;
            }
            else
            {
                $this->non_page_files[] = $loadable_file;
            }
        }

        $this->n_ops += count($this->non_page_files);

        // ---------------------------------------------------------------------

        // We're down to just the page-related files ($loadable_files).

        // Group these filenames by base.

        $this->page_file_table = array();
        $this->db_text_for_base = array();

        foreach ( $page_files as $page_file )
        {
            list($base,$ext) = split_filename($page_file);
            if ( $ext == '.txt' )
            {
                $toi = 'text';
            }
            else
            {
                $toi = 'image';
            }
            $this->page_file_table[$base][$toi]['src'][] = $ext;
        }

        foreach ( $db_entries as $db_image_file => $text )
        {
            list($base,$ext) = split_filename($db_image_file);
            if ( array_key_exists( $base, $this->page_file_table ) )
            {
                $this->page_file_table[$base]['text']['db'][] = '.txt';
                $this->page_file_table[$base]['image']['db'][] = $ext;
                $this->db_text_for_base[$base] = $text;
            }
        }

        ksort($this->page_file_table, SORT_STRING);

        // echo "<pre>"; var_dump($this->page_file_table); echo "</pre>";

        // --------------------------------

        // Find out how long the 'image' field is.
        $res = mysqli_query(DPDatabase::get_connection(), "
            SELECT image
            FROM $this->projectid
            LIMIT 0
        ") or die(mysqli_error(DPDatabase::get_connection()));
        $field_data = mysqli_fetch_field_direct($res, 0);
        $this->image_field_len = $field_data->length;

        // -----------

        // Now go through, looking for problems, deciding what to do.

        foreach ( array_keys($this->page_file_table) as $base )
        {
            $row =& $this->page_file_table[$base];

            $error_msgs = '';
            $warning_msgs = '';

            foreach ( array('text','image') as $toi )
            {
                $db_exts = @$row[$toi]['db'];
                $src_exts = @$row[$toi]['src'];

                list($action, $error_msg) =
                    $this->_get_action( $base, $toi, $db_exts, $src_exts );

                $row[$toi]['action'] = $action;
                if ( $action == 'error' )
                {
                    $this->n_errors++;
                    $error_msgs .= "$error_msg\n";
                }
            }

            // check new text for valid codepoints
            if( isset($row['text']['src'][0]) )
            {
                $text_filename = $base . $row['text']['src'][0];
                $warnings = get_load_page_from_file_changes($text_filename, $this->projectid);
                $this->n_warnings += count($warnings);
                $warning_msgs .= implode("\n", $warnings);
            }

            if ($row['text']['action'] == 'error' ||
                $row['image']['action'] == 'error' )
            {
                // okay
            }
            else
            {
                // '', 'add', 'replace', 'same'
                $combo = $row['text']['action'] . '|' . $row['image']['action'];

                switch ( $combo )
                {
                    case 'add|add':
                        // Add both text and image (the normal case).
                        $this->n_ops += 1;
                        $this->adding_pages = TRUE;
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
                        $error_msgs .= _('Adding text without image') . "\n";
                        break;

                    case '|':
                        die( "base=$base: combo='$combo' impossible: "
                            . "this row wouldn't exist" );

                    case '|add':
                        die( "base=$base: combo='$combo' impossible "
                            . "due to the way we define 'page-related'" );

                    case 'add|replace':
                    case 'add|same':
                    case 'replace|add':
                    case 'same|add':
                        die( "base=$base: combo='$combo' impossible: "
                            . "no db image <=> no db text" );

                    default:
                        // No other combo
                        assert( 0 );
                }
            }

            $row['error_msgs'] = $error_msgs;
            $row['warning_msgs'] = $warning_msgs;
        }

    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function _check_file( $filename )
    // If there is any reason to exclude the file from the load,
    // return it as a string.
    // Otherwise, return empty string.
    {
        if ( !is_file($filename) )
        {
            return _('not a regular file');
        }

        if ( !is_readable($filename) )
        {
            return _('file is unreadable by server process');
        }

        // Note: putting \w instead of explicitly a-zA-Z0-9 may cause
        // trouble since it will allow extra characters depending on 
        // the user's locale.
        if ( !preg_match('/^[-.\w]+$/', $filename ) )
        {
            return _('filename has disallowed characters');
        }

        // Filenames starting with a hyphen are excluded because
        // they may be mistaken with command line options of
        // shell utilities such as cmp (in this file, below) and zip
        // (when generating zips to download image files) 
        if ( preg_match('/^-/', $filename ) )
        {
            return _('filename starts with a hyphen');
        }

        if ( substr_count( $filename, '.' ) == 0 )
        {
            return _('filename does not contain a dot');
        }

        $ALLOWED_EXTENSIONS = array( '.txt', '.png', '.jpg' );

        list($base,$ext) = split_filename($filename);
        if ( !in_array( $ext, $ALLOWED_EXTENSIONS ) )
        {
            return _('filename has unrecognized suffix');
        }
        // Task 851, complain if image file is under 100 bytes
        else
        {
            if ( $ext != ".txt" && (filesize($filename) < 100) )
            {
                return _('image file is small and probably bad');
            }
        }

        return '';
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function _get_action( $base, $toi, $db_exts, $src_exts )
    {
        // First, consider error conditions.

        if ( count($db_exts) > 1 )
        {
            // It would take some work to get this to occur.
            // I'm not even sure it could be done without
            // hand-tweaking the page-table.
            return array(
                'error',
                ($toi == 'text')
                ? _("Multiple text in db!")
                : _("Multiple image in db!")
            );
        }

        if ( count($src_exts) > 1 )
        {
            // This, on the other hand, is easy.
            // e.g. src dir contains 001.txt 001.png 001.jpg
            return array(
                'error',
                ($toi == 'text')
                ? _("Multiple text in source dir.")
                : _("Multiple image in source dir.")
            );
        }

        if ( count($src_exts) == 0 )
        {
            // No file in source dir.
            // Nothing to do
            return array( '', '' );
        }

        // There's a file in source dir.
        assert( count($src_exts) == 1 );
        list($src_ext) = $src_exts;
        $src_file = $base . $src_ext;

        if ( $toi == 'text' )
        {
            if ( !is_readable($src_file) )
            {
                return array(
                    'error',
                    sprintf(
                        _('%s is not readable by the server process.'),
                        $src_file
                    )
                );
            }
        }
        elseif ( $toi == 'image' )
        {
            // Check that image filename will fit in db
            if ( strlen($src_file) > $this->image_field_len )
            {
                return array(
                    'error',
                    sprintf(
                        _('filename longer than %d characters'),
                        $this->image_field_len
                    )
                );
            }
        }
        else
        {
            assert( FALSE );
        }

        if ( count($db_exts) == 0 )
        {
            // No info in db.
            return array( 'add', '' );
        }

        // There's a file in the source dir *and* info in the db.
        assert( count($db_exts) == 1 );
        list($db_ext) = $db_exts;
        $db_file = $base . $db_ext;

        // Check if it's the same content.
        if ( $toi == 'text' )
        {
            $same = (
                load_page_from_file($src_file, $this->projectid)
                ==
                $this->db_text_for_base[$base]
            );
        }
        elseif ( $toi == 'image' )
        {
            $p_file = "$this->dest_project_dir/$db_file";
            $same = (
                is_file($p_file)
                &&
                shell_exec( "cmp " . escapeshellarg($src_file) 
                    . " " . escapeshellarg($p_file) ) == ''
            );
        }
        if ( $same )
        {
            // content is same
            return array( 'same', '' );
        }
        else
        {
            return array( 'replace', '' );
        }
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function has_errors()
    {
        return ( $this->n_errors > 0 );
    }

    function has_warnings()
    {
        return ( $this->n_warnings > 0 );
    }

    function would_do_nothing()
    {
        return ( $this->n_ops == 0 );
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function display()
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
        echo _("If there's a problem, return to the  <a href='$code_url/project.php?id=$projectid&detail_level=4'>Project Page</a> without loading the files.");
        echo "</p>\n";

        // --------------

        echo "<h2>" . _("Ignored Files") . "</h2>\n";
        if ( count($this->ignored_files) == 0 )
        {
            echo "<i>" . pgettext("no files", "none") . "</i>";
        }
        else
        {
            echo "<table class='basic striped'>";

            echo "<tr>";
            echo "<th>" . _('Filename') . "</th>";
            echo "<th>" . _('Reason') . "</th>";
            echo "</tr>\n";

            foreach ( $this->ignored_files as $ignored_file => $reason )
            {
                echo "<tr><td>$ignored_file</td><td>$reason</td></tr>\n";
            }
            echo "</table>\n";
        }

        // --------------

        echo "<h2>" . _("Non-page files") . "</h2>\n";
        if ( count($this->non_page_files) == 0 )
        {
            echo "<i>" . pgettext("no files", "none") . "</i>";
        }
        else
        {
            echo "<p>";
            echo "(", _("Usually these are illustrations."), ")\n";
            echo _("They will simply be copied into the project directory."), 
                "</p>\n";
            echo "<table class='basic striped'>";
            foreach ( $this->non_page_files as $filename )
            {
                echo "<tr><td>$filename</td></tr>\n";
            }
            echo "</table>\n";
        }

        // --------------

        echo "<h2>", _("Page files"), "</h2>\n";
        if ( count($this->page_file_table) == 0 )
        {
            echo "<i>" . pgettext("no files", "none") . "</i>";
        }
        else
        {
            echo "<table class='basic striped'>\n";
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
            foreach ( $this->page_file_table as $base => $row )
            {
                echo "<tr>";
                echo "<td>$base</td>";

                foreach ( array('text','image') as $toi )
                {
                    $db_exts = @$row[$toi]['db'];
                    $src_exts = @$row[$toi]['src'];

                    $action = $row[$toi]['action'];

                    // pre-existing
                    echo "<td class='center-align'>";
                    if ( $db_exts ) echo implode( ' ', $db_exts );
                    echo "</td>";

                    // new
                    echo "<td class='center-align'>";
                    if ( $src_exts ) echo implode( ' ', $src_exts );
                    echo "</td>";

                    // action
                    $bgcolors = array(
                        ''        => '#ffffff',
                        'add'     => '#ccffcc',
                        'replace' => '#ffccaa',
                        'same'    => '#ffffff',
                        'error'   => '#ffcccc',
                    );
                    $action_labels = array(
                        ''        => '',
                        'add'     => _('Add'),
                        'replace' => _('Replace'),
                        'same'    => _('Same'),
                        'error'   => _('Error'),
                    );
                    $action_bgcolor = $bgcolors[$action];
                    echo "<td class='center-align' style='background-color: $action_bgcolor'>";
                    echo $action_labels[$action];
                    echo "</td>";
                }

                $warning_msgs = nl2br($row['warning_msgs']);
                echo "<td>$warning_msgs</td>";

                $error_msgs = nl2br($row['error_msgs']);
                echo "<td>$error_msgs</td>";

                echo "</tr>\n";
            }
            echo "</table>";
        }
        if ( $this->adding_pages &&  count($this->non_page_files) == 0)
        {
            // adding page files but no illos
            // (we don't want to display this warning if they are only
            // replacing files)
            echo ("<p>\n");
            echo _("<b>Reminder</b>: If there are any illustration files for this project you should upload them <b>before</b> releasing the project into the rounds.");
            echo ("</p>\n");
        }
        echo "<br>";
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function execute()
    {
        global $pguser;

        assert( $this->n_errors == 0 );

        $this->dry_run = FALSE;

        // Non-page files
        foreach ( $this->non_page_files as $filename )
        {
            $this->_do_command( sprintf("cp %s %s", 
                escapeshellarg($filename), 
                escapeshellarg($this->dest_project_dir)) );
        }

        // Page files
        $now = time();

        foreach ( $this->page_file_table as $base => $row )
        {
            $text_a  = $row['text']['action'];
            $image_a = $row['image']['action'];

            $src_text_ext  = @$row['text']['src'][0];
            $src_image_ext = @$row['image']['src'][0];
            $db_image_ext  = @$row['image']['db'][0];

            $src_text_file_name  = $base . $src_text_ext;
            $src_image_file_name = $base . $src_image_ext;
            $db_image_file_name  = $base . $db_image_ext;

            $src_text_file_path = "$this->source_project_dir/$src_text_file_name";

            if ( $text_a == 'add' && $image_a == 'add' )
            {
                if ( $this->dry_run )
                {
                    echo "
                        project_add_page(
                            $this->projectid,
                            $base,
                            $src_image_file_name,
                            $src_text_file_path,
                            $pguser,
                            $now );<br>
                    ";
                }
                else
                {
                    $errs = project_add_page(
                        $this->projectid,
                        $base,
                        $src_image_file_name,
                        $src_text_file_path,
                        $pguser,
                        $now );
                    if ( $errs )
                    {
                        echo "for base=$base, project_add_page raised error:<br>";
                        echo "$errs<br>\n";
                    }
                }

                $this->_do_command( sprintf("cp %s %s", 
                    escapeshellarg($src_image_file_name), 
                    escapeshellarg($this->dest_project_dir)) );
            }
            else
            {
                if ( $text_a == 'replace' )
                {
                    if ( $this->dry_run )
                    {
                        echo "
                            Page_replaceText(
                                $this->projectid,
                                $db_image_file_name,
                                $src_text_file_path,
                                $pguser );
                        ";
                    }
                    else
                    {
                        Page_replaceText(
                            $this->projectid,
                            $db_image_file_name,
                            $src_text_file_path,
                            $pguser );
                    }
                }

                if ( $image_a == 'replace' )
                {
                    if ( $src_image_file_name != $db_image_file_name )
                    {
                        // e.g., replacing 001.png with 001.jpg

                        if ( $this->dry_run )
                        {
                            echo "
                                Page_replaceImage(
                                    $this->projectid,
                                    $db_image_file_name,
                                    $src_image_file_name,
                                    $pguser );
                            ";
                        }
                        else
                        {
                            Page_replaceImage(
                                $this->projectid,
                                $db_image_file_name,
                                $src_image_file_name,
                                $pguser );
                        }

                        $this->_do_command( "rm " . escapeshellarg(
                            "$this->dest_project_dir/$db_image_file_name") );
                    }

                    $this->_do_command( sprintf("cp %s %s", 
                        escapeshellarg($src_image_file_name), 
                        escapeshellarg($this->dest_project_dir)) );
                }
            }
        }
    }

    function _do_command( $cmd )
    {
        if ( $this->dry_run )
        {
            echo "$cmd<br>";
        }
        else
        {
            system($cmd, $exit_status);
            if ( $exit_status != 0 )
            {
                echo "$cmd:<br>";
                echo "exit status was $exit_status<br>";
            }
        }
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function split_filename( $filename )
// Return an array of two strings:
// -- the part of $filename before the rightmost dot, and
// -- the part from the rightmost dot to the end.
{
    $p = strrpos($filename,'.');
    if ( $p === FALSE )
    {
        // No '.' in $filename.
        return array( $filename, '' );
    }
    else
    {
        return array(
            substr( $filename, 0, $p ),
            substr( $filename, $p )
        );
    }
}

// vim: sw=4 ts=4 expandtab
