<?PHP
function bookpages($project,$state)
{
  $dbQ = mysql_query("SELECT count(fileid) AS totalpages FROM $project");
  if ($dbQ != "") { $book['total']=mysql_result($dbQ,0,"totalpages"); } else $book['total'] = -1;
  $dbQ=mysql_query("SELECT count(fileid) AS availpages FROM $project WHERE state$state");
  if ($dbQ != "") { $book['available']=mysql_result($dbQ,0,"availpages"); } else $book['available'] = -1;
  return $book;
}
function total_pages($project)
{
  // todo: populate and use a table when project has been posted
  $dbQ = mysql_query("SELECT count(fileid) AS totalpages FROM $project");
  if ($dbQ != "") { $total = mysql_result($dbQ,0,"totalpages"); } else $total = -1;
  return $total;
}
function avail_pages($project)
{
   static $last_updated = 1059740561;
   static $avail_array = array("projectIDseed" => -1);
   $now = time();
   // if array hasn't had a general refresh in last half hour, do so now
   if (($now - $last_updated) > 1800) {
      $last_updated = $now;
      $i = 0;
      // only refresh counts for projects currently available for proofing
      $avail_projs = mysql_query("SELECT projectid FROM projects WHERE state IN ('".PROJ_PROOF_FIRST_AVAILABLE."', '".PROJ_PROOF_SECOND_AVAILABLE."')");
      $num_avail_projs = mysql_num_rows($avail_projs);
      while ($i < $num_avail_projs) {
         $currProject = mysql_result($avail_projs,$i,0);
         $booknums = bookpages($project," IN ('".AVAIL_FIRST."','".AVAIL_SECOND."')");   
         $avail_array[$project] = $booknums['available'];
         $i++;
      } 
   } else {
   // else if THIS project was updated in last 30 min refresh it alone
   // (it may have just arrived in round two, say)
   $dbQ = mysql_query("SELECT modifieddate AS lastchanged FROM projects WHERE projectid = $project");
   if ($dbQ != "") { $lastchanged = mysql_result($dbQ,0,"lastchanged"); } else $lastchanged = 0;
      if ($lastchanged > 0) {
         if (($now - $last_changed) < 1800)) {
            $booknums = bookpages($project," IN ('".AVAIL_FIRST."','".AVAIL_SECOND."')");   
            $avail_array[$project] = $booknums['available'];
         }
      }
   }
   // if we have a saved total already in the array for this project, report it
   if (array_key_exists($project, $avail_array)) {
      return $avail_array[$project]
   } else {
   // else count and return total, saving it in array for next time
     $booknums = bookpages($project," IN ('".AVAIL_FIRST."','".AVAIL_SECOND."')");   
     $avail_array[$project] = $booknums['available'];
     return $booknums['available'];
   }
}
?>