<?
//Declare variables to create database and tables
$db_host = "localhost";
$db_user = "username";
$db_pass = "password";
$db_name = "dproofreaders";

//Connect to the mysql database
$db = mysql_connect($db_host,$db_user,$db_pass);

//Create the database
$createdb = mysql_query("CREATE DATABASE $db_name");

//Connect to the new database so we can create the tables
mysql_select_db($db_name,$db) or die ("Unable to select database.");

//Create the tables
$sql_pagestats = " CREATE TABLE pagestats (";
  $sql_pagestats = $sql_pagestats . " year smallint(4) NOT NULL default '0', ";
  $sql_pagestats = $sql_pagestats . " month tinyint(2) NOT NULL default '0', ";
  $sql_pagestats = $sql_pagestats . " day tinyint(2) NOT NULL default '0', ";
  $sql_pagestats = $sql_pagestats . " date date NOT NULL default '0000-00-00', ";
  $sql_pagestats = $sql_pagestats . " pages int(12) NOT NULL default '0', ";
  $sql_pagestats = $sql_pagestats . " dailygoal int(12) NOT NULL default '0' ";
$sql_pagestats = $sql_pagestats . " ) TYPE=MyISAM;";
$result = mysql_query("$sql_pagestats"); 
if ($result == 1) { echo "$db_name.pagestats ....... Created<br>"; } else { echo "$db_name.pagestats ....... Not Created<br>"; }

$sql_projects = " CREATE TABLE projects ( ";
  $sql_projects = $sql_projects . " nameofwork varchar(255) NOT NULL default '', ";
  $sql_projects = $sql_projects . " authorsname varchar(255) NOT NULL default '', ";
  $sql_projects = $sql_projects . " language varchar(255) NOT NULL default '', ";
  $sql_projects = $sql_projects . " username varchar(255) NOT NULL default '', ";
  $sql_projects = $sql_projects . " comments text NOT NULL, ";
  $sql_projects = $sql_projects . " projectid text NOT NULL, ";
  $sql_projects = $sql_projects . " checkedoutby text NOT NULL, ";
  $sql_projects = $sql_projects . " modifieddate text NOT NULL, ";
  $sql_projects = $sql_projects . " scannercredit tinytext NOT NULL, ";
  $sql_projects = $sql_projects . " state tinyint(3) unsigned NOT NULL default '0', ";
  $sql_projects = $sql_projects . " txtlink varchar(200) default NULL, ";
  $sql_projects = $sql_projects . " ziplink varchar(200) default NULL, ";
  $sql_projects = $sql_projects . " htmllink varchar(200) default NULL, ";
  $sql_projects = $sql_projects . " postednum smallint(5) unsigned NOT NULL default '6000', ";
  $sql_projects = $sql_projects . " clearance varchar(200) NOT NULL default '', ";
  $sql_projects = $sql_projects . " year varchar(4) NOT NULL default '' ";
$sql_projects = $sql_projects . " ) TYPE=MyISAM;";
$result = mysql_query("$sql_projects"); 
if ($result == 1) { echo "$db_name.projects ....... Created<br>"; } else { echo "$db_name.projects ....... Not Created<br>"; }

$sql_ranks = " CREATE TABLE ranks ( ";
  $sql_ranks = $sql_ranks . " rankid tinyint(2) NOT NULL default '0', ";
  $sql_ranks = $sql_ranks . " rankname text, ";
  $sql_ranks = $sql_ranks . " minpages smallint(5) default NULL, ";
  $sql_ranks = $sql_ranks . " maxpages int(8) default NULL, ";
  $sql_ranks = $sql_ranks . " imagepath varchar(50) default NULL ";
$sql_ranks = $sql_ranks . " ) TYPE=MyISAM;";
$result = mysql_query("$sql_ranks"); 
if ($result == 1) { echo "$db_name.ranks ....... Created<br>"; } else { echo "$db_name.ranks ....... Not Created<br>"; }


