<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'prefs_options.inc'); // $f_f, $f_s

// Page-related code that's common to the standard and enhanced interfaces.

    // -----------------------------------------------------

    function page_image_url( $npage )
    {
        global $projects_url;
        return "$projects_url/{$npage['project']}/{$npage['image']}";
    }

    function project_comments_url( $npage )
    {
        global $code_url;
        return "$code_url/project.php?id={$npage['project']}&amp;expected_state={$npage['proofstate']}&amp;detail_level=1";
    }

    // -----------------------------------------------------

    function ReportBadPage_button_should_be_shown( $npage, $username )
    {
        // get bad page code
        $res2 = mysql_query("SELECT b_code FROM {$npage['project']} WHERE image = '{$npage['image']}'");
        $b_code = mysql_result($res2, 0, "b_code");

        return (($npage['proofstate'] == PROJ_P1_AVAILABLE) || ($b_code > 0));
    }

    // -----------------------------------------------------

    function echo_hidden_fields( $npage )
    {
        // set values for hidden form fields
        $sHidFS="<input type='hidden' value='";
        $sHidFM="' name='";
        $sHidFM2="' id='";
        $sHidFE="'>\n";

        echo $sHidFS.$npage['image']     .$sHidFM.'imagefile'  .$sHidFM2.'imagefile'  .$sHidFE;
        echo $sHidFS.$npage['proofstate'].$sHidFM.'proofstate' .$sHidFM2.'proofstate' .$sHidFE;
        echo $sHidFS.$npage['pagestate'] .$sHidFM.'pagestate'  .$sHidFM2.'pagestate'  .$sHidFE;
        echo $sHidFS.$npage['project']   .$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
    }

    function echo_page_info( $npage )
    {
        global $forums_url;

        //display page number
        $pageNum=substr($npage['image'],0,-4);
        echo "<font size='-1'><b>";
        echo _("Page: $pageNum");

        $round = get_Round_for_project_state($npage['proofstate']);
        $other_round_ids = $round->other_rounds_with_visible_usernames;
        if (count($other_round_ids) > 0)
        {
            echo " -- ";

            $show_comma = FALSE;
            foreach ($other_round_ids as $other_round_id)
            {
                $other_round = get_Round_for_round_id($other_round_id);
                $res = mysql_query("SELECT {$other_round->user_column_name} FROM {$npage['project']} WHERE image = '{$npage['image']}'");
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
                    $res = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
                    $user_id = mysql_result($res, 0, "user_id");

                    echo "<a href='$forums_url/privmsg.php?mode=post&u=$user_id' target='comments'>$username</a>";
                }
            }
        }
        echo "</b></font>\n";
    }

    function echo_proofing_textarea( $npage, $enhanced )
    {
        global $userP, $f_f, $f_s;

        $page_text = nPage_getText($npage);

        // get page language
        $res = mysql_query("SELECT language FROM projects WHERE projectid='{$npage['project']}'");
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

// vim: sw=4 ts=4 expandtab
?>
