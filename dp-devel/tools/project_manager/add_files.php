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
        echo "here is the output of \"$string\"...<br>\n";
        echo "<hr>\n";
        echo "<pre>\n";
        system($string);
        echo "</pre>\n";
        echo "<hr>\n";
        echo "Return to <a href='projectmgr.php?project=$project'>Project Page</a>.\n";

        // metarefresh(0, "projectmgr.php?project=$project", "Files Added", "");

    }
?>

