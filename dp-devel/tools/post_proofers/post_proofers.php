<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include($relPath.'bookpages.inc');
include($relPath.'show_projects_in_state.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'site_news.inc');

global $pguser;

$userSettings = Settings::get_Settings($pguser);

theme(_("Post Processing"), "header");

if (!user_is_PP()) {
	echo _("You're not recorded as a post processor. If you feel this is an error, please contact the site administration.");
	exit();
}

// there are up to 4 potential sort orders for this page:
// one each for *Ch*ecked out PP and PPV, and for the pools of available PP and PPV

// read saved sort orders from user_settings table 

$qry = "SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'ChPPorder'";
$result = mysql_query($qry);

if (mysql_num_rows($result) >= 1) {
	$orderChPPold = mysql_result($result, 0, "value");
} else {
	$orderChPPold = 'DaysD';
}

$qry = "SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'ChPPVorder'";
$result = mysql_query($qry);


if (mysql_num_rows($result) >= 1) {
	$orderChPPVold = mysql_result($result, 0, "value");
} else {
	$orderChPPVold = 'DaysD';
}

$qry = "SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'PPorder'";
$result = mysql_query($qry);

if (mysql_num_rows($result) >= 1) {
	$orderPPold = mysql_result($result, 0, "value");
} else {
	$orderPPold = 'DaysD';
}

$qry = "SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'PPVorder'";
$result = mysql_query($qry);


if (mysql_num_rows($result) >= 1) {
	$orderPPVold = mysql_result($result, 0, "value");
} else {
	$orderPPVold = 'DaysD';
}


// read new sort order from url, if any

$orderChPP = (isset($_GET['orderChPP']) ? $_GET['orderChPP'] : $orderChPPold );
$orderChPPV = (isset($_GET['orderChPPV']) ? $_GET['orderChPPV'] : $orderChPPVold );
$orderPP = (isset($_GET['orderPP']) ? $_GET['orderPP'] : $orderPPold );
$orderPPV = (isset($_GET['orderPPV']) ? $_GET['orderPPV'] : $orderPPVold );

// if orders have changed, save them to database

if ($orderChPP != $orderChPPold) {
	$result = mysql_query("DELETE FROM usersettings WHERE username = '$pguser' AND setting = 'ChPPorder'");
	$result = mysql_query("INSERT INTO usersettings VALUES ('$pguser', 'ChPPorder', '$orderChPP') ");
}

if ($orderChPPV != $orderChPPVold) {
	$result = mysql_query("DELETE FROM usersettings WHERE username = '$pguser' AND setting = 'ChPPVorder'");
	$result = mysql_query("INSERT INTO usersettings VALUES ('$pguser', 'ChPPVorder', '$orderChPPV') ");
}

if ($orderPP != $orderPPold) {
	$result = mysql_query("DELETE FROM usersettings WHERE username = '$pguser' AND setting = 'PPorder'");
	$result = mysql_query("INSERT INTO usersettings VALUES ('$pguser', 'PPorder', '$orderPP') ");
}

if ($orderPPV != $orderPPVold) {
	$result = mysql_query("DELETE FROM usersettings WHERE username = '$pguser' AND setting = 'PPVorder'");
	$result = mysql_query("INSERT INTO usersettings VALUES ('$pguser', 'PPVorder', '$orderPPV') ");
}


show_site_news_for_page("post_proofers.php");
random_news_item_for_page("post_proofers.php");

$isPPV = user_is_post_proof_verifier();


echo "
<br>
<p><font color=red>
The display of texts available for (and checked out for) post-processing and verification has changed. 
The options which were presented before in a pulldown menu are now available on a new project-specific page. 
This new page is available by clicking on the title of the text you are interested in. 
(We strongly recommend you right-click and open this project-specific page in a new window or tab.) 
The new page lets you see the project comments and check the project in or out as well as download the associated text and image files without having to wait for the main post-processing page to reload after each action. 
</font>
</p>
";


if (!$isPPV) {
	echo "<br><p>";
	echo _("The books listed below have already gone through two rounds of proofreading on this site and they now need to be massaged into a final e-text. ");
	echo _("Once you have checked out and downloaded a book it will remain checked out to you until you check it back in. ");
	echo _("When you have finished your work on the book, select <i>Upload for Verification</i> from the drop-down list for that project. ");
	echo _("If you have several files to submit for a single project (say a text and HTML version), zip them up together first.");

	echo "</p><p>";
	echo _("<b>First Time here?</b>");
	echo _("Julie Barkley (<a href='http://www.pgdp.net/phpBB2/privmsg.php?mode=post&u=1674'>juliebarkley</a>) maintains our");
	echo " <a href='$code_url/faq/post_proof.php'>";
	echo _("Post Processing FAQ");
	echo "</a>. ";
	echo _("Please read the FAQ as it covers all the steps needed to post process an e-text. ");
	echo _("Select an easy work to get started on (usually fiction with a low page count is a good starter book; projects whose manager is BEGIN make excellent first projects for a new post processor). ");
	echo _("Check out the");
	echo " <a href='$post_processing_forum_url'>";
	echo _("Post Processing Forum</a> to post all your questions. ");
	echo _("If nothing interests you right now, check back later and there will be more!");
	echo "</p>";
} else {

	echo "<p><br>";
	echo _("As an experienced volunteer, you have access to do verification of texts that have been Post Processed already, if you wish. ");
	echo "<font color='red' size=4>";
	echo _("Make sure you read the <b>new</b>");
	echo " <a href='$code_url/faq/ppv.php'>";
	echo _("Post Processing Verification Guidelines</a> and use the");
	echo " <a href='$code_url/faq/ppv_report.txt'>";
	echo _("PPV Report Card for each project you PPV");
	echo "</a>.</font><br />";

	echo _("Julie Barkley (<a href='http://www.pgdp.net/phpBB2/privmsg.php?mode=post&u=1674'>juliebarkley</a>) maintains our");
	echo " <a href='$code_url/faq/post_proof.php'>";
	echo _("Post Processing FAQ");
	echo "</a>. ";
	echo _("As always, the ");
	echo "<a href='$post_processing_forum_url'>";
	echo _("Post Processing Forum</a> is available for any of your questions.</p>");
}

// special colours legend
// Don't display if the user has selected the
// setting "Show Special Colors: No".
if (!$userSettings->get_boolean('hide_special_colors'))
{
    echo "<hr width='75%'>\n";
    echo "<p><font face='{$theme['font_mainbody']}'>\n";
    echo_special_legend(" 1 = 1");
    echo "</font></p><br>\n";
}

echo "<hr width=75% align='center'>\n";
echo "<a name='ChPP'></a>\n";
echo "<center><b>"._("Books I Have Checked Out for Post Processing:")."</b></center>";
show_projects_in_state(PROJ_POST_FIRST_CHECKED_OUT, 1, " ", $orderChPP);
echo "<br>";

if ($isPPV) {
echo "<a name='ChPPV'></a>\n";
echo "<center><b>"._("Books I Have Checked Out for Verifying Post Processing:")."</b></center>";
show_projects_in_state(PROJ_POST_SECOND_CHECKED_OUT, 1, " ", $orderChPPV);
echo "<br>";
}

echo "<br><hr><br><br>";

$label = _("Post-Processing");
$state_sql = " (state = '".PROJ_POST_FIRST_AVAILABLE."') ";
$filtertype_stem = "avail_PP";
include($relPath.'filter_project_list.inc');

if (!isset($RFilter)) { $RFilter = ""; }

echo "<a name='PP'></a>\n";
echo "<center><b>"._("Books Available for Post Processing:")."</b></center>";
show_projects_in_state(PROJ_POST_FIRST_AVAILABLE, 1, $RFilter, $orderPP);
echo "<br>";

if ($isPPV) {

echo "<br><hr><br><br>";

$label = _("Post-Processing Verification");
$state_sql = " (state = '".PROJ_POST_SECOND_AVAILABLE."') ";
$filtertype_stem = "avail_PPV";
include($relPath.'filter_project_list.inc');

echo "<a name='PPV'></a>\n";
echo "<center><b>"._("Books Available for Post Processing Verification & Posting:")."</b></center>";
show_projects_in_state(PROJ_POST_SECOND_AVAILABLE, 1, $RFilter, $orderPPV);
echo "<br>";
}

theme("", "footer");
?>
