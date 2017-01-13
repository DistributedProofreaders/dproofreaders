<?php
set_time_limit(0);
error_reporting(0);

$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');

$result = mysqli_query(DPDatabase::get_connection(), "SELECT projectid FROM projects");

$numProjs = mysqli_num_rows($result);
$numOK = 0;
$numBad = 0;
$numPartial = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $result1 = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM ".$row['projectid']."");
    $numPages = mysqli_num_rows($result1);
    if ($result1 == FALSE || $numPages == 0) {
        echo $row['projectid']." -- Not moved due to either missing table or 0 rows\n";
        $numBad++;
    } else {
    // DAK - Keep project id for now.
        // $projectid = substr($row['projectid'], -13);
        $projectid = $row['projectid'];

        $columns_for_rounds = "";
        for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
        {
            $round = get_Round_for_round_number($rn);
            $columns_for_rounds .= "
                {$round->time_column_name} ,
                {$round->user_column_name} ,
                {$round->text_column_name} ,
            ";
        }
        $result2 = mysqli_query(DPDatabase::get_connection(),
            "INSERT project_pages 
            SELECT '".$projectid."', fileid , image , master_text ,
            $columns_for_rounds
            state , b_user , b_code , 
            NULL, NULL 
            FROM ".$projectid ) ;

        echo $row['projectid']." -- Moved to project_pages table (".$numPages." pages copied)\n";
        $numOK++;

    }
}

echo "\n\n\nOut of $numProjs projects:\n";
echo "$numOK OK, and $numBad bad.\n";
echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
