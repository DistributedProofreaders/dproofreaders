#!/usr/bin/perl -w
use strict;
use DBI;
use Time::Local;

open (OUTFILE, ">/home/charlz/public_html/dproofreaders/stats/hourly.txt");
open (HOURLYOUT, ">/home/charlz/public_html/dproofreaders/hourpages.txt");
open (MONTHLYOUT, ">/home/charlz/public_html/dproofreaders/monthpages.txt");

my $dsn = "DBI:mysql:dproofreaders:localhost";
my $db_user = "";
my $db_pass = "";

my ($min, $hour, $day, $month, $year) = (localtime)[1..5];
my $midnight = timelocal(0, 0, 0, $day, $month, $year);

my $dbh = DBI->connect($dsn, $db_user, $db_pass, {RaiseError => 1});

my $sth = $dbh->prepare("SELECT projectid FROM projects WHERE state != 30");
$sth->execute();

my $total_pages = 0;

while (my $project = $sth->fetchrow_array()) {
  my $query = qq{
    SELECT SUM(CASE WHEN round1_time >= $midnight 
                         AND state > 5
                    THEN 1 ELSE 0 END
               + 
               CASE WHEN round2_time >= $midnight 
                         AND state > 15
                    THEN 1 ELSE 0 END)
    FROM $project
  };

  my $sth2 = $dbh->prepare($query); 
  $sth2->execute();

  my $pages = $sth2->fetchrow_array();
  $total_pages += $pages;

  $sth2->finish();
}
     
print OUTFILE (
  "Pages completed today: $total_pages as of $hour:$min Pacific Time today<br>"
);

print HOURLYOUT $total_pages;

$sth = $dbh->prepare(qq{
  SELECT SUM(pages) 
  FROM pagestats 
  WHERE MONTH(date) = MONTH(CURDATE()) 
    AND YEAR(date) = YEAR(CURDATE())
});

$sth->execute();

my $pages = $sth->fetchrow_array();

$sth->finish();
$dbh->disconnect();

print OUTFILE "Pages completed this month: $pages\n";
print MONTHLYOUT $pages;

close OUTFILE;
close MONTHLYOUT;
close HOURLYOUT;
