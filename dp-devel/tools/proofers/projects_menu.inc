<table border=1 width=630><tr>
<td width=126 bgcolor="CCCCCC" align=center><a href ="proof_per.php">Back</a></td>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
if ($userP['i_prefs']==0)
{
  echo "<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\" colspan=\"2\">";
  echo "<a href=\"../../userprefs.php?project=$project\">Please set Interface Preferences before proofing</A>";
  echo "</td>";
}
else {
  if ($projectinfo->availablepages > 0) {
  $pURL="proof.php?project=".$project."&amp;prooflevel=".$prooflevel;
  echo "<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\" colspan=\"2\">";
  if ($userP['i_newwin']==0)
  {echo "<a href=\"$pURL\">";}
  else {echo "<a href=\"#\" onclick=\"newProofWin('$pURL')\">";}
  echo "Start Proofing</a></td>";
     }
  else {
  echo "<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\"></td>";
  echo "<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\"></td>";
     }
}
?>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
</tr></table>
