<?
$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];

include_once($relPath.'v_site.inc');
include_once($relPath.'udb_user.php');

if(isset($userP['i_theme'])) { 
		include_once($relPath.'templates/'.$userP['i_theme'].'/theme.tpl');		
		$theme_name = $userP['i_theme'];
		$theme = ${$theme_name};		
	} else { 
		include_once($relPath.'templates/project_gutenberg/theme.tpl'); 
		$theme_name = "project_gutenberg";
		$theme = ${$theme_name};
	}

function theme ($nameofpage, $location) {
				
	if (isset($GLOBALS['pguser'])) { 
		$userP = $GLOBALS['userP']; 
		$statsbar_align = $userP['u_align'];
		} else {
		$statsbar_align = 0;
		$userP = "";
	}
	
	$siteurl = $GLOBALS['siteurl'];
	$theme_name = $GLOBALS['theme_name'];
	$theme = $GLOBALS['theme'];
						
	if ($statsbar_align == 1) { 
		if ($location == "header") {
			html_header($siteurl, $theme, $nameofpage);
			html_logobar($siteurl, $theme, $nameofpage, $theme_name);
			echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
			echo "<tr>";
			echo "<td align='left' valign='top' bgcolor='".$theme['color_mainbody_bg']."'>";
			echo "</form>";
			echo "<table align='left' border='0' cellpadding='0' cellspacing='0' width='26%'>";
			echo "<tr>";
			echo "<td align='left' valign='top' bgcolor='".$theme['color_navbar_bg']."'>";
			html_statsbar($siteurl, $theme, $nameofpage, $theme_name, $userP);
			echo "</td>";
			echo "<td align='left' valign='top' bgcolor='".$theme['color_mainbody_bg']."'>";
			echo "<img border='0' src='$siteurl/pinc/templates/".$theme_name."".$theme['image_curve_left']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>";
			echo "</tr></table>";		
		} elseif ($location == "footer") {
			echo "</td></tr></table>";
			html_footer($siteurl, $theme, $theme_name);
		}	
	} else {
		if ($location == "header") {
			html_header($siteurl, $theme, $nameofpage);
			html_logobar($siteurl, $theme, $nameofpage, $theme_name);
			echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
			echo "<tr>";
			echo "<td align='left' valign='top' width='5' bgcolor='".$theme['color_mainbody_bg']."'>&nbsp;</td>";
			echo "<td align='left' bgcolor='".$theme['color_mainbody_bg']."'>";
			echo "</form>";
			echo "<table align='right' border='0' cellpadding='0' cellspacing='0' width='26%'>";
			echo "<tr>";
			echo "<td align='right' valign='top' bgcolor='".$theme['color_mainbody_bg']."'>";
			echo "<img border='0' src='$siteurl/pinc/templates/".$theme_name."".$theme['image_curve_right']."' width='13' height='12' alt='Provides a round curve for a navigation bar'></td>";
			echo "<td align='left' valign='top' bgcolor='".$theme['color_navbar_bg']."'>";
			html_statsbar($siteurl, $theme, $nameofpage, $theme_name, $userP);
			echo "</td></tr></table>";
		} elseif ($location == "footer") {
			echo "</td></tr></table>";
			html_footer($siteurl, $theme, $theme_name);
		}
	}
}

function html_header($siteurl, $theme, $nameofpage) {
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">";
        echo "<html><head><link rel='shortcut icon' href='/dp/favicon.ico'><title>Distributed Proofreaders";
        if (isset($nameofpage)) { echo " -- $nameofpage"; }
        echo "</title><META http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\"></head>";
	echo "<body bgcolor='".$theme['body_bgcolor']."' text='".$theme['body_text']."' link='".$theme['body_link']."' vlink='".$theme['body_vlink']."' alink='".$theme['body_alink']."' topmargin=0 leftmargin=0>";
}

