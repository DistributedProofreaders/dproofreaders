<?
$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];

include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'udb_user.php');
include_once($relPath.'cookie.inc');
include_once($relPath.'user_is.inc');

//Let's check to see if the user has a cookie already
	$cookieC=new userCookie();
	$cookieSet=$cookieC->checkCookie();
	if ($cookieSet) { $pguser=$cookieC->userID;
		if (isset($_COOKIE[$pguser.'_prefs'])){
			$cookieC->deleteOldPrefs($pguser);
			$cookieC->setUserPrefs($pguser);
			echo 'Cookie reset by site update.  Please refresh current page.';
			exit;
		}
	$userP=$cookieC->getUserPrefs($pguser);
}

if(isset($userP['i_theme'])) {
		include_once($relPath.'templates/'.$userP['i_theme'].'/theme.tpl');
		$theme_name = $userP['i_theme'];
		$theme = ${$theme_name};
	} else {
		include_once($relPath.'templates/project_gutenberg/theme.tpl');
		$theme_name = "project_gutenberg";
		$theme = ${$theme_name};
	}

include_once($relPath.'gettext_setup.inc');

function theme ($nameofpage, $location) {

	if (isset($GLOBALS['pguser'])) {
		$userP = $GLOBALS['userP'];
		$statsbar_align = $userP['u_align'];
		} else {
		$statsbar_align = 0;
		$userP = "";
	}

	$code_url = $GLOBALS['code_url'];
	$theme_name = $GLOBALS['theme_name'];
	$theme = $GLOBALS['theme'];

	if (!isset($GLOBALS['no_stats'])) {
		if ($statsbar_align == 1) {
			if ($location == "header") {
				html_header($code_url, $theme, $nameofpage);
				html_logobar($code_url, $theme, $nameofpage, $theme_name);
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
				echo "<tr>\n";
				echo "<td align='left' valign='top' width='25%' bgcolor='".$theme['color_navbar_bg']."'>\n";
				html_statsbar($code_url, $theme, $nameofpage, $theme_name, $userP);
				echo "</td>\n";
				echo "<td align='right' valign='top' bgcolor='".$theme['color_mainbody_bg']."'><img border='0' src='$code_url/pinc/templates/".$theme_name."".$theme['image_curve_left']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>\n";
				echo "<td align='left' valign='top' width='75%' bgcolor='".$theme['color_mainbody_bg']."'>\n";
			} elseif ($location == "footer") {
				echo "</td></tr></table>\n";
				html_footer($code_url, $theme, $theme_name);
			}
		} else {
			if ($location == "header") {
				html_header($code_url, $theme, $nameofpage);
				html_logobar($code_url, $theme, $nameofpage, $theme_name);
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
				echo "<tr>\n";
				echo "<td align='left' valign='top' width='5' bgcolor='".$theme['color_mainbody_bg']."'>&nbsp;</td>\n";
				echo "<td align='left' valign='top' width='75%' bgcolor='".$theme['color_mainbody_bg']."'>\n";
			} elseif ($location == "footer") {
				echo "</td>\n";
				echo "<td align='right' valign='top' bgcolor='".$theme['color_mainbody_bg']."'><img border='0' src='$code_url/pinc/templates/".$theme_name."".$theme['image_curve_right']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>\n";
				echo "<td align='left' valign='top' width='25%' bgcolor='".$theme['color_navbar_bg']."'>\n";
				html_statsbar($code_url, $theme, $nameofpage, $theme_name, $userP);
				echo "</td></tr></table>\n";
				html_footer($code_url, $theme, $theme_name);
			}
		}
	} else {
		if ($location == "header") {
			html_header($code_url, $theme, $nameofpage);
			html_logobar($code_url, $theme, $nameofpage, $theme_name);
			echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
			echo "<tr>\n";
			echo "<td align='left' valign='top' width='5' bgcolor='".$theme['color_mainbody_bg']."'>&nbsp;</td>\n";
			echo "<td align='left' valign='top' width='100%' bgcolor='".$theme['color_mainbody_bg']."'>\n";
		} elseif ($location == "footer") {
			echo "</td></tr></table>\n";
			html_footer($code_url, $theme, $theme_name);
		}
	}
}

