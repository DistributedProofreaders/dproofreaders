<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'misc.inc'); // array_get(), get_enumerated_param(), attr_safe(), javascript_safe(), html_safe()

require_login();

$project = $projectid = $page = $round_id = NULL;

//See if the user input looks valid
$error_messages = array();
$is_valid_page = false;

$projectid = trim(array_get($_GET,"projectid",""));

$page = trim(array_get($_GET,"page",""));

$expanded_rounds = array_keys($Round_for_round_id_);
array_unshift($expanded_rounds, 'OCR');
$round_id = get_enumerated_param($_GET, 'round_id', 'OCR', $expanded_rounds);

if(isset($_GET["reset"])) {
    $projectid="";
    $page="";
    $round_id='OCR';
}


// If the projectID looks to be of roughly the right format, see if it exists.
if($projectid=="") {
    $error_messages[] = _("select a project");
} elseif (!preg_match('/^projectID[0-9a-f]{13}$/', $projectid ) ) {
    $error_messages[] = sprintf(_("projectID '%s' does not appear to be valid"),
        html_safe($projectid));
}

// See if the projectID exists in the projects table
if(!count($error_messages)) {
    try
    {
        $project = new Project($projectid);
    }
    catch(NonexistentProjectException $exception)
    {
        $error_messages[] = sprintf(_("no project with projectID '%s'"),
            html_safe($projectid));
    }
}

