/*
    The table may be created by executing this SQL directly, or including
    this file in a php module to execute $sql.

CREATE TABLE `dproofreaders`.`project_pages` (
`projectid` varchar( 25 ) NOT NULL default '',
`fileid` varchar( 20 ) NOT NULL default '',
`image` varchar( 8 ) NOT NULL default '',
`master_text` longtext NOT NULL ,
`round1_text` longtext NOT NULL ,
`round2_text` longtext NOT NULL ,
`round1_user` varchar( 25 ) NOT NULL default '',
`round2_user` varchar( 25 ) NOT NULL default '',
`round1_time` int( 20 ) NOT NULL default '0',
`round2_time` int( 20 ) NOT NULL default '0',
`state` varchar( 50 ) NOT NULL default '',
`b_user` varchar( 25 ) NOT NULL default '',
`b_code` int( 1 ) NOT NULL default '0',
`metadata` set( 'frontmatter', 'backmatter', 'division', 'verse', 'poetry', 'letter', 'toc', 'footnote', 'sidenote', 'epigraph', 'table', 'list', 'math', 'drawing', 'badscan', 'blank', 'illustration', 'missing', 'drawing', 'math' ) NOT NULL default '',
`orig_page_num` varchar( 6 ) NOT NULL default '',
PRIMARY KEY ( `projectid` , `fileid` ) ,
KEY `round1_user` ( `round1_user` ) ,
KEY `round2_user` ( `round2_user` ) ,
KEY `round1_time` ( `round1_time` ) ,
KEY `round2_time` ( `round2_time` ) 
) TYPE = MYISAM CHARSET = utf8;

*/
// DAK This was created phpMyAdmin and was copied directly here.

sql .= ' CREATE TABLE `dproofreaders`.`temp_pages` ( `projectid` varchar( 25 ) NOT NULL default \'\',';
$sql .= ' `fileid` varchar( 20 ) NOT NULL default \'\',';
$sql .= ' `image` varchar( 8 ) NOT NULL default \'\',';
$sql .= ' `master_text` longtext NOT NULL ,';
$sql .= ' `round1_text` longtext NOT NULL ,';
$sql .= ' `round2_text` longtext NOT NULL ,';
$sql .= ' `round1_user` varchar( 25 ) NOT NULL default \'\',';
$sql .= ' `round2_user` varchar( 25 ) NOT NULL default \'\',';
$sql .= ' `round1_time` int( 20 ) NOT NULL default \'0\',';
$sql .= ' `round2_time` int( 20 ) NOT NULL default \'0\',';
$sql .= ' `state` varchar( 50 ) NOT NULL default \'\',';
$sql .= ' `b_user` varchar( 25 ) NOT NULL default \'\',';
$sql .= ' `b_code` int( 1 ) NOT NULL default \'0\',';
$sql .= ' `metadata` set( \'frontmatter\', \'backmatter\', \'division\', \'verse\', \'poetry\', \'letter\', \'toc\', \'footnote\', \'sidenote\', \'epigraph\', \'table\', \'list\', \'math\', \'drawing\', \'badscan\', \'blank\', \'illustration\', \'missing\', \'drawing\', \'math\' ) NOT NULL default \'\',';
$sql .= ' `orig_page_num` varchar( 6 ) NOT NULL default \'\',';
$sql .= ' PRIMARY KEY ( `projectid` , `fileid` ) ,';
$sql .= ' KEY `round1_user` ( `round1_user` ) ,';
$sql .= ' KEY `round2_user` ( `round2_user` ) ,';
$sql .= ' KEY `round1_time` ( `round1_time` ) ,';
$sql .= ' KEY `round2_time` ( `round2_time` ) ) TYPE = MYISAM CHARSET = utf8;';
$sql .= ''; 

