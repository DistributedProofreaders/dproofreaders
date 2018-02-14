
#
# Table structure for table `access_log`
#
# Creation:
# Last update:
#

CREATE TABLE `access_log` (
  `timestamp` int(20) NOT NULL default '0',
  `subject_username` varchar(25) NOT NULL default '',
  `modifier_username` varchar(25) NOT NULL default '',
  `action` varchar(16) NOT NULL default '',
  `activity` varchar(32) NOT NULL default '',
  KEY `subject_username` (`subject_username`,`timestamp`)
);
# --------------------------------------------------------

#
# Table structure for table `authors`
#
# Creation:
# Last update:
#

CREATE TABLE `authors` (
  `author_id` mediumint(8) unsigned NOT NULL auto_increment,
  `other_names` varchar(40) NOT NULL default '',
  `last_name` varchar(25) NOT NULL default '',
  `byear` mediumint(9) NOT NULL default '0',
  `bmonth` tinyint(4) NOT NULL default '0',
  `bday` tinyint(4) NOT NULL default '0',
  `bcomments` varchar(20) NOT NULL default '',
  `dyear` mediumint(9) NOT NULL default '0',
  `dmonth` tinyint(4) NOT NULL default '0',
  `dday` tinyint(4) NOT NULL default '0',
  `dcomments` varchar(20) NOT NULL default '',
  `enabled` tinytext NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (`author_id`)
);
# --------------------------------------------------------

#
# Table structure for table `best_tally_rank`
#
# Creation:
# Last update:
#

CREATE TABLE `best_tally_rank` (
  `tally_name` char(2) NOT NULL default '',
  `holder_type` char(1) NOT NULL default '',
  `holder_id` int(6) unsigned NOT NULL default '0',
  `best_rank` int(6) NOT NULL default '0',
  `best_rank_timestamp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tally_name`,`holder_type`,`holder_id`)
);
# --------------------------------------------------------

#
# Table structure for table `biographies`
#
# Creation:
# Last update:
#

CREATE TABLE `biographies` (
  `bio_id` int(11) NOT NULL auto_increment,
  `author_id` int(11) NOT NULL default '0',
  `bio` text NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (`bio_id`)
) COMMENT='Contains biographies (see authors)';
# --------------------------------------------------------

#
# Table structure for table `current_tallies`
#
# Creation:
# Last update:
#

CREATE TABLE `current_tallies` (
  `tally_name` char(2) NOT NULL default '',
  `holder_type` char(1) NOT NULL default '',
  `holder_id` int(6) unsigned NOT NULL default '0',
  `tally_value` int(8) NOT NULL default '0',
  PRIMARY KEY  (`tally_name`,`holder_type`,`holder_id`)
);
# --------------------------------------------------------

#
# Table structure for table `image_sources`
#
# Creation:
# Last update:
#

CREATE TABLE `image_sources` (
  `code_name` varchar(10) NOT NULL default '',
  `display_name` varchar(30) NOT NULL default '',
  `full_name` varchar(100) NOT NULL default '',
  `info_page_visibility` tinyint(3) unsigned NOT NULL default '0',
  `is_active` tinyint(3) NOT NULL default '-1',
  `url` varchar(200) default NULL,
  `credit` varchar(200) default NULL,
  `ok_keep_images` tinyint(4) NOT NULL default '-1',
  `ok_show_images` tinyint(4) NOT NULL default '-1',
  `public_comment` varchar(255) default NULL,
  `internal_comment` text,
  UNIQUE KEY `code_name` (`code_name`),
  UNIQUE KEY `display_name` (`display_name`)
);
# --------------------------------------------------------

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
);
# --------------------------------------------------------

#
# Table structure for table `marc_records`
#
# Creation:
# Last update:
#

CREATE TABLE `marc_records` (
  `projectid` varchar(22) NOT NULL default '',
  `original_array` text NOT NULL,
  `updated_array` text NOT NULL,
  PRIMARY KEY  (`projectid`)
);
# --------------------------------------------------------

#
# Table structure for table `news_items`
#
# Creation:
# Last update:
#

CREATE TABLE `news_items` (
  `id` int(11) NOT NULL auto_increment,
  `date_posted` int(11) NOT NULL default '0',
  `news_page_id` varchar(8) default NULL,
  `status` varchar(8) NOT NULL default '',
  `ordering` smallint(6) NOT NULL default '0',
  `content` text NOT NULL,
  `locale` varchar(8) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  KEY `pageid_locale` (`news_page_id`,`locale`)
);
# --------------------------------------------------------

