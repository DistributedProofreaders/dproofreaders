<?php
$relPath = './../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'DPage.inc'); // project_recalculate_page_counts
include_once($relPath.'Project.inc');
include_once($relPath.'user_project_info.inc');
include_once($relPath.'wordcheck_engine.inc');

require_login();

if (!user_is_a_sitemanager()) {
    die(_("You are not authorized to invoke this script."));
}

$extra_args["css_data"] = "
    input[type=text] { font-family: monospace; }
";

$title = _("Copy Pages");
output_header($title, NO_STATSBAR, $extra_args);
echo "<h1>" . $title . "</h1>\n";

echo "<p>" . _("This tool will allow you to copy pages from one project to another.") . "</p>";
// Validate the $projectid_ and $from_image_ 'by hand'
$projectid_ = array_get($_POST, 'projectid_', null);
if (is_array($projectid_)) {
    foreach ($projectid_ as $which => $projectid) {
        validate_projectID($projectid);
    }
}
$from_image_ = array_get($_POST, 'from_image_', null);
if (is_array($from_image_)) {
    foreach ($from_image_ as $which => $filename) {
        if ($filename) {
            validate_page_image($filename);
        }
    }
}

$action = get_enumerated_param($_POST, 'action', 'showform', ['showform', 'showagain', 'check', 'docopy']);
$page_name_handling = get_enumerated_param($_POST, 'page_name_handling', null, ['PRESERVE_PAGE_NAMES', 'RENUMBER_PAGES'], true);
$transfer_notifications = get_integer_param($_POST, 'transfer_notifications', 0, 0, 1);
$add_deletion_reason = get_integer_param($_POST, 'add_deletion_reason', 0, 0, 1);
$merge_wordcheck_data = get_integer_param($_POST, 'merge_wordcheck_data', 0, 0, 1);
$repeat_project = get_enumerated_param($_POST, 'repeat_project', null, ['TO', 'FROM', 'NONE'], true);

switch ($action) {
    case 'showform':
        display_form($projectid_, $from_image_, $page_name_handling,
                     $transfer_notifications, $add_deletion_reason,
                     $merge_wordcheck_data, $repeat_project, false);
        break;

    case 'showagain':
        display_form($projectid_, $from_image_, $page_name_handling,
                     $transfer_notifications, $add_deletion_reason,
                     $merge_wordcheck_data, $repeat_project, true);
        break;

    case 'check':
        do_stuff($projectid_, $from_image_, $page_name_handling,
                  $transfer_notifications, $add_deletion_reason,
                  $merge_wordcheck_data, true);

        echo "<form method='post'>\n";
        display_hiddens($projectid_, $from_image_, $page_name_handling,
                        $transfer_notifications, $add_deletion_reason,
                        $merge_wordcheck_data);
        echo "\n<input type='hidden' name='action' value='docopy'>";
        echo "\n<input type='submit' name='submit_button' value='" . attr_safe(_("Do it")) ."'>";
        echo "\n</form>";
        echo "<div style='height: 4em;'>&nbsp;</div>"; // Spacer
        break;

    case 'docopy':
        do_stuff($projectid_, $from_image_, $page_name_handling,
                  $transfer_notifications, $add_deletion_reason,
                  $merge_wordcheck_data, false);

        echo "<hr>\n";
        $url = "$code_url/tools/project_manager/page_detail.php?project={$projectid_['to']}&amp;show_image_size=0";
        echo sprintf(_("<p><a href='%s'>Go to destination project's detail page</a></p>\n"), $url);
        echo "<form method='post'>\n";
        echo "<fieldset>\n";
        echo "<legend>" . _("Copy more pages...") . "</legend>";
        echo "<input type='radio' name='repeat_project' id='rp-1' value='FROM'>";
        echo "<label for='rp-1'>" . _("From the same project") . "</label>";
        echo "<input type='radio' name='repeat_project' id='rp-2' value='TO'>";
        echo "<label for='rp-2'>" . _("To the same project") . "</label>";
        // Or explicitly just go back to the original blank form by default
        echo "<input type='radio' name='repeat_project' id='rp-3' value='NONE' CHECKED>";
        echo "<label for='rp-3'>" . _("Neither") . "</label>";

        display_hiddens($projectid_, $from_image_, $page_name_handling,
                        $transfer_notifications, $add_deletion_reason,
                        $merge_wordcheck_data);

        echo "<input type='hidden' name='action' value='showagain'>\n";
        echo "<input type='submit' name='submit_button' value='" . attr_safe(_("Again!")) . "'>\n";
        echo "</fieldset>\n";
        echo "</form>\n";
        echo "<div style='height: 4em;'>&nbsp;</div>"; // Spacer
        break;

    default:
        echo sprintf(_("Unexpected value for action: '%s'"), html_safe($action));
        break;
}

