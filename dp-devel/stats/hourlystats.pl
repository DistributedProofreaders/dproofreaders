#!/usr/bin/perl -w
use strict;
use DBI;
use Time::Local;

my $base = "<<SITE_DIR>>";
# for testing:
# my $base = "/tmp";

open (OUTFILE,    ">$base/stats/hourly.txt");
open (HOURLYOUT,  ">$base/hourpages.txt");
open (MONTHLYOUT, ">$base/monthpages.txt");

my $dsn = "DBI:mysql:dproofreaders:localhost";
my $db_user = "";
my $db_pass = "";

my ($min, $hour, $day, $month, $year) = (localtime)[1..5];
if ($min  < 10) { $min  = "0$min";  }
my $midnight = timelocal(0, 0, 0, $day, $month, $year);

my $dbh = DBI->connect($dsn, $db_user, $db_pass, {RaiseError => 1});

#limit to looking at projects modified after 1 Jan 2003 as older projects have a different table structure.
my $sth = $dbh->prepare("SELECT projectid FROM projects WHERE modifieddate > '1041465600O'");
$sth->execute();

my $total_pages = 0;

while (my $project = $sth->fetchrow_array()) {
  my $query = qq{
      SELECT COUNT(*) FROM $project WHERE
        (state = 'save_first'  AND round1_time >= $midnight)
        OR
        (state = 'save_second' AND round2_time >= $midnight)
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
