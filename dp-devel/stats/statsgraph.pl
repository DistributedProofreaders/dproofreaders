#!/usr/bin/perl -w
use strict;
use DBI;
use Date::Calc qw(Today Days_in_Month Month_to_Text);
use vars qw($year $day $month_name $date $dsn $db_user $db_pass);
sub create_monthly_target_graph();
sub create_daily_pages_graph();
sub save_graph($$);

$dsn = "DBI:mysql:dproofreaders:localhost";
$db_user = "";
$db_pass = "";

my $month;

if (@ARGV) {
  ($month, $year) = @ARGV;
  $day = Days_in_Month($year, $month);
}
else {
  ($year, $month, $day) = Today();
}

$date = "'$year-$month-$day'";
$month_name = Month_to_Text($month);

create_monthly_target_graph();
create_daily_pages_graph();

sub create_monthly_target_graph() {
  use GD::Graph::lines;

  my $dbh = DBI->connect($dsn, $db_user, $db_pass, {RaiseError => 1});

  # Get daily totals for the given month.

  my $query = qq{
    SELECT
      p1.date AS date,
      DAYOFMONTH(p1.date) AS day_of_month,
      SUM(p2.pages) AS pages,
      SUM(p2.dailygoal) AS target
    FROM pagestats AS p1, pagestats AS p2
    WHERE MONTH(p1.date) = MONTH(p2.date)
      AND YEAR(p1.date) = YEAR(p2.date)
      AND p1.date >= p2.date
      AND MONTH(p1.date) = MONTH($date)
      AND YEAR(p1.date) = YEAR($date)
    GROUP BY p1.date
    ORDER BY p1.date
  };

  my $sth = $dbh->prepare($query);
  $sth->execute();

  my ($row, @dates, @pages, @targets, $monthly_target);

  while ($row = $sth->fetchrow_hashref()) {
    push @dates, $row->{date};

    my $day_of_month = $row->{day_of_month};

    # Put in undef for all days in the future

    if ($day_of_month < $day) {
      push @pages, $row->{pages};
    }
    else {
      push @pages, undef;
    }

    push @targets, $row->{target};

    # Since the query is ordered by date, the total monthly target will be
    # correct after the last iteration

    $monthly_target = $row->{target};
  }

  # Get daily totals for the previous month

  $query = qq{
    SELECT SUM(p2.pages) AS pages
    FROM pagestats AS p1, pagestats AS p2
    WHERE MONTH(p1.date) = MONTH(p2.date)
      AND YEAR(p1.date) = YEAR(p2.date)
      AND p1.date >= p2.date
      AND MONTH(p1.date) = MONTH(DATE_SUB($date, INTERVAL 1 MONTH))
      AND YEAR(p1.date) = YEAR(DATE_SUB($date, INTERVAL 1 MONTH))
    GROUP BY p1.date
    ORDER BY p1.date
  };

  $sth = $dbh->prepare($query);
  $sth->execute();

  my @previous_pages;
  my $previous_total = 0;

  while ($row = $sth->fetchrow_hashref()) {
    push @previous_pages, $row->{pages};
    $previous_total = $row->{pages} if $row->{pages};
  }

  $dbh->disconnect();

  my $data = [\@dates, \@targets, \@previous_pages, \@pages];
  my $graph = new GD::Graph::lines(600, 500);

  $graph->set_legend(
    "Monthly Target ($monthly_target pages)",
    "Previous Month ($previous_total pages)",
    'Current Month',
    legend_marker_width => '48',
    legend_marker_height => '32',
  );

  $graph->set(
    title => "Total Pages Done for $month_name $year",
    y_label => 'Pages',
    y_max_value => 250000,
    y_tick_number => 25,
    line_types => 1,
    line_width => 2,
    box_axis => 0,
    x_label_position => 1/2,
    r_margin => 15,
    b_margin => 15,
    x_labels_vertical => 1,
    transparent => 1,
  );

  $graph->plot($data);

  save_graph($graph, 'target');
}

sub create_daily_pages_graph() {
  use GD::Graph::mixed;

  my $dbh = DBI->connect($dsn, $db_user, $db_pass, {RaiseError => 1});

  my $query = qq{
    SELECT date, pages, dailygoal
    FROM pagestats
    WHERE MONTH(date) = MONTH($date) AND YEAR(date) = YEAR($date)
    ORDER BY date
  };

  my $sth = $dbh->prepare($query);
  $sth->execute();

  my (@dates, @pages, @targets);

  while (my $row = $sth->fetchrow_hashref()) {
    push @dates, $row->{date};
    push @pages, $row->{pages};
    push @targets, $row->{dailygoal};
  }

  $dbh->disconnect();

  my $daily_goal = 0;
  $daily_goal = $targets[0] if $targets[0];

  my $data = [\@dates, \@pages, \@targets];
  my $graph = new GD::Graph::mixed(600, 500);

  $graph->set_legend(
    'Daily Pages Done',
    "Daily Page Goal ($daily_goal pages)",
    'Current Month',
    legend_marker_width => '28',
    legend_marker_height => '32',
  );

  $graph->set(
    types => [qw(bars lines)],
    default_type => 'bars',
    title => "Pages Done Per Day For $month_name $year",
    y_label => 'Pages',
    y_min_value => 0,
    y_max_value => 16000,
    y_tick_number => 16,
    box_axis => 0,
    line_width => 2,
    x_label_position => 1/2,
    r_margin => 15,
    b_margin => 15,
    x_labels_vertical => 1,
    transparent => 1,
  );

  $graph->plot($data);

  save_graph($graph, 'stats');
}

sub save_graph($$) {
  my ($graph, $filename) = @_;
  my $ext = $graph->export_format();

  open(OUT, "> $filename.$ext") or die "Unable to save $filename.$ext: $!";
  binmode OUT;
  print OUT $graph->gd->$ext();
  close OUT;
}
