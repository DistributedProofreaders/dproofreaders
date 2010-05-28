<?php
$relPath="../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'slim_header.inc');

$projectid = $page = $round_id = NULL;

//See if the user input looks valid
$error_messages = array();
$is_valid_project = false;
$is_valid_page = false;

$projectid = stripslashes(trim(array_get($_GET,"projectid","")));

$page = stripslashes(trim(array_get($_GET,"page","")));

$expanded_rounds = array_keys($Round_for_round_id_);
array_unshift($expanded_rounds, 'OCR');
$round_id = get_enumerated_param($_GET, 'round_id', 'OCR', $expanded_rounds);

if(isset($_GET["submit"])) {
    $projectid="";
    $page="";
    $round_id='OCR';
}


// If the projectID looks to be of roughly the right format, see if it exists.
if($projectid=="") {
    $error_messages[] = _("select a project");
} elseif (!preg_match('/^projectID[0-9a-f]{13}$/', $projectid ) ) {
    $error_messages[] = sprintf(_("projectID '%s' does not appear to be valid"),
        htmlspecialchars($projectid,ENT_QUOTES));
}

// See if the projectID exists in the projects table
if(!count($error_messages)) {
    $res = mysql_query(sprintf("SELECT 1 FROM projects WHERE projectid = '%s'",
        mysql_real_escape_string($projectid))) or die(mysql_error());

    if (mysql_num_rows($res) == 0)
        $error_messages[] = sprintf(_("no project with projectID '%s'"),
            htmlspecialchars($projectid,ENT_QUOTES));
    else
        $is_valid_project = true;

    mysql_free_result($res);
}

// See if the requested page (if any) exists in the project table
if(!count($error_messages)) {
    if($page) {
        $res2 = mysql_query(sprintf("SELECT 1 FROM $projectid WHERE image = '%s'", mysql_real_escape_string($page))) or die(mysql_error());
        if (mysql_num_rows($res2) == 0) {
            $error_messages[] = sprintf(_("no page '%1\$s' in project with projectID '%2\$s'"),
                htmlspecialchars($page,ENT_QUOTES),
                htmlspecialchars($projectid,ENT_QUOTES));
        } else {
            $is_valid_page = true;
        }

        mysql_free_result($res2);
    } else {
        $error_messages[] = _("select a project page");
    }
}


// $frame determines which frame we're operating from
// 'master' - we're the master frame
//    'top' - we're the top frame with the basic info
//  'image' - frame with the image
//   'text' - we're the bottom frame for the text
$frame = get_enumerated_param($_GET,"frame","master",array("master","top","image","text"));

if ($frame=="master") {
    slim_header(_("Image and text for page"),TRUE,FALSE);

    $projectid=htmlspecialchars($projectid,ENT_QUOTES);
    $page=htmlspecialchars($page,ENT_QUOTES);
    $round_id=htmlspecialchars($round_id,ENT_QUOTES);

?>
</head>
<frameset rows="15%,50%,35%">
<frame name="topframe" src="view_page_text_image.php?projectid=<?php echo $projectid;?>&amp;page=<?php echo $page;?>&amp;round_id=<?php echo $round_id;?>&amp;frame=top">
<frame name="imageframe" src="view_page_text_image.php?projectid=<?php echo $projectid;?>&amp;page=<?php echo $page;?>&amp;round_id=<?php echo $round_id;?>&amp;frame=image">
<frame name="textframe" src="view_page_text_image.php?projectid=<?php echo $projectid;?>&amp;page=<?php echo $page;?>&amp;round_id=<?php echo $round_id;?>&amp;frame=text">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
</html>
<?php
    // note: can't use slim_footer() because framesets don't have a </body> tag
}


