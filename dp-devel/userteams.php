<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');
include($relPath.'html_main.inc');

if (!isset($tstart))
  {$tstart=0;}
$popHelpDir='faq/pophelp/teams/edit_';

$beginPage="</head>".$htmlC->startBody(0,1,0,1);
$tb=$htmlC->startTable(1,'650',0,1);
$tb2=$htmlC->startTable(1,'250',0,1);
$tr=$htmlC->startTR(0,0,1);
$td1=$htmlC->startTD(2,0,2,0,"center",0,0,1);
$td2=$htmlC->startTD(1,0,0,0,"center",0,0,1);
$td3=$htmlC->startTD(0,0,0,0,"center",0,0,1);
$td4=$htmlC->startTD(0,0,2,0,"left",0,0,1);
$td5=$htmlC->startTD(1,0,0,0,"left",0,0,1);
$td6=$htmlC->startTD(0,0,0,0,"left",0,0,1);
$td6a=$htmlC->startTD(1,0,0,0,"left",0,0,1);
$td7=$htmlC->startTD(2,0,6,0,"center",0,0,1);
$td8=$htmlC->startTD(0,0,6,0,"center",0,0,1);
$td9=$htmlC->startTD(0,0,3,0,"left",0,0,1);
$td10=$htmlC->startTD(0,0,3,0,"right",0,0,1);
$td11=$htmlC->startTD(2,0,0,0,"center",0,0,1);
$td12=$htmlC->startTD(2,0,3,0,"center",0,0,1);
$td12a=$htmlC->startTD(0,0,3,0,"center",0,0,1);
$td13=$htmlC->startTD(1,0,2,0,"center",0,0,1);
$td13a=$htmlC->startTD(0,0,2,0,"center",0,0,1);
$tde=$htmlC->closeTD(1);
$tre=$htmlC->closeTD(1).$htmlC->closeTR(1);
$tbe=$htmlC->closeTable(1);
$closePage=$htmlC->closeBody(1);

$tdM0=$htmlC->startTD(0,126,0,0,"center",0,0,1);
$tdM1=$htmlC->startTD(1,126,0,0,"center",0,0,1);

$menuBar='<br>'.$tb.$tr.$tdM0.'<a href="./tools/proofers/proof_per.php">Personal Page</a>'.
         $tde.$tdM1.' '.$tde.$tdM1.' '.$tde.$tdM1.' '.$tde.$tdM0.'<a href="./phpBB2/index.php">Forums</a>'.
         $tre.$tbe.'<br>';

function stripAllString($ttext)
  {
    return str_replace(array('[b]','[B]','[/b]','[/B]','[i]','[I]','[/i]','[/I]','[p]','[P]','[/p]','[/P]','[lb]','[LB]'), 
      array('<b>','<b>','</b>','</b>','<i>','<i>','</i>','</i>','<p>','<p>','</p>','</p>','<br>','<br>'), 
      htmlentities(strip_tags(stripslashes($ttext))));
  }

function unstripAllString($ttext,$sType)
  {
    $ttext=str_replace(array('<b>','</b>','<i>','</i>','<p>','</p>','<br>'), 
      array('[b]','[/b]','[i]','[/i]','[p]','[/p]','[lb]'), 
      stripslashes($ttext));
    if ($sType==1)
      {
      $htmlchars = array_flip(get_html_translation_table(HTML_ENTITIES));
      $ttext=strtr($ttext, $htmlchars);
      }
  return $ttext;
  }

