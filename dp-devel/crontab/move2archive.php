<?
//Moves archived projects out of the active db into dp_archive

$relPath="./../pinc/";
include($relPath.'dp_main.inc');


//get projects that have archived flag set

$result = mysql_query("SELECT projectid
                     FROM projects
                     WHERE archived = '1'");

$numrows = mysql_numrows($result);
$rownum = 0;
echo "number of rows is $numrows<br>";
  
while ($rownum < $numrows) {

    $projectid = mysql_result($result, $rownum, "projectid");

    echo "moving project number $rownum which is $projectid<br>";

    $sql = "CREATE TABLE dp_archive.$projectid SELECT * FROM $projectid";
    echo mysql_errno().": ".mysql_error()."<BR>";
    $moveresult = mysql_query($sql);


    $sql = "DROP TABLE IF EXISTS $projectid";
    $dropresult = mysql_query($sql);

    $rownum++;
}


?>
