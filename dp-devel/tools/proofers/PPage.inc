<?PHP
include_once($relPath.'v_site.inc');

// Page-related code that's common to the standard and enhanced interfaces.


function echo_hidden_fields( $npage )
{
    // get page language
    $res = mysql_query("SELECT language FROM projects WHERE projectid='{$npage['project']}'");
    $lang = proj_lang_code(mysql_result($res,0,"language"),"primary");

    // set values for hidden form fields
    $sHidFS="<input type='hidden' value='";
    $sHidFM="' name='";
    $sHidFM2="' id='";
    $sHidFE="'>\n";

    echo $sHidFS.$npage['image']     .$sHidFM.'imagefile'  .$sHidFM2.'imagefile'  .$sHidFE;
    echo $sHidFS.$npage['fileid']    .$sHidFM.'fileid'     .$sHidFM2.'fileid'     .$sHidFE;
    echo $sHidFS.$npage['proofstate'].$sHidFM.'proofstate' .$sHidFM2.'proofstate' .$sHidFE;
    echo $sHidFS.$npage['pagestate'] .$sHidFM.'pagestate'  .$sHidFM2.'pagestate'  .$sHidFE;
    echo $sHidFS.$npage['project']   .$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
    echo $sHidFS.$lang               .$sHidFM.'lang'       .$sHidFM2.'lang'       .$sHidFE;
}

function ReportBadPage_button_should_be_shown( $npage, $username )
{
    // Find out how many pages the user has proofread so far.
    $pagessql = "SELECT pagescompleted FROM users WHERE username = '$username' LIMIT 1";
    $res1 = mysql_query($pagessql);
    $pagescompleted = mysql_result($res1, 0, "pagescompleted");

    // get bad page code
    $res2 = mysql_query("SELECT b_code FROM {$npage['project']} WHERE image = '{$npage['image']}' AND fileid='{$npage['fileid']}'");
    $b_code = mysql_result($res2, 0, "b_code");

    return ((($npage['proofstate'] == PROJ_P1_AVAILABLE) && ($pagescompleted >= 50)) || ($b_code > 0));
}

function echo_page_info( $npage )
{
    global $forums_url;

    //display page number
    $pageNum=substr($npage['image'],0,-4);
    echo "<font size='-1'><b>";
    echo _("Page: $pageNum");

    $prd = get_PRD_for_project_state($npage['proofstate']);
    $other_prds = $prd->other_rounds_with_visible_usernames;
    if (count($other_prds) > 0)
    {
        echo " - ";
        echo _("Proofread by:");

        $show_comma = FALSE;
        foreach ($other_prds as $other_prd)
        {
            $res = mysql_query("SELECT {$other_prd->user_column_name} FROM {$npage['project']} WHERE image = '{$npage['image']}' AND fileid='{$npage['fileid']}'");
            $username = mysql_result($res, 0, $other_prd->user_column_name);

            $res = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
            $user_id = mysql_result($res, 0, "user_id");

            if ($show_comma) { echo ","; } else { $show_comma = TRUE; }
            echo " <a href='$forums_url/privmsg.php?mode=post&u=$user_id' target='comments'>$username</a>";
        }
    }
    echo "</b></font>\n";
}

function project_comments_url( $npage )
{
    return "projects.php?project={$npage['project']}&amp;proofstate={$npage['proofstate']}&amp;proofing=1";
}

function page_image_url( $npage )
{
    global $projects_url;
    return "$projects_url/{$npage['project']}/{$npage['image']}";
}

// vim: sw=4 ts=4 expandtab
?>
