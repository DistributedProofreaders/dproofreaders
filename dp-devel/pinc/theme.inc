<?
$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
include_once($relPath.'v_site.inc');
include_once($relPath.'dpsession.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'user_is.inc');

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
      echo "<html>\n<head>\n<link rel='shortcut icon' href='$code_url/favicon.ico'>\n";
	echo "<title>DP";
      if (isset($nameofpage)) { echo " -- $nameofpage"; }
        echo "</title>\n";
	if (isset($nameofpage) && substr($nameofpage,0,13) == 'Personal Page')
	{
		include($relPath.'special_colours.inc');
	}
	echo "\n<META http-equiv=\"Content-Type\" content=\"text/html; charset={$GLOBALS['charset']}\">\n";
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

    // show number of projects posted to PG from DP
        echo "<td width='50%' bgcolor='" . $theme['color_logobar_bg'] .  "' align='center' valign='middle'>\n";

        $result = mysql_query( "SELECT COUNT(*) FROM projects WHERE state like 'proj_submit%' or state like 'proj_correct%'");
        $numproj = mysql_result($result,0);
        if ($maintenance) {
                echo _("MAINTENANCE MODE");
                echo "  ";
        }
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='3'><b>".number_format($numproj)." DP projects posted to PG!</b></font>\n";

     echo "<br>";

    // show number of books posted to PG from DP
    // (takes into account that some books are processed as several projects)

        $result = mysql_query( "SELECT COUNT(distinct postednum) FROM projects WHERE state like 'proj_submit%' or state like 'proj_correct%'");
        $numproj = mysql_result($result,0);
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'><b>".number_format($numproj)." different books posted to PG from DP!</b></font>\n";

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
	echo "<a href='$code_url/faq/".cur_lang_dir()."faq_central.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Help") . "</font></a>&nbsp;</font>\n";
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
	echo "<td align='right' bgcolor='".$theme['color_headerbar_bg']."'>\n";
    	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>";
	if ((time() - $datecreated) < 604800) { echo "Start Here -->&nbsp;"; }
	echo "<a href='$code_url/faq/".cur_lang_dir()."faq_central.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Help") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='".$GLOBALS['forums_url']."/'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Forums") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	if(cur_lang_forum()) {
		echo "<a href='".$GLOBALS['forums_url']."/".cur_lang_forum()."'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>"._("Your language forums")."</font></a>";
		echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	}
	echo "<a href='".$GLOBALS['forums_url']."/privmsg.php?folder=inbox'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>". _("Inbox") ;
	if ($numofPMs[0] > 0) { echo " ($numofPMs[0] unread)"; }
	echo "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='$code_url/userprefs.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Preferences") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='$code_url/stats/members/mdetail.php?id=$u_id'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Statistics") . "</font></a>";
	echo "&nbsp;<b>&#183;</b>&nbsp;\n";
	echo "<a href='$code_url/tools/logout.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>" . _("Log Out") . "</font></a>&nbsp;</font>\n";
	echo "</td>\n";
	}

	echo "</tr>\n";
	echo "</table>\n";
}

