<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');
include($relPath.'html_main.inc');
include($relPath.'doctype.inc');

if (!isset($tstart))
  {$tstart=0;}

$beginPage=$docType."<html><head><title>User Teams</title></head><body><div align=\"center\">";

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
    echo "<table id=\"teamtabletop\" border=\"1\" cellpadding=\"10\" width=\"650\">".
      "<tr><td align=\"center\">".
      "<img src=\"./users/teams/icons/{$curTeam['avatar']}\" width=\"100\" height=\"100\" alt=\"{$curTeam['teamname']}\"></td>".
      "</td><td align=\"center\"><b>{$curTeam['teamname']}</b>".
      "<p>Created: ".date("l, F jS, Y \a\\t g:i:sA",$curTeam['created']);
    echo "</td></tr></table><br>";
    echo "<table id=\"teamtable\" border=\"1\" cellpadding=\"10\" width=\"650\">";
    echo "<tr><td colspan=\"2\" align=\"center\"><strong>Description</strong>";
    echo "</td></tr><tr><td colspan=\"2\">".
      stripslashes($curTeam['team_info']);
    echo "</td></tr><tr><td>".
      "Created by: <b>{$curTeam['createdby']}</b>";
    echo "</td><td>".
      "Owned by: <b>$ownerName</b>";
    // if owner, let them edit
      if ($ownerName==$pguser)
        {echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"userteams.php?etid={$curTeam['id']}\">Edit</a>";}
    echo "</td></tr><tr><td>".
      "Number of Members: <b>{$curTeam['member_count']}</b>";
    echo "</td><td>".
      "Pages Proofed: <b>{$curTeam['page_count']}</b>";
  // end display
    echo "<tr><td align=\"left\">".
      "<b><a href=\"userteams.php?tstart=$tstart\">View Teams</a>";
    echo "</td><td align=\"right\">";
    if ($curTeam['id']!=1 && $userP['team_1']!=$curTeam['id'] && $userP['team_2']!=$curTeam['id'] && $userP['team_3']!=$curTeam['id'])
      {echo "<a href=\"userteams.php?jtid={$curTeam['id']}\">Join</a></b>";}
    else if ($curTeam['id'] !=1)
      {echo "<a href=\"userteams.php?qtid={$curTeam['id']}\">Quit the Team</a></b>";}
    echo "</td></tr></table></div></body></html>";
  exit;
  } // end of show team profile

// join a team
if (isset($jtid))
  {
  $joinQ='';
  if ($userP['team_1']==0 || $otid==1)
    {$joinQ="team_1='$jtid'";}
  else if ($userP['team_2']==0 || $otid==2)
    {$joinQ="team_2='$jtid'";}
  else if ($userP['team_3']==0 || $otid==3)
    {$joinQ="team_3='$jtid'";}
  if ($joinQ=='')
  {
    // all the spaces are filled, which would the like to overwrite?
      echo $beginPage;
      echo "You have already joined three teams.<br>".
        "Which team would you like to replace?<P>";
      $teamQ1="SELECT teamname FROM user_teams WHERE id='{$userP['team_1']}'";
      $teamQ2="SELECT teamname FROM user_teams WHERE id='{$userP['team_2']}'";
      $teamQ3="SELECT teamname FROM user_teams WHERE id='{$userP['team_3']}'";
      $teamR=mysql_query($teamQ1);
      echo "<a href=\"userteams.php?qtid=$qtid&amp;otid=1\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
      $teamR=mysql_query($teamQ2);
      echo "<a href=\"userteams.php?qtid=$qtid&amp;otid=2\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
      $teamR=mysql_query($teamQ3);
      echo "<a href=\"userteams.php?qtid=$qtid&amp;otid=3\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "<br><a href=\"../../userteams.php?tid=$gtid\">Do Not Join Team</a>";
      echo "</td></tr></table></div></body></html>";
  }  
  else
  {
    $joinQuery="UPDATE users SET ".$joinQ." WHERE username='$pguser' AND u_id='{$userP['u_id']}'";
    mysql_query($joinQuery);
    $teamQuery="UPDATE user_teams SET member_count=member_count+1 WHERE id='$jtid'";
    mysql_query($teamQuery);
    // update cookie
      $cookieC->setUserPrefs($pguser);
    metarefresh(0,"userteams.php?tid=$jtid",'Join the Team','Joining the team....');
  }
  exit;
  }

