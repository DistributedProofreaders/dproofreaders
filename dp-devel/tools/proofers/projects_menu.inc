<table border=1 width=630><tr>
<td width=126 bgcolor="CCCCCC" align=center><a href ="proof_per.php">Back</a></td>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
$setT="<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\">";
$setU="project=".$project."&amp;proofstate=".$proofstate;
$setP="<a href=\"../../userprefs.php?".$setU."\">Interface Preferences</a>";

if ($projectinfo->availablepages > 0) {
    $pURL="proof.php?".$setU;
    echo $setT."<b>";
    if ($userP['i_newwin']==0)
    {echo "<a href=\"$pURL\">";}
    else {echo "<a href=\"#\" onclick=\"newProofWin('$pURL')\">";}
    echo "Start Proofing</a></b></td>";
} else {
    echo $setT;
    echo "Round Complete</td>";
}
echo $setT;
echo $setP."\r\n</td>";
?>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
</tr></table>