// if we're in the top frame, load the form and any error messages
elseif ($frame=="top") {
    slim_header($page,TRUE,TRUE);

    if (!count($error_messages)) {
        $myresult = mysql_query(sprintf("SELECT nameofwork FROM projects WHERE projectid = '%s'", mysql_real_escape_string($projectid)));
        $row = mysql_fetch_assoc($myresult);
        $project_name = $row['nameofwork'];
        echo "<h3>".sprintf(_("Viewing %1\$s text for %2\$s in '%3\$s'"),$round_id,$page,$project_name)."</h3>\n";
    } else {
        echo "<h3>"._("Choose a page image/text to view");
        echo " - " . implode("; ",$error_messages);
        echo "</h3>\n";
    }

    echo "<form method='get' action='view_page_text_image.php' target='_top'>\n";
    if(!$is_valid_project) {
        echo _("ProjectID:") . "&nbsp;";
        echo "<input type='text' maxlength='25' name='projectid' size='25' value='" . htmlspecialchars($projectid,ENT_QUOTES) . "'> \n";
        echo "<input type='submit' value='"._("Select Project")."'> &nbsp; &nbsp;";
    } else {
        echo "<input type='hidden' name='projectid' value='" . htmlspecialchars($projectid,ENT_QUOTES) . "'>";
    }

    echo _("Page:") . "&nbsp;";
    if(!$is_valid_project)
    {
        echo "<input type='text' name='page' size='8'> " . _("(optional)") . " &nbsp; &nbsp;\n";
    }
    else
    {
        echo "<select name='page'>";
        echo "<option value=''></option>";
        $res = mysql_query( "SELECT image FROM $projectid ORDER BY image ASC") or die(mysql_error());
        if($res) {
            while(list($imagefile) = mysql_fetch_row($res))
            {
                echo "<option value=\"$imagefile\"";
                if ($page == $imagefile) echo " selected";
                echo ">".$imagefile."</option>\n";
            }
            mysql_free_result($res);
        }
        echo "</select> &nbsp; &nbsp;\n";
    }
    echo "<select name='round_id'>";

    foreach ($expanded_rounds as $round) {
        echo "<option value='$round'";
        if($round_id && $round == $round_id) echo " selected";
        echo ">$round</option>\n";
    }
    echo "</select>";

    if(!$is_valid_project)
        echo " " . _("(optional)");

    echo " &nbsp; &nbsp;<input type='submit' value='"._("View")."'>";

    if($is_valid_project)
        echo " &nbsp; <input type='submit' name='submit' value='"._("Reset")."'>";
    echo "</form>";
    slim_footer();
}

//If we're loading the image frame, load the image and a little form to control the size
elseif ($frame=="image") {
    slim_header(_("Image Frame"),TRUE,TRUE);
    if(!count($error_messages)) {
        $percent = get_integer_param($_GET, 'percent', 100, 1, 999);
        $width = 10*$percent;
?>
<form method="get" action="view_page_text_image.php">
<input type="hidden" name="projectid" value="<?php echo $projectid; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="text" maxlength="3" name="percent" size="3" value="<?php echo $percent; ?>">%
<input type="hidden" name="round_id" value="<?php echo $round_id; ?>">
<input type="hidden" name="frame" value="image">
<input type="submit" value="<?php echo _("Resize"); ?>" size="3">
</form>
<?php
        echo "<img src='$projects_url/$projectid/$page' width='$width' border='1'>";
    }
    slim_footer();
}

//If it's the text frame, we show the saved text in a textarea 
//with some of the user's preferences from the proofreading interface
elseif ($frame=="text") {
    slim_header(_("Text Frame"),TRUE,TRUE);
    if (!count($error_messages)) {
        if ($round_id == "OCR") {
            $text_column_name = 'master_text';
        } else {
            $round = get_Round_for_round_id($round_id);
            if ( is_null($round) )
            {
                die("unexpected parameter round_id = '$round_id'");
            }
            $text_column_name = $round->text_column_name;
        }

        $result = mysql_query(sprintf("SELECT $text_column_name FROM $projectid WHERE image = '%s'",mysql_real_escape_string($page))); 
        $data = mysql_result($result, 0, $text_column_name);

        // Use the font and wrap prefs for the user's default interface layout, 
        // since they're more likely to have set those prefs
        if ( $userP['i_layout']==1 ) {
            $font_face_i = $userP['v_fntf'];
            $font_size_i = $userP['v_fnts'];
            $line_wrap   = $userP['v_twrap'];
        } else {
            $font_face_i = $userP['h_fntf'];
            $font_size_i = $userP['h_fnts'];
            $line_wrap   = $userP['h_twrap'];
        }
        $font_face = $f_f[$font_face_i];
        $font_size = $f_s[$font_size_i];

        // Since this page doesn't have a vertical layout version, 
        // we'll use their horizontal prefs for textarea size
        $n_cols = $userP['h_tchars'];
        $n_rows = $userP['h_tlines'];

        echo "<textarea
            name='text_data'
            id='text_data'
            cols='$n_cols'
            rows='$n_rows'
            style='";
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

        echo ">\n";
        echo htmlspecialchars( $data, ENT_NOQUOTES );
        echo "</textarea>";
    }
    slim_footer();
}

// vim: sw=4 ts=4 expandtab