$sql_rules = " CREATE TABLE rules ( ";
  $sql_rules = $sql_rules . " id int(4) NOT NULL auto_increment, ";
  $sql_rules = $sql_rules . " subject varchar(100) NOT NULL default '', ";
  $sql_rules = $sql_rules . " rule text NOT NULL, ";
  $sql_rules = $sql_rules . " doc varchar(10) NOT NULL default '', ";
  $sql_rules = $sql_rules . " PRIMARY KEY  (id) ";
$sql_rules = $sql_rules . " ) TYPE=MyISAM;";
$result = mysql_query("$sql_rules"); 
if ($result == 1) { echo "$db_name.rules ....... Created<br>"; } else { echo "$db_name.rules ....... Not Created<br>"; }

$sql_states = " CREATE TABLE states ( ";
  $sql_states = $sql_states . " id tinyint(3) unsigned NOT NULL default '0', ";
  $sql_states = $sql_states . " name tinytext NOT NULL ";
$sql_states = $sql_states . " ) TYPE=MyISAM COMMENT='States that a project or a page can be in';";
$result = mysql_query("$sql_states"); 
if ($result == 1) { echo "$db_name.states ....... Created<br>"; } else { echo "$db_name.states ....... Not Created<br>"; }

$sql_tempstats = " CREATE TABLE tempstats ( ";
  $sql_tempstats = $sql_tempstats . " date date NOT NULL default '0000-00-00', ";
  $sql_tempstats = $sql_tempstats . " goal int(12) NOT NULL default '0', ";
  $sql_tempstats = $sql_tempstats . " prevmonth int(12) NOT NULL default '0', ";
  $sql_tempstats = $sql_tempstats . " currmonth int(12) NOT NULL default '0' ";
$sql_tempstats = $sql_tempstats . " ) TYPE=MyISAM;";
$result = mysql_query("$sql_tempstats"); 
if ($result == 1) { echo "$db_name.tempstats ....... Created<br>"; } else { echo "$db_name.tempstats ....... Not Created<br>"; }

$sql_users = " CREATE TABLE users ( ";
  $sql_users = $sql_users . " id varchar(50) NOT NULL default '', ";
  $sql_users = $sql_users . " real_name varchar(100) NOT NULL default '', ";
  $sql_users = $sql_users . " username varchar(25) NOT NULL default '', ";
  $sql_users = $sql_users . " email varchar(50) NOT NULL default '', ";
  $sql_users = $sql_users . " manager varchar(5) NOT NULL default '', ";
  $sql_users = $sql_users . " date_created varchar(8) NOT NULL default '', ";
  $sql_users = $sql_users . " last_login varchar(8) NOT NULL default '', ";
  $sql_users = $sql_users . " emailupdates varchar(4) NOT NULL default '', ";
  $sql_users = $sql_users . " pagescompleted mediumint(8) default '0', ";
  $sql_users = $sql_users . " postprocessor tinytext NOT NULL, ";
  $sql_users = $sql_users . " sitemanager tinytext NOT NULL, ";
  $sql_users = $sql_users . " active tinytext NOT NULL, ";
  $sql_users = $sql_users . " PRIMARY KEY  (username), ";
  $sql_users = $sql_users . " UNIQUE KEY username (username) ";
$sql_users = $sql_users . " ) TYPE=MyISAM;";
$result = mysql_query("$sql_users"); 
if ($result == 1) { echo "$db_name.users ....... Created<br>"; } else { echo "$db_name.users ....... Not Created<br>"; }

$sql_usersettings = " CREATE TABLE usersettings ( ";
  $sql_usersettings = $sql_usersettings . " username varchar(25) NOT NULL default '', ";
  $sql_usersettings = $sql_usersettings . " setting varchar(25) NOT NULL default '', ";
  $sql_usersettings = $sql_usersettings . " value varchar(25) NOT NULL default '' ";
$sql_usersettings = $sql_usersettings . " ) TYPE=MyISAM;";
$result = mysql_query("$sql_usersettings"); 
if ($result == 1) { echo "$db_name.usersettings ....... Created<br>"; } else { echo "$db_name.usersettings ....... Not Created<br>"; }

//Finish Completion
echo "<br><br>Database creation completed!  Created database $db_name with the above tables";
?> 


