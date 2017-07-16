<?php

// One-time script to create 'image_sources' table

$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');

// -----------------------------------------------
// Create 'image_sources' table.

echo "Creating 'images_sources' table...\n";
dpsql_query("
CREATE TABLE image_sources (
  code_name varchar(10) NOT NULL default '',
  display_name varchar(30) NOT NULL default '',
  full_name varchar(100) NOT NULL default '',
  info_page_visibility tinyint(3) unsigned NOT NULL default '0',
  is_active tinyint(3) NOT NULL default '-1',
  url varchar(200) default NULL,
  credit varchar(200) default NULL,
  ok_keep_images tinyint(4) NOT NULL default '-1',
  ok_show_images tinyint(4) NOT NULL default '-1',
  public_comment varchar(255) default NULL,
  internal_comment text default NULL,
  UNIQUE KEY code_name (code_name),
  UNIQUE KEY display_name (display_name)
) TYPE=MyISAM;

  
    
") or die("Aborting.");


echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
