<?PHP
function bookpages($project,$state)
{
$dbQ = mysql_query("SELECT fileid FROM $project");
if ($dbQ != "") { $book['total']=mysql_num_rows($dbQ); } else $book['total'] = -1;
$dbQ=mysql_query("SELECT fileid FROM $project WHERE state$state");
if ($dbQ != "") { $book['available']=mysql_num_rows($dbQ); } else $book['available'] = -1;
return $book;
}
?>