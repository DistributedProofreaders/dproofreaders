<?php
include("connect.php");

if($_GET['x'] == "g" OR $_GET['x'] == "") {
$type = "Gold";
$state = "state=30";
$info = "Below is the list of Gold e-texts that have passed through this site.  Gold e-texts are books that have passed through a first round proofing, second round proofing and then a post processing.  After that they are then submitted to the Project Gutenberg database for your enjoyment and download.  These e-texts are the product of hundreds of hours of labor donated by all of our volunteers.  The list is sorted with the most recently submitted e-texts at the top.  You can sort them based upon your own preferences by clicking below.  Enjoy!!";
} elseif ($_GET['x'] == "s") {
$type = "Silver";
$state = "state>=19 AND state<=29";
$info = "Below is the list of Silver e-texts that are almost finished their proofreading life on our site.  Silver e-texts are books that have passed through a first round proofing, second round proofing and are now in the post-processing phase.  During the post-processing phase the project manager runs the e-text through some final checks to make sure they are as correct as possible.  After that they are then submitted to the Project Gutenberg database for your enjoyment and download.  These e-texts are the product of hundreds of hours of labor donated by all of our volunteers.  The list is sorted with the most recently submitted e-texts at the top.  You can sort them based upon your own preferences by clicking below.  Enjoy!!";
} elseif ($_GET['x'] == "b") {
$type = "Bronze";
$state = "state=2 OR state=12 OR state=8 OR state=18";
$info = "Below is the list of Bronze e-texts that are currently available for proofreading on this site.  Bronze e-texts is what most of our members see and what you can work on now by logging in.  These e-texts are either in the first round or second round proofing where you have a chance to correct any mistakes that may be found.  After the proofing phase the e-text is then passed onto the Project Manager for post-processing.  After that they are then submitted to the Project Gutenberg database for your enjoyment and download.  These e-texts are the product of hundreds of hours of labor donated by all of our volunteers.  The list is sorted with the most recently submitted e-texts at the top.  You can sort them based upon your own preferences by clicking below.  Enjoy!!";
}
?>

<html>
<head>
<title>Completed <? echo $type; ?> E-Texts</title>
</head>

<body alink="#0000FF" vlink="#0000ff" link="#0000ff">
<center><font face="Verdana" size="6" color="<? echo $type; ?>"><b>Completed <? echo $type; ?> E-Texts</b></font></center>
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
</center>
<br>

<center><? echo $info; ?></center><br>

<center>
<i>Title:</i> <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=0">asc</a> or <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=1">desc</a> | 
<i>Author:</i> <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=2">asc</a> or <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=3">desc</a> | 
<i>Submitted Date:</i> <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=4">asc</a> or <a href="list_etexts.php?x=<? echo $_GET['x']; ?>&sort=5">desc</a><br></center>
<hr width="75%" align="center"><br>

<?
$sort = $_GET['sort'];

if (!isset($sort)) $sort=0;

$sortlist[0]=" Order by nameofwork asc";
$sortlist[1]=" Order by nameofwork desc";
$sortlist[2]=" Order by authorsname asc";
$sortlist[3]=" Order by authorsname desc";
$sortlist[4]=" Order by modifieddate asc";
$sortlist[5]=" Order by modifieddate desc";

$result=mysql_query("SELECT nameofwork, authorsname, ziplink, txtlink, htmllink, modifieddate, postednum FROM projects WHERE $state $sortlist[$sort]");

$numofetexts = 1;
while($row = mysql_fetch_array($result)) {

if ($type == "Gold") {
$links="";
if (trim($row['ziplink']) <> "") $links=$links."<a href='".$row['ziplink']."'>zip version</a>, ";
if (trim($row['txtlink']) <> "") $links=$links."<a href='".$row['txtlink']."'>text version</a>, ";
if (trim($row['htmllink']) <> "") $links=$links."<a href='".$row['htmllink']."'>html version</a>";
if ($links == "") {
$links = $links."<br>";
} else {
$links = $links."<br><br>";
} } else {
$links = "<br>";
}

$moddate = $row['modifieddate'];
$dateyear = substr($moddate, 0, 4);
$datemonth = substr($moddate, 4, 2);
$dateday = substr($moddate, 6, 2);
$datecomplete = $dateyear."-".$datemonth."-".$dateday;
$unixsec = strtotime($datecomplete);
$moddate = date("l, F jS, Y",$unixsec);

if ($type == "Gold") {
$moddate = "Uploaded: ".$moddate;
} elseif ($type == "Silver") {
$moddate = "Last Proofed: ".$moddate;
} elseif ($type == "Bronze") {
$moddate = "Released: ".$moddate;
}

echo "<font face='Verdana' size='1' color='#444444'><b>$numofetexts) \"".$row['nameofwork']."\"</b></font><font face='Verdana' size='1'>, ".$row['authorsname']."<br>".$row['postednum']." pages; $moddate<br>$links</font>";

$numofetexts++;
}
?>

</body>
</html>
