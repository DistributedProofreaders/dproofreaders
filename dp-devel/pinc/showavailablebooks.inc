<?
include_once($relPath.'project_states.inc');
include_once($relPath.'tdecho.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'gettext_setup.inc');

function show_books_available_for_proofing( $round, $userP, $tList, $where_filter = " ", $order = "DaysD" )
{

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
	elseif ( $order == 'DaysA' )
	{
		$orderclause = 'days_avail ASC, nameofwork ASC';
		$flip_days = TRUE;
	}
	elseif ( $order == 'DaysD' )
	{
		$orderclause = 'days_avail DESC, nameofwork ASC';
	}
	else
	{
		echo "showavailablebooks.inc: bad order value: '$order'";
		exit;
	}

	if ( $round == 'first' )
	{
		// Select all projects in the list for round 1., subject to filter
		// Put the BEGIN projects at the top.
		$query = "
			SELECT *, (username = 'BEGIN' OR difficulty = 'beginner') AS is_beginner,
                   round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) as days_avail
			FROM projects
			WHERE
				(state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
				state = '".PROJ_PROOF_FIRST_VERIFY."') "
				.$where_filter."
			ORDER BY
				is_beginner DESC, "
				. $orderclause."	
		";
		$linkbase = "<a href=proof_per.php?orderR1=";
	}
	else if ( $round == 'second' )
	{
		// Select all projects in the list for round 2.
		$query = "
			SELECT *, (username = 'BEGIN' OR difficulty = 'beginner') AS is_beginner,
		      round((unix_timestamp() - modifieddate)/(24 * 60 * 60)) as days_avail
			FROM projects
			WHERE
				(state = '".PROJ_PROOF_SECOND_AVAILABLE."' OR
				state = '".PROJ_PROOF_SECOND_VERIFY."') "
				.$where_filter."
			ORDER BY
				is_beginner DESC, "
				.$orderclause."
		";
		$linkbase = "<a href=proof_per.php?orderR2=";
	}

	$result = mysql_query($query);

	echo "<tr align=center ".bgcol(1,$round).">";

	$word = _("Title");
 	$link = $linkbase.($flip_title?"TitleD":"TitleA").">";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Author");
 	$link = $linkbase.($flip_author?"AuthorD":"AuthorA").">";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Language");
 	$link = $linkbase.($flip_lang?"LangD":"LangA").">";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Genre");
 	$link = $linkbase.($flip_genre?"GenreD":"GenreA").">";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Project Manager");
 	$link = $linkbase.($flip_PM?"PMD":"PMA").">";
	echo "\n<td>$link<b>$word</b></a></td>";

	$word = _("Available Pages");
	echo "\n<td><b>$word</b></td>";

	$word = _("Total Pages");
	echo "\n<td><b>$word</b></td>";

	$word = _("Days");
 	$link = $linkbase.($flip_days?"DaysD":"DaysA").">";
	echo "\n<td>$link<b>$word</b></a></td>";

	echo "</tr>";

	global $pageCountArray;
	$numrows = mysql_num_rows($result);
	$rownum = 0;
	$rownum2 = 0;

	if (empty($pageCountArray)) {
		update_pageCountArray();
	}

	while ($rownum2 < $numrows) {
		$book=mysql_fetch_assoc($result);
		$bgcolor=bgcol($rownum,$round);

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
			// green for India
			if (  startswith( $book['comments'], 'SPECIAL: India') )
			{
				$bgcolor = " bgcolor='#33FF99'";
			}

			// poppy red for Armistice Day, pale red for Christmas
			if ( startswith( $book['comments'], 'SPECIAL: Nov 11' ) or
 				startswith( $book['comments'], 'SPECIAL: CHRISTMAS' ) or
				startswith( $book['comments'], 'SPECIAL: Christmas' ))
			{
				$bgcolor = " bgcolor='#FF9090'";
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


			// yellow for Children's Book Week
			if ( startswith( $book['comments'], 'SPECIAL: CBW' ) )
			{
				$bgcolor = " bgcolor='#FFFF33'";
			}

                               // nice green for Abolition of Slavery and IWD
                        if ( startswith( $book['comments'], 'SPECIAL: Abolition' )  OR startswith( $book['comments'], 'SPECIAL: IWD'))
                        {
                                $bgcolor = " bgcolor='#00CC99'";
                        }

	               // blue for Wright Bros
                        if ( startswith( $book['comments'], 'SPECIAL: FLY100' ) )
                        {
                                $bgcolor = " bgcolor='#0099FF'";
                        }

	               // middle grey for Black History Month
                        if ( startswith( $book['comments'], 'SPECIAL: BHM' ) )
                        {
                                $bgcolor = " bgcolor='#D6D600'";
                        }

			// pink for Saint Valentine's Day
                        if ( startswith( $book['comments'], 'SPECIAL: St. Val' ) or
				startswith( $book['comments'], 'SPECIAL: April Fool' ))
                        {
                                $bgcolor = " bgcolor='#FF99FF'";
                        }



		}

		if (($round == 'first'  && $book['state'] == PROJ_PROOF_FIRST_AVAILABLE) ||
		    ($round == 'second' && $book['state'] == PROJ_PROOF_SECOND_AVAILABLE))
		{
			echo "<tr $bgcolor>";
			if ($tList==0)
				{$eURL="proof.php";}
			else
				{$eURL="projects.php";}
			$eURL.="?project={$book['projectid']}&amp;proofstate={$book['state']}";
			if ($userP['i_newwin']==0)
				{$temp="<a href=\"$eURL\">";}
			else
				{$temp="<a href=\"#\" onclick=\"newProofWin('$eURL')\">";}
			$temp.="{$book['nameofwork']}</a>";
			echo "\n<td>$temp</td>";
			echo "\n<td>{$book['authorsname']}</td>";
			echo "\n<td>{$book['language']}</td>";
			if ($book['difficulty'] == "beginner")
			{
				if  ($round == "first")
					$genre = _("BEGINNERS ONLY")." ".$book['genre'];
				else
					$genre = _("MENTORS ONLY")." ".$book['genre'];
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
			if ($pageCountArray[$book['projectid']]['avail_pages'] == 0) {
				update_total_pages($book['projectid'], 1);
				update_pageCountArray;
			}

			echo "\n<td align=center>{$pageCountArray[$book['projectid']]['avail_pages']}</td>";
			echo "\n<td align=center>{$pageCountArray[$book['projectid']]['total_pages']}</td>";

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

