#!/usr/bin/perl
use DBI;
#use strict;
use Time::localtime;

open (OUTFILE, ">/home/charlz/public_html/dproofreaders/stats/hourly.txt");
open (HOURLYOUT, ">/home/charlz/public_html/dproofreaders/hourpages.txt");
open (MONTHLYOUT, ">/home/charlz/public_html/dproofreaders/monthpages.txt");


my ($dsn)= "DBI:mysql:dproofreaders:localhost";
my ($newdsn)= "DBI:mysql:dproofreaders:localhost";
my ($user_name) = "";
my ($password) = "";
my ($newpassword) = "";
my ($dbh, $sth);
my (@ary);
my ($numrows) = 0;
my ($todaysdate, $stats);
my ($mydbh,$mysth);
my ($project,$year,$month,$day,@monthlypages);
my (@myary,$pages);



$year = localtime->year() + 1900;
#printf "Year is %s\n", $year;


$month = (localtime->mon()) +1;
if ($month <=9){
$month = "0" . $month;
}
#printf "month is %s\n", $month;


$day = localtime->mday();
if ($day <= 9){
$day = "0" . $day;
}

$hour = localtime->hour();

$minutes = localtime->min();

if ($minutes <=9){
$minutes ="0". $minutes;
}

#printf "Time is %s:%s\n", $hour, $minutes;

$colon = chr(58);

$mytime = $hour.$colon.$minutes;
$todaysdate = $year.$month.$day;
#print "todays date", $todaysdate,"\n";


#connect to database
$dbh = DBI ->connect ($dsn, $user_name, $password, {RaiseError => 1});


#get all project id's
$sth = $dbh->prepare ("SELECT projectid FROM projects WHERE state != '30'");
#$sth = $dbh->prepare ("SELECT projectid FROM projects WHERE state = '2'");
$sth->execute();

# read results and cleanup
while (@ary = $sth->fetchrow_array ())
{
#   print join ("\t", @ary), "\n";

#during daylight savings
#$currenttime = time() - 25200;
#$startofday = ($currenttime - ($currenttime % 86400)) + 25200; 
#during standard time
$currenttime = time() - 28800;
$startofday = ($currenttime - ($currenttime % 86400)) + 28800;

$startofyesterday = ($startofday - 86400); 

#print "currenttime is $currenttime\n";
#print "startofday is $startofday\n";

#    $mydbh = DBI ->connect ($dsn, $user_name, $password, {RaiseError => 1});
#   $mysth = $mydbh->prepare ("SELECT image_filename FROM projectID3a31bd77c4d16 WHERE date_uploaded = '20010116' AND prooflevel != '0' AND prooflevel != '2' ");
    $mysth = $dbh->prepare ("SELECT image_filename FROM @ary WHERE timestamp >= $startofday AND (prooflevel = '10' 
OR prooflevel 
= '1' OR prooflevel = '3')");
    $mysth->execute();

    while (@myary = $mysth->fetchrow_array ())
    {
    $numrows++;
    }
    $mysth->finish ();

#print ("my project is:\n", $project);

}
     
$sth->finish ();

#print ("number of rows",$numrows);



# write info out to file
print OUTFILE ("Pages completed today: ",$numrows, " as of ", $mytime, " Pacific Time today<br>");
print HOURLYOUT ($numrows);

$dbh->disconnect ();


#get total pages for the month
#connect to database
$dbh = DBI ->connect ($newdsn, $user_name, $password, {RaiseError => 1});
$mysth = $dbh->prepare ("SELECT SUM(pages) FROM pagestats WHERE month = $month AND year = $year");
$mysth->execute();


while (@pageary = $mysth->fetchrow_array())
{
$monthlypages = @pageary[0];
print OUTFILE ("Pages completed this month: ", $monthlypages,"\n");
print MONTHLYOUT ($monthlypages);
}


# write info out to file

$mysth->finish ();
$dbh->disconnect ();




close OUTFILE;
close MONTHLYOUT;
close HOURLYOUT;

exit(0);

