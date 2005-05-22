<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include_once($relPath.'misc.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include($relPath.'project_edit.inc');
include($relPath.'page_ops.inc');

$projectid = $_GET['project'];

abort_if_cant_edit_project( $projectid );

$loading_tpnv = ( isset($_GET['tpnv']) && $_GET['tpnv'] == '1' );

if ( $_GET['rel_source'] == '' )
{
    die('rel_source parameter is empty or unset');
}
else
{
    $rel_source = stripslashes($_GET['rel_source']);
    // Prevent sneaky parent-link tricks.
    if ( ereg( '\.\.', $rel_source ) )
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

    exec("unzip -o -j $abs_source -d $source_project_dir");

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
exec("chmod -R a+r $source_project_dir");


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
    system("cp *.png $dest_project_dir");
    system("cp *.jpg $dest_project_dir");
    echo "</pre>\n";

    $result = mysql_query("UPDATE projects SET state = 'project_new_waiting_app' WHERE projectid = '$projectid'");
}
else
{
    $loader = new Loader( $source_project_dir, $dest_project_dir, $projectid );
    $loader->analyze();

    if ( !@$_GET['confirmed'] )
    {
        $loader->display();

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
    }
    else
    {
        if ( $loader->has_errors() )
        {
            $loader->display();
            echo _("Please fix errors and try again.");
        }
        elseif ( $loader->would_do_nothing() )
        {
            $loader->display();
            echo _("Nothing to do.");
        }
        else
        {
            $loader->execute();
            echo _("Done.");
        }
    }
}

echo "<hr>\n";
echo "Return to <a href='$code_url/project.php?id=$projectid&verbosity=4'>Project Page</a>.\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class Loader
{
    function Loader( $source_project_dir, $dest_project_dir, $projectid )
    {
        $this->source_project_dir = $source_project_dir;
        $this->dest_project_dir = $dest_project_dir;
        $this->projectid = $projectid;
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function analyze()
    {
        $this->n_errors = 0;
        $this->n_ops = 0;

        // Get the set of all files in the source (current) directory.
        $source_files = glob('*');

        // Get the set of all 'image' fields in the page-table.
        $db_entries = array();
        $res = mysql_query("SELECT image,master_text FROM $this->projectid");
        while( list($image,$text) = mysql_fetch_row($res) )
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
        $res = mysql_query("
            SELECT image
            FROM $this->projectid
            LIMIT 0
        ") or die(mysql_error());
        $this->image_field_len = mysql_field_len($res,0);

        // -----------

        // Now go through, looking for problems, deciding what to do.

        foreach ( array_keys($this->page_file_table) as $base )
        {
            $row =& $this->page_file_table[$base];

            $error_msgs = '';

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

        if ( !preg_match('/^[-.\w]+$/', $filename ) )
        {
            return _('filename has disallowed characters');
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

        return '';
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function _get_action( $base, $toi, $db_exts, $src_exts )
    {
        $action = null;

        // First, consider error conditions.

        if ( count($db_exts) > 1 )
        {
            // It would take some work to get this to occur.
            // I'm not even sure it could be done without
            // hand-tweaking the page-table.
            return array(
                'error',
                "Multiple $toi in db!"
            );
        }

        if ( count($src_exts) > 1 )
        {
            // This, on the other hand, is easy.
            // e.g. src dir contains 001.txt 001.png 001.jpg
            return array(
                'error',
                "Multiple $toi in source dir."
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
            // File must be world-readable, or
            // MySQL's LOAD_FILE() will return NULL.

            $perms = fileperms($src_file);
            if ( $perms === FALSE )
            {
                return array(
                    'error',
                    sprintf(
                        _('Unable to get permissions on %s.'),
                        $src_file
                    )
                );
            }

            if ( ($perms & 0x0004) == 0 )
            {
                return array(
                    'error',
                    sprintf(
                        _('%s is not world-readable.'),
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
                file_get_contents($src_file)
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
                shell_exec( "cmp $src_file $p_file" ) == ''
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

    function would_do_nothing()
    {
        return ( $this->n_ops == 0 );
    }

    // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    function display()
    {
        echo "<h2>";
        echo sprintf(
            _('Loading files from %s into project %s'),
            $this->source_project_dir,
            $this->projectid
        );
        echo "</h2>\n";

        // --------------

        echo "<h3>Ignored Files</h3>\n";
        if ( count($this->ignored_files) == 0 )
        {
            echo "none";
        }
        else
        {
            echo "<table border='1'>";

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

        echo "<h3>Non-page files</h3>\n";
        if ( count($this->non_page_files) == 0 )
        {
            echo "none";
        }
        else
        {
            echo "(Usually these are illustrations.)\n";
            echo "They will simply be copied into the project directory.<br>\n";
            echo "<table border='1'>";
            foreach ( $this->non_page_files as $filename )
            {
                echo "<tr><td>$filename</td></tr>\n";
            }
            echo "</table>\n";
        }

        // --------------

        echo "<h3>Page files</h3>\n";
        if ( count($this->page_file_table) == 0 )
        {
            echo "none";
        }
        else
        {
            echo "<table border='1'>\n";
            {
                echo "<tr>";
                echo "<th rowspan='2'>base</th>";
                echo "<th colspan='3'>text</th>";
                echo "<th colspan='3'>image</th>";
                echo "<th rowspan='2'>error msgs</th>";
                echo "</tr>";
            }
            {
                echo "<tr>";
                echo "<th>pre-existing</th>";
                echo "<th>new</th>";
                echo "<th>action</th>";
                echo "<th>pre-existing</th>";
                echo "<th>new</th>";
                echo "<th>action</th>";
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
                    echo "<td align='center'>";
                    if ( $db_exts ) echo implode( ' ', $db_exts );
                    echo "</td>";

                    // new
                    echo "<td align='center'>";
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
                    $action_bgcolor = $bgcolors[$action];
                    echo "<td align='center' bgcolor='$action_bgcolor'>";
                    echo $action;
                    echo "</td>";
                }

                $error_msgs = $row['error_msgs'];
                echo "<td>$error_msgs</td>";

                echo "</tr>\n";
            }
            echo "</table>";
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
            $this->_do_command( "cp $filename $this->dest_project_dir" );
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

                $this->_do_command(
                    "cp $src_image_file_name $this->dest_project_dir" );
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

                        $this->_do_command(
                            "rm $this->dest_project_dir/$db_image_file_name" );
                    }

                    $this->_do_command(
                        "cp $src_image_file_name $this->dest_project_dir" );
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
?>
