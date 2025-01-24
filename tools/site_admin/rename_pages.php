<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');

require_login();

if (!user_is_a_sitemanager()) {
    die(_("You are not authorized to invoke this script."));
}

$extra_args["css_data"] = "
    input[type=text] { font-family: monospace; }
";

$title = _("Rename Pages");
output_header($title, NO_STATSBAR, $extra_args);

echo "<h1>$title</h1>";

echo "<p>" . _("This tool will allow you to rename pages in a project.") . "</p>";

$projectid = get_projectID_param($_REQUEST, 'projectid', true);

if (!$projectid) {
    echo "<form method='GET'>";
    echo "Project: ";
    echo "<input type='text' name='projectid' size='23' required>";
    echo "<input type='submit' value='Go'>";
    echo "</form>\n";
    exit;
}

$project = new Project($projectid);
$title = $project->nameofwork;

echo "<pre>";
echo "projectid: $projectid\n";
echo "title    : " . html_safe($title) . "\n";
echo "</pre>\n";

$res = DPDatabase::query("
    SELECT fileid, image
    FROM $projectid
    ORDER BY image
");

$current_image_for_fileid_ = [];
while ([$fileid, $image] = mysqli_fetch_row($res)) {
    $current_image_for_fileid_[$fileid] = $image;
}

// -----------

$submit_button = $_POST['submit_button'] ?? '';

switch ($submit_button) {
    case '':

        echo "<form method='post'>";
        echo "<input type='hidden' name='projectid' value='$projectid'>\n";

        echo "<hr>";
        echo "<p>";
        echo "If you just want to number all pages serially\n";
        echo "(while maintaining their current order), check here,\n";
        echo "and specify a starting name (including any leading zeroes).\n";
        echo "</p>";

        echo "<input type='checkbox' name='renumber_from_n'> Renumber from ";
        echo "<input type='text' size='4' name='renumbering_start' value='001'>\n";
        echo "<br>";
        echo "<input type='submit' name='submit_button' value='Check renamings'>";

        echo "<hr>";
        echo "<p>";
        echo "Otherwise, for each page that you wish to rename, type in the new fileid.\n";
        echo "('.png' will automatically be appended to obtain the new image name.)\n";
        echo "</p>";
        echo "<p>";
        echo "Please note that <b>leading zeroes are significant</b>.\n";
        echo "Leave a box blank if you don't want to rename that page.\n";
        echo "</p>";

        echo "<table class='basic striped'>\n";

        echo "<tr>";
        echo "<th>fileid</th>";
        echo "<th>image</th>";
        echo "<th>new fileid<br>(image base)</th>";
        echo "</tr>";
        echo "\n";

        echo_name_mapping_subform();

        echo "</table>";

        echo "<input type='submit' name='submit_button' value='Check renamings'>";

        echo "</form>\n";
        break;

    case 'Check renamings':

        $renumber_from_n = $_POST['renumber_from_n'] ?? 'off';

        if ($renumber_from_n == 'on') {
            // Ignore any name-mapping in $_POST.

            $start_str = $_POST['renumbering_start'] ?? '001';

            $n_matches = preg_match('/^(\D*)(\d+)(\D*)$/', $start_str, $matches);
            if ($n_matches == 0) {
                echo "<p class='error'>";
                echo "Starting name '$start_str' is invalid.\n";
                echo "Please hit 'Back' and fix.\n";
                echo "</p>";
                return;
            }

            [$all, $prefix, $numeral, $postfix] = $matches;
            $n_digits = strlen($numeral);
            $numeral_format = "%0{$n_digits}d";

            $start_number = intval($numeral);
            $end_number = $start_number + count($current_image_for_fileid_) - 1;
            if ($end_number >= pow(10, $n_digits)) {
                echo "<p class='error'>";
                echo "The last page would be numbered $end_number, which exceeds $n_digits digits.\n";
                echo "Please hit 'Back' and fix.\n";
                echo "</p>";
                return;
            }

            $new_fileid_for_ = [];
            $i = $start_number;
            foreach ($current_image_for_fileid_ as $fileid => $image) {
                $new_numeral = sprintf($numeral_format, $i);
                $new_fileid = $prefix . $new_numeral . $postfix;
                $new_fileid_for_[$fileid] = $new_fileid;
                $i++;
            }
        } else {
            $new_fileid_for_ = get_requested_name_mapping();
        }

        echo "You requested:\n";
        echo "<table class='basic striped'>\n";

        echo "<tr>";
        echo "<th colspan='2'>old</th>";
        echo "<th>-></th>";
        echo "<th colspan='2'>new</th>";
        echo "</tr>";
        echo "\n";

        echo "<tr>";
        echo "<th>fileid</th>";
        echo "<th>image</th>";
        echo "<th></th>";
        echo "<th>fileid</th>";
        echo "<th>image</th>";
        echo "</tr>";
        echo "\n";

        foreach ($current_image_for_fileid_ as $old_fileid => $old_image) {
            $new_fileid = $new_fileid_for_[$old_fileid];
            $new_image = "$new_fileid.png";

            echo "<tr>";
            echo "<td>$old_fileid</td>";
            echo "<td>$old_image</td>";
            if ($new_fileid == $old_fileid && $new_image == $old_image) {
                echo "<td>==</td>";
            } else {
                echo "<td>-></td>";
            }
            echo "<td>$new_fileid</td>";
            echo "<td>$new_image</td>";
            echo "</tr>";
            echo "\n";
        }
        echo "</table>";

        echo "\n";

        // ------------

        $n_errors = 0;

        foreach (array_count_values($new_fileid_for_) as $new_fileid => $freq) {
            if ($new_fileid != '' && $freq > 1) {
                echo "<p class='error'>";
                echo "Error: You have requested $new_fileid as the new fileid for $freq different pages.\n";
                echo "</p>\n";
                $n_errors++;
            }
        }

        if ($n_errors > 0) {
            echo "\n";
            die("Hit 'Back' and fix.");
        }

        // ------------

        // Simulate the renamings to check that they won't violate the
        // uniqueness constraint on each of the fileid and image columns.
        // Try it both backward and forward.

        $direction_that_works = null;

        echo "<pre>";
        foreach (['forward', 'backward'] as $direction) {
            echo "<hr>";
            echo "Considering doing the renamings $direction ...\n";
            echo "\n";

            $max_n_failed_steps_to_show = 3;
            $n_failed_steps = 0;

            $sim = $current_image_for_fileid_; // copies it

            $olds = (
                $direction == 'forward' ?
                $current_image_for_fileid_ :
                array_reverse($current_image_for_fileid_, true)
            );

            $i = 0;
            foreach ($olds as $old_fileid => $old_image) {
                $i++;

                $new_fileid = $new_fileid_for_[$old_fileid];
                $new_image = "$new_fileid.png";

                unset($sim[$old_fileid]);

                $reasons = [];
                if (array_key_exists($new_fileid, $sim)) {
                    $reasons[] = "a row with fileid='$new_fileid' will already exist";
                }

                if (in_array($new_image, $sim)) {
                    $reasons[] = "a row with image='$new_image' will already exist";
                }

                if (count($reasons) > 0) {
                    $n_failed_steps++;
                    if ($n_failed_steps <= $max_n_failed_steps_to_show) {
                        echo "Renamings will fail at step #$i:\n";
                        echo "    ($old_fileid,$old_image) -> ($new_fileid,$new_image)\n";
                        echo "because:\n";
                        foreach ($reasons as $reason) {
                            echo "    $reason\n";
                        }
                        echo "\n";
                    }
                }

                $sim[$new_fileid] = $new_image;
            }

            if ($n_failed_steps == 0) {
                echo "Okay, it looks like $direction will work.\n\n";
                $direction_that_works = $direction;
                break;
            } else {
                if ($n_failed_steps > $max_n_failed_steps_to_show) {
                    $n_more = $n_failed_steps - $max_n_failed_steps_to_show;
                    echo "and $n_more more such failures.\n";
                    echo "\n";
                }

                echo "So $direction won't work.\n\n";
            }
        }

        if (is_null($direction_that_works)) {
            die("Neither forward nor backward works.");
        }

        echo "</pre>";

        // ------------

        echo "<hr>";
        echo "If that's not what you want, hit 'Back' and fix. Otherwise...\n";
        echo "<form method='post'>\n";
        echo "<input type='hidden' name='projectid' value='$projectid'>\n";
        echo "<input type='hidden' name='direction' value='$direction_that_works'>\n";
        echo "<input type='submit' name='submit_button' value='Do renamings'>\n";

        echo_name_mapping_hiddens($new_fileid_for_);

        echo "</form>";
        break;

    case 'Do renamings':
        $for_real = true;

        $new_fileid_for_ = get_requested_name_mapping();
        $direction = $_POST['direction'] ?? '';

        if (empty($direction)) {
            die("direction param is empty");
        } elseif ($direction != 'forward' && $direction != 'backward') {
            die("direction param is '$direction'");
        }

        echo "<pre>";

        echo "Doing renamings $direction ...\n\n";

        // cd to project dir to simplify filesystem moves
        $project_dir = "$projects_dir/$projectid";
        echo "cd $project_dir\n";
        if (! chdir($project_dir)) {
            die("Unable to 'cd $project_dir'");
        }
        echo "\n";

        $olds = (
            $direction == 'forward' ?
            $current_image_for_fileid_ :
            array_reverse($current_image_for_fileid_, true)
        );

        foreach ($olds as $old_fileid => $old_image) {
            $new_fileid = $new_fileid_for_[$old_fileid];
            assert(!empty($new_fileid));
            $new_image = "$new_fileid.png";

            echo "($old_fileid,$old_image) ";
            if ($new_fileid == $old_fileid && $new_image == $old_image) {
                echo "-> no change\n";
            } else {
                echo "-> ($new_fileid,$new_image) ...\n";

                // database
                echo "    database:";

                // first the project table
                $project_query = sprintf(
                    "
                    UPDATE $projectid
                    SET fileid = '%s', image = '%s'
                    WHERE fileid = '%s' AND image = '%s'
                    ",
                    DPDatabase::escape($new_fileid),
                    DPDatabase::escape($new_image),
                    DPDatabase::escape($old_fileid),
                    DPDatabase::escape($old_image)
                );
                echo $project_query;
                if ($for_real) { /** @phpstan-ignore-line */
                    DPDatabase::query($project_query);
                    $n = DPDatabase::affected_rows();
                    echo "
                        $n rows affected.
                    ";
                    if ($n != 1) {
                        echo "\n";
                        echo "Unexpected number of rows affected.\n";
                        die("Aborting");
                    }
                }

                // now the wordcheck_events table
                $wce_query = sprintf(
                    "
                    UPDATE wordcheck_events
                    SET image = '%s'
                    WHERE projectid = '%s' AND image = '%s'
                    ",
                    DPDatabase::escape($new_image),
                    DPDatabase::escape($projectid),
                    DPDatabase::escape($old_image)
                );
                echo $wce_query;
                if ($for_real) { /** @phpstan-ignore-line */
                    DPDatabase::query($wce_query);
                    $n = DPDatabase::affected_rows();
                    echo "
                        $n rows affected.
                    ";
                }

                // finally, the page_events table
                $pe_query = sprintf(
                    "
                    UPDATE page_events
                    SET image = '%s'
                    WHERE projectid = '%s' AND image = '%s'
                    ",
                    DPDatabase::escape($new_image),
                    DPDatabase::escape($projectid),
                    DPDatabase::escape($old_image)
                );
                echo $pe_query;
                if ($for_real) { /** @phpstan-ignore-line */
                    DPDatabase::query($pe_query);
                    $n = DPDatabase::affected_rows();
                    echo "
                        $n rows affected.
                    ";
                }
                echo "\n";

                // file-system
                echo "    filesystem:";
                echo "
                    mv $old_image $new_image
                ";
                if ($for_real) { /** @phpstan-ignore-line */
                    $success = rename($old_image, $new_image);
                    $s = ($success ? 'succeeded' : 'FAILED');
                    echo "
                        mv $s
                    ";
                    if (!$success) {
                        echo "\n";
                        die("Aborting");
                    }
                }
                echo "\n";
            }
        }
        echo "</pre>";
        break;

    default:
        echo "Whaaaa? submit_button='$submit_button'";
        break;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// As reported in http://www.pgdp.net/c/tasks.php?action=show&task_id=1525
// this script couldn't handle projects with more than 1000 pages.
// bfoley tracked it down to: https://bugs.php.net/bug.php?id=55810
//
// The restriction appears to be that count($_POST) <= 1001, and likewise
// for any array that $_POST contains. But there's no restriction on the
// total number of array-entries that $_POST can recursively contain.
//
// So, where formerly we would declare the per-page controls with:
//     name='new_fileid_for_[$fileid]'
// the workaround is to instead declare them with:
//     name='nff_[$k][$fileid]'
// where $k increments infrequently, but often enough to prevent too many
// entries in any given array within PHP's $_POST.
//
// Re $WORKAROUND_MAX:
// -- It could (apparently) be as high as 1001, but making it smaller means
//    that the code will be exercised somewhat better.
// -- It doesn't have to have the same value in the two echo_name_mapping_*
//    functions, but I don't see a good reason for them to differ.
// -- get_requested_name_mapping() works regardless of the value that was used.

function echo_name_mapping_subform(): void
{
    global $current_image_for_fileid_;

    $WORKAROUND_MAX = 100;
    $i = 0;
    foreach ($current_image_for_fileid_ as $fileid => $image) {
        $k = floor($i / $WORKAROUND_MAX);
        $i += 1;
        echo "<tr>";
        echo "<td>$fileid</td>";
        echo "<td>$image</td>";
        echo "<td><input type='text' style='width: 97%'; name='nff_[$k][$fileid]'></td>";
        echo "</tr>";
        echo "\n";
    }
}

/** @param array<string, string> $new_fileid_for_ */
function echo_name_mapping_hiddens(array $new_fileid_for_): void
{
    $WORKAROUND_MAX = 100;
    $i = 0;
    foreach ($new_fileid_for_ as $old_fileid => $new_fileid) {
        $k = floor($i / $WORKAROUND_MAX);
        $i += 1;
        echo "<input type='hidden' name='nff_[$k][$old_fileid]' value='$new_fileid'>";
    }
}

/** @return array<string, string> */
function get_requested_name_mapping(): array
{
    $nff_ = $_POST['nff_'] ?? null;

    if (empty($nff_)) {
        die("nff_ param is empty");
    }

    foreach ($nff_ as $k => $part_new_fileid_for_) {
        // (Ignore $k, it doesn't convey any useful information.)

        foreach ($part_new_fileid_for_ as $old_fileid => $new_fileid) {
            assert(!isset($new_fileid_for_[$old_fileid]));
            $new_fileid_for_[$old_fileid] = $new_fileid;
        }
    }

    // If the user left the field empty, it means don't rename that page.
    foreach ($new_fileid_for_ as $old_fileid => $new_fileid) {
        if (empty($new_fileid)) {
            $new_fileid_for_[$old_fileid] = $old_fileid;
        }
    }

    return $new_fileid_for_;
}