#
# Table structure for table `news_pages`
#
# Creation:
# Last update:
#

CREATE TABLE `news_pages` (
  `news_page_id` varchar(8) NOT NULL default '',
  `t_last_change` int(11) NOT NULL default '0',
  PRIMARY KEY  (`news_page_id`)
);
# --------------------------------------------------------

#
# Table structure for table `non_activated_users`
#
# Creation:
# Last update:
#

CREATE TABLE `non_activated_users` (
  `id` varchar(50) NOT NULL default '',
  `real_name` varchar(100) NOT NULL default '',
  `username` varchar(25) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `date_created` int(20) NOT NULL default '0',
  `email_updates` tinyint(1) NOT NULL default 0,
  `referrer` varchar(32) NOT NULL DEFAULT '',
  `http_referrer` varchar(256) NOT NULL DEFAULT '',
  `u_intlang` varchar(25) default '',
  `user_password` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`username`)
) COMMENT='Each row represents a not-yet-activated user, user_password ';
# --------------------------------------------------------

#
# Table structure for table `page_events`
#
# Creation:
# Last update:
#

CREATE TABLE `page_events` (
  `event_id` int(10) unsigned NOT NULL auto_increment,
  `timestamp` int(10) unsigned NOT NULL default '0',
  `projectid` varchar(22) NOT NULL default '',
  `image` varchar(12) NOT NULL default '',
  `event_type` varchar(16) NOT NULL default '',
  `username` varchar(25) NOT NULL default '',
  `round_id` char(2) default NULL,
  PRIMARY KEY  (`event_id`),
  KEY `projectid` (`projectid`,`image`,`round_id`),
  KEY `username` (`username`,`round_id`),
  KEY `projectid_username` (`projectid`,`username`),
  KEY `username_projectid_round_time` (`username`,`projectid`,`round_id`,`timestamp`)
);
# --------------------------------------------------------

#
# Table structure for table `past_tallies`
#
# Creation:
# Last update:
#

CREATE TABLE `past_tallies` (
  `timestamp` int(10) unsigned NOT NULL default '0',
  `holder_type` char(1) NOT NULL default '',
  `holder_id` int(6) unsigned NOT NULL default '0',
  `tally_name` char(2) NOT NULL default '',
  `tally_delta` int(8) NOT NULL default '0',
  `tally_value` int(8) NOT NULL default '0',
  PRIMARY KEY  (`tally_name`,`holder_type`,`holder_id`,`timestamp`),
  KEY `tallyboard_time` (`tally_name`,`holder_type`,`timestamp`)
);
# --------------------------------------------------------

#
# Table structure for table `pg_books`
#
# Creation:
# Last update:
#

CREATE TABLE `pg_books` (
  `etext_number` int(10) unsigned NOT NULL,
  `formats` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`etext_number`)
) COMMENT='Each row represents a different PG etext';
# --------------------------------------------------------

#
# Table structure for table `project_events`
#
# Creation:
# Last update:
#

CREATE TABLE `project_events` (
  `event_id` int(10) unsigned NOT NULL auto_increment,
  `timestamp` int(10) unsigned NOT NULL default '0',
  `projectid` varchar(22) NOT NULL default '',
  `who` varchar(25) NOT NULL default '',
  `event_type` varchar(15) NOT NULL default '',
  `details1` varchar(255) NOT NULL default '',
  `details2` varchar(255) NOT NULL default '',
  `details3` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`event_id`),
  KEY `project` (`projectid`),
  KEY `timestamp` (`timestamp`)
);
# --------------------------------------------------------

#
# Table structure for table `project_holds`
#
# Creation:
# Last update:
#

CREATE TABLE `project_holds` (
  `projectid` varchar(22) NOT NULL,
  `state` varchar(50) NOT NULL,
  PRIMARY KEY (`projectid`,`state`)
);
# --------------------------------------------------------

#
# Table structure for table `project_state_stats`
#
# Creation:
# Last update:
#

