#!/usr/bin/perl

use DBI;
#use strict;
use Time::localtime;
use GD::Graph::bars;

my ($dsn)= "DBI:mysql:<<DB_NAME>>:<<DB_SERVER>>";

my ($user_name) = "<<DB_USER>>";
my ($password) = "<<DB_PASSWORD>>";
my ($dbh, $sth);
my (@ary);
my ($numrows) = 0;
my ($projrows) = 0;
my ($todaysdate, $stats);
my ($mydbh,$mysth, $userdbh, $usersth);
my ($project,$year,$month,$day, $counter, $counter2);
my (@myary, @data);
my (@userary);

open (MYOUTFILE, ">temp.txt");



#old date code
$year = localtime->year() + 1900;
#printf "Year is %s\n", $year;


$month = (localtime->mon()) +1;

#my $SECS_IN_A_DAY = 86400;  # 60 secs * 60 mins * 24 hrs

#my ($day, $month, $year) = (localtime(time-$SECS_IN_A_DAY))[3..5];
#$month++;
#$year += 1900;




if ($month <=9){
$month = "0" . $month;
}
printf "month is %s\n", $month;


if ($month == 1) {$namemonth = "January"} 
if ($month == 2) {$namemonth = "February"} 
if ($month == 3) {$namemonth = "March"} 
if ($month == 4) {$namemonth = "April"} 
if ($month == 5) {$namemonth = "May"} 
if ($month == 6) {$namemonth = "June"} 
if ($month == 7) {$namemonth = "July"} 
if ($month == 8) {$namemonth = "August"} 
if ($month == 9) {$namemonth = "September"} 
if ($month == 10) {$namemonth = "October"} 
if ($month == 11) {$namemonth = "November"} 
if ($month == 12) {$namemonth = "December"}; 

#print "month name is $namemonth";

$day = localtime->mday();


if ($day <= 9){
$day = "0" . $day;
}
printf "day is %s\n", $day;



$legend = "Pages Done Per Day For ".$namemonth." ".$year;
#print "legend is $legend";


#needs to be renamed to $yesterdaysdate
$todaysdate = $year.$month.$day;
print "todays date", $todaysdate,"\n";


#connect to user database and read in user 


#connect to database
$dbh = DBI ->connect ($dsn, $user_name, $password, {RaiseError => 1});


#get pages and dates
$sth = $dbh->prepare ("SELECT date,pages,dailygoal FROM pagestats WHERE month = $month AND year = $year ORDER BY day DESC");
$sth->execute();




while (@ary = $sth->fetchrow_array())
{
$dailygoal = @ary[2];
print MYOUTFILE join ("\t", @ary), "\n";
}
     

$sth->finish ();
$dbh->disconnect ();

close MYOUTFILE;

############ generate daily pages graph

use GD::Graph::mixed;
require '<<SITE_DIR>>/stats/save.pl';

print STDERR "Processing file\n";

@data = read_data("temp.txt") 
	or die "Cannot read data file";


$my_graph = new GD::Graph::mixed(600,500);


#create daily goal statement for graph
$daily = "Daily Page Goal (".$dailygoal." pages)";




#set graph legends
$my_graph->set_legend(

'Daily Pages Done', 
$daily, 
'Current Month',
legend_marker_width => '28',
legend_marker_height =>'32',); 



#set graph variables

$my_graph->set( 

    types => [ qw( bars lines ) ],
    default_type => 'bars',


	y_label => 'Pages',
	title => $legend,

	y_min_value => 0,
	y_max_value => 16000,
	y_tick_number => 16,
#	y_label_skip => 100,

#	x_tick_number => 'auto',
#	x_label_skip => 0,

	box_axis => 0,
	line_width => 2,
	x_label_position => 1/2,
	r_margin => 15,
        b_margin => 15,
	x_labels_vertical => 1,

    line_width      => 1,

	transparent => 1,
);

$my_graph->plot(\@data);
save_chart($my_graph, 'stats.png');

close MYOUTFILE;




###### create montly target graph


use GD::Graph::lines;
require '<<SITE_DIR>>/stats/save.pl';
open (MYOUTFILE, ">temp.txt");




#delete all old data from temporary table
$deletedbh = DBI ->connect ($dsn, $user_name, $password, {RaiseError => 1});
$deletesth = $deletedbh->prepare ("DELETE FROM tempstats");
$deletesth->execute();
$deletesth->finish ();
$deletedbh->disconnect ();



#connect to database
$dbh = DBI ->connect ($dsn, $user_name, $password, {RaiseError => 1});


