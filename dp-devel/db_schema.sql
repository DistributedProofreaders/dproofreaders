# phpMyAdmin MySQL-Dump
# version 2.3.2
# http://www.phpmyadmin.net/ (download page)
#
# Host: josephgruber.com
# Generation Time: Nov 26, 2002 at 11:35 AM
# Server version: 3.23.53
# PHP Version: 4.2.4-dev
# Database : `dproofreaders`
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
  modifieddate text NOT NULL,
  scannercredit tinytext NOT NULL,
  state tinyint(3) unsigned NOT NULL default '0',
  txtlink varchar(200) default NULL,
  ziplink varchar(200) default NULL,
  htmllink varchar(200) default NULL,
  postednum smallint(5) unsigned NOT NULL default '6000',
  clearance varchar(200) NOT NULL default '',
  year varchar(4) NOT NULL default ''
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
# Table structure for table `states`
#

CREATE TABLE states (
  id tinyint(3) unsigned NOT NULL default '0',
  name tinytext NOT NULL
) TYPE=MyISAM COMMENT='States that a project or a page can be in';
# --------------------------------------------------------

#
# Table structure for table `tempstats`
#

CREATE TABLE tempstats (
  date date NOT NULL default '0000-00-00',
  goal int(12) NOT NULL default '0',
  prevmonth int(12) NOT NULL default '0',
  currmonth int(12) NOT NULL default '0'
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
  date_created varchar(8) NOT NULL default '',
  last_login varchar(8) NOT NULL default '',
  emailupdates varchar(4) NOT NULL default '',
  pagescompleted mediumint(8) default '0',
  postprocessor tinytext NOT NULL,
  sitemanager tinytext NOT NULL,
  active tinytext NOT NULL,
  PRIMARY KEY  (username),
  UNIQUE KEY username (username)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `usersettings`
#

CREATE TABLE usersettings (
  username varchar(25) NOT NULL default '',
  setting varchar(25) NOT NULL default '',
  value varchar(25) NOT NULL default ''
) TYPE=MyISAM;

