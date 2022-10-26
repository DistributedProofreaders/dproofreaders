<?php
include_once('RoundDescriptor.inc'); // get_Round_for_round_number()
include_once('wordcheck_engine.inc'); // get_file_info_object()
include_once('links.inc'); // new_window_link()
include_once('slim_header.inc'); // slim_header()
include_once($relPath.'../tools/project_manager/post_files.inc'); // page_info_query page_info_fetch
include_once('bad_bytes.inc');
include_once('image_check.inc'); // get_image_size_error()

// Transition callback function to prevent a project state change if PQC
// results return an error.
function gate_on_pqc($projectid)
{
    global $code_url;

    $results = get_pqc_test_results($projectid);

    // loop thorugh results looking for errors
    $found_error = false;
    foreach ($results as $function => $test_result) {
        if ($test_result["status"] == _("Error")) {
            $found_error = true;
            break;
        }
    }

    if ($found_error) {
        $title = _("Change Project State");
        slim_header($title);
        echo "<h1>$title</h1>";

        echo "<p>";
        echo sprintf(_("One or more errors were found when checking the project. Review the <a href='%s'>project quick check results</a>, address any problems found, and try the project transition again."), "$code_url/tools/project_manager/project_quick_check.php?projectid=$projectid");
        echo "</p>";

        echo "<p>";
        echo _("If you believe these results are incorrect, please contact db-req for assistance.");
        echo "</p>";

        echo "<p>";
        echo sprintf(_("Return to the <a %s>project page</a>"), "href='$code_url/project.php?id=$projectid'");
        echo "</p>";

        echo "<h2>" . _("Project Quick Check Summary") . "</h2>";
        show_pqc_result_summary($results, false);

        exit();
    }
}

