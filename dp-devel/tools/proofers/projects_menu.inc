<table border=1 width=630><tr>
<td width=126 bgcolor="CCCCCC" align=center><a href ="proof_per.php">Back</a></td>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
if ($projectinfo->availablepages > 0) {
  echo "<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\">";
  echo "Standard Proofing Interface<BR>";
  echo "<a href=\"proof.php?project=".$project."&prooflevel=".$prooflevel."&amp;orient=vert&amp;js=0\"><IMG SRC=\"gfx/bt4.png\" TITLE=\"Vertical Proofing\" ALT=\"Vertical Proofing\" BORDER=\"0\"></a> ";
  echo "<a href=\"proof.php?project=".$project."&prooflevel=".$prooflevel."&amp;orient=hrzn&amp;js=0\"><IMG SRC=\"gfx/bt5.png\" TITLE=\"Horizontal Proofing\" ALT=\"Horizontal Proofing\" BORDER=\"0\"></a></td>";
  echo "<SCRIPT LANGUAGE=\"JavaScript\" TYPE=\"text/javascript\">\r\n";
  echo "<!-- \r\n";
  echo "iCan=0;\r\n";
  echo "if (!iCan)\r\n";
  echo "{document.open();document.write('<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\">');\r\n";
  echo "document.write('Enhanced Proofing Interface<BR>\\r\\n');\r\n";
  echo "document.write('<a href=\"#\" onclick=\"newProofWin(\'proof.php?project=$project"."&amp;prooflevel=".$prooflevel."&amp;orient=vert&amp;js=1\')\"><IMG SRC=\"gfx/bt4.png\" TITLE=\"Vertical Proofing\" ALT=\"Vertical Proofing\" BORDER=\"0\"></a> ')\r\n";
  echo "document.write('<a href=\"#\" onclick=\"newProofWin(\'proof.php?project=$project"."&amp;prooflevel=".$prooflevel."&amp;orient=hrzn&amp;js=1\')\"><IMG SRC=\"gfx/bt5.png\" TITLE=\"Horizontal Proofing\" ALT=\"Horizontal Proofing\" BORDER=\"0\"></a></td>');}\r\n";
  echo "// -->\r\n</SCRIPT>";
} else {
  echo "<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\"></td>";
  echo "<td width=\"252\" bgcolor=\"#CCCCCC\" align=\"center\"></td>";
}
?>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
</tr></table>
