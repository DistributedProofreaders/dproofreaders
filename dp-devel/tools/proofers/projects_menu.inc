<table border=1 width=630><tr>
<td width=126 bgcolor="CCCCCC" align=center><a href ="proof_per.php" target="_top">Personal Page</a></td>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
$setT="<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\">";
$setU="project=".$project."&amp;proofstate=".$proofstate;
$setP="<a href=\"../../userprefs.php?".$setU."\">Interface Preferences</a>";

if ($projectinfo->availablepages > 0) {
    $pURL="proof_frame.php?".$setU;
    echo $setT."<b>";
    echo "<a href=\"$pURL\" target=\"proofframe\">";
    echo "Start Proofing</a></b></td>";
} else {
    echo $setT;
    echo "Round Complete</td>";
}
echo $setT;
echo $setP."\r\n</td>";
?>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
</tr><tr><td colspan="5" align="center"><a href="list_avail.php">View Available Projects</a></td></table>