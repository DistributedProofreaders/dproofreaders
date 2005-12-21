<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'prefs_options.inc'); // $f_f, $f_s
include_once($relPath.'c_pages.inc');

// Page-related code that's common to the standard and enhanced interfaces.

// "pi" = "proofing interface"

function url_for_pi_do_whichever_page( $projectid, $proj_state )
{
    global $code_url;
    return "$code_url/tools/proofers/proof.php"
        . "?"
        . "project=$projectid"
        . "&amp;"
        . "proofstate=$proj_state"
    ;
}

function url_for_pi_do_particular_page( $projectid, $proj_state, $imagefile, $page_state )
{
    global $code_url;
    return "$code_url/tools/proofers/proof.php"
        . "?"
        . "project=$projectid"
        . "&amp;"
        . "imagefile=$imagefile"
        . "&amp;"
        . "proofstate=$proj_state"
        . "&amp;"
        . "pagestate=$page_state"
    ;
}

// Note that proof.php and proof_frame.php take a 'project' parameter,
// but processtext.php etc take a 'projectname' parameter.

// -----------------------------------------------------------------------------

function get_requested_PPage( $request_params )
{
    $err = NULL;
    do // a poor man's try/catch stmt
    {
        foreach(
            array( 'projectname', 'proofstate', 'imagefile', 'pagestate', 'revert' )
            as
            $param_name
        )
        {
            if (!isset($request_params[$param_name]) )
            {
                $err = sprintf(
                    _("parameter '%s' is not set."),
                    $param_name );
                break;
            }
            else if ($request_params[$param_name] == '')
            {
                $err = sprintf(
                    _("parameter '%s' is empty."),
                    $param_name );
                break;
            }
        }

        $lpage = get_indicated_LPage(
            $request_params['projectname'],
            $request_params['proofstate'],
            $request_params['imagefile'],
            $request_params['pagestate'],
            $request_params['revert'],
            $err
        );

        if ( is_null($lpage) )
        {
            break;
        }

        return new PPage($lpage);
    }
    while(0);

    // -------------------------------------------------------------------------
    // We arrive here via a 'break' from inside the "loop".
    // I.e., an error has occurred.

    echo "<pre>\n";
    echo "get_requested_PPage detected a problem with your request.\n";
    echo "\n";
    echo "If you want to resolve, report, or get help on this incident,\n";
    echo "it may be useful to save the information below.\n";
    echo "\n";

    global $code_url;

    echo "Go to...\n";

    // link to project
    $projectid = @$request_params['projectname'];
    if ( $projectid != '' )
    {
        // This link won't work if the error is that the project doesn't exist.
        $link_url = "$code_url/project.php?id=$projectid";
        $link_text = _('Project Home');
        echo "    <a href='$link_url' target='_top'>$link_text</a>\n";
    }

    // link to round
    $round = get_Round_for_project_state( @$request_params['proofstate'] );
    if ( is_null($round) )
    {
        $round = get_Round_for_page_state( @$request_params['pagestate'] );
    }
    if ( !is_null($round) )
    {
        $link_url = "$code_url/tools/proofers/round.php?round_id={$round->id}";
        $link_text = $round->name;
        echo "    <a href='$link_url' target='_top'>$link_text</a>\n";
    }

    // link to activity hub
    $link_url = "$code_url/activity_hub.php";
    $link_text = _('Activity Hub');
    echo "    <a href='$link_url' target='_top'>$link_text</a>\n";
    echo "\n";

    echo "<hr>\n";
    $now = time(); $date = date('Y-m-d H:i:s',$now);
    echo "error message: $err\n";
    echo "\n";
    echo "time: $now ($date)\n";
    echo "username: {$GLOBALS['pguser']}\n";
    echo "requested path: {$_SERVER['PHP_SELF']}\n";

    // If this error occurred when they hit one of the submit buttons
    // in the proofing interface, echoing the request parameters will
    // give them a last chance to save the page text.
    echo "request parameters:\n";
    // var_dump($request_params);
    foreach( $request_params as $param_name => $param_value )
    {
        echo "    $param_name = ";
        if ( is_string($param_value) )
        {
            if ( get_magic_quotes_gpc() )
            {
                $slashed_param_value = $param_value;
                $unslashed_param_value = stripslashes($param_value);
            }
            else
            {
                $unslashed_param_value = $param_value;
                $slashed_param_value = addslashes($param_value);
            }
            if ( strpos($param_value, "\n") )
            {
                echo "'''\n";
                echo $unslashed_param_value, "\n";
                echo "'''\n";
            }
            else
            {
                echo "'$slashed_param_value'\n";
            }
        }
        else
        {
            // might be an array.
            // This will be ugly, but I don't care.
            var_dump($param_value);
        }
    }
    echo "</pre>\n";

    exit;
}

// ---------------------------------------------------------