function html_header($code_url, $theme, $nameofpage) {
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
        echo "<html><head><link rel='shortcut icon' href='$code_url/favicon.ico'>\n";
	echo "<title>DP";
        if (isset($nameofpage)) { echo " -- $nameofpage"; }
        echo "</title>\n";
	echo "<META http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\"></head>\n";
	echo "<body bgcolor='".$theme['body_bgcolor']."' text='".$theme['body_text']."' link='".$theme['body_link']."' vlink='".$theme['body_vlink']."' alink='".$theme['body_alink']."' topmargin=0 leftmargin=0 marginheight=0 marginwidth=0>\n";
}

function html_logobar($code_url, $theme, $nameofpage, $theme_name) {
	global $dynstats_dir;
	echo "<table width='100%' cellSpacing=0 cellPadding=0 border=0>\n";
	echo "<tr>\n";
	echo "<td width='50%' bgcolor='".$theme['color_logobar_bg']."'>\n";
	echo "<a href='$code_url'><img src='$code_url/pinc/templates/".$theme_name."".$theme['image_logo']."' width='360' height='68' alt='Distributed Proofreaders Logo' border='0'></a>\n";
	echo "</td>\n";
	// show number of projects left till 10,000
	echo "<td width='50%' bgcolor='" . $theme['color_logobar_bg'] . "' align='center' valign='middle'>\n";
	if ($fp = fopen("$dynstats_dir/total_count.txt", "r"))
	{
		$line = fgets($fp);
	}
	else
	{
		$line = "-1";
	}
	$numProjectsLeft = 10000 - $line;
	if ($numProjectsLeft > 0) {
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>".number_format($numProjectsLeft)." books left till number 10,000 is posted to PG!</font>\n";
		} elseif ($numProjectsLeft <= 0) {
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><b><i><u>WE MADE IT!!  10,000 Books Posted to Project Gutenberg!!</u></i></b></font>\n";
	}
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_headerbar_bg']."'>\n";
	echo "<tr>\n";
	echo "<td width='10' align='left' bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td>\n";
	echo "<td width='50%' align='left' valign='middle' bgcolor='".$theme['color_headerbar_bg']."'>\n";

    //Create the name of the page
    	echo "<a href='$code_url/default.php'>\n";
	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>DP</font></a>\n";
        if (isset($nameofpage)) { echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'> &#187; $nameofpage</font>\n"; }
	echo "</td>\n";

    //Code to display login form if not logged in
	if (!isset($GLOBALS['pguser'])) {
	// If you put the form element inside the td element (which would
	// seem to make sense), Netscape and IE both render the cell
	// (and thus the row and the table) twice as tall as it needs to be.
	// (Mozilla gets it right, but that's small comfort.)
	// So instead, put the td element within the form.
	// (Yeah, it doesn't make sense, but the browsers don't complain.)
	echo "<form action='$code_url/accounts/login.php' method='post'>\n";
	echo "<td width='50%' align='right' bgcolor='".$theme['color_headerbar_bg']."'>\n";
	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>";
	echo "ID:<input type='text' name='userNM' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;";
	echo "Password:<input type='password' name='userPW' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;";
	echo "<input type='submit' value='Sign In' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>";
	echo "&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$code_url/accounts/addproofer.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Register</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$code_url/faq/faq_central.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Help</font></a>&nbsp;</font>\n";
	echo "</td>\n";
	echo "</form>\n";
	} else {
    //Code to display if the user is logged in
    	$result = mysql_query("SELECT user_id FROM phpbb_users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
    	$pguser_id = mysql_result($result, 0, "user_id");
    	$result = mysql_query("SELECT COUNT(*) FROM phpbb_privmsgs WHERE privmsgs_to_userid = $pguser_id && privmsgs_type = 1 || privmsgs_to_userid = $pguser_id && privmsgs_type = 5");
    	$numofPMs = mysql_fetch_row($result);
    	$result = mysql_query("SELECT date_created, u_id FROM users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
    	$u_id = mysql_result($result, 0, "u_id");
    	$datecreated = mysql_result($result, 0, "date_created");
	echo "<td width='50%' align='right' bgcolor='".$theme['color_headerbar_bg']."'>\n";
    	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>";
	if ((time() - $datecreated) < 604800) { echo "Start Here -->&nbsp;"; }
	echo "<a href='$code_url/faq/faq_central.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Help</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='".$GLOBALS['forums_url']."/'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Forums</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='".$GLOBALS['forums_url']."/privmsg.php?folder=inbox'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Inbox</a>";
	if ($numofPMs[0] > 0) { echo " ($numofPMs[0] unread)"; }
	echo "</font>";
	echo "&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$code_url/userprefs.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Preferences</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$code_url/stats/members/mdetail.php?id=$u_id'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Statistics</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$code_url/tools/logout.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Log Out</font></a>&nbsp;</font>\n";
	echo "</td>\n";
	}

	echo "</tr>\n";
	echo "</table>\n";
}

function html_statsbar($code_url, $theme, $nameofpage, $theme_name, $userP) {
    global $dynstats_dir, $dynstats_url;

	if (isset($GLOBALS['pguser'])) {

	//Get the personal statistics array
	$result = mysql_query("SELECT username, pagescompleted FROM users WHERE pagescompleted > 0 ORDER BY pagescompleted DESC");
	$i = 0;
	$row = mysql_fetch_assoc($result);
	if ($row['username'] == $GLOBALS['pguser']) {
    		$userindex = 0;
	}
	$neighborhood['rank'][0] = 1;
	$neighborhood['username'][0] = $row['username'];
	$neighborhood['pages'][0] = $row['pagescompleted'];
	$lastcompleted = $row['pagescompleted'];
	$i++;
	while ($row = mysql_fetch_assoc($result)) {
    		if ($row['username'] == $GLOBALS['pguser']) {
        		$userindex = $i;
    		}
    		if ($row['pagescompleted'] == $lastcompleted) {
        		$neighborhood['rank'][$i] = $neighborhood['rank'][$i - 1];
    		} else {
        		$neighborhood['rank'][$i] = $i+1;
    		}
    		$neighborhood['username'][$i] = $row['username'];
    		$neighborhood['pages'][$i] = $row['pagescompleted'];
    		$lastcompleted = $row['pagescompleted'];
    		$i++;
	}

		if (!isset($userindex)) {
			$userindex = $i;
			$neighborhood['username'][$userindex] = $GLOBALS['pguser'];
			$neighborhood['pages'][$userindex] = 0;
			$neighborhood['rank'][$userindex] = $userindex+1;
			$neighborhood['total_ranks'] = $userindex+1;
		} else {
			$neighborhood['total_ranks'] = $neighborhood['rank'][$i-1];
		}

		//If logged in & either a Post Processor or Project Manager display links
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'><center>&nbsp;";

		if (substr($_SERVER['PHP_SELF'], -13) != "proof_per.php") {
			echo "<a href='$code_url/tools/proofers/proof_per.php'>Proofread Projects</a>";
			$divider = 1;
		}
		if ($userP['manager'] == "yes" && substr($_SERVER['PHP_SELF'], -14) != "projectmgr.php") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$code_url/tools/project_manager/projectmgr.php'>Manage Projects</a>";
			$divider = 1;
		}
		if (($userP['postprocessor'] == "yes" || $neighborhood['pages'][$userindex] >= 400) && substr($_SERVER['PHP_SELF'], -17) != "post_proofers.php") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$code_url/tools/post_proofers/post_proofers.php'>Post Processing</a>";
			$divider = 1;
		}
		if (user_is_a_sitemanager() && substr($_SERVER['PHP_SELF'], -12) != "sitenews.php") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$code_url/tools/site_admin/sitenews.php'>News Update</a>";
			$divider = 1;
		}
		echo "</center></font>\n";
		if (isset($divider)) { echo "<br>\n"; }
	}

    //Show site, personal, team & top ten stats.
    //Provides a link for users to get more stats
    //Shows on all pages
	include("$dynstats_dir/stats.inc");

    //Show the site statistics
      echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";
	echo "<center><u>Site Statistics:</u></center>\n";
	echo "&nbsp;Today: " . number_format($sitestats['daily']) . " pages<br>\n";
	echo "&nbsp;" . date("F: ") . "" . number_format(($sitestats['monthly']+$sitestats['daily'])) . " pages<br>\n";
	$sitestatsgoal = $sitestats['goal'];
	if ($sitestatsgoal=="0") {$sitestatsgoal=="1";}
	echo "&nbsp;Goal: " . number_format($sitestats['goal']) . " pages (" . number_format((($sitestats['monthly']+$sitestats['daily'])/$sitestatsgoal)*100, 2) . "%)<br>\n";
	echo "<br><hr width='75%' align='center'><br>\n";

    //If the user is logged in show personal & team stats
	if (isset($GLOBALS['pguser'])) {
    //Show the personal statistics
	//get rank
	if ($neighborhood['pages'][$userindex] >= 0) {
	 $rankname = "Newbie";
	}
	if ($neighborhood['pages'][$userindex] >= 26) {
	 $rankname = "Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 151) {
	 $rankname = "Ace";
	}
	if ($neighborhood['pages'][$userindex] >= 401) {
	 $rankname = "Proofer Savant";
	}
	if ($neighborhood['pages'][$userindex] >= 1001) {
	 $rankname = "Master Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 2001) {
	 $rankname = "Maestro";
	}
	if ($neighborhood['pages'][$userindex] >= 5001) {
	 $rankname = "Connoisseur";
	}
	if ($neighborhood['pages'][$userindex] >= 10001) {
	 $rankname = "Rare Talent";
	}
	if ($neighborhood['pages'][$userindex] >= 20001) {
	 $rankname = "Super Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 30001) {
	 $rankname = "Profound Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 40001) {
	 $rankname = "Priceless Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 50001) {
	 $rankname = "Prishan Level Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 50101) {
	 $rankname = "Prime Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 60001) {
	 $rankname = "Peerless Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 70001) {
	 $rankname = "Primordial Proofer";
	}
	if ($neighborhood['pages'][$userindex] >= 75001) {
	 $rankname = "Proofer Magnifico";
	}
	if ($neighborhood['pages'][$userindex] >= 80001) {
	 $rankname = "Proofer Supreme";
	}
	if ($neighborhood['pages'][$userindex] >= 90001) {
	 $rankname = "Prooferissimo";
	}
	if ($neighborhood['pages'][$userindex] >= 99001) {
	 $rankname = "Master of Proofing";
	}
	if ($neighborhood['pages'][$userindex] >= 99501) {
	 $rankname = "Doctor of Proofing";
	}
	if ($neighborhood['pages'][$userindex] >= 100001) {
	 $rankname = "Proofer of Proofers";
	}

	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>" . _("Personal Statistics:") . "</u></font></center>\n";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;" . _("Total Pages: ");
	echo number_format($neighborhood['pages'][$userindex])."<br>\n";
	echo "&nbsp;" . _("Current Position: ");
	echo number_format($neighborhood['rank'][$userindex])."/".number_format($neighborhood['total_ranks'])."<br>\n";
	echo "&nbsp;" . _("Current Rank: ");
	echo "$rankname<br></font>\n";
	echo "<" . "!-- " . $userP['user_id'] . " -->";
	echo "<br><hr width='75%' align='center'><br>\n";

	//Show the team statistics
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Your Teams:</u></font></center>\n";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;<a href='$code_url/stats/teams/tdetail.php?tid=1'>Distributed Proofreaders</a></font><br>\n";
	$teamRes=mysql_query("SELECT teamname, id FROM user_teams WHERE id='{$userP['team_1']}' OR id='{$userP['team_2']}' OR id='{$userP['team_3']}'");
		while($row = mysql_fetch_assoc($teamRes)) {
			echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;<a href='$code_url/stats/teams/tdetail.php?tid=".$row['id']."'>".$row['teamname']."</a></font><br>\n";
		}
        echo "&nbsp;<a href='$code_url/stats/teams/tlist.php'><font face='".$theme['font_navbar']."' size='1'>View all teams...</font></a><br>\n";

        //Show the Top Ten users is user has the option set
        if ($userP['u_top10']) {
        	echo "<br><hr width='75%' align='center'><br>\n";
        	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Top Ten Proofers:</u></font></center>\n";
		$i = 0;
		$p = 1;
		while ($p <= 10) {
			if ($neighborhood['username'][$i] == $GLOBALS['pguser']) { echo "<b><i>"; }
			echo "&nbsp;$p.) ".$neighborhood['username'][$i]." - ".number_format($neighborhood['pages'][$i])."<br>\n";
			if ($neighborhood['username'][$i] == $GLOBALS['pguser']) { echo "</b></i>"; }
			$i++;
			$p++;
			if (empty($neighborhood['username'][$i])) { break; }
		}
        }

        //Show the Neighbor table if the user has requested so
        if ($userP['u_neigh']) {
        	echo "<br><hr width='75%' align='center'><br>\n";
        	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Your Neighborhood:</u></font></center>\n";
		$i = $userP['u_neigh'];
		if ($userindex <= 10) {
			$i = $userindex;
		}
		while ($i > 0) {
			echo "&nbsp;".$neighborhood['rank'][$userindex-$i].".) ".$neighborhood['username'][$userindex-$i]." - ".number_format($neighborhood['pages'][$userindex-$i])."<br>\n";
			$i--;

		}
		echo "&nbsp;<b><i>".$neighborhood['rank'][$userindex].".) ".$neighborhood['username'][$userindex]." - ".number_format($neighborhood['pages'][$userindex])."</b></i><br>\n";
		$i = 1;
		if ($neighborhood['pages'][$userindex] != 0) {
		while ($i <= $userP['u_neigh']) {
			if (empty($neighborhood['username'][$userindex+$i])) { break; }
			echo "&nbsp;".$neighborhood['rank'][$userindex+$i].".) ".$neighborhood['username'][$userindex+$i]." - ".number_format($neighborhood['pages'][$userindex+$i])."<br>\n";
			$i++;
		}
		}
	}

   //If it is a guest user show the past years completed projects
        } else {
		echo "<center><u>Completed Projects:</u></center>\n";
		echo "<table border=0 cellspacing=0 cellpadding=0 width='100%'>\n";
		$thismonth = date("n");
		$thisyear = date("Y");
		for ( $months_ago = 11; $months_ago >= 0; $months_ago-- )
		{
			// midnight that begins the 1st day of this month and the next:
			$begindate = mktime(0,0,0,$thismonth-$months_ago,  1,$thisyear);
			$enddate   = mktime(0,0,0,$thismonth-$months_ago+1,1,$thisyear);

			$displaydate = date("M Y", $begindate );

			$result = mysql_query("SELECT COUNT(projectid) FROM projects WHERE modifieddate >= $begindate && modifieddate <= $enddate && state = '".PROJ_SUBMIT_PG_POSTED."'");
			$totalprojects = mysql_result($result,0);
			//echo "&nbsp;$displaydate - $totalprojects<br>\n";
			echo "<tr><td width='60%' align='right'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>&nbsp;$displaydate&nbsp;-</font></td><td width='40%' align='left'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>&nbsp;$totalprojects&nbsp;</font></td></tr>\n";
		}
		echo "</table>\n";
        }
	echo "<br></font>\n";
	echo "<center><a href='$code_url/stats/stats_central.php'><font size='1' face='".$theme['font_navbar']."'>See more statistics here...</font></a></center>\n";

	if (!empty($userP)) {
		echo "<br><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><center><u>Key Help Documents:</u></center></font>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='$code_url/faq/faq_central.php'>Frequently Asked Questions</a></font><br>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='$code_url/faq/document.php'>Proofing Guidelines</a> (<a href='$code_url/faq/summary.pdf'>Summary</a>)</font><br>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='".$GLOBALS['forums_url']."/viewtopic.php?t=2368'>Tips & How-To's</a></font><br>\n";
	}


    //Display the donation icon and link to donate.
    //Should be the last thing displayed in the right hand column
	echo "<br><center>";
	echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>
<input type='hidden' name='cmd' value='_xclick'><input type='hidden' name='business' value='donate@gutenberg.net'>\n";
echo "<input type='image' src='$code_url/pinc/templates/".$theme_name."".$theme['image_donate']."' border='0' name='submit' alt='Donate to Distributed Proofreading'></center>\n";
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'>The Project Gutenberg Literary Archive Foundation (<a href='http://www.gutenberg.net/donation.html' target='_new'>PGLAF</a>) is a 501(c)(3) charitable organization with U.S. Federal Tax ID #64-6221541</font></center></form>\n";
}

function html_footer($code_url, $theme, $theme_name) {
$mtime = explode(" ",microtime());
$endtime = $mtime[1] + $mtime[0];
$totaltime = ($endtime - $GLOBALS['starttime']);

    //Bottom Copyright Text
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_copyright_bg']."'>\n";
	echo "<tr><td width='10'>&nbsp;</td><td width='100%'><center><font color='".$theme['color_copyright_font']."' face='".$theme['font_copyright']."' size='1'>Copyright Distributed Proofreaders (".substr($totaltime, 0, 5).")</font></center></td></tr>\n";
	echo "</table>\n";

    //The End
	echo "</body>\n";
	echo "</html>\n";
}
?>
