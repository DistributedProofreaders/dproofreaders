# phpMyAdmin SQL Dump
# version 2.5.3
# http://www.phpmyadmin.net
#
# Create: July 10, 2004, at 00:08
# Server version: 4.0.15
# PHP-version: 4.3.3
# 
# Database : `dproofreaders`
# 

# --------------------------------------------------------

#
# Structure of table `authors`
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
  `last_modified` timestamp(14) NOT NULL,
  UNIQUE KEY `author_id` (`author_id`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Structure of table `biographies`
#

CREATE TABLE `biographies` (
  `bio_id` int(11) NOT NULL auto_increment,
  `author_id` int(11) NOT NULL default '0',
  `bio` text NOT NULL,
  `last_modified` timestamp(14) NOT NULL,
  UNIQUE KEY `bio_id` (`bio_id`)
) TYPE=MyISAM COMMENT='Contains biographies (see authors)';