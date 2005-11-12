<?PHP
$relPath = '../../../pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

echo "Altering 'projects' table...\n";

mysql_query("
    ALTER TABLE projects
        MODIFY COLUMN projectid VARCHAR(22) NOT NULL DEFAULT '',
        MODIFY COLUMN clearance TEXT NOT NULL,
        ADD PRIMARY KEY (projectid),
        ADD INDEX (state)
") or die(mysql_error());

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
