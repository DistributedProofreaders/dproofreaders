<?PHP
include_once($relPath.'tdecho.inc');
function showbooklistheader($round)
{
echo "<tr>";
tde('<b>Name&nbsp;of&nbsp;Work</b>',bgcol(1,$round));
tde('<b>Author</b>',bgcol(1,$round) . " align=center");
tde('<b>Language</b>',bgcol(1,$round) . " width=70");
tde('<b>Genre</b>',bgcol(1,$round) . "70");
tde('<b>Project<BR>Manager</b>',bgcol(1,$round) . "60");
tde('<b>Pages available for proofing</b>',bgcol(1,$round) . "100");
tde('<b>Total pages in the book</b>',bgcol(1,$round) . "100");
echo "</tr>";
}
function showavailablebooks($result,$state,$userP,$tList,$round)
{
    $numrows = mysql_num_rows($result);
    $rownum = 0;
    $rownum2 = 0;
    while ($rownum2 < $numrows) {
           $book=dassoc($result);
            // find out how many files the project has total/available!!!!!
            $apages = avail_pages($book['projectid']);
            $tpages = total_pages($book['projectid']);
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
	    tde($book['genre'], bgcol($rownum, $round));
            tde($book['username'],bgcol($rownum, $round));
            tde($apages, bgcol($rownum, $round) . " align=center");
            tde($tpages, bgcol($rownum, $round) . " align=center");
        } else $rownum--;
        $rownum++;
        $rownum2++;
    }
}
?>
