<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');


if ($userP['i_newwin']==1) { include($relPath.'js_newwin.inc'); }
theme(_("Personal Page for $pguser"), "header");

	// determine number of pages

	$result = mysql_query("SELECT pagescompleted FROM users WHERE username = '$pguser'");
	$pagesproofed = mysql_result($result,0);

	// DEMO VERSION allows people to simulate different page counts via parameter in URL,
	// so long as LESS than their actual number of pages
	if (isset($_GET['numofpages']))
	{
		if ($_GET['numofpages'] < $pagesproofed)
		{
	       	   $pagesproofed =  $_GET['numofpages'];
		}
	}


	// Unread messages

        if ($pagesproofed <= 300) {
		$result = mysql_query("SELECT user_id FROM phpbb_users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
    		$pguser_id = mysql_result($result, 0, "user_id");
    	
	    	$result = mysql_query("SELECT COUNT(*) as num FROM phpbb_privmsgs WHERE privmsgs_to_userid = $pguser_id && privmsgs_type = 1 || privmsgs_to_userid = $pguser_id && privmsgs_type = 5");
		$numofPMs = (int) mysql_result($result, 0, "num");
		if ($numofPMs > 0) {
			echo "<br><br><font color='red' size=3><b>";
			echo _("You have received a private message in your Inbox!");
			echo "</b></font><br><br><font color='red'>";
			echo _("This could be from somebody sending you feedback on some of the pages you had proofread earlier. We strongly recommend you READ your messages. Near the upper right corner of this page, there is a link that says Inbox. Just click on that to open your Inbox.");
			echo "</font><br><br><i><font size=-1>";
			echo _("(After a while this explanatory paragraph will not appear when you have new messages, but the link to your Inbox will always be up there and when you have new messages that will be shown in the link)");
			echo "</font></i><br><br>\n";
		}

	}

        // Beginners Info

        if ($pagesproofed <= 100) {

                echo '<font face="'. $theme['font_mainbody'] .'" color = "blue" size=+3><br><b>';
                echo _("Welcome");
                echo "</b></font><br><br>";
                echo _("Please see our");
                echo " <a href=".$beginners_site_forum_url.">";
                echo _("Beginner's Forum");
                echo "</a> ";
                echo _("for answers to common questions.");
                echo "<br><br>\n";

                if ($pagesproofed > 80) {
                        echo "<i><font size=-1>";
                        echo _("After you proof a few more pages, the following introductory Simple Proofreading Rules will be removed from this page. However, they are permanently available ");
 	                echo "<a href =" . $code_url . "/faq/simple_proof_rules.php>";
			echo _("here");
			echo "</a> ";
			echo _("if you wish to refer to them later. (You can bookmark that link if you like.)");
                        echo "</font></i><br><br>";
                }

		include($relPath.'simple_proof_text.inc');

                echo "<center><hr width='75%'></center><br>";

        }




        //Display News Updates

        if ($pagesproofed >= 20) {
                if ($pagesproofed < 40) {
                        echo "<font size=-1 face=" . $theme['font_mainbody'] . "><i>";
                        echo _("Now that you have proofread 20 pages you can see the Site News. This is updated regularly with announcements from the administrators.");
			echo "<br>";
			echo _("(This explanatory line will eventually vanish.)");
                        echo "</i></font><br><br>\n";
                }

                $result = mysql_query("SELECT date_posted, message FROM news ORDER BY uid DESC LIMIT 1");
                $news = mysql_fetch_assoc($result);
                echo "<font size=2 face=" . $theme['font_mainbody'] . "><center><b>";
                echo _("News Update for")." ".strftime(_("%A, %B %e, %Y"), $news['date_posted'])." (<a href='$code_url/pastnews.php'>";

		echo _("archives") . "</a>)";


		// this commented out until fuller rollout

         	// echo " <a href='$code_url/feeds/backend.php?content=news'><img src='$code_url/graphics/xml.gif'></a>";
 	        // echo "<a href='$code_url/feeds/backend.php?content=news&type=rss'><img src='$code_url/graphics/rss.gif'></a>";

         	echo "</b></font><br><br><font size=2 face=";


                echo $theme['font_mainbody'] . ">".$news['message']."<hr width='75%'></center></font><br>\n";

		include("./../../stats/currentstatestats.php");

        }


        // Plug for providing content

        if ($pagesproofed >= 60) {
                echo "<font face=" . $theme['font_mainbody'] ."><b>";
                echo _("Providing Content");
                echo "</b></font><br>";
                echo _("Want to help out the site by providing material for us to proofread?");
                echo "<a href=" . $code_url . "/faq/scan/submitting.php> ";
                echo _("Find out how!");
                echo "</a><br><br>\n";
        }



        // If Post Processor give link to post processing page.
        if ( $pagesproofed >= 400) {

                $result = mysql_query("SELECT count(projectid) FROM projects WHERE state='".PROJ_POST_FIRST_AVAILABLE."' || state='".PROJ_POST_SECOND_AVAILABLE."'");
                $numprojects = mysql_result($result,0);
                $result = mysql_query("SELECT count(projectid) FROM projects WHERE checkedoutby='$pguser' && state='".PROJ_POST_FIRST_CHECKED_OUT."'");
                $yourPPprojects = mysql_result($result,0);
                $result = mysql_query("SELECT count(projectid) FROM projects WHERE checkedoutby='$pguser' && state='".PROJ_POST_SECOND_CHECKED_OUT."'");
                $yourPPVprojects = mysql_result($result,0);

                echo "<font face=" . $theme['font_mainbody'] ."><b>";
                echo _("Post Processing");
                echo "</b></font><br>";
                echo _("After going through two rounds of proofreading, the books need to be massaged into a final e-text. You can help in the ");
                echo "<a href =" . $code_url . "/tools/post_proofers/post_proofers.php>";
                echo _("Post-Processing");
                echo "</a> ";
                echo _(" phase of Distributed Proofreaders!  Currently there are");
                echo " <b>".$numprojects."</b> ";
                echo _("projects waiting.");

                if (($yourPPprojects + $yourPPVprojects)  > 0) {
                        echo "  ". _("You currently have") . " ";
                        if ($yourPPprojects == 1) {
                                echo "<b>1</b> " . _("project checked out for PPing");
                        }
                        elseif ($yourPPprojects > 1) {
                                echo "<b>".$yourPPprojects."</b> ". _("projects checked out for PPing");
                        }
                        if ($yourPPprojects > 0 && $yourPPVprojects > 0) {
                                echo " ". _("and") . " ";
                        }
                        if ($yourPPVprojects == 1) {
                                echo "<b>1</b> " . _("project checked out for PPVing");
                        }
                        elseif ($yourPPVprojects > 1) {
                                echo "<b>".$yourPPVprojects."</b> ". _("projects checked out for PPVing");
                        }
                        echo ".";
                }
                echo "<br><br>\n";
                echo "<center><hr width='75%'></center><br>";

        }


        if ($pagesproofed >= 10) {

                if ($pagesproofed < 40) {
                        echo "<font size=-1 face=" . $theme['font_mainbody'] . "><i>";
                        echo _("Now that you have proofread 10 pages you can see the Random Rule. Every time this page is refreshed, a random rule is selected from the");
			echo " <a href=" . $code_url . "/faq/document.php>";
			echo _("Proofreading Guidelines");
			echo "</a> ";
			echo _("is displayed in this section");
			echo "<br>";
			echo _("(This explanatory line will eventually vanish.)");
                        echo "</i></font><br><br>";
                }


                echo "<font face=".$theme['font_mainbody']."><b>";
                echo _("Random Rule");
                echo "</b></font><br>";


                $result = mysql_query("SELECT subject,rule,doc FROM rules ORDER BY RAND(NOW()) LIMIT 1");
                $rule = mysql_fetch_assoc($result);
                echo "<i>".$rule['subject']."</i><br>";
                echo "".$rule['rule']."<br>";
                echo _("See the ");
                echo "<a href='$code_url/faq/document.php#".$rule['doc']."'>".$rule['subject']."</a>";
                echo _(" section of the ");
                echo "<a href='$code_url/faq/document.php'>";
		echo _("Proofreading Guidelines");
                echo "</a><br><br>";

                echo "<center><hr width='75%'></center><br>";

        }

        if ($pagesproofed >= 15) {

                if ($pagesproofed < 200) {

			echo _("New Proofreaders:");
                        echo " <a href='$forums_url/viewtopic.php?t=6651'>";
			echo _("What did you think of the Mentor feedback you received?");
                        echo "</a><br><br>";

		}
	}

        if ($pagesproofed >= 90) {

                if ($pagesproofed < 100) {
                        echo "<font size=-1 face=" . $theme['font_mainbody'] . "><br><br><i>";
                        echo _("Soon you will be able to see the books in Second Round. ");
			echo _("Every page that is proofread in First Round is proofread again, by someone else, in Second Round, to check for any errors that may have been missed.");
			echo "<br>";
			echo _("(This explanatory line will eventually vanish.)");
                        echo "</i></font><br><br>";
                }
        }


        if ($pagesproofed >= 100) {
                if ($pagesproofed < 150) {
                        echo "<font size=-1 face=" . $theme['font_mainbody'] . "><br><br><i>";
                        echo _("Now that you have proofread 100 pages you can see the books in Second Round. ");
			echo _("Every page that is proofread in First Round is proofread again, by someone else, in Second Round, to check for any errors that may have been missed.");
			echo "<br>";
			echo _("(This explanatory line will eventually vanish.)");
                        echo "</i></font><br><br>";
                }
        }

if ($pagesproofed >= 10) {
?>

<p><font face="<? echo $theme['font_mainbody']; ?>">

<? include('special_legend.php'); ?>

</font>
</p>
<br>

<?
}

include_once('proof_list.inc');

theme("", "footer");
?>
