<?

// This is an ad hoc file for testing things on the server,
// for developers who don't have shell accounts on it.

$relPath='./pinc/';
include($relPath.'connect.inc');
new dbConnect();

echo "<pre>\n";

echo date("r");
echo "<BR>\n";
system("date");
echo "<BR>\n";
echo "<hr>\n";

system("pwd");
echo "<BR>\n";
echo $_ENV['PWD'];
echo "<BR>\n";
echo "<hr>\n";

system("ls -l /0/htdocs");
echo "<hr>\n";

$res = mysql_query("DESCRIBE projects")
	    or die(mysql_error());
while( $project_row = mysql_fetch_array($res) )
{
    echo $project_row, "\n";
}

if (0)
{
    $project_cutoff_ts = gmmktime(0,0,0,1,2,2003);
    $res = mysql_query("SELECT projectid FROM projects WHERE modifieddate >= $project_cutoff_ts" )
	    or die(mysql_error());

    while( $project_row = mysql_fetch_array($res) )
    {
	list($projectid) = $project_row;

	echo $projectid;
	echo " ";
	$res2 = mysql_query("SELECT COUNT(*) FROM $projectid");
	if (!$res2)
	{
	    echo mysql_error();
	}
	else
	{
	    list($n_pages) = mysql_fetch_array($res2);
	    echo $n_pages;
	}
	echo "\n";
    }
    echo "<hr>\n";
}

$res = mysql_query("SELECT username FROM users") or die(mysql_error());
while( $user_row = mysql_fetch_array($res))
{
    list($username) = mysql_fetch_array($res);
    echo $username, "\n";
}


echo "</pre>\n";
?>
