# phpMyAdmin MySQL-Dump
# version 2.5.0
# http://www.phpmyadmin.net/ (download page)
#
# Host: josephgruber.com
# Generation Time: Jul 05, 2003 at 07:43 AM
# Server version: 4.0.12
# PHP Version: 4.3.3RC1
# Database : `dproofreaders`
# --------------------------------------------------------

#
# Table structure for table `news`
#
# Creation: May 26, 2003 at 07:39 PM
# Last update: Jun 12, 2003 at 09:33 PM
#

CREATE TABLE `news` (
  `uid` int(11) NOT NULL auto_increment,
  `date_posted` int(20) NOT NULL default '0',
  `message` text NOT NULL,
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=13 ;
# --------------------------------------------------------

#
# Table structure for table `phpbb_users`
#
# Creation: May 26, 2003 at 07:53 PM
# Last update: May 26, 2003 at 07:59 PM
#

CREATE TABLE `phpbb_users` (
  `user_id` mediumint(8) NOT NULL default '0',
  `user_active` tinyint(1) default '1',
  `username` varchar(25) NOT NULL default '',
  `user_password` varchar(32) NOT NULL default '',
  `user_session_time` int(11) NOT NULL default '0',
  `user_session_page` smallint(5) NOT NULL default '0',
  `user_lastvisit` int(11) NOT NULL default '0',
  `user_regdate` int(11) NOT NULL default '0',
  `user_level` tinyint(4) default '0',
  `user_posts` mediumint(8) unsigned NOT NULL default '0',
  `user_timezone` decimal(5,2) NOT NULL default '0.00',
  `user_style` tinyint(4) default NULL,
  `user_lang` varchar(255) default NULL,
  `user_dateformat` varchar(14) NOT NULL default 'd M Y H:i',
  `user_new_privmsg` smallint(5) unsigned NOT NULL default '0',
  `user_unread_privmsg` smallint(5) unsigned NOT NULL default '0',
  `user_last_privmsg` int(11) NOT NULL default '0',
  `user_emailtime` int(11) default NULL,
  `user_viewemail` tinyint(1) default NULL,
  `user_attachsig` tinyint(1) default NULL,
  `user_allowhtml` tinyint(1) default '1',
  `user_allowbbcode` tinyint(1) default '1',
  `user_allowsmile` tinyint(1) default '1',
  `user_allowavatar` tinyint(1) NOT NULL default '1',
  `user_allow_pm` tinyint(1) NOT NULL default '1',
  `user_allow_viewonline` tinyint(1) NOT NULL default '1',
  `user_notify` tinyint(1) NOT NULL default '1',
  `user_notify_pm` tinyint(1) NOT NULL default '0',
  `user_popup_pm` tinyint(1) NOT NULL default '0',
  `user_rank` int(11) default '0',
  `user_avatar` varchar(100) default NULL,
  `user_avatar_type` tinyint(4) NOT NULL default '0',
  `user_email` varchar(255) default NULL,
  `user_icq` varchar(15) default NULL,
  `user_website` varchar(100) default NULL,
  `user_from` varchar(100) default NULL,
  `user_sig` text,
  `user_sig_bbcode_uid` varchar(10) default NULL,
  `user_aim` varchar(255) default NULL,
  `user_yim` varchar(255) default NULL,
  `user_msnm` varchar(255) default NULL,
  `user_occ` varchar(100) default NULL,
  `user_interests` varchar(255) default NULL,
  `user_actkey` varchar(32) default NULL,
  `user_newpasswd` varchar(32) default NULL,
  PRIMARY KEY  (`user_id`),
  KEY `user_session_time` (`user_session_time`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `projects`
#
# Creation: Jul 03, 2003 at 08:11 PM
# Last update: Jul 03, 2003 at 08:14 PM
#

CREATE TABLE `projects` (
  `nameofwork` varchar(255) NOT NULL default '',
  `authorsname` varchar(255) NOT NULL default '',
  `language` varchar(255) NOT NULL default '',
  `genre` varchar(50) NOT NULL default 'General Fiction',
  `username` varchar(255) NOT NULL default '',
  `comments` text NOT NULL,
  `projectid` text NOT NULL,
  `checkedoutby` text NOT NULL,
  `modifieddate` int(20) NOT NULL default '0',
  `scannercredit` tinytext NOT NULL,
  `state` varchar(50) NOT NULL default 'waiting_1',
  `txtlink` varchar(200) default NULL,
  `ziplink` varchar(200) default NULL,
  `htmllink` varchar(200) default NULL,
  `postednum` smallint(5) unsigned NOT NULL default '6000',
  `clearance` varchar(200) NOT NULL default '',
  `year` varchar(4) NOT NULL default '',
  `topic_id` int(10) default NULL,
  `updated` tinyint(1) NOT NULL default '1',
  `int_level` int(11) NOT NULL default '0',
  `archived` tinyint(1) NOT NULL default '0',
  `postproofer` varchar(255) NOT NULL default '',
  `postcomments` text NOT NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `rules`
#
# Creation: Jul 05, 2003 at 07:42 AM
# Last update: Jul 05, 2003 at 07:42 AM
#

CREATE TABLE `rules` (
  `id` int(4) NOT NULL auto_increment,
  `doc` varchar(10) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `rule` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=38 ;
# --------------------------------------------------------

#
# Table structure for table `themes`
#
# Creation: May 26, 2003 at 07:45 PM
# Last update: Jun 30, 2003 at 05:03 PM
#

CREATE TABLE `themes` (
  `theme_id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `unixname` varchar(100) NOT NULL default '',
  `created_by` varchar(25) NOT NULL default '',
  KEY `theme_id` (`theme_id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;
# --------------------------------------------------------

#
# Table structure for table `user_profiles`
#
# Creation: May 26, 2003 at 07:45 PM
# Last update: May 26, 2003 at 07:45 PM
#

CREATE TABLE `user_profiles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `u_ref` int(10) unsigned NOT NULL default '0',
  `profilename` varchar(30) NOT NULL default 'default',
  `i_res` tinyint(1) default '1',
  `i_type` tinyint(1) default '0',
  `i_layout` tinyint(1) default '0',
  `i_toolbar` tinyint(1) default '0',
  `i_statusbar` tinyint(1) default '0',
  `i_newwin` tinyint(1) default '1',
  `v_fnts` tinyint(2) default '0',
  `v_fntf` tinyint(1) default '0',
  `v_zoom` smallint(3) default '59',
  `v_tframe` tinyint(2) default '50',
  `v_tlines` tinyint(2) default '40',
  `v_tchars` tinyint(2) default '65',
  `v_tscroll` tinyint(1) default '1',
  `v_twrap` tinyint(1) default '1',
  `h_fnts` tinyint(2) default '0',
  `h_fntf` tinyint(1) default '0',
  `h_zoom` smallint(3) default '76',
  `h_tframe` tinyint(2) default '35',
  `h_tlines` tinyint(2) default '6',
  `h_tchars` tinyint(2) default '70',
  `h_tscroll` tinyint(1) default '1',
  `h_twrap` tinyint(1) default '1',
  PRIMARY KEY  (`id`),
  KEY `u_ref` (`u_ref`)
) TYPE=MyISAM AUTO_INCREMENT=11238 ;
# --------------------------------------------------------

#
# Table structure for table `user_teams`
#
# Creation: May 26, 2003 at 07:46 PM
# Last update: Jun 24, 2003 at 07:55 PM
#

CREATE TABLE `user_teams` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `teamname` varchar(50) NOT NULL default 'default',
  `team_info` text NOT NULL,
  `createdby` varchar(25) NOT NULL default '',
  `owner` int(10) unsigned NOT NULL default '0',
  `created` int(20) NOT NULL default '0',
  `member_count` int(20) NOT NULL default '0',
  `page_count` int(20) NOT NULL default '0',
  `avatar` varchar(25) NOT NULL default 'avatar_default.png',
  `icon` varchar(25) NOT NULL default 'icon_default.png',
  `topic_id` int(10) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=107 ;
# --------------------------------------------------------

#
# Table structure for table `users`
#
# Creation: May 26, 2003 at 07:46 PM
# Last update: Jul 04, 2003 at 04:21 AM
#

CREATE TABLE `users` (
  `id` varchar(50) NOT NULL default '',
  `real_name` varchar(100) NOT NULL default '',
  `username` varchar(25) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `manager` varchar(5) NOT NULL default '',
  `date_created` int(20) NOT NULL default '0',
  `last_login` int(20) NOT NULL default '0',
  `emailupdates` varchar(4) NOT NULL default '',
  `pagescompleted` mediumint(8) default '0',
  `postprocessor` tinytext NOT NULL,
  `sitemanager` tinytext NOT NULL,
  `active` tinytext NOT NULL,
  `u_lang` tinyint(1) default '0',
  `email_updates` tinyint(1) default '1',
  `u_plist` tinyint(1) default '3',
  `u_top10` tinyint(1) NOT NULL default '0',
  `u_neigh` tinyint(4) NOT NULL default '0',
  `u_align` tinyint(1) NOT NULL default '0',
  `i_prefs` tinyint(1) default '0',
  `i_theme` varchar(100) NOT NULL default 'project_gutenberg',
  `u_id` int(10) unsigned NOT NULL auto_increment,
  `u_profile` int(10) unsigned NOT NULL default '0',
  `team_1` int(10) unsigned NOT NULL default '0',
  `team_2` int(10) unsigned NOT NULL default '0',
  `team_3` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`username`),
  UNIQUE KEY `username` (`username`),
  KEY `u_id` (`u_id`)
) TYPE=MyISAM AUTO_INCREMENT=10808 ;
# --------------------------------------------------------

#
# Table structure for table `usersettings`
#
# Creation: May 26, 2003 at 07:47 PM
# Last update: May 26, 2003 at 07:47 PM
#

CREATE TABLE `usersettings` (
  `username` varchar(25) NOT NULL default '',
  `setting` varchar(25) NOT NULL default '',
  `value` varchar(25) NOT NULL default '',
  FULLTEXT KEY `setting` (`setting`)
) TYPE=MyISAM;