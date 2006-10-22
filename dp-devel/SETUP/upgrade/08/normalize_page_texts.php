<?php

// Go to every version of every page in every project
// and ensure that its text is normalized.

$relPath='../../../pinc/';
include($relPath.'dpsql.inc');
include($relPath.'connect.inc');
include($relPath.'DPage.inc'); // _normalize_page_text
new dbConnect();

header('Content-type: text/plain');

echo "...\n";
$res = dpsql_query("
    SELECT projectid
    FROM projects
    WHERE archived != 1 AND state != 'project_delete'
    ORDER BY projectid
");

$n_projects = mysql_num_rows($res);

$n_total_texts_changed = 0;
$n_total_pages_changed = 0;
$n_total_projects_changed = 0;

$i = 0;
while ( list($projectid) = mysql_fetch_row($res) )
{
    $i++;
    echo sprintf( "%4d/%4d: %s:", $i, $n_projects, $projectid );

    $res2 = mysql_query("
        SELECT * FROM $projectid
        ORDER BY image
    ");
    if ( $res2 === FALSE )
    {
        echo " odd, SELECT failed\n";
        continue;
    }
    echo sprintf( " (%3d pages)", mysql_num_rows($res2) );

    $n_texts_changed = 0;
    $n_pages_changed = 0;

    while ( $page = mysql_fetch_assoc($res2) )
    {
        $image = $page['image'];
        $changes = array();
        foreach ( $page as $field_name => $field_value )
        {
            if ( preg_match( '/^(master|round\d+)_text$/', $field_name ) )
            {
                // This is a page-text field.
                $normalized_page_text = _normalize_page_text($field_value);
                if ( $normalized_page_text != $field_value )
                {
                    if (0)
                    {
                        echo "\n";
                        echo "$image $field_name:\n";
                        echo "changed ", addcslashes($field_value,         "\0..\37"), "\n";
                        echo "to      ", addcslashes($normalized_page_text,"\0..\37"), "\n";
                    }

                    $changes[] = sprintf( "%s = '%s'",
                        $field_name,
                        mysql_escape_string($normalized_page_text)
                    );
                }
            }
        }

        if ( count($changes) > 0 )
        {
            $changes_str = implode(",\n", $changes);

            $sql = "
                UPDATE $projectid
                SET $changes_str
                WHERE image='$image'
            ";
            if (0)
            {
                echo $sql;
            }
            if (1)
            {
                dpsql_query($sql);
            }

            $n_texts_changed += count($changes);
            $n_pages_changed += 1;
        }
    }
    
    echo sprintf( "... changed %3d texts for %3d pages", $n_texts_changed, $n_pages_changed );
    echo "\n";

    $n_total_texts_changed += $n_texts_changed;
    $n_total_pages_changed += $n_pages_changed;
    if ( $n_pages_changed > 0 ) $n_total_projects_changed += 1;
}

echo "\n";
echo "In total, changed $n_total_texts_changed texts for $n_total_pages_changed pages in $n_total_projects_changed projects.\n";

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
