# MySQL dump 10.2
#
# Host: localhost    Database: dproofreaders
# ------------------------------------------------------
# Server version	4.1.1-alpha-standard

/*!40101 SET NAMES latin1*/;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=NO_AUTO_VALUE_ON_ZERO */;

#
# Table structure for table `job_logs`
#
# Creation:
# Last update:
#

CREATE TABLE `job_logs` (
  `filename` varchar(40) NOT NULL default ''' ''',
  `tracetime` int(12) unsigned NOT NULL default '0',
  `event` varchar(20) NOT NULL default ''' ''',
  `comments` varchar(255) default NULL
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `marc_records`
#
# Creation:
# Last update:
#

CREATE TABLE `marc_records` (
  `projectid` varchar(22) NOT NULL default '',
  `original_marc` text NOT NULL,
  `updated_marc` text NOT NULL,
  `original_array` text NOT NULL,
  `updated_array` text NOT NULL
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `member_stats`
#
# Creation:
# Last update:
#

CREATE TABLE `member_stats` (
  `u_id` int(10) unsigned NOT NULL default '0',
  `date_updated` int(11) NOT NULL default '0',
  `total_pagescompleted` mediumint(9) NOT NULL default '0',
  `daily_pagescompleted` mediumint(9) NOT NULL default '0',
  `rank` mediumint(9) NOT NULL default '0',
  KEY `u_id` (`u_id`),
  KEY `daily_pagescompleted` (`daily_pagescompleted`),
  KEY `date_updated` (`date_updated`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `news`
#
# Creation:
# Last update:
#

CREATE TABLE `news` (
  `uid` int(11) NOT NULL auto_increment,
  `date_posted` varchar(10) NOT NULL default '',
  `message` text NOT NULL,
  KEY `uid` (`uid`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `page_counts`
#
# Creation:
# Last update:
#

CREATE TABLE `page_counts` (
  `projectid` char(22) NOT NULL default '',
  `total_pages` smallint(4) unsigned NOT NULL default '0',
  `avail_pages` smallint(4) unsigned NOT NULL default '0',
  UNIQUE KEY `projectid` (`projectid`)
) TYPE=HEAP DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `pagestats`
#
# Creation:
# Last update:
#

CREATE TABLE `pagestats` (
  `year` smallint(4) NOT NULL default '2004',
  `month` tinyint(2) NOT NULL default '6',
  `day` tinyint(2) NOT NULL default '0',
  `date` date NOT NULL default '2004-06-00',
  `pages` int(12) NOT NULL default '0',
  `dailygoal` int(12) NOT NULL default '5900',
  `comments` varchar(255) default NULL,
  PRIMARY KEY  (`date`),
  KEY `yearmonth` (`year`,`month`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `phpbb_forums`
#
# Creation:
# Last update:
#

CREATE TABLE `phpbb_forums` (
  `forum_id` smallint(5) unsigned NOT NULL default '0',
  `cat_id` mediumint(8) unsigned NOT NULL default '0',
  `forum_name` varchar(150) default NULL,
  `forum_desc` text,
  `forum_status` tinyint(4) NOT NULL default '0',
  `forum_order` mediumint(8) unsigned NOT NULL default '1',
  `forum_posts` mediumint(8) unsigned NOT NULL default '0',
  `forum_topics` mediumint(8) unsigned NOT NULL default '0',
  `forum_last_post_id` mediumint(8) unsigned NOT NULL default '0',
  `prune_next` int(11) default NULL,
  `prune_enable` tinyint(1) NOT NULL default '0',
  `auth_view` tinyint(2) NOT NULL default '0',
  `auth_read` tinyint(2) NOT NULL default '0',
  `auth_post` tinyint(2) NOT NULL default '0',
  `auth_reply` tinyint(2) NOT NULL default '0',
  `auth_edit` tinyint(2) NOT NULL default '0',
  `auth_delete` tinyint(2) NOT NULL default '0',
  `auth_sticky` tinyint(2) NOT NULL default '0',
  `auth_announce` tinyint(2) NOT NULL default '0',
  `auth_vote` tinyint(2) NOT NULL default '0',
  `auth_pollcreate` tinyint(2) NOT NULL default '0',
  `auth_attachments` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`forum_id`),
  KEY `forums_order` (`forum_order`),
  KEY `cat_id` (`cat_id`),
  KEY `forum_last_post_id` (`forum_last_post_id`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `phpbb_posts`
#
# Creation:
# Last update:
#

CREATE TABLE `phpbb_posts` (
  `post_id` mediumint(8) unsigned NOT NULL auto_increment,
  `topic_id` mediumint(8) unsigned NOT NULL default '0',
  `forum_id` smallint(5) unsigned NOT NULL default '0',
  `poster_id` mediumint(8) NOT NULL default '0',
  `post_time` int(11) NOT NULL default '0',
  `poster_ip` varchar(8) NOT NULL default '',
  `post_username` varchar(25) default NULL,
  `enable_bbcode` tinyint(1) NOT NULL default '1',
  `enable_html` tinyint(1) NOT NULL default '0',
  `enable_smilies` tinyint(1) NOT NULL default '1',
  `enable_sig` tinyint(1) NOT NULL default '1',
  `post_edit_time` int(11) default NULL,
  `post_edit_count` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `forum_id` (`forum_id`),
  KEY `topic_id` (`topic_id`),
  KEY `poster_id` (`poster_id`),
  KEY `post_time` (`post_time`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `phpbb_posts_text`
#
# Creation:
# Last update:
#

CREATE TABLE `phpbb_posts_text` (
  `post_id` mediumint(8) unsigned NOT NULL default '0',
  `bbcode_uid` varchar(10) NOT NULL default '',
  `post_subject` varchar(60) default NULL,
  `post_text` text,
  PRIMARY KEY  (`post_id`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `phpbb_privmsgs`
#
# Creation:
# Last update:
#

CREATE TABLE `phpbb_privmsgs` (
  `privmsgs_id` mediumint(8) unsigned NOT NULL auto_increment,
  `privmsgs_type` tinyint(4) NOT NULL default '0',
  `privmsgs_subject` varchar(255) NOT NULL default '0',
  `privmsgs_from_userid` mediumint(8) NOT NULL default '0',
  `privmsgs_to_userid` mediumint(8) NOT NULL default '0',
  `privmsgs_date` int(11) NOT NULL default '0',
  `privmsgs_ip` varchar(8) NOT NULL default '',
  `privmsgs_enable_bbcode` tinyint(1) NOT NULL default '1',
  `privmsgs_enable_html` tinyint(1) NOT NULL default '0',
  `privmsgs_enable_smilies` tinyint(1) NOT NULL default '1',
  `privmsgs_attach_sig` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`privmsgs_id`),
  KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
  KEY `privmsgs_to_userid` (`privmsgs_to_userid`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `phpbb_topics`
#
# Creation:
# Last update:
#

CREATE TABLE `phpbb_topics` (
  `topic_id` mediumint(8) unsigned NOT NULL auto_increment,
  `forum_id` smallint(8) unsigned NOT NULL default '0',
  `topic_title` char(60) NOT NULL default '',
  `topic_poster` mediumint(8) NOT NULL default '0',
  `topic_time` int(11) NOT NULL default '0',
  `topic_views` mediumint(8) unsigned NOT NULL default '0',
  `topic_replies` mediumint(8) unsigned NOT NULL default '0',
  `topic_status` tinyint(3) NOT NULL default '0',
  `topic_vote` tinyint(1) NOT NULL default '0',
  `topic_type` tinyint(3) NOT NULL default '0',
  `topic_first_post_id` mediumint(8) unsigned NOT NULL default '0',
  `topic_last_post_id` mediumint(8) unsigned NOT NULL default '0',
  `topic_moved_id` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `topic_moved_id` (`topic_moved_id`),
  KEY `topic_status` (`topic_status`),
  KEY `topic_type` (`topic_type`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `phpbb_users`
#
# Creation:
# Last update:
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
  KEY `user_session_time` (`user_session_time`),
  KEY `username` (`username`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `project_pages`
#
# Creation:
# Last update:
#

CREATE TABLE `project_pages` (
  `projectid` varchar(25) NOT NULL default '',
  `fileid` varchar(20) NOT NULL default '',
  `image` varchar(8) NOT NULL default '',
  `master_text` longtext NOT NULL,
  `round1_text` longtext NOT NULL,
  `round2_text` longtext NOT NULL,
  `round1_user` varchar(25) NOT NULL default '',
  `round2_user` varchar(25) NOT NULL default '',
  `round1_time` int(20) NOT NULL default '0',
  `round2_time` int(20) NOT NULL default '0',
  `state` varchar(50) NOT NULL default '',
  `b_user` varchar(25) NOT NULL default '',
  `b_code` int(1) NOT NULL default '0',
  `metadata` set('frontmatter','backmatter','division','verse','poetry','letter','toc','footnote','sidenote','epigraph','table','list','math','drawing','badscan','blank','illustration','missing','drawing') NOT NULL default '',
  `orig_page_num` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`projectid`,`fileid`),
  KEY `round1_user` (`round1_user`),
  KEY `round2_user` (`round2_user`),
  KEY `round1_time` (`round1_time`),
  KEY `round2_time` (`round2_time`),
  KEY `state` (`state`),
  KEY `ProjectidStateIdx` (`projectid`,`state`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `project_state_stats`
#
# Creation:
# Last update:
#

CREATE TABLE `project_state_stats` (
  `year` smallint(4) NOT NULL default '2003',
  `month` tinyint(2) NOT NULL default '0',
  `day` tinyint(2) NOT NULL default '0',
  `date` date NOT NULL default '2003-00-00',
  `state` varchar(50) NOT NULL default '0',
  `num_projects` int(12) NOT NULL default '0',
  `comments` varchar(255) default NULL,
  KEY `date` (`date`),
  KEY `state` (`state`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `projects`
#
# Creation:
# Last update:
#

CREATE TABLE `projects` (
  `nameofwork` varchar(255) NOT NULL default '',
  `authorsname` varchar(255) NOT NULL default '',
  `language` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default '',
  `comments` text NOT NULL,
  `projectid` varchar(22) NOT NULL default '',
  `checkedoutby` text NOT NULL,
  `correctedby` varchar(25) NOT NULL default '',
  `modifieddate` int(20) NOT NULL default '0',
  `scannercredit` tinytext NOT NULL,
  `state` varchar(50) default NULL,
  `txtlink` varchar(200) default NULL,
  `ziplink` varchar(200) default NULL,
  `htmllink` varchar(200) default NULL,
  `postednum` smallint(5) unsigned NOT NULL default '6000',
  `clearance` text NOT NULL,
  `year` varchar(4) NOT NULL default '',
  `topic_id` int(10) default NULL,
  `updated` tinyint(1) NOT NULL default '1',
  `int_level` int(11) NOT NULL default '0',
  `genre` varchar(50) NOT NULL default '',
  `difficulty` varchar(20) NOT NULL default 'average',
  `archived` tinyint(1) NOT NULL default '0',
  `postproofer` varchar(255) NOT NULL default '',
  `postcomments` text NOT NULL,
  PRIMARY KEY  (`projectid`),
  KEY `state` (`state`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `queue_defns`
#
# Creation:
# Last update:
#

CREATE TABLE `queue_defns` (
  `ordering` mediumint(5) NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `project_selector` text NOT NULL,
  `release_criterion` text NOT NULL,
  `comment` text,
  UNIQUE KEY `ordering` (`ordering`),
  UNIQUE KEY `name` (`name`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `rules`
#
# Creation:
# Last update:
#

CREATE TABLE `rules` (
  `id` int(4) NOT NULL auto_increment,
  `doc` varchar(10) NOT NULL default '',
  `subject` varchar(100) NOT NULL default '',
  `rule` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `sessions`
#
# Creation:
# Last update:
#

CREATE TABLE `sessions` (
  `sid` varchar(32) NOT NULL default '',
  `expiration` int(11) NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`sid`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `stats_hourly_pages_completed`
#
# Creation:
# Last update:
#

CREATE TABLE `stats_hourly_pages_completed` (
  `sample_time` varchar(20) NOT NULL default '',
  `pages_completed` mediumint(7) NOT NULL default '0'
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `tasks`
#
# Creation:
# Last update:
#

CREATE TABLE `tasks` (
  `task_id` mediumint(9) NOT NULL auto_increment,
  `task_summary` varchar(80) NOT NULL default '',
  `task_type` tinyint(3) unsigned NOT NULL default '0',
  `task_category` tinyint(3) unsigned NOT NULL default '0',
  `task_status` tinyint(3) unsigned NOT NULL default '0',
  `task_assignee` mediumint(8) unsigned NOT NULL default '0',
  `task_severity` tinyint(3) unsigned NOT NULL default '0',
  `task_priority` tinyint(3) unsigned NOT NULL default '3',
  `task_os` tinyint(3) unsigned NOT NULL default '0',
  `task_browser` tinyint(3) unsigned NOT NULL default '0',
  `task_version` tinyint(3) unsigned NOT NULL default '0',
  `task_details` mediumtext NOT NULL,
  `date_opened` int(11) NOT NULL default '0',
  `opened_by` mediumint(9) NOT NULL default '0',
  `date_closed` int(11) NOT NULL default '0',
  `closed_by` mediumint(9) NOT NULL default '0',
  `closed_reason` tinyint(4) NOT NULL default '0',
  `date_edited` int(11) NOT NULL default '0',
  `edited_by` mediumint(9) NOT NULL default '0',
  `percent_complete` tinyint(3) NOT NULL default '0',
  `related_tasks` mediumtext NOT NULL,
  KEY `task_id` (`task_id`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `tasks_comments`
#
# Creation:
# Last update:
#

CREATE TABLE `tasks_comments` (
  `task_id` mediumint(9) NOT NULL default '0',
  `u_id` mediumint(9) NOT NULL default '0',
  `comment_date` int(11) NOT NULL default '0',
  `comment` mediumtext NOT NULL
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `themes`
#
# Creation:
# Last update:
#

CREATE TABLE `themes` (
  `theme_id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `unixname` varchar(100) NOT NULL default '',
  `created_by` varchar(25) NOT NULL default '',
  KEY `theme_id` (`theme_id`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `user_active_log`
#
# Creation:
# Last update:
#

CREATE TABLE `user_active_log` (
  `year` smallint(4) unsigned NOT NULL default '2003',
  `month` tinyint(2) unsigned NOT NULL default '0',
  `day` tinyint(2) unsigned NOT NULL default '0',
  `hour` smallint(2) unsigned NOT NULL default '0',
  `time_stamp` int(10) unsigned NOT NULL default '0',
  `U_lasthour` mediumint(6) unsigned NOT NULL default '0',
  `U_day` mediumint(6) unsigned NOT NULL default '0',
  `U_week` mediumint(7) unsigned NOT NULL default '0',
  `U_4wks` mediumint(7) unsigned NOT NULL default '0',
  `comments` varchar(255) default NULL,
  KEY `timestamp_ndx` (`time_stamp`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `user_filters`
#
# Creation:
# Last update:
#

CREATE TABLE `user_filters` (
  `username` varchar(25) NOT NULL default '',
  `filtertype` varchar(25) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`username`,`filtertype`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `user_profiles`
#
# Creation:
# Last update:
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
  `v_twrap` tinyint(1) default '0',
  `h_fnts` tinyint(2) default '0',
  `h_fntf` tinyint(1) default '0',
  `h_zoom` smallint(3) default '76',
  `h_tframe` tinyint(2) default '35',
  `h_tlines` tinyint(2) default '6',
  `h_tchars` tinyint(2) default '70',
  `h_tscroll` tinyint(1) default '1',
  `h_twrap` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `u_ref` (`u_ref`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `user_teams`
#
# Creation:
# Last update:
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
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `user_teams_stats`
#
# Creation:
# Last update:
#

CREATE TABLE `user_teams_stats` (
  `team_id` int(10) unsigned NOT NULL default '0',
  `date_updated` int(11) NOT NULL default '0',
  `daily_page_count` int(11) NOT NULL default '0',
  `total_page_count` int(11) NOT NULL default '0',
  `rank` smallint(6) NOT NULL default '0',
  KEY `team_id` (`team_id`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `users`
#
# Creation:
# Last update:
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
  `task_priority` int(4) NOT NULL default '0',
  PRIMARY KEY  (`username`),
  UNIQUE KEY `username` (`username`),
  KEY `u_id` (`u_id`),
  KEY `last_login` (`last_login`),
  KEY `pages_index` (`pagescompleted`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

#
# Table structure for table `usersettings`
#
# Creation:
# Last update:
#

CREATE TABLE `usersettings` (
  `username` varchar(25) NOT NULL default '',
  `setting` varchar(25) NOT NULL default '',
  `value` varchar(25) NOT NULL default '',
  FULLTEXT KEY `setting` (`setting`)
) TYPE=MyISAM DEFAULT CHARSET=latin1;
# --------------------------------------------------------

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;

