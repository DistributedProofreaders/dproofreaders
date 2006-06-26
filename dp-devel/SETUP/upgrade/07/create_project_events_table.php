<?php
$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

echo "Creating 'project_events' table...\n";
$sql = "
    CREATE TABLE project_events
    (
        event_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
        timestamp  INT UNSIGNED NOT NULL,
        projectid  VARCHAR(22)  NOT NULL,
        who        VARCHAR(25)  NOT NULL,
        event_type VARCHAR(15)  NOT NULL,
        details1   VARCHAR(255) NOT NULL,
        details2   VARCHAR(255) NOT NULL,
        details3   VARCHAR(255) NOT NULL,

        PRIMARY KEY  (event_id),
        KEY project  (projectid)
    )
";

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";
?>

