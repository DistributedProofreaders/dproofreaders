<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');

header('Content-type: text/plain');

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Obsoleting 'link' columns...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE projects
        CHANGE txtlink  txtlink_obsolete  VARCHAR(200) DEFAULT NULL,
        CHANGE ziplink  ziplink_obsolete  VARCHAR(200) DEFAULT NULL,
        CHANGE htmllink htmllink_obsolete VARCHAR(200) DEFAULT NULL
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Altering 'postednum' column...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE projects
        MODIFY COLUMN postednum SMALLINT(5) UNSIGNED DEFAULT NULL
") or die(mysqli_error(DPDatabase::get_connection()));

// default used to be 6000
echo "Changing postednum=6000 to postednum=NULL for non-posted projects...\n";
$psd = get_project_status_descriptor( 'posted' );
mysqli_query(DPDatabase::get_connection(), "
    UPDATE projects
    SET postednum=NULL
    WHERE postednum=6000 AND NOT($psd->state_selector)
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Adding 'special_code' column...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE projects
        ADD special_code VARCHAR(20) NOT NULL DEFAULT '' AFTER projectid
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\n";
echo "----------------------------------\n";
echo "Populating 'special_code' column by extracting info from 'comments' column...\n";

$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT projectid, comments
    FROM projects
    WHERE LEFT(comments,8) = 'special:'
") or die(mysqli_error(DPDatabase::get_connection()));

while ( list($projectid,$comments) = mysqli_fetch_row($res) )
{
    move_special_info( $projectid, $comments );
}

$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT COUNT(*)
    FROM projects
    WHERE LEFT(comments,8) = 'special:'
") or die(mysqli_error(DPDatabase::get_connection()));
list($num_left) = mysqli_fetch_row($res);
echo "There are $num_left project comments left that begin with 'SPECIAL:'.\n";

echo "\n";
echo "----------------------------------\n";
echo "Changing queue_defns.project_selector to look for special info in projects.special_code rather than projects.comments...\n";

$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT ordering, project_selector
    FROM queue_defns
    WHERE INSTR(project_selector,'special:')
") or die(mysqli_error(DPDatabase::get_connection()));
while ( list($ordering,$project_selector) = mysqli_fetch_row($res) )
{
    tweak_queue_defn( $ordering, $project_selector );
}
$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT COUNT(*)
    FROM queue_defns
    WHERE INSTR(project_selector,'special:')
") or die(mysqli_error(DPDatabase::get_connection()));
list($num_left) = mysqli_fetch_row($res);
echo "There are $num_left queue_defns left whose project_selector mentions 'SPECIAL:'.\n";


echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Deleting 'Refer to the Guidelines' from project comments...\n";

$text_to_delete = "<p>Refer to the <a href=\"$code_url/faq/document.php\">Proofreading Guidelines</a>.</p>";

mysqli_query(DPDatabase::get_connection(), "
    UPDATE projects
    SET comments=REPLACE(comments,'$text_to_delete','')
") or die(mysqli_error(DPDatabase::get_connection()));
echo mysqli_affected_rows(DPDatabase::get_connection()), " project comments changed.\n";

$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT COUNT(*)
    FROM projects
    WHERE INSTR(comments,'document.php')
") or die(mysqli_error(DPDatabase::get_connection()));
list($num_left) = mysqli_fetch_row($res);
echo "There are still $num_left project comments left that mention document.php in some way.\n";

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Inserting transitional warning into project comments of in-rounds projects...\n";

$R1_msg = "This project was in R1 at the cutover from the old 2-round system. It may contain formatting that was added in R1.";
$R2_msg = "This project was in R2 at the cutover from the old 2-round system. It may contain formatting that was added in R1 and may need additional proofreading in F1.";

$R1_msg = mysqli_real_escape_string(DPDatabase::get_connection(), "<p style='color: red'>$R1_msg</p>\n");
$R2_msg = mysqli_real_escape_string(DPDatabase::get_connection(), "<p style='color: red'>$R2_msg</p>\n");

// Use old and new state strings, so that there's no ordering depdency
// between this script and update_project_states.php

mysqli_query(DPDatabase::get_connection(), "
    UPDATE projects
    SET comments=CONCAT('$R1_msg',comments)
    WHERE state IN ('avail_1','P1.proj_avail')
") or die(mysqli_error(DPDatabase::get_connection()));

mysqli_query(DPDatabase::get_connection(), "
    UPDATE projects
    SET comments=CONCAT('$R2_msg',comments)
    WHERE state IN ('avail_2','P2.proj_avail')
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Adding various fields to `projects` table...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE projects
        ADD n_pages             SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
        ADD n_available_pages   SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
        ADD ppverifier          VARCHAR(25),
        ADD image_source        VARCHAR(10) NOT NULL,
        ADD image_preparer      VARCHAR(25) NOT NULL,
        ADD text_preparer       VARCHAR(25) NOT NULL,
        ADD extra_credits       TINYTEXT    NOT NULL,
        ADD smoothread_deadline INT(20)     DEFAULT '0' NOT NULL,
        ADD up_projectid        INT(10)     DEFAULT '0',
        ADD INDEX (special_code)
") or die(mysqli_error(DPDatabase::get_connection()));

echo "Addition of fields is complete!\n";

echo "\n";
echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
echo "Now, filling in n_pages and n_available_pages. This could take a while...\n";

$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT projectid
    FROM projects
") or die(mysqli_error(DPDatabase::get_connection()));

$n = mysqli_num_rows($res);
echo "$n projects...\n";

$i = 0;
while ( list($projectid) = mysqli_fetch_row($res) )
{
    $i++;
    flush();
    echo "  #$i: $projectid: ";

    // "state LIKE '%avail%'" works before and after update_page_tables.php has run,
    // so we don't have an ordering dependency.

    $res2 = mysqli_query(DPDatabase::get_connection(), "
        SELECT COUNT(*), SUM(state LIKE '%avail%')
        FROM $projectid
    ");
    if ( $res2 === FALSE )
    {
        echo "no pages table\n";
        continue;
    }

    list($n_pages,$n_available_pages) = mysqli_fetch_row($res2);

    if ( is_null($n_available_pages) ) $n_available_pages = 0;

    echo "pages: $n_pages, $n_available_pages\n";

    mysqli_query(DPDatabase::get_connection(), "
        UPDATE projects
        SET n_pages=$n_pages, n_available_pages=$n_available_pages
        WHERE projectid='$projectid'
    ") or die(mysqli_error(DPDatabase::get_connection()));
}

echo "\nDone!\n";

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
            mysqli_query(DPDatabase::get_connection(), "
                UPDATE projects
                SET
                    special_code = '$special_code',
                    comments = SUBSTRING(comments,$c+1)
                WHERE projectid='$projectid'
            ") or die(mysqli_error(DPDatabase::get_connection()));
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

function tweak_queue_defn( $ordering, $project_selector )
{
    $new_project_selector =
        preg_replace(
            array(
                '/comments like (["\'])SPECIAL: (birthday|otherday)%\1/i',
                '/comments like (["\'])SPECIAL: ([^%]+)%\1/i',
                '/comments like (["\'])SPECIAL:%\1/i',
                '/comments not like (["\'])SPECIAL:%\1/i',
            ),
            array(
                'special_code like "\2%"',
                'special_code = "\2"',
                "special_code != ''",
                "special_code = ''",
            ),
            $project_selector );
    if (0)
    {
        echo "\n";
        echo "$project_selector\n";
        echo "$new_project_selector\n";
    }
    else
    {
        $new_project_selector = mysqli_real_escape_string(DPDatabase::get_connection(), $new_project_selector);
        mysqli_query(DPDatabase::get_connection(), "
            UPDATE queue_defns
            SET project_selector='$new_project_selector'
            WHERE ordering='$ordering'
        ") or die(mysqli_error(DPDatabase::get_connection()));
    }
}

// vim: sw=4 ts=4 expandtab
?>
