# phpMyAdmin MySQL-Dump
# version 2.3.3pl1
# http://www.phpmyadmin.net/ (download page)
#
# Host: josephgruber.com
# Generation Time: Mar 17, 2003 at 12:47 PM
# Server version: 4.00.10
# PHP Version: 4.3.0
# Database : `dproofreaders`
# --------------------------------------------------------

#
# Table structure for table `news`
#

CREATE TABLE news (
  uid int(11) NOT NULL auto_increment,
  date_posted varchar(10) NOT NULL default '',
  message text NOT NULL,
  KEY uid (uid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `pagestats`
#

CREATE TABLE pagestats (
  year smallint(4) NOT NULL default '0',
  month tinyint(2) NOT NULL default '0',
  day tinyint(2) NOT NULL default '0',
  date date NOT NULL default '0000-00-00',
  pages int(12) NOT NULL default '0',
  dailygoal int(12) NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `phpbb_users`
#

CREATE TABLE phpbb_users (
  user_id mediumint(8) NOT NULL default '0',
  user_active tinyint(1) default '1',
  username varchar(25) NOT NULL default '',
  user_password varchar(32) NOT NULL default '',
  user_session_time int(11) NOT NULL default '0',
  user_session_page smallint(5) NOT NULL default '0',
  user_lastvisit int(11) NOT NULL default '0',
  user_regdate int(11) NOT NULL default '0',
  user_level tinyint(4) default '0',
  user_posts mediumint(8) unsigned NOT NULL default '0',
  user_timezone decimal(4,2) NOT NULL default '0.00',
  user_style tinyint(4) default NULL,
  user_lang varchar(255) default NULL,
  user_dateformat varchar(14) NOT NULL default 'd M Y H:i',
  user_new_privmsg smallint(5) unsigned NOT NULL default '0',
  user_unread_privmsg smallint(5) unsigned NOT NULL default '0',
  user_last_privmsg int(11) NOT NULL default '0',
  user_emailtime int(11) default NULL,
  user_viewemail tinyint(1) default NULL,
  user_attachsig tinyint(1) default NULL,
  user_allowhtml tinyint(1) default '1',
  user_allowbbcode tinyint(1) default '1',
  user_allowsmile tinyint(1) default '1',
  user_allowavatar tinyint(1) NOT NULL default '1',
  user_allow_pm tinyint(1) NOT NULL default '1',
  user_allow_viewonline tinyint(1) NOT NULL default '1',
  user_notify tinyint(1) NOT NULL default '1',
  user_notify_pm tinyint(1) NOT NULL default '1',
  user_popup_pm tinyint(1) NOT NULL default '0',
  user_rank int(11) default '0',
  user_avatar varchar(100) default NULL,
  user_avatar_type tinyint(4) NOT NULL default '0',
  user_email varchar(255) default NULL,
  user_icq varchar(15) default NULL,
  user_website varchar(100) default NULL,
  user_from varchar(100) default NULL,
  user_sig text,
  user_sig_bbcode_uid varchar(10) default NULL,
  user_aim varchar(255) default NULL,
  user_yim varchar(255) default NULL,
  user_msnm varchar(255) default NULL,
  user_occ varchar(100) default NULL,
  user_interests varchar(255) default NULL,
  user_actkey varchar(32) default NULL,
  user_newpasswd varchar(32) default NULL,
  PRIMARY KEY  (user_id),
  KEY user_session_time (user_session_time)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `projects`
#

CREATE TABLE projects (
  nameofwork varchar(255) NOT NULL default '',
  authorsname varchar(255) NOT NULL default '',
  language varchar(255) NOT NULL default '',
  username varchar(255) NOT NULL default '',
  comments text NOT NULL,
  projectid text NOT NULL,
  checkedoutby text NOT NULL,
  modifieddate int(20) NOT NULL default '0',
  scannercredit tinytext NOT NULL,
  state varchar(50) default NULL,
  txtlink varchar(200) default NULL,
  ziplink varchar(200) default NULL,
  htmllink varchar(200) default NULL,
  postednum smallint(5) unsigned NOT NULL default '6000',
  clearance varchar(200) NOT NULL default '',
  year varchar(4) NOT NULL default '',
  topic_id int(10) default NULL,
  updated tinyint(1) NOT NULL default '1',
  int_level int(11) NOT NULL default '0',
  genre varchar(50) NOT NULL default '',
  archived tinyint(1) NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `ranks`
#

CREATE TABLE ranks (
  rankid tinyint(2) NOT NULL default '0',
  rankname text,
  minpages smallint(5) default NULL,
  maxpages int(8) default NULL,
  imagepath varchar(50) default NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `rules`
#

CREATE TABLE rules (
  id int(4) NOT NULL auto_increment,
  subject varchar(100) NOT NULL default '',
  rule text NOT NULL,
  doc varchar(10) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `user_profiles`
#

CREATE TABLE user_profiles (
  id int(10) unsigned NOT NULL auto_increment,
  u_ref int(10) unsigned NOT NULL default '0',
  profilename varchar(30) NOT NULL default 'default',
  i_res tinyint(1) default '1',
  i_type tinyint(1) default '0',
  i_layout tinyint(1) default '0',
  i_toolbar tinyint(1) default '0',
  i_statusbar tinyint(1) default '0',
  i_newwin tinyint(1) default '1',
  v_fnts tinyint(2) default '0',
  v_fntf tinyint(1) default '0',
  v_zoom smallint(3) default '59',
  v_tframe tinyint(2) default '50',
  v_tlines tinyint(2) default '40',
  v_tchars tinyint(2) default '65',
  v_tscroll tinyint(1) default '1',
  v_twrap tinyint(1) default '1',
  h_fnts tinyint(2) default '0',
  h_fntf tinyint(1) default '0',
  h_zoom smallint(3) default '76',
  h_tframe tinyint(2) default '35',
  h_tlines tinyint(2) default '6',
  h_tchars tinyint(2) default '70',
  h_tscroll tinyint(1) default '1',
  h_twrap tinyint(1) default '1',
  PRIMARY KEY  (id),
  KEY u_ref (u_ref)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `user_teams`
#

CREATE TABLE user_teams (
  id int(10) unsigned NOT NULL auto_increment,
  teamname varchar(50) NOT NULL default 'default',
  team_info text NOT NULL,
  createdby varchar(25) NOT NULL default '',
  owner int(10) unsigned NOT NULL default '0',
  created int(20) NOT NULL default '0',
  member_count int(20) NOT NULL default '0',
  page_count int(20) NOT NULL default '0',
  avatar varchar(25) NOT NULL default 'avatar_default.png',
  icon varchar(25) NOT NULL default 'icon_default.png',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `users`
#

CREATE TABLE users (
  id varchar(50) NOT NULL default '',
  real_name varchar(100) NOT NULL default '',
  username varchar(25) NOT NULL default '',
  email varchar(50) NOT NULL default '',
  manager varchar(5) NOT NULL default '',
  date_created int(20) NOT NULL default '0',
  last_login int(20) NOT NULL default '0',
  emailupdates varchar(4) NOT NULL default '',
  pagescompleted mediumint(8) default '0',
  postprocessor tinytext NOT NULL,
  sitemanager tinytext NOT NULL,
  active tinytext NOT NULL,
  u_lang tinyint(1) default '0',
  email_updates tinyint(1) default '1',
  u_plist tinyint(1) default '3',
  i_prefs tinyint(1) default '0',
  u_top10 tinyint(1) NOT NULL default '1',
  u_neigh tinyint(1) NOT NULL default '5',
  u_id int(10) unsigned NOT NULL auto_increment,
  u_profile int(10) unsigned NOT NULL default '0',
  team_1 int(10) unsigned NOT NULL default '0',
  team_2 int(10) unsigned NOT NULL default '0',
  team_3 int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (username),
  KEY u_id (u_id)
) TYPE=MyISAM;