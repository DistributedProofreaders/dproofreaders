<?PHP

// One-time script to create 'special_days' table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

// -----------------------------------------------
// Create 'special_days' table.

dpsql_query("
    # --------------------------------------------------------

#
# Table structure for table `special_days`
#

CREATE TABLE special_days (
  spec_code varchar(20) NOT NULL default '',
  display_name varchar(80) NOT NULL default '',
  enable tinyint(1) NOT NULL default '1',
  comment varchar(255) default NULL,
  color varchar(8) NOT NULL default '',
  open_day tinyint(2) default NULL,
  open_month tinyint(2) default NULL,
  close_day tinyint(2) default NULL,
  close_month tinyint(2) default NULL,
  date_changes varchar(100) default NULL,
  info_url varchar(255) default NULL,
  image_url varchar(255) default NULL,
  UNIQUE KEY spec_code (spec_code)
) TYPE=MyISAM COMMENT='definitions of SPECIAL days';
    
    
") or die("Aborting.");


echo "Done!";

// vim: sw=4 ts=4 expandtab
?>
