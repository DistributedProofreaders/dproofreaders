<?PHP

include_once($relPath.'dpsession.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'v_site.inc');

class processpage
{
  var $project='';
  var $fileid='';
  var $imagefile='';
  var $pageround=NULL;
  var $prd=NULL;
  var $initial_text='';

function processpage()
{
    $server_name = ( isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "" );
    $this->cookieDomain = ( substr_count($server_name, ".") > 0 ? $server_name : "" );
}

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
  $this->pageround=$this->getPageRound($pagestate);

  $this->prd = get_PRD_for_round( $this->pageround );

  if ($pagestate==$this->prd->avail_state || $pagestate==$this->prd->out_state)
  {
    $this->initial_text=$this->prd->prevtext_column_name;
  }
  else
  {
    $this->initial_text=$this->prd->text_column_name;
  }
}

function noPages($newwin, $begin_threshold = 0)
{
    if ($begin_threshold) {	
       $body=_("You have already proofread your allowed quota of pages from this Beginners Only project. Perhaps you could try proofreading an EASY project.<br> You will be taken back to the project listing page in 4 seconds.");
       $title = _("Beginners Only quota reached for this Project");
    } else {
       $body=_("No more files available for proofreading for this round of the project.<br> You will be taken back to the project listing page in 4 seconds.");
       $title = _("Project Round Complete");
    }
    metarefresh(4,"list_avail.php",$title,$body);
}

function getAvailablePage($project,$proofstate,$pguser)
{

  // if a BEGIN project in R1, people can only get a new page 
  // if they've done less than X pages on this project already;
  // X is currently, let's say, 20

  if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {
       $dbQuery = "
                   SELECT COUNT(fileid) as pagesdone FROM $project, projects 
				WHERE  round1_user = '$pguser' AND difficulty = 'beginner' AND projectid ='$project'
       "; 
       $result=mysql_query($dbQuery);
       $pagesdone = mysql_result($result, 0, "pagesdone" );
       if ($pagesdone > 20) {
           $rval['TooManyBegin'] = 1;
           return $rval;
       }
  }

  //find page to be proofed.
  $dbQuery="SELECT fileid, image, state FROM $project WHERE state='";
    if ($proofstate==PROJ_PROOF_SECOND_AVAILABLE) {$dbQuery.=AVAIL_SECOND."' AND round1_user != '$pguser'";}
    else {$dbQuery.=AVAIL_FIRST."'";}
  $dbQuery.=" ORDER BY image ASC LIMIT 1";
  $result=mysql_query($dbQuery);
  $numrows = mysql_num_rows($result);
    if ($numrows !=0) {$rval=mysql_fetch_assoc($result);$rval['isopen']=1;$rval['TooManyBegin'] = 0;}
    else {$rval['isopen']=0;$rval['TooManyBegin'] = 0;}
  return $rval;
}

function setPageCookie($project,$proofstate,$fileid,$image,$pagestate,$saved,$editone,$revert,$spcheck,$errcheck,$isDone)
{
  //project|proofstate|fileid|image|pagestate|saved|editone|revert|spcheck|errcheck|isDone|pageTime
  $pagerefs="$project|$proofstate|$fileid|$image|$pagestate|$saved|$editone|$revert|$spcheck|$errcheck|$isDone|".time();
  dpsession_page_set($pagerefs);
}

function setTempPageCookie($tempPrefs)
{
  $pagerefs=implode("|",$tempPrefs);
  dpsession_page_set($pagerefs);
}

function getPageCookie()
{
  @$cPage=explode("|", dpsession_page_get() );
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
{
	dpsession_page_end();
}

function checkOutPage($project,$proofstate,$pguser,$fileid,$imagefile)
{
  if ($proofstate==PROJ_PROOF_SECOND_AVAILABLE)
  {
    $round_number = 2;
  }
  else
  {
    $round_number = 1;
  }

  return Page_checkout( $project, $fileid, $imagefile, $round_number, $pguser );
}

function getText($pagestate,$saved,$revert)
{
  $wText=$this->initial_text;
    if ($saved==1) {$wText=$this->prd->text_column_name;}
    if ($revert==1 || $pagestate==OUT_FIRST || $pagestate==OUT_SECOND) {$wText=$this->prd->prevtext_column_name;}
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
    // content of a <textarea> element (the Proofreading Page's text frame),
    // which the browser won't interpret as HTML anyhow.
    // (That's why we see <i> *tags* rather than seeing italicized text.)
    // So just return the text as-is.

  return $text_data;
}

function addUserCount($proofstate,$pguser,$userP)
{
  $sql = "SELECT state FROM {$this->project} WHERE image='{$this->imagefile}' AND fileid='{$this->fileid}'";
  $result=mysql_query($sql);
  $rows=mysql_num_rows($result);
    if ($rows !=0)
    {
      $curState=mysql_result($result,0,'state');
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
  $result=mysql_query($sql);
  $rows=mysql_num_rows($result);
    if ($rows !=0)
    {
      $curState=mysql_result($result,0,'state');
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
  return Page_saveAsInProgress( $this->project, $this->fileid, $this->imagefile, $this->pageround, $pguser, $text_data );
}


// when a page is reopened from MRC, no need to update text, just record state change
function reOpen($proofstate,$pguser)
{
  return Page_reopen( $this->project, $this->fileid, $this->imagefile, $this->pageround, $pguser );
}


function saveComplete($proofstate,$text_data,$pguser,$userP)
{
  $this->addUserCount($proofstate,$pguser,$userP);
  return Page_saveAsDone( $this->project, $this->fileid, $this->imagefile, $this->pageround, $pguser, $text_data );
}

function getRevertState()
{
  return $this->prd->temp_state;
}

function exitInterface($newwin,$editone)
{
  $returnURL=$editone==1?"projects.php?project={$this->project}&proofstate={$this->proofstate}":"proof_per.php";
    if ($newwin==0)
      {metarefresh(0,"$returnURL",' ',' ');}
    else
      {
        include($relPath.'doctype.inc');
        echo "$docType\r\n<HTML><BODY>";
        echo "<SCRIPT LANGUAGE=\"JavaScript\" TYPE=\"text/javascript\">window.opener.location.href=\"$returnURL\";";
        echo "window.close();</SCRIPT>";
        echo "<A HREF=\"#\" onclick=\"window.close()\">"._("Click here to close the proofreading window.")."</A>";
        echo "</BODY></HTML>";
      }
}

function returnPage($proofstate,$pguser,$userP)
{
  return Page_returnToRound( $this->project, $this->fileid, $this->imagefile, $this->pageround, $pguser );
}

} // end class
?>
