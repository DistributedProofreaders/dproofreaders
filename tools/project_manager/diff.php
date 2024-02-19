<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'links.inc');
include_once($relPath."DifferenceEngineWrapperTable.inc");
include_once($relPath."DifferenceEngineWrapperBB.inc");
include_once($relPath."PageUnformatter.inc"); // PageUnformatter()

require_login();

$projectid = get_projectID_param($_GET, 'project');
$image = get_page_image_param($_GET, 'image', true);
$L_round_num = get_integer_param($_GET, 'L_round_num', null, 0, Rounds::get_last()->round_number);
$R_round_num = get_integer_param($_GET, 'R_round_num', null, 0, Rounds::get_last()->round_number);
$format = get_enumerated_param($_GET, "format", null, ["keep", "remove"], true);
$only_nonempty_diffs = @$_GET['only_nonempty_diffs'] === 'on';
$bb_diffs = (@$_GET['bb_diffs'] === 'on' and user_can_mentor_in_any_round());


$project = new Project($projectid);
$state = $project->state;
$project_title = $project->nameofwork;
$navigation_text = "";

if (!$project->pages_table_exists) {
    // This shouldn't normally happen --
    // if the page table doesn't exist, a "diff" link shouldn't be shown.
    // But a user might have a bookmarked or otherwise saved a 'diff' URL.
    echo "<p>", _("Page details are not available for this project."), "</p>\n";
    echo "<p>", _("Project ID"), ": $projectid</p>\n";
    echo "<p>", _("Title"), ": " . html_safe($project_title) . "</p>\n";
    exit;
}

// --------------------------------------------------------------
// get information about this diff
if ($L_round_num == 0) {
    $L_text_column_name = 'master_text';
    $L_user_column_name = "'none'";  // string literal, not column name
    $L_round_name = _('OCR');
    $L_round_id = 'OCR';
    $L_format = false;
} else {
    $L_round = get_Round_for_round_number($L_round_num);
    $L_text_column_name = $L_round->text_column_name;
    $L_user_column_name = $L_round->user_column_name;
    $L_round_name = $L_round->id;
    $L_round_id = $L_round->id;
    $L_format = is_formatting_round($L_round);
}

if ($R_round_num == 0) {
    $R_text_column_name = 'master_text';
    $R_user_column_name = "'none'";  // string literal, not column name
    $R_round_name = _('OCR');
    $R_round_id = 'OCR';
    $R_format = false;
} else {
    $R_round = get_Round_for_round_number($R_round_num);
    $R_text_column_name = $R_round->text_column_name;
    $R_user_column_name = $R_round->user_column_name;
    $R_round_name = $R_round->id;
    $R_round_id = $R_round->id;
    $R_format = is_formatting_round($R_round);
}

if (!$format) { // no parameter passed
    // default to remove format if only one of the rounds is formatting
    $format = ($L_format xor $R_format) ? "remove" : "keep";
}

// Create a label for the two columns with a link to the page text in the
// Image Viewer. $projectid & $image have been validated above and
// L/R_round_name are known round names or 'OCR', so no need to escape them.
$L_label = new_window_link("../page_browser.php?project=$projectid&imagefile=$image&mode=text&round_id=$L_round_id", $L_round_name);
$R_label = new_window_link("../page_browser.php?project=$projectid&imagefile=$image&mode=text&round_id=$R_round_id", $R_round_name);

validate_projectID($projectid);
$query = sprintf(
    "
    SELECT $L_text_column_name, $R_text_column_name,
        $L_user_column_name, $R_user_column_name
    FROM $projectid
    WHERE image='%s'
    ",
    DPDatabase::escape($image)
);

$res = DPDatabase::query($query);
[$L_text, $R_text, $L_user, $R_user] = mysqli_fetch_row($res);
$can_see_names_for_this_page = can_see_names_for_page($projectid, $image);
if ($can_see_names_for_this_page) {
    $L_label .= " ($L_user)";
    $R_label .= " ($R_user)";
}

if ($format == "remove") {
    $un_formatter = new PageUnformatter();
    // also remove blank lines and leading and trailing spaces
    $L_text = $un_formatter->remove_formatting($L_text, false);
    $R_text = $un_formatter->remove_formatting($R_text, false);
    $link_text = _('Difference for page %s with formatting removed');
} else {
    $link_text = _('Difference for page %s');
}

// now have the image, users, labels etc all set up
// -----------------------------------------------------------------------------

