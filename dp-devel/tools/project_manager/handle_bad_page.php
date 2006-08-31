<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'stages.inc');

if (!isset($_POST['resolution'])) {
    //Get variables to use for form
    $projectid = $_GET['projectid'];
    $image = $_GET['image'];
    if (!isset($projectid)) {
        $projectid = $_POST['projectid'];
        $image = $_POST['image'];
    }

    //Find out information about the bad page report
    $result = mysql_query("SELECT * FROM $projectid WHERE image='$image'");
    $page = mysql_fetch_assoc($result);
    $state  = $page['state'];
    $b_User = $page['b_user'];
    $b_Code = $page['b_code'];

    $result = mysql_query("SELECT * FROM projects WHERE projectid='$projectid'");
    $b_NameofWork = mysql_result($result,0,"nameofwork");
    
    $round = get_Round_for_page_state($state);

    //Display form
    $header = _("Bad Page Report");
    theme($header, "header");

    echo "<br><h3>Project/Page: ".$b_NameofWork."&mdash;".$image."</h3>";
    echo "<h3>State: ".$state."</h3>";

    echo "<form action='handle_bad_page.php' method='post'>";
    echo "<input type='hidden' name='projectid' value='$projectid'>";
    echo "<input type='hidden' name='image' value='$image'>";
    echo "<input type='hidden' name='state' value='$state'>";
    echo "<br><div align='center'><table bgcolor='".$theme['color_mainbody_bg']."' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'>";
    echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='2' align='center'>";
    echo "<B><font color='".$theme['color_headerbar_font']."'>Bad Page Report</font></B></td></tr>";
    
    if (!empty($b_User)) {
        //Get the user id of the reporting user to be used for private messaging
        $result = mysql_query("SELECT * FROM phpbb_users WHERE username='$b_User'");
        $b_UserID = mysql_result($result,0,"user_id");

        echo "<tr><td bgcolor='$theme[color_logobar_bg]' align='left'>";
        echo "<strong>Username:</strong></td>";
        echo "<td bgcolor='#ffffff' align='center'>";
        echo "$b_User (<a href='$forums_url/privmsg.php?mode=post&u=$b_UserID'>Private Message</a>)</td></tr>";
    }
    
    if (!empty($b_Code)) {
        echo "<tr><td bgcolor='$theme[color_logobar_bg]' align='left'>";
        echo "<strong>Reason:</strong></td>";
        echo "<td bgcolor='#ffffff' align='center'>";
        echo $PAGE_BADNESS_REASONS[$b_Code]."</td></tr>";
    }

    // It's a bit messy to have this here,
    // since it reiterates stuff that appears in other files,
    // but this page is kind of messy to begin with.
    // It'll get cleaned up eventually.
    for ( $prev_round_num = $round->round_number-1; $prev_round_num > 0; $prev_round_num-- )
    {
        $r = get_Round_for_round_number($prev_round_num);
        if ( $page[$r->user_column_name] != '' )
        {
            $prevtext_column = $r->text_column_name;
            break;
        }
    }
    if ( $prev_round_num == 0 )
    {
        $prevtext_column = 'master_text';
    }

    echo "<tr><td bgcolor='$theme[color_logobar_bg]' align='left'>";
    echo "<strong>Originals:</strong></td>";
    echo "<td bgcolor='#ffffff' align='center'>";
    echo "<a href='downloadproofed.php?project=$projectid&image=$image&round_num=$prev_round_num' target='_new'>View Text</a>";
    echo " | ";
    echo "<a href='displayimage.php?project=$projectid&imagefile=$image' target='_new'>View Image</a>";
    echo "</td></tr>";
    echo "<tr><td bgcolor='$theme[color_logobar_bg]' align='left'>";

    echo "<strong>Modify:</strong></td>";
    echo "<td bgcolor='#ffffff' align='center'>";
    echo "<a href='handle_bad_page.php?projectid=$projectid&image=$image&modify=text'>Text from Previous Round</a>";
    echo " | ";
    echo "<a href='handle_bad_page.php?projectid=$projectid&image=$image&modify=image'>Original Image</a>";
    echo "</td></tr>";
    echo "<tr><td bgcolor='$theme[color_logobar_bg]' align='left'>";
    
    if (!empty($b_User) && !empty($b_Code)) {
        echo "<strong>What to do:&nbsp;&nbsp;</strong></td>";
        echo "<td bgcolor='#ffffff' align='center'>";
        echo "<input name='resolution' value='fixed' type='radio'>Fixed&nbsp;";
        echo "<input name='resolution' value='invalid' type='radio'>Invalid Report&nbsp;";
        echo "<input name='resolution' value='unfixed' checked type='radio'>Not Fixed&nbsp;";
        echo "</td></tr>";
    }
    else
    {
        echo "<input name='resolution' value='something' type='hidden'>";
        // Doesn't really matter what the value is.
    }
    
    echo "<tr><td bgcolor='$theme[color_headerbar_bg]' colspan='2' align='center'>";
    echo "<input type='submit' VALUE='Continue'>";
    echo "</td></tr></table></form></div><br><br>";

    //Determine if modify is set & if so display the form to either modify the image or text
    if (isset($_GET['modify']) && $_GET['modify'] == "text") {
        $prev_text = $page[$prevtext_column];

        echo "<form action='handle_bad_page.php' method='post'>";
        echo "<input type='hidden' name='modify' value='text'>";
        echo "<input type='hidden' name='projectid' value='$projectid'>";
        echo "<input type='hidden' name='image' value='$image'>";
        echo "<input type='hidden' name='prevtext_column' value='$prevtext_column'>";
        echo _("The textarea below contains the text from the previous round for ").$image.".<br>";
        echo _("You may use it as-is, or insert other replacement text for this page:<br>");
        echo "<textarea name='prev_text' cols=70 rows=10>";
        // SENDING PAGE-TEXT TO USER
        echo htmlspecialchars($prev_text,ENT_NOQUOTES);
        echo "</textarea><br><br>";
        echo "<input type='submit' value='Update Text From Previous Round'></form>";

    } elseif (isset($_POST['modify']) && $_POST['modify'] == "text") {
        $prev_text = $_POST['prev_text'];
        $prevtext_column = $_POST['prevtext_column'];
        Page_modifyText( $projectid, $image, $prev_text, $prevtext_column, $pguser );
        echo "<b>"._("Update of Text from Previous Round Complete!")."</b>";

    } elseif (isset($_GET['modify']) && $_GET['modify'] == "image") {
        echo "<form enctype='multipart/form-data' action='handle_bad_page.php' method='post'>";
        echo "<input type='hidden' name='modify' value='image'>";
        echo "<input type='hidden' name='projectid' value='$projectid'>";
        echo "<input type='hidden' name='image' value='$image'>";
        echo _("Select an image to upload and replace ").$image._(" with:<br>");
        echo "<input type='file' name='image_upload' size=30><br><br>";
        echo "<input type='submit' value='Update Original Image'></form>";
    } elseif (isset($_POST['modify']) && $_POST['modify'] == "image") {

        $org_image_ext = substr($image, -4);
        $org_image_basename = basename($image, $org_image_ext);
        $tmp_image_ext = substr($_FILES['image_upload']['name'], -4);

        if ( $tmp_image_ext == ".png" || $tmp_image_ext == ".jpg" ) {
            if ( $tmp_image_ext == $org_image_ext ) {
                copy($_FILES['image_upload']['tmp_name'],"$projects_dir/$projectid/$image") or die("Could not upload new image!");
                echo "<b>"._("Update of Original Image ").$image._(" Complete!")."</b>";
            } else {
                echo "<b>"._("Image NOT updated.<br>");
                echo _("The uploaded file type ($tmp_image_ext) does not match the original file type ($org_image_ext).<br>");
		echo _("Click ") . "<a href='handle_bad_page.php?projectid=$projectid&image=$image&modify=image'>"._("here")."</a>"._(" to return.")."</b>";
            }
        } else {
            echo "<b>"._("The uploaded file must be a PNG or JPG file! Click")." <a href='handle_bad_page.php?projectid=$projectid&image=$image&modify=image'>"._("here")."</a>"._(" to return.")."</b>";
        }
    }

    echo "</center>";
    theme("", "footer");
} else {

    //Get variables passed from form
    $projectid = $_POST['projectid'];
    $image = $_POST['image'];
    $state = $_POST['state'];

    //If the PM fixed the problem or stated the report was invalid update the database to reflect
    if (($resolution == "fixed") || ($resolution == "invalid")) {
        $round = get_Round_for_page_state($state);
        Page_eraseBadMark( $projectid, $image, $round, $pguser );
    }

    //Redirect the user back to the project detail page.
    header("Location: $code_url/project.php?id=$projectid&detail_level=4");
}

// vim: sw=4 ts=4 expandtab
?>
