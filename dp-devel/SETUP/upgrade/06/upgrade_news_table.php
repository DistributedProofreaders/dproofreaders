<?PHP

// One-time script to create 'news_pages' table and modify news_table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

// -----------------------------------------------
// Add new columns to news table

dpsql_query("
    ALTER TABLE `news` 
    ADD `news_page_id` VARCHAR(8) FIRST,
    ADD `display` TINYINT AFTER `uid` ,
    ADD `ordering` TINYINT AFTER `display` ,
    ADD `archive` TINYINT AFTER `ordering`
") 
or die("Aborting.");

echo "News table alteration...Done!";


// -----------------------------------------------
// Create news_pages table

dpsql_query("
CREATE TABLE `news_pages` (
  `news_page_id` varchar(8) NOT NULL default '',
  `news_type` varchar(40) NOT NULL default '',
  `modifieddate` varchar(10) 
) TYPE=MyISAM
") 
or die("Aborting.");

echo "News_pages table creation...Done!";


// -----------------------------------------------
// Populate news_pages table with default values


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



echo "News_pages table population...Done!";    

echo "All done!";

?>
