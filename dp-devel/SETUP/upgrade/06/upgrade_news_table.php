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
    ADD `news_page_id` VARCHAR (8) BEFORE ``uid`,
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
  `display_page` varchar(255) NOT NULL default '',
  `site_news_id` varchar(8) NOT NULL default '',
  `news_type` varchar(40) NOT NULL default '',
  `modifieddate` varchar(10) 
) TYPE=MyISAM COMMENT='maps urls of display_page to a site_news_id'
") 
or die("Aborting.");

echo "News_pages table creation...Done!";


// -----------------------------------------------
// Populate news_pages table with default values


dpsql_query("
INSERT INTO `news_pages` VALUES ('activity_hub.php', 'ACT', 'Activity Hub',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('round.php?round_id=P1', 'P1', 'First Round Proofreading',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('round.php?round_id=P2', 'P2', 'Second Round Proofreading',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('round.php?round_id=F1', 'F1', 'First Round Formatting',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('round.php?round_id=F2', 'F2', 'Second Round Formatting',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('default.php', 'FRONT', 'Front Page',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('post_proofers.php', 'PP', 'Post Processing',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('faq_central.php', 'FAQ', 'FAQ CENTRAL',UNIX_TIMESTAMP());
") 
or die("Aborting.");

dpsql_query("
INSERT INTO `news_pages` VALUES ('stats_central.php', 'STATS', 'Stats Central',UNIX_TIMESTAMP());
") 
or die("Aborting.");


echo "News_pages table population...Done!";    

echo "All done!";

?>
