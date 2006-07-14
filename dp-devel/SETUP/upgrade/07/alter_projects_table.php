<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

echo "Altering 'projects' table...\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

echo "Adding the 'deletion_reason' column...\n";

$sql = "
    ALTER TABLE projects
        ADD COLUMN deletion_reason TINYTEXT NOT NULL
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

echo "Harmonizing the types of user-name columns...\n";

// Currently, in the 'projects' table,
// the columns that contain usernames have various types:
//     username       VARCHAR(255) NOT NULL default '',
//     checkedoutby   TEXT         NOT NULL,
//     correctedby    VARCHAR(25)  NOT NULL default '',
//     postproofer    VARCHAR(255) NOT NULL default '',
//     ppverifier     VARCHAR(25)           default NULL,
//     image_preparer VARCHAR(25)  NOT NULL default '',
//     text_preparer  VARCHAR(25)  NOT NULL default '',
//
// Change username, checkedoutby, and postproofer so that they are
// consistent with the others.
//
// This isn't just housekeeping. There's a MySQL bug wherein
//     UPDATE projects SET checkedoutby=postproofer, postproofer='new-value'
// results in checkedoutby getting a bad value (the new value of postproofer,
// truncated or blank-padded to the length of the old value of postproofer)
// when checkedoutby has TEXT type, but not when it has a VARCHAR type.
// So that at least must change.

// First, check that changing the column types won't throw away information.
// This shouldn't happen, since presumably all username values are just copies
// of values in users.username, which is VARCHAR(25), but just in case...
$abort = FALSE;
foreach ( array('username', 'checkedoutby', 'postproofer') as $colname )
{
    $res = mysql_query("
        SELECT distinct $colname 
        FROM projects
        WHERE LENGTH($colname) > 25
    ") or die(mysql_error());
    if ( mysql_num_rows($res) > 0 )
    {
        echo "The following values in $colname are too long:\n";
        while ( list($name) = mysql_fetch_row($res) )
        {
            echo "    $name\n";
        }
        $abort = TRUE;
    }
}
if ( $abort ) exit;

$sql = "
    ALTER TABLE projects
        MODIFY COLUMN username     VARCHAR(25) NOT NULL default '',
        MODIFY COLUMN checkedoutby VARCHAR(25) NOT NULL default '',
        MODIFY COLUMN postproofer  VARCHAR(25) NOT NULL default ''
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
