<?
include_once($relPath.'tdecho.inc');
include_once($relPath.'bookpages.inc');

function showbooklistheader($round) {
echo "<tr>";
tde('<b>'._("Name&nbsp;of&nbsp;Work").'</b>',bgcol(1,$round));
tde('<b>'._("Author").'</b>',bgcol(1,$round) . " align=center");
tde('<b>'._("Language").'</b>',bgcol(1,$round) . " width=70");
tde('<b>'._("Genre").'</b>',bgcol(1,$round) . "70");
tde('<b>'._("Project<BR>Manager").'</b>',bgcol(1,$round) . "60");
tde('<b>'._("Pages available for proofing").'</b>',bgcol(1,$round) . "100");
tde('<b>'._("Total pages in the book").'</b>',bgcol(1,$round) . "100");
echo "</tr>";
}

function showavailablebooks($result,$state,$userP,$tList,$round) {
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
            ($state==AVAIL_SECOND && $book['state'] == PROJ_PROOF_SECOND_AVAILABLE)) {
            echo "<tr>";
            if ($tList==0)
              {$eURL="proof";}
            else
              {$eURL="projects";}
            $eURL.=".php?project={$book['projectid']}&amp;proofstate={$book['state']}";
              if ($userP['i_newwin']==0)
                {$temp="<a href=\"$eURL\">";}
              else
                {$temp="<a href=\"#\" onclick=\"newProofWin('$eURL')\">";}
            $temp.="{$book['nameofwork']}</a>";
            tde($temp, bgcol($rownum, $round));
            tde($book['authorsname'], bgcol($rownum, $round));
            tde($book['language'], bgcol($rownum, $round));
            	if ($book['difficulty'] == "beginner") {
                    if  ($round == "first") 
                      $genre = "BEGINNERS ONLY ".$book['genre'];
                    else 
                      $genre = "MENTORS ONLY ".$book['genre'];  }
            	elseif ($book['difficulty'] == "easy") { $genre = "Easy ".$book['genre']; }
            	elseif ($book['difficulty'] == "hard") { $genre = "Hard ".$book['genre']; }
            	else { $genre = $book['genre']; }
            tde($genre, bgcol($rownum, $round));
            tde($book['username'],bgcol($rownum, $round));
            tde($pageCountArray[$book['projectid']]['avail_pages'], bgcol($rownum, $round) . " align=center");
            tde($pageCountArray[$book['projectid']]['total_pages'], bgcol($rownum, $round) . " align=center");
        } else $rownum--;
        $rownum++;
        $rownum2++;
    }
}
?>