function display_form($projectid_, $from_image_, $page_name_handling,
                      $transfer_notifications, $add_deletion_reason,
                      $merge_wordcheck_data, $repeat_project, $repeating)
{
    echo "<form method='post'>\n";
    echo "<table class='copy'>\n";

    // always leave the page numbers blank
    echo "<tr>\n";
    echo "<th>" . _("Copy Page(s):") . "</th>\n";
    echo "<td><input type='text' name='from_image_[lo]' size='12'>";
    echo " &ndash; <input type='text' name='from_image_[hi]' size='12'>";
    echo " " . _("(leave these fields blank to copy all pages)");
    echo "</td></tr>\n";

    // if we are repeating, will want to fill one of these in
    $val = '';
    if ($repeating && $repeat_project == 'FROM') {
        $val = "value='" . attr_safe($projectid_['from']) . "'";
    }
    echo "<tr><th>" . _("Source Project:") . "</th>\n";
    echo "<td><input type='text' name='projectid_[from]' size='28' $val required> (projectid)</td></tr>\n";
    $val = '';
    if ($repeating && $repeat_project == 'TO') {
        $val = "value='" . attr_safe($projectid_['to']) . "'";
    }
    echo "<tr><th>" . _("Destination Project:") . "</th>\n";
    echo "<td><input type='text' name='projectid_[to]' size='28' $val required> (projectid)</td></tr>\n";

    // If we are repeating, we want the same buttons to be checked
    echo "<tr><td></td><td>\n";
    echo "<fieldset>\n";
    echo "<legend>" . _("Page number handling:") . "</legend>";

    if (!$repeating ||
        ($repeating && $page_name_handling == 'PRESERVE_PAGE_NAMES')) {
        $checked1 = 'CHECKED';
        $checked2 = '';
    } else {
        $checked1 = '';
        $checked2 = 'CHECKED';
    }

    echo "<input type='radio' name='page_name_handling' id='pnh-1' value='PRESERVE_PAGE_NAMES' $checked1>\n";
    echo "<label for='pnh-1'>" . _("Preserve page numbers") . "</label>";

    echo "<input type='radio' name='page_name_handling' id='pnh-2' value='RENUMBER_PAGES' $checked2>\n";
    echo "<label for='pnh-2'>" . _("Renumber pages") . "</label>\n";
    echo "</fieldset>\n";
    echo "</td></tr>\n";

    do_radio_button_pair(_("Transfer event notifications:"),
        "transfer_notifications", $repeating, $transfer_notifications);
    do_radio_button_pair(_("Add deletion reason to source project:"),
        "add_deletion_reason", $repeating, $add_deletion_reason);
    do_radio_button_pair(_("Merge WordCheck files and events into destination project:"),
        "merge_wordcheck_data", $repeating, $merge_wordcheck_data);

    echo "<tr><td></td><td>";
    echo "<input type='hidden' name='action' value='check'>\n";
    echo "<input type='submit' name='submit_button' value='" . _("Check") . "'>";
    echo "</td></tr>";
    echo "</table>\n";
    echo "</form>";

    echo "<p><b>Note:</b> 'pages' are specified by their designation in the project table: e.g., '001.png'</p>\n";
    echo "<div style='height: 4em;'>&nbsp;</div>"; // Spacer
}

// Display table row with a fieldset containing a pair of radio buttons, one selected.
// NB $input_name must be a valid HTML ID (i.e. no spaces and shouldn't start with a number)
function do_radio_button_pair($prompt, $input_name, $repeating, $first_is_checked)
{
    if (!$repeating ||
        ($repeating && $first_is_checked)) {
        $checked1 = 'CHECKED';
        $checked2 = '';
    } else {
        $checked1 = '';
        $checked2 = 'CHECKED';
    }

    echo "<tr><td></td><td>\n";
    echo "<fieldset>\n";
    echo "<legend>$prompt</legend>\n";
    echo "<input type='radio' name='$input_name' id='{$input_name}-1' value='1' $checked1>\n";
    echo "<label for='{$input_name}-1'>" . _("Yes") . "</label>\n";
    echo "&nbsp;&nbsp;&nbsp;&nbsp; ";

    echo "<input type='radio' name='$input_name' id='{$input_name}-2' value='0' $checked2>\n";
    echo "<label for='{$input_name}-2'>" . _("No") . "</label>\n";
    echo "</fieldset>\n";
    echo "</td></tr>\n";
}

