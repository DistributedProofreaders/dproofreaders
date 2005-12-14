<?PHP

include_once($relPath.'dpsession.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_ops.inc');
include_once($relPath.'page_tally.inc');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_available_page( $project, $proofstate, $pguser )
{
    $round = get_Round_for_project_state($proofstate);

    // Normally, pages are served in order of "page number"
    // (i.e., by the 'image' field)
    $order = 'image ASC';
    // but this can be overridden below.

    // Projects with difficulty='beginner' are treated differently in
    // various ways, so ascertain whether this is a beginner project.

    $res1 = mysql_query("
        SELECT difficulty
        FROM projects
        WHERE projectid='$project'
    ") or die(mysql_error());
    $difficulty = mysql_result($res1,0);

    if ( $difficulty == 'beginner' )
    {
        if ($round->round_number == 1)
        {
            if ( !user_can_work_on_beginner_pages_in_round($round) )
            {
                $err = _("You have reached your quota of pages from 'Beginners Only' projects in this round.")
                    . " "
                    . _("Perhaps you could try working on an EASY project.");
                return $err;
            }

            // When a beginner project is in the first round (ELR),
            // any given user can work on at most 21 pages
            // from that project in that round.

            $dbQuery = "
                SELECT COUNT(fileid) as pagesdone
                FROM $project
                WHERE {$round->user_column_name} = '$pguser'
            "; 
            $result = mysql_query($dbQuery);
            $pagesdone = mysql_result($result, 0, "pagesdone" );
            if ($pagesdone > 20)
            {
                $err = _("You have reached your quota of pages from this 'Beginners Only' project, but you're welcome to work on a different one.")
                    . " "
                    . _("Or you could try working on an EASY project.");
                return $err;
            }
        }
        else if ($round->round_number == 2)
        {
            if ( !user_can_work_on_beginner_pages_in_round($round) )
            {
                $err = _("You do not have access to difficulty='beginner' projects in this round.");
                return $err;
            }

            // For beginner projects in P2,
            // we serve up pages in order of the P1 proofer.
            // (So that mentors can easily get all the pages by one proofer.)
            $order = 'round1_user ASC, image ASC';
        }
    }

    // Find page to be proofed.
    // (It appears that a simultaneous query from someone else would return the same page.)
    $dbQuery = "SELECT fileid, image, state FROM $project WHERE state='{$round->page_avail_state}'";
    for ( $rn = 1; $rn < $round->round_number; $rn++ )
    {
        $earlier_round = get_Round_for_round_number( $rn );
        $dbQuery .= " AND {$earlier_round->user_column_name} != '$pguser'";
    }
    $dbQuery .= " ORDER BY $order LIMIT 1";
    $result = mysql_query($dbQuery);
    $numrows = mysql_num_rows($result);
    if ($numrows == 0)
    {
        $err = _("No more files available for proofreading for this round of the project.");
        return $err;
    }
    $npage = mysql_fetch_assoc($result);

    $fileid    = $npage['fileid'];
    $imagefile = $npage['image'];

    $pagestate = Page_checkout( $project, $imagefile, $round, $pguser );

    setPageCookie( $project, $proofstate, $fileid, $imagefile, $pagestate, 0, 0, 0, 0, 0, 0 );
}

// -----------------------------------------------------------------------------

function resume_saved_page( $project, $proofstate, $fileid, $imagefile, $pagestate, $pguser )
{
    $round = get_Round_for_project_state($proofstate);

    $res = mysql_query("SELECT state, {$round->user_column_name} FROM $project WHERE image='$imagefile' AND fileid = '$fileid'");
    if (mysql_num_rows($res) == 0)
    {
        // This should only happen if they've been tweaking URLs.
        $err = _('No such page.');
        return $err;
    }

    list($current_page_state, $current_round_user) = mysql_fetch_row($res);

    if ($pguser != $current_round_user)
    {
        $err = sprintf( _("You (%s) do not have the necessary access to page %s"), $pguser, $imagefile );
        return $err;
    }

    if ($pagestate != $current_page_state)
    {
        $err = sprintf(
            _("Page %s has changed state from '%s' to '%s', so your request is invalid."),
            $imagefile, $pagestate, $current_page_state );
        return $err;
    }

    if ($pagestate == $round->page_save_state)
    {
        // Page comes from DONE.
        // When it was saved, the user's page-count was incremented.
        // Now they 'unsaving' it, so decrement their page-count.
        // They'll get it back if/when they save-as-done again.
        // (Plugs a former page-count cheat.)

        page_tallies_add( $round->id, $pguser, -1 );

        // no need to update text, just record state change
        Page_reopen( $project, $imagefile, $round, $pguser );
    }

    $saved = 1;
    $editone = 1;
    setPageCookie($project,$proofstate,$fileid,$imagefile,$pagestate,$saved,$editone,0,0,0,0);
}

// -----------------------------------------------------------------------------

function nPage_can_be_reverted_to_last_save( $npage )
{
    $round = get_Round_for_page_state( $npage['pagestate'] );

    return ( $npage['revert']==1
        || $npage['pagestate'] == $round->page_save_state
        || $npage['pagestate'] == $round->page_temp_state
    );
}

function nPage_getText( $npage )
{
    $round = get_Round_for_page_state( $npage['pagestate'] );

    if ( $npage['revert']==1
        || $npage['pagestate']==$round->page_avail_state
        || $npage['pagestate']==$round->page_out_state )
    {
        $desired_column_name = $round->prevtext_column_name;
    }
    else
    {
        $desired_column_name = $round->text_column_name;
    }

    $q = "
        SELECT $desired_column_name
        FROM {$npage['project']}
        WHERE image='{$npage['image']}'
    ";
    $res = mysql_query($q);
    list($page_text) = mysql_fetch_row($res);

    return $page_text;

    // old comment:
    //
    // We need to protect our users against malicious HTML code.
    // For example, see CERT Advisory CA-2000-02.
    // But we can't throw out all tags, because we use some tags, like <i>..</i>.
    // And we can't throw out all but the trusted tags, because there are other
    // uses of angle-brackets that don't look like trusted tags, e.g. X < Y
    // And really, we don't want to throw out *anything*, because there's
    // the risk that we might throw out a big chunk of non-malicious work.
    // So let's try this: anything that looks like it might be a nasty tag,
    // we convert its opening angle-bracket to '&lt' and mark it with an asterisk.
    // $page_text = preg_replace( ';<([\s/]*(SCRIPT|OBJECT|APPLET|EMBED));i', '&lt;*\1', $page_text );

    // Actually, this is all moot.
    // The value returned by this function will be sent to the client as the
    // content of a <textarea> element (the Proofreading Page's text frame),
    // which the browser won't interpret as HTML anyhow.
    // (That's why we see <i> *tags* rather than seeing italicized text.)
    // So just return the text as-is.
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class processpage
{
    var $project='';
    var $imagefile='';
    var $round=NULL;

    function processpage()
    {
    }

    function setPageState($pagestate,$project,$imagefile,$proofstate)
    {
        $this->project=$project;
        $this->imagefile=$imagefile;
        $this->pagestate=$pagestate;
        $this->proofstate=$proofstate;

        $this->round = get_Round_for_page_state($pagestate);
    }

    function addUserCount($pguser)
    {
        $sql = "SELECT state FROM {$this->project} WHERE image='{$this->imagefile}'";
        $result=mysql_query($sql);
        $rows=mysql_num_rows($result);
        if ($rows !=0)
        {
            $curState=mysql_result($result,0,'state');
            if ($curState == $this->round->page_out_state || $curState == $this->round->page_temp_state)
            {
                // add to user page count
                page_tallies_add( $this->round->id, $pguser, +1 );
            }
        }
    }


    function saveTemp($text_data,$pguser)
    {
        return Page_saveAsInProgress( $this->project, $this->imagefile, $this->round, $pguser, $text_data );
    }

    function saveComplete($text_data,$pguser)
    {
        $this->addUserCount($pguser);
        return Page_saveAsDone( $this->project, $this->imagefile, $this->round, $pguser, $text_data );
    }

    function getRevertState()
    {
        return $this->round->page_temp_state;
    }

    function returnPage($pguser)
    {
        return Page_returnToRound( $this->project, $this->imagefile, $this->round, $pguser );
    }

} // end class

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Functions that used to be in the 'processpage' class, but didn't need to be.

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

// vim: sw=4 ts=4 expandtab
?>