function html_statsbar($code_url, $theme, $nameofpage, $theme_name, $userP) {
    global $dynstats_dir, $dynstats_url, $metadata;

        if(!(isset($userP) && $userP['u_intlang'])) {
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
                echo "</select><input type=hidden name=returnto value='".$_SERVER['PHP_SELF']."'>\n</form>\n";
                echo "Languages below the line are planned to be added to the site, but currently are not; visit <a href='$code_url/faq/translate.php'>this page</a> if you can help us with translating the site into one of them.<hr>\n";
        }

	if (isset($GLOBALS['pguser'])) {

	//Get the personal statistics array
	$result = mysql_query("SELECT username, pagescompleted, u_privacy FROM users WHERE pagescompleted > 0 ORDER BY pagescompleted DESC");
	$i = 0;
	$row = mysql_fetch_assoc($result);
	if ($row['username'] == $GLOBALS['pguser']) {
    		$userindex = 0;
	}
	$neighborhood['rank'][0] = 1;
	if ($row['u_privacy'] == 1 && $row['username'] != $GLOBALS['pguser']) {
		$neighborhood['username'][0] = _("Anonymous");
	} else $neighborhood['username'][0] = $row['username'];

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
		if ($row['u_privacy'] == 1 && $row['username'] != $GLOBALS['pguser']) {
			$neighborhood['username'][$i] = _("Anonymous");
		} else $neighborhood['username'][$i] = $row['username'];
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
		echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'>&nbsp;";

		if (substr($_SERVER['PHP_SELF'], -13) != "proof_per.php") {
			echo "<a href='$code_url/tools/proofers/proof_per.php'>" . _("Proofread Projects") . "</a>";
			$divider = 1;
		}
		if (user_is_PM() && substr($_SERVER['PHP_SELF'], -14) != "projectmgr.php") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$code_url/tools/project_manager/projectmgr.php'>" . _("Manage Projects") . "</a>";
			$divider = 1;
		}
		if (user_is_PP() && substr($_SERVER['PHP_SELF'], -17) != "post_proofers.php") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$code_url/tools/post_proofers/post_proofers.php'>" . _("Post Processing") . "</a>";
			$divider = 1;
		}
		if (user_is_a_sitemanager() && substr($_SERVER['PHP_SELF'], -12) != "sitenews.php") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$code_url/tools/site_admin/sitenews.php'>" . _("News Update") . "</a>";
			$divider = 1;
		}
