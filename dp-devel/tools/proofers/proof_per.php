<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
?>
<style type="text/css">
<!--
.orange {
	background-color: #FF9900;
}
.green {
	background-color: #00FF33;
}
.paleblue {
	background-color: #CCFFFF;
}
.richblue {
	background-color: #33CCFF;
}
.yellow {
	background-color: #FFFF33;
}
.grey {
	background-color: #CCCCCC;
}
.red {
	background-color: #FF0000;
}
-->
</style>
<?
if ($userP['i_newwin']==1) { include($relPath.'js_newwin.inc'); }
theme("Personal Page for $pguser", "header");



	//Display News Updates
	$result = mysql_query("SELECT date_posted, message FROM news ORDER BY uid DESC LIMIT 1");
	$news = mysql_fetch_assoc($result);
	echo "<font size=2 face=" . $theme['font_mainbody'] . "><center><b>";
	echo _("News Update for")." ".date("l, F jS, Y", $news['date_posted'])." (<a href='$code_url/pastnews.php'>";
	echo _("archives") . "</a>)</b></font><br><br><font size=2 face=";
	echo $theme['font_mainbody'] . ">".$news['message']."<hr width='75%'></center></font><br>";
?>

<font face="<? echo $theme['font_mainbody']; ?>"><b><? echo _("Brand New Proofers"); ?></b></font><br>
<? echo _("BEGINNERS ONLY projects are reserved for brand new proofers; after you have done between 5 to 15 pages total from these BEGINNERS ONLY projects, though, please move on to other projects. EASY projects are available for everyone, but make a great second step for beginners, too."); ?>
<br><br>

<font face="<? echo $theme['font_mainbody']; ?>"><b><? echo _("Providing Content"); ?></b></font><br>
<? echo _("Want to help out the site by providing material for us to proof?"); ?>
 <a href="<? echo $code_url ?>/faq/scan/submitting.php"><? echo _("Find out how!"); ?></a>
<br><br>

<?
	// If Post Processor give link to post processing page.
    	$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
    	$postprocessorpages = mysql_result($result,0);

    	if ($userP['postprocessor'] == "yes" || $postprocessorpages >= 400) {
    		$result = mysql_query("SELECT count(projectid) FROM projects WHERE state='".PROJ_POST_FIRST_AVAILABLE."' || state='".PROJ_POST_SECOND_AVAILABLE."'");
        	$numprojects = mysql_result($result,0);
        	$result = mysql_query("SELECT count(projectid) FROM projects WHERE checkedoutby='$pguser' && (state='".PROJ_POST_FIRST_CHECKED_OUT."' || state='".PROJ_POST_SECOND_CHECKED_OUT."')");
        	$yourprojects = mysql_result($result,0);
?>

<font face="<? echo $theme['font_mainbody']; ?>"><b><? echo _("Post Processing"); ?></b></font><br>
<? echo _("After going through two rounds of proofreading, the books need to be massaged into a final e-text. You can help in the"); ?> <a href ="<? echo $code_url ?>/tools/post_proofers/post_proofers.php"><? echo _("post processing"); ?></a> <? echo _("phase of Distributed Proofreaders!  Currently there are"); ?> <b><? echo $numprojects; ?></b> <? echo _("projects waiting."); ?>

<?
if ($yourprojects > 0) { echo "  ". _("You currently have <b>$yourprojects</b> projects checked out."); }
echo "<br><br>";
}
?>


<font face="<? echo $theme['font_mainbody']; ?>"><b>Random Rule</b></font><br>

<?
        $result = mysql_query("SELECT subject,rule,doc FROM rules ORDER BY RAND(NOW()) LIMIT 1");
    	$rule = mysql_fetch_assoc($result);
    	echo "<i>".$rule['subject']."</i><br>";
    	echo "".$rule['rule']."<br>";
    	echo "See the <a href='$code_url/faq/document.php#".$rule['doc']."'>".$rule['subject']."</a> section of the <a href='$code_url/faq/document.php'>Proofing Guidelines</a><br><br>";

	echo "<center><hr width='75%'></center><br>";
?>



//<body bgcolor="#FFFFFF">
<p><font face="<? echo $theme['font_mainbody']; ?>">Legend for Special Books:<br><br><b><span class="orange"> Halloween </span>&nbsp; 
<span class="green"> Ramadan </span>&nbsp;<span class="paleblue"> recent authors' birthdays </span>&nbsp;
  <span class="richblue"> TODAY's author birthdays </span>&nbsp;<br><span class="yellow"> Children's Book Week </span> 
   &nbsp;<span class="grey"> Native American Heritage Month </span></b></font></p>


<?
$tList=0;
include_once('proof_list.inc');
?>

<?
theme("", "footer");
?>
