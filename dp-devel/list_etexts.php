<?php
$relPath="./pinc/";
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'list_projects.inc');

$x        = array_get($_GET, 'x', 'g');
$sort     = get_integer_param($_GET, 'sort',      0, 0, 5);
$per_page = get_integer_param($_GET, 'per_page', 20, 1, NULL);
$offset   = get_integer_param($_GET, 'offset',    0, 0, NULL);

$boilerplate = "These e-texts are the product of hundreds of hours of labor
    donated by all of our volunteers. The list is sorted with the most recently
    submitted e-texts at the top. You can sort them based upon your own preferences
    by clicking below. Enjoy!!";

if($x == "g" OR $x == "") {
    $type = "Gold";
    $status = "Completed";
    $state = SQL_CONDITION_GOLD;
    $info = "Below is the list of Gold e-texts that have been produced on
    this site. Gold e-texts are books that have passed through all phases of
    proofreading, formatting, and post-processing. They have been submitted
    to Project Gutenberg and are now available for your enjoyment and download.";
} elseif ($x == "s") {
    $type = "Silver";
    $status = "In Progress";
    $state = SQL_CONDITION_SILVER;
    $info = "Below is the list of Silver e-texts that have almost completed
    processing on our site. Silver e-texts are books that have passed
    through all phases of proofreading and formatting and are now in
    the post-processing phase. Post-processing is the final assembly
    stage in which one volunteer performs a series of checks for consistency
    and correctness before the e-book is submitted to Project Gutenberg
    for your enjoyment and download.";
} elseif ($x == "b") {
    $type = "Bronze";
    $status = "Now Proofing";
    $state = SQL_CONDITION_BRONZE;
    $info = "Below is the list of Bronze e-texts that are currently
    available for proofreading on this site. Bronze e-texts are what our
    newest volunteers see and what you can work on now by logging in.
    These e-texts are in the initial stages of proofreading 
    where everyone has a chance to correct any OCR errors which may be found.
    After going through a number of other phases, the e-text then goes to
    an experienced volunteer for final assembly (post-processing), after
    which the e-text is submitted to Project Gutenberg for your enjoyment
    and download.";
} else {
    die("x parameter must be 'g', 's', or 'b'. ('$x')");
}

theme("$type E-Texts", "header");
?>

<center><font face="Verdana" size="6" color="<? echo $type; ?>"><b><? echo $status." ".$type; ?> E-Texts</b></font></center>
<center>
<?
$listsuffix = "&amp;sort=$sort&amp;per_page=$per_page";
if ($type == "Gold") {
    echo "Gold | <a href='list_etexts.php?x=s$listsuffix'>Silver</a> | <a href='list_etexts.php?x=g$listsuffix'>Bronze</a>";
} elseif ($type == "Silver") {
    echo "<a href='list_etexts.php?x=g$listsuffix'>Gold</a> | Silver | <a href='list_etexts.php?x=b$listsuffix'>Bronze</a>";
} elseif ($type == "Bronze") {
    echo "<a href='list_etexts.php?x=g$listsuffix'>Gold</a> | <a href='list_etexts.php?x=s$listsuffix'>Silver</a> | Bronze";
}
?>
</center><br>

<center><? echo $info . " " . $boilerplate; ?></center><br>

<?
$listurl = "list_etexts.php?x=$x&amp;per_page=$per_page&amp;offset=$offset";
?>
<center>
<i>Title:</i>          <a href="<? echo $listurl;?>&amp;sort=0">asc</a> or
                       <a href="<? echo $listurl;?>&amp;sort=1">desc</a> |
<i>Author:</i>         <a href="<? echo $listurl;?>&amp;sort=2">asc</a> or
                       <a href="<? echo $listurl;?>&amp;sort=3">desc</a> |
<i>Submitted Date:</i> <a href="<? echo $listurl;?>&amp;sort=4">asc</a> or
                       <a href="<? echo $listurl;?>&amp;sort=5">desc</a>
<br>
</center>
<hr width="75%" align="center">

<?
$sortlist[0]="ORDER BY nameofwork asc";
$sortlist[1]="ORDER BY nameofwork desc";
$sortlist[2]="ORDER BY authorsname asc";
$sortlist[3]="ORDER BY authorsname desc";
$sortlist[4]="ORDER BY modifieddate asc";
$sortlist[5]="ORDER BY modifieddate desc";

list_projects($state, $sortlist[$sort], "list_etexts.php?x=$x&amp;sort=$sort&", $per_page, $offset);

theme("", "footer");

// vim: sw=4 ts=4 expandtab
?>

