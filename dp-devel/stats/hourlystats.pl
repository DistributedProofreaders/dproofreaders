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
my $midnight = timelocal(0, 0, 0, $day, $month, $year);
my $thishour = timelocal (0, 0, $hour, $day, $month, $year);
my $hourago = $thishour - 3600;
print "this hour is $thishour and the hour before that was $hourago";
if ($min  < 10) { $min  = "0$min";  }

my $dbh = DBI->connect($dsn, $db_user, $db_pass, {RaiseError => 1});

#limit to looking at projects that have not been archived.
my $sth = $dbh->prepare("SELECT projectid FROM projects WHERE archived = '0'");

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
        
print "project is $project and pages is $pages\n";
  $sth2->finish();
}

 $sth->finish();



#print "total pages  $total_pages";
#print "total second pages  $total_second_pages";

#$total_pages = $total_first_pages + $total_second_pages;     

print OUTFILE (
  "Pages completed today: $total_pages as of $hour:$min Pacific Time today<br>"
);




#calculate pages done in last hourly interval
my $sth = $dbh->prepare("SELECT projectid FROM projects WHERE archived = '0'");

$sth->execute();

my $total_hourly_pages = 0;
my $total_first_pages = 0;
my $total_second_pages = 0;

while (my $project = $sth->fetchrow_array()) {
  my $query = qq{
      SELECT COUNT(*) FROM $project WHERE
         (state = 'save_first'  AND round1_time >= $hourago AND round1_time < $thishour)
     OR
         (state = 'save_second' AND round2_time >= $hourago AND round2_time < $thishour)
  };

 my $sth2 = $dbh->prepare($query);
  $sth2->execute();

  my $pages = $sth2->fetchrow_array();
  $total_hourly_pages += $pages;

  $sth2->finish();
}
  
 $sth->finish();
print "pages in the last hour is $total_hourly_pages";

##write pages done in the last hour to db table
  my $query = qq{INSERT INTO `stats_hourly_pages_completed` ( `sample_time` , `pages_completed` ) VALUES ('$thishour', 
'$total_hourly_pages')
};

my $sth = $dbh->prepare($query);
$sth->execute();
 $sth->finish();
print HOURLYOUT $total_pages;


## calculate total pages done for the month
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
