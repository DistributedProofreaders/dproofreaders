<?PHP

// One-time script to create 'news_pages' table and modify news_table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

header('Content-type: text/plain');

/*
In the old 'news' table, each 'message' was a largish concatenation
of several items. But in the new table, each 'message' is (meant
to be) a single item.  Thus, it wouldn't be very useful to have
the old messages as-is in the new table.  (Though if old messages
could be broken into items, those might be useful.)

So, rather than altering the existing 'news' table, mothball it
and create a new one.
*/

// -----------------------------------------------
// Mothball the old news table

echo "Mothballing the old 'news' table...\n";

dpsql_query("
    RENAME TABLE news TO old_site_news
") or die("Aborting.");

// -----------------------------------------------
// Create new news table.

echo "Creating the new 'news_items' table...\n";

dpsql_query("
    CREATE TABLE news_items (
        uid          INT         NOT NULL auto_increment,
        date_posted  VARCHAR(10) NOT NULL default '',
        news_page_id VARCHAR(8)           default NULL,
        status       VARCHAR(8)  NOT NULL default '',
        ordering     TINYINT     NOT NULL default '0',
        message      TEXT        NOT NULL,
        KEY uid (uid)
    ) TYPE=MyISAM
") 
or die("Aborting.");

// -----------------------------------------------
// Create news_pages table

echo "Creating 'news_pages' table...\n";

dpsql_query("
CREATE TABLE `news_pages` (
  `news_page_id` varchar(8) NOT NULL default '',
  `news_type` varchar(40) NOT NULL default '',
  `modifieddate` varchar(10) 
) TYPE=MyISAM
") 
or die("Aborting.");

// -----------------------------------------------
// Populate news_pages table with default values

echo "Populating 'news_pages' table...\n";

dpsql_query("
INSERT INTO `news_pages` VALUES ('HUB', 'Activity Hub',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('P1', 'First Round Proofreading',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('P2', 'Second Round Proofreading',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('F1', 'First Round Formatting',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('F2', 'Second Round Formatting',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('FRONT', 'Front Page',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('PP', 'Post Processing',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('FAQ', 'FAQ CENTRAL',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('STATS', 'Stats Central',UNIX_TIMESTAMP());
") 
or die("Aborting.");


echo "All done!";

?>