function html_logobar($siteurl, $theme, $nameofpage, $theme_name) {
	echo "<table width='100%' cellSpacing=0 cellPadding=0 border=0>";	
	echo "<tr>";
	echo "<td width='50%' bgcolor='".$theme['color_logobar_bg']."'>";
	echo "<img src='$siteurl/pinc/templates/".$theme_name."".$theme['image_logo']."' width='360' height='68' alt='Distributed Proofreaders Logo'></td>";
	echo "<td width='50%' bgcolor='".$theme['color_logobar_bg']."'>&nbsp;</td>";
	echo "</tr></table>";
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_headerbar_bg']."'>";
	echo "<tr>";
	echo "<td width='10' align='left' bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td>";
	echo "<td width='50%' align='left' valign='middle' bgcolor='".$theme['color_headerbar_bg']."'>";

    //Create the name of the page
    	if (isset($GLOBALS['pguser'])) { echo "<a href='$siteurl/tools/proofers/proof_per.php'>"; } else { echo "<a href='$siteurl/default.php'>"; }
	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Distributed Proofreading</font></a>";
        if (isset($nameofpage)) { echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'> » $nameofpage</font>"; }
	echo "</td><td width='50%' align='right' bgcolor='".$theme['color_headerbar_bg']."'>";
	
    //Code to display login form if not logged in
	if (!isset($GLOBALS['pguser'])) {
	echo "<form action='$siteurl/accounts/login.php' method='post'>";
	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>ID:<input type='text' name='userNM' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;Password:<input type='password' name='userPW' size='10' maxlength='50' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;<input type='submit' value='Sign In' style='font-family: ".$theme['font_headerbar']."; font-size: 11px;'>&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$siteurl/accounts/addproofer.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Register</font></a>&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$siteurl/faq/faq_central.html'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Help</font></a>&nbsp;</font></td>";
	} else {
    //Code to display if the user is logged in
    	$result = mysql_query("SELECT user_id FROM phpbb_users WHERE username='".$GLOBALS['pguser']."' LIMIT 1");
    	$pguser_id = mysql_result($result, 0, "user_id");
    	$result = mysql_query("SELECT COUNT(*) FROM phpbb_privmsgs WHERE privmsgs_to_userid = $pguser_id && privmsgs_type = 1 || privmsgs_to_userid = $pguser_id && privmsgs_type = 5");
    	$numofPMs = mysql_fetch_row($result);
    	echo "<font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>";
	echo "<a href='$siteurl/faq/faq_central.html'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Help</font></a>&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$siteurl/phpBB2/'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Forums</font></a>&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$siteurl/phpBB2/privmsg.php?folder=inbox'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Inbox</a>";
	if ($numofPMs[0] > 0) { echo " ($numofPMs[0] new)"; }
	echo "</font>&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$siteurl/userprefs.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Preferences</font></a>&nbsp;<b>&#183;</b>&nbsp;";
	echo "<a href='$siteurl/tools/logout.php'><font face='".$theme['font_headerbar']."' size='1' color='".$theme['color_headerbar_font']."'>Log Out</font></a>&nbsp;</font></td>";
	}

	echo "</tr></table>";
}
	
function html_statsbar($siteurl, $theme, $nameofpage, $theme_name, $userP) {
    //If logged in & either a Post Processor or Project Manager display links
	if (isset($GLOBALS['pguser'])) {
		echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'><center>";
		if ($userP['manager'] == "yes") {
			echo "<a href='$siteurl/tools/project_manager/projectmgr.php'>Manage Projects</a>";
			$divider = 1;
		}
		if ($userP['postprocessor'] == "yes") {
			if (isset($divider)) { 
				echo " | ";
			}
			echo "<a href='$siteurl/tools/post_proofers/post_proofers.php'>Post Processing</a>";
			$divider = 1;
		}
		if ($userP['sitemanager'] == "yes") {
			if (isset($divider)) {
				echo " | ";
			}
			echo "<a href='$siteurl/tools/site_admin/sitenews.php'>News Update</a>";
			$divider = 1;
		}
		echo "</center></font>";
		if (isset($divider)) { echo "<br>"; }
	}
	
    //Show site, personal, team & top ten stats.
    //Provides a link for users to get more stats
    //Shows on all pages
	include("$siteurl/stats/stats.inc");
	
    //Show the site statistics
        echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><center><u>Site Statistics:</u></center>";
	echo "&nbsp;Today: ".$sitestats['daily']." pages<br>";
	echo "&nbsp;".date("F: ")."".$sitestats['monthly']." pages<br>";
	echo "&nbsp;Goal: ".$sitestats['goal']." pages<br>";
	echo "<br><hr width='75%' align='center'><br>";

    //If the user is logged in show personal & team stats
	if (isset($GLOBALS['pguser'])) {
    //Show the personal statistics
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

	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Personal Statistics:</u></font></center>";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;Total Pages: ";
	echo $neighborhood['pages'][$userindex]."<br>";
	echo "&nbsp;Current Position: ";
	echo $neighborhood['rank'][$userindex]."/".$neighborhood['rank'][$i-1]."<br>";
	echo "&nbsp;Current Rank: ";
	echo "$rankname<br></font>";
	echo "<br><hr width='75%' align='center'><br>";
	
	//Show the team statistics
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Your Teams:</u></font></center>";
	echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;<a href='$siteurl/userteams.php?tid=1'>Distributed Proofreaders</a></font><br>";
	$teamRes=mysql_query("SELECT teamname, id FROM user_teams WHERE id='{$userP['team_1']}' OR id='{$userP['team_2']}' OR id='{$userP['team_3']}'");
		while($row = mysql_fetch_assoc($teamRes)) {
			echo "<font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'>&nbsp;<a href='$siteurl/userteams.php?tid=".$row['id']."'>".$row['teamname']."</a></font><br>";
			}
        echo "&nbsp;<a href='$siteurl/userteams.php'><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'>View all teams...</font></a><br>";
        
        //Show the Top Ten users is user has the option set
        if ($userP['u_top10']) {
        	echo "<br><hr width='75%' align='center'><br>";
        	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Top Ten Proofers:</u></font></center>";
		$i = 1;
		while ($i <= 10) {
			if ($neighborhood['username'][$i] == $GLOBALS['pguser']) { echo "<b><i>"; }
			echo "&nbsp;$i.) ".$neighborhood['username'][$i]." - ".number_format($neighborhood['pages'][$i])."<br>";
			if ($neighborhood['username'][$i] == $GLOBALS['pguser']) { echo "</b></i>"; }
			$i++;
		}
        }
        
        //Show the Neighbor table if the user has requested so
        if ($userP['u_neigh']) {
        	echo "<br><hr width='75%' align='center'><br>";
        	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='2'><u>Your Neighborhood:</u></font></center>";
		$i = $userP['u_neigh'];
		if ($userindex <= 10) {
			$i = $userindex;
		}
		while ($i > 0) {
			echo "&nbsp;".$neighborhood['rank'][$userindex-$i].".) ".$neighborhood['username'][$userindex-$i]." - ".number_format($neighborhood['pages'][$userindex-$i])."<br>";
			$i--;
		
		}
		echo "&nbsp;<b><i>".$neighborhood['rank'][$userindex].".) ".$neighborhood['username'][$userindex]." - ".number_format($neighborhood['pages'][$userindex])."</b></i><br>";
		$i = 1;
		while ($i <= $userP['u_neigh']) {
			echo "&nbsp;".$neighborhood['rank'][$userindex+$i].".) ".$neighborhood['username'][$userindex+$i]." - ".number_format($neighborhood['pages'][$userindex+$i])."<br>";
			$i++;		
		}
	}
	
   //If it is a guest user show the past years completed projects	
        } else {
        echo "<center><u>Completed Projects:</u></center>";
        $thisyear = date("Y", mktime(0,0,0,date("m"),date("d"),date("Y")));
        $lastyear = date("Y", mktime(0,0,0,date("m"),date("d"),date("Y")-1));
	$i = 1;
		while ($i <= 12) {
			if ($i <= date("n")) { 
				$displaydate = date(" F $thisyear - ", mktime(0,0,0,$i,1,$thisyear));
				$begindate = mktime(0,0,0,$i,1,$thisyear);
				$enddate = mktime(0,0,0,$i+1,0,$thisyear); 
				} else {
				$displaydate = date(" F $lastyear - ", mktime(0,0,0,$i,1,$lastyear));
				$begindate = mktime(0,0,0,$i,1,$lastyear);
				$enddate = mktime(0,0,0,$i+1,0,$lastyear); 
			}
			$result = mysql_query("SELECT COUNT(projectid) FROM projects WHERE modifieddate >= $begindate && modifieddate <= $enddate && state = 'proj_submit_pgposted'");
			$totalprojects = mysql_result($result,0);
			echo $displaydate."".$totalprojects."<br>";
			$i++;
		}
        }	
	echo "<br></font><center><a href='$siteurl/previous_stats.html'><font color='".$theme['color_navbar_font']."' size='1' face='".$theme['font_navbar']."'>See more statistics here...</font></a></center>";
    
    //Display the donation icon and link to donate.
    //Should be the last thing displayed in the right hand column
	echo "<br><br><center><input type='hidden' name='cmd' value='_xclick'><input type='hidden' name='business' value='donate@gutenberg.net'>";
	echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post'><input type='image' src='$siteurl/pinc/templates/".$theme_name."".$theme['image_donate']."' border='0' name='submit' alt='Donate to Distributed Proofreading'></center>";
	echo "<center><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'>The Project Gutenberg Literary Archive Foundation (</font><a href='http://www.gutenberg.net/donation.html' target='_new'><font color='".$theme['color_navbar_font']."' face='".$theme['font_navbar']."' size='1'>PGLAF</a>) is a 501(c)(3) charitable organization with U.S. Federal Tax ID #64-622154</font></center></form>";  
}

function html_footer($siteurl, $theme, $theme_name) {
$mtime = explode(" ",microtime());
$endtime = $mtime[1] + $mtime[0];
$totaltime = ($endtime - $GLOBALS['starttime']);

    //Bottom Copyright Text
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='".$theme['color_copyright_bg']."'>";
	echo "<tr><td width='10'>&nbsp;</td><td width='100%'><center><font color='".$theme['color_copyright_font']."' face='".$theme['font_copyright']."' size='1'>Copyright Distributed Proofreaders (".substr($totaltime, 0, 5).")</font></center></td></tr>";
	echo "</table>";

    //The End
	echo "</body>";
	echo "</html>";
}
?>
