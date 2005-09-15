<?PHP

// One-time script to create 'image_sources' table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'f_dpsql.inc');
new dbConnect();

// -----------------------------------------------
// Create 'image_sources' table.

dpsql_query("
CREATE TABLE `image_sources` (
`code_name` VARCHAR(10) NOT NULL ,
`display_name` VARCHAR( 30 ) NOT NULL ,
`full_name` VARCHAR( 100 ) NOT NULL ,
`is_active` TINYINT DEFAULT '-1' NOT NULL ,
`info_page_visibility` TINYINT UNSIGNED  DEFAULT '0' NOT NULL,
`url` VARCHAR( 200 ) ,
`credit` VARCHAR( 200 ) ,
`ok_keep_images` TINYINT DEFAULT '-1' NOT NULL ,
`ok_show_images` TINYINT DEFAULT '-1' NOT NULL ,
`public_comment` VARCHAR( 255 ) ,
`internal_comment` VARCHAR( 255 ) ,
UNIQUE ( `code_name` ),
UNIQUE  ( `display_name` )
)
  
    
") or die("Aborting.");


echo "Done!";

// vim: sw=4 ts=4 expandtab
?>
