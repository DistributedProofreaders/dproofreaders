<?
include_once($relPath.'project_states.inc');
include_once($relPath.'page_states.inc');
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
		$state = AVAIL_FIRST;
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
		$state = AVAIL_SECOND;
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
		if (($state==AVAIL_FIRST && $book['state'] == PROJ_PROOF_FIRST_AVAILABLE) ||
		    ($state==AVAIL_SECOND && $book['state'] == PROJ_PROOF_SECOND_AVAILABLE))
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
			tde($temp, bgcol($rownum, $round));
			tde($book['authorsname'], bgcol($rownum, $round));
			tde($book['language'], bgcol($rownum, $round));
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
			tde($genre, bgcol($rownum, $round));
			tde($book['username'],bgcol($rownum, $round));
			tde($pageCountArray[$book['projectid']]['avail_pages'], bgcol($rownum, $round) . " align=center");
			tde($pageCountArray[$book['projectid']]['total_pages'], bgcol($rownum, $round) . " align=center");
		}
		else
		{
			$rownum--;
		}
		$rownum++;
		$rownum2++;
	}

}
?>