function showTeamProfile($pguser,$userP,$tstart,$tid,$tname,$ttext,$tavatar,$tmembers,$tpages,$ttime,$tcreator,$ownerName,$tpreview)
  {
    global $tb,$tr,$td1,$td2,$td3,$td4,$td5,$td6,$tde,$tre,$tbe;

    echo $tb.$tr.$td2;
    if (strcmp($tavatar,0)!=0)
      {echo "<img \r\nsrc=\"./users/teams/avatar/$tavatar\" width=\"100\" height=\"100\" alt=\"".strip_tags($tname)."\">";}
    echo $tde.$td3."<b>".stripslashes($tname)."</b>".
      "<p>Created: ".date("l, F jS, Y \a\\t g:i:sA",$ttime);
    echo $tre.$tbe."<br>";
    echo $tb.$tr.$td1;
    echo "<strong>Description</strong>";
    echo $tre.$tr.$td4.stripslashes($ttext);
    echo $tre.$tr.$td5."Created by: <b>$tcreator</b>";
    echo $tde.$td5."Owned by: <b>$ownerName</b>";
    // if owner, let them edit
      if ($ownerName==$pguser && $tpreview==0)
        {echo "&nbsp;&nbsp;&nbsp;&nbsp;<a \r\nhref=\"userteams.php?etid=$tid\">Edit</a>";}
    echo $tre.$tr.$td5."Number of Members: <b>$tmembers</b>";
    echo $tde.$td5."Pages Proofed: <b>$tpages</b>";
  // end display
    if ($tpreview ==0)
      {
        echo $tre.$tr.$td3."<b><a href=\"userteams.php?tstart=$tstart\">View Teams</a></b>";
        echo $tde.$td3;
        if ($tid!=1 && $userP['team_1']!=$tid && $userP['team_2']!=$tid && $userP['team_3']!=$tid)
          {echo "<b><a href=\"userteams.php?jtid=$tid\">Join</a></b>";}
        else if ($tid !=1)
          {echo "<b><a href=\"userteams.php?qtid=$tid\">Quit the Team</a></b>";}
      }
    echo $tre.$tbe;
  }

// see if they just want a team profile
if (isset($tid))
  {
  $htmlC->startHeader("User Teams");
  echo $beginPage.$menuBar;
  $tQuery="SELECT * FROM user_teams WHERE id=$tid";
  $tResult=mysql_query($tQuery);
  $curTeam=mysql_fetch_assoc($tResult);
  // get owner
    $ownerID=$curTeam['owner'];
    $ownerQuery=mysql_query("SELECT username from users WHERE u_id='$ownerID'");
    $ownerName=mysql_result($ownerQuery,0,'username');
  // start display
    showTeamProfile($pguser,$userP,$tstart,$curTeam['id'],$curTeam['teamname'],$curTeam['team_info'],$curTeam['avatar'],
      $curTeam['member_count'],$curTeam['page_count'],$curTeam['created'],$curTeam['createdby'],$ownerName,0);
    echo $menuBar.$closePage;
  exit;
  } // end of show team profile


function joinTeam($userP,$pguser,$otid,$jtid)
  {
  $joinQ='';
  if ($userP['team_1']!=$jtid && $userP['team_2']!=$jtid && $userP['team_3']!=$jtid)
    {
    if ($userP['team_1']==0 || $otid==1)
      {$joinQ="team_1='$jtid'";$qtid=$userP['team_1'];}
    else if ($userP['team_2']==0 || $otid==2)
      {$joinQ="team_2='$jtid'";$qtid=$userP['team_2'];}
    else if ($userP['team_3']==0 || $otid==3)
      {$joinQ="team_3='$jtid'";$qtid=$userP['team_3'];}
    if ($joinQ!='')
      {
      $joinQuery="UPDATE users SET ".$joinQ." WHERE username='$pguser' AND u_id='{$userP['u_id']}'";
      $teamResult=mysql_query($joinQuery);
      if ($teamResult==1)
        {
          $teamQuery="UPDATE user_teams SET member_count=member_count+1 WHERE id='$jtid'";
          mysql_query($teamQuery);
          if ($otid!=0)
          {
            $teamQuery="UPDATE user_teams SET member_count=member_count-1 WHERE id='$qtid'";
            mysql_query($teamQuery);
          }
        }
      }
    }
  else
    {$joinQ='mem';}
  return $joinQ;
  }

