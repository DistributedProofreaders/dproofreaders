<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include($relPath.'gettext_setup.inc');
include($relPath.'bookpages.inc');
include($relPath.'showavailablebooks.inc');
include($relPath.'project_states.inc');
include($relPath.'page_states.inc');
if ($userP['i_newwin']==1) { include($relPath.'js_newwin.inc'); }
theme("Personal Page for $pguser", "header");



	//Display News Updates
	$result = mysql_query("SELECT * FROM news ORDER BY uid DESC LIMIT 1");
	$date_posted = date("l, F jS, Y", mysql_result($result,0,"date_posted"));
	$message = mysql_result($result,0,"message");
	echo "<font size=2 face=".$theme['font_mainbody']."><center><b>"._("News Update for")." $date_posted (<a href='$code_url/pastnews.php'>"._("archives")."</a>)</b></font><br><br><font size=2 face=".$theme['font_mainbody'].">$message<hr width='75%'></center></font><br>";
	
	// If Post Processor give link to post processing page.
    	$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
    	$postprocessorpages = mysql_result($result,0);
    	if ($userP['postprocessor'] == "yes" || $postprocessorpages >= 400) {
    		$result = mysql_query("SELECT count(projectid) FROM projects WHERE state='".PROJ_POST_AVAILABLE."' || state='".PROJ_POST_VERIFY."'");
        	$numprojects = mysql_result($result,0);
        	$result = mysql_query("SELECT count(projectid) FROM projects WHERE checkedoutby='$pguser' && (state='".PROJ_POST_CHECKED_OUT."' || state='".PROJ_POST_VERIFYING."')");
        	$yourprojects = mysql_result($result,0);
?>

<font face="<? echo $theme['font_mainbody']; ?>"><b><? _("Providing Content"); ?></b></font><br>
<? _("Want to help out the site by providing material for us to proof?"); ?>
<a href="<? echo $code_url ?>/faq/scan/submitting.php"><? _("Find out how!"); ?></a>
<br><br>

<font face="<? echo $theme['font_mainbody']; ?>"><b><? _("Post Processing"); ?></b></font><br>
<? _("After going through two rounds of proofreading, the books need to be massaged into a final e-text. You can help in the"); ?> <a href ="<? echo $code_url ?>/tools/post_proofers/post_proofers.php"><? _("post processing"); ?></a> <? _("phase of Distributed Proofreaders!  Currently there are"); ?> <b><? echo $numprojects; ?></b> <? _("projects waiting."); ?>

<? 
if ($yourprojects > 0) { echo "  "._("You currently have <b>$yourprojects</b> projects checked out."); }
echo "<br><br>";
}
?>


<font face="<? echo $theme['font_mainbody']; ?>"><b>Random Rule</b></font><br>

<?
	$result = mysql_query("SELECT count(id) FROM rules");
 	$num_rules = mysql_result($result,0);
	$randid  = rand(0,$num_rules); 
        $result = mysql_query("SELECT subject,rule,doc FROM rules WHERE id=$randid");
    	$rule = mysql_fetch_assoc($result);
    	echo "<i>".$rule['subject']."</i><br>";
    	echo "".$rule['rule']."<br><br>";
    	echo "See the <a href='$code_url/faq/document.php#".$rule['doc']."'>".$rule['subject']."</a> section of the <a href='$code_url/faq/document.php'>Proofing Guidelines</a><br><br>";

	echo "<center><hr width='75%'></center><br>";

$tList=0;
include_once('proof_list.inc');
?>

<?
theme("", "footer");
?>
