<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Replacing biography references in project comments with their contents...\n";

$sql = "
    SELECT projectid, comments
    FROM projects
    WHERE comments like '%[biography=%'
    ORDER BY projectid
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

while ([$projectid, $comments] = mysqli_fetch_row($result)) {
    // handle all templates in the comment
    while (preg_match("/\[biography=([^\]]+)\]/", $comments, $matches)) {
        $template = $matches[1];
        $bio = _get_biography($template);
        $comments = str_replace("[biography=$template]", $bio, $comments);
    }

    echo "Updating $projectid...\n";

    $sql = sprintf(
        "
        UPDATE projects
        SET comments = '%s'
        WHERE projectid = '%s'
        ",
        DPDatabase::escape($comments),
        DPDatabase::escape($projectid)
    );

    mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));
}


echo "\nDone!\n";

// ------------------------------------------------------------

function _get_biography($id)
{
    $sql = sprintf(
        "
        SELECT bio, bio_format
        FROM biographies
        WHERE bio_id = %d
        ",
        $id
    );
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        throw new Exception("Nonexistent bio found: $id");
    } else {
        if ($row["bio_format"] === 'markdown') {
            $bio_text = render_markdown_as_html($row["bio"]);
        } else {
            $bio_text = sanitize_html($row["bio"], 'td');
        }
    }
    return $bio_text;
}
