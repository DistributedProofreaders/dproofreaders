<?

// This is an ad hoc file for testing things on the server,
// for developers who don't have shell accounts on it.

$relPath='./pinc/';
include($relPath.'f_dpsql.inc');
include($relPath.'connect.inc');
new dbConnect();

echo "<pre>\n";

echo date("r");
echo "<BR>\n";
system("date");
echo "<BR>\n";
echo "<hr>\n";

if (0)
{
    system("pwd");
    echo "\n";
    system("ls -l .");
    echo "<hr>\n";

    system("ls -l /0/htdocs");
    echo "\n";
    echo "<hr>\n";
}

if (1)
{
    $project_cutoff_ts = gmmktime(0,0,0,1,2,2003);
    $criterion = "modifieddate >= $project_cutoff_ts";
    $criterion = "archived='0'";
    $criterion = "1";
    $res = mysql_query("SELECT projectid FROM projects WHERE $criterion")
	    or die(mysql_error());

    while( $project_row = mysql_fetch_array($res) )
    {
	list($projectid) = $project_row;

	echo $projectid;
	echo " ";
	# $res2 = mysql_query("SELECT COUNT(*) FROM $projectid");
	$res2 = mysql_query("SELECT COUNT(*), COUNT(DISTINCT(fileid)), COUNT(DISTINCT(image)) FROM $projectid");
	if (!$res2)
	{
	    echo mysql_error();
	}
	else
	{
	    list($n_pages,$n_distinct_fileid,$n_distinct_image) = mysql_fetch_array($res2);
	    echo "$n_pages $n_distinct_fileid $n_distinct_image";
	}
	echo "\n";
    }
    echo "<hr>\n";
}

if (0)
{
    $res = mysql_query("SELECT username FROM users") or die(mysql_error());
    while( $user_row = mysql_fetch_array($res))
    {
	list($username) = mysql_fetch_array($res);
	echo $username, "\n";
    }
    echo "<hr>\n";
}


echo "</pre>\n";

if (0)
{
    $res = mysql_query("DESCRIBE projects")
		or die(mysql_error());
    dpsql_dump_query_result($res);
    echo "<HR>\n";
}

if (0)
{
    $res = mysql_query("SELECT projectID, modifieddate FROM projects WHERE archived='1' ORDER BY modifieddate")
	or die(mysql_error());
    dpsql_dump_query_result($res);

    $res = mysql_query("SELECT projectID, modifieddate FROM projects WHERE archived='0' ORDER BY modifieddate")
	or die(mysql_error());
    dpsql_dump_query_result($res);
}
?>
