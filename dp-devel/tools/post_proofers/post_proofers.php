<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include($relPath.'bookpages.inc');
include($relPath.'showavailablepost.inc');
theme("Post Processing", "header");

echo "<br><p>This is the post processing section. The books listed below have already gone through two rounds of proofreading on this site and they now need to be massaged into a final e-text. Once you have checked out and downloaded a book it will remain checked out to you until you check it back in. <b>The Completed Post-Processing feature is currently disabled until it gets fully developed. E-mail the project manager with the completed project for now.</b>";
echo "<p>Here is a <a href='$siteurl/faq/post_proof.html'><b>Post Proof FAQ</b></a> that details all of the steps that we normally take to post proof an etext. There is a <a href='$forums_url/viewforum.php?f=3'>forum</a> to post all your questions. If no books are listed that means no work is currently available, please check back later!";
echo "<p><b>First Time here?</b>  Juliet Sutherland is our Post Processing Coordinator. Please read the FAQ, select an easy work to get started on (usually fiction with a low page count is a good starter book) and write <a href='mailto:juliet.sutherland@verizon.net'>Juliet</a> with any questions/comments.";
echo "<p><hr width=75% align='center'><br>";

//List the "My Checked Out Post-Processing Books"
echo "<center><b>My Checked Out Post-Processing Books:</b></center>";
echo "<table align='center' border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse;' bordercolor='#111111'>";
$rows = mysql_query("SELECT projectid, nameofwork, authorsname, username, scannercredit, language FROM projects WHERE checkedoutby = '$pguser' AND state='".PROJ_POST_CHECKED_OUT."'");
showavailablepost($rows,PROJ_POST_CHECKED_OUT);
echo "</table><br>";

//List the "My Checked Out Verifying Post-Processing Books"
echo "<center><b>My Checked Out Verifying Post-Processing Books:</b></center>";
echo "<table align='center' border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse;' bordercolor='#111111'>";
$rows = mysql_query("SELECT projectid, nameofwork, authorsname, username, scannercredit, language, postproofer FROM projects WHERE checkedoutby = '$pguser' AND state='".PROJ_POST_VERIFYING."'");
showavailableverify($rows,PROJ_POST_VERIFYING);
echo "</table><br>";

//List the "Available for Post Processing Books"
echo "<center><b>Available for Post-Processing Books:</b></center>";
echo "<table align='center' border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse;' bordercolor='#111111'>";
$rows = mysql_query("SELECT username, projectid, nameofwork, authorsname, language, genre FROM projects WHERE state='".PROJ_POST_AVAILABLE."'");
showavailablepost($rows,PROJ_POST_AVAILABLE);
echo "</table><br>";

//List the "Available for Post-Processing Verification & Posting Books"
echo "<center><b>Available for Post-Processing Verification & Posting Books:</b></center>";
echo "<table align='center' border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse;' bordercolor='#111111'>";
$rows = mysql_query("SELECT username, projectid, nameofwork, authorsname, language, genre, postproofer FROM projects WHERE state='".PROJ_POST_VERIFY."'");
showavailableverify($rows,PROJ_POST_VERIFY);
echo "</table><br>";

theme("", "footer");
?>