// quit a team
if (isset($qtid))
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
  $teamQuery="UPDATE user_teams SET member_count=member_count-1 WHERE id='$jtid'";
  mysql_query($teamQuery);
  // update cookie
    $cookieC->setUserPrefs($pguser);
  metarefresh(0,"userteams.php?tid=$qtid",'Quit the Team','Quitting the team....');
  exit;
  }

// edit a team
if (isset($etid))
  {
echo $etid;
  exit;
  }

// create a team
if (isset($qtid))
  {
echo $ctid;
  exit;
  }

// show a list of teams
echo $beginPage;
// the teams table query
  $tQuery="SELECT teamname, id, icon, member_count, page_count FROM user_teams WHERE id>$tstart ORDER BY id ASC LIMIT 20";
  $tResult=mysql_query($tQuery);
  $tRows=mysql_num_rows($tResult);
  $cellCount=0;
// start display
  echo "<table id=\"teamtable\" border=\"1\" cellpadding=\"10\" width=\"650\"><tr><td colspan=\"12\" align=\"center\"><b>Teams</b></td></tr><tr>";
  $teamRow="<td align=\"center\"> </td><td align=\"center\"> </td><td align=\"center\">Team Name</td>".
    "<td align=\"center\">Total Members</td><td align=\"center\">Page Count</td><td align=\"center\">Options</td>";
  echo $teamRow.$teamRow;
  if ($tRows!=0)
  {
    for ($i=0;$i<$tRows;$i++)
      {
        $curTeam=mysql_fetch_assoc($tResult);
        $teamIcon=$curTeam['icon'];
        $teamID=$curTeam['id'];
        $teamName=$curTeam['teamname'];
        $newRow=($i%2)==0?1:0;
        if ($newRow)
          {echo "</tr>\r\n<tr>";}
        echo "<td><img src=\"./users/teams/icons/$teamIcon\" width=\"25\" height=\"25\" alt=\"$teamName\"></td>".
          "<td><strong>$teamID</strong></td><td>$teamName</td>".
          "<td>{$curTeam['member_count']}</td><td>{$curTeam['page_count']}</td><td>".
          "<b><a href=\"userteams.php?tid=$teamID&amp;tstart=$tstart\">View</a> ";
        if ($curTeam['id']!=1 && $userP['team_1']!=$curTeam['id'] && $userP['team_2']!=$curTeam['id'] && $userP['team_3']!=$curTeam['id'])
          {echo "<a href=\"userteams.php?jtid=$teamID\">Join</a></b>";}
        else if ($teamID !=1)
          {echo "<a href=\"userteams.php?qtid=$teamID\">Quit</a></b>";}
        echo "</td>";
        $cellCount++;
      } // end team display loop

  // finish off table
    for ($i=$cellCount;$i<20;$i++)
      {
        $newRow=($i%2)==0?1:0;
        if ($newRow)
          {echo "</tr>\r\n<tr>";}
        echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
      } // end finish cells
  } // end of are there any rows to display if
  else
  {
    echo "</tr><tr><td colspan=\"12\" align=\"center\">";
    echo "<strong>No more teams available.</strong></td>";
  }
echo "</tr><tr><td colspan=\"6\">";
// show back if needed
if ($tstart!=0)
  {echo "<strong><a href=\"userteams.php?tstart=".($tstart-20)."\">Previous</a></strong>";}

echo "</td><td colspan=\"6\" align=\"right\">";
if ($tRows!=0)
  {echo "<strong><a href=\"userteams.php?tstart=".($tstart+20)."\">Next</a></strong>";}
echo "</td></tr></table></div></body></html>";




?>
