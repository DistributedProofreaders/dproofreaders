<?
$relPath="./../../pinc/";
include_once($relPath.'project_edit.inc');
include_once($relPath.'metarefresh.inc');
include_once('page_operations.inc');

    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $pagestate = $_GET['state'];
    $confirm = $_GET['confirm'];

    abort_if_cant_edit_project( $project );

    if (!empty($confirm)) {

	$err = page_clear( $project, $fileid );
	if ( $err )
	{
		echo "$err\n";
		echo "Go <a href='project_detail.php?project=$project'>back</a>.";
		exit;
	}

        metarefresh(0, "project_detail.php?project=$project", "Page Checked In ($round_number)", "");
    } else {
        echo "Confirm clear of $fileid <a href='checkin.php?project=$project&fileid=$fileid&state=$pagestate&confirm=yes'>here</a>";
    }
?>

