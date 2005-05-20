<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

mysql_query("
    ALTER TABLE projects
        ADD special             VARCHAR(20) AFTER projectid,
        ADD n_pages             SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
        ADD n_available_pages   SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
        ADD ppverifier          VARCHAR(25),
        ADD image_provider      VARCHAR(10),
        ADD smoothread_deadline INT(20)     DEFAULT '0' NOT NULL,
        ADD up_projectid        INT(10)     DEFAULT '0',
        ADD INDEX (special)
") or die(mysql_error());

echo "Addition of various fields to `projects` table is complete!\n";
echo "\n";
echo "Now, filling in n_pages and n_available_pages. This could take a while...\n";

$res = mysql_query("
    SELECT projectid
    FROM projects
") or die(mysql_error());

$n = mysql_num_rows($res);
echo "$n projects...\n";

$i = 0;
while ( list($projectid) = mysql_fetch_row($res) )
{
    $i++;
    flush();
    echo "  #$i: $projectid: ";

    // "state LIKE '%avail%'" works before and after update_page_tables.php has run,
    // so we don't have an ordering dependency.

    $res2 = mysql_query("
        SELECT COUNT(*), SUM(state LIKE '%avail%')
        FROM $projectid
    ");
    if ( $res2 === FALSE )
    {
        echo "no pages table\n";
        continue;
    }

    list($n_pages,$n_available_pages) = mysql_fetch_row($res2);

    if ( is_null($n_available_pages) ) $n_available_pages = 0;

    echo "pages: $n_pages, $n_available_pages\n";

    mysql_query("
        UPDATE projects
        SET n_pages=$n_pages, n_available_pages=$n_available_pages
        WHERE projectid='$projectid'
    ") or die(mysql_error());
}

echo "\n";
echo "Done!\n";

// vim: sw=4 ts=4 expandtab
?>
