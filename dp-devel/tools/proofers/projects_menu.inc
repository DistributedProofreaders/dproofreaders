<table border=1 width=630><tr>
<td width=126 bgcolor="CCCCCC" align=center><?PHP
  if ($userP['i_newwin']==0)
    {echo "<a href =\"proof_per.php\" target=\"_top\">";}
  else
    {echo "<a href =\"#\" onclick=\"top.window.opener.location='proof_per.php';top.window.close()\">";}
?>Personal Page</a></td>
<td width=126 bgcolor="CCCCCC" align=center><a href ="<? echo $forums_url ?>/index.php" target="forumwin">Forums</a></td>
<?
  $setT="<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\">";
  $setU="project=".$project."&amp;proofstate=".$proofstate;
  $setP="<a href=\"../../userprefs.php?".$setU."\">Interface Preferences</a>";

  if ($projectinfo->availablepages > 0)
    {
      $pURL="proof_frame.php?".$setU;
      echo $setT."<b>";
      echo "<a href=\"$pURL\" target=\"proofframe\">";
      echo "Start Proofing</a></b></td>";
    }
  else
    {
      echo $setT;
      echo "Round Complete</td>";
    }
  echo $setT;
  echo $setP."\r\n</td>";
  echo "<td width=\"126\" bgcolor=\"#CCCCCC\" align=\"center\">";
  if ($userP['i_newwin']==0)
    {echo "<a href =\"../logout.php\">";}
  else
    {echo "<a href =\"#\" onclick=\"top.window.opener.location='../logout.php';top.window.close();\">";}
  echo "Logout</a></td>";
?>
</tr><tr><td colspan="5" align="center"><a href="list_avail.php">View Available Projects</a></td></table>