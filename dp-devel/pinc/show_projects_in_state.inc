<?PHP
include_once($relPath.'tdecho.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'gettext_setup.inc');

if ( empty($pageCountArray) )
{
	update_pageCountArray();
}

function show_projects_in_state($state,$table=1, $where_filter = ' ')
{
	global $pguser, $code_url, $pageCountArray;
	$theme = $GLOBALS['theme'];
	$bgcolor = $theme['color_headerbar_bg'];
	if ($state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT)
	{
		$stage = 'post_1';
		$foo_Header = _("Manager");
		$foo_field_name = 'username';
	}
	elseif ($state==PROJ_POST_SECOND_AVAILABLE || $state==PROJ_POST_SECOND_CHECKED_OUT)
	{
		$stage = 'post_2';
		$bgcolor = '#66ccff';
		$foo_Header = _("Post Processor");
		$foo_field_name = 'postproofer';
	}
	elseif ($state==PROJ_CORRECT_AVAILABLE || $state==PROJ_CORRECT_CHECKED_OUT)
	{
		$stage = 'correct';
		$foo_Header = _("Editor");
		$foo_field_name = 'correctedby';
	}
	else
	{
		// This function doesn't handle anything else so far.
		assert(0);
	}

	$query = "
		SELECT
			projectid,
			nameofwork,
			authorsname,
			language,
			genre,
			username,
			postproofer,
			checkedoutby,
			correctedby,
                        difficulty, 
			comments
		FROM projects
		WHERE state='$state'
                $where_filter 
	";
	if (
		$state == PROJ_POST_FIRST_CHECKED_OUT ||
		$state == PROJ_POST_SECOND_CHECKED_OUT ||
		$state == PROJ_CORRECT_CHECKED_OUT
	)
	{
		// The project must be checked-out to somebody.
		// We're only interested if it's checked out to the current user.
		$query .= " AND checkedoutby = '$pguser'";
	}

	$query .= " ORDER BY modifieddate";
	$result = mysql_query($query);


	if ($table)
	{
		echo "
			<table
				align='center'
				border=1
				width=630
				cellpadding=0
				cellspacing=0
				style='border-collapse: collapse;'
				bordercolor='#111111'
			>
		";
	}

	$tds="<td bgcolor='".$bgcolor."' align=\"center\"";
	$tdm="<font color='".$theme['color_headerbar_font']."'>";
	$tdc="</b></font></td>";
	echo "
		<tr>
		$tds width=\"190\">$tdm<b>"._("Title")."$tdc
		$tds width=\"100\">$tdm<b>"._("Author")."$tdc
		$tds width=\"90\">$tdm<b>"._("Language")."$tdc
		$tds width=\"90\">$tdm<b>"._("Genre")."$tdc
		$tds width=\"50\">$tdm<b>"._("Pages")."$tdc
		$tds width=\"75\">$tdm<b>$foo_Header$tdc
		$tds>$tdm<b>"._("Book Options")."$tdc
		</tr>
	";

	// the following only need to be done once, not every iteration of the loop below!
	$userid_q = mysql_query("SELECT u_id FROM users WHERE username = '".$GLOBALS['pguser']."'");
	$u_id = mysql_result($userid_q, 0, "u_id");
	$show_email = user_is_a_sitemanager() || $u_id == 1342 || $u_id == 12417;

	$numrow = mysql_numrows($result);
	for ( $rownum = 0; $rownum < $numrow; $rownum++ )
	{
		echo "<tr>\n";



		$book = mysql_fetch_assoc($result);

		$bgcolor = bgcol($rownum,$stage);

		// experimental!

		// pink for Saint Valentine's Day
                if ( startswith( $book['comments'], 'SPECIAL: St. Val' ) )
                {
                	$bgcolor = " bgcolor='#FF99FF'";
                }


		$tpages = $pageCountArray[$book['projectid']]['total_pages'];

		$foo_username = $book[$foo_field_name];
		$users = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$foo_username'");
		
		if ( mysql_num_rows($users) > 0 )
		{
			if ($show_email) {
				$users_email = mysql_query("SELECT email FROM users WHERE username = '$foo_username'");
				$foo_email = mysql_result($users_email, 0, "email");
				$foo_cell = "<A HREF=\"mailto:$foo_email\">".$book[$foo_field_name]."</A>";
			} else {
				$foo_userid = mysql_result($users, 0, "user_id");
				$foo_cell = "<A HREF=\"".$GLOBALS['forums_url']."/privmsg.php?mode=post&u=$foo_userid\">".$book[$foo_field_name]."</A>";
			}
		}
		else
		{
			$foo_cell = $book[$foo_field_name];
		}

		echo "\n<td $bgcolor> ". $book['nameofwork']. " </td>";
		echo "\n<td $bgcolor> ". $book['authorsname']. " </td>";
		echo "\n<td $bgcolor align=center> ". $book['language']. " </td>";

		if ($book['difficulty'] == "easy") { $genre = _("EASY")." ".$book['genre']; }
            		elseif ($book['difficulty'] == "hard") { $genre = _("HARD")." ".$book['genre']; }
	            	else { $genre = $book['genre']; }

		echo "\n<td $bgcolor align=center>$genre</td>";
		echo "\n<td $bgcolor align=center>$tpages</td>";
		echo "\n<td $bgcolor align=center>$foo_cell</td>";

		echo "<td $bgcolor>\n";
		echo "<form name=\"{$book['projectid']}\" method=\"get\" action=\"$code_url/tools/changestate.php\">";
		echo "<input type=\"hidden\" name=\"project\" value=\"{$book['projectid']}\">\n";
		echo "<input type=\"hidden\" name=\"curr_state\" value=\"$state\">\n";

		if ($state==PROJ_POST_FIRST_AVAILABLE)
		{
			$serious_code=PROJ_POST_FIRST_CHECKED_OUT;
			$serious_label= _("Check Out Book");
			$serious_question= _("Are you sure you want to check this book out for post processing?");
		}
		elseif ($state==PROJ_POST_FIRST_CHECKED_OUT)
		{
			$serious_code=PROJ_POST_FIRST_AVAILABLE;
			$serious_label=_("Return to Available");
			$serious_question=_("Are you sure you want to make this book available to others for post processing?");
		}
		elseif ($state==PROJ_POST_SECOND_AVAILABLE)
		{
			$serious_code=PROJ_POST_SECOND_CHECKED_OUT;
			$serious_label= _("Check Out Book");
			$serious_question=_("Are you sure you want to check this book out for verifying post processing?");
		}
		elseif ($state==PROJ_POST_SECOND_CHECKED_OUT)
		{
			$serious_code=PROJ_POST_SECOND_AVAILABLE;
			$serious_label=_("Return to Available");
			$serious_question=_("Are you sure you want to make this book available to others to verify and lose your work?");
		}
		elseif ($state==PROJ_CORRECT_AVAILABLE)
		{
			$serious_code=PROJ_CORRECT_CHECKED_OUT;
			$serious_label= _("Check Out Book");
			$serious_question=_("Are you sure you want to check this book out to review corrections?");
		}
		elseif ($state==PROJ_CORRECT_CHECKED_OUT)
		{
			$serious_code=PROJ_CORRECT_AVAILABLE;
			$serious_label=_("Return to Available");
			$serious_question=_("Are you sure you want to make this book available to others for reviewing corrections?");
		}
		echo "<select name=\"request\" onchange=\"";
		echo "if (this.value=='$serious_code'){di=confirm('$serious_question');if(di){this.form.submit();}}else {this.form.submit();}";
		echo "\">\n";

		echo "<option selected>"._("Select")."...</option>\n";

		$label = _("View Images Online");
		echo_option( 'GET_IMAGES_HTML', $label);
		$label = _("Download Zipped Images");
		echo_option( 'GET_IMAGES_ZIP', $label);

		if ($stage == 'post_1')
		{
			$label = _("Download Zipped Text");
			echo_option( 'GET_TEXT_POST_1_ZIP', $label);
			$label = _("Download Zipped TEI Text");
			echo_option( 'GET_XML_POST_1_ZIP',  $label);
			$label = _("View Project Comments");
			echo_option( 'GET_COMMENTS_POST', $label);
			if ($state==PROJ_POST_FIRST_CHECKED_OUT) {
				$label = _("View Project Details");
	                        echo_option( 'GET_PROJ_DETAILS', $label);
			}
		}
		elseif ($stage == 'post_2')
		{
			$label = _("Download Zipped Text");
			echo_option( 'GET_TEXT_POST_2_ZIP', $label);
			$label = _("View Project Comments");
			echo_option( 'GET_COMMENTS_POST', $label);
			if ($state==PROJ_POST_SECOND_CHECKED_OUT) {
				$label = _("View Project Details");
	                        echo_option( 'GET_PROJ_DETAILS', $label);
			}

		}
		elseif ($stage == 'correct')
		{
			$label = _("Download Zipped Text");
			echo_option( 'GET_TEXT_CORR_ZIP', $label);
		}

		echo_option( $serious_code, $serious_label );

		if ($state == PROJ_POST_FIRST_CHECKED_OUT)
		{
			$label = _("Upload for Verification");
			echo_option( PROJ_POST_SECOND_AVAILABLE, $label);
		}
		elseif ($state == PROJ_CORRECT_CHECKED_OUT)
		{
			$label = _("Posted to Project Gutenberg");
			echo_option(PROJ_SUBMIT_PG_POSTED, $label);
		}

		echo "</select></form></td>\n";
		echo "</tr>\n";
	}

	if ($table)
	{
		echo "</table>\n";
	}
}

function echo_option($code,$label)
{
	echo "<option value=\"$code\">$label</option>\n";
}

function startswith( $subject, $prefix )
// Return TRUE iff $subject starts with $prefix.
{
	return ( strncmp( $subject, $prefix, strlen($prefix) ) == 0 );
}

?>
