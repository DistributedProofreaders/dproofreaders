<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include($relPath.'project_edit.inc');

    $project = $_GET['project'];

    abort_if_cant_edit_project( $project );

    {
        if ($userP['sitemanager'] == 'yes') {
            $string = "perl add_files.pl $project $projects_dir/";
        } else $string = "perl add_files.pl $project $uploads_dir/";
        exec($string);
        metarefresh(0, "projectmgr.php?project=$project", "Files Added", "");
    }
?>