#fetch data for current month
$sth = $dbh->prepare ("SELECT date, day, pages, dailygoal, year, month FROM pagestats WHERE month = $month AND year = $year");
$sth->execute();



#iterate through data and make totals
while (@ary = $sth->fetchrow_array())
{
$sampledate = @ary[0];
$newyear = @ary[4];
$newmonth = @ary[5];
$newday = @ary[1];


##if January subtract 1 from year to get previous year
if ($newmonth == 1){
$prevyear = $year-1;
$prevmonth = 12;
}else{ 
$prevyear = $newyear;
$prevmonth = $newmonth-1;
}


print "newyear is $newyear\n";

if ($newmonth <= 9){
$newmonth = "0" . $newmonth;
}
print "new new month is $newmonth\n";


$totalgoal = $totalgoal + @ary[3];


 if (@ary[2] == 0) {
  $totalpages = "0"
 }else{
$totalpages = $totalpages + @ary[2];
 }



$prevday = @ary[1];

print "prevday is $prevday and prev year is $prevyear prev month is $prevmonth\n";

 #fetch previous months pages for that day
  #connect to database
  $mydbh = DBI ->connect ($dsn, $user_name, $password, {RaiseError => 1});
  $newsth = $mydbh->prepare ("SELECT pages FROM pagestats WHERE month = $prevmonth AND day = $prevday AND year = $prevyear");
  $newsth->execute();
  @newary = $newsth->fetchrow_array();
  $prevpages = $prevpages+@newary[0];



#write totals to temporary table

#connect to database
$tempdbh = DBI ->connect ($dsn, $user_name, $password, {RaiseError => 1});

#write

if ($newday <= 9){
$newday = "0" . $newday;
}

$datestamp = $year.$newmonth.$newday;

print ("sampledate is $datestamp\n");

$tempsth = $tempdbh->prepare ("INSERT INTO tempstats (date, goal, prevmonth, currmonth) VALUES ($datestamp, $totalgoal, $prevpages, $totalpages)");
$tempsth->execute ();
$tempsth->finish ();
$tempdbh->disconnect ();



$newsth->finish ();
$mydbh->disconnect ();
}



#read back in from temp table and reverse read order

#connect to database
$tempdbh = DBI ->connect ($dsn, $user_name, $password, {RaiseError => 1});

$tempsth = $tempdbh->prepare ("SELECT date, goal, prevmonth, currmonth FROM tempstats ORDER BY date DESC");
$tempsth->execute();
$oldtemp = 0;

while (@tempary = $tempsth->fetchrow_array())
{
if ($tempary[3] == 0){
$totalpages = "undef";
}else{
$totalpages = $tempary[3];
}


print MYOUTFILE ($tempary[0],"\t", $tempary[1],"\t", $tempary[2],"\t",$totalpages, "\n");


}
$tempsth->finish ();
$tempdbh->disconnect ();

close MYOUTFILE;
$sth->finish ();
$dbh->disconnect ();


@data = read_data("temp.txt") 
	or die "Cannot read data from file";


$my_graph = new GD::Graph::lines(600,500);

#$my_graph->set_legend(@legend_keys); 

$my_graph->set_legend_font(gdLargeBoldFont);
$curr_mth = "Current Month ($last)";

$my_graph->set_legend(

'Monthly target (134,008 pages)', 
'Previous Month (134,776 pages)', 
#$curr_mth,
'Current Month',
legend_marker_width => '48',
legend_marker_height =>'32',); 

$title = "Total Pages Done for ".$namemonth." ".$year;


$my_graph->set( 

	y_label => 'Pages',
	#title => 'Total Pages Done For November, 2001',
         title => $title,
        line_types => 1,

	#y_min_value => 0,
	y_max_value => 250000,
	y_tick_number => 25,
#	y_label_skip => 0,

#	x_tick_number => 'auto',
#	x_label_skip => 0,

	box_axis => 0,
	line_width => 2,
	x_label_position => 1/2,
	r_margin => 15,
        b_margin => 15,
	x_labels_vertical => 1,

	transparent => 1,
);

$my_graph->plot(\@data);
save_chart($my_graph, 'target.png');



sub read_data
{
	my $fn = shift;
	my @d = ();

	open(ZZZ, $fn) || return ();

	while (<ZZZ>)
	{
		chomp;
		my @row = split;

		for (my $i = 0; $i <= $#row; $i++)
		{
			undef $row[$i] if ($row[$i] eq 'undef');
			unshift @{$d[$i]}, $row[$i];
		}
	}

	close (ZZZ);

	return @d;
}





exit(0);