class PPage
// The initial 'P' is for 'Presentation' (i.e., user interface).
{
    function PPage( &$lpage )
    {
        $this->lpage =& $lpage;
    }

    // -----------------------------------------------------

    function url_for_image()
    {
        global $projects_url;
        return "$projects_url/{$this->lpage->projectid}/{$this->lpage->imagefile}";
    }

    function url_for_do_another_page()
    {
        global $code_url;
        return "$code_url/tools/proofers/proof_frame.php"
            . "?"
            . "project={$this->lpage->projectid}"
            . "&amp;"
            . "proofstate={$this->lpage->proj_state}";
    }

    function url_for_project_comments()
    {
        global $code_url;
        return "$code_url/project.php"
            . "?"
            . "id={$this->lpage->projectid}"
            . "&amp;"
            . "expected_state={$this->lpage->proj_state}"
            . "&amp;"
            . "detail_level=1";
    }

    function urlencoded()
    {
        return implode( '&',
            array(
                'projectname=' . $this->lpage->projectid,
                'proofstate='  . $this->lpage->proj_state,
                'imagefile='   . $this->lpage->imagefile,
                'pagestate='   . $this->lpage->page_state,
                'revert='      . $this->lpage->reverting_to_orig,
            )
        );
    }

    // -----------------------------------------------------

    function echo_hidden_fields()
    {
        foreach ( array(
                'imagefile'   => $this->lpage->imagefile,
                'proofstate'  => $this->lpage->proj_state,
                'pagestate'   => $this->lpage->page_state,
                'projectname' => $this->lpage->projectid,
                'revert'      => $this->lpage->reverting_to_orig,
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
        global $forums_url;

        //display page number
        $pageNum=substr($this->lpage->imagefile,0,-4);
        echo "<font size='-1'><b>";
        echo _("Page: $pageNum");

        $round = $this->lpage->round;
        $other_round_ids = $round->other_rounds_with_visible_usernames;
        if (count($other_round_ids) > 0)
        {
            echo " -- ";

            $show_comma = FALSE;
            foreach ($other_round_ids as $other_round_id)
            {
                $other_round = get_Round_for_round_id($other_round_id);
                $username = $this->lpage->get_username_for_round( $other_round );

                if ($show_comma) { echo ","; } else { $show_comma = TRUE; }
                echo "$other_round->id: ";

                if ( $username == '' )
                {
                    // e.g., the project might have skipped $other_round,
                    // or it could be a future round.
                    echo "[none]";
                }
                else
                {
                    $res = mysql_query("
                        SELECT user_id
                        FROM phpbb_users
                        WHERE username = '$username'
                    ");
                    $user_id = mysql_result($res, 0, "user_id");

                    echo "<a href='$forums_url/privmsg.php?mode=post&u=$user_id' target='comments'>$username</a>";
                }
            }
        }
        echo "</b></font>\n";
    }

    function echo_proofing_textarea( $enhanced )
    {
        global $userP, $f_f, $f_s;

        $page_text = $this->lpage->get_text();

        $lang = $this->lpage->get_language();

        if ( $userP['i_layout']==1 )
        {
            // "vertical"
            $n_cols      = $userP['v_tchars'];
            $n_rows      = $userP['v_tlines'];
            $line_wrap   = $userP['v_twrap'];
            $font_face_i = $userP['v_fntf'];
            $font_size_i = $userP['v_fnts'];
        }
        else
        {
            // "horizontal"
            $n_cols      = $userP['h_tchars'];
            $n_rows      = $userP['h_tlines'];
            $line_wrap   = $userP['h_twrap'];
            $font_face_i = $userP['h_fntf'];
            $font_size_i = $userP['h_fnts'];
        }

        echo "<textarea
            name='text_data'
            id='text_data'
            cols='$n_cols'
            rows='$n_rows'
            dir='".lang_direction($lang)."'
        ";

        $font_face = $f_f[$font_face_i];
        $font_size = $f_s[$font_size_i];
        echo "style='";
        if ( $font_face != '' && $font_face != BROWSER_DEFAULT_STR )
        {
            echo "font-family: $font_face;";
            echo " ";
        }
        if ( $font_size != '' && $font_size != BROWSER_DEFAULT_STR )
        {
            echo "font-size: $font_size;";
        }
        echo "padding-left: 0.25em;' ";

        if ( !$line_wrap )
        {
            echo "wrap='off' ";
        }

        if ( $enhanced )
        {
            echo "
                onselect='getCurSel()'
                onclick='getCurCaret()'
                onkeyup='getCurCaret()'
            ";
        }

        echo ">\n";

        // SENDING PAGE-TEXT TO USER
        // We're sending it in an HTML document, so encode special characters.
        echo htmlspecialchars( $page_text, ENT_NOQUOTES );

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

    function saveAsDone( $page_text, $user )
    {
        $this->lpage->saveAsDone( $page_text, $user );
    }

    function can_be_marked_bad_by( $user )
    {
        return $this->lpage->can_be_marked_bad_by( $user );
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
