<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Replacing template references in project comments with their contents...\n";

$sql = "
    SELECT projectid, comments
    FROM projects
    WHERE comments like '%[template=%'
    ORDER BY projectid
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

while(list($projectid, $comments) = mysqli_fetch_row($result))
{
    // handle all templates in the comment
    while(preg_match("/\[template=([^\]]+)\]/", $comments, $matches))
    {
        $template = $matches[1];
        $template_path = "$relPath/templates/comment_files/$template";
        if(!is_file($template_path))
        {
            echo "ERROR: template '$template' referenced in $projectid but does not exist on the filesystem; skipping.\n";
            continue 2;
        }
        $comments = str_replace("[template=$template]", file_get_contents($template_path), $comments);
    }

    echo "Updating $projectid...\n";

    $sql = sprintf("
        UPDATE projects
        SET comments = '%s'
        WHERE projectid = '%s'
    ", DPDatabase::escape($comments), DPDatabase::escape($projectid));

    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
