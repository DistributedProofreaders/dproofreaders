<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');
include($relPath.'html_main.inc');
include($relPath.'doctype.inc');

if (!isset($tstart))
  {$tstart=0;}

$beginPage=$docType."<html><head><title>User Teams</title></head><body><div align=\"center\">";

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
    echo "<table \r\nid=\"teamtabletop\" border=\"1\" cellpadding=\"10\" width=\"650\">".
      "<tr><td \r\nalign=\"center\">";

    if (strcmp($tavatar,0)!=0)
      {echo "<img \r\nsrc=\"./users/teams/icons/$tavatar\" width=\"100\" height=\"100\" alt=\"".strip_tags($tname)."\">";}
    echo "</td>".
      "</td><td \r\nalign=\"center\"><b>".stripslashes($tname)."</b>".
      "<p>Created: ".date("l, F jS, Y \a\\t g:i:sA",$ttime);
    echo "</td></tr></table><br>";
    echo "<table \r\nid=\"teamtable\" border=\"1\" cellpadding=\"10\" width=\"650\">";
    echo "<tr><td \r\ncolspan=\"2\" align=\"center\"><strong>Description</strong>";
    echo "</td></tr><tr><td \r\ncolspan=\"2\">".
      stripslashes($ttext);
    echo "</td></tr><tr><td>".
      "Created by: <b>$tcreator</b>";
    echo "</td><td>".
      "Owned by: <b>$ownerName</b>";
    // if owner, let them edit
      if ($ownerName==$pguser && $tpreview==0)
        {echo "&nbsp;&nbsp;&nbsp;&nbsp;<a \r\nhref=\"userteams.php?etid=$tid\">Edit</a>";}
    echo "</td></tr><tr><td>".
      "Number of Members: <b>$tmembers</b>";
    echo "</td><td>".
      "Pages Proofed: <b>$tpages</b>";
  // end display
    if ($tpreview ==0)
      {
        echo "</td></tr><tr><td \r\nalign=\"left\">".
          "<b><a href=\"userteams.php?tstart=$tstart\">View Teams</a>";
        echo "</td><td \r\nalign=\"right\">";
        if ($tid!=1 && $userP['team_1']!=$tid && $userP['team_2']!=$tid && $userP['team_3']!=$tid)
          {echo "<a href=\"userteams.php?jtid=$tid\">Join</a></b>";}
        else if ($tid !=1)
          {echo "<a href=\"userteams.php?qtid=$tid\">Quit the Team</a></b>";}
      }
    echo "</td></tr></table>";
  }

// see if they just want a team profile
if (isset($tid))
  {
  echo $beginPage;
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
    echo "</div></body></html>";
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
      if ($otid!=0)
        {
        $teamQuery="UPDATE user_teams SET member_count=member_count-1 WHERE id='$jtid'";
        mysql_query($teamQuery);
        }
      $joinQuery="UPDATE users SET ".$joinQ." WHERE username='$pguser' AND u_id='{$userP['u_id']}'";
        mysql_query($joinQuery);
      $teamQuery="UPDATE user_teams SET member_count=member_count+1 WHERE id='$jtid'";
        mysql_query($teamQuery);
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
        echo $beginPage;
        echo "<table id=\"teamtabletop\" border=\"1\" cellpadding=\"10\" width=\"650\">".
          "<tr><td align=\"center\" colspan=\"3\">";
        echo "You have already joined three teams.<br>".
          "Which team would you like to replace?<P>";
        $teamQ1="SELECT teamname FROM user_teams WHERE id='{$userP['team_1']}'";
        $teamQ2="SELECT teamname FROM user_teams WHERE id='{$userP['team_2']}'";
        $teamQ3="SELECT teamname FROM user_teams WHERE id='{$userP['team_3']}'";
        $teamR=mysql_query($teamQ1);
        echo "</td></tr><tr><td align=\"center\">";
        echo "<a href=\"userteams.php?jtid=$jtid&amp;otid=1\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "</td><td align=\"center\">";
        $teamR=mysql_query($teamQ2);
        echo "<a href=\"userteams.php?jtid=$jtid&amp;otid=2\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "</td><td align=\"center\">";
        $teamR=mysql_query($teamQ3);
        echo "<a href=\"userteams.php?jtid=$jtid&amp;otid=3\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;".
          "</td></tr><tr><td align=\"center\" colspan=\"3\">";
        echo "<br><a href=\"userteams.php?tid=$jtid\">Do Not Join Team</a>";
        echo "</td></tr></table></div></body></html>";
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
    mysql_query($quitQuery);
    $teamQuery="UPDATE user_teams SET member_count=member_count-1 WHERE id='$qtid'";
    mysql_query($teamQuery);
    // update cookie
      $cookieC->setUserPrefs($pguser);
    metarefresh(0,"userteams.php?tid=$qtid",'Quit the Team','Quitting the team....');
    }
  else {metarefresh(3,"userteams.php?tid=$qtid",'Not a member','Unable to quit team....');}
  exit;
  }