// join a team
if (isset($jtid))
  {
  $otid=isset($otid)?$otid:0;
  if ($jtid !=1)
    {
    $joinQ=joinTeam($userP,$pguser,$otid,$jtid);
    if ($joinQ=='')
      {
      // all the spaces are filled, which would the like to overwrite?
        $htmlC->startHeader("User Teams");
        echo $beginPage.$menuBar;
        echo $tb.$tr.$td12."<b>Three Team Maximum</b>";
        echo $tre.$tr.$td12a;
        echo "You have already joined three teams.<br>".
          "Which team would you like to replace?";
        $teamQ1="SELECT teamname FROM user_teams WHERE id='{$userP['team_1']}'";
        $teamQ2="SELECT teamname FROM user_teams WHERE id='{$userP['team_2']}'";
        $teamQ3="SELECT teamname FROM user_teams WHERE id='{$userP['team_3']}'";
        echo $tre.$tr.$td2;
        $teamR=mysql_query($teamQ1);
        echo "<b><a href=\"userteams.php?jtid=$jtid&amp;otid=1\">".mysql_result($teamR,0,'teamname')."</a></b>&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $tde.$td2;
        $teamR=mysql_query($teamQ2);
        echo "<b><a href=\"userteams.php?jtid=$jtid&amp;otid=2\">".mysql_result($teamR,0,'teamname')."</a></b>&nbsp;&nbsp;&nbsp;&nbsp;";
        echo $tde.$td2;
        $teamR=mysql_query($teamQ3);
        echo "<b><a href=\"userteams.php?jtid=$jtid&amp;otid=3\">".mysql_result($teamR,0,'teamname')."</a></b>&nbsp;&nbsp;&nbsp;&nbsp;".
          $tre.$tr.$td12;
        echo "<br><b><a href=\"userteams.php?tid=$jtid\">Do Not Join Team</a></b>";
        echo $tre.$tbe.$menuBar.$closePage;
      }  
    else if ($joinQ=='mem')
      {
      metarefresh(4,"userteams.php?tid=$jtid",'Unable to Join the Team','You are already a member of this team....');
      }
    else
      {
      // update cookie
        $cookieC->setUserPrefs($pguser);
      metarefresh(0,"userteams.php?tid=$jtid",'Join the Team','Joining the team....');
      }
  }
  else {metarefresh(3,"userteams.php?tid=$jtid",'Cannot Join Team','Unable to join team. You are already a member.');}
  exit;
  }

// quit a team
if (isset($qtid))
  {
  if ($qtid !=1 && ($userP['team_1']==$qtid || $userP['team_2']==$qtid || $userP['team_3']==$qtid))
    {
    $quitQuery="UPDATE users SET ";
    if ($userP['team_1']==$qtid)
      {$quitQuery.="team_1='0'";}
    if ($userP['team_2']==$qtid)
      {$quitQuery.="team_2='0'";}
    if ($userP['team_3']==$qtid)
      {$quitQuery.="team_3='0'";}
    $quitQuery.=" WHERE username='$pguser' AND u_id='{$userP['u_id']}'";
    $teamResult=mysql_query($quitQuery);
    if ($teamResult==1)
      {
        $teamQuery="UPDATE user_teams SET member_count=member_count-1 WHERE id='$qtid'";
        mysql_query($teamQuery);
        // update cookie
          $cookieC->setUserPrefs($pguser);
        metarefresh(0,"userteams.php?tid=$qtid",'Quit the Team','Quitting the team....');
      }
    }
  else {metarefresh(3,"userteams.php?tid=$qtid",'Not a member','Unable to quit team....');}
  exit;
  }


function showEdit($userP,$tstart,$tname,$ttext,$tedit,$tsid)
  {
  global $tb,$tr,$td1,$td13,$td13a,$tde,$tre,$tbe;
  echo "<form \r\n".
    "id=\"mkTeam\" name=\"mkTeam\" action=\"userteams.php\" method=\"POST\" target=\"_top\"><input \r\n".
    "type=\"hidden\" name=\"tstart\" value=\"$tstart\"><input \r\n".
    "type=\"hidden\" name=\"tsid\" value=\"$tsid\">".
    $tb.$tr.$td1.
    "New Proofing Team".$tre.$tr.$td13a.
    "<strong>Team Name</strong> <INPUT \r\n".
    "TYPE=\"text\" VALUE=\"$tname\" name=\"teamname\" size=\"50\">\r\n".
    " <b><a href=\"JavaScript:newHelpWin('teamname');\">?</a></b>".$tre.$tr.$td13.
    "<strong>Team Description</strong> <b><a href=\"JavaScript:newHelpWin('teamdesc');\">?</a></b><br><textarea \r\n".
    "name=\"text_data\" COLS=\"40\" ROWS=\"6\">\r\n".
    $ttext."</textarea>".$tre.$tr.$td13a.
    "The Team Icon and Team Logo can be uploaded after the team is created.";
  $team_1=$userP['team_1'];$team_2=$userP['team_2'];$team_3=$userP['team_3'];
  if ($tedit==1 && $team_1!=0 && $team_2!=0 && $team_3!=0)
    {
    echo $tre.$tr.$td13.
      "You must join the team to create it, \r\n".
      "which team space would you like to use?<br><select \r\n".
      "name=\"tteams\" ID=\"tteams\" title=\"Team List\">";
    $teamQuery="SELECT teamname,id FROM user_teams WHERE id='{$userP['team_1']}' OR  id='{$userP['team_2']}' OR  id='{$userP['team_3']}'";
      $teamRes=mysql_query($teamQuery);
      while($row = mysql_fetch_assoc($teamRes))
        {echo "<option value=\"{$row['id']}\">".unstripAllString(strip_tags($row['teamname']),1)."</option>";}
    echo "</select>".$tre;
    }
  else
    {echo "<input \r\n"."type=\"hidden\" name=\"teamall\" value=\"1\">$tre";}
  if($tedit==1)
    {
    echo $tr.$td13a."<input \r\n".
      "type=\"submit\" name=\"mkPreview\" value=\"Preview Team Display\">&nbsp;&nbsp;&nbsp;<input \r\n".
      "type=\"submit\" name=\"mkMake\" value=\"Make Team\">&nbsp;&nbsp;&nbsp;<input \r\n".
      "type=\"submit\" name=\"mkQuit\" value=\"Quit\">".$tre.$tbe."</form>";
    }
  else
    {
    echo $tr.$td13a."<input \r\n".
      "type=\"submit\" name=\"edPreview\" value=\"Preview Changes\">&nbsp;&nbsp;&nbsp;<input \r\n".
      "type=\"submit\" name=\"edMake\" value=\"Save Changes\">&nbsp;&nbsp;&nbsp;<input \r\n".
      "type=\"submit\" name=\"edQuit\" value=\"Quit\">".$tre.$tbe."</form>";
    }
  }

// edit a team
if (isset($etid) || isset($edPreview) || isset($edMake))
  {
  $ntid=isset($tsid)?$tsid:$etid;
  $tQuery="SELECT * FROM user_teams WHERE id=$ntid";
  $tResult=mysql_query($tQuery);
  $curTeam=mysql_fetch_assoc($tResult);
  $ownerID=$curTeam['owner'];
  if ($userP['u_id']!=$ownerID)
    {
    metarefresh(4,"userteams.php?tid=$etid",'Authorization Failed','You are not authorized to edit this team....');
    exit;
    }
  // start display
  if (isset($etid))
    {
    $htmlC->startHeader("User Teams");
    include($relPath.'js_newpophelp.inc');
    echo $beginPage.$menuBar;
    showEdit($userP,$tstart,unstripAllString($curTeam['teamname'],0),unstripAllString($curTeam['team_info'],1),0,$ntid);
    echo $menuBar.$closePage;
    }
  else if (isset($edPreview))
    {
    $htmlC->startHeader("User Teams");
    include($relPath.'js_newpophelp.inc');
    echo $beginPage.$menuBar;
    showEdit($userP,$tstart,htmlentities(stripslashes($teamname)),stripslashes($text_data),0,$ntid);
    echo "</div><br><div align=\"center\">";
    showTeamProfile($pguser,$userP,$tstart,$ntid,stripAllString($teamname),stripAllString($text_data),0,$curTeam['member_count'],$curTeam['page_count'],$curTeam['created'],$curTeam['createdby'],$pguser,1);
    echo $menuBar.$closePage;
    }
  else if (isset($edMake))
    {
    $teamQuery="UPDATE user_teams SET teamname='".addslashes(stripAllString($teamname))."', 
      team_info='".addslashes(stripAllString($text_data))."' WHERE id='$ntid'";
      mysql_query($teamQuery);
      metarefresh(0,"userteams.php?tid=$ntid",'Saving Team Update','Updating team....');
    }
  exit;
  }

// create a team
if (isset($ctid) || isset($mkPreview) || isset($mkMake))
  {
  // should add some limit so that people don't mass create teams.
  // may also wish to limit new members from creating teams
  if (isset($ctid))
    {
    // show creation interface
    $htmlC->startHeader("User Teams");
    include($relPath.'js_newpophelp.inc');
    echo $beginPage.$menuBar;
    showEdit($userP,$tstart,"","",1,0);
    echo $menuBar.$closePage;
    }
  else if (isset($mkPreview))
    {
    $htmlC->startHeader("User Teams");
    include($relPath.'js_newpophelp.inc');
    echo $beginPage.$menuBar;
    showEdit($userP,$tstart,htmlentities(stripslashes($teamname)),stripslashes($text_data),1,0);
    echo "</div><br><div align=\"center\">";
    showTeamProfile($pguser,$userP,$tstart,0,stripAllString($teamname),stripAllString($text_data),0,0,0,time(),$pguser,$pguser,1);
    echo $menuBar.$closePage;
    }
  else if (isset($mkMake))
    {
    // create team in database
      $teamQuery="INSERT INTO user_teams (teamname,team_info,createdby,owner,created) 
        VALUES('".addslashes(stripAllString($teamname))."','".addslashes(stripAllString($text_data))."','$pguser','{$userP['u_id']}','".time()."')";
      mysql_query($teamQuery);
      $jtid=mysql_insert_id($db_link);
      createThread($teamname,$text_data,$pguser, $jtid);
    //figure out which team to overwrite
      $otid=0;
      if (!isset($teamall))
        {
        $team_1=$userP['team_1'];$team_2=$userP['team_2'];$team_3=$userP['team_3'];
        if ($team_1==$tteams)
          {$otid=1;}
        else if ($team_2==$tteams)
          {$otid=2;}
        else if ($team_3==$tteams)
          {$otid=3;}
         }
      joinTeam($userP,$pguser,$otid,$jtid);
      // update cookie
        $cookieC->setUserPrefs($pguser);
      metarefresh(0,"userteams.php?tid=$jtid",'Join the Team','Joining the team....');
    }
  exit;
  }

// show a list of teams
  $htmlC->startHeader("User Teams");
  echo $beginPage.$menuBar;
// the teams table query
  $tQuery="SELECT teamname, id, icon, member_count, page_count FROM user_teams ORDER BY id ASC LIMIT $tstart,20";
  $tResult=mysql_query($tQuery);
  $tRows=mysql_num_rows($tResult);
  $cellCount=0;
// start display
$td_ar=array($td2,$td3,$td6a,$td6);

  echo $tb.$tr.$td7."<b>User Teams</b>".$tre.$tr;
  $teamRow="$td2<b>Icon</b>$tde$td2<b>ID</b>$tde$td6a<b>Team Name</b>".
    "$tde$td2<b>Total Members</b>$tde$td2<b>Page Count</b>$tde$td2<b>Options</b>";
  echo $teamRow;
  if ($tRows!=0)
  {
    for ($i=0;$i<$tRows;$i++)
      {
        if (($i % 2)==0)
          {$t_td1=$td_ar[1];$t_td2=$td_ar[3];}
        else
          {$t_td1=$td_ar[0];$t_td2=$td_ar[2];}

        $curTeam=mysql_fetch_assoc($tResult);
        $teamIcon=$curTeam['icon'];
        $teamID=$curTeam['id'];
        $teamName=$curTeam['teamname'];
        $teamLink="<a href=\"userteams.php?tid=$teamID&amp;tstart=$tstart\">";
        echo $tre."\r\n".$tr;
        echo $t_td1.$teamLink."<img src=\"./users/teams/icon/$teamIcon\" width=\"25\" height=\"25\" alt=\"".strip_tags($teamName)."\" border=\"0\"></a>".
          "$tde$t_td1<strong>$teamID</strong>$tde$t_td2$teamName$tde".
          "$t_td1{$curTeam['member_count']}$tde$t_td1{$curTeam['page_count']}$tde$t_td1".
          "<b>".$teamLink."View</a> ";
        if ($curTeam['id']!=1 && $userP['team_1']!=$curTeam['id'] && $userP['team_2']!=$curTeam['id'] && $userP['team_3']!=$curTeam['id'])
          {echo " <a href=\"userteams.php?jtid=$teamID\">Join</a></b>";}
        else if ($teamID !=1)
          {echo " <a href=\"userteams.php?qtid=$teamID\">Quit</a></b>";}
        else {echo "</b>";}
        $cellCount++;
      } // end team display loop

  // finish off table
    for ($i=$cellCount;$i<20;$i++)
      {
       echo $tre."\r\n".$tr;
        echo $td11.$tde.$td11.$tde.$td11.$tde.$td11.$tde.$td11.$tde.$td11;
      } // end finish cells
  } // end of are there any rows to display if
  else
  {
    echo $tre.$tr.$td8."<strong>No more teams available.</strong>";
  }
