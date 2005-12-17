<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'prefs_options.inc'); // $f_f, $f_s
include_once($relPath.'c_pages.inc');

// Page-related code that's common to the standard and enhanced interfaces.

function get_requested_PPage( $request_params=NULL )
{
    $ppage = new PPage();

    $ppage->npage = array(
        'project'    => $request_params['projectname'],
        'proofstate' => $request_params['proofstate'],
        'image'      => $request_params['imagefile'],
        'pagestate'  => $request_params['pagestate'],
        'revert'     => $request_params['revert'],
    );

    if ( !is_null($request_params) )
    {
        $ppage->lpage = new LPage(
            $request_params['projectname'],
            $request_params['proofstate'],
            $request_params['imagefile'],
            $request_params['pagestate']
        );
    }

    return $ppage;
}

// ---------------------------------------------------------

class PPage
// The initial 'P' is for 'Presentation' (i.e., user interface).
{

    function revertToOriginal()
    {
        $this->npage['revert'] = 1;
    }

    function revertToSaved()
    {
        $this->npage['pagestate'] = $this->lpage->getRevertState();
        $this->npage['revert'] = 0;
    }

    function returnToRound( $user )
    {
        $this->lpage->returnPage($user);
    }

    function saveAsInProgress( $page_text, $user )
    {
        $this->npage['pagestate'] = $this->lpage->saveTemp($page_text, $user);
        $this->npage['revert'] = 0;
    }

    function saveAsDone( $page_text, $user )
    {
        $this->lpage->saveComplete($page_text, $user);
    }

    // -----------------------------------------------------

    function image_url()
    {
        global $projects_url;
        return "$projects_url/{$this->npage['project']}/{$this->npage['image']}";
    }

    function project_comments_url()
    {
        global $code_url;
        return "$code_url/project.php?id={$this->npage['project']}&amp;expected_state={$this->npage['proofstate']}&amp;detail_level=1";
    }

    function urlencoded()
    {
        return implode( '&',
            array(
                'projectname=' . $this->npage['project'],
                'proofstate='  . $this->npage['proofstate'],
                'imagefile='   . $this->npage['image'],
                'pagestate='   . $this->npage['pagestate'],
                'revert='      . $this->npage['revert'],
            )
        );
    }

    // -----------------------------------------------------

    function can_be_marked_bad_by( $username )
    {
        // get bad page code
        $res2 = mysql_query("
            SELECT b_code
            FROM {$this->npage['project']}
            WHERE image = '{$this->npage['image']}'
        ");
        $b_code = mysql_result($res2, 0, "b_code");

        return (($this->npage['proofstate'] == PROJ_P1_AVAILABLE) || ($b_code > 0));
    }

    function can_be_reverted_to_last_save()
    {
        return nPage_can_be_reverted_to_last_save($this->npage);
    }

    // -----------------------------------------------------

    function echo_hidden_fields()
    {
        // set values for hidden form fields
        $sHidFS="<input type='hidden' value='";
        $sHidFM="' name='";
        $sHidFM2="' id='";
        $sHidFE="'>\n";

        echo $sHidFS.$this->npage['image']     .$sHidFM.'imagefile'  .$sHidFM2.'imagefile'  .$sHidFE;
        echo $sHidFS.$this->npage['proofstate'].$sHidFM.'proofstate' .$sHidFM2.'proofstate' .$sHidFE;
        echo $sHidFS.$this->npage['pagestate'] .$sHidFM.'pagestate'  .$sHidFM2.'pagestate'  .$sHidFE;
        echo $sHidFS.$this->npage['project']   .$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
        echo $sHidFS.$this->npage['revert']    .$sHidFM.'revert'     .$sHidFM2.'revert'     .$sHidFE;
    }

    function echo_info()
    {
        global $forums_url;

        //display page number
        $pageNum=substr($this->npage['image'],0,-4);
        echo "<font size='-1'><b>";
        echo _("Page: $pageNum");

        $round = get_Round_for_project_state($this->npage['proofstate']);
        $other_round_ids = $round->other_rounds_with_visible_usernames;
        if (count($other_round_ids) > 0)
        {
            echo " -- ";

            $show_comma = FALSE;
            foreach ($other_round_ids as $other_round_id)
            {
                $other_round = get_Round_for_round_id($other_round_id);
                $res = mysql_query("
                    SELECT {$other_round->user_column_name}
                    FROM {$this->npage['project']}
                    WHERE image = '{$this->npage['image']}'
                ");
                $username = mysql_result($res, 0, $other_round->user_column_name);

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

        $page_text = nPage_getText($this->npage);

        // get page language
        $res = mysql_query("SELECT language FROM projects WHERE projectid='{$this->npage['project']}'");
        $lang = proj_lang_code(mysql_result($res,0,"language"),"primary");

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

}

// vim: sw=4 ts=4 expandtab
?>
