<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');
if ($userP['i_newwin']==1) { include($relPath.'js_newwin.inc'); }
theme("Personal Page for $pguser", "header");

	// determine number of pages
    	$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
    	$pagesproofed = mysql_result($result,0);



	//Display News Updates

	if ($pagesproofed >= 20) {
		if ($pagesproofed < 40) {
			echo "<font face=" . $theme['font_mainbody'] . "><i>";
			echo _("Now you have proofed 20 pages you can see the Site News. This explanatory line will eventually vanish.");
			echo "</i></font><br><br>";
		}
	
		$result = mysql_query("SELECT date_posted, message FROM news ORDER BY uid DESC LIMIT 1");
		$news = mysql_fetch_assoc($result);
		echo "<font size=2 face=" . $theme['font_mainbody'] . "><center><b>";
		echo _("News Update for")." ".date("l, F jS, Y", $news['date_posted'])." (<a href='$code_url/pastnews.php'>";
		echo _("archives") . "</a>)</b></font><br><br><font size=2 face=";
		echo $theme['font_mainbody'] . ">".$news['message']."<hr width='75%'></center></font><br>";
	}

	// Beginners Info

	if ($pagesproofed <= 40) {
		echo "<font face=" . $theme['font_mainbody'] . "><b>";
		echo _("Brand New Proofers"); 
		echo "</b></font><br>";
		echo _("BEGINNERS ONLY projects are reserved for brand new proofers; after you have done between 5 to 15 pages total from these BEGINNERS ONLY projects, though, please move on to other projects. EASY projects are available for everyone, but make a great second step for beginners, too."); 
		echo "<br><br>";
	}


	// Plug for providing content

	if ($pagesproofed >= 60) {
		echo "<font face=" . $theme['font_mainbody'] ."><b>"; 
		echo _("Providing Content");
		echo "</b></font><br>";
		echo _("Want to help out the site by providing material for us to proof?");
		echo "<a href=" . $code_url . "/faq/scan/submitting.php>"; 
		echo _("Find out how!"); 
		echo "</a><br><br>";
	}

	// If Post Processor give link to post processing page.

    	if ($userP['postprocessor'] == "yes" || $pagesproofed >= 400) {
    		$result = mysql_query("SELECT count(projectid) FROM projects WHERE state='".PROJ_POST_FIRST_AVAILABLE."' || state='".PROJ_POST_SECOND_AVAILABLE."'");
        	$numprojects = mysql_result($result,0);
        	$result = mysql_query("SELECT count(projectid) FROM projects WHERE checkedoutby='$pguser' && state='".PROJ_POST_FIRST_CHECKED_OUT."'");
        	$yourPPprojects = mysql_result($result,0);
        	$result = mysql_query("SELECT count(projectid) FROM projects WHERE checkedoutby='$pguser' && state='".PROJ_POST_SECOND_CHECKED_OUT."'");
        	$yourPPVprojects = mysql_result($result,0);

		echo "<font face=" . $theme['font_mainbody'] ."><b>";
		echo _("Post Processing"); 
		echo "</b></font><br>";
		echo _("After going through two rounds of proofreading, the books need to be massaged into a final e-text. You can help in the"); 
		echo "<a href =" . $code_url . "/tools/post_proofers/post_proofers.php>";
		echo _("post processing"); 
		echo "</a> "; 
		echo _("phase of Distributed Proofreaders!  Currently there are"); 
		echo "<b>".$numprojects."</b>"
		echo _("projects waiting."); 

		if (($yourPPprojects + $yourPPVprojects)  > 0) { 
			echo "  ". _("You currently have") . " ";
			if ($yourPPprojects > 0) {
				echo " <b>".$yourPPprojects."</b>". _("projects checked out for PPing");
				if ($yourPPVprojects > 0) {
					echo " ". _("and");
				} 
			}
			if ($yourPPVprojects > 0) {
				echo " <b>".$yourPPVprojects."</b>". _("projects checked out for PPVing");
			} 
			echo ".";
		}
		echo "<br><br>";
	}


	if ($pagesproofed >= 10) {

		if ($pagesproofed < 40) {
			echo "<font face=" . $theme['font_mainbody'] . "><i>";
			echo _("Now you have proofed 10 pages you can see the Random Rule. This explanatory line will eventually vanish.");
			echo "</i></font><br><br>";
		}


		echo "<font face=".$theme['font_mainbody']."><b>";
		echo _(Random Rule);
		echo "</b></font><br>";


	        $result = mysql_query("SELECT subject,rule,doc FROM rules ORDER BY RAND(NOW()) LIMIT 1");
	    	$rule = mysql_fetch_assoc($result);
	    	echo "<i>".$rule['subject']."</i><br>";
	    	echo "".$rule['rule']."<br>";
	    	echo "See the <a href='$code_url/faq/document.php#".$rule['doc']."'>".$rule['subject']."</a> section of the <a href='$code_url/faq/document.php'>Proofing Guidelines</a><br><br>";

		echo "<center><hr width='75%'></center><br>";

	}

$tList=0;
include_once('proof_list.inc');
?>

<?
theme("", "footer");
?>