$title = sprintf(_('Difference for page %s'), $image);
$image_url = "$code_url/tools/page_browser.php?project=$projectid&amp;imagefile=$image";
$image_link = sprintf($link_text, new_window_link($image_url, $image));
$extra_args = [
    "css_data" => get_DifferenceEngine_css_data(),
    "js_data" => "
        function copyToClip(textstring) {
            navigator.clipboard.writeText(textstring).catch(function () {alert('Unable to copy!');});
        }
    ",
];

output_header("$title: $project_title", NO_STATSBAR, $extra_args);

echo "<h1>" . html_safe($project_title) . "</h1>\n";
echo "<h2>$image_link</h2>\n";

do_navigation(
    $projectid,
    $image,
    $L_round_num,
    $R_round_num,
    $L_user_column_name,
    $L_user,
    $format,
    $L_text_column_name,
    $R_text_column_name,
    $only_nonempty_diffs,
    $bb_diffs
);
echo $navigation_text;

echo "\n<p>" . return_to_project_page_link($projectid, ["expected_state=$state"]) . "\n";

if ($L_format || $R_format) {
    // show option to change compare method
    $format_save = array_get($_GET, "format", $format);
    if ($format == "remove") {
        $format_label = _("Compare with formatting");
        $_GET["format"] = "keep";
    } else {
        $format_label = _("Compare without formatting");
        $_GET["format"] = "remove";
    }
    $format_url = "?" . attr_safe(http_build_query($_GET));
    $format_label = html_safe($format_label);
    echo "| <a href='$format_url'>$format_label</a>\n";
    // Restore $_GET in case needed below
    $_GET["format"] = $format_save;
}

if (user_can_mentor_in_any_round()) {
    // show option to change diff style
    $bb_diffs_save = array_get($_GET, "bb_diffs", "off");
    if ($bb_diffs) {
        $bb_diffs_label = _("Show table-style diff");
        $_GET["bb_diffs"] = "off";
    } else {
        $bb_diffs_label = _("Show BBCode-style diff");
        $_GET["bb_diffs"] = "on";
    }
    $bb_diffs_url = "?" . attr_safe(http_build_query($_GET));
    $bb_diffs_label = html_safe($bb_diffs_label);
    echo "| <a href='$bb_diffs_url'>$bb_diffs_label</a>\n";
    // Restore $_GET in case needed below
    $_GET["bb_diffs"] = $bb_diffs_save;
}

echo "</p>\n";

// ---------------------------------------------------------

$diffurl = "$code_url/tools/project_manager/diff.php?project=$projectid&image=$image&L_round_num=$L_round_num&R_round_num=$R_round_num";

$diffEngine = $bb_diffs ? new DifferenceEngineWrapperBBHTML($diffurl) : new DifferenceEngineWrapperTable();
$diffEngine->setText($L_text, $R_text);
echo $diffEngine->getDiff($L_label, $R_label);

// don't print out the navigation bit again if there is no difference
// at the top of the page it's buttons, then project page
// we reverse the order here for symmetry :)
if ($L_text != $R_text) {
    echo "\n<p>" . return_to_project_page_link($projectid, ["expected_state=$state"]) . "\n";
    echo $navigation_text;
}

/**
 * Build up the text for the navigation bit, so we can repeat it
 * again at the bottom of the page
 */
