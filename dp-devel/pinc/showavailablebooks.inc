<?
include_once($relPath.'project_states.inc');
include_once($relPath.'tdecho.inc');
include_once($relPath.'bookpages.inc');

function show_books_available_for_proofing( $round, $userP, $tList )
{
	if ( $round == 'first' )
	{
		// Select all projects in the list for round 1.
		// Put the BEGIN projects at the top.
		$query = "
			SELECT *, (username = 'BEGIN' OR difficulty = 'beginner') AS is_beginner
			FROM projects
			WHERE
				state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR
				state = '".PROJ_PROOF_FIRST_VERIFY."'
			ORDER BY
				is_beginner DESC,
				modifieddate DESC,
				nameofwork ASC
		";
	}
	else if ( $round == 'second' )
	{
		// Select all projects in the list for round 2.
		$query = "
			SELECT *
			FROM projects
			WHERE
				state = '".PROJ_PROOF_SECOND_AVAILABLE."' OR
				state = '".PROJ_PROOF_SECOND_VERIFY."'
			ORDER BY
				nameofwork ASC
		";
	}

	$result = mysql_query($query);

	echo "<tr>";
	tde('<b>'._("Name&nbsp;of&nbsp;Work").'</b>',bgcol(1,$round));
	tde('<b>'._("Author").'</b>',bgcol(1,$round) . " align=center");
	tde('<b>'._("Language").'</b>',bgcol(1,$round) . " width=70");
	tde('<b>'._("Genre").'</b>',bgcol(1,$round) . "70");
	tde('<b>'._("Project<BR>Manager").'</b>',bgcol(1,$round) . "60");
	tde('<b>'._("Pages available for proofing").'</b>',bgcol(1,$round) . "100");
	tde('<b>'._("Total pages in the book").'</b>',bgcol(1,$round) . "100");
	echo "</tr>";

	global $pageCountArray;
	$numrows = mysql_num_rows($result);
	$rownum = 0;
	$rownum2 = 0;

	if (empty($pageCountArray)) {
		update_pageCountArray();
	}

	while ($rownum2 < $numrows) {
		$book=dassoc($result);
		$bgcolor=bgcol($rownum,$round);

		// Special colours for special books of various types
		if (1)
		{

			// will be nice one day to get all the following out of a table perhaps?

                        // default Special colour (will be over-ridden by any specific matches below)

			if ( startswith( $book['comments'], 'SPECIAL:' ) )
			{
				$bgcolor = " bgcolor='#9933CC'";
			}

			// Halloween colors for Halloween projects!
			if ( startswith( $book['comments'], 'Halloween' ) )
			{
				$bgcolor = " bgcolor='orange'";
			}
			// Wanted to do orange-on-black, too,
			// but couldn't figure out how to make link-text orange,
			// and it was pretty unreadable as is on a black background.

			// green for Ramadan
			if ( startswith( $book['comments'], 'SPECIAL: Ramadan' ) )
			{
				$bgcolor = " bgcolor='#00FF33'";
			}

			// poppy red for Armistice Day
			if ( startswith( $book['comments'], 'SPECIAL:Nov 11' ) )
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

			// Native Anerican Heritage Month 
			if ( startswith( $book['comments'], 'SPECIAL: NAHM' ) )
			{
				$bgcolor = " bgcolor='#CCCCCC'";
			}

			

		}

		if (($round == 'first'  && $book['state'] == PROJ_PROOF_FIRST_AVAILABLE) ||
		    ($round == 'second' && $book['state'] == PROJ_PROOF_SECOND_AVAILABLE))
		{
			echo "<tr>";
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
			tde($temp, $bgcolor);
			tde($book['authorsname'], $bgcolor);
			tde($book['language'], $bgcolor);
			if ($book['difficulty'] == "beginner")
			{
				if  ($round == "first") 
					$genre = "BEGINNERS ONLY ".$book['genre'];
				else 
					$genre = "MENTORS ONLY ".$book['genre'];
			}
			elseif ($book['difficulty'] == "easy")
			{
				$genre = "EASY ".$book['genre'];
			}
			elseif ($book['difficulty'] == "hard")
			{
				$genre = "HARD ".$book['genre'];
			}
			else
			{
				$genre = $book['genre'];
			}
			tde($genre, $bgcolor);
			tde($book['username'],$bgcolor);
			tde($pageCountArray[$book['projectid']]['avail_pages'], $bgcolor . " align=center");
			tde($pageCountArray[$book['projectid']]['total_pages'], $bgcolor . " align=center");
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
