<?PHP
$relPath='../../pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}

echo "<pre>";
echo "<h2>Rename Pages</h2>";

$projectid = array_get( $_GET, 'projectid', '' );

if ( empty($projectid) )
{
    $projectid = array_get( $_POST, 'projectid', '' );
    if ( empty($projectid) )
    {
        echo "<form method='GET'>";
        echo "Please specify a project: ";
        echo "<input type='text' name='projectid' size='23'> ";
        echo "<input type='submit' value='Go'>";
        echo "</form>";
        exit;
    }
}

$res = mysql_query("
    SELECT nameofwork
    FROM projects
    WHERE projectid='$projectid'
") or die(mysql_error());

if ( mysql_numrows($res) == 0 )
{
    die( "No project matches projectid='$projectid'." );
}
else if ( mysql_numrows($res) > 1 )
{
    die( "More than 1 project matched projectid='$projectid'. Can't happen!" );
}

list($title) = mysql_fetch_row( $res );

echo "projectid: $projectid\n";
echo "title    : $title\n";
echo "\n";

$res = mysql_query("
    SELECT fileid, image
    FROM $projectid
    ORDER BY image
") or die(mysql_error());

$current_image_for_fileid_ = array();
while ( list($fileid,$image) = mysql_fetch_row($res) )
{
    $current_image_for_fileid_[$fileid] = $image;
}

// -----------

$submit_button = array_get( $_POST, 'submit_button', '' );

switch ( $submit_button )
{
    case '':

        echo "<form method='post'>";
        echo "<input type='hidden' name='projectid' value='$projectid'>";

        echo "<hr>";
        echo "If you just want to renumber the pages from 001\n";
        echo "(while maintaining their current order), click here:\n";
        echo "<input type='checkbox' name='renumber_from_001'>Renumber from 001";

        echo "<hr>";
        echo "Otherwise, for each page that you wish to rename, type in the new fileid.\n";
        echo "('.png' will automatically be appended to obtain the new image name.)\n";
        echo "Leave a box blank if you don't want to rename that page.\n";
        echo "\n";

        echo "<table>";

        echo "<tr>";
        echo "<th>fileid</th>";
        echo "<th>image</th>";
        echo "<th>new fileid<br>(image base)</th>";
        echo "</tr>";

        foreach ( $current_image_for_fileid_ as $fileid => $image )
        {
            echo "<tr>";
            echo "<td>$fileid</td>";
            echo "<td>$image</td>";
            echo "<td><input type='text' size='4' name='new_fileid_for_[$fileid]'><td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<hr>";
        echo "\n";
        echo "<input type='submit' name='submit_button' value='Check renamings'>";

        echo "</form>\n";
        break;

    case 'Check renamings':

        $renumber_from_001 = array_get( $_POST, 'renumber_from_001', 'off' );

        if ( $renumber_from_001 == 'on' )
        {
            // Ignore $_POST['new_fileid_for_']

            $new_fileid_for_ = array();
            $i = 1;
            foreach ( $current_image_for_fileid_ as $fileid => $image )
            {
                $new_fileid = sprintf( '%03d', $i );
                $new_fileid_for_[$fileid] = $new_fileid;
                $i++;
            }
        }
        else
        {
            $new_fileid_for_ = array_get( $_POST, 'new_fileid_for_', NULL );

            foreach ( $new_fileid_for_ as $old_fileid => $new_fileid )
            {
                if ( empty($new_fileid) )
                {
                    $new_fileid_for_[$old_fileid] = $old_fileid;
                }
            }
        }

        echo "You requested:\n";
        echo "<table>";

        echo "<tr>";
        echo "<th colspan='2'>old</th>";
        echo "<th>-></th>";
        echo "<th colspan='2'>new</th>";
        echo "</tr>";

        echo "<tr>";
        echo "<th>fileid</th>";
        echo "<th>image</th>";
        echo "<th>|</th>";
        echo "<th>fileid</th>";
        echo "<th>image</th>";
        echo "</tr>";

        foreach ( $current_image_for_fileid_ as $old_fileid => $old_image )
        {
            $new_fileid = $new_fileid_for_[$old_fileid];
            $new_image = "$new_fileid.png";

            echo "<tr>";
            echo "<td>$old_fileid</td>";
            echo "<td>$old_image</td>";
            if ( $new_fileid == $old_fileid && $new_image == $old_image )
            {
                echo "<td>==</td>";
            }
            else
            {
                echo "<td>-></td>";
            }
            echo "<td>$new_fileid</td>";
            echo "<td>$new_image</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "\n";

        // ------------

        $n_errors = 0;

        foreach( array_count_values( $new_fileid_for_ ) as $new_fileid => $freq )
        {
            if ( $new_fileid != '' && $freq > 1 )
            {
                echo "Error: You have requested $new_fileid as the new fileid for $freq different pages.\n";
                echo "\n";
                $n_errors++;
            }
        }

        if ( $n_errors > 0 )
        {
            echo "\n";
            die( "Hit 'Back' and fix." );
        }

        // ------------

        // Simulate the renamings to check that they won't violate the
        // uniqueness constraint on each of the fileid and image columns.
        // Try it both backward and forward.

        $direction_that_works = NULL;

        foreach ( array( 'forward', 'backward' ) as $direction )
        {
            echo "<hr>";
            echo "Considering doing the renamings $direction ...\n";
            echo "\n";

            $max_n_failed_steps_to_show = 3;
            $n_failed_steps = 0;

            $sim = $current_image_for_fileid_; // copies it

            $olds = (
                $direction == 'forward' ?
                $current_image_for_fileid_ :
                array_reverse($current_image_for_fileid_, TRUE)
            );

            $i = 0;
            foreach ( $olds as $old_fileid => $old_image )
            {
                $i++;

                $new_fileid = $new_fileid_for_[$old_fileid];
                $new_image = "$new_fileid.png";

                unset( $sim[$old_fileid] );

                $reasons = array();
                if ( array_key_exists($new_fileid, $sim) )
                {
                    $reasons[] = "a row with fileid='$new_fileid' will already exist";
                }

                if ( in_array($new_image, $sim) )
                {
                    $reasons[] = "a row with image='$new_image' will already exist";
                }

                if ( count($reasons) > 0 )
                {
                    $n_failed_steps++;
                    if ($n_failed_steps <= $max_n_failed_steps_to_show)
                    {
                        echo "Renamings will fail at step #$i:\n";
                        echo "    ($old_fileid,$old_image) -> ($new_fileid,$new_image)\n";
                        echo "because:\n";
                        foreach ( $reasons as $reason )
                        {
                            echo "    $reason\n";
                        }
                        echo "\n";
                    }
                }
                
                $sim[$new_fileid] = $new_image;
            }

            if ($n_failed_steps == 0)
            {
                echo "Okay, it looks like $direction will work.\n";
                $direction_that_works = $direction;
                break;
            }
            else
            {
                if ($n_failed_steps > $max_n_failed_steps_to_show)
                {
                    $n_more = $n_failed_steps - $max_n_failed_steps_to_show;
                    echo "and $n_more more such failures.\n";
                    echo "\n";
                }

                echo "So $direction won't work.\n";
            }
        }

        if ( is_null($direction_that_works) )
        {
            die( "Neither forward nor backward works." );
        }

        // ------------

        echo "<hr>";
        echo "If that's not what you want, hit 'Back' and fix. Otherwise...\n";
        echo "<form method='post'>";
        echo "<input type='hidden' name='projectid' value='$projectid'>";
        echo "<input type='hidden' name='direction' value='$direction_that_works'>";
        echo "<input type='submit' name='submit_button' value='Do renamings'>";
        foreach ( $new_fileid_for_ as $old_fileid => $new_fileid )
        {
            echo "<input type='hidden' name='new_fileid_for_[$old_fileid]' value='$new_fileid'>";
        }
        echo "</form>";
        break;

    case 'Do renamings':
        $for_real = 1;

        $new_fileid_for_ = array_get( $_POST, 'new_fileid_for_', '' );
        $direction = array_get( $_POST, 'direction', '' );

        if ( empty($new_fileid_for_) )
        {
            die( "new_fileid_for_ param is empty" );
        }

        if ( empty($direction) )
        {
            die( "direction param is empty" );
        }
        else if ( $direction != 'forward' && $direction != 'backward' )
        {
            die( "direction param is '$direction'" );
        }

        echo "Doing renamings $direction ...\n\n";

        // cd to project dir to simplify filesystem moves
        $project_dir = "$projects_dir/$projectid";
        echo "cd $project_dir\n";
        if ( ! chdir( $project_dir ) )
        {
            die( "Unable to 'cd $project_dir'" );
        }
        echo "\n";

        $olds = (
            $direction == 'forward' ?
            $current_image_for_fileid_ :
            array_reverse($current_image_for_fileid_, TRUE)
        );

        foreach ( $olds as $old_fileid => $old_image )
        {
            $new_fileid = $new_fileid_for_[$old_fileid];
            if ( empty($new_fileid) )
            {
                $new_fileid = $old_fileid;
            }
            $new_image = "$new_fileid.png";

            echo "($old_fileid,$old_image) ";
            if ( $new_fileid == $old_fileid && $new_image == $old_image )
            {
                echo "-> no change\n";
            }
            else
            {
                echo "-> ($new_fileid,$new_image) ...\n";

                // database
                echo "    database:";
                // This ignores $writeBIGtable
                $query = "
                    UPDATE $projectid
                    SET fileid='$new_fileid', image='$new_image'
                    WHERE fileid='$old_fileid' AND image='$old_image'
                ";
                echo $query;
                if ($for_real)
                {
                    mysql_query($query) or die(mysql_error());
                    $n = mysql_affected_rows();
                    echo "
                        $n rows affected.
                    ";
                    if ($n != 1)
                    {
                        echo "\n";
                        echo "Unexpected number of rows affected.\n";
                        die("Aborting");
                    }
                }
                echo "\n";

                // file-system
                echo "    filesystem:";
                echo "
                    mv $old_image $new_image
                ";
                if ($for_real)
                {
                    $success = rename( $old_image, $new_image );
                    $s = ( $success ? 'succeeded' : 'FAILED' ); 
                    echo "
                        mv $s
                    ";
                    if (!$success)
                    {
                        echo "\n";
                        die("Aborting");
                    }
                }
                echo "\n";
            }
        }
        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

// vim: sw=4 ts=4 expandtab
?>
