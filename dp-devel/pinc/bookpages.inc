<?PHP
function bookpages($project,$state)
{
$dbQ = mysql_query("SELECT count(fileid) AS totalpages FROM $project");
if ($dbQ != "") { $book['total']=mysql_result($dbQ,0,"totalpages"); } else $book['total'] = -1;
$dbQ=mysql_query("SELECT count(fileid) AS availpages FROM $project WHERE state$state");
if ($dbQ != "") { $book['available']=mysql_result($dbQ,0,"availpages"); } else $book['available'] = -1;
return $book;
}
?>