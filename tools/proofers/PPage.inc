<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'Project.inc');         // $PROJECT_STATES_IN_ORDER
include_once($relPath.'RoundDescriptor.inc'); // $PAGE_STATES_IN_ORDER
include_once($relPath.'page_tally.inc');
include_once($relPath.'prefs_options.inc'); // get_user_proofreading_font()
include_once($relPath.'LPage.inc');
include_once($relPath.'abort.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'links.inc'); // private_message_link()
include_once($relPath.'forum_interface.inc'); // get_forum_user_id()
include_once($relPath.'misc.inc'); // get_enumerated_param()

// Page-related code that's common to the standard and enhanced interfaces.

// "pi" = "proofing interface"

function url_for_pi_do_whichever_page( $projectid, $proj_state, $escape_amp=FALSE )
{
    global $code_url;
    if($escape_amp) $amp = '&amp;';
    else $amp = '&';
    return "$code_url/tools/proofers/proof.php"
        . "?"
        . "projectid=$projectid"
        . $amp
        . "proj_state=$proj_state"
    ;
}

function url_for_pi_do_particular_page( $projectid, $proj_state, $imagefile, $page_state, $escape_amp=FALSE )
{
    global $code_url;
    if($escape_amp) $amp = '&amp;';
    else $amp = '&';
    return "$code_url/tools/proofers/proof.php"
        . "?"
        . "projectid=$projectid"
        . $amp
        . "imagefile=$imagefile"
        . $amp
        . "proj_state=$proj_state"
        . $amp
        . "page_state=$page_state"
    ;
}

// -----------------------------------------------------------------------------

function get_requested_PPage( $request_params )
{
    global $PROJECT_STATES_IN_ORDER, $PAGE_STATES_IN_ORDER;
    $projectid  = validate_projectID('projectid', @$request_params['projectid']);
    $proj_state = get_enumerated_param($request_params, 'proj_state', null, $PROJECT_STATES_IN_ORDER);
    $imagefile  = validate_page_image_filename('imagefile', @$request_params['imagefile']);
    $page_state = get_enumerated_param($request_params, 'page_state', null, $PAGE_STATES_IN_ORDER);
    $reverting  = get_integer_param($request_params, 'reverting', 0, 0, 1);

    $lpage = get_indicated_LPage(
        $projectid,$proj_state, $imagefile, $page_state, $reverting
    );

    if (is_null($lpage))
    {
        abort($err);
    }

    return new PPage($lpage, $proj_state);
}

// ---------------------------------------------------------

class PPage
// The initial 'P' is for 'Presentation' (i.e., user interface).
{
    function __construct( &$lpage, $proj_state )
    {
        $this->lpage =& $lpage;
        $this->proj_state = $proj_state;
    }

    // -----------------------------------------------------

    function url_for_image($escape_amp=FALSE)
    {
        global $projects_url;
        return "$projects_url/{$this->lpage->projectid}/{$this->lpage->imagefile}";
    }

    function url_for_display_image($escape_amp=FALSE)
    {
        global $code_url;
        if($escape_amp) $amp = '&amp;';
        else $amp = '&';
        return "$code_url/tools/project_manager/displayimage.php"
            . "?project={$this->lpage->projectid}"
            . $amp
            . "imagefile={$this->lpage->imagefile}";
    }

    function url_for_do_another_page($escape_amp=FALSE)
    {
        global $code_url;
        if($escape_amp) $amp = '&amp;';
        else $amp = '&';
        return "$code_url/tools/proofers/proof_frame.php"
            . "?"
            . "projectid={$this->lpage->projectid}"
            . $amp
            . "proj_state={$this->proj_state}";
    }

    function url_for_project_comments($escape_amp=FALSE)
    {
        global $code_url;
        if($escape_amp) $amp = '&amp;';
        else $amp = '&';
        return "$code_url/project.php"
            . "?"
            . "id={$this->lpage->projectid}"
            . $amp
            . "expected_state={$this->proj_state}"
            . $amp
            . "detail_level=1";
    }

    function urlencoded($escape_amp=FALSE)
    {
        if($escape_amp) $amp = '&amp;';
        else $amp = '&';
        return implode( $amp,
            array(
                'projectid='  . $this->lpage->projectid,
                'proj_state=' . $this->proj_state,
                'imagefile='  . $this->lpage->imagefile,
                'page_state=' . $this->lpage->page_state,
                'reverting='  . $this->lpage->reverting_to_orig,
            )
        );
    }

    // -----------------------------------------------------

    function echo_hidden_fields()
    {
        foreach ( array(
                'imagefile'   => $this->lpage->imagefile,
                'proj_state'  => $this->proj_state,
                'page_state'  => $this->lpage->page_state,
                'projectid'   => $this->lpage->projectid,
                'reverting'   => $this->lpage->reverting_to_orig,
            )
            as
            $param_name => $param_value
        )
        {
            echo "<input type='hidden' value='$param_value' name='$param_name' id='$param_name'>\n";
        }
    }

    function echo_info()
    {
        //display page number
        $pageNum=substr($this->lpage->imagefile,0,-4);
        echo "<span class='small bold'>";
        echo sprintf(_("Page: %s"), $pageNum);

        $round = $this->lpage->round;
        $other_round_ids = $round->other_rounds_with_visible_usernames;
        if (count($other_round_ids) > 0)
        {
            echo " &mdash; ";

            $show_comma = FALSE;
            foreach ($other_round_ids as $other_round_id)
            {
                $other_round = get_Round_for_round_id($other_round_id);
                $username = $this->lpage->get_username_for_round( $other_round );

                if ($show_comma) { echo ", "; } else { $show_comma = TRUE; }
                echo "$other_round->id: ";

                $user_id = get_forum_user_id($username);

                if ( $username == '' || $user_id == NULL )
                {
                    // Grey, because it's not the link expected by the user.
                    echo "<span style='color: #666;'>";
                    if ($username == '')
                    {
                        // e.g., the project might have skipped $other_round,
                        // or it could be a future round.
                        echo "<i>" . pgettext("no user", "none") . "</i>";
                    }
                    else
                        echo $username;
                    echo "</span>";
                }
                else
                {
                    echo private_message_link($username, "comments");
                }
            }
        }
        echo "</span>\n";
    }

    function echo_proofing_textarea()
    {
        global $userP;

        $page_text = $this->lpage->get_text();

        $lang = $this->lpage->get_language();

        if ( $userP['i_layout']==1 )
        {
            // "vertical"
            $n_cols      = $userP['v_tchars'];
            $n_rows      = $userP['v_tlines'];
            $line_wrap   = $userP['v_twrap'];
        }
        else
        {
            // "horizontal"
            $n_cols      = $userP['h_tchars'];
            $n_rows      = $userP['h_tlines'];
            $line_wrap   = $userP['h_twrap'];
        }

        echo "<textarea
            name='text_data'
            id='text_data'
            cols='$n_cols'
            rows='$n_rows'
            dir='".lang_direction($lang)."'
        ";

        list( , $font_size, $font_family) = get_user_proofreading_font();
        $font_size_string = '';
        if ( $font_size != '' )
        {
            $font_size_string = "font-size: $font_size;";
        }
        echo "style=\"font-family: $font_family; $font_size_string padding-left: 0.25em;\" ";

        if ( !$line_wrap )
        {
            echo "wrap='off' ";
        }

        echo ">\n";

        // SENDING PAGE-TEXT TO USER
        // We're sending it in an HTML document, so encode special characters.
        echo html_safe($page_text);

        echo "</textarea>";
    }

    // -------------------------------------------------------------------------

    // The remaining functions just delegate to $this->lpage...

    function revertToOriginal()
    {
        $this->lpage->revertToOriginal();
    }

    function revertToSaved()
    {
        $this->lpage->revertToSaved();
    }

    function returnToRound( $user )
    {
        $this->lpage->returnToRound( $user );
    }

    function markAsBad( $user, $reason )
    {
        return $this->lpage->markAsBad( $user, $reason );
    }

    function saveAsInProgress( $page_text, $user )
    {
        $this->lpage->saveAsInProgress( $page_text, $user );
    }

    function attemptSaveAsDone( $page_text, $user )
    {
        return $this->lpage->attemptSaveAsDone( $page_text, $user );
    }

    function can_be_reverted_to_last_save()
    {
        return $this->lpage->can_be_reverted_to_last_save();
    }

    function projectid()
    {
        return $this->lpage->projectid;
    }

    function imagefile()
    {
        return $this->lpage->imagefile;
    }

}

// vim: sw=4 ts=4 expandtab
?>
