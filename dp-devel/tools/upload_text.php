<?
$relPath="../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include($relPath.'project_states.inc');
include($relPath.'project_trans.inc');
include($relPath.'html_main.inc');

// use: $code_url/tools/upload_text.php?project=projectid&curr_state=...

if ($stage == 'post_1')
{
	$what = "Post-Processed File";
	$indicator = "_second";
	$new_state = PROJ_POST_SECOND_AVAILABLE;
	$extras = array();
	$back_url = "$code_url/tools/post_proofers/post_proofers.php";
	$back_blurb = "Back to Post Proofers Page";
	$bottom_blurb = "
		<B>Note:</B>Please make sure the file you upload is<BR> Zipped
		(not Gzip, TAR, etc.).<BR>
		After you click Upload, the browser will appear to be slow<BR>
		getting to the next page.
		This is because it is uploading the file.
	";
}
else if ($stage == 'correct')
{
	$what = "Corrected Edition";
	$indicator = "_corrections";
	$new_state = PROJ_CORRECT_AVAILABLE;
	$extras = array( 'correctedby' => $pguser );
	$back_url = "$code_url/list_etexts.php?x=g";
	$back_blurb = "Back to Gold List";
	$bottom_blurb = "
		<B>Note:</B>Please make sure the file you upload is Zipped
		(not Gzip, TAR, etc.).
		After you click Upload, the browser will appear to be slow
		getting to the next page.
		This is because it is uploading the file.
		When making corrections, please read over the entire book
		and compare your corrections to the page images available.
		Frequently Asked Questions will be developed
		as this feature is used more.
		Put any questions in the forums.
	";
}
else
{
	echo "Don't know how to handle stage='$stage'<br>\n";
	return;
}

if (!isset($action))
{
	// Present the upload page.

	$htmlC->startHeader("$what Upload");
	$htmlC->startBody(0,1,0,0);
	$tb=$htmlC->startTable(0,0,0,0);
	$tr=$htmlC->startTR(0,0,1);
	$td1=$htmlC->startTD(2,0,2,0,"center",0,0,1);
	$td2=$htmlC->startTD(1,0,0,0,"center",0,0,1);
	$td3=$htmlC->startTD(0,0,0,0,"center",0,0,1);
	$td4=$htmlC->startTD(1,0,2,0,"center",0,0,1);
	$td5=$htmlC->startTD(0,0,2,0,"center",0,0,1);
	echo "<FORM ACTION='upload_text.php' METHOD='POST' ENCTYPE='multipart/form-data'>";
	echo $tb;
	echo $tr.$td1;
	echo "<B>Upload $what for Verification</B>";
	echo $td3;
	echo "<INPUT TYPE='hidden' NAME='project' VALUE=$project>";
	echo "<INPUT TYPE='hidden' NAME='stage' VALUE='$stage'>";
	echo "<INPUT TYPE='hidden' NAME='action' VALUE='1'>";
	echo "<INPUT TYPE='hidden' NAME='MAX_FILE_SIZE' VALUE='8388608'>";
	echo $tr.$td2;
	echo "<STRONG>Zipped File:</STRONG>";
	echo $td3;
	echo "<INPUT TYPE='file' NAME='files[]' SIZE='25' MAXSIZE='50'>";
	echo $tr.$td4;
	echo "<STRONG>Leave Comments:</STRONG>";
	echo $tr.$td4;
	echo "<textarea NAME='postcomments' COLS='50' ROWS='16'></textarea>";
	echo $tr.$td4;
	echo "<INPUT TYPE='submit' VALUE='Upload'>";
	echo $tr.$td5;
	echo $bottom_blurb;
	echo $tr.$td1;
	echo "<A HREF='$back_url'><B>$back_blurb</B></A>";
	echo "</TD></TR></TABLE></FORM></DIV></CENTER></BODY></HTML>";
}
else
{
	// Handle a submission from the upload page.

	// if files have been uploaded, process them

	// make reasonably sure script does not timeout on large file uploads
	set_time_limit(3600);
	$path_to_file = "$projects_dir/$project";

	$files = $HTTP_POST_FILES['files'];

	if (substr($files['name'][0], -4) != ".zip") {
		echo "Invalid Filename";
		exit();
	}

	if (!ereg("/$", $path_to_file))
	{
		$path_to_file = $path_to_file."/";
	}
	foreach ($files['name'] as $key=>$name)
	{
		if ($files['size'][$key])
		{
			// replace filename
			$zipext = ".zip";
			$name = $project.$indicator.$zipext;
			$location = $path_to_file.$name;
			while (file_exists($location)) $location .= ".copy";
			copy($files['tmp_name'][$key],$location);
			unlink($files['tmp_name'][$key]);

			$error_msg = project_transition( $project, $new_state, $extras );
			if ($error_msg)
			{
				echo "$error_msg<br>\n";
			}

			// let them know file uploaded and send back to pp page
			metarefresh(1, $back_url, "File uploaded", "File uploaded!");
		}
	}
}

?>
