<?
  // Auto-Release Project

function autorelease($pagesleft) {

  $pagesneeded = 15000;

  while ($pagesleft < $pagesneeded) {
    $result = mysql_query("SELECT projectid FROM projects WHERE state = 1 ORDER BY modifieddate ASC LIMIT 1");
    if (mysql_num_rows($result) > 0) {
      $project = mysql_result($result, 0, "projectid");

      $level0rows = mysql_query("SELECT fileid FROM $project WHERE prooflevel = '0' ORDER BY Image_Filename ASC");
      if ($level0rows != "") { $level0pages = (mysql_num_rows($level0rows)); } else $level0pages = 0;

      $result = mysql_query("UPDATE projects SET state = 8, modifieddate = '$todaysdate' WHERE projectid = '$project'");
      $pagesleft += (2 * $level0pages);

      print "Released project = $project<BR>";
    } else {
      $pagesleft = $pagesneeded;
    }
  }
}
?>