function display_hiddens($projectid_, $from_image_, $page_name_handling,
                         $transfer_notifications, $add_deletion_reason,
                         $merge_wordcheck_data)
{
    echo "\n<input type='hidden' name='from_image_[lo]'        value='" . attr_safe($from_image_['lo']) . "'>";
    echo "\n<input type='hidden' name='from_image_[hi]'        value='" . attr_safe($from_image_['hi']) . "'>";
    echo "\n<input type='hidden' name='projectid_[from]'       value='" . attr_safe($projectid_['from']) . "'>";
    echo "\n<input type='hidden' name='projectid_[to]'         value='" . attr_safe($projectid_['to']) . "'>";
    echo "\n<input type='hidden' name='page_name_handling'     value='" . attr_safe($page_name_handling) . "'>";
    echo "\n<input type='hidden' name='transfer_notifications' value='" . attr_safe($transfer_notifications) . "'>";
    echo "\n<input type='hidden' name='add_deletion_reason'    value='" . attr_safe($add_deletion_reason) . "'>";
    echo "\n<input type='hidden' name='merge_wordcheck_data'   value='" . attr_safe($merge_wordcheck_data) . "'>";
}

function do_stuff($projectid_, $from_image_, $page_name_handling,
                   $transfer_notifications, $add_deletion_reason,
                   $merge_wordcheck_data,
                   $just_checking)
{
    if (is_null($projectid_)) {
        error_and_die("No projectid data supplied to do_stuff()");
    }

    $project_obj = [
        "from" => new Project($projectid_["from"]),
        "to" => new Project($projectid_["to"]),
    ];

    if ($projectid_['from'] == $projectid_['to']) {
        error_and_die("You can't copy a project into itself.");
    }

    foreach (['from', 'to'] as $which) {
        $project = $project_obj[$which];

        if (!$project->check_pages_table_exists($message)) {
            error_and_die("Project {$project->projectid}: $message");
        }

        if (!$project->is_utf8) {
            error_and_die("Project table {$project->projectid} is not UTF-8.");
        }

        $sql = "DESCRIBE {$project->projectid}";
        $res = DPDatabase::query($sql);

        $column_names = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $column_names[] = $row['Field'];
        }
        $column_names_[$which] = $column_names;
    }
    $clashing_columns = array_intersect($column_names_['from'], $column_names_['to']);

    foreach (['from', 'to'] as $which) {
        $project = $project_obj[$which];

        // clever use of $which above means we need label uses translated
        // separately, which is convenient, since 'to/from' could be mistaken
        // as indicating a range.
        echo "<h3>";
        switch ($which) {
            case 'from': echo _("Source Project:"); break;
            case 'to':   echo _("Destination Project:"); break;
            default:
            // Shouldn't happen
            break;
        }
        echo "</h3>";

        echo "<table class='copy'>";

        echo "<tr><th>" . _("Project ID") . ":</th><td>" . $project->projectid . "</td></tr>\n";

        echo "<tr><th>" . _("Title") . ":</th><td>" . html_safe($project->nameofwork) . "</td></tr>\n";

        // ----------------------

        $sql = "
            SELECT image, fileid
            FROM {$project->projectid}
            ORDER BY image";
        $res = DPDatabase::query($sql);

        $n_pages = mysqli_num_rows($res);

        // TRANSLATORS: abbreviated form of "number of pages"
        echo "<tr><th>" . _("No. of pages") . ":</th><td>" . $n_pages . "</td></tr>\n";

        if ($which == 'from' && $n_pages == 0) {
            error_and_die("Project {$project->projectid} has no page data to extract");
        }

        $all_image_values = [];
        $all_fileid_values = [];
        while ([$image, $fileid] = mysqli_fetch_row($res)) {
            $all_image_values[] = $image;
            $all_fileid_values[] = $fileid;
        }

        $all_image_values_[$which] = $all_image_values;
        $all_fileid_values_[$which] = $all_fileid_values;

        // ----------------------

        $n_columns = count($column_names_[$which]);
        echo "<tr><th>";
        // TRANSLATORS: abbreviated form of "number of columns"
        echo _("No. of columns") . ":</th><td>" . $n_columns . "</td></tr>\n";

        $extra_columns_[$which] = array_diff($column_names_[$which], $clashing_columns);
        if (count($extra_columns_[$which]) > 0) {
            echo "<tr><th>" . _("Extra columns") . ":</th>";
            echo "<td><code>" . html_safe(implode(" ", $extra_columns_[$which])) . "</code></td></tr>\n";

            echo "<tr><td></td><td>";
            if ($which == 'from') {
                echo _("(These columns will simply be ignored.)");
            } else {
                echo _("(These columns will be given their default value.)");
            }
            echo "</td></tr>";
        }

        echo "</table>\n";

        // ----------------------

        if ($which == 'from') {
            $lo = trim($from_image_['lo']);
            $hi = trim($from_image_['hi']);

            if ($lo == '' && $hi == '') {
                $lo = $all_image_values[0];
                $hi = $all_image_values[count($all_image_values) - 1];
            } elseif ($hi == '') {
                $hi = $lo;
            }

            $lo_i = array_search($lo, $all_image_values);
            $hi_i = array_search($hi, $all_image_values);

            if ($lo_i === false) {
                error_and_die("Project {$project->projectid} does not have a page with image='$lo'");
            }

            if ($hi_i === false) {
                error_and_die("Project {$project->projectid} does not have a page with image='$hi'");
            }

            if ($lo_i > $hi_i) {
                error_and_die("Low end of range ($lo) is greater than high end ($hi)");
            }

            $n_pages_to_copy = 1 + $hi_i - $lo_i;

            echo "<p>";
            echo sprintf(_('Pages to copy: %1$s &ndash; %2$s'), $lo, $hi);
            echo " " . sprintf(_("(%d pages)"), $n_pages_to_copy);
            echo "</p>\n";
        }
    }

    $charsuites_not_in_to = array_udiff(
        $project_obj["from"]->get_charsuites(false),
        $project_obj["to"]->get_charsuites(false),
        function ($a, $b) {
            return $a == $b ? 0 : 1;
        }
    );
    if ($charsuites_not_in_to) {
        echo "<p class='warning'>" . _("Character Suite mismatch") . "</p>";
        echo "<p>" . _("The following character suites are in the source project but not in the destination project. Ensure the copied pages do not use these or adjust the destination project character suites accordingly.") . "</p>";
        echo "<ul>";
        foreach ($charsuites_not_in_to as $charsuite) {
            echo "<li>" . html_safe($charsuite->name) . "</li>";
        }
        echo "</ul>";
    }

    $chars_not_in_to_customsuite = array_diff(
        utf8_codepoints_combining($project_obj["from"]->custom_chars),
        utf8_codepoints_combining($project_obj["to"]->custom_chars)
    );
    if ($chars_not_in_to_customsuite) {
        echo "<p class='warning'>" . _("Custom Characters mismatch") . "</p>";
        echo "<p>" . _("The source project has custom characters not in the destination project's custom character suite. Confirm these characters are not in the pages being copied or are in the destination project's character suites.") . "</p>";
    }

    // ----------------------------------------------------

    if ($page_name_handling == 'PRESERVE_PAGE_NAMES') {
        // fine
    } elseif ($page_name_handling == 'RENUMBER_PAGES') {
        if (count($all_fileid_values_['to']) == 0) {
            $c_dst_format = '%03d';
            $c_dst_start_b = 1;
        } else {
            $max_dst_fileid = str_max($all_fileid_values_['to']);
            $max_dst_image = str_max($all_image_values_['to']);
            $max_dst_image_base = preg_replace('/\.[^.]+$/', '', $max_dst_image);
            $max_dst_base = (
                strcmp($max_dst_fileid, $max_dst_image_base) > 0
                ? $max_dst_fileid
                : $max_dst_image_base);
            $c_dst_format = '%0' . strlen($max_dst_base) . 'd';
            $c_dst_start_b = 1 + intval($max_dst_base);
        }
    } else {
        error_and_die("Bad \$page_name_handling");
    }

    // The c_ prefix means that it only pertains to *copied* pages.

    $c_src_image_ = [];
    $c_src_fileid_ = [];
    $c_dst_image_ = [];
    $c_dst_fileid_ = [];

    for ($i = $lo_i; $i <= $hi_i; $i++) {
        $c_src_image = $all_image_values_['from'][$i];
        $c_src_fileid = $all_fileid_values_['from'][$i];

        if ($page_name_handling == 'PRESERVE_PAGE_NAMES') {
            $c_dst_fileid = $c_src_fileid;
            $c_dst_image = $c_src_image;
        } elseif ($page_name_handling == 'RENUMBER_PAGES') {
            $c_src_image_ext = preg_replace('/.*\./', '', $c_src_image);
            $c_dst_b = ($i - $lo_i + $c_dst_start_b);
            $c_dst_fileid = sprintf($c_dst_format, $c_dst_b);
            $c_dst_image = "$c_dst_fileid.$c_src_image_ext";
        } else {
            assert(false);
        }

        $c_src_image_[] = $c_src_image;
        $c_src_fileid_[] = $c_src_fileid;
        $c_dst_image_[] = $c_dst_image;
        $c_dst_fileid_[] = $c_dst_fileid;
    }

    $clashing_image_values = array_intersect($c_dst_image_, $all_image_values_['to']);
    if (count($clashing_image_values) > 0) {
        echo "<p class='error'>" . _("Page name collisions!") . "</p>";
        echo "<p>";
        echo _("The destination project already has pages with these 'image' values:");
        echo "</p>\n";
        echo "<pre>\n";
        foreach ($clashing_image_values as $clashing_image_value) {
            echo html_safe("    $clashing_image_value\n");
        }
        echo "</pre>\n";
        error_and_die(_("Aborting due to page name collisions!"));
    }

    $clashing_fileid_values = array_intersect($c_dst_fileid_, $all_fileid_values_['to']);
    if (count($clashing_fileid_values) > 0) {
        echo "<p class='error'>" . _("Page name collisions!") . "</p>";
        echo "<p>";
        echo _("The destination project already has pages with these 'fileid' values:");
        echo "</p>\n";
        echo "<pre>\n";
        foreach ($clashing_fileid_values as $clashing_fileid_value) {
            echo html_safe("    $clashing_fileid_value\n");
        }
        echo "</pre>\n";
        error_and_die(_("Aborting due to page name collisions!"));
    }

    echo "<p>";
    if ($page_name_handling == 'PRESERVE_PAGE_NAMES') {
        echo _("There don't appear to be any page name collisions.");
    } elseif ($page_name_handling == 'RENUMBER_PAGES') {
        echo _("As expected, there aren't any page name collisions.");
    }
    echo "</p>";

    // Report the settings/selections that were chosen

    echo "<h3>" . _("Per your request:") . "</h3>";

    echo "<ul>";
    echo "<li>" . _("Page Name Handling:");
    echo "&nbsp;<code>" . html_safe($page_name_handling) . "</code>";
    echo "</li>\n";

    echo "<li>";
    if ($transfer_notifications) {
        echo _("Event notifications WILL be transferred");
    } else {
        echo _("Event notifications WILL NOT be transferred");
    }
    echo "</li>\n";

    echo "<li>\n";
    if ($add_deletion_reason) {
        echo _("The following deletion reason will be added to the source project:");
        echo "&nbsp;&nbsp;<code>" . sprintf(_("'merged into %s'"), html_safe($projectid_['to'])) . "</code>";
    } else {
        echo _("A deletion reason WILL NOT be added to the source project");
    }
    echo "</li>\n";

    echo "<li>\n";
    if ($merge_wordcheck_data) {
        echo _("WordCheck files and events from the source project WILL be merged into the destination project");
    } else {
        echo _("WordCheck files and events WILL NOT be merged");
    }
    echo "</li>\n";
    echo "</ul>\n";

    if ($just_checking) {
        return;
    }

    // ---BEGIN COPY OPERATIONS-----------------------------------------

    // Emit a nice big heads-up notification/separator
    echo "<hr><h2>" . _("Copying Pages...") . "</h2>\n";

    $for_real = 1;

    // cd to projects dir to simplify filesystem moves
    global $projects_dir;
    echo "<p>" . _("Changing into projects directory:");
    echo " (<code>cd " . html_safe($projects_dir) . "</code>)" . "</p>\n";
    if (! chdir($projects_dir)) {
        error_and_die("Unable to 'cd " . html_safe($projects_dir) . "'");
    }

    $items_array = [];
    foreach ($column_names_['to'] as $col) {
        // if the column exists in both, we want to pull the value over
        if (!in_array($col, $extra_columns_['to'])) {
            $items_array[] = $col;
        }
        // if it's a time field (which is an integer), set the value to 0
        // we could do a much more robust check by querying the database
        // for the column type but that seems overkill
        elseif (endswith($col, "_time")) {
            $items_array[] = 0;
        }
        // otherwise use an empty string
        else {
            $items_array[] = '""';
        }
    }

    $items_list_template = join(',', $items_array);

    // Switch to <pre> for the 'technical' output section
    echo "<pre>\n";

    for ($j = 0; $j < $n_pages_to_copy; $j++) {
        $c_src_image = $c_src_image_[$j];
        $c_src_fileid = $c_src_fileid_[$j];
        $c_dst_image = $c_dst_image_[$j];
        $c_dst_fileid = $c_dst_fileid_[$j];

        echo "\n";
        echo html_safe("    $c_src_image ...\n");

        $items_list = str_replace(
            [
                'image',
                'fileid',
            ],
            [
                sprintf("'%s'", DPDatabase::escape($c_dst_image)),
                sprintf("'%s'", DPDatabase::escape($c_dst_fileid)),
            ],
            $items_list_template);

        $insert = "
            INSERT INTO {$projectid_['to']}
            SELECT $items_list
            FROM {$projectid_['from']}
        ";
        $query = sprintf("
            %s
            WHERE image = '%s'",
            $insert,
            DPDatabase::escape($c_src_image)
        );
        // FIXME These are very long and should perhaps be suppressed, wrapped or made smaller.
        echo html_safe($query) . "\n";
        if ($for_real) {
            DPDatabase::query($query);
            $n = DPDatabase::affected_rows();
            echo sprintf(_("%d rows inserted."), $n) . "\n";
            if ($n != 1) {
                error_and_die("unexpected number of rows inserted");
            }
        }

        $c_src_path = "{$projectid_['from']}/$c_src_image";
        $c_dst_path = "{$projectid_['to']}/$c_dst_image";

        echo "\n" . html_safe(sprintf(_('Copying %1$s to %2$s...'), $c_src_path, $c_dst_path)) . " ";

        if ($for_real) {
            $success = copy($c_src_path, $c_dst_path);
            $s = ($success ? _('Copy succeeded.') : _('<b>Copy failed!</b>'));
            echo $s . "\n";
        }
    }
    echo "</pre>\n";

    project_recalculate_page_counts($projectid_['to']);
    echo "<p>" . _("Page counts recalculated") . "</p>\n";

    if ($transfer_notifications && $for_real) {
        echo "<p>" . _("Transferring event notifications...") . "</p>\n";

        // for each subscribable event
        //   for each user subscribed to "from" project
        //      subscribe user to "to" project
        global $subscribable_project_events;
        $count = 0;
        foreach ($subscribable_project_events as $event => $label) {
            $query = sprintf("
                SELECT username FROM user_project_info
                WHERE projectid = '%s' AND
                    iste_$event = 1",
                $projectid_['from'] // validated input
            );
            $res1 = DPDatabase::query($query);
            while ([$username] = mysqli_fetch_row($res1)) {
                set_user_project_event_subscription($username,
                                                     $projectid_['to'],
                                                     $event, 1);
                $count++;
            }
        }
        echo "<p>" . sprintf(_("%d notifications transferred."), $count) . "</p>\n";
    }

    if ($add_deletion_reason) {
        echo "<p>" . _("Adding deletion reason to source project.") . "</p>\n";
        $query = sprintf("
              UPDATE projects
              SET deletion_reason = 'merged into %s'
              WHERE projectid = '%s'",
              $projectid_['to'], // validated input
              $projectid_['from'] // validated input
        );
        echo "<code>" . html_safe($query) . "</code>";
        if ($for_real) {
            DPDatabase::query($query);
            $n = DPDatabase::affected_rows();
            echo "<p>" . sprintf(_("%d rows updated."), $n) . "</p>\n";
        }
    }

    if ($merge_wordcheck_data) {
        echo "<p>" . _("Merging WordCheck files and events.") . "</p>\n";
        if ($for_real) {
            merge_project_wordcheck_data($projectid_['from'], $projectid_['to']);
        }
    }
}

function str_max(& $arr)
{
    $max_so_far = null;
    foreach ($arr as $s) {
        if (is_null($max_so_far) || strcmp($s, $max_so_far) > 0) {
            $max_so_far = $s;
        }
    }
    return $s;
}

function error_and_die($message)
{
    echo "<p class='error'>" . html_safe($message) . "</p>";
    exit();
}
