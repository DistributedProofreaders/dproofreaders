<?PHP
function bookpages($project,$state)
{
$dbQ = mysql_query("SELECT fileid FROM $project");
$book['total']=mysql_num_rows($dbQ);
mysql_query("SELECT fileid FROM $project WHERE state$state");
$book['available']=mysql_num_rows($dbQ);
return $book;
}
?>