echo $tre.$tr.$td9;
// show back if needed
if ($tstart!=0)
  {echo "<strong><a href=\"userteams.php?tstart=".($tstart-20)."\">Previous</a></strong>";}

echo $tde.$td10;
if ($tRows==20)
  {echo "<strong><a href=\"userteams.php?tstart=".($tstart+20)."\">Next</a></strong>";}
echo $tre.$tbe."<br>".$tb2.$tr.$td3."<strong><a href=\"userteams.php?ctid=1&amp;tstart=$tstart\">Create New Team</a></strong>";
echo $tre.$tbe.$menuBar.$closePage;

function createThread(tname, $tinfo, $owner, $tid) {
	//Declare variables
	$timeposted = time();
	$post_ip = $_SERVER['REMOTE_ADDR'];
	$owner = 527;
	$title = $tname;
	$message = "Team Name: $tname<br>Created By: $towner<br>Info: $tinfo<br>Team Page: <a href='http://texts01.archive.org/dp/userteams.php?tid=$tid'>http://texts01.archive.org/dp/userteams.php?tid=$tid<br><br>Use this area to have a discussion with your fellow teammates! :-D";
	$message = addslashes($message);

	//Add Topic into phpbb_topics
	$insert_topic = mysql_query("INSERT INTO phpbb_topics (topic_id, forum_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_status, topic_vote, topic_type, topic_first_post_id, topic_last_post_id, topic_moved_id) VALUES (NULL, 11, '$title', $owner, $timeposted, 0, 0, 0, 0, 0, 1, 1, 0)");
	$topic_id = mysql_insert_id();

	//Add Post into phpbb_posts
	$insert_post = mysql_query("INSERT INTO phpbb_posts (post_id, topic_id, forum_id, poster_id, post_time, poster_ip, post_username, enable_bbcode, enable_html, enable_smilies, enable_sig, post_edit_time, post_edit_count) VALUES (NULL,$topic_id, 11, $owner, $timeposted, '$post_ip', NULL, 1, 0, 1, 1, NULL, 0)");
	$post_id = mysql_insert_id();

	//Add Post Text into phpbb_posts_text
	$insert_post_text = mysql_query("INSERT INTO phpbb_posts_text (post_id, bbcode_uid, post_subject, post_text) VALUES ($post_id, '', '$title', '$message')");

	//Update phpbb_topics with post_id
	$update_topic = mysql_query("UPDATE phpbb_topics SET topic_first_post_id=$post_id, topic_last_post_id=$post_id WHERE topic_id=$topic_id");

	//Update forum post count
	$get_count = mysql_query("SELECT forum_posts, forum_topics FROM phpbb_forums WHERE forum_id=11");
	while($row = mysql_fetch_array($get_count)) {
	$forum_posts = $row['forum_posts'];
	$forum_topics = $row['forum_topics'];
	$forum_posts++;
	$forum_topics++;
	$update_count = mysql_query("UPDATE phpbb_forums SET forum_posts=$forum_posts, forum_topics=$forum_topics, forum_last_post_id=$post_id WHERE forum_id=11");
	}

	//Update teams db with topic_id so it can be deleted
	$update_project = mysql_query("UPDATE user_teams SET topic_id=$topic_id WHERE id='$tid'");
}
?>