<?PHP
include_once($relPath.'tdecho.inc');
function showavailablebooks($result,$state,$userP,$tList)
{
$tds="<td bgcolor=\"#CCCCCC\"";
echo "<tr>";
$tdc="</b></td>";
echo "$tds><b>Name&nbsp;of&nbsp;Work$tdc".
"$tds align=\"center\"><b>Author$tdc".
"$tds width=\"70\" align=\"center\"><b>Language$tdc".
"$tds width=\"70\" align=\"center\"><b>Genre$tdc".
"$tds width=\"60\" align=\"center\"><b>Project<BR>Manager$tdc".
"$tds width=\"100\" align=\"center\"><b>Pages available for proofing$tdc".
"$tds width=\"100\" align=\"center\"><b>Total pages in the book$tdc</tr>";
    $numrows = mysql_num_rows($result);
    $rownum = 0;
    $rownum2 = 0;
    while ($rownum2 < $numrows) {
           $book=dassoc($result);
            // find out how many files the project has total/available!!!!!
            $pages=bookpages($book['projectid'],"='{$state}'");
            $bgcolor=bgcol($rownum);

        if ($book['state'] == $state) {
            // NOTE: Leave space before <tr> in there, needed for client.
            echo " <tr>";
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
            tde($temp,$rownum,0);
            tde($book['authorsname'],$rownum,0);
            tde($book['language'],$rownum,0);
	    tde($book['genre'],$rownum,0);
            tde($book['username'],$rownum,0);
            tde($pages['available'],$rownum,1);
            tde($pages['total'],$rownum,1);
        } else $rownum--;
        $rownum++;
        $rownum2++;
    }
}
?>
