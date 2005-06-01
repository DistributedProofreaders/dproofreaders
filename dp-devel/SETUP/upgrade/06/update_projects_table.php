<?php
$relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Adding 'special_code' column...\n";

mysql_query("
    ALTER TABLE projects
        ADD special_code VARCHAR(20) AFTER projectid
") or die(mysql_error());

echo "Populating 'special_code' column by extracting info from 'comments' column...\n";

$res = mysql_query("
    SELECT projectid, comments
    FROM projects
    WHERE LEFT(comments,8) = 'special:'
") or die(mysql_error());

while ( list($projectid,$comments) = mysql_fetch_row($res) )
{
    move_special_info( $projectid, $comments );
}

$res = mysql_query("
    SELECT COUNT(*)
    FROM projects
    WHERE LEFT(comments,8) = 'special:'
") or die(mysql_error());
$num_left = mysql_result($res,0);
echo "There are $num_left project comments left that begin with 'SPECIAL:'.\n";

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Deleting 'Refer to the Guidelines' from project comments...\n";

$text_to_delete = "<p>Refer to the <a href=\"$code_url/faq/document.php\">Proofreading Guidelines</a>.</p>";

mysql_query("
    UPDATE projects
    SET comments=REPLACE(comments,'$text_to_delete','')
") or die(mysql_error());
echo mysql_affected_rows(), " project comments changed.\n";

$res = mysql_query("
    SELECT COUNT(*)
    FROM projects
    WHERE INSTR(comments,'document.php')
") or die(mysql_error());
$num_left = mysql_result($res,0);
echo "There are still $num_left project comments left that mention document.php in some way.\n";

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Adding various fields to `projects` table...\n";

mysql_query("
    ALTER TABLE projects
        ADD n_pages             SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
        ADD n_available_pages   SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
        ADD ppverifier          VARCHAR(25),
        ADD image_provider      VARCHAR(10),
        ADD smoothread_deadline INT(20)     DEFAULT '0' NOT NULL,
        ADD up_projectid        INT(10)     DEFAULT '0',
        ADD INDEX (special_code)
") or die(mysql_error());

echo "Addition of fields is complete!\n";

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
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

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function move_special_info( $projectid, $comments )
// $comments matches /^special:/i
// Extract the special_code from $comments and move it to the 'special_code' column.
{
    $subpatterns = array(
        '((birth|other)day \d\d\d\d)\b\s*',
        '(April Fool|St\. Pat|St\. Val|Nov 11)\.?\s+',
        '(\S+) *([\n\r]\s*|$)',
        '(\S+) *(?=<)',
    );

    $found = FALSE;
    foreach ( $subpatterns as $subpattern )
    {
        $pattern = "/^special: *$subpattern/i";
        $r = preg_match( $pattern, $comments, $matches );
        if ( $r === FALSE )
        {
            die("bad return from preg_match, pattern='$pattern'");
        }
        else if ( $r == 0 )
        {
            // didn't match
        }
        else if ( $r == 1 )
        {
            // matched!
            $special_comment = $matches[0];
            $special_code = $matches[1];
            // echo "delete from comments: '", $special_comment, "'\n";
            // echo "special code: ", $special_code, "\n";

            $c = strlen($special_comment);
            $res = mysql_query("
                UPDATE projects
                SET
                    special_code = '$special_code',
                    comments = SUBSTRING(comments,$c+1)
                WHERE projectid='$projectid'
            ") or die(mysql_error());
            return;
        }
        else
        {
            assert( FALSE );
        }
    }

    // no subpattern matched
    echo "vvvvvvvvvvvvvvvvv\n";
    echo "Unable to extract comments for $projectid. Comments start:\n";
    echo addcslashes(substr($comments,0,50),"\r\n"), "\n";
    echo "^^^^^^^^^^^^^^^^^\n";
}

// vim: sw=4 ts=4 expandtab
?>
