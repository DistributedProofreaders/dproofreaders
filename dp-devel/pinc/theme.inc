<?
$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'pg.inc');
include_once($relPath.'dpsession.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.php');

$user_is_logged_in = dpsession_resume();

include_once($relPath.'gettext_setup.inc');
include_once($relPath.'languages.inc');

if(isset($userP['i_theme']) && $userP['i_theme'] != '' ) {
		$theme_name = $userP['i_theme'];
	} else {
		$theme_name = "project_gutenberg";
}
include_once($relPath."templates/$theme_name/theme.tpl");
$theme = ${$theme_name};

function theme ($nameofpage, $location) {

	if (isset($GLOBALS['pguser'])) {
		$userP = $GLOBALS['userP'];
		$statsbar_align = $userP['u_align'];
		} else {
		$statsbar_align = 0;
		$userP = "";
	}

	//if (isset($userP['last_login']) && $userP['last_login'] <= (time() - 1440)) {
	//	$result = mysql_query("UPDATE users SET last_login = ".time()." WHERE u_id = ".$userP['u_id']."");
	//}

	$code_url = $GLOBALS['code_url'];
	$theme_name = $GLOBALS['theme_name'];
	$theme = $GLOBALS['theme'];

	if (!isset($GLOBALS['no_stats'])) {
		if ($statsbar_align == 1) {
			if ($location == "header") {
				html_header($code_url, $theme, $nameofpage);
				html_logobar($code_url, $theme, $nameofpage, $theme_name);
				echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
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
				echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
				echo "<tr>\n";
				echo "<td align='left' valign='top' width='5' bgcolor='".$theme['color_mainbody_bg']."'>&nbsp;</td>\n";
				echo "<td align='left' valign='top' width='75%' bgcolor='".$theme['color_mainbody_bg']."'>\n";
			} elseif ($location == "footer") {
				echo "</td>\n";
				echo "<td align='right' valign='top' bgcolor='" . $theme['color_mainbody_bg'] . "'><img border='0' src='$code_url/pinc/templates/".$theme_name."".$theme['image_curve_right']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>\n";
				echo "<td align='left' valign='top' width='25%' bgcolor='" . $theme['color_navbar_bg'] . "'>\n";
				html_statsbar($code_url, $theme, $nameofpage, $theme_name, $userP);
				echo "</td></tr></table>\n";
				html_footer($code_url, $theme, $theme_name);
			}
		}
	} else {
		if ($location == "header") {
			html_header($code_url, $theme, $nameofpage);
			html_logobar($code_url, $theme, $nameofpage, $theme_name);
			echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
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
	global $relPath;
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
	echo "<html".lang_html_header().">\n<head>\n<link rel='shortcut icon' href='$code_url/favicon.ico'>\n";
	echo "<title>DP";
      if (isset($nameofpage)) { echo " -- $nameofpage"; }
        echo "</title>\n";
	echo "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset={$GLOBALS['charset']}\">\n";
	echo "\n<link type='text/css' rel='Stylesheet' href='";
	echo $relPath . 'templates/' . $theme['template_unix_name'] . "/main.css'>";
	echo "</head>\n\n";
	echo "<body bgcolor='" . $theme['body_bgcolor'] . "' text='" . $theme['body_text']; 
	echo "' link='" . $theme['body_link'] . "' vlink='" . $theme['body_vlink']; 
	echo "' alink='" . $theme['body_alink']; 
	echo "' topmargin='0' leftmargin='0' marginheight='0' marginwidth='0'>\n\n";
}

function html_logobar($code_url, $theme, $nameofpage, $theme_name) {
	global $dynstats_dir, $maintenance;
	echo "\n<table width='100%' cellSpacing=0 cellPadding=0 border=0>\n";
	echo "<tr>\n";
	echo "<td width='50%' bgcolor='".$theme['color_logobar_bg']."'>\n";
	echo "<a href='$code_url'><img src='$code_url/pinc/templates/".$theme_name."".$theme['image_logo']."' width='360' height='68' alt='Distributed Proofreaders' border='0'></a>\n";
	echo "</td>\n";

        echo "<td width='50%' bgcolor='" . $theme['color_logobar_bg'] .  "' align='center' valign='middle'>\n";

	if ($maintenance) {
		echo _("MAINTENANCE MODE");
		echo "<br>\n";
	}

	if (0) {
		// show number of projects posted to PG from DP
		$result = mysql_query( "
			SELECT COUNT(*)
			FROM projects
			WHERE state like 'proj_submit%' or state like 'proj_correct%'
		");
		$numproj = mysql_result($result,0);
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='3'><b>";
		echo sprintf(
			_('%d projects completed!'),
			number_format($numproj));
		echo "</b></font>\n";
		echo "<br>";
	}

	if (1) {
		// show number of books posted to PG from DP
		// (takes into account that some books are processed as several projects)

		$result = mysql_query( "
			SELECT COUNT(distinct postednum)
			FROM projects
			WHERE state like 'proj_submit%' or state like 'proj_correct%'
		");
		$numproj = mysql_result($result,0);
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='3'><b>";
		echo sprintf(
			_('%d titles preserved for the world!'),
			number_format($numproj));
		echo "</b></font>\n";
	}

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_headerbar_bg']."'>\n";
	echo "<tr>\n";
	echo "<td width='10' align='left' bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td>\n";
	echo "<td align='left' valign='middle' bgcolor='".$theme['color_headerbar_bg']."'>\n";

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
	echo "<td align='right' bgcolor='".$theme['color_headerbar_bg']."'>\n";
	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>";
	echo _("ID:") . "<input type='text' name='userNM' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;";
	echo _("Password:") . "<input type='password' name='userPW' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;";
	echo "<input type='submit' value='" . _("Sign In") ."' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='$code_url/accounts/addproofer.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Register") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='$code_url/faq/".lang_dir()."faq_central.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Help") . "</font></a>&nbsp;</font>\n";
	echo "</td>\n";
	echo "</form>\n";
	} else {
    //Code to display if the user is logged in
    	$result = mysql_query("SELECT user_id FROM phpbb_users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
    	$pguser_id = mysql_result($result, 0, "user_id");
    	$result = mysql_query("SELECT COUNT(*) FROM phpbb_privmsgs WHERE privmsgs_to_userid = $pguser_id && privmsgs_type = 1 || privmsgs_to_userid = $pguser_id && privmsgs_type = 5");
    	$numofPMs = mysql_fetch_row($result);
    	$result = mysql_query("SELECT date_created FROM users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
    	$datecreated = mysql_result($result, 0, "date_created");
	echo "<td align='right' bgcolor='".$theme['color_headerbar_bg']."'>\n";
    	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>";
	if ((time() - $datecreated) < 604800) { echo "Start Here -->&nbsp;"; }
	echo "<a href='$code_url/faq/".lang_dir()."faq_central.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Help") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='$code_url/activity_hub.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Activity Hub") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='".$GLOBALS['forums_url']."/'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Forums") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	if(lang_forum()) {
		echo "<a href='".$GLOBALS['forums_url']."/".lang_forum()."'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>"._("Your language forums")."</font></a>";
		echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	}
	echo "<a href='".$GLOBALS['forums_url']."/privmsg.php?folder=inbox'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>". _("My Inbox") ;
	if ($numofPMs[0] > 0) { echo " ($numofPMs[0] unread)"; }
	echo "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='$code_url/userprefs.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("My Preferences") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='$code_url/tools/logout.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Log Out") . "</font></a>&nbsp;</font>\n";
	echo "</td>\n";
	}

	echo "</tr>\n";
	echo "</table>\n";
}

function html_statsbar($code_url, $theme, $nameofpage, $theme_name, $userP) {
    global $dynstats_dir, $dynstats_url, $site_supports_metadata;
    global $PG_paypal_business_id, $PG_donation_url;

    		if (empty($userP) || (!(empty($userP)) && !($userP['u_intlang']))) {    		
                ?>
                <script language="JavaScript"><!--
                        function submitLang(i) {
                                top.document.langform.submit();
                        }
                // --></script>
                <?

                echo "<form name=langform action='$code_url/tools/setlangcookie.php' method=POST>\n<input type=submit value='"._("Set language:")."'>\n<select name=lang onChange='submitLang(this)'>\n";
		$langarr=installed_langs();
                foreach($langarr as $v)
                        echo "<option value='".$v."'".(($v==$GLOBALS['intlang'])?" selected":"").">".bilingual_name($v)."</option>\n";
                echo "<option value='en_EN' disabled>-------------------</option>\n";
                echo "<option value='en_EN' disabled>(To be translated:)</option>\n";
		$langarr=uninstalled_langs();
                foreach($langarr as $v)
                        echo "<option value='"."en_EN"."' disabled>".bilingual_name($v)."</option>\n";
                echo "</select><input type=hidden name=returnto value='".$_SERVER['REQUEST_URI']."'>\n</form>\n";
                echo "Languages below the line are planned to be added to the site, but currently are not; visit <a href='$code_url/faq/translate.php'>this page</a> if you can help us with translating the site into one of them.<hr>\n";
        }

    //Show site, personal, team & top ten stats.
    //Provides a link for users to get more stats
    //Shows on all pages
	if (file_exists("$dynstats_dir/stats.inc")) { include("$dynstats_dir/stats.inc"); }

    //Show the site statistics
       echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";
        echo "<u>" . _("Site Statistics:") . "</u></font></center>\n";
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";


        // Today's Numbers
	$result = mysql_query("SELECT dailygoal FROM pagestats WHERE date = '".date("Y-m-d", time())."'");
	if (mysql_num_rows($result) > 0) { $todaysGoal = mysql_result($result, 0, "dailygoal"); } else { $todaysGoal = 0; }
	echo "&nbsp;"._("Today's Goal:")." ".number_format($todaysGoal)."<br>\n";
	if (isset($sitestats)) { echo "&nbsp;"._("Today's Total:")." ".number_format($sitestats['daily'])." (" . strftime("%R") . ")<br><br>\n"; }

        // Yesterday's Numbers
	$result = mysql_query("SELECT pages, dailygoal FROM pagestats WHERE date = '".date("Y-m-d", (time()-86400))."'");
	if (mysql_num_rows($result) > 0) {
		$yesterdaysGoal = mysql_result($result, 0, "dailygoal");
		$yesterdaysTotal = mysql_result($result, 0, "pages");
	} else {
		$yesterdaysGoal = 0;
		$yesterdaysTotal = 0;
	}
	echo "&nbsp;"._("Yesterday's Goal:")." ".number_format($yesterdaysGoal)."<br>\n";
	echo "&nbsp;"._("Yesterday's Total:")." ".number_format($yesterdaysTotal)."<br><br>\n";

	// Month's Numbers
	if (isset($sitestats)) { $sitestatsgoal = $sitestats['goal']; } else { $sitestatsgoal = 0; }

	if (isset($sitestats)) { echo "&nbsp;".strftime(_("%B's Goal:"))." ".number_format($sitestatsgoal).($sitestatsgoal?(" (" . number_format((($sitestats['monthly']+$sitestats['daily'])/$sitestatsgoal)*100, 2) . "%)"):"")."<br>\n"; }
	if (isset($sitestats)) { echo "&nbsp;".strftime(_("%B's Total:"))." ".number_format(($sitestats['monthly']+$sitestats['daily']))."<br>\n"; }

	echo "<br></font>\n";
	echo "<center><font size='2'><a href='$code_url/stats/stats_central.php'>"._("More Statistics...")."</a></center>\n";

	echo "</font><br><hr width='75%' align='center'><br>\n";


    //If the user is logged in show personal & team stats
	if (isset($GLOBALS['pguser'])) {
    //Show the personal statistics

	//Get the personal statistics array
	list($neighbors, $max_page_tally_rank) =
		user_get_page_tally_neighborhood(
			'P', $GLOBALS['pguser'], $userP['u_neigh']);
	$usern = $neighbors[0];

	//get rank
	$rankname = user_page_tally_get_interval_title(
		$usern->get_current_page_tally() );

	// Get userid for personal stats link
	$result = mysql_query("SELECT u_id FROM users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
	$u_id = mysql_result($result, 0, "u_id");

	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>" . _("Personal Statistics:") . "</u></font></center>\n";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;" . _("Total Pages: ");
	echo number_format($usern->get_current_page_tally())."<br>\n";
	echo "&nbsp;" . _("Current Position: ");
	echo number_format($usern->get_current_page_tally_rank())."/".number_format($max_page_tally_rank)."<br>\n";
	echo "&nbsp;" . _("Current Rank: ");
	echo "$rankname<br></font>\n";
	echo "<center><font size='2'><a href='$code_url/stats/members/mdetail.php?id=".$u_id."'>" . _("Details...") . "</a></font></center>\n";
	echo "<" . "!-- " . $userP['u_id'] . " -->";
	echo "<br><hr width='75%' align='center'><br>\n";

	//Show the team statistics
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>" . _("Your Teams:") . "</u></font></center>\n";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;<a href='$code_url/stats/teams/tdetail.php?tid=1'>"._("Distributed Proofreaders")."</a></font><br>\n";
	$teamRes=mysql_query("SELECT teamname, id FROM user_teams WHERE id IN ({$userP['team_1']}, {$userP['team_2']}, {$userP['team_3']})");
		while($row = mysql_fetch_assoc($teamRes)) {
			echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;<a href='$code_url/stats/teams/tdetail.php?tid=".$row['id']."'>".$row['teamname']."</a></font><br>\n";
		}
        echo "<center><font size='2'><a href='$code_url/stats/teams/tlist.php'>"._("View all teams...")."</a></font></center>\n";

        //Show the Neighbor table if the user has requested so
        if ($userP['u_neigh']) {
        	echo "<br><hr width='75%' align='center'><br>\n";
        	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Your Neighborhood:</u></font></center>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";

		foreach ( $neighbors as $rel_posn => $neighbor )
		{
			echo "&nbsp;";
			if ($rel_posn==0) echo "<b><i>";
			echo $neighbor->get_current_page_tally_rank(), ".) ";
			echo ( $neighbor->is_anonymized() ? _('Anonymous') : $neighbor->get_username() ), " - ";
			echo number_format($neighbor->get_current_page_tally());
			if ($rel_posn==0) echo "</i></b>";
			echo "<br>\n";
		}
	}

   //If it is a guest user show the past years completed projects
        } else {
		echo "<center><u>" . _("Completed Projects:") . "</u></center>\n";
		echo "<table border=0 cellspacing=0 cellpadding=0 width='100%'>\n";
		$thismonth = date("n");
		$thisyear = date("Y");
		for ( $months_ago = 11; $months_ago >= 0; $months_ago-- )
		{
			// midnight that begins the 1st day of this month and the next:
			$begindate = mktime(0,0,0,$thismonth-$months_ago,  1,$thisyear);
			$enddate   = mktime(0,0,0,$thismonth-$months_ago+1,1,$thisyear);

			$displaydate = strftime(_("%b %Y"), $begindate );

			$result = mysql_query("SELECT COUNT(projectid) FROM projects WHERE modifieddate >= $begindate && modifieddate <= $enddate && state = '".PROJ_SUBMIT_PG_POSTED."'");
			$totalprojects = mysql_result($result,0);
			//echo "&nbsp;$displaydate - $totalprojects<br>\n";
			echo "<tr><td width='60%' align='right'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>&nbsp;$displaydate&nbsp;-</font></td><td width='40%' align='left'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>&nbsp;$totalprojects&nbsp;</font></td></tr>\n";
		}
		echo "</table>\n";
        }

	if (!empty($userP)) {
		echo "<br><hr width='75%' align='center'><br>\n";
		echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>"._("Key Help Documents:")."</u></font></center>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='$code_url/faq/".lang_dir()."faq_central.php'>"._("Frequently Asked Questions")."</a></font><br>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='$code_url/faq/".lang_dir()."document.php'>"._("Proofreading Guidelines")."</a> (<a href='$code_url/faq/".lang_dir()."summary.pdf'>"._("PDF Summary")."</a>)</font><br>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='".$GLOBALS['forums_url']."/viewtopic.php?t=2368'>"._("Tips and How-To's")."</a></font><br>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='$code_url/tasks.php'>"._("Support Request")."</a></font><br>\n";
	}


    //Display the donation icon and link to donate.
    //Should be the last thing displayed in the right hand column
	echo "<br><center>";
	echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>
<input type='hidden' name='cmd' value='_xclick'><input type='hidden' name='business' value='$PG_paypal_business_id'>\n";
echo "<input type='image' src='$code_url/pinc/templates/".$theme_name."".$theme['image_donate']."' border='0' name='submit' alt='Donate to Distributed Proofreading'></form></center>\n";
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'>"._("The Project Gutenberg Literary Archive Foundation")." (<a href='$PG_donation_url' target='_new'>PGLAF</a>) "._("is a 501(c)(3) charitable organization with U.S. Federal Tax ID #64-6221541")."</font></center>\n";
}

function html_footer($code_url, $theme, $theme_name) {
	$mtime = explode(" ",microtime());
	$endtime = $mtime[1] + $mtime[0];
	$totaltime = ($endtime - $GLOBALS['starttime']);

    //Bottom Copyright Text
	echo "\n<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_copyright_bg']."'>\n";
	echo "<tr><td width='10'>&nbsp;</td><td width='100%'><center><font color='".$theme['color_copyright_font']."' face='".$theme['font_copyright']."' size='1'>"._("Copyright")." "._("Distributed Proofreaders")." ("._("Page Build Time").": ".substr($totaltime, 0, 5).") </font><a href='".$GLOBALS['code_url']."/tasks.php'><font color='".$theme['color_copyright_font']."' face='".$theme['font_copyright']."' size='1'>"._("Report a Bug")."</font></a></center></td></tr>\n";
	echo "</table>\n";

    //The End
	echo "\n</body>\n";
	echo "</html>\n";
}
?>
