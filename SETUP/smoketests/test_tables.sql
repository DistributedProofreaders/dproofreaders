USE dp_db;
SET FOREIGN_KEY_CHECKS=0;

/* $p = new Project(); $p->foo = bar; $p->save() does not allow new projects
 * to be created with programmatic control of the project_id.
 */
REPLACE INTO `projects` VALUES ('*UTF-8 Practice','Lummis, Charles Fletcher','English','admin','notes','html','projectID5e23a810ef693','','',1704080514,1579564017,1579395673,1708309119,'','P3.proj_avail',NULL,'20200118BEGIN001','',417,1,0,'Folklore','beginner',0,'','',15,6,NULL,'TESTING','admin','admin','',0,'','');

/* `users` rows populated by INSERTs in accounts/activate.php.
 * Can't do this programatically yet.
 */
REPLACE INTO `users` VALUES ('userID1234567890abc', 'Adam Adminston', 'admin', 'a@example.com', 1234567890, 1234567890, 1234567890, 1, '', '', '', 10, 0, 'project_gutenberg', 1, 110, '', 0, 'admin_key');
REPLACE INTO `users` VALUES ('userID460b20a8a8a71','BKeir','teststeel','teststeel@localhost',1175134376,1175134406,1175134457,1,'','','',10,0,'project_gutenberg',110,119,'en_US',0,NULL);

/* $up = new UserProfile(); $up->foo = bar; $up->save(); does not allow new
 * profiles to be created with programmatic control of id.
 */
REPLACE INTO `user_profiles` VALUES (110,105,'default',4,1,1,0,0,1,5,6,'',50,40,65,1,0,5,6,'',35,20,70,1,0);

/* This can be done with $s = new Settings('admin'); $s->set_true('sitemanager');
 * But there's not much point until we can do some of the rest with code.
 */
REPLACE INTO `usersettings` SET username='admin', setting='PP.access', value='yes';
REPLACE INTO `usersettings` SET username='admin', setting='PPV.access', value='yes';
REPLACE INTO `usersettings` SET username='admin', setting='sitemanager', value='yes';

/* `user_teams` rows populated by INSERTs in stats/teams/new_team.php
 * Can't do this programatically yet.
 */
REPLACE INTO `user_teams` VALUES (44,'%','','http://webpage.org','wickedcoder256',1,1532715008,34,'avatar_605f6ae3e7386.png','icon_5f9490f9bf32b.png',509,151);

/* There's no code in dproofers to INSERT into queue_defns yet. */
REPLACE INTO `queue_defns` VALUES (500,'P3',3155,1,'Esperanto','language like \'Esperanto%\' and not (genre = \'Mathematics\' and difficulty = \'hard\')',4,400,'Have Esperanto available when possible (special case artificial language: \"with\" titles more common)');

/* The table can be created with DPage.inc project_allow_pages, and in theory
 * can be populated (via intermediate text files) using project_add_page,
 * and Page_modifyText, but it's clunky and annoying at the moment.
 */
