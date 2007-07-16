<?
include_once($relPath.'site_vars.php'); // $archive_db_name $projects_dir $archive_projects_dir
include_once($relPath.'project_events.inc'); // log_project_event


function archive_project( $project, $dry_run )
// -- Move the project's page-table to the archive database.
// -- Move the project's directory out of $projects_dir
//    (for later off-site migration).
// -- Mark the project as having been archived.
{
    $projectid = $project->projectid;

    $mod_time_str = strftime('%Y-%m-%d %H:%M:%S',$project->modifieddate);
    echo "$projectid ($mod_time_str) \"$project->nameofwork\"\n";

    global $archive_db_name;
    if (!mysql_query("DESCRIBE $projectid"))
    {
        echo "    Table $projectid does not exist.\n";
    }
    elseif ($dry_run)
    {
        echo "    Move table $projectid to $archive_db_name.\n";
    }
    else
    {
        mysql_query("
            ALTER TABLE $projectid
            RENAME AS $archive_db_name.$projectid
        ") or die(mysql_error());
    }

    global $projects_dir;
    $project_dir = "$projects_dir/$projectid";
    if (file_exists($project_dir))
    {
        global $archive_projects_dir;
        $new_dir = "$archive_projects_dir/$projectid";
        if ($dry_run)
        {
            echo "    Move $project_dir to $new_dir.\n";
        }
        else
        {
            // Remove uncompressed versions of whole-project texts, leaving zips.
            exec( "rm $project_dir/projectID*.txt" );
            rename( $project_dir, $new_dir ) or die( "Unable to move $project_dir to $new_dir" );
        }
    }
    else
    {
        echo "    Warning: $project_dir does not exist.\n";
    }

    if ($dry_run)
    {
        echo "    Mark project as archived.\n";
    }
    else
    {
        mysql_query("
            UPDATE projects
            SET archived = '1'
            WHERE projectid='$projectid'
        ") or die(mysql_error());

        log_project_event( $projectid, '[archiver]', 'archive' );
    }
}

// vim: sw=4 ts=4 expandtab
?>
