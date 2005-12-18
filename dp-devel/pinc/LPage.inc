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

function get_available_page( $projectid, $proj_state, $pguser, &$err )
// Returns an LPage, unless no page is available,
// in which case returns NULL and sets $err.
{
    $round = get_Round_for_project_state($proj_state);

    // Normally, pages are served in order of "page number"
    // (i.e., by the 'image' field)
    $order = 'image ASC';
    // but this can be overridden below.

    // Projects with difficulty='beginner' are treated differently in
    // various ways, so ascertain whether this is a beginner project.

    $res1 = mysql_query("
        SELECT difficulty
        FROM projects
        WHERE projectid='$projectid'
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
                FROM $projectid
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
    $dbQuery = "SELECT image, state FROM $projectid WHERE state='{$round->page_avail_state}'";
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

    $page_state = Page_checkout( $projectid, $imagefile, $round, $pguser );

    $err = NULL;
    return new LPage( $projectid, $proj_state, $imagefile, $page_state, 0 );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class LPage
// The "L" stands for "Logic",
// as in the 3 tiers of Data, Logic, and Presentation.
// (For pages, the Data layer is pinc/page_ops.inc,
// and the Presentation layer is most of tools/proofers/*.
// However, we're not very strict about the divisions yet.)
{
    function LPage($projectid, $proj_state, $imagefile, $page_state, $reverting_to_orig)
    {
        $this->projectid  = $projectid;
        $this->proj_state = $proj_state;
        $this->imagefile  = $imagefile;
        $this->page_state = $page_state;
        $this->reverting_to_orig = $reverting_to_orig;

        // It's debateable whether LPage should have a 'reverting_to_orig' property.
        // You could argue that it's an interface-level artifact,
        // and thus belongs in PPage.

        $this->round = get_Round_for_page_state($page_state);
    }

    // -------------------------------------------------------------------------

    function can_be_marked_bad_by( $username )
    {
        // get bad page code
        $res2 = mysql_query("
            SELECT b_code
            FROM {$this->projectid}
            WHERE image = '{$this->imagefile}'
        ");
        $b_code = mysql_result($res2, 0, "b_code");

        return (($this->proj_state == PROJ_P1_AVAILABLE) || ($b_code > 0));
    }

    function can_be_reverted_to_last_save()
    {
        $round = get_Round_for_page_state( $this->page_state );

        return ( $this->reverting_to_orig==1
            || $this->page_state == $round->page_save_state
            || $this->page_state == $round->page_temp_state
        );
    }

    function get_text()
    {
        $round = get_Round_for_page_state( $this->page_state );

        if ( $this->reverting_to_orig==1
            || $this->page_state == $round->page_avail_state
            || $this->page_state == $round->page_out_state )
        {
            $desired_column_name = $round->prevtext_column_name;
        }
        else
        {
            $desired_column_name = $round->text_column_name;
        }

        $q = "
            SELECT $desired_column_name
            FROM {$this->projectid}
            WHERE image='{$this->imagefile}'
        ";
        $res = mysql_query($q);
        list($page_text) = mysql_fetch_row($res);

        return $page_text;
    }

    function get_language()
    {
        $res = mysql_query("SELECT language FROM projects WHERE projectid='{$this->projectid}'");
        $lang = proj_lang_code(mysql_result($res,0,"language"),"primary");
    }

    function get_username_for_round( $round )
    {
        $res = mysql_query("
            SELECT {$round->user_column_name}
            FROM {$this->projectid}
            WHERE image = '{$this->imagefile}'
        ");
        $username = mysql_result($res, 0, $round->user_column_name);
        return $username;
    }

    // -------------------------------------------------------------------------

    function addUserCount($pguser)
    {
        $sql = "SELECT state FROM {$this->projectid} WHERE image='{$this->imagefile}'";
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


    function saveAsInProgress( $page_text, $user )
    {
        $this->page_state = Page_saveAsInProgress( $this->projectid, $this->imagefile, $this->round, $user, $page_text );
        $this->reverting_to_orig = 0;
    }

    function saveAsDone($text_data,$pguser)
    {
        $this->addUserCount($pguser);
        return Page_saveAsDone( $this->projectid, $this->imagefile, $this->round, $pguser, $text_data );
    }

    function returnToRound($pguser)
    {
        return Page_returnToRound( $this->projectid, $this->imagefile, $this->round, $pguser );
    }

    function revertToOriginal()
    {
        $this->reverting_to_orig = 1;
    }

    function revertToSaved()
    {
        $this->page_state = $this->round->page_temp_state;
        $this->reverting_to_orig = 0;
    }

    // -----------------------------------------------------------------------------

    function resume_saved_page( $pguser )
    {
        $round = get_Round_for_project_state($this->proj_state);

        $res = mysql_query("
            SELECT state, {$round->user_column_name}
            FROM $this->projectid
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

        if ($this->page_state != $current_page_state)
        {
            $err = sprintf(
                _("Page %s has changed state from '%s' to '%s', so your request is invalid."),
                $this->imagefile, $this->page_state, $current_page_state );
            return $err;
        }

        if ($this->page_state == $round->page_save_state)
        {
            // Page comes from DONE.
            // When it was saved, the user's page-count was incremented.
            // Now they 'unsaving' it, so decrement their page-count.
            // They'll get it back if/when they save-as-done again.
            // (Plugs a former page-count cheat.)

            page_tallies_add( $round->id, $pguser, -1 );

            // no need to update text, just record state change
            $this->page_state = Page_reopen( $this->projectid, $this->imagefile, $round, $pguser );
        }
    }

} // end class

// vim: sw=4 ts=4 expandtab
?>