function showEdit($userP,$tstart,$tname,$ttext,$tedit,$tsid)
  {
  echo "<form \r\n".
    "id=\"mkTeam\" name=\"mkTeam\" action=\"userteams.php\" method=\"POST\" target=\"_top\"><input \r\n".
    "type=\"hidden\" name=\"tstart\" value=\"$tstart\"><input \r\n".
    "type=\"hidden\" name=\"tsid\" value=\"$tsid\"><table \r\n".
    "id=\"teamtable\" border=\"1\" cellpadding=\"10\"><tr><td \r\n".
    "colspan=\"2\" align=\"center\">New Proofing Team</td></tr><tr><td \r\n".
    "colspan=\"2\" align=\"center\"><strong>Team Name</strong> <INPUT \r\n".
    "TYPE=\"text\" VALUE=\"$tname\" name=\"teamname\" size=\"30\">\r\n".
    "<br>limit 50 characters</td></tr><tr><td \r\n".
    "colspan=\"2\" align=\"center\"><strong>Team Description</strong><br><textarea \r\n".
    "name=\"text_data\" COLS=\"40\" ROWS=\"6\">\r\n".
    $ttext."</textarea><br>You may use the following markup in the description:<br>\r\n".
    "[b][/b]=bold [i][/i]=italic [p][/p]=paragraph [lb]=line break</td></tr><tr><td \r\n".
    "colspan=\"2\">The Team Icon and Team Logo can be uploaded after ".
    "the team is created.";
  $team_1=$userP['team_1'];$team_2=$userP['team_2'];$team_3=$userP['team_3'];
  if ($tedit==1 && $team_1!=0 && $team_2!=0 && $team_3!=0)
    {
    echo "</td></tr><tr><td \r\n".
      "colspan=\"2\" align=\"center\">You must join the team to create it, \r\n".
      "which team space would you like to use?<br><select \r\n".
      "name=\"tteams\" ID=\"tteams\" title=\"Team List\">";
    $teamQuery="SELECT teamname,id FROM user_teams WHERE id='{$userP['team_1']}' OR  id='{$userP['team_2']}' OR  id='{$userP['team_3']}'";
      $teamRes=mysql_query($teamQuery);
      while($row = mysql_fetch_assoc($teamRes))
        {echo "<option value=\"{$row['id']}\">{$row['teamname']}</option>";}
    echo "</select></td></tr>";
    }
  else
    {echo "<input \r\n"."type=\"hidden\" name=\"teamall\" value=\"1\"></td></tr>";}
  if($tedit==1)
    {
    echo "<tr><td \r\n".
      "colspan=\"2\" align=\"center\"><input \r\n".
      "type=\"submit\" name=\"mkPreview\" value=\"Preview Team Display\">&nbsp;&nbsp;&nbsp;<input \r\n".
      "type=\"submit\" name=\"mkMake\" value=\"Make Team\">&nbsp;&nbsp;&nbsp;<input \r\n".
      "type=\"submit\" name=\"mkQuit\" value=\"Quit\"></td></tr></table></form>";
    }
  else
    {
    echo "<tr><td \r\n".
      "colspan=\"2\" align=\"center\"><input \r\n".
      "type=\"submit\" name=\"edPreview\" value=\"Preview Changes\">&nbsp;&nbsp;&nbsp;<input \r\n".
      "type=\"submit\" name=\"edMake\" value=\"Save Changes\">&nbsp;&nbsp;&nbsp;<input \r\n".
      "type=\"submit\" name=\"edQuit\" value=\"Quit\"></td></tr></table></form>";
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
    echo $beginPage;
    showEdit($userP,$tstart,unstripAllString($curTeam['teamname'],0),unstripAllString($curTeam['team_info'],1),0,$ntid);
    echo "</div></body></html>";
    }
  else if (isset($edPreview))
    {
    echo $beginPage;
    showEdit($userP,$tstart,htmlentities(stripslashes($teamname)),stripslashes($text_data),0,$ntid);
    echo "</div><br><div align=\"center\">";
    showTeamProfile($pguser,$userP,$tstart,$ntid,stripAllString($teamname),stripAllString($text_data),0,$curTeam['member_count'],$curTeam['page_count'],$curTeam['created'],$curTeam['createdby'],$pguser,1);
    echo "</div></body></html>";
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
    echo $beginPage;
    showEdit($userP,$tstart,"","",1,0);
    echo "</div></body></html>";
    }
  else if (isset($mkPreview))
    {
    echo $beginPage;
    showEdit($userP,$tstart,htmlentities(stripslashes($teamname)),stripslashes($text_data),1,0);
    echo "</div><br><div align=\"center\">";
    showTeamProfile($pguser,$userP,$tstart,0,stripAllString($teamname),stripAllString($text_data),0,0,0,time(),$pguser,$pguser,1);
    echo "</div></body></html>";
    }
  else if (isset($mkMake))
    {
    // create team in database
      $teamQuery="INSERT INTO user_teams (teamname,team_info,createdby,owner,created) 
        VALUES('".addslashes(stripAllString($teamname))."','".addslashes(stripAllString($text_data))."','$pguser','{$userP['u_id']}','".time()."')";
      mysql_query($teamQuery);
      $jtid=mysql_insert_id($db_link);
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

/*
  teamname varchar(30) NOT NULL default 'default',
team_info TEXT NOT NULL,
  createdby varchar(25) NOT NULL default '',
  owner INT UNSIGNED NOT NULL REFERENCES users,
  created  INT(20) NOT NULL default '0',
  member_count  int(20) NOT NULL default '0',
  page_count  int(20) NOT NULL default '0',
avatar VARCHAR(25) NOT NULL default 'avatar_default.png',
icon VARCHAR(25) NOT NULL default 'icon_default.png',
*/

// show a list of teams
echo $beginPage;
// the teams table query
  $tQuery="SELECT teamname, id, icon, member_count, page_count FROM user_teams WHERE id>$tstart ORDER BY id ASC LIMIT 20";
  $tResult=mysql_query($tQuery);
  $tRows=mysql_num_rows($tResult);
  $cellCount=0;
// start display
  echo "<table id=\"teamtable\" border=\"1\" cellpadding=\"10\"><tr><td colspan=\"6\" align=\"center\"><b>Teams</b></td></tr><tr>";
  $teamRow="<td align=\"center\">Icon</td><td align=\"center\">ID</td><td align=\"center\" width=\"150\">Team Name</td>".
    "<td align=\"center\">Total Members</td><td align=\"center\">Page Count</td><td align=\"center\">Options</td>";
  echo $teamRow;
  if ($tRows!=0)
  {
    for ($i=0;$i<$tRows;$i++)
      {
        $curTeam=mysql_fetch_assoc($tResult);
        $teamIcon=$curTeam['icon'];
        $teamID=$curTeam['id'];
        $teamName=$curTeam['teamname'];
        echo "</tr>\r\n<tr>";
        echo "<td><img src=\"./users/teams/icons/$teamIcon\" width=\"25\" height=\"25\" alt=\"".strip_tags($teamName)."\"></td>".
          "<td><strong>$teamID</strong></td><td>$teamName</td>".
          "<td align=\"center\">{$curTeam['member_count']}</td><td align=\"center\">{$curTeam['page_count']}</td><td>".
          "<b><a href=\"userteams.php?tid=$teamID&amp;tstart=$tstart\">View</a> ";
        if ($curTeam['id']!=1 && $userP['team_1']!=$curTeam['id'] && $userP['team_2']!=$curTeam['id'] && $userP['team_3']!=$curTeam['id'])
          {echo " <a href=\"userteams.php?jtid=$teamID\">Join</a></b>";}
        else if ($teamID !=1)
          {echo " <a href=\"userteams.php?qtid=$teamID\">Quit</a></b>";}
        echo "</td>";
        $cellCount++;
      } // end team display loop

  // finish off table
    for ($i=$cellCount;$i<20;$i++)
      {
       echo "</tr>\r\n<tr>";
        echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
      } // end finish cells
  } // end of are there any rows to display if
  else
  {
    echo "</tr><tr><td colspan=\"6\" align=\"center\">";
    echo "<strong>No more teams available.</strong></td>";
  }
echo "</tr><tr><td colspan=\"3\">";
// show back if needed
if ($tstart!=0)
  {echo "<strong><a href=\"userteams.php?tstart=".($tstart-20)."\">Previous</a></strong>";}

echo "</td><td colspan=\"3\" align=\"right\">";
if ($tRows!=0)
  {echo "<strong><a href=\"userteams.php?tstart=".($tstart+20)."\">Next</a></strong>";}
echo "</td></tr></table><p><strong><a href=\"userteams.php?ctid=1&amp;tstart=$tstart\">Create New Team</a></strong></p></div></body></html>";




?>
