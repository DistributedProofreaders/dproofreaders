<?php
$relPath = './../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'../tools/project_manager/page_operations.inc');

require_login();

if (!user_is_a_sitemanager()) {
    die(_("You are not authorized to invoke this script."));
}

$extra_args["css_data"] = "
    input[type=text] { font-family: monospace; }
";

$title = _("Delete Pages");
output_header($title, NO_STATSBAR, $extra_args);

echo "<h1>$title</h1>";

echo "<p>" . _("This tool will allow you to delete pages out of a project.") . "</p>";

// validate inputs
$projectid = get_projectID_param($_POST, 'projectid', true);
$from_image_ = array_get($_POST, 'from_image_', null);
if (is_array($from_image_)) {
    foreach ($from_image_ as $which => $filename) {
        if ($filename) {
            validate_page_image($filename);
        }
    }
}

$action = get_enumerated_param($_POST, 'action', 'showform', ['showform', 'check', 'dodelete']);

switch ($action) {
    case 'showform':
        display_form('showform', $projectid, $from_image_);
        break;

    case 'check':
        do_stuff($projectid, $from_image_, true);
        display_form('check', $projectid, $from_image_);

        break;

    case 'dodelete':
        do_stuff($projectid, $from_image_, false);

        $url = "$code_url/tools/project_manager/page_detail.php?project={$projectid}&amp;show_image_size=0";
        echo "<a href='$url'>" . _("Project's detail page") . "</a>\n";

        break;
}

function display_form($action, $projectid, $from_image_)
{
    echo "<form method='post'>\n";
    echo "<table class='delete'>\n";

    if ($action == "showform") {
        echo "<tr>\n";
        echo "<th>" . _("Delete Page(s):") . "</th>\n";
        echo "<td><input type='text' name='from_image_[lo]' size='12'
            value='" . attr_safe(@$from_image_['lo']) . "' required>";
        echo " &ndash; <input type='text' name='from_image_[hi]' size='12'
            value='" . attr_safe(@$from_image_['hi']) . "' required>";
        echo "</td></tr>\n";
        echo "<tr><th>" . _("Project:") . "</th>\n";
        echo "<td><input type='text' name='projectid' size='28'
            value='" . attr_safe(@$projectid) . "' required>
            (projectid)</td></tr>\n";

        echo "<tr>";
        echo "<td></td><td>";
        echo "<input type='hidden' name='action' value='check'>\n";
        echo "<input type='submit' name='submit_button' value='" . attr_safe(_("Check")) . "'>";
        echo "</td>";
        echo "</tr>";
    } elseif ($action == "check") {
        echo "<tr>";
        echo "<td>";
        echo "<input type='hidden' name='from_image_[lo]' value='" . attr_safe($from_image_['lo']) . "'>";
        echo "<input type='hidden' name='from_image_[hi]' value='" . attr_safe($from_image_['hi']) . "'>";
        echo "<input type='hidden' name='projectid' value='" . attr_safe($projectid) . "'>";
        echo "<input type='hidden' name='action' value='dodelete'>\n";
        echo "<input type='submit' name='submit_button' value='" . attr_safe(_("Do it")) . "'>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>\n";
    echo "</form>";

    echo "<p><b>Note:</b> 'pages' are specified by their designation in the project table: e.g., '001.png'</p>\n";
}

function do_stuff($projectid, $from_image_, $just_checking)
{
    echo "<pre>";

    if (is_null($projectid)) {
        die("Error: no projectid supplied");
    }

    echo "    projectid: $projectid\n";

    $sql = sprintf("
        SELECT nameofwork
        FROM projects
        WHERE projectid='%s'
        ", DPDatabase::escape($projectid));
    $res = DPDatabase::query($sql);

    $n_projects = mysqli_num_rows($res);
    if ($n_projects == 0) {
        die("projects table has no match for projectid='$projectid'");
    } elseif ($n_projects > 1) {
        die("projects table has $n_projects matches for projectid='$projectid'. (Can't happen)");
    }

    [$title] = mysqli_fetch_row($res);

    echo "    title    : $title\n";

    // ------------

    validate_projectID($projectid);
    $res = DPDatabase::query("
        SELECT image, fileid
        FROM $projectid
        ORDER BY image
    ");

    $n_pages = mysqli_num_rows($res);

    echo "    # pages  : $n_pages\n";

    if ($n_pages == 0) {
        die("project has no pages to delete");
    }

    $all_image_values = [];
    while ([$image, $fileid] = mysqli_fetch_row($res)) {
        $all_image_values[] = $image;
    }

    // ----------------------

    $lo = trim($from_image_['lo']);
    $hi = trim($from_image_['hi']);

    if ($lo == '' && $hi == '') {
        die("no pages specified for deletion");
    } elseif ($hi == '') {
        $hi = $lo;
    }

    echo "    pages to delete: $lo - $hi\n";

    $lo_i = array_search($lo, $all_image_values);
    $hi_i = array_search($hi, $all_image_values);

    if ($lo_i === false) {
        die("project does not have a page with image='$lo'");
    }

    if ($hi_i === false) {
        die("project does not have a page with image='$hi'");
    }

    if ($lo_i > $hi_i) {
        die("low end of range ($lo) is greater than high end ($hi)");
    }

    $n_pages_to_delete = 1 + $hi_i - $lo_i;
    echo "    ($n_pages_to_delete pages)\n";
    echo "</pre>";

    if ($just_checking) {
        return;
    }

    // ----------------------------------------------------

    echo "<hr>";
    echo "<pre>";

    for ($i = $lo_i; $i <= $hi_i; $i++) {
        $image = $all_image_values[$i];
        echo "image=$image: ";
        $err = page_del($projectid, $image);
        echo($err ? $err : "success");
        echo "<br>";
    }

    echo "</pre>";
}