if ($metadata){

		if (substr($_SERVER['PHP_SELF'], -16) != "md_available.php") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$code_url/tools/proofers/md_available.php'>" . _("Metadata Collection") . "</a>";
			$divider = 1;
		}
		if (user_is_a_sitemanager() && substr($_SERVER['PHP_SELF'], -18) != "proj_approvals.php") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$code_url/tools/site_admin/proj_approvals.php'>" . _("Clearance Approvals") . "</a>";
			$divider = 1;
		}
}
		echo "</font></center>\n";
		if (isset($divider)) { echo "<br>\n"; }
	}

    //Show site, personal, team & top ten stats.
    //Provides a link for users to get more stats
    //Shows on all pages
	include("$dynstats_dir/stats.inc");

    //Show the site statistics
       echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";
        echo "<u>" . _("Site Statistics:") . "</u></font></center>\n";
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";


        // Today's Numbers
	$result = mysql_query("SELECT dailygoal FROM pagestats WHERE date = '".date("Y-m-d", time())."'");
	if (mysql_num_rows($result) > 0) { $todaysGoal = mysql_result($result, 0, "dailygoal"); } else { $todaysGoal = 0; }
	echo "&nbsp;"._("Today's Goal:")." ".number_format($todaysGoal)."<br>\n";
	echo "&nbsp;"._("Today's Total:")." ".number_format($sitestats['daily'])." (" . strftime("%R") . ")<br><br>\n";

        // Yesterday's Numbers
	$result = mysql_query("SELECT pages, dailygoal FROM pagestats WHERE date = '".date("Y-m-d", (time()-86400))."'");
	if (mysql_num_rows($result) > 0) {
		$yesterdaysGoal = mysql_result($result, 0, "dailygoal");
		$yesterdaysTotal = mysql_result($result, 0, "pages");
	} else {
		$yesterdaysGoal = 0;
		$yesterdaysTotal = 0;
	}
	echo "&nbsp;"._("Yesterday's Goal:").number_format($yesterdaysGoal)."<br>\n";
	echo "&nbsp;"._("Yesterday's Total:")." ".number_format($yesterdaysTotal)."<br><br>\n";

	// Month's Numbers
	$sitestatsgoal = $sitestats['goal'];
	if ($sitestatsgoal=="0") {$sitestatsgoal=="1";}

	echo "&nbsp;".strftime(_("%B's Goal:")).number_format($sitestats['goal'])." (" . number_format((($sitestats['monthly']+$sitestats['daily'])/$sitestatsgoal)*100, 2) . "%)<br>\n";
	echo "&nbsp;".strftime(_("%B's Total:")).number_format(($sitestats['monthly']+$sitestats['daily']))."<br>\n";

       echo "</font><br><hr width='75%' align='center'><br>\n";


    //If the user is logged in show personal & team stats
	if (isset($GLOBALS['pguser'])) {
    //Show the personal statistics
	//get rank
	if ($neighborhood['pages'][$userindex] >= 0) {
	 $rankname = _("Newbie");
	}
	if ($neighborhood['pages'][$userindex] >= 26) {
	 $rankname = _("Proofreader");
	}
	if ($neighborhood['pages'][$userindex] >= 151) {
	 $rankname = _("Ace");
	}
	if ($neighborhood['pages'][$userindex] >= 401) {
	 $rankname = _("Proofreader Savant");
	}
	if ($neighborhood['pages'][$userindex] >= 1001) {
	 $rankname = _("Master Proofreader");
	}
	if ($neighborhood['pages'][$userindex] >= 2001) {
	 $rankname = _("Maestro");
	}
	if ($neighborhood['pages'][$userindex] >= 5001) {
	 $rankname = _("Connoisseur");
	}
	if ($neighborhood['pages'][$userindex] >= 10001) {
	 $rankname = _("Rare Talent");
	}
	if ($neighborhood['pages'][$userindex] >= 20001) {
	 $rankname = _("Super Proofreader");
	}
	if ($neighborhood['pages'][$userindex] >= 30001) {
	 $rankname = _("Profound Proofreader");
	}
	if ($neighborhood['pages'][$userindex] >= 40001) {
	 $rankname = _("Priceless Proofreader");
	}
	if ($neighborhood['pages'][$userindex] >= 50001) {
	 $rankname = _("Prishan Level Proofreader");
	}
	if ($neighborhood['pages'][$userindex] >= 50101) {
	 $rankname = _("Prime Proofreader");
	}
	if ($neighborhood['pages'][$userindex] >= 60001) {
	 $rankname = _("Peerless Proofer");
	}
	if ($neighborhood['pages'][$userindex] >= 70001) {
	 $rankname = _("Primordial Proofer");
	}
	if ($neighborhood['pages'][$userindex] >= 75001) {
	 $rankname = _("Proofer Magnifico");
	}
	if ($neighborhood['pages'][$userindex] >= 80001) {
	 $rankname = _("Proofer Supreme");
	}
	if ($neighborhood['pages'][$userindex] >= 90001) {
	 $rankname = _("Prooferissimo");
	}
	if ($neighborhood['pages'][$userindex] >= 99001) {
	 $rankname = _("Master of Proofreading");
	}
	if ($neighborhood['pages'][$userindex] >= 99501) {
	 $rankname = _("Doctor of Proofreading");
	}
	if ($neighborhood['pages'][$userindex] >= 100001) {
	 $rankname = _("Prishan of Proofers");
	}
	if ($neighborhood['pages'][$userindex] >= 101001) {
	 $rankname = _("Proofer of Proofers");
	}
	if ($neighborhood['pages'][$userindex] >= 110001) {
	 $rankname = _("Proofer Extraordinaire");
	}
	if ($neighborhood['pages'][$userindex] >= 120001) {
	 $rankname = _("Presidential Proofer");
	}
	if ($neighborhood['pages'][$userindex] >= 125001) {
	 $rankname = _("Professor of Proofreading");
	}


	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>" . _("Personal Statistics:") . "</u></font></center>\n";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;" . _("Total Pages: ");
	echo number_format($neighborhood['pages'][$userindex])."<br>\n";
	echo "&nbsp;" . _("Current Position: ");
	echo number_format($neighborhood['rank'][$userindex])."/".number_format($neighborhood['total_ranks'])."<br>\n";
	echo "&nbsp;" . _("Current Rank: ");
	echo "$rankname<br></font>\n";
	echo "<" . "!-- " . $userP['u_id'] . " -->";
	echo "<br><hr width='75%' align='center'><br>\n";

	//Show the team statistics
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>" . _("Your Teams:") . "</u></font></center>\n";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;<a href='$code_url/stats/teams/tdetail.php?tid=1'>"._("Distributed Proofreaders")."</a></font><br>\n";
	$teamRes=mysql_query("SELECT teamname, id FROM user_teams WHERE id='{$userP['team_1']}' OR id='{$userP['team_2']}' OR id='{$userP['team_3']}'");
		while($row = mysql_fetch_assoc($teamRes)) {
			echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;<a href='$code_url/stats/teams/tdetail.php?tid=".$row['id']."'>".$row['teamname']."</a></font><br>\n";
		}
        echo "&nbsp;<a href='$code_url/stats/teams/tlist.php'><font face='".$theme['font_navbar']."' size='1'>"._("View all teams...")."</font></a><br>\n";

        // show link to replacement page for top ten
        if ($userP['u_top10']) {
             echo "<br><hr width='75%' align='center'><br>\n";
             echo "<center><a href='$code_url/stats/proof_stats.php'>Proofreading Statistics</a></center>\n";
        }

        //Show the Neighbor table if the user has requested so
        if ($userP['u_neigh']) {
        	echo "<br><hr width='75%' align='center'><br>\n";
        	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Your Neighborhood:</u></font></center>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>\n";
		$i = $userP['u_neigh'];
		if ($userindex <= 10) {
			$i = $userindex;
		}
		while ($i > 0) {
			echo "&nbsp;".$neighborhood['rank'][$userindex-$i].".) ".$neighborhood['username'][$userindex-$i]." - ".number_format($neighborhood['pages'][$userindex-$i])."<br>\n";
			$i--;

		}
		echo "&nbsp;<b><i>".$neighborhood['rank'][$userindex].".) ".$neighborhood['username'][$userindex]." - ".number_format($neighborhood['pages'][$userindex])."</i></b><br>\n";
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
	echo "<br></font>\n";
	echo "<center><a href='$code_url/stats/stats_central.php'><font size='1' face='".$theme['font_navbar']."'>" . _("See more statistics here...") . "</font></a></center>\n";

	if (!empty($userP)) {
		echo "<br><center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>"._("Key Help Documents:")."</u></font></center>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='$code_url/faq/".cur_lang_dir()."faq_central.php'>"._("Frequently Asked Questions")."</a></font><br>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='$code_url/faq/".cur_lang_dir()."document.php'>"._("Proofreading Guidelines")."</a> (<a href='$code_url/faq/".cur_lang_dir()."summary.pdf'>"._("Summary")."</a>)</font><br>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='".$GLOBALS['forums_url']."/viewtopic.php?t=2368'>"._("Tips and How-To's")."</a></font><br>\n";
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><a href='$code_url/tasks.php'>"._("Support Request")."</a></font><br>\n";
	}


    //Display the donation icon and link to donate.
    //Should be the last thing displayed in the right hand column
	echo "<br><center>";
	echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>
<input type='hidden' name='cmd' value='_xclick'><input type='hidden' name='business' value='donate@gutenberg.net'>\n";
echo "<input type='image' src='$code_url/pinc/templates/".$theme_name."".$theme['image_donate']."' border='0' name='submit' alt='Donate to Distributed Proofreading'></form></center>\n";
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'>"._("The Project Gutenberg Literary Archive Foundation")." (<a href='http://gutenberg.net/donate.shtml' target='_new'>PGLAF</a>) "._("is a 501(c)(3) charitable organization with U.S. Federal Tax ID #64-6221541")."</font></center>\n";
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