CREATE TABLE `project_state_stats` (
  `date` date NOT NULL default '2003-01-01',
  `state` varchar(50) NOT NULL default '0',
  `num_projects` int(12) NOT NULL default '0',
  `num_pages` int(12) NOT NULL default '0',
  `comments` varchar(255) default NULL,
  KEY `date` (`date`),
  KEY `state` (`state`)
);
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
  `username` varchar(25) NOT NULL default '',
  `comments` text NOT NULL,
  `projectid` varchar(22) NOT NULL default '',
  `special_code` varchar(20) NOT NULL default '',
  `checkedoutby` varchar(25) NOT NULL default '',
  `correctedby` varchar(25) NOT NULL default '',
  `modifieddate` int(20) NOT NULL default '0',
  `t_last_edit` int(11) NOT NULL default '0',
  `t_last_change_comments` int(11) NOT NULL default '0',
  `t_last_page_done` int(11) NOT NULL default '0',
  `scannercredit` tinytext NOT NULL,
  `state` varchar(50) default NULL,
  `postednum` mediumint(8) unsigned default NULL,
  `clearance` text NOT NULL,
  `year` varchar(4) NOT NULL default '',
  `topic_id` int(10) default NULL,
  `updated` tinyint(1) NOT NULL default '1',
  `int_level` int(11) NOT NULL default '0',
  `genre` varchar(50) NOT NULL default '',
  `difficulty` varchar(20) NOT NULL default 'average',
  `archived` tinyint(1) NOT NULL default '0',
  `postproofer` varchar(25) NOT NULL default '',
  `postcomments` text NOT NULL,
  `n_pages` smallint(4) unsigned NOT NULL default '0',
  `n_available_pages` smallint(4) unsigned NOT NULL default '0',
  `ppverifier` varchar(25) default NULL,
  `image_source` varchar(10) NOT NULL default '',
  `image_preparer` varchar(25) NOT NULL default '',
  `text_preparer` varchar(25) NOT NULL default '',
  `extra_credits` tinytext NOT NULL,
  `smoothread_deadline` int(20) NOT NULL default '0',
  `up_projectid` int(10) default '0',
  `deletion_reason` tinytext NOT NULL,
  PRIMARY KEY  (`projectid`),
  KEY `special_code` (`special_code`),
  KEY `projectid_archived_state` (`projectid`,`archived`,`state`),
  KEY `state_moddate` (`state`,`modifieddate`)
);
# --------------------------------------------------------

#
# Table structure for table `queue_defns`
#
# Creation:
# Last update:
#

CREATE TABLE `queue_defns` (
  `round_id` char(2) NOT NULL default '',
  `ordering` mediumint(5) NOT NULL default '0',
  `enabled` tinyint(1) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `project_selector` text NOT NULL,
  `release_criterion` text NOT NULL,
  `comment` text,
  UNIQUE KEY `ordering` (`round_id`,`ordering`),
  UNIQUE KEY `name` (`round_id`,`name`)
);
# --------------------------------------------------------

#
# Table structure for table `quiz_passes`
#
# Creation:
# Last update:
#

CREATE TABLE `quiz_passes` (
  `username` varchar(25) NOT NULL default '',
  `date` int(20) NOT NULL default '0',
  `quiz_page` varchar(15) NOT NULL default '',
  `result` varchar(10) NOT NULL default '',
  KEY `username` (`username`,`quiz_page`)
);
# --------------------------------------------------------

#
# Table structure for table `rules`
#
# Creation:
# Last update:
#

CREATE TABLE `rules` (
  `id` int(4) NOT NULL auto_increment,
  `document` varchar(255) default NULL,
  `anchor` varchar(255) default NULL,
  `subject` varchar(100) NOT NULL default '',
  `rule` text NOT NULL,
  PRIMARY KEY  (`id`)
);
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
  PRIMARY KEY  (`sid`),
  KEY `expiration` (`expiration`)
);
# --------------------------------------------------------

#
# Table structure for table `site_tally_goals`
#
# Creation:
# Last update:
#

CREATE TABLE `site_tally_goals` (
  `date` date NOT NULL default '2000-01-01',
  `tally_name` char(2) NOT NULL default '',
  `goal` int(6) NOT NULL default '0',
  PRIMARY KEY  (`date`,`tally_name`)
);
# --------------------------------------------------------

#
# Table structure for table `smoothread`
#
# Creation:
# Last update:
#