DROP TABLE IF EXISTS `projectID5e23a810ef693`;
CREATE TABLE `projectID5e23a810ef693` (
  fileid varchar(20) NOT NULL DEFAULT '', UNIQUE (fileid),
  image varchar(12) NOT NULL DEFAULT '', UNIQUE (image),
  master_text longtext NOT NULL,
  round1_time int NOT NULL DEFAULT '0',
  round1_user varchar(25) NOT NULL DEFAULT '',
  round1_text longtext NOT NULL,
  round2_time int NOT NULL DEFAULT '0',
  round2_user varchar(25) NOT NULL DEFAULT '',
  round2_text longtext COLLATE utf8mb4_general_ci NOT NULL,
  round3_time int NOT NULL DEFAULT '0',
  round3_user varchar(25) NOT NULL DEFAULT '',
  round3_text longtext NOT NULL,
  round4_time int NOT NULL DEFAULT '0',
  round4_user varchar(25) NOT NULL DEFAULT '',
  round4_text longtext NOT NULL,
  round5_time int NOT NULL DEFAULT '0',
  round5_user varchar(25) NOT NULL DEFAULT '',
  round5_text longtext NOT NULL,
  state varchar(50) NOT NULL DEFAULT '',
  b_user varchar(25) NOT NULL DEFAULT '',
  b_code int NOT NULL DEFAULT '0',
  orig_page_num varchar(6) NOT NULL DEFAULT '',
  KEY round1_user (round1_user),
  KEY round2_user (round2_user),
  KEY round3_user (round3_user),
  KEY round4_user (round4_user),
  KEY round5_user (round5_user),
  KEY state (state)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

REPLACE INTO `projectID5e23a810ef693` VALUES ('001','001.png','THE BOY IN THE IIOL\'SE OF THE TRUES. (SEE PAGE 115.)',1579633527,'sanspeur43','THE BOY IN THE HOUSE OF THE TRUES. (SEE PAGE 115.)',1579652671,'jjz','\r\nTHE BOY IN THE HOUSE OF THE TRUES. (SEE PAGE 115.)',1708294896,'bfoley','\r\nTHE BOY IN THE HOUSE OF THE TRUES. (SEE PAGE 115.)',0,'','',0,'','','P3.page_saved','',0,'');
REPLACE INTO `projectID5e23a810ef693` VALUES ('002','002.png','THE MAN WHO MARRIED\r\nTHE MOON\r\n\r\nAND OTHER PUEBLO INDIAN FOLK -- STORIES\r\n\r\nBY\r\n\r\nCHARLES E. LUMMIS\r\n\r\n(AUYHOR \'OF SOME STRANGE CORNERS OF OUR COUNTRY\"\r\n\"A IVEW MEX/CO DAVID,\" ETC.\r\n\r\n\r\n\r\nNEW YORK\r\nTHE CENIURY CO.\r\n1894',1579633637,'sanspeur43','THE MAN WHO MARRIED\r\nTHE MOON\r\n\r\nAND OTHER PUEBLO INDIAN FOLK-STORIES\r\n\r\nBY\r\n\r\nCHARLES E. LUMMIS\r\n\r\n(AUTHOR \'OF SOME STRANGE CORNERS OF OUR COUNTRY\"\r\n\"A NEW MEXICO DAVID,\" ETC.\r\n\r\n\r\n\r\nNEW YORK\r\nTHE CENTURY CO.\r\n1894',1579651078,'jjz','\r\nTHE MAN WHO MARRIED\r\nTHE MOON\r\n\r\nAND OTHER PUEBLO INDIAN FOLK-STORIES\r\n\r\nBY\r\n\r\nCHARLES E. LUMMIS\r\n\r\n(AUTHOR OF \"SOME STRANGE CORNERS OF OUR COUNTRY[**,]\"\r\n\"A NEW MEXICO DAVID,\" ETC.\r\n\r\nNEW YORK\r\nTHE CENTURY CO.\r\n1894',1708308780,'srjfoo','\r\nTHE MAN WHO MARRIED\r\nTHE MOON\r\n\r\nAND OTHER PUEBLO INDIAN FOLK-STORIES\r\n\r\nBY\r\n\r\nCHARLES E. LUMMIS\r\n\r\n(AUTHOR OF \"SOME STRANGE CORNERS OF OUR COUNTRY[**,]\"\r\n\"A NEW MEXICO DAVID,\" ETC.\r\n\r\nNEW YORK\r\n\r\nTHE CENTURY CO.\r\n\r\n1894',0,'','',0,'','','P3.page_saved','',0,'');
REPLACE INTO `projectID5e23a810ef693` VALUES ('003','003.png','Copyright, 1891, 1892, 1894,\r\n\r\nBy THE CENTURY C0.\r\n\r\n\r\n\r\nTHE DEVINNE PREss;',1579633720,'sanspeur43','Copyright, 1891, 1892, 1894,\r\nBy THE CENTURY Co.\r\n\r\n\r\n\r\nTHE DE VINNE PREss.',1579651130,'jjz','\r\nCopyright, 1891, 1892, 1894,\r\nBy THE CENTURY Co.\r\n\r\nTHE DE VINNE PREss.',1708308890,'srjfoo','\r\nCopyright, 1891, 1892, 1894,\r\nBy THE CENTURY Co.\r\n\r\nThe De Vinne Press.',0,'','',0,'','','P3.page_saved','',0,'');
REPLACE INTO `projectID5e23a810ef693` VALUES ('004','004.png','To\r\nTHE FAIRY TALE THAT CAME TRUE IN\r\nTHE HOME OF THE TEE-WAHN\r\nMY WIFE AND CHILD',1579633808,'sanspeur43','To\r\nTHE FAIRY TALE THAT CAME TRUE IN\r\nTHE HOME OF THE TEE-WAHN\r\nMY WIFE AND CHILD',1579651187,'jjz','\r\nTo\r\nTHE FAIRY TALE THAT CAME TRUE IN\r\nTHE HOME OF THE TEE-WAHN\r\nMY WIFE AND CHILD',1708308955,'srjfoo','\r\nTo\r\nTHE FAIRY TALE THAT CAME TRUE IN\r\nTHE HOME OF THE TEE-WAHN\r\nMY WIFE AND CHILD',0,'','',0,'','','P3.page_saved','',0,'');
REPLACE INTO `projectID5e23a810ef693` VALUES ('005','005.png','',1579633830,'sanspeur43','[Blank Page]',1579651201,'jjz','[Blank Page]',1708308967,'srjfoo','[Blank Page]',0,'','',0,'','','P3.page_saved','',0,'');
