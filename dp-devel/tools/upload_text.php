<?
$relPath="../pinc/";
include($relPath.'v_site.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include($relPath.'project_states.inc');
include($relPath.'project_trans.inc');
include_once($relPath.'theme.inc');

// use: $code_url/tools/upload_text.php?project=projectid&curr_state=...

if ($stage == 'post_1')
{
	$what = _("Post-Processed File");
      $for_what = _("for Verification");
	$indicator = "_second";
	$new_state = PROJ_POST_SECOND_AVAILABLE;
	$extras = array();
	$back_url = "$code_url/tools/post_proofers/post_proofers.php";
	$back_blurb = _("Back to Post Proofers Page");
	$bottom_blurb = _("<B>Note:</B>Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc. After you click Upload, the browser will appear to be slow getting to the next page. This is because it is uploading the file.");
}
else if ($stage == 'correct')
{
	$what = _("Corrected Edition");
      $for_what = _("for Verification");
	$indicator = "_corrections";
	$new_state = PROJ_CORRECT_AVAILABLE;
	$extras = array( 'correctedby' => $pguser );
	$back_url = "$code_url/list_etexts.php?x=g";
	$back_blurb = _("Back to Gold List");
	$bottom_blurb = _("<B>Note:</B>Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc. After you click Upload, the browser will appear to be slow getting to the next page.	This is because it is uploading the file.")._(" When making corrections, please read over the entire book and compare your corrections to the <a href='http://www.pgdp.net/projects/$project'>page images</a> available. Frequently Asked Questions will be developed as this feature is used more. Put any questions in the forums.");
}
else if ($stage == 'smooth_avail')
{
	$what = _("File Ready for Smooth Reading");
      $for_what = "";
	$indicator = "_smooth_avail";
	$new_state = PROJ_POST_FIRST_CHECKED_OUT;
	$extras = array();
	$back_url = "$code_url/tools/post_proofers/post_comments.php?project=$project";
	$back_blurb = _("Back to Project Information Page");
	$bottom_blurb = _("<B>Note:</B>Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc. After you click Upload, the browser will appear to be slow getting to the next page. This is because it is uploading the file.");
      $deadline = time() + ($weeks * 60 * 60 * 24 * 7);
}
else if ($stage == 'smooth_done')
{
	$what = _("Smooth Read Version");
      $for_what = "";
	$indicator = "_smooth_done_".$pguser;
	$new_state = PROJ_POST_FIRST_CHECKED_OUT;
	$extras = array();
	$back_url = "$code_url/tools/post_proofers/SR_info.php?project=$project";
	$back_blurb = _("Back to Smooth Reading Project Information Page");
	$bottom_blurb = _("<B>Note:</B>Please make sure the file you upload is Zipped (not Gzip, TAR, etc.). The file should have the .zip extension, NOT .Zip, .ZIP, etc. After you click Upload, the browser will appear to be slow getting to the next page. This is because it is uploading the file.");
      $deadline = time() + ($weeks * 60 * 60 * 24 * 7);

}

else
{
	echo "Don't know how to handle stage='$stage'<br>\n";
	return;
}

if (!isset($action))
{
	// Present the upload page.

	$header = "$what "._("Upload");
	theme($header, "header");

	echo "<FORM ACTION='upload_text.php' METHOD='POST' ENCTYPE='multipart/form-data'>";
	echo "<br><table bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'>";
	echo "<tr><td bgcolor='#336633' colspan='2' align='center'>";
	echo "<B><font color='#ffffff'>"._("Upload $what $for_what")."</font></B>";
	echo "<td bgcolor='#ffffff' align='center'>";
	echo "<INPUT TYPE='hidden' NAME='project' VALUE=$project>";
	echo "<INPUT TYPE='hidden' NAME='stage' VALUE='$stage'>";
	echo "<INPUT TYPE='hidden' NAME='weeks' VALUE='$weeks'>";
	echo "<INPUT TYPE='hidden' NAME='action' VALUE='1'>";
	echo "<INPUT TYPE='hidden' NAME='MAX_FILE_SIZE' VALUE='25165824'>";
	echo "<tr><td bgcolor='#e0e8dd' align='center'>";
	echo "<STRONG>"._("Zipped File:")."</STRONG>";
	echo "<td bgcolor='#ffffff' align='center'>";
	echo "<INPUT TYPE='file' NAME='files[]' SIZE='25' MAXSIZE='50'>";
	echo "<tr><td bgcolor='#e0e8dd' colspan='2' align='center'>";
      if ($stage != 'smooth_done') {
          if ($stage != 'smooth_avail') {
              echo "<STRONG>"._("Leave Comments:")."</STRONG>";
          } else {
              echo "<STRONG>"._("Leave Instructions for Smooth Readers:")."</STRONG>";
          }
          echo "<tr><td bgcolor='#e0e8dd' colspan='2' align='center'>";
          echo "<textarea NAME='postcomments' COLS='50' ROWS='16'></textarea>";
      }
	echo "<tr><td bgcolor='#e0e8dd' colspan='2' align='center'>";
	echo "<INPUT TYPE='submit' VALUE='Upload'>";
	echo "<tr><td bgcolor='#ffffff' colspan='2' align='center'>";
	echo $bottom_blurb;
	echo "<tr><td bgcolor='#336633' colspan='2' align='center'>";
	echo "<A HREF='$back_url'><B>$back_blurb</B></A>";
	echo "</TD></TR></TABLE></FORM></DIV></CENTER>";
	theme("", "footer");

}
else
{
	// Handle a submission from the upload page.

	// if files have been uploaded, process them

	// make reasonably sure script does not timeout on large file uploads
	set_time_limit(14400);
	$path_to_file = "$projects_dir/$project";

	$files = $HTTP_POST_FILES['files'];

	if (substr($files['name'][0], -4) != ".zip") {
		echo _("Invalid Filename");
		exit();
	}

	if (!ereg("/$", $path_to_file))
	{
		$path_to_file = $path_to_file."/";
	}

	function ensure_path_is_unused( $path )
	// Ensure that nothing exists at $path.
	// (If something's there, rename it.)
      // EXCEPT: let people overwrite their finished SR files as often as they want
	{
             global $stage;

		if ( file_exists($path) )
		{

                   if (($stage != 'smooth_done') AND ($stage != 'smooth_avail')){

   			    $bak = "$path.bak";
			    ensure_path_is_unused( $bak );
			    $success = rename( $path, $bak );
			    if (!$success)
			    {
				    // It will already have printed a warning.
				    echo sprintf(
				           	_("A problem occurred with your upload. Please email %s for assistance, and include the text of this page."),
					     'db-req@pgdp.net' );
				   exit;
			    }
                  } else {

                        unlink($path);
                  }
		}
	}

	foreach ($files['name'] as $key=>$name)
	{
		if ($files['size'][$key])
		{
			// replace filename
			$zipext = ".zip";
			$name = $project.$indicator.$zipext;
			$location = $path_to_file.$name;
			ensure_path_is_unused( $location );
			copy($files['tmp_name'][$key],$location);
			unlink($files['tmp_name'][$key]);

			$error_msg = project_transition( $project, $new_state, $extras );
			if ($error_msg)
			{
				echo "$error_msg<br>\n";
			}

                   // special handling for smooth reading, which does not involve a state change
                   // but still needs some changes recorded in project table
                   if ($stage == 'smooth_avail') {
                      $qry =  mysql_query("
                          UPDATE projects SET smoothread_deadline = $deadline,  postcomments = '$postcomments'
                          WHERE projectid = '$project'
                      ");

                   }


			// let them know file uploaded and send back to pp page
			$msg = _("File uploaded. Thank you!");
			metarefresh(1, $back_url, $msg, $msg);
		}
	}
}

?>
