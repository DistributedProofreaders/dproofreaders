<?PHP
include_once($relPath.'tdecho.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'project_states.inc');

function show_projects_in_state($state,$table=1)
{
	global $pguser, $code_url;

	if ($state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT)
	{
		$stage = 'post_1';
		$foo_Header = 'Manager';
		$foo_field_name = 'username';
	}
	elseif ($state==PROJ_POST_SECOND_AVAILABLE || $state==PROJ_POST_SECOND_CHECKED_OUT)
	{
		$stage = 'post_2';
		$foo_Header = 'Post Processor';
		$foo_field_name = 'postproofer';
	}
	elseif ($state==PROJ_CORRECT_AVAILABLE || $state==PROJ_CORRECT_CHECKED_OUT)
	{
		$stage = 'correct';
		$foo_Header = 'Editor';
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
			correctedby
		FROM projects
		WHERE state='$state'
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

	$theme = $GLOBALS['theme'];
	$tds="<td bgcolor='".$theme['color_headerbar_bg']."' align=\"center\"";
	$tdm="<font color='".$theme['color_headerbar_font']."'>";
	$tdc="</b></font></td>";
	echo "
		<tr>
		$tds width=\"190\">$tdm<b>Title$tdc
		$tds width=\"100\">$tdm<b>Author$tdc
		$tds width=\"90\">$tdm<b>Language$tdc
		$tds width=\"90\">$tdm<b>Genre$tdc
		$tds width=\"50\">$tdm<b>Pages$tdc
		$tds width=\"75\">$tdm<b>$foo_Header$tdc
		$tds>$tdm<b>Book Options$tdc
		</tr>
	";

	$numrow = mysql_numrows($result);
	for ( $rownum = 0; $rownum < $numrow; $rownum++ )
	{
		echo "<tr>\n";

		$bgcolor = bgcol($rownum,'blah');

		$book = mysql_fetch_assoc($result);

		$tpages = total_pages($book['projectid']);

		$foo_username = $book[$foo_field_name];
		$users = mysql_query("SELECT email FROM users WHERE username = '$foo_username'");
		if ( mysql_num_rows($users) > 0 )
		{
			$foo_email = mysql_result($users, 0, "email");
			$foo_cell = "<A HREF=\"mailto: $foo_email\">".$book[$foo_field_name]."</A>";
		}
		else
		{
			$foo_cell = $book[$foo_field_name];
		}

		tde($book['nameofwork'],  $bgcolor);
		tde($book['authorsname'], $bgcolor);
		tde($book['language'],    $bgcolor . " align=center");
		tde($book['genre'],       $bgcolor . " align=center");
		tde($tpages,              $bgcolor . " align=center");
		tde($foo_cell,            $bgcolor . " align=center");

		echo "<td $bgcolor>\n";
		echo "<form name=\"{$book['projectid']}\" method=\"get\" action=\"$code_url/tools/changestate.php\">";
		echo "<input type=\"hidden\" name=\"project\" value=\"{$book['projectid']}\">\n";
		echo "<input type=\"hidden\" name=\"curr_state\" value=\"$state\">\n";

		if ($state==PROJ_POST_FIRST_AVAILABLE)
		{
			$serious_code=PROJ_POST_FIRST_CHECKED_OUT;
			$serious_label='Check Out Book';
			$serious_question='Are you sure you want to check this book out for post processing?';
		}
		elseif ($state==PROJ_POST_FIRST_CHECKED_OUT)
		{
			$serious_code=PROJ_POST_FIRST_AVAILABLE;
			$serious_label='Return to Available';
			$serious_question='Are you sure you want to make this book available to others for post processing?';
		}
		elseif ($state==PROJ_POST_SECOND_AVAILABLE)
		{
			$serious_code=PROJ_POST_SECOND_CHECKED_OUT;
			$serious_label='Check Out Book';
			$serious_question='Are you sure you want to check this book out for verifying post processing?';
		}
		elseif ($state==PROJ_POST_SECOND_CHECKED_OUT)
		{
			$serious_code=PROJ_POST_SECOND_AVAILABLE;
			$serious_label='Return to Available';
			$serious_question='Are you sure you want to make this book available to others to verify and lose your work?';
		}
		elseif ($state==PROJ_CORRECT_AVAILABLE)
		{
			$serious_code=PROJ_CORRECT_CHECKED_OUT;
			$serious_label='Check Out Book';
			$serious_question='Are you sure you want to check this book out to review corrections?';
		}
		elseif ($state==PROJ_CORRECT_CHECKED_OUT)
		{
			$serious_code=PROJ_CORRECT_AVAILABLE;
			$serious_label='Return to Available';
			$serious_question='Are you sure you want to make this book available to others for reviewing corrections?';
		}
		echo "<select name=\"request\" onchange=\"";
		echo "if (this.value=='$serious_code'){di=confirm('$serious_question');if(di){this.form.submit();}}else {this.form.submit();}";
		echo "\">\n";

		echo "<option selected>Select...</option>\n";

		echo_option( 'GET_IMAGES_HTML', 'View Images Online' );
		echo_option( 'GET_IMAGES_ZIP', 'Download Zipped Images' );

		if ($stage == 'post_1')
		{
			echo_option( 'GET_TEXT_POST_1_ZIP', 'Download Zipped Text' );
			echo_option( 'GET_COMMENTS_POST', 'View Project Comments' );
		}
		elseif ($stage == 'post_2')
		{
			echo_option( 'GET_TEXT_POST_2_ZIP', 'Download Zipped Text' );
			echo_option( 'GET_COMMENTS_POST', 'View Project Comments' );
		}
		elseif ($stage == 'correct')
		{
			echo_option( 'GET_TEXT_CORR_ZIP', 'Download Zipped Text' );
		}

		echo_option( $serious_code, $serious_label );

		if ($state == PROJ_POST_FIRST_CHECKED_OUT)
		{
			echo_option( PROJ_POST_SECOND_AVAILABLE, 'Upload for Verification' );
		}
		elseif ($state == PROJ_CORRECT_CHECKED_OUT)
		{
			echo_option(PROJ_SUBMIT_PG_POSTED, "Posted to Project Gutenberg");
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

?>
