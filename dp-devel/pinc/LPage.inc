<?PHP

include_once($relPath.'page_states.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'v_keepmarkup.inc');
include_once($relPath.'project_trans.inc');

class processpage
{
  var $project='';
  var $fileid='';
  var $imagefile='';
  var $initial_text='';
  var $revert_text='';
  var $save_text='';
  var $checkout_state='';
  var $return_state='';
  var $tempsave_state='';
  var $save_state='';
  var $save_time='';
  var $save_user='';
  var $all_complete_state='';

function processpage()
{$this->cookieDomain=(substr_count($_SERVER['SERVER_NAME'], ".")) > 0? $_SERVER['SERVER_NAME'] : "";}

function getPageRound($state)
{
  if ($state==AVAIL_FIRST  || $state==OUT_FIRST || $state==TEMP_FIRST || $state==SAVE_FIRST)
  {return 1;}
  else if ($state==AVAIL_SECOND  || $state==OUT_SECOND || $state==TEMP_SECOND || $state==SAVE_SECOND)
  {return 2;}
  else
  {return 0;}
}

function getProjectRound($state)
{
  if ($state==PROJ_PROOF_FIRST_AVAILABLE || $state==PROJ_PROOF_FIRST_VERIFY || $state==PROJ_PROOF_FIRST_BAD_PROJECT)
  {return 1;}
  else if ($state==PROJ_PROOF_SECOND_AVAILABLE || $state==PROJ_PROOF_SECOND_VERIFY || $state==PROJ_PROOF_SECOND_BAD_PROJECT)
  {return 2;}
  else
  {return 0;}
}

function setPageState($pagestate,$project,$fileid,$imagefile,$proofstate)
{
  $this->project=$project;
  $this->fileid=$fileid;
  $this->imagefile=$imagefile;
  $this->pagestate=$pagestate;
  $this->proofstate=$proofstate;
    if ($this->getPageRound($pagestate)==1)
    {
      $this->bad_page=BAD_FIRST;
      $this->revert_text="master_text";
      $this->save_text="round1_text";
      $this->checkout_state=OUT_FIRST;
      $this->return_state=AVAIL_FIRST;
      $this->tempsave_state=TEMP_FIRST;
      $this->save_state=SAVE_FIRST;
      $this->save_time="round1_time";
      $this->save_user="round1_user";
      $this->all_complete_state=SAVE_FIRST;
        if ($pagestate==AVAIL_FIRST || $pagestate==OUT_FIRST) {$this->initial_text="master_text";}
        else {$this->initial_text="round1_text";}
    }
    if ($this->getPageRound($pagestate)==2)
    {
      $this->bad_page=BAD_SECOND;
      $this->revert_text="round1_text";
      $this->save_text="round2_text";
      $this->checkout_state=OUT_SECOND;
      $this->return_state=AVAIL_SECOND;
      $this->tempsave_state=TEMP_SECOND;
      $this->save_state=SAVE_SECOND;
      $this->save_time="round2_time";
      $this->save_user="round2_user";
      $this->all_complete_state=SAVE_SECOND;
        if ($pagestate==AVAIL_SECOND || $pagestate==OUT_SECOND) {$this->initial_text="round1_text";}
        else {$this->initial_text="round2_text";}
    }
}

function noPages($newwin)
{
    $body="No more files available for proofing for this round of the project.<BR> You will be taken back to the project listing page in 4 seconds.";
    metarefresh(4,"list_avail.php",'Project Round Complete',$body);
}

function getAvailablePage($project,$proofstate,$pguser)
{
  //find page to be proofed.
  $dbQuery="SELECT fileid, image, state FROM $project WHERE state='";
    if ($proofstate==PROJ_PROOF_SECOND_AVAILABLE) {$dbQuery.=AVAIL_SECOND."' AND round1_user != '$pguser'";}
    else {$dbQuery.=AVAIL_FIRST."'";}
  $dbQuery.=" ORDER BY image ASC LIMIT 1";
  $result=mysql_query($dbQuery);
  $numrows = mysql_num_rows($result);
    if ($numrows !=0) {$rval=mysql_fetch_assoc($result);$rval['isopen']=1;}
    else {$rval['isopen']=0;}
  return $rval;
}

function setPageCookie($project,$proofstate,$fileid,$image,$pagestate,$saved,$editone,$revert,$spcheck,$errcheck,$isDone)
{
  //project|proofstate|fileid|image|pagestate|saved|editone|revert|spcheck|errcheck|isDone|pageTime
  $pagerefs="$project|$proofstate|$fileid|$image|$pagestate|$saved|$editone|$revert|$spcheck|$errcheck|$isDone|".time();
  setcookie("userPage",$pagerefs,time() + 86400,"/",$this->cookieDomain,0);
}

function setTempPageCookie($tempPrefs)
{
  $pagerefs=implode("|",$tempPrefs);
  setcookie("userPage",$pagerefs,time() + 86400,"/",$this->cookieDomain,0);
}

function getPageCookie()
{
  @$cPage=explode("|",$_COOKIE['userPage']);
  $uPage=array();
  $i=0;
  @$uPage['project']=$cPage[$i++];
  @$uPage['proofstate']=$cPage[$i++];
  @$uPage['fileid']=$cPage[$i++];
  @$uPage['image']=$cPage[$i++];
  @$uPage['pagestate']=$cPage[$i++];
  @$uPage['saved']=$cPage[$i++];
  @$uPage['editone']=$cPage[$i++];
  @$uPage['revert']=$cPage[$i++];
  @$uPage['spcheck']=$cPage[$i++];
  @$uPage['errcheck']=$cPage[$i++];
  @$uPage['isDone']=$cPage[$i++];
  @$uPage['pageTime']=$cPage[$i++];
  //@$uPage['']=$cPage[$i++];
  return $uPage;
}

function deletePageCookie()
  {setcookie("userPage","",time() - 86400,"/",$this->cookieDomain,0);}

function checkOutPage($project,$proofstate,$pguser,$fileid,$imagefile)
{
  $timestamp=time();
  $dbQuery="UPDATE $project SET state='";
    if ($proofstate==PROJ_PROOF_SECOND_AVAILABLE)
    {
      $rval=OUT_SECOND;
      $dbQuery.="$rval', round2_time='$timestamp', round2_user='$pguser'";
    }
    else
    {
      $rval=OUT_FIRST;
      $dbQuery.="$rval', round1_time='$timestamp', round1_user='$pguser'";
    }
  $dbQuery.="  WHERE fileid='$fileid' AND image='$imagefile'";
  $update = mysql_query($dbQuery);
  return $rval;  
}

function getText($pagestate,$saved,$revert)
{
  $wText=$this->initial_text;
    if ($saved==1) {$wText=$this->save_text;}
    if ($revert==1 || $pagestate==OUT_FIRST || $pagestate==OUT_SECOND) {$wText=$this->revert_text;}
  $dbQuery="SELECT $wText FROM {$this->project} WHERE fileid = '{$this->fileid}' AND image='{$this->imagefile}'";
  $result = mysql_query($dbQuery);
  $myrow = mysql_fetch_row($result);
  $text_data = mysql_result($result, 0, $wText);

    // We need to protect our users against malicious HTML code.
    // For example, see CERT Advisory CA-2000-02.
    // But we can't throw out all tags, because we use some tags, like <i>..</i>.
    // And we can't throw out all but the trusted tags, because there are other
    // uses of angle-brackets that don't look like trusted tags, e.g. X < Y
    // And really, we don't want to throw out *anything*, because there's
    // the risk that we might throw out a big chunk of non-malicious work.
    // So let's try this: anything that looks like it might be a nasty tag,
    // we convert its opening angle-bracket to '&lt' and mark it with an asterisk.
    // $text_data = preg_replace( ';<([\s/]*(SCRIPT|OBJECT|APPLET|EMBED));i', '&lt;*\1', $text_data );

    // Actually, this is all moot.
    // The value returned by this function will be sent to the client as the
    // content of a <textarea> element (the Proofing Page's text frame),
    // which the browser won't interpret as HTML anyhow.
    // (That's why we see <i> *tags* rather than seeing italicized text.)
    // So just return the text as-is.

  return $text_data;
}

function isProjectDone($proofstate)
{
  $sql ="SELECT * FROM {$this->project} WHERE state='";
  $sql.=$this->getProjectRound($proofstate)==1? AVAIL_FIRST:AVAIL_SECOND;
  $sql.="' OR state='";
  $sql.=$this->getProjectRound($proofstate)==1? OUT_FIRST:OUT_SECOND;
  $sql.="' LIMIT 1";
  $result = dquery($sql);
  $rows=nrows($result);
    if ($rows == 0)
    {
      $newstate = $this->getProjectRound($proofstate)==1 ? PROJ_PROOF_FIRST_VERIFY : PROJ_PROOF_SECOND_VERIFY;
      $error_msg = project_transition( $this->project, $newstate );
      if ($error_msg)
      {
        echo "$error_msg<br>\n";
      }
    }
}

function addUserCount($proofstate,$pguser,$userP)
{
  $sql = "SELECT state FROM {$this->project} WHERE image='{$this->imagefile}' AND fileid='{$this->fileid}'";
  $result=dquery($sql);
  $rows=nrows($result);
    if ($rows !=0)
    {
      $curState=dresult($result,0,'state');
        if (($this->getProjectRound($proofstate)==1 && ($curState==OUT_FIRST || $curState==TEMP_FIRST)) || ($this->getProjectRound($proofstate)==2 && ($curState==OUT_SECOND || $curState==TEMP_SECOND)))
        {
          // add to user page count
          $sql = "UPDATE users SET pagescompleted = pagescompleted+1 WHERE username = '$pguser'";
          $result = mysql_query($sql);
          $sql = "UPDATE user_teams SET page_count = page_count+1 WHERE id=1 OR id={$userP['team_1']} OR id={$userP['team_2']} OR id={$userP['team_3']}";
          $result = mysql_query($sql);
        }
    }
}

function deleteUserCount($proofstate,$pguser,$userP)
{
  $sql = "SELECT state FROM {$this->project} WHERE image='{$this->imagefile}' AND fileid='{$this->fileid}'";
  $result=dquery($sql);
  $rows=nrows($result);
    if ($rows !=0)
    {
      $curState=dresult($result,0,'state');
        if (($this->getProjectRound($proofstate)==1 && $curState==SAVE_FIRST) || ($this->getProjectRound($proofstate)==2 && $curState==SAVE_SECOND))
        {
          // delete from user page count
          $sql = "UPDATE users SET pagescompleted = pagescompleted-1 WHERE username = '$pguser'";
          $result = mysql_query($sql);
          $sql = "UPDATE user_teams SET page_count = page_count-1 WHERE id=1 OR id={$userP['team_1']} OR id={$userP['team_2']} OR id={$userP['team_3']}";
          $result = mysql_query($sql);
        }
    }
}


function saveTemp($proofstate,$text_data,$pguser)
{
  $timestamp = time();
  $dbQuery="UPDATE {$this->project} SET {$this->save_text}='$text_data', {$this->save_time}='$timestamp', state='{$this->tempsave_state}', {$this->save_user}='$pguser'";
  $dbQuery.=" WHERE image='{$this->imagefile}' AND fileid='{$this->fileid}'";
  $result = dquery($dbQuery);
  return $this->tempsave_state;
}


// when a page is reopened from MRC, no need to update text, just record state change
function reOpen($proofstate,$pguser)
{
  $timestamp = time();
  $dbQuery="UPDATE {$this->project} SET {$this->save_time}='$timestamp', state='{$this->tempsave_state}', {$this->save_user}='$pguser'";
  $dbQuery.=" WHERE image='{$this->imagefile}' AND fileid='{$this->fileid}'";
  $result = dquery($dbQuery);
  return $this->tempsave_state;
}


function saveComplete($proofstate,$text_data,$pguser,$userP)
{
  $this->addUserCount($proofstate,$pguser,$userP);
  $timestamp = time();
  $dbQuery="UPDATE {$this->project} SET {$this->save_text}='$text_data', {$this->save_time}='$timestamp', state='{$this->save_state}', {$this->save_user}='$pguser'";
  $dbQuery.=" WHERE image='{$this->imagefile}' AND fileid='{$this->fileid}'";
  $result = mysql_query($dbQuery);
  $this->isProjectDone($proofstate);
  return $this->save_state;
}

function getRevertState()
{
  return $this->tempsave_state;
}

function exitInterface($newwin,$editone)
{
  $returnURL=$editone==1?"projects.php?project={$this->project}&proofstate={$this->proofstate}":"proof_per.php";
    if ($newwin==0)
      {metarefresh(0,"$returnURL",' ',' ');}
    else
      {
        include($relPath.'doctype.inc');
        echo "$docType\r\n<HTML><HEAD><TITLE>Quit</TITLE></HEAD><BODY>";
        echo "<SCRIPT LANGUAGE=\"JavaScript\" TYPE=\"text/javascript\">window.opener.location.href=\"$returnURL\";";
        echo "window.close();</SCRIPT>";
        echo "Please <A HREF=\"#\" onclick=\"window.close()\">click here</A> to close the proofing window.";
        echo "</BODY></HTML>";
      }
}

function returnPage($proofstate,$pguser,$userP)
{
  $timestamp = time();
  $dbQuery="UPDATE {$this->project} SET {$this->save_time}='$timestamp', state='{$this->return_state}', {$this->save_user}='$pguser'";
  $dbQuery.=" WHERE image='{$this->imagefile}' AND fileid='{$this->fileid}'";
  $result = mysql_query($dbQuery);
  return $this->return_state;
}

} // end class
?>
