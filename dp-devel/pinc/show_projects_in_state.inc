<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');

if ( empty($pageCountArray) )
{
	update_pageCountArray();
}

function show_projects_in_state($state,$table=1, $where_filter = ' ', $order = 'DaysD')
{
	global $pguser, $code_url, $pageCountArray;

	$flip_title = FALSE;
	$flip_author = FALSE;
	$flip_lang = FALSE;
	$flip_genre = FALSE;
	$flip_PgTot = FALSE;
	$flip_Person = FALSE;
	$flip_days = FALSE;

	$theme = $GLOBALS['theme'];
	$bgcolor = $theme['color_headerbar_bg'];
	if ($state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT)
	{
		$foo_Header = _("Manager");
		$foo_field_name = 'username';
  		if ($state==PROJ_POST_FIRST_CHECKED_OUT) {
			$linkbase = "<a href=post_proofers.php?orderChPP=";
                        $linkend  = '#ChPP>';
		} else {
			$linkbase = "<a href=post_proofers.php?orderPP=";
                        $linkend  = '#PP>';
		}
		$bgcolor_odd = $theme['color_mainbody_bg'];
		$bgcolor_even = $theme['color_navbar_bg'];
	}
	elseif ($state==PROJ_POST_SECOND_AVAILABLE || $state==PROJ_POST_SECOND_CHECKED_OUT)
	{
		$bgcolor = '#66ccff';
		$foo_Header = _("Post Processor");
		$foo_field_name = 'postproofer';
  		if ($state==PROJ_POST_SECOND_CHECKED_OUT) {
			$linkbase = "<a href=post_proofers.php?orderChPPV=";
                        $linkend  = '#ChPPV>';
		} else {
			$linkbase = "<a href=post_proofers.php?orderPPV=";
                        $linkend  = '#PPV>';
		}
		$bgcolor_odd = '#EAF7F7'; // "paledarkskyblue"
		$bgcolor_even = '#99FFFF'; // "harshflourolightblue"

	}
	elseif ($state==PROJ_CORRECT_AVAILABLE || $state==PROJ_CORRECT_CHECKED_OUT)
	{
		$foo_Header = _("Editor");
		$foo_field_name = 'correctedby';
		// no sorting in CORRECT mode yet
		$linkbase = " ";
		$linkend  = " ";
		$bgcolor_odd = $theme['color_mainbody_bg'];
		$bgcolor_even = $theme['color_navbar_bg'];
	}
	else
	{
		// This function doesn't handle anything else so far.
		assert(0);
	}


	if ( $order == 'TitleA' )
	{
		$orderclause = 'nameofwork ASC';
		$flip_title = TRUE;
	}
	elseif ( $order == 'TitleD' )
	{
		$orderclause = 'nameofwork DESC';
	}
	elseif ( $order == 'AuthorA' )
	{
		$orderclause = 'authorsname ASC, nameofwork ASC';
		$flip_author = TRUE;
	}	
	elseif ( $order == 'AuthorD' )
	{
		$orderclause = 'authorsname DESC, nameofwork ASC';
	}
	elseif ( $order == 'LangA' )
	{
		$orderclause = 'language ASC, nameofwork ASC';
		$flip_lang = TRUE;
	}
	elseif ( $order == 'LangD' )
	{
		$orderclause = 'language DESC, nameofwork ASC';
	}
	elseif ( $order == 'GenreA' )
	{
		$orderclause = 'genre ASC, nameofwork ASC';
		$flip_genre = TRUE;
	}
	elseif ( $order == 'GenreD' )
	{
		$orderclause = 'genre DESC, nameofwork ASC';
	}
	elseif ( $order == 'PgTotA' )
	{
		$orderclause = 'total_pages ASC, nameofwork ASC';
		$flip_PgTot = TRUE;
	}
	elseif ( $order == 'PgTotD' )
	{
		$orderclause = 'total_pages DESC, nameofwork ASC';
	}
	elseif ( $order == 'PersonA' )
	{
		$orderclause = "$foo_field_name ASC, nameofwork ASC";
		$flip_Person = TRUE;
	}
	elseif ( $order == 'PersonD' )
	{
		$orderclause = "$foo_field_name DESC, nameofwork ASC";
	}
	
	// note that we SHOW "days since M", but *order* by M, so the logic is flipped
	elseif ( $order == 'DaysA' )
	{
		$orderclause = 'modifieddate DESC, nameofwork ASC';
		$flip_days = TRUE;
	}
	elseif ( $order == 'DaysD' )
	{
		$orderclause = 'modifieddate ASC, nameofwork ASC';
	}
	else
	{
		echo "show_projects_in_state.inc: bad order value: '$order'";
		exit;
	}


	$query = "
		SELECT
			projects.projectid,
			nameofwork,
			authorsname,
			language,
			genre,
			username,
			postproofer,
			checkedoutby,
			correctedby,
                        modifieddate,
              difficulty, 
	         round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) as days_avail,
			total_pages,
			comments
		FROM projects
			LEFT OUTER JOIN page_counts USING (projectid)
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

	$query .= " ORDER BY $orderclause";

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
	$tdc="</b></font></a></td>";

	echo "<tr>";

	$word = _("Title");
 	$link = $linkbase.($flip_title?"TitleD":"TitleA").$linkend;
	echo "$tds width=\"185\">$link$tdm<b>$word$tdc\n";

	$word = _("Author");
 	$link = $linkbase.($flip_author?"AuthorD":"AuthorA").$linkend;
	echo	"$tds width=\"95\">$link$tdm<b>$word$tdc\n";

	$word = _("Language");
 	$link = $linkbase.($flip_lang?"LangD":"LangA").$linkend;
	echo	"$tds width=\"85\">$link$tdm<b>$word$tdc\n";

	$word = _("Genre");
 	$link = $linkbase.($flip_genre?"GenreD":"GenreA").$linkend;
	echo "$tds width=\"85\">$link$tdm<b>$word$tdc\n";

	$word = _("Pages");
 	$link = $linkbase.($flip_PgTot?"PgTotD":"PgTotA").$linkend;
	echo	"$tds width=\"45\">$link$tdm<b>$word$tdc\n";

	$word = $foo_Header;
 	$link = $linkbase.($flip_Person?"PersonD":"PersonA").$linkend;
	echo "$tds width=\"70\">$link$tdm<b>$word$tdc\n";

	$word = _("Days");
 	$link = $linkbase.($flip_days?"DaysD":"DaysA").$linkend;
	echo "$tds width=\"35\">$link$tdm<b>$word$tdc\n";

	echo "</tr>";


	// the following only need to be done once, not every iteration of the loop below!
	$userid_q = mysql_query("SELECT u_id FROM users WHERE username = '".$GLOBALS['pguser']."'");
	$u_id = mysql_result($userid_q, 0, "u_id");
	$show_email = user_is_a_sitemanager() || $u_id == 1342 || $u_id == 12417;

	// Determine whether to use special colors or not
	// (this does not affect the alternating between two
	// background colors) in the project listing.
	$userSettings = Settings::get_Settings($pguser);
	$show_special_colors = !$userSettings->get_boolean('hide_special_colors');

	$numrow = mysql_numrows($result);
	for ( $rownum = 0; $rownum < $numrow; $rownum++ )
	{
		echo "<tr>\n";



		$book = mysql_fetch_assoc($result);

		if ($rownum % 2) 
		{
			$bgcolor = " bgcolor='$bgcolor_odd'";
		}
		else 
		{
			$bgcolor = " bgcolor='$bgcolor_even'";
		}

		// Special colours for special books of various types
		if ($show_special_colors)
		{
			$special_color = get_special_color_for_project($book);
			if (!is_null($special_color)) {
				$bgcolor = " bgcolor='$special_color'";
			}
		}


		$foo_username = $book[$foo_field_name];
		$users = mysql_query("SELECT user_id, user_email FROM phpbb_users WHERE username = '$foo_username'");
		
		if ( mysql_num_rows($users) > 0 )
		{
			if ($show_email) {
				$foo_email = mysql_result($users, 0, "user_email");
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

		$url = "$code_url/tools/post_proofers/post_comments.php?project={$book['projectid']}";
		echo "\n<td $bgcolor><a href='$url'>". $book['nameofwork']. "</a></td>";
		echo "\n<td $bgcolor> ". $book['authorsname']. " </td>";
		echo "\n<td $bgcolor align=center> ". $book['language']. " </td>";

		if ($book['difficulty'] == "easy") { $genre = _("EASY")." ".$book['genre']; }
            		elseif ($book['difficulty'] == "hard") { $genre = _("HARD")." ".$book['genre']; }
	            	else { $genre = $book['genre']; }

		echo "\n<td $bgcolor align=center>$genre</td>";
		echo "\n<td $bgcolor align=center> ". $book['total_pages']. " </td>";
		echo "\n<td $bgcolor align=center>$foo_cell</td>";
		echo "\n<td $bgcolor align=center> ". $book['days_avail']. " </td>";

		echo "</tr>\n";
	}

	if ($table)
	{
		echo "</table>\n";
	}
}

?>