function do_navigation(
    $projectid,
    $image,
    $L_round_num,
    $R_round_num,
    $L_user_column_name,
    $L_user,
    $format,
    $L_text_column_name,
    $R_text_column_name,
    $only_nonempty_diffs,
    $bb_diffs
) {
    global $navigation_text;
    $jump_to_js = "this.form.image.value=this.form.jumpto[this.form.jumpto.selectedIndex].value; this.form.submit();";

    $navigation_text .= "\n<form method='get' action='diff.php'>";
    $navigation_text .= "\n<input type='hidden' name='project' value='$projectid'>";
    $navigation_text .= "\n<input type='hidden' name='image' value='$image'>";
    $navigation_text .= "\n<input type='hidden' name='L_round_num' value='$L_round_num'>";
    $navigation_text .= "\n<input type='hidden' name='R_round_num' value='$R_round_num'>";
    $navigation_text .= "\n<input type='hidden' name='format' value='$format'>";
    if ($bb_diffs) {
        $navigation_text .= "\n<input type='hidden' name='bb_diffs' value='on'>";
    }
    $navigation_text .= "\n" . _("Jump to") . ": <select name='jumpto' onChange='$jump_to_js'>\n";

    validate_projectID($projectid);
    $query = "
        SELECT image,
            $L_user_column_name,
            CAST($L_text_column_name AS BINARY) = CAST($R_text_column_name AS BINARY) AS is_empty_diff
        FROM $projectid
        ORDER BY image ASC
    ";
    $res = DPDatabase::query($query);
    $prev_image = "";
    $next_image = "";
    $prev_from_proofer = "";
    $next_from_proofer = "";
    $got_there = false;
    // construct the dropdown; work out where previous and next buttons should take us
    while ([$this_val, $this_user, $is_empty_diff] = mysqli_fetch_row($res)) {
        $navigation_text .= "\n<option value='$this_val'";

        if ($this_val == $image) {
            $navigation_text .= " selected";  // make the correct element of the drop down selected
            $got_there = true;
        } elseif ($only_nonempty_diffs && $is_empty_diff) {
            $navigation_text .= " disabled"; // Disable empty diffs in the dropdown and skip the other checks
        } elseif ($got_there) {
            // we are at the one after the current one
            if ($next_image == "") {
                $next_image = $this_val;
            }

            if ($next_from_proofer == "" && $this_user == $L_user) {
                $next_from_proofer = $this_val;
            }
        } elseif (!$got_there) {
            $prev_image = $this_val;  // keep track of what the previous image was
            if ($this_user == $L_user) {
                $prev_from_proofer = $this_val;
            }
        }

        $navigation_text .= ">$this_val</option>";
    }
    $navigation_text .= "\n</select>";
    $previous_js = "this.form.image.value='$prev_image'; this.form.submit();";
    $next_js = "this.form.image.value='$next_image'; this.form.submit();";
    $previous_from_proofer_js = "this.form.image.value='$prev_from_proofer'; this.form.submit();";
    $next_from_proofer_js = "this.form.image.value='$next_from_proofer'; this.form.submit();";

    $navigation_text .= "\n<input type='button' value='" . attr_safe(_("Previous")) . "' onClick=\"$previous_js\"";
    if ($prev_image == "") {
        $navigation_text .= " disabled";
    }
    $navigation_text .= ">";
    $navigation_text .= "\n<input type='button' value='" . attr_safe(_("Next")) . "' onClick=\"$next_js\"";
    if ($next_image == "") {
        $navigation_text .= " disabled";
    }
    $navigation_text .= ">";

    if (can_navigate_by_proofer($projectid, $L_user)) {
        $navigation_text .= "\n<input type='button' value='" . attr_safe(_("Proofreader previous")) . "' onClick=\"$previous_from_proofer_js\"";
        if ($prev_from_proofer == "") {
            $navigation_text .= " disabled";
        }
        $navigation_text .= ">";
        $navigation_text .= "\n<input type='button' value='" . attr_safe(_("Proofreader next")) . "' onClick=\"$next_from_proofer_js\"";
        if ($next_from_proofer == "") {
            $navigation_text .= " disabled";
        }
        $navigation_text .= ">";
    }

    $checked_attribute = $only_nonempty_diffs ? 'checked' : '';

    $navigation_text .= "\n<input type='checkbox' name='only_nonempty_diffs' $checked_attribute id='only_nonempty_diffs' onclick='this.form.submit()'>\n";
    $navigation_text .= "\n<label for='only_nonempty_diffs'>" . html_safe(_('Skip empty diffs')) . "</label>\n";
    $navigation_text .= "\n</form>\n";
}

/**
 * Discover whether the user is allowed to see proofreader names for this page
 */
function can_see_names_for_page($projectid, $image)
{
    global $pguser;

    // If requester isn't logged in, they can't see any names.
    if ($pguser == '') {
        return false;
    }

    $project = new Project($projectid);
    $answer = $project->names_can_be_seen_by_current_user; // can see for all pages
    if (! $answer) {
        $fields = "";
        foreach (Rounds::get_all() as $round) {
            if ($fields != "") {
                $fields .= ", ";
            }
            $fields .= $round->user_column_name;
        }

        validate_projectID($projectid);
        $query = sprintf(
            "SELECT $fields from $projectid WHERE image = '%s'",
            DPDatabase::escape($image)
        );
        $res = DPDatabase::query($query);
        $page_res = mysqli_fetch_array($res);
        foreach ($page_res as $page_user) {
            if ($page_user == $pguser) {
                $answer = true;
                break;
            }
        }
    }
    return $answer;
}

/**
 * Discover whether the user is allowed to navigate by proofreader for this page
 */
function can_navigate_by_proofer($projectid, $L_user)
{
    global $pguser;
    $answer = false;
    // If user isn't logged in, they definitely can't
    if ($pguser == '') {
        return false;
    }
    $project = new Project($projectid);

    // if user can manage project, or is evaluator they can
    $answer = $project->can_be_managed_by_current_user ||
        user_is_an_access_request_reviewer();

    // otherwise, they can if this diff is one of theirs
    if (! $answer) {
        $answer = ($pguser == $L_user);
    }
    return $answer;
}
