<?php
$relPath="./pinc/";
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'list_projects.inc');

if($_GET['x'] == "g" OR $_GET['x'] == "") {
	$type = "Gold";
	$status = "Completed";
	$state = SQL_CONDITION_GOLD;
	$info = "Below is the list of Gold e-texts that have passed through this site.  Gold e-texts are books that have passed through a two rounds of proofreading and then post-processing.  After that they are then submitted to the Project Gutenberg database for your enjoyment and download.  These e-texts are the product of hundreds of hours of labor donated by all of our volunteers.  The list is sorted with the most recently submitted e-texts at the top.  You can sort them based upon your own preferences by clicking below.  Enjoy!!";
} elseif ($_GET['x'] == "s") {
	$type = "Silver";
	$status = "In Progress";
	$state = SQL_CONDITION_SILVER;
	$info = "Below is the list of Silver e-texts that are almost finished with their proofreading life on our site.  Silver e-texts are books that have passed through two rounds of proofreading and are now in the post-processing phase.  During the post-processing phase the project manager runs the e-text through some final checks to make sure they are as correct as possible.  After that they are then submitted to the Project Gutenberg database for your enjoyment and download.  These e-texts are the product of hundreds of hours of labor donated by all of our volunteers.  The list is sorted with the most recently submitted e-texts at the top.  You can sort them based upon your own preferences by clicking below.  Enjoy!!";
} elseif ($_GET['x'] == "b") {
	$type = "Bronze";
	$status = "Now Proofing";
	$state = SQL_CONDITION_BRONZE;
	$info = "Below is the list of Bronze e-texts that are currently available for proofreading on this site.  Bronze e-texts is what most of our members see and what you can work on now by logging in.  These e-texts are either in the first round or second round of proofreading where you have a chance to correct any mistakes that may be found.  After the proofreading phases, the e-text is then passed onto the Project Manager for post-processing.  After that they are then submitted to the Project Gutenberg database for your enjoyment and download.  These e-texts are the product of hundreds of hours of labor donated by all of our volunteers.  The list is sorted with the most recently submitted e-texts at the top.  You can sort them based upon your own preferences by clicking below.  Enjoy!!";
}
theme("$type E-Texts", "header");
?>

<center><font face="Verdana" size="6" color="<? echo $type; ?>"><b><? echo $status." ".$type; ?> E-Texts</b></font></center>
<center>
<?
if ($type == "Gold") {
	echo "<a href='list_etexts.php?x=s'>Silver</a> | <a href='list_etexts.php?x=b'>Bronze</a>";
} elseif ($type == "Silver") {
	echo "<a href='list_etexts.php?x=g'>Gold</a> | <a href='list_etexts.php?x=b'>Bronze</a>";
} elseif ($type == "Bronze") {
	echo "<a href='list_etexts.php?x=g'>Gold</a> | <a href='list_etexts.php?x=s'>Silver</a>";
}
?>
</center><br>

<center><? echo $info; ?></center><br>

<center>
<i>Title:</i> <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=0">asc</a> or <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&amp;sort=1">desc</a> |
<i>Author:</i> <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=2">asc</a> or <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&amp;sort=3">desc</a> |
<i>Submitted Date:</i> <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=4">asc</a> or <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&amp;sort=5">desc</a><br></center>
<hr width="75%" align="center">

<?
$sort = isset($_GET['sort'])? $_GET['sort'] : 0;

$sortlist[0]=" Order by nameofwork asc";
$sortlist[1]=" Order by nameofwork desc";
$sortlist[2]=" Order by authorsname asc";
$sortlist[3]=" Order by authorsname desc";
$sortlist[4]=" Order by modifieddate asc";
$sortlist[5]=" Order by modifieddate desc";

list_projects( $state, $sortlist[$sort], "" );

theme("", "footer");
?>
