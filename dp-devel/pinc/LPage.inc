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

function get_available_page( $project, $proofstate, $pguser, &$err )
// Returns an LPage, unless no page is available,
// in which case returns NULL and sets $err.
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
                return NULL;
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
                return NULL;
            }
        }
        else if ($round->round_number == 2)
        {
            if ( !user_can_work_on_beginner_pages_in_round($round) )
            {
                $err = _("You do not have access to difficulty='beginner' projects in this round.");
                return NULL;
            }

            // For beginner projects in P2,
            // we serve up pages in order of the P1 proofer.
            // (So that mentors can easily get all the pages by one proofer.)
            $order = 'round1_user ASC, image ASC';
        }
    }

    // Find page to be proofed.
    // (It appears that a simultaneous query from someone else would return the same page.)
    $dbQuery = "SELECT image, state FROM $project WHERE state='{$round->page_avail_state}'";
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
        return NULL;
    }
    $npage = mysql_fetch_assoc($result);

    $imagefile = $npage['image'];

    $pagestate = Page_checkout( $project, $imagefile, $round, $pguser );

    $err = NULL;
    return new LPage( $project, $proofstate, $imagefile, $pagestate );
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

class LPage
// The "L" stands for "Logic",
// as in the 3 tiers of Data, Logic, and Presentation.
// (For pages, the Data layer is pinc/page_ops.inc,
// and the Presentation layer is most of tools/proofers/*.
// However, we're not very strict about the divisions yet.)
{
    var $project='';
    var $imagefile='';
    var $round=NULL;

    function LPage($project, $proofstate, $imagefile, $pagestate)
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

    // -----------------------------------------------------------------------------

    function resume_saved_page( $pguser )
    {
        $round = get_Round_for_project_state($this->proofstate);

        $res = mysql_query("
            SELECT state, {$round->user_column_name}
            FROM $this->project
            WHERE image='$this->imagefile'
        ");
        if (mysql_num_rows($res) == 0)
        {
            // This should only happen if they've been tweaking URLs.
            $err = _('No such page.');
            return $err;
        }

        list($current_page_state, $current_round_user) = mysql_fetch_row($res);

        if ($pguser != $current_round_user)
        {
            $err = sprintf(
                _("You (%s) do not have the necessary access to page %s"),
                $pguser, $this->imagefile );
            return $err;
        }

        if ($this->pagestate != $current_page_state)
        {
            $err = sprintf(
                _("Page %s has changed state from '%s' to '%s', so your request is invalid."),
                $this->imagefile, $this->pagestate, $current_page_state );
            return $err;
        }

        if ($this->pagestate == $round->page_save_state)
        {
            // Page comes from DONE.
            // When it was saved, the user's page-count was incremented.
            // Now they 'unsaving' it, so decrement their page-count.
            // They'll get it back if/when they save-as-done again.
            // (Plugs a former page-count cheat.)

            page_tallies_add( $round->id, $pguser, -1 );

            // no need to update text, just record state change
            $this->pagestate = Page_reopen( $this->project, $this->imagefile, $round, $pguser );
        }
    }

} // end class

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Functions that used to be in the 'LPage' class, but didn't need to be.

function setPageCookie($project,$proofstate,$image,$pagestate,$revert)
{
  // The following components are no longer used,
  // so it doesn't matter what values we use for them,
  // and we don't need to get values via the arg list.
  $fileid = $saved = $editone = $spcheck = $errcheck = $isDone = '';

  $pagerefs="$project|$proofstate|$fileid|$image|$pagestate|$saved|$editone|$revert|$spcheck|$errcheck|$isDone|".time();
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

// vim: sw=4 ts=4 expandtab
?>
