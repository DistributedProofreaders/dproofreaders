<?
include_once($relPath.'project_states.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'gettext_setup.inc');

function show_block_for_round( $round_number, $projects_filter )
{
	global $pguser, $userP;

	$prd = get_PRD_for_round($round_number);

	$setting = "{$prd->round_name}order";

	// read saved sort orders from user_settings table 
	$result = mysql_query("
		SELECT value
		FROM usersettings
		WHERE username = '$pguser' AND setting = '$setting'"
	);
	if (mysql_num_rows($result) >= 1)
	{
		$order_old = mysql_result($result, 0, "value");
	}
	else
	{
		$order_old = 'DaysD';
	}

	// read new sort order from url, if any
	$url_param = "order{$prd->round_name}";
	$order_new = (isset($_GET[$url_param]) ? $_GET[$url_param] : $order_old );

	// if orders have changed, save them to database
	if ($order_new != $order_old)
	{
		$result = mysql_query("
			DELETE FROM usersettings
			WHERE username = '$pguser' AND setting = '$setting'
		");
		$result = mysql_query("
			INSERT INTO usersettings
			VALUES ('$pguser', '$setting', '$order_new')
		");
	}

	$result = mysql_query("
		SELECT pagescompleted
		FROM users
		WHERE username = '$pguser'
	");
	$pagescompleted = mysql_result($result,0);

	echo "\n<a name='{$prd->round_name}'></a>";
	echo "\r\n<table border=1>";
	echo "\r\n<tr bgcolor='{$prd->listing_bgcolors[1]}'>";
	echo "\n<td colspan=2><h3>{$prd->title}</h3></td>";

	$min_num_pages_to_see_this_round = $prd->minimum_user_pages;
	if ($pagescompleted < $min_num_pages_to_see_this_round)
	{
		$sorry = sprintf(
			_("These projects are unavailable until you have proofread %d first round pages.  After %d pages of first round proofreading, the second round projects will be unlocked for you."),
			$min_num_pages_to_see_this_round,
			$min_num_pages_to_see_this_round );
		echo "\n<td colspan=6>$sorry</td>";
		echo "</tr>";
	}
	else
	{
		echo "\n<td colspan=6>{$prd->description}</td>";
		echo "</tr>";

		if (! user_can_see_BEGIN_in_round($prd->round_number))
		{
			$projects_filter .= " AND difficulty != 'beginner' ";
		}

		show_books_available_for_proofing( $prd, $userP, $projects_filter, $order_new );
	}
	echo "</table>\n<br>";
}

// -----------------------------------------------------------------------------

function show_books_available_for_proofing( $prd, $userP, $where_filter = " ", $order = "DaysD" )
{

	$flip_title = FALSE;
	$flip_author = FALSE;
	$flip_lang = FALSE;
	$flip_genre = FALSE;
	$flip_PM = FALSE;
	$flip_PgAv = FALSE;
	$flip_PgTot = FALSE;
	$flip_days = FALSE;
	
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
	elseif ( $order == 'PMA' )
	{
		$orderclause = 'username ASC, nameofwork ASC';
		$flip_PM = TRUE;
	}
	elseif ( $order == 'PMD' )
	{
		$orderclause = 'username DESC, nameofwork ASC';
	}
	elseif ( $order == 'PgAvA' )
	{
		$orderclause = 'avail_pages ASC, nameofwork ASC';
		$flip_PgAv = TRUE;
	}
	elseif ( $order == 'PgAvD' )
	{
		$orderclause = 'avail_pages DESC, nameofwork ASC';
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
		echo "showavailablebooks.inc: bad order value: '$order'";
		exit;
	}


	global $pageCountArray;

	if (empty($pageCountArray)) {
		update_pageCountArray();
	}


	// make sure we have a figure for total pages of every available book

	$query = "
		SELECT projectid
		FROM projects
		WHERE state = '{$prd->project_available_state}'
	";

	$result = mysql_query($query);

	$numrows = mysql_num_rows($result);
	$rownum = 0;

	while ($rownum < $numrows) {
		$book=mysql_fetch_assoc($result);
		if ($pageCountArray[$book['projectid']]['avail_pages'] == 0) {
			project_update_page_counts( $book['projectid'] );
			update_pageCountArray();
		}
		$rownum++;
	}



	$linkbase = "<a href=proof_per.php?order{$prd->round_name}=";
	$linkend = "#{$prd->round_name}";

	// Always put the BEGIN projects at the top.
	$query = "
		SELECT *,
			(username = 'BEGIN' OR difficulty = 'beginner') AS is_beginner,
			round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) AS days_avail
		FROM projects, page_counts
		WHERE
			projects.projectid = page_counts.projectid
		       	AND (state = '{$prd->project_available_state}')
			$where_filter
		ORDER BY
			is_beginner DESC,
			$orderclause
	";
	$result = mysql_query($query);

	echo "<tr align=center bgcolor='{$prd->listing_bgcolors[1]}'>";

	$word = _("Title");
 	$link = $linkbase.($flip_title?"TitleD":"TitleA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Author");
 	$link = $linkbase.($flip_author?"AuthorD":"AuthorA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Language");
 	$link = $linkbase.($flip_lang?"LangD":"LangA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Genre");
 	$link = $linkbase.($flip_genre?"GenreD":"GenreA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Project Manager");
 	$link = $linkbase.($flip_PM?"PMD":"PMA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Available Pages");
 	$link = $linkbase.($flip_PgAv?"PgAvD":"PgAvA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Total Pages");
 	$link = $linkbase.($flip_PgTot?"PgTotD":"PgTotA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Days");
 	$link = $linkbase.($flip_days?"DaysD":"DaysA")."$linkend>";
	echo "\n<td>$link<b>$word</b></a></td>";

	echo "</tr>";

	$numrows = mysql_num_rows($result);
	$rownum = 0;
	$rownum2 = 0;

	while ($rownum2 < $numrows) {
		$book=mysql_fetch_assoc($result);
		$bgcolor=" bgcolor='{$prd->listing_bgcolors[$rownum % 2]}'";

		// Special colours for special books of various types
		if (1)
		{


                       // will be nice one day to get all the following out of a table perhaps?

                        // default Special colour (will be over-ridden by any specific matches below)

                        if ( startswith( $book['comments'], 'SPECIAL:' ) )
                        {
                                $bgcolor = " bgcolor='#FFFF66'";
                        }

                        // Halloween colors for Halloween projects!
                        if ( startswith( $book['comments'], 'Halloween' ) )
                        {
                                $bgcolor = " bgcolor='orange'";
                        }
                        // Wanted to do orange-on-black, too,
                        // but couldn't figure out how to make link-text orange,
                        // and it was pretty unreadable as is on a black background.

                        // green for Ramadan, St Patrick
                        if ( startswith( $book['comments'], 'SPECIAL: Ramadan' )
                              or startswith( $book['comments'], 'SPECIAL: St. Pat') )
                        {
                                $bgcolor = " bgcolor='#00FF00'";
                        }
                        // green for India & George Sand
                        if (  startswith( $book['comments'], 'SPECIAL: India') 
			       or ($book['username'] == 'PM-Sand') )
                        {
                                $bgcolor = " bgcolor='#33FF99'";
                        }

                        // khaki for Boer War
                        if (startswith( $book['comments'], 'SPECIAL: Boer'))
                        {
                                $bgcolor = " bgcolor='#D7CDA4'";
                        }

                        // blue for Burns' night (Scotland)
                        if (startswith( $book['comments'], 'SPECIAL: Burns'))
                        {
                                $bgcolor = " bgcolor='#99CCFF'";
                        }

                        // poppy red for Armistice Day, pale red for Christmas
                        if ( startswith( $book['comments'], 'SPECIAL: Nov 11' ) or
                                startswith( $book['comments'], 'SPECIAL: CHRISTMAS' ) or
                                startswith( $book['comments'], 'SPECIAL: Christmas' ) or
                                startswith( $book['comments'], 'SPECIAL: WWI' ) or
                                startswith( $book['comments'], 'SPECIAL: Ides' ))
                        {
                                $bgcolor = " bgcolor='#FF9090'";
                        }

                        // Earth blue for Earth Day
                        if ( startswith( $book['comments'], 'SPECIAL: Earth' ) or
				startswith( $book['comments'], 'SPECIAL: DP-5000' ))
                        {
                                $bgcolor = " bgcolor='#669CFF'";
                        }

                        // very light blue for birthday books still avaialble after the day
                        if ( startswith( $book['comments'], 'SPECIAL: Birthday' ) )
                        {
                                $bgcolor = " bgcolor='#CCFFFF'";
                        }


                         // slightly richer blue for birthday books when today IS the day
                        $bday = date('md');
                        if ( startswith( $book['comments'], "SPECIAL: Birthday $bday" ) )
                        {
                                $bgcolor = " bgcolor='#33CCFF'";
                        }

                        // Native American Heritage Month
                        if ( startswith( $book['comments'], 'SPECIAL: NAHM' ) )
                        {
                                $bgcolor = " bgcolor='#CCCCCC'";
                        }


                        // green for World Health Day
                        if ( startswith( $book['comments'], 'SPECIAL: Health' ) )
                        {
                                $bgcolor = " bgcolor='#00FF00'";
                        }

                        // yellow for Children's Book Week - actually Blue so as not to clash with other special
                        if ( startswith( $book['comments'], 'SPECIAL: CBW' )
                                OR startswith( $book['comments'], 'SPECIAL: ChBook'))
                        {
                                $bgcolor = " bgcolor='#0099FF'";
                                //$bgcolor = " bgcolor='#FFFF33'";
                        }

                               // nice green for Abolition of Slavery
                        if ( startswith( $book['comments'], 'SPECIAL: Abolition' )
                                OR startswith( $book['comments'], 'SPECIAL: IWD'))
                        {
                                $bgcolor = " bgcolor='#00CC99'";
                        }

                       // blue for Wright Bros
                        if ( startswith( $book['comments'], 'SPECIAL: FLY100' ) )
                        {
                                $bgcolor = " bgcolor='#0099FF'";
                        }

                       // middle grey for Black History Month (and Slave Narratives - temp)
                        if ( startswith( $book['comments'], 'SPECIAL: BHM' ))
                        {
                                $bgcolor = " bgcolor='#D6D6D6'";
                        }

                        // pink for Saint Valentine's Day
                        if ( startswith( $book['comments'], 'SPECIAL: St. Val' ) or
                             startswith( $book['comments'], 'SPECIAL: April Fool') )
                        {
                                $bgcolor = " bgcolor='#FF99FF'";
                        }

		}

		if (TRUE)
		{
			echo "<tr $bgcolor>";
			$eURL="proof.php";
			$eURL.="?project={$book['projectid']}&amp;proofstate={$book['state']}";
			if ($userP['i_newwin']==0)
				{$temp="<a href=\"$eURL\">";}
			else
				{$temp="<a href=\"$eURL\" onclick=\"newProofWin('$eURL'); return false;\">";}
			$temp.="{$book['nameofwork']}</a>";
			echo "\n<td>$temp</td>";
			echo "\n<td>{$book['authorsname']}</td>";
			echo "\n<td>{$book['language']}</td>";
			if ($book['difficulty'] == "beginner")
			{
				if  ($prd->round_number == 1)
					$genre = _("BEGINNERS ONLY")." ".$book['genre'];
				else if ($prd->round_number == 2)
					$genre = _("MENTORS ONLY")." ".$book['genre'];
				else
					$genre = $book['genre'];
			}
			elseif ($book['difficulty'] == "easy")
			{
				$genre = _("EASY")." ".$book['genre'];
			}
			elseif ($book['difficulty'] == "hard")
			{
				$genre = _("HARD")." ".$book['genre'];
			}
			else
			{
				$genre = $book['genre'];
			}
			echo "\n<td>$genre</td>";
			echo "\n<td>{$book['username']}</td>";

			echo "\n<td align=center>{$book['avail_pages']}</td>";
			echo "\n<td align=center>{$book['total_pages']}</td>";

			echo "\n<td align=center>{$book['days_avail']}</td>";

		}
		else
		{
			$rownum--;
		}
		$rownum++;
		$rownum2++;
	}

}

function startswith( $subject, $prefix )
// Return TRUE iff $subject starts with $prefix.
{
	return ( strncmp( $subject, $prefix, strlen($prefix) ) == 0 );
}

?>