function show_pqc_result_summary($results, $output_links = true)
{
    echo "<table class='basic striped'>";
    echo "<tr>";
    echo "<th>" . _("Test") . "</th>";
    echo "<th>" . _("Status") . "</th>";
    echo "<th>" . _("Summary") . "</th>";
    echo "</tr>";
    foreach ($results as $function => $test_result) {
        echo "<tr>";
        echo "<td>" . $test_result["name"] . "</td>";
        echo "<td>";
        if ($output_links) {
            echo "<a href='#$function'>";
        }
        echo $test_result["status"];
        if ($output_links) {
            echo "</a>";
        }
        echo "</td>";
        $css = get_css_for_pqc_status($test_result["status"]);
        echo "<td $css>" . $test_result["summary"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function get_css_for_pqc_status($status)
{
    if ($status == _("Warning")) {
        $css = "class='warning'";
    } elseif ($status == _("Error")) {
        $css = "class='error'";
    } else {
        $css = '';
    }
    return $css;
}

function get_pqc_test_results($projectid)
{
    global $test_functions;

    $result = [];
    foreach ($test_functions as $function) {
        $result[$function] = $function($projectid);
    }
    return $result;
}

// ---------------------------------------------------------------------------
// Test functions

// $test_functions, an array of function names, specifies the list of test
// functions that will be applied to the project.
//
// Each test function:
// - is declared later in this file
// - by convention has a name that begins "_test_project_"
// - is called with a single $projectid argument
// - performs a particular kind of check on the given project
// - returns an associative array of the form
//    [
//        "name" => $test_name,  # name of the test
//        "description" => $test_desc,  # description of the test
//        "status" => $status,  # overall result of the test
//            # one of [ _("Success"), _("Warning"), _("Error"), _("Skipped") ]
//        "summary" => $summary,  # result summary string (no HTML)
//        "details" => $details,  # result details (can contain HTML)
//    ]

global $test_functions;
$test_functions = [
    // Automatic tests
    "_test_project_has_all_page_images",
    "_test_project_for_bad_bytes",
    "_test_project_for_corrupt_pngs",
    "_test_project_for_small_images",
    "_test_project_for_large_page_images",
    "_test_project_for_illo_images",
    "_test_project_for_word_lists",
    "_test_project_for_valid_clearance",
    // Manual tests
    "_test_project_for_credited_source",
    "_test_project_for_correct_metadata",
    "_test_project_for_missing_pages",
];

function _test_project_for_large_page_images($projectid)
{
    global $projects_dir;
    global $projects_url;
    global $page_image_size_limit;

    $test_name = _("Large Page Image Files");
    $test_desc = _("This test checks to see if there are any unusually large image files within a project.");

    $project = new Project($projectid);
    if ($project->pages_table_exists) {
        $sql = "SELECT image FROM $projectid ORDER BY image";
        $result_res = DPDatabase::query($sql);

        $details = "<table class='basic striped'>";
        $details .= "<tr>";
        $details .= "<th>" . _("Image") . "</th>";
        $details .= "<th>" . _("Size") . "</th>";
        $details .= "</tr>";

        $num_large_images = 0;
        while ([$image] = mysqli_fetch_row($result_res)) {
            // the the file's info
            $file_info = get_file_info_object($image, "$projects_dir/$projectid", "$projects_url/$projectid");

            // check to see if the file is > size limit
            $error = get_image_size_error($file_info->size);
            if (isset($error)) {
                $details .= "<tr>";
                $details .= "<td><a href='{$file_info->abs_url}'>" . html_safe($image) . "</a></td>";
                $details .= "<td>" . $error . "</td>";
                $details .= "</tr>";

                $num_large_images++;
            }
        }

        mysqli_free_result($result_res);

        $details .= "</table>";

        if ($num_large_images == 0) {
            $status = _("Success");
            $summary = sprintf(_("No pages with images > %dkb."), $page_image_size_limit);
            $details = "";
        } else {
            $status = _("Warning");
            $summary = sprintf(_("%d pages had images > %dkb, see detail."), $num_large_images, $page_image_size_limit);
        }
    } else {
        $status = _("Skipped");
        $summary = _("Page table does not exist.");
        $details = "";
    }

    return ["name" => $test_name, "description" => $test_desc, "status" => $status, "summary" => $summary, "details" => $details];
}

function _test_project_for_corrupt_pngs($projectid)
{
    global $projects_dir;
    global $projects_url;

    $test_name = _("Corrupt page PNGs");
    $test_desc = _("This test checks to see if any page PNGs are corrupt or have internal errors.");

    $project = new Project($projectid);

    // see if pngcheck is installed
    exec('pngcheck', $output, $return_code);
    if ($return_code != 2) {
        $status = _("Skipped");
        $summary = _("pngcheck program not available on this system.");
        $details = "";
    } elseif ($project->pages_table_exists) {
        $sql = "SELECT image FROM $projectid ORDER BY image";
        $result_res = DPDatabase::query($sql);

        $details = "<table class='basic striped'>";
        $details .= "<tr>";
        $details .= "<th>" . _("Image") . "</th>";
        $details .= "<th>" . _("Status") . "</th>";
        $details .= "</tr>";

        $error_pngs = 0;
        while ([$image] = mysqli_fetch_row($result_res)) {
            // the the file's info
            $file_info = get_file_info_object($image, "$projects_dir/$projectid", "$projects_url/$projectid");

            // if the file doesn't exist, skip it -- we're not testing for that
            if (!$file_info->exists) {
                continue;
            }

            $path_info = pathinfo($file_info->abs_path);

            if (strtolower($path_info["extension"]) == "png") {
                $output = "";
                exec('pngcheck -q ' . escapeshellarg($file_info->abs_path), $output, $return_code);
                if ($return_code != 0) {
                    $details .= "<tr>";
                    $details .= "<td><a href='{$file_info->abs_url}'>" . html_safe($image) . "</a></td>";
                    $details .= "<td>" . str_replace($file_info->abs_path, "", $output[0]) . "</td>";
                    $details .= "</tr>";

                    $error_pngs++;
                }
            }
        }

        mysqli_free_result($result_res);

        $details .= "</table>";

        if ($error_pngs == 0) {
            $status = _("Success");
            $summary = _("No corrupt PNGs.");
            $details = "";
        } else {
            $status = _("Error");
            $summary = _("Some pages images have corrupt PNGs.");
        }
    } else {
        $status = _("Skipped");
        $summary = _("Page table does not exist.");
        $details = "";
    }

    return ["name" => $test_name, "description" => $test_desc, "status" => $status, "summary" => $summary, "details" => $details];
}

function _test_project_for_small_images($projectid)
{
    global $projects_dir;
    global $projects_url;

    $test_name = _("Small page images");
    $test_desc = _("This test checks to see if any page image is possibly too small.");

    $project = new Project($projectid);

    if ($project->pages_table_exists) {
        $sql = "SELECT image FROM $projectid ORDER BY image";
        $result_res = DPDatabase::query($sql);

        $details = "<table class='basic striped'>";
        $details .= "<tr>";
        $details .= "<th>" . _("Image") . "</th>";
        $details .= "<th>" . _("Status") . "</th>";
        $details .= "</tr>";

        $error_images = false;
        while ([$image] = mysqli_fetch_row($result_res)) {
            // the the file's info
            $file_info = get_file_info_object($image, "$projects_dir/$projectid", "$projects_url/$projectid");

            // if the file doesn't exist, skip it -- we're not testing for that
            if (!$file_info->exists) {
                continue;
            }

            $image_status = "";
            $size = getimagesize($file_info->abs_path);
            if ($size == false) {
                $image_status = _("Unable to determine page image size");
            } elseif ($size[0] < 1000 || $size[1] < 1000) {
                $image_status = sprintf(_("Page image size of %dx%d may be too small"), $size[0], $size[1]);
            }

            if (!empty($image_status)) {
                $details .= "<tr>";
                $details .= "<td><a href='{$file_info->abs_url}'>" . html_safe($image) . "</a></td>";
                $details .= "<td>" . $image_status . "</td>";
                $details .= "</tr>";
                $error_images = true;
            }
        }

        mysqli_free_result($result_res);

        $details .= "</table>";

        if ($error_images) {
            $status = _("Warning");
            $summary = _("Some page images may be too small.");
        } else {
            $status = _("Success");
            $summary = _("No small images.");
            $details = "";
        }
    } else {
        $status = _("Skipped");
        $summary = _("Page table does not exist.");
        $details = "";
    }

    return ["name" => $test_name, "description" => $test_desc, "status" => $status, "summary" => $summary, "details" => $details];
}

function _test_project_for_illo_images($projectid)
{
    global $projects_dir;
    global $projects_url;
    global $code_url;

    $test_name = _("Illustration Image Files");
    $test_desc = _("This test checks to see if there are any non-page (probably illustration) files within a project. Even for books without illustrations, adding a high-res image of the cover and/or title page is encouraged, where at all possible.");

    $project = new Project($projectid);
    if ($project->pages_table_exists) {
        $sql = "SELECT image FROM $projectid ORDER BY image";
        $result_res = DPDatabase::query($sql);

        $page_image_names = [];
        while ([$image] = mysqli_fetch_row($result_res)) {
            $page_image_names[] = $image;
        }
        mysqli_free_result($result_res);

        chdir("$projects_dir/$projectid");
        $existing_image_names = glob("*.{png,jpg}", GLOB_BRACE);
        // That returns a sorted list of the .png files
        // followed by a sorted list of the .jpg files,
        // but we want the two lists interleaved...
        sort($existing_image_names);

        $nonpage_image_names = array_diff($existing_image_names, $page_image_names);

        $num_nonpage_image_files = count($nonpage_image_names);
        if ($num_nonpage_image_files > 0) {
            $status = _("Success");
            $summary = sprintf(_("%d illustration files found"), $num_nonpage_image_files);
        } else {
            $status = _("Warning");
            $summary = _("No illustration files found");
        }

        $details = "
            <p><a href='$code_url/tools/proofers/images_index.php?project=$projectid'>" . _("Image Index") . "</a></p>
            <p>$summary</p>
        ";
    } else {
        $status = _("Skipped");
        $summary = _("Page table does not exist.");
        $details = "";
    }

    return ["name" => $test_name, "description" => $test_desc, "status" => $status, "summary" => $summary, "details" => $details];
}

function _test_project_for_missing_pages($projectid)
{
    global $code_url;

    $display_image_link = new_window_link(
        "$code_url/tools/page_browser.php?project=$projectid&amp;imagefile=001.png&amp;showreturnlink=0&amp;preload=next&amp;percent=50",
        _("Display Image")
    );

    $test_name = _("Missing Pages");
    $test_desc = sprintf(_("This manual test requires looking through each page of a project to see if there are missing pages. This is done by looking for missing page numbers, eg: image 100.png says it's page 80 and image 101.png says it's page 82. To perform this check, you can look at the pages online from the <a href='%s'>Page Details</a> page or download the project files from the project page and check them offline. Please check to make sure all illustration images are also loaded."),
        "$code_url/tools/project_manager/page_detail.php?project=$projectid");

    $status = _("Manual");
    $summary = _("This is a manual test.");
    $details = "";

    return ["name" => $test_name, "description" => $test_desc, "status" => $status, "summary" => $summary, "details" => $details];
}

function _test_project_for_correct_metadata($projectid)
{
    global $code_url;

    $page_details_link = new_window_link(
        "$code_url/tools/project_manager/page_detail.php?project=$projectid",
        _("Page Details")
    );

    $test_name = _("Correct Project Metadata");
    $test_desc = sprintf(_("This manual test requires looking at a few pages to confirm that the project's Genre, Language, and Difficulty are set correctly. The %s page is very useful for this purpose."), $page_details_link);

    $project = new Project($projectid);
    $genre = $project->genre;
    $language = $project->language;
    $difficulty = $project->difficulty;

    $status = _("Manual");
    $summary = sprintf(_("This is a manual test: %s. Genre: <b>%s</b>. Language: <b>%s</b>, Difficulty: <b>%s</b>"), $page_details_link, html_safe($genre), html_safe($language), html_safe($difficulty));
    $details = "";
    $details .= "<p><b>" . html_safe(_("Genre")) . "</b>: " . html_safe($genre) . "</p>";
    $details .= "<p><b>" . html_safe(_("Language")) . "</b>: " . html_safe($language) . "</p>";
    $details .= "<p><b>" . html_safe(_("Difficulty")) . "</b>: " . html_safe($difficulty) . "</p>";

    return ["name" => $test_name, "description" => $test_desc, "status" => $status, "summary" => $summary, "details" => $details];
}

function _test_project_for_credited_source($projectid)
{
    $project = new Project($projectid);
    $image_source = $project->image_source_name;

    $test_name = _("Credited Source");
    $test_desc = _("This manual test confirms if a valid source has been credited for the images.");

    $status = _("Manual");
    $summary = sprintf(_("This is a manual test. Image Source: <b>%s</b>"), html_safe($image_source));
    $details = "";
    $details .= "<p><b>" . _("Image Source") . "</b>: " . html_safe($image_source) . "</p>";

    return ["name" => $test_name, "description" => $test_desc, "status" => $status, "summary" => $summary, "details" => $details];
}

function _test_project_for_bad_bytes($projectid)
{
    global $code_url;

    $r['name'] = 'Bad Bytes in Page Text';
    $r['description'] = "This test checks the project's latest page-texts for bad byte-sequences.";

    $project = new Project($projectid);
    if (!$project->pages_table_exists) {
        $r['status'] = _("Skipped");
        $r['details'] = '';
        $r['summary'] = _("Page table does not exist.");
        return $r;
    }

    $pages_res = page_info_query($projectid, 'F2', 'LE');
    if ($pages_res === false) {
        echo "!page_info_query returned FALSE!\n";
        return null;
    }

    $page_details_url = "$code_url/tools/project_manager/page_detail.php?project=$projectid";
    $details_for_this_project = "
        <p><a href='$page_details_url'>Page Details</a></p>
        <p><small>" . sprintf(_("For more detail about this table, see %s"), "<a href='bad_bytes_explainer.php'>here</a>") . "</small></p>
        <table class='basic striped'>
        <tr>
            <th>" . _("Image") . "</th>
            <th>" . _("Text") . "</th>
            <th>" . _("#") . "</th>
            <th>" . _("Raw") . "</th>
            <th>" . _("Bytes") . "</th>
            <th>" . _("Codepoints") . "</th>
            <th>" . _("Likely intended character") . "</th>
            <th>" . _("Why bad") . "</th>
        </tr>
    ";

    $n_bad_pages = 0;
    $valid_codepoints = $project->get_valid_codepoints();

    while ([$text, $imagename, $proofers] = page_info_fetch($pages_res)) {
        $round_num = get_round_num_from_proofers($proofers);

        // note that this can change $text
        $occurrences = find_bad_byte_sequences_in_text($text);
        $invalid_chars = get_invalid_characters($text, $valid_codepoints);

        $number_bad_chars = count($occurrences) + count($invalid_chars);
        if ($number_bad_chars == 0) {
            // Nothing bad on this page!
            continue;
        }

        // page had at least one bad byte-sequence
        $n_bad_pages += 1;

        // write image and round columns
        $rowspan = $number_bad_chars;
        $img_url = "$code_url/tools/page_browser.php?project=$projectid&imagefile=$imagename";
        $text_url = "$code_url/tools/project_manager/downloadproofed.php?project=$projectid&amp;image=$imagename&amp;round_num=$round_num";
        $details_for_this_page = "<tr>\n";
        $details_for_this_page .= "<td class='top-align' rowspan='$rowspan'><a href='$img_url'>$imagename</a></td>\n";
        if ($round_num == 0) {
            $round_name = 'OCR';
        } else {
            $round = get_Round_for_round_number($round_num);
            $round_name = $round->id;
        }
        $details_for_this_page .= "<td class='top-align' rowspan='$rowspan'><a href='$text_url'>" . html_safe($round_name) . "</a></td>\n";

        // on the first row <tr> is already written out with image and round
        // columns so only write it on subsequent rows
        $first_row = true;
        // write bad bytes data
        ksort($occurrences);

        foreach ($occurrences as $raw => $n_occurrences) {
            $details_for_this_page .= write_row_start($first_row);
            $details_for_this_page .= "<td class='right-align'>$n_occurrences</td>\n";
            $details_for_this_page .= tds_for_bad_bytes($raw);
            $details_for_this_page .= "</tr>\n";
        }

        ksort($invalid_chars);
        foreach ($invalid_chars as $raw => $n_occurrences) {
            $details_for_this_page .= write_row_start($first_row);
            $details_for_this_page .= "<td class='right-align'>$n_occurrences</td>\n";
            $details_for_this_page .= tds_for_invalid_chars($raw);
            $details_for_this_page .= "</tr>\n";
        }

        $details_for_this_project .= $details_for_this_page;
    }

    $details_for_this_project .= "</table>";

    if ($n_bad_pages == 0) {
        $r['status'] = _('Success');
        $r['summary'] = _('No pages with bad characters.');
        $r['details'] = "";
    } else {
        $r['status'] = _('Error');
        $r['summary'] = sprintf(_("%d pages had bad characters, see detail."), $n_bad_pages);
        $r['details'] = $details_for_this_project;
    }

    return $r;
}

// write <tr> if not the first row
function write_row_start(&$first_row)
{
    if ($first_row) {
        $first_row = false;
        return "";
    } else {
        return "<tr>\n";
    }
}

// We can calculate what round number a page text was selected from by
// the number of proofers that has touched it.
function get_round_num_from_proofers($proofers)
{
    $round_num = 0;
    foreach ($proofers as $proofer) {
        if ($proofer == '') {
            break;
        }
        $round_num++;
    }

    return $round_num;
}

function tds_for_bad_bytes($raw)
// Returns a string containing TD elements
// that present the byte-sequence $raw in various ways,
// and say why it's bad.
{
    $tds = "";

    $tds .= "<td>$raw</td>";

    if (startswith($raw, '&')) {
        // It's a named or numeric character reference
        // so just show the reference itself,
        // rather than converting it to hex.
        $tds .= "<td>" . htmlspecialchars($raw) . "</td><td></td>";
    } else {
        $tds .= "<td>" . string_to_hex($raw) . "</td>"; // bytes
        $tds .= "<td>" . string_to_codepoints_string($raw) . "</td>"; // codepoints
    }

    [$intended_character, $why_bad] = get_remarks_for_bad_byte_sequence($raw);

    $tds .= "<td>" . htmlspecialchars($intended_character) . "</td>";

    $tds .= "<td>" . htmlspecialchars($why_bad) . "</td>";

    return $tds;
}

function tds_for_invalid_chars($raw)
{
    $tds = "<td>$raw</td>";
    $tds .= "<td>" . string_to_hex($raw) . "</td>"; // bytes
    $tds .= "<td>" . string_to_codepoints_string($raw) . "</td>"; // codepoints
    $tds .= "<td></td>"; // intended character
    $tds .= "<td>" . _("Codepoint not valid for project") . "</td>";

    return $tds;
}

function _test_project_for_word_lists($projectid)
{
    $num_good_words = count(load_project_good_words($projectid));
    $num_bad_words = count(load_project_bad_words($projectid));

    $test_name = _("Good/Bad Wordlists");
    $test_desc = _("This test checks if the project good and bad word lists are populated.");

    $details = "<p><ul>";
    $details .= "<li>" . _("Number of good words") . ": $num_good_words</li>";
    $details .= "<li>" . _("Number of bad words") . ": $num_bad_words</li>";
    $details .= "</ul></p>";

    if ($num_good_words || $num_bad_words) {
        $status = _("Success");
        $summary = _("At least one of the good and bad word lists have entries.");
    } else {
        $status = _("Warning");
        $summary = _("Both of the word lists are empty.");
    }

    return [
        "name" => $test_name,
        "description" => $test_desc,
        "status" => $status,
        "summary" => $summary,
        "details" => $details,
    ];
}

function _test_project_for_valid_clearance($projectid)
{
    $project = new Project($projectid);
    $clearance = $project->clearance;

    $test_name = _("Clearance");
    $test_desc = _("This test checks if the project has a clearance in a valid form. This doesn't mean that the clearance itself is good, just that it looks like it could be.");

    $details = "<p><b>" . _("Clearance Line") . "</b>: " . html_safe($clearance) . "</p>";

    $valid_pattern_1 = '/^20[0-9]{12}[a-z]+\.?$/';
    $valid_pattern_2 = '/^gbn[0-9]/';
    if (preg_match($valid_pattern_1, $clearance) || preg_match($valid_pattern_2, $clearance)) {
        $status = _("Success");
        $summary = _("Clearance line appears to be in a valid format.");
    } else {
        $status = _("Warning");
        $summary = _("Unrecognized clearance format.");
        $details .= "<p>" . _("Unrecognized clearance format, confirm the clearance line is correct.");
    }

    return [
        "name" => $test_name,
        "description" => $test_desc,
        "status" => $status,
        "summary" => $summary,
        "details" => $details,
    ];
}

function _test_project_has_all_page_images($projectid)
{
    global $projects_dir;
    global $projects_url;

    $test_name = _("Page images exist");
    $test_desc = _("This test validates that all page images exist on the server.");

    $project = new Project($projectid);

    if ($project->pages_table_exists) {
        $sql = "SELECT image FROM $projectid ORDER BY image";
        $result_res = DPDatabase::query($sql);

        $details = "<p>" . _("The following page images do not exist on the server.") . "</p>";
        $details .= "<ul>";
        $error_images = 0;
        $image_count = 0;
        while ([$image] = mysqli_fetch_row($result_res)) {
            // the the file's info
            $file_info = get_file_info_object($image, "$projects_dir/$projectid", "$projects_url/$projectid");

            if (!$file_info->exists) {
                $details .= "<li>$image</li>";
                $error_images++;
            }
            $image_count += 1;
        }
        $details .= "</ul>";

        mysqli_free_result($result_res);

        if ($image_count == 0) {
            $status = _("Error");
            $summary = _("Project has no page images.");
            $details = "<p>" . _("Project has no page images.") . "</p>";
        } elseif ($error_images == 0) {
            $status = _("Success");
            $summary = _("All images exist on the server.");
            $details = "";
        } else {
            $status = _("Error");
            $summary = _("Some images are missing on the server.");
        }
    } else {
        $status = _("Skipped");
        $summary = _("Page table does not exist.");
        $details = "";
    }

    return [
        "name" => $test_name,
        "description" => $test_desc,
        "status" => $status,
        "summary" => $summary,
        "details" => $details,
    ];
}
