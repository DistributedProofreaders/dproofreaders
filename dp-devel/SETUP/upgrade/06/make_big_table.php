<?
set_time_limit(0);
error_reporting(0);

$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$result = mysql_query("SELECT projectid FROM projects");

$numProjs = mysql_num_rows($result);
$numOK = 0;
$numBad = 0;
$numPartial = 0;

while ($row = mysql_fetch_assoc($result)) {
    $result1 = mysql_query("SELECT * FROM ".$row['projectid']."");
    $numPages = mysql_num_rows($result1);
    if ($result1 == FALSE || $numPages == 0) {
        echo $row['projectid']." -- Not moved due to either missing table or 0 rows<br>";
        $numBad++;
    } else {
    // DAK - Keep project id for now.
        // $projectid = substr($row['projectid'], -13);
        $projectid = $row['projectid'];

        $result2 = mysql_query( 
            "INSERT project_pages 
            SELECT '".$projectid."', fileid , image , master_text ,
            round1_text , round2_text , round1_user , round2_user , 
            round1_time , round2_time , state , b_user , b_code , 
            NULL, NULL 
            FROM ".$projectid ) ;

        echo $row['projectid']." -- Moved to project_pages table (".$numPages." pages copied)<br>";
        $numOK++;

    }
}

echo "<br><h1><center><b>Finished!</b></center></h1><br>";
echo "<br><br><br><h1><center><b>Out of ".$numProjs." projects:<br>";
echo $numOK." OK, and ".$numBad." bad.</b></center></h1><br>";

// vim: sw=4 ts=4 expandtab
?>
