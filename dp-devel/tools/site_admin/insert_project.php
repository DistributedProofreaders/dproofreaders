<?PHP
$relPath='../../pinc/';
include_once($relPath.'misc.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');

if ( !user_is_a_sitemanager() )
{
    die( "You are not allowed to run this script." );
}

echo "<pre>";

echo "<h2>Insert one project into another</h2>";
echo "\n";

$submit_button = array_get( $_POST, 'submit_button', '' );

switch ( $submit_button )
{
    case '':
        echo "<form method='post'>";
        echo "Extract page-data from project: <input type='text' name='projectid_[from]'>\n";
        echo "    and insert it into project: <input type='text' name='projectid_[to]'>\n";
        echo "<input type='submit' name='submit_button' value='Check'>";
        echo "</form>";
        break;

    case 'Check':
        $projectid_ = array_get( $_POST, 'projectid_', NULL );

        review( $projectid_ );

        echo "There don't appear to be any page-name clashes.\n\n";
        echo "<form method='post'>";
        echo "<input type='hidden' name='projectid_[from]' value='{$projectid_['from']}'>";
        echo "<input type='hidden' name='projectid_[to]'   value='{$projectid_['to']}'>";
        echo "<input type='submit' name='submit_button' value='Do insertions!'>";
        echo "</form>";
        break;

    case 'Do insertions!':
        $for_real = 1;

        $projectid_ = array_get( $_POST, 'projectid_', NULL );

        list($src_image_values,$dest_column_names) = review( $projectid_ );

        $n_moving_pages = count($src_image_values);

        echo "database:\n";
        // This ignores $writeBIGtable
        $dest_column_list = join( $dest_column_names, ',' );
        $query = "
            INSERT INTO {$projectid_['to']}
            SELECT $dest_column_list
            FROM {$projectid_['from']}
        ";
        echo $query;
        if ($for_real)
        {
            mysql_query($query) or die(mysql_error());
            $n = mysql_affected_rows();
            echo "
                $n rows inserted.
            ";
            if ( $n != $n_moving_pages )
            {
                die( "unexpected number of rows inserted" );
            }
        }
        echo "\n";

        echo "filesystem:\n";
        // cd to projects dir to simplify filesystem moves
        echo "    cd $projects_dir\n";
        if ( ! chdir( $projects_dir ) )
        {
            die( "Unable to 'cd $projects_dir'" );
        }

        echo "    copying image-files from {$projectid_['from']}/ to {$projectid_['to']}/\n";
        foreach ( $src_image_values as $image )
        {
            echo "\n";
            echo "        $image ...\n";

            $old_path = "{$projectid_['from']}/$image";
            $new_path = "{$projectid_['to']}/$image";

            if ($for_real)
            {
                $success = copy( $old_path, $new_path );
                $s = ( $success ? 'succeeded' : 'failed' ); 
                echo "
                        copy $s
                ";
            }
        }

        echo "\n";
        $url = "$code_url/tools/project_manager/page_detail.php?project={$projectid_['to']}&amp;show_image_size=0";
        echo "<a href='$url'>'To' project's detail page</a>\n";

        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

echo "</pre>";

function review( $projectid_ )
{
    if ( is_null($projectid_) )
    {
        die( "Error: no projectid data supplied" );
    }

    $page_names_ = array();

    foreach ( array( 'from', 'to' ) as $which )
    {
        echo "$which:\n";

        $projectid = $projectid_[$which];

        echo "    projectid: $projectid\n";

        $res = mysql_query("
            SELECT nameofwork
            FROM projects
            WHERE projectid='$projectid'
        ") or die(mysql_error());

        $n_projects = mysql_num_rows($res);
        if ( $n_projects == 0 )
        {
            die( "projects table has no match for projectid='$projectid'" );
        }
        else if ( $n_projects > 1 )
        {
            die( "projects table has $n_projects matches for projectid='$projectid'. (Can't happen)" );
        }

        list($title) = mysql_fetch_row($res);

        echo "    title    : $title\n";

        // ------------

        $res = mysql_query("
            SELECT image, fileid
            FROM $projectid
        ") or die(mysql_error());

        $n_pages = mysql_num_rows($res);

        echo "    # pages  : $n_pages\n";

        if ( $which == 'from' && $n_pages == 0 )
        {
            die( "project has no page data to extract" );
        }

        $image_values = array();
        $fileid_values = array();
        while ( list($image,$fileid) = mysql_fetch_row($res) )
        {
            $image_values[] = $image;
            $fileid_values[] = $fileid;
        }

        $image_values_[$which] = $image_values;
        $fileid_values_[$which] = $fileid_values;

        // ----------------------

        $res= mysql_query("
            DESCRIBE $projectid
        ") or die(mysql_error());

        $n_columns = mysql_num_rows($res);
        echo "    # columns: $n_columns\n";

        $column_names = array();
        while ( $row = mysql_fetch_assoc($res) )
        {
            $column_names[] = $row['Field'];
        }
        $column_names_[$which] = $column_names;

        // ----------------------

        echo "\n";
    }

    if ( $projectid_['from'] == $projectid_['to'] )
    {
        die( "You can't insert a project into itself." );
    }

    $common_image_values = array_intersect( $image_values_['from'], $image_values_['to'] );
    if ( count($common_image_values) > 0 )
    {
        echo "Name clash! The 'to' project already has pages with these 'image' values:\n";
        foreach ( $common_image_values as $common_image_value )
        {
            echo "    $common_image_value\n";
        }
        die("");
    }

    $common_fileid_values = array_intersect( $fileid_values_['from'], $fileid_values_['to'] );
    if ( count($common_fileid_values) > 0 )
    {
        echo "Name clash! The 'to' project already has pages with these 'fileid' values:\n";
        foreach ( $common_fileid_values as $common_fileid_value )
        {
            echo "    $common_fileid_value\n";
        }
        die("");
    }

    if ( count($column_names_['from']) < count($column_names_['to']) )
    {
        die( "The 'from' project has fewer columns than the 'to' project. Not handled yet." );
    }
    else if ( count($column_names_['from']) > count($column_names_['to']) )
    {
        echo "The 'from' project has more columns than the 'to' project, but we can handle this now.\n";
    }

    return array( $image_values_['from'], $column_names_['to'] );
}

// vim: sw=4 ts=4 expandtab
?>