CREATE TABLE `smoothread` (
  `projectid` varchar(22) NOT NULL default '',
  `user` varchar(25) NOT NULL default '',
  `committed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`projectid`,`user`),
  KEY `user` (`user`)
) COMMENT='Each row represents an association between a user and a proj';
# --------------------------------------------------------

#
# Table structure for table `special_days`
#
# Creation:
# Last update:
#

CREATE TABLE `special_days` (
  `spec_code` varchar(20) NOT NULL default '',
  `display_name` varchar(80) NOT NULL default '',
  `enable` tinyint(1) NOT NULL default '1',
  `comment` varchar(255) default NULL,
  `color` varchar(8) NOT NULL default '',
  `open_day` tinyint(2) default NULL,
  `open_month` tinyint(2) default NULL,
  `close_day` tinyint(2) default NULL,
  `close_month` tinyint(2) default NULL,
  `date_changes` varchar(100) default NULL,
  `info_url` varchar(255) default NULL,
  `image_url` varchar(255) default NULL,
  UNIQUE KEY `spec_code` (`spec_code`)
) COMMENT='definitions of SPECIAL days';
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
  `related_postings` mediumtext NOT NULL,
  KEY `task_id` (`task_id`)
);
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
  `comment` mediumtext NOT NULL,
  PRIMARY KEY (`task_id`,`u_id`,`comment_date`)
);
# --------------------------------------------------------

#
# Table structure for table `tasks_related_tasks`
#
# Creation:
# Last update:
#

CREATE TABLE `tasks_related_tasks` (
  `task_id_1` mediumint(9) NOT NULL,
  `task_id_2` mediumint(9) NOT NULL,
  PRIMARY KEY (`task_id_1`,`task_id_2`),
  KEY `task_id_2` (`task_id_2`)
);
# --------------------------------------------------------

#
# Table structure for table `tasks_votes`
#
# Creation:
# Last update:
#

CREATE TABLE `tasks_votes` (
  `id` int(11) NOT NULL auto_increment,
  `task_id` mediumint(9) NOT NULL default '0',
  `u_id` int(10) NOT NULL default '0',
  `vote_os` tinyint(1) NOT NULL default '0',
  `vote_browser` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `id` (`id`),
  KEY `task_id` (`task_id`,`u_id`)
);
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
);
# --------------------------------------------------------

#
# Table structure for table `uber_projects`
#
# Creation:
# Last update:
#

CREATE TABLE `uber_projects` (
  `up_projectid` int(10) NOT NULL auto_increment,
  `up_nameofwork` varchar(255) NOT NULL default '',
  `up_topic_id` int(10) default NULL,
  `up_contents_post_id` int(10) default NULL,
  `up_modifieddate` int(20) NOT NULL default '0',
  `up_enabled` tinyint(1) default '1',
  `up_description` text,
  `d_nameofwork` varchar(255) default NULL,
  `d_authorsname` varchar(255) default NULL,
  `d_language` varchar(255) default NULL,
  `d_comments` text,
  `d_special` varchar(20) default NULL,
  `d_checkedoutby` varchar(25) default NULL,
  `d_scannercredit` tinytext,
  `d_clearance` text,
  `d_year` varchar(4) default NULL,
  `d_genre` varchar(50) default NULL,
  `d_difficulty` varchar(20) default NULL,
  `d_image_source` varchar(10) default NULL,
  `d_image_preparer` varchar(25) default NULL,
  `d_text_preparer` varchar(25) default NULL,
  `d_extra_credits` tinytext,
  PRIMARY KEY  (`up_projectid`)
);
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
  `L_hour` mediumint(8) unsigned default NULL,
  `L_day` mediumint(8) unsigned default NULL,
  `L_week` mediumint(8) unsigned default NULL,
  `L_4wks` mediumint(8) unsigned default NULL,
  `A_hour` mediumint(8) unsigned default NULL,
  `A_day` mediumint(8) unsigned default NULL,
  `A_week` mediumint(8) unsigned default NULL,
  `A_4wks` mediumint(8) unsigned default NULL,
  `comments` varchar(255) default NULL,
  KEY `timestamp_ndx` (`time_stamp`)
);
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
);
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
  `v_fntf` tinyint(1) default '2',
  `v_fntf_other` varchar(32) default '',
  `v_zoom` smallint(3) default '59',
  `v_tframe` tinyint(2) default '50',
  `v_tlines` tinyint(2) default '40',
  `v_tchars` tinyint(2) default '65',
  `v_tscroll` tinyint(1) default '1',
  `v_twrap` tinyint(1) default '0',
  `h_fnts` tinyint(2) default '0',
  `h_fntf` tinyint(1) default '2',
  `h_fntf_other` varchar(32) default '',
  `h_zoom` smallint(3) default '76',
  `h_tframe` tinyint(2) default '35',
  `h_tlines` tinyint(2) default '6',
  `h_tchars` tinyint(2) default '70',
  `h_tscroll` tinyint(1) default '1',
  `h_twrap` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `u_ref` (`u_ref`)
);
# --------------------------------------------------------

#
# Table structure for table `user_project_info`
#
# Creation:
# Last update:
#

CREATE TABLE `user_project_info` (
  `username` varchar(25) NOT NULL,
  `projectid` varchar(22) NOT NULL,
  `t_latest_home_visit` int(10) unsigned NOT NULL default '0',
  `t_latest_page_event` int(10) unsigned NOT NULL default '0',
  `iste_round_available` tinyint(1) NOT NULL default '0',
  `iste_round_complete` tinyint(1) NOT NULL default '0',
  `iste_pp_enter` tinyint(1) NOT NULL default '0',
  `iste_sr_available` tinyint(1) NOT NULL default '0',
  `iste_sr_complete` tinyint(1) NOT NULL default '0',
  `iste_ppv_enter` tinyint(1) NOT NULL default '0',
  `iste_posted` tinyint(1) NOT NULL default '0',
  `iste_sr_reported` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`username`,`projectid`),
  KEY `projectid` (`projectid`)
);
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
  `daily_average` int(11) NOT NULL default '0',
  `avatar` varchar(25) NOT NULL default 'avatar_default.png',
  `icon` varchar(25) NOT NULL default 'icon_default.png',
  `topic_id` int(10) default NULL,
  `latestUser` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `teamname` (`teamname`)
);
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
  `email` varchar(100) NOT NULL default '',
  `date_created` int(20) NOT NULL default '0',
  `last_login` int(20) NOT NULL default '0',
  `t_last_activity` int(10) unsigned NOT NULL default '0',
  `email_updates` tinyint(1) default '1',
  `referrer` varchar(32) NOT NULL DEFAULT '',
  `http_referrer` varchar(256) NOT NULL DEFAULT '',
  `u_neigh` tinyint(4) NOT NULL default '0',
  `u_align` tinyint(1) NOT NULL default '0',
  `i_prefs` tinyint(1) default '0',
  `i_theme` varchar(100) NOT NULL default 'project_gutenberg',
  `i_pmdefault` smallint(1) NOT NULL default '2',
  `u_id` int(10) unsigned NOT NULL auto_increment,
  `u_profile` int(10) unsigned NOT NULL default '0',
  `u_intlang` varchar(25) default '',
  `u_privacy` tinyint(1) default '0',
  `team_1` int(10) unsigned NOT NULL default '0',
  `team_2` int(10) unsigned NOT NULL default '0',
  `team_3` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`username`),
  UNIQUE KEY `username` (`username`),
  KEY `u_id` (`u_id`),
  KEY `last_login` (`last_login`),
  KEY `t_last_activity` (`t_last_activity`)
);
# --------------------------------------------------------

#
# Table structure for table `usersettings`
#
# Creation:
# Last update:
#

CREATE TABLE `usersettings` (
  `username` varchar(25) NOT NULL default '',
  `setting` varchar(64) NOT NULL default '',
  `value` varchar(25) NOT NULL default '',
  KEY `username_setting_val` (`username`,`setting`,`value`),
  KEY `setting` (`setting`,`value`),
  KEY `value` (`value`,`setting`)
);
# --------------------------------------------------------

#
# Table structure for table `wordcheck_events`
#
# Creation:
# Last update:
#

CREATE TABLE `wordcheck_events` (
  `check_id` int(10) unsigned NOT NULL auto_increment,
  `projectid` varchar(22) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `image` varchar(12) NOT NULL,
  `round_id` char(2) NOT NULL,
  `username` varchar(25) NOT NULL,
  `suggestions` text,
  `corrections` text,
  PRIMARY KEY  (`check_id`),
  KEY `pc_compound` (`projectid`,`timestamp`,`image`)
);
# --------------------------------------------------------

