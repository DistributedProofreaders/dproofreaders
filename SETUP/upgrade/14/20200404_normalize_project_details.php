<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'unicode.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Normalizing project details...";

$sql = "
    SELECT projectid, nameofwork, authorsname, comments, postcomments, extra_credits
    FROM projects
    ORDER BY projectid;
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

while($row = mysqli_fetch_assoc($result))
{
    $sql = sprintf("
        UPDATE projects SET
            nameofwork    = '%s',
            authorsname   = '%s',
            comments      = '%s',
            postcomments  = '%s',
            extra_credits = '%s'
        WHERE projectid = '%s';
        ",
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize(html_entity_decode($row["nameofwork"]))),
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize(html_entity_decode($row["authorsname"]))),
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize($row["comments"])),
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize($row["postcomments"])),
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize(html_entity_decode($row["extra_credits"]))),
        $row["projectid"]
    );

    echo "Processing " . $row["projectid"] . "\n";
    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