// See if the requested page (if any) exists in the project table
if(!count($error_messages)) {
    if($page) {
        $res2 = mysqli_query(DPDatabase::get_connection(), sprintf("SELECT 1 FROM $projectid WHERE image = '%s'", mysqli_real_escape_string(DPDatabase::get_connection(), $page))) or die(mysqli_error(DPDatabase::get_connection()));
        if (mysqli_num_rows($res2) == 0) {
            $error_messages[] = sprintf(_("no page '%1\$s' in project with projectID '%2\$s'"),
                html_safe($page),
                html_safe($projectid));
        } else {
            $is_valid_page = true;
        }

        mysqli_free_result($res2);
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
    slim_header_frameset(_("Image and text for page"));

    $projectid = urlencode($projectid);
    $page = urlencode($page);
    $round_id = urlencode($round_id);

?>
<frameset rows="15%,50%,35%">
<frame name="topframe" src="view_page_text_image.php?projectid=<?php echo $projectid;?>&amp;page=<?php echo $page;?>&amp;round_id=<?php echo $round_id;?>&amp;frame=top">
<frame name="imageframe" src="view_page_text_image.php?projectid=<?php echo $projectid;?>&amp;page=<?php echo $page;?>&amp;round_id=<?php echo $round_id;?>&amp;frame=image">
<frame name="textframe" src="view_page_text_image.php?projectid=<?php echo $projectid;?>&amp;page=<?php echo $page;?>&amp;round_id=<?php echo $round_id;?>&amp;frame=text">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
<?php
}


// if we're in the top frame, load the form and any error messages
elseif ($frame=="top") {
    slim_header($page);

    if (!count($error_messages)) {
        $myresult = mysqli_query(DPDatabase::get_connection(), sprintf("SELECT nameofwork FROM projects WHERE projectid = '%s'", mysqli_real_escape_string(DPDatabase::get_connection(), $projectid)));
        $row = mysqli_fetch_assoc($myresult);
        $project_name = $row['nameofwork'];
        echo "<h3>".sprintf(_("Viewing %1\$s text for %2\$s in '%3\$s'"),$round_id,$page,$project_name)."</h3>\n";
    } else {
        echo "<h3>"._("Choose a page image/text to view");
        echo " - " . implode("; ",$error_messages);
        echo "</h3>\n";
    }

    echo "<form method='get' action='view_page_text_image.php' target='_top'>\n";
    if(!$project) {
        echo _("Project ID") . ":&nbsp;";
        echo "<input type='text' maxlength='25' name='projectid' size='25' value='" . attr_safe($projectid) . "' required> \n";
        echo "<input type='submit' value='"._("Select Project")."'> &nbsp; &nbsp;";
    } else {
        echo "<input type='hidden' name='projectid' value='" . attr_safe($projectid) . "'>";
    }

    echo _("Page") . ":&nbsp;";
    if(!$project)
    {
        echo "<input type='text' name='page' size='8'> " . _("(optional)") . " &nbsp; &nbsp;\n";
    }
    else
    {
        $prev_image = "";
        $next_image = "";
        $res = mysqli_query(DPDatabase::get_connection(),  "SELECT image FROM $projectid ORDER BY image ASC") or die(mysqli_error(DPDatabase::get_connection()));
        if($res) {
            // load all images into an array
            $images = array();
            while($row = mysqli_fetch_assoc($res))
            {
                $images[] = $row["image"];
            }
            mysqli_free_result($res);

            echo "<select name='page'>\n";
            echo "<option value=''></option>\n";
            $num_rows = count($images);
            for ($row=0; $row<$num_rows; $row++)
            {
                $imagefile = $images[$row];
                echo "<option value=\"$imagefile\"";
                if ($page == $imagefile)
                {
                    echo " selected";
                    if ( $row != 0 )           $prev_image = $images[$row - 1];
                    if ( $row != $num_rows-1 ) $next_image = $images[$row + 1];
                }
                echo ">".$imagefile."</option>\n";
            }
            echo "</select> \n";
        }

        $prev_label    = attr_safe(_("Previous"));
        $prev_image_js = javascript_safe($prev_image, $charset);
        echo "<input type='button' value='$prev_label' onClick=\"this.form.page.value='$prev_image_js'; this.form.submit();\"";
        if ($prev_image == "") {
            echo " disabled";
        }
        echo ">\n";

        $next_label    = attr_safe(_("Next"));
        $next_image_js = javascript_safe($next_image, $charset);
        echo "<input type='button' value='$next_label' onClick=\"this.form.page.value='$next_image_js'; this.form.submit();\"";
        if ($next_image == "") {
            echo " disabled";
        }
        echo ">";

        echo " &nbsp; &nbsp;\n";
    }
    echo "<select name='round_id'>";

    foreach ($expanded_rounds as $round) {
        echo "<option value='$round'";
        if($round_id && $round == $round_id) echo " selected";
        echo ">$round</option>\n";
    }
    echo "</select>";

    if(!$project)
        echo " " . _("(optional)");

    echo " &nbsp; &nbsp;<input type='submit' value='" . attr_safe(_("View")) . "'>";

    if($project)
        echo " &nbsp; <input type='submit' name='reset' value='" . attr_safe(_("Reset")) . "'>";
    echo "</form>";
    exit();
}

//If we're loading the image frame, load the image and a little form to control the size
elseif ($frame=="image") {
    slim_header(_("Image Frame"));
    if(!count($error_messages)) {
        $percent = get_integer_param($_GET, 'percent', 100, 1, 999);
        $width = 10*$percent;
?>
<form method="get" action="view_page_text_image.php">
<input type="hidden" name="projectid" value="<?php echo $projectid; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="number" name="percent" value="<?php echo $percent; ?>" min="1" max="999" required>%
<input type="hidden" name="round_id" value="<?php echo $round_id; ?>">
<input type="hidden" name="frame" value="image">
<input type="submit" value="<?php echo _("Resize"); ?>" size="3">
</form>
<?php
        echo "<img src='$projects_url/$projectid/$page' width='$width' border='1'>";
    }
    exit();
}

//If it's the text frame, we show the saved text in a textarea 
//with some of the user's preferences from the proofreading interface
elseif ($frame=="text") {
    slim_header(_("Text Frame"));
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

        $result = mysqli_query(DPDatabase::get_connection(), sprintf("SELECT $text_column_name FROM $projectid WHERE image = '%s'",mysqli_real_escape_string(DPDatabase::get_connection(), $page))); 
        $row = mysqli_fetch_assoc($result);
        $data = $row[$text_column_name];

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
        $font_face = $proofreading_font_faces[$font_face_i];
        $font_size = $proofreading_font_sizes[$font_size_i];

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
        if ( $font_face != '' )
        {
            echo "font-family: $font_face;";
            echo " ";
        }
        if ( $font_size != '' )
        {
            echo "font-size: $font_size;";
        }
        echo "padding-left: 0.25em;' ";

        if ( !$line_wrap )
        {
            echo "wrap='off' ";
        }

        echo ">\n";
        echo html_safe($data);
        echo "</textarea>";
    }
    exit();
}

// vim: sw=4 ts=4 expandtab
