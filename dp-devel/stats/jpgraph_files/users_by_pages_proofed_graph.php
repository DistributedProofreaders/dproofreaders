<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($relPath.'connect.inc');
new dbConnect();


///////////////////////////////////////////////////////////////////
//Number of users who have done X pages, and how recently logged in
//(time scales used: ever, last 90 days, last 28 days, last 7 days)


// define threshold timestamps
$now = time();
$last90d = $now - (90 * 60 * 60 * 24);
$last28d = $now - (4 * 7 * 60 * 60 * 24);
$lastweek = $now - (7 * 60 * 60 * 24);


// how many bars in the graph?
$result0 = mysql_query("
	SELECT max(pagescompleted) as maxpages FROM users 
");
$maxpages = mysql_result($result, 0,"maxpages");



//query db and put results into arrays
$resultAll = mysql_query("
	SELECT pagescompleted, count(*) as NumUsers FROM users
	GROUP BY pagescompleted
	ORDER BY pagescompleted ASC
");


$result90 = mysql_query("
	SELECT pagescompleted, count(*) as NumUsers FROM users
	WHERE last_login > $last90d
	GROUP BY pagescompleted
	ORDER BY pagescompleted ASC
");

$result28 = mysql_query("
	SELECT pagescompleted, count(*) as NumUsers FROM users
	WHERE last_login > $last28d
	GROUP BY pagescompleted
	ORDER BY pagescompleted ASC
");


$result7 = mysql_query("
	SELECT pagescompleted, count(*) as NumUsers FROM users
	WHERE last_login > $lastweek
	GROUP BY pagescompleted
	ORDER BY pagescompleted ASC
");


$numrowsAll = mysql_numrows($resultAll);
$numrows90 = mysql_numrows($result90);
$numrows28 = mysql_numrows($result28);
$numrows7 = mysql_numrows($result7);


$count = 0;
$countAll = 0;
$count90 = 0;
$count28 = 0;
$count7 = 0;


// consider in turn each possible pagescompleted value...
while ($count < $maxpages) {

    // ... for each of our four data sets, 
    // if for the current pagescompleted value ($count) a 
    // corresponding value exists for NumUsers, add it to the array of Y-axis data
    // for that result set;
    // else no users in that result set have done that number of pages,
    // so set the Y-axis data to 0 for that number of pagescompleted


    // track how many values we've looked at and recorded from this data set
    // so as not to run out of bounds
    if ($countAll < $numRowsAll) {
	if (mysql_result($resultAll, $countAll,"pagescompleted") == $count) {
           $datayAll[$count] = mysql_result($resultAll, $count,"NumUsers");	
	   $countAll++;
	} else {
	   $datayAll[$count] = 0;
	}
    }

    if ($count90 < $numRows90) {
	if (mysql_result($result90, $count90,"pagescompleted") == $count) {
           $datay90[$count] = mysql_result($result90, $count,"NumUsers");	
	   $count90++;
	} else {
	   $datay90[$count] = 0;
	}
    }

    if ($count28 < $numRows28) {
	if (mysql_result($result28, $count28,"pagescompleted") == $count) {
           $datay28[$count] = mysql_result($result28, $count,"NumUsers");	
	   $count28++;
	} else {
	   $datay28[$count] = 0;
	}
    }

    if ($count7 < $numRows7) {
	if (mysql_result($result7, $count7,"pagescompleted") == $count) {
           $datay7[$count] = mysql_result($result7, $count,"NumUsers");	
	   $count7++;
	} else {
	   $datay7[$count] = 0;
	}
    }


    $datax[$count] = $count;
    $count++;
}




// Create the graph. These two calls are always required
//Last value controls how long the graph is cached for in minutes
$graph = new Graph(640,400,"auto",1440);
$graph->SetScale("textint");

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set(_("Pages Proofed"));
// Only draw labels on every 2nd tick mark
$graph->xaxis->SetTextTickInterval(91.25);

//Set Y axis
$graph->yaxis->title->Set(_("Number of Proofers"));
$graph->yaxis->SetTitleMargin(45);

//Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
//left, right , top, bottom
$graph->img->SetMargin(70,30,20,100);


// TODO - colours, barplot_Group_thing

// Create the bar pots
$bplotAll = new BarPlot($datayAll);
$bplotAll ->SetFillColor ("cadetblue1");
$bplotAll->SetLegend(_("All Registered Users"));

$bplot90 = new BarPlot($datay90);
$bplotAll ->SetFillColor ("mediumseagreen");
$bplotAll->SetLegend(_("Logged on in last 90 days"));


$bplot28 = new BarPlot($datay28);
$bplotAll ->SetFillColor ("lime");
$bplotAll->SetLegend(_("Logged on in last 28 days"));


$bplot7 = new BarPlot($datay7);
$bplotAll ->SetFillColor ("yello");
$bplotAll->SetLegend(_("Logged on in last 7 days"));


// Create the grouped bar plot
$gbplot = new GroupBarPlot (array($bplotAll ,$bplot90,$bplot28 ,$bplot7 ));

// ...and add it to the graPH
$graph->Add( $gbplot);


// Setup the title
$graph->title->Set(
	_("Numbers of Proofers who have Proofed X pages")
);
$graph->subtitle->Set(
	_("and How Recently Logged In")
);



$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

// Display the graph
$graph->Stroke();

?>
