<?
$relPath="./../../pinc/";
include($relPath.'metarefresh.inc');
include($relPath.'project_edit.inc');
include('page_operations.inc');

    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $confirm = $_GET['confirm'];
    
    abort_if_cant_edit_project( $project );

    if (!empty($confirm)) {

	page_delete( $project, $fileid );

        metarefresh(0, "project_detail.php?project=$project", "Page(s) Deleted", ""); 
    } else {
        echo "Confirm delete of $fileid <a href='deletefile.php?project=$project&fileid=$fileid&confirm=yes'>here</a>";
    }
?>
