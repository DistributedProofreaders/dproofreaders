# phpMyAdmin SQL Dump
# version 2.5.3
# http://www.phpmyadmin.net
#
# Host: josephgruber.com
# Generation Time: Oct 04, 2003 at 09:45 AM
# Server version: 4.1.0
# PHP Version: 4.3.3
#
# Database : `dproofreaders`
#

# --------------------------------------------------------

#
# Table structure for table `marc_records`
#

CREATE TABLE `marc_records` (
  `projectid` varchar(22) NOT NULL default '',
  `original_marc` text NOT NULL,
  `updated_marc` text NOT NULL,
  `original_array` text NOT NULL,
  `updated_array` text NOT NULL
) TYPE=MyISAM CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `member_stats`
#

CREATE TABLE `member_stats` (
  `u_id` varchar(6) NOT NULL default '',
  `date_updated` int(11) NOT NULL default '0',
  `total_pagescompleted` mediumint(9) NOT NULL default '0',
  `daily_pagescompleted` mediumint(9) NOT NULL default '0',
  `rank` mediumint(9) NOT NULL default '0',
  KEY `u_id` (`u_id`)
) TYPE=MyISAM CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `news`
#

CREATE TABLE `news` (
  `uid` int(11) NOT NULL auto_increment,
  `date_posted` varchar(10) NOT NULL default '',
  `message` text NOT NULL,
  KEY `uid` (`uid`)
) TYPE=MyISAM CHARSET=latin1 AUTO_INCREMENT=34 ;

# --------------------------------------------------------

#
# Table structure for table `page_counts`
#

CREATE TABLE `page_counts` (
  `projectid` char(22) NOT NULL default '',
  `total_pages` smallint(4) unsigned NOT NULL default '0',
  `avail_pages` smallint(4) unsigned NOT NULL default '0',
  UNIQUE KEY `projectid` (`projectid`)
) TYPE=HEAP CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `pagestats`
#

CREATE TABLE `pagestats` (
  `year` smallint(4) NOT NULL default '0',
  `month` tinyint(2) NOT NULL default '0',
  `day` tinyint(2) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `pages` int(12) NOT NULL default '0',
  `dailygoal` int(12) NOT NULL default '0'
) TYPE=MyISAM CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `phpbb_users`
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
  `user_timezone` decimal(4,2) NOT NULL default '0.00',
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
  `user_notify_pm` tinyint(1) NOT NULL default '1',
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
) TYPE=MyISAM CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `projects`
#

CREATE TABLE `projects` (
  `nameofwork` varchar(255) NOT NULL default '',
  `authorsname` varchar(255) NOT NULL default '',
  `language` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default '',
  `comments` text NOT NULL,
  `projectid` text NOT NULL,
  `checkedoutby` text NOT NULL,
  `correctedby` varchar(25) NOT NULL default '',
  `modifieddate` int(20) NOT NULL default '0',
  `scannercredit` tinytext NOT NULL,
  `state` varchar(50) default NULL,
  `txtlink` varchar(200) default NULL,
  `ziplink` varchar(200) default NULL,
  `htmllink` varchar(200) default NULL,
  `postednum` smallint(5) unsigned NOT NULL default '6000',
  `clearance` varchar(200) NOT NULL default '',
  `year` varchar(4) NOT NULL default '',
  `topic_id` int(10) default NULL,
  `updated` tinyint(1) NOT NULL default '1',
  `int_level` int(11) NOT NULL default '0',
  `genre` varchar(50) NOT NULL default '',
  `difficulty` varchar(20) NOT NULL default 'average',
  `archived` tinyint(1) NOT NULL default '0',
  `postproofer` varchar(255) NOT NULL default '',
  `postcomments` text NOT NULL
) TYPE=MyISAM CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `queue_defns`
#

CREATE TABLE `queue_defns` (
  `ordering` tinyint(3) NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `project_selector` text NOT NULL,
  `release_criterion` text NOT NULL,
  `comment` text,
  UNIQUE KEY `ordering` (`ordering`),
  UNIQUE KEY `name` (`name`)
) TYPE=MyISAM CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `rules`
#

CREATE TABLE `rules` (
  `id` int(4) NOT NULL auto_increment,
  `doc` varchar(10) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `rule` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM CHARSET=latin1 AUTO_INCREMENT=58 ;

# --------------------------------------------------------

#
# Table structure for table `themes`
#

CREATE TABLE `themes` (
  `theme_id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `unixname` varchar(100) NOT NULL default '',
  `created_by` varchar(25) NOT NULL default '',
  KEY `theme_id` (`theme_id`)
) TYPE=MyISAM CHARSET=latin1 AUTO_INCREMENT=4 ;

# --------------------------------------------------------

#
# Table structure for table `user_profiles`
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
) TYPE=MyISAM CHARSET=latin1 AUTO_INCREMENT=16691 ;

# --------------------------------------------------------

#
# Table structure for table `user_teams`
#

CREATE TABLE `user_teams` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `teamname` varchar(50) NOT NULL default 'default',
  `team_info` text NOT NULL,
  `webpage` varchar(255) NOT NULL default 'http://www.pgdp.net',
  `createdby` varchar(25) NOT NULL default '',
  `owner` int(10) unsigned NOT NULL default '0',
  `created` int(20) NOT NULL default '0',
  `member_count` int(20) NOT NULL default '0',
  `active_members` int(11) NOT NULL default '0',
  `page_count` int(20) NOT NULL default '0',
  `daily_average` int(11) NOT NULL default '0',
  `avatar` varchar(25) NOT NULL default 'avatar_default.png',
  `icon` varchar(25) NOT NULL default 'icon_default.png',
  `topic_id` int(10) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM CHARSET=latin1 AUTO_INCREMENT=200 ;

# --------------------------------------------------------

#
# Table structure for table `user_teams_stats`
#

CREATE TABLE `user_teams_stats` (
  `team_id` int(10) unsigned NOT NULL default '0',
  `date_updated` int(11) NOT NULL default '0',
  `daily_page_count` int(11) NOT NULL default '0',
  `total_page_count` int(11) NOT NULL default '0',
  `rank` smallint(6) NOT NULL default '0',
  KEY `team_id` (`team_id`)
) TYPE=MyISAM CHARSET=latin1;

# --------------------------------------------------------

#
# Table structure for table `users`
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
  `i_pmdefault` smallint(1) NOT NULL default '2',
  `u_id` int(10) unsigned NOT NULL auto_increment,
  `u_profile` int(10) unsigned NOT NULL default '0',
  `u_intlang` varchar(5) default 'en_EN',
  `u_privacy` tinyint(1) default '0',
  `team_1` int(10) unsigned NOT NULL default '0',
  `team_2` int(10) unsigned NOT NULL default '0',
  `team_3` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`username`),
  UNIQUE KEY `username` (`username`),
  KEY `u_id` (`u_id`)
) TYPE=MyISAM CHARSET=latin1 AUTO_INCREMENT=15796 ;

# --------------------------------------------------------

#
# Table structure for table `usersettings`
#

CREATE TABLE `usersettings` (
  `username` varchar(25) NOT NULL default '',
  `setting` varchar(25) NOT NULL default '',
  `value` varchar(25) NOT NULL default '',
  FULLTEXT KEY `setting` (`setting`)
) TYPE=MyISAM CHARSET=latin1;