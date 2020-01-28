<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

# Force the DB connection into UTF8
mysqli_set_charset(DPDatabase::get_connection(), "utf8");

header('Content-type: text/plain');

$topic_table_name = PHPBB_TABLE_PREFIX . "_topics";
$forum_table_name = PHPBB_TABLE_PREFIX . "_forums";

// ------------------------------------------------------------

echo "Finding topics with unescaped HTML attributes in the title...\n";

$sql = "
    SELECT topic_id, topic_title
    FROM $topic_table_name
    WHERE
        topic_title like '%& %' OR
        topic_title like '%<%' OR
        topic_title like '%>%' OR
        topic_title like '%\"%'
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "Updating topic titles...\n";

while($row = mysqli_fetch_assoc($result))
{
    $topic_id = $row["topic_id"];
    $subject_shorter = preg_replace("/\s+/", " ", $row["topic_title"]);
    $subject_encoded = htmlspecialchars($subject_shorter, ENT_COMPAT, 'UTF-8', false);
    if(!$subject_encoded)
    {
        echo "    ERROR: Failed to encode topic_title for topic $topic_id, likely the string wasn't in UTF-8\n";
        echo "           $subject_shorter\n";
        continue;
    }
    $subject_escaped = mysqli_real_escape_string(DPDatabase::get_connection(), $subject_encoded);
    $sql = sprintf("
        UPDATE $topic_table_name
        SET topic_title = '%s'
        WHERE topic_id = %d
    ", $subject_escaped, $topic_id);
    echo "    $sql\n";

    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

echo "\n";

// ------------------------------------------------------------

echo "Finding topics with unescaped HTML attributes in the last post subject...\n";

$sql = "
    SELECT topic_id, topic_last_post_subject
    FROM $topic_table_name
    WHERE
        topic_last_post_subject like '%& %' OR
        topic_last_post_subject like '%<%' OR
        topic_last_post_subject like '%>%' OR
        topic_last_post_subject like '%\"%'
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "Updating topic last post subjects...\n";

while($row = mysqli_fetch_assoc($result))
{
    $topic_id = $row["topic_id"];
    $subject_shorter = preg_replace("/\s+/", " ", $row["topic_last_post_subject"]);
    $subject_encoded = htmlspecialchars($subject_shorter, ENT_COMPAT, 'UTF-8', false);
    if(!$subject_encoded)
    {
        echo "    ERROR: Failed to encode topic_last_post_subject for topic $topic_id, likely the string wasn't in UTF-8\n";
        echo "           $subject_shorter\n";
        continue;
    }
    $subject_escaped = mysqli_real_escape_string(DPDatabase::get_connection(), $subject_encoded);
    $sql = sprintf("
        UPDATE $topic_table_name
        SET topic_last_post_subject = '%s'
        WHERE topic_id = %d
    ", $subject_escaped, $topic_id);
    echo "    $sql\n";

    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

echo "\n";

// ------------------------------------------------------------

echo "Finding forums with unescaped HTML attributes in the last post subject...\n";

$sql = "
    SELECT forum_id, forum_last_post_subject
    FROM $forum_table_name
    WHERE
        forum_last_post_subject like '%& %' OR
        forum_last_post_subject like '%<%' OR
        forum_last_post_subject like '%>%' OR
        forum_last_post_subject like '%\"%'
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "Updating forum last post subjects...\n";

while($row = mysqli_fetch_assoc($result))
{
    $forum_id = $row["forum_id"];
    $subject_shorter = preg_replace("/\s+/", " ", $row["forum_last_post_subject"]);
    $subject_encoded = htmlspecialchars($subject_shorter, ENT_COMPAT, 'UTF-8', false);
    if(!$subject_encoded)
    {
        echo "    ERROR: Failed to encode forum_last_post_subject for forum $forum_id, likely the string wasn't in UTF-8\n";
        echo "           $subject_shorter\n";
        continue;
    }
    $subject_escaped = mysqli_real_escape_string(DPDatabase::get_connection(), $subject_encoded);
    $sql = sprintf("
        UPDATE $forum_table_name
        SET forum_last_post_subject = '%s'
        WHERE forum_id = %d
    ", $subject_escaped, $forum_id);
    echo "    $sql\n";

    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

echo "\n";

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
