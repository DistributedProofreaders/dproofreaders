<?php
$relPath = "../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'send_mail.inc');
include_once($relPath.'Project.inc'); // get_projectID_param()
include_once($relPath.'Stage.inc'); //user_can_work_in_stage()
include_once($relPath.'User.inc');
include_once($relPath.'project_states.inc'); // get_project_status_descriptor()
include_once($relPath.'misc.inc');  // array_get() startswith() attr_safe()
include_once($relPath.'faq.inc');
include_once($relPath.'pg.inc');

header_remove("Expires");
header_remove("Cache-Control");

require_login();

// -------------------------------------

$page_title = _('Post-Processing Verification Reporting');

output_header($page_title, NO_STATSBAR);

echo "\n<h1>$page_title</h1>\n";

// -------------------------------------

// To make PPVer collaboration easier, allow any PPVer to fill in the summary.
// (The link is still only shown to the PPVer with the project checked-out.)
// All summaries are sent to the PPVers' list, signed by the person filling
// out the summary, so a mischievous PPVer couldn't get away with anything, anyway.
if (!user_can_work_in_stage($pguser, 'PPV')) {
    echo _("You're not recorded as a Post-Processing Verifier.
            If you feel this is an error, please contact a Site Administrator.");
    exit();
}

// -------------------------------------

// When this script is invoked without a 'project' arg,
// just show a form that elicits a project ID
// and re-invokes this script with it.

if (!isset($_REQUEST['project'])) {
    $prompt = _("Please enter a project ID:");
    echo "
        <form method='get'>
        $prompt <input type='text' name='project' required>
        <input type='submit'>
        </form>
    ";
    exit();
}

// -------------------------------------

define('SHOW_BLANK_ENTRY_FORM', 'SHOW_BLANK_ENTRY_FORM');
define('HANDLE_ENTRY_FORM_SUBMISSION', 'HANDLE_ENTRY_FORM_SUBMISSION');
define('SEND_OUT_REPORTCARD', 'SEND_OUT_REPORTCARD');

if (isset($_GET['confirm'])) {
    $action = HANDLE_ENTRY_FORM_SUBMISSION;
} elseif (isset($_GET['send'])) {
    $action = SEND_OUT_REPORTCARD;
} else {
    $action = SHOW_BLANK_ENTRY_FORM;
}

$projectid = get_projectID_param($_REQUEST, 'project');

$project = new Project($projectid);

$nameofwork = $project->nameofwork;
$authorsname = $project->authorsname;
$language = $project->language;
$difficulty_level = $project->difficulty;
$pages = $project->n_pages;
$subdate = date('jS \o\f F, Y');

// number of books post-processed by this PPer (including this one).
$psd = get_project_status_descriptor('PPd');
$sql = sprintf("
    SELECT COUNT(*) AS num_post_processed
    FROM projects
    WHERE %s
    AND postproofer = LEFT('%s', 25)
", $psd->state_selector, DPDatabase::escape($project->postproofer));
$result = DPDatabase::query($sql);
$row = mysqli_fetch_assoc($result);
$number_post_processed = $row["num_post_processed"];
mysqli_free_result($result);

// Compute the date of PP upload. We must take into account cases when
// the project is being sent back to the PPer, and also when a PPer
// takes back a project from the PPV pool before a PPV has picked
// up the project.
//
// So, the date we are looking for is the latest transition to PPV.avail
// before the earliest transition from PPV.avail to PPV.checked out...
//
// The following queries do not take into account the case where the PPer,
// after having the project returned to them, returns the project to the
// PP pool, after which it is checked out by another PPer who has to
// submit for PPV.

$pp_date = "";

// earliest transition from PPV.avail to PPV.checked out
$sql = sprintf("SELECT timestamp FROM project_events
    WHERE projectid = '%s'
      AND event_type = 'transition'
      AND details1 = '%s'
      AND details2 = '%s'
    ORDER BY timestamp ASC
    LIMIT 1",
    DPDatabase::escape($projectid),
    DPDatabase::escape(PROJ_POST_SECOND_AVAILABLE),
    DPDatabase::escape(PROJ_POST_SECOND_CHECKED_OUT));
$result = DPDatabase::query($sql);
$row = mysqli_fetch_assoc($result);
mysqli_free_result($result);
if ($row) {
    $earliest_in_ppv = $row["timestamp"];

    // latest transition from PP.checked out to PPV.avail
    $sql = sprintf("SELECT timestamp FROM project_events
        WHERE projectid = '%s'
          AND event_type = 'transition'
          AND details1 = '%s'
          AND details2 = '%s'
          AND timestamp < %d
        ORDER BY timestamp DESC
        LIMIT 1",
        DPDatabase::escape($projectid),
        DPDatabase::escape(PROJ_POST_FIRST_CHECKED_OUT),
        DPDatabase::escape(PROJ_POST_SECOND_AVAILABLE),
        $earliest_in_ppv
    );
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    if ($row) {
        $pp_date = date("d-M-Y", $row["timestamp"]);
    }
}

if ($action == SHOW_BLANK_ENTRY_FORM || $action == HANDLE_ENTRY_FORM_SUBMISSION) {
    $i4 = "                ";
    $i5 = $i4 . "    ";
    $i6 = $i5 . "    ";

    function tr_w_one_cell_centered($class, $content)
    {
        global $i4, $i5;
        return ""
            . "\n$i4<tr>"
            . "\n$i5<th colspan='2' class='$class center-align'>$content</th>"
            . "\n$i4</tr>";
    }

    function tr_w_two_cells($left_content, $right_content)
    {
        global $i4, $i5;
        return ""
            . "\n$i4<tr>"
            . "\n$i5<th class='label' style='width: 25%;'><b>$left_content</b></td>"
            . "\n$i5<td>"
            . $right_content
            . "\n$i5</td>"
            . "\n$i4</tr>";
    }

    // ------------------------

    $n_form_problems = 0;

    function maybe_report_form_problem($message)
    {
        if ($message == "") {
            return "";
        } else {
            global $n_form_problems, $i6;
            $n_form_problems += 1;
            return "\n$i6<p class='form_problem'>$message</p>";
        }
    }

    // Checkboxes to record level of complexity for a feature; Basic, Average, Complex
    function feature_level_combo($feature_type)
    {
        global $action;
        $feature_label_map = get_feature_labels(true);

        $problem = "";
        $basic_id = "bsc_" . $feature_type;
        $average_id = "avg_" . $feature_type;
        $complex_id = "cpx_" . $feature_type;
        $final_label = $feature_label_map[$feature_type];
        if ($action == HANDLE_ENTRY_FORM_SUBMISSION) {
            $numsel = 0;
            if (isset($_POST[$basic_id])) {
                $numsel++;
            }
            if (isset($_POST[$average_id])) {
                $numsel++;
            }
            if (isset($_POST[$complex_id])) {
                $numsel++;
            }
            if ($numsel > 1) {
                $problem = _('You may only select one of "Basic", "Average" or "Complex".');
            }
        }

        global $i6;
        return ""
            . maybe_report_form_problem($problem)
            . "\n$i6"
            . "<p class='inline_input hanging_indent'>"
            . _checkbox($basic_id, _("Basic"))
            . "&nbsp;&nbsp;"
            . _checkbox($average_id, _("Average"))
            . "&nbsp;&nbsp;"
            . _checkbox($complex_id, _("Complex"))
            . " &mdash; "
            . $final_label
            . "</p>";
    }

    // Checkbox to record hard feature, which always counts as Complex
    // Optional argument to provide hyperlink to further information
    function hard_check_box($feature_type, $info_url = "")
    {
        $feature_label_map = get_feature_labels(true);

        $label = $feature_label_map[$feature_type];
        if (!empty($info_url)) {
            $label = "<a href='$info_url'>$label</a>";
        }
        return check_box("hrd_".$feature_type, $label);
    }

    function check_box($id, $label, $checked_in_blank_form = false)
    {
        global $i6;
        return ""
            . "\n$i6"
            . "<p class='inline_input hanging_indent'>"
            . _checkbox($id, $label, $checked_in_blank_form)
            . "</p>";
    }

    function _checkbox($id, $label, $checked_in_blank_form = false)
    {
        global $action;
        if ($action == SHOW_BLANK_ENTRY_FORM) {
            $checked = $checked_in_blank_form;
        } elseif ($action == HANDLE_ENTRY_FORM_SUBMISSION) {
            $checked = isset($_POST[$id]);
        }

        $checked_attr = ($checked ? ' checked' : '');
        return "<input type='checkbox' name='$id' id='$id'$checked_attr><label for='$id'>$label</label>";
    }

    function number_box($id, $label, $options = [])
    {
        global $action;
        $problem = "";
        if ($action == HANDLE_ENTRY_FORM_SUBMISSION) {
            $arg = array_get($_POST, $id, '');

            if ($id == 'kb_size') {
                if ($arg == "") {
                    $problem = _("You must enter a file size.");
                } elseif (strpos($arg, ',') !== false) {
                    $problem = _("The file size should not contain commas.");
                } elseif (!preg_match('/^\d+(\.\d+)?$/', $arg)) {
                    $problem = _("You must enter a valid number.");
                } elseif ($arg == 0) {
                    $problem = _("You must enter a file size that is greater than 0.");
                } elseif ($arg > 3000) {
                    $problem = _("You put in a file size greater than 3000 KB.
                        Please make sure that you have the file size in kilobytes, not bytes.");
                }
            } else {
                if (!empty($arg) && !is_decimal_digits($arg)) {
                    $problem = _("You must enter a number (or leave the field blank).");
                }
            }
        }

        global $i6;
        return ""
            . maybe_report_form_problem($problem)
            . "\n$i6"
            . "<p class='inline_input hanging_indent_long'>"
            . _textbox($id, $label, $options)
            . "</p>";
    }

    function _textbox($id, $label, $options = [])
    {
        global $action;
        if ($action == SHOW_BLANK_ENTRY_FORM) {
            $value = '';
        } elseif ($action == HANDLE_ENTRY_FORM_SUBMISSION) {
            $value = array_get($_POST, $id, '');
        }

        if ($value == '') {
            $value_attr = "";
        } else {
            $value_attr = sprintf(" value='%s'", attr_safe($value));
        }

        $size = array_get($options, 'size', 3);
        $use_a_label_element = array_get($options, 'use_a_label_element', false);
        $put_label_on_left = array_get($options, 'put_label_on_left', false);

        $input_element = "<input type='text' size='$size' name='$id' id='$id'$value_attr>";

        if ($use_a_label_element) {
            $label_thing = "<label for='$id'>$label</label>";
            $connector = " ";
        } else {
            $label_thing = $label;
            $connector = "&nbsp;&nbsp;";
        }

        if ($put_label_on_left) {
            $result = $label_thing . $connector . $input_element;
        } else {
            $result = $input_element . $connector . $label_thing;
        }
        return $result;
    }

    function comment_box($id)
    {
        global $action;
        if ($action == SHOW_BLANK_ENTRY_FORM) {
            $text = '';
        } elseif ($action == HANDLE_ENTRY_FORM_SUBMISSION) {
            $text = array_get($_POST, $id, '');
        }

        $esc_text = html_safe($text);

        return "<textarea rows='4' cols='67' name='$id' id='$id' wrap='hard'>$esc_text</textarea>";
    }

    function is_decimal_digits($s)
    {
        assert(is_string($s));
        return ctype_digit($s) && strlen($s) > 0; // the strlen check is necessary before PHP 5.1.0.
    }

    function get_feature_labels($translate)
    {
        return [
            "poetry" => $translate ? _("Poetry") : "Poetry",
            "block" => $translate ? _("Blockquotes") : "Blockquotes",
            "foot" => $translate ? _("Footnotes") : "Footnotes",
            "side" => $translate ? _("Sidenotes") : "Sidenotes",
            "ads" => $translate ? _("Ads") : "Ads",
            "tables" => $translate ? _("Tables") : "Tables",
            "drama" => $translate ? _("Drama") : "Drama",
            "index" => $translate ? _("Index") : "Index",
            "illos" => $translate ? _("Illustrations") : "Illustrations",
            "multilang" => $translate ? _("Multiple Languages") : "Multiple Languages",
            "spell" => $translate ? _("Extensive Spellcheck/Gutcheck") : "Extensive Spellcheck/Gutcheck",
            "englifh" => $translate ? _("Difficult typography, e.g. long ess, Fraktur, etc.") : "Difficult typography, e.g. long ess, Fraktur, etc.",
            "music" => $translate ? _("Musical Notation and Files") : "Musical Notation and Files",
            "math" => $translate ? _("Extensive mathematical/chemical notation") : "Extensive mathematical/chemical notation",
        ];
    }

    // ---------------------------------

    $ppv_guidelines_url = get_faq_url("ppv.php");
    $pp_faq_url = get_faq_url("post_proof.php");

    $entry_form = "<br>
          <form action='{$code_url}/tools/post_proofers/ppv_report.php?project=$projectid&amp;confirm=1' name='ppvform' method='post'>
          <table class='basic ppv_reportcard' id='report_card'>
"
        . tr_w_one_cell_centered('major_section', _("Project Information"))
        . tr_w_two_cells(
            _("Project ID"),
            "<input type='hidden' name='projectid' value='$projectid'>$projectid"
        )
        . tr_w_two_cells(
            _("Title"),
            $nameofwork
        )
        . tr_w_two_cells(
            _("Author"),
            $authorsname
        )
        . tr_w_two_cells(
            _("Language"),
            $language
        )
        . tr_w_two_cells(
            _("Difficulty"),
            $difficulty_level
        )
        . tr_w_two_cells(
            _("Pages"),
            $pages
        )
        . tr_w_two_cells(
            _("Post-Processed by"),
            $project->postproofer
                . "<br>"
                . sprintf(_("Number of books post-processed by %1\$s (including this one): %2\$d"),
                    $project->postproofer, $number_post_processed)
        )
        . tr_w_two_cells(
            _("Submitted by PP on"),
            $pp_date
        )

        . tr_w_one_cell_centered('major_section', "<a href='$ppv_guidelines_url#difficulty'>" . _("Post-Processing Difficulty") . "</a>")
        . tr_w_two_cells(
            "File Information",
            number_box('kb_size', _("Text File Size in kb (Please do not insert commas. For example, you should input 1450 instead of 1,450 and, if you use commas as decimal marks, 1450.5 instead of 1450,5)"), ['size' => 5])
        )
        . tr_w_two_cells(
            _("Present in the text"),
            ""
                . feature_level_combo('poetry')
                . feature_level_combo('block')
                . feature_level_combo('foot')
                . feature_level_combo('side')
                . feature_level_combo('ads')
                . feature_level_combo('tables')
                . feature_level_combo('drama')
                . feature_level_combo('index')
                . feature_level_combo('illos')
                . hard_check_box('multilang', "$ppv_guidelines_url#mult")
                . hard_check_box('spell')
                . hard_check_box('englifh')
                . hard_check_box('music')
                . hard_check_box('math')
        )
        . tr_w_one_cell_centered("major_section", "<a href='$ppv_guidelines_url#errors'>" . _("ERRORS") . "</a>")
        . tr_w_one_cell_centered("major_section", _("Level 1 (Minor Errors)"))
        . tr_w_one_cell_centered("heading", "<a href='$ppv_guidelines_url#errors_minor_all'>" . _("All Versions") . "</a>")
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e1_spellcheck_num', _("Spellcheck/Scanno errors"))
                . number_box('e1_gutcheck_num', _("Gutcheck-type errors, e.g. punctuation, hyphen/emdash, missing/extra space, line length, illegal characters, etc."))
                . number_box('e1_jeebies_num', _("Jeebies errors (English only)"))
                . number_box('e1_para_num', _("Paragraph breaks missing or incorrectly added"))
                . number_box('e1_hyph_num', _("A few occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)"))
                . number_box('e1_chap_num', _("Chapter and other headings inconsistently spaced, aligned, capitalized or punctuated"))
                . number_box('e1_format_num', _("Formatting inconsistencies (e.g., in margins, blank lines, etc.)"))
                . number_box('e1_xhtml_genother_num', _("Other minor errors (such as a minor rewrap error, misplaced entry in the TN, or minor inconsistency between the text and HTML versions) (Please explain in the Comments Field below)"))
                . "\n"
                . comment_box('level1_general_comments')
        )
        . tr_w_one_cell_centered("heading", _("HTML Version Only"))
        . tr_w_one_cell_centered("heading", "<a href='$ppv_guidelines_url#errors_minor_images'>" . _("Images") . "</a>")
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e1_unused_num', _("Unused files in images folder (Thumbs.db and .DS_Store are not counted toward rating)"))
                . number_box('e1_imagesize_num', sprintf(_("Appropriate image size not used for thumbnail, inline and linked-to images. Image sizes should not normally exceed <a href='%s'>these described limits</a>, but exceptions may be made if warranted by the type of image or book (provided the PPer explains the exception)."), "$pp_faq_url#imagesizes"))
                . number_box('e1_blemish_num', _("Images with major blemishes, uncorrected rotation/distortion or without appropriate cropping"))
                . number_box('e1_distort_num', _("Failure to enter image size appropriately via HTML attribute or CSS such that the image is distorted in HTML, epub or mobi"))
                . number_box('e1_alt_num', _("Failure to use appropriate \"alt\" tags for images that have no caption and to include empty \"alt\" tags if captions exist"))
        )
        . tr_w_one_cell_centered("heading", "<a href='$ppv_guidelines_url#errors_minor_html'>" . _("HTML Code") . "</a>")
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e1_px_num', _("Use of px sizing units for items other than images and borders"))
                . number_box('e1_title_num', _("&lt;title&gt; missing or incorrectly worded (Should be &lt;title&gt;Alice's Adventures in Wonderland | Project Gutenberg&lt;/title&gt;)"))
                . number_box('e1_pre_num', _("Use of &lt;pre&gt; tags instead of their CSS equivalents"))
                . number_box('e1_body_num', _("Failure to place &lt;html&gt;, &lt;body&gt;, &lt;head&gt;, &lt;/head&gt;&lt;/body&gt;, and &lt;/html&gt; tags each on their own line and correctly use them"))
                . number_box('e1_tabl_num', _("Use of tables for things that are not tables"))
                . number_box('e1_css_num', sprintf(_("Used CSS other than CSS 2.1 or below (except for the dropcap \"transparent\" element and other CSS 3 REC status features permitted by <a href='%s'>PGLAF</a>)"), $PGLAF_upload_url))
                . number_box('e1_xhtml_num', sprintf(_("Used HTML version other than that specified in the <a href='%s'>Post-Processing FAQ</a>"), "$pp_faq_url#What_HTML_version_should_you_use.3F"))
                . number_box('e1_chapter_num', _("Failure to add &lt;div class=\"chapter\"&gt; or &lt;div class=\"section\"&gt; at chapter breaks to enable proper page breaks for e-readers"))
                . number_box('e1_xhtml_genhtml_num', _("Minor HTML errors in code that do not generate an HTML validation alert such as misspelling a language code (Please explain in the Comments Field below)"))
                . "\n"
                . comment_box('level1_html_comments')
        )
        . tr_w_one_cell_centered("major_section", _("Level 2 (Major Errors)"))
        . tr_w_one_cell_centered("heading", "<a href='$ppv_guidelines_url#errors_major_all'>" . _("All Versions") . "</a>")
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e2_markup_num', _("Markup not handled (e.g. blockquotes, poetry indentation, or widespread failure to mark italics)"))
                . number_box('e2_poetry_num', _("Poetry indentation does not match original"))
                . number_box('e2_foot_num', _("Footnotes/footnote markers missing or incorrectly placed"))
                . number_box('e2_printers_num', "<a href='$ppv_guidelines_url#printers'>" . _("Printers' errors not addressed") . "</a>")
                . number_box('e2_missing_num', _("Missing page(s) or substantial sections of missing text"))
                . number_box('e2_rewrap_num', _("Substantial rewrapping errors, e.g., poetry has been rewrapped or text version generally not rewrapped to required length (not exceeding 75 characters or falling below 55 characters) except where unavoidable, e.g., some tables though the aim should be 72 characters"))
                . number_box('e2_hyphen_num', _("Widespread/general occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)"))
                . number_box('e2_gen_num', _("Other major errors that could seriously impact the readability of the book or that represent major inconsistencies between the text and the HTML versions (Please explain in the Comments Field below)"))
                . "\n"
                . comment_box('level2_general_comments')
        )
        . tr_w_one_cell_centered("heading", "<a href='$ppv_guidelines_url#errors_major_html'>" . _("HTML Version Only") . "</a>")
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e2_tidy_num', _("The W3C Markup Validation Service generates errors or warning messages (Please enter number of errors)"))
                . number_box('e2_csscheck_num', sprintf(_("The W3C CSS Validation Service generates errors or warning messages other than for the dropcap \"transparent\" element and other CSS 3 REC status features permitted by <a href='%s'>PGLAF</a> (Please enter number of errors)"), $PGLAF_upload_url))
                . number_box('e2_links_num', _("Non-working links within HTML or to images. (Either broken or link to wrong place/file)"))
                . number_box('e2_file_num', _("File and folder names not in lowercase or contain spaces, images not in \"images\" folder, etc."))
                . number_box('e2_cover_num', sprintf(_("Cover image has not been included and/or has not been coded for e-reader use. (The cover should meet <a href='%s'>current DP guidelines</a>.)"), "$pp_faq_url#covers"))
                . number_box('e2_epub_num', sprintf(_("Project not presentable/useable when put through <a href='%s'>ebookmaker</a>"), "$ppv_guidelines_url#reader"))
                . number_box('e2_heading_num', _("Heading elements used for things that are not headings and failure to use hierarchical headings for book, chapter and section headings (single h1, appropriate h2s and h3s etc.)"))
        )
        . tr_w_one_cell_centered("major_section", "<a href='$ppv_guidelines_url#strongly_recommended'>" . _("Strongly Recommended") . "</a>")
        . tr_w_one_cell_centered("heading", _("(Failure to follow these guidelines will not be tabulated as errors, but the PPer should be counselled to correct any problems)"))
        . tr_w_two_cells(
            _("Occurrence"),
            ""
                . check_box('s_multi', _("Enclose entire multi-part headings within the related heading tag"))
                . check_box('s_empty', _("Avoid using empty tags (with &amp;nbsp; entities) or &lt;br/&gt; elements for vertical spacing. e.g. &lt;p&gt;&lt;br/&gt;&lt;br/&gt;&lt;/p&gt; (or with nbsps) -- &lt;td&gt;&amp;nbsp;&lt;/td&gt; is still acceptable though"))
                . check_box('s_list', _("List Tags should be used for lists (e.g., a normal index)"))
                . check_box('s_text', _("Include all text as text, not just as images"))
                . check_box('s_code', _("Keep your code line lengths reasonable"))
                . check_box('s_tables', _("Tables should display left, right, and center justification and top and bottom align appropriately"))
                . check_box('s_th', _("Tables contain &lt;th&gt; elements for headings"))
                . check_box('s_thumbs', _("Remove unwanted system/hidden OS files, e.g. Thumbs.db or .DS_Store, from the images folder"))
                . check_box('s_ereader', _("E-reader version, although without major flaws, should also look as good as possible"))
        )
        . tr_w_one_cell_centered("major_section", "<a href='$ppv_guidelines_url#mildly_recommended'>" . _("Mildly Recommended") . "</a>")
        . tr_w_one_cell_centered("heading", _("(Failure to follow these guidelines will not be tabulated as errors, and any corrections are solely at the discretion of the PPVer and PPer)"))
        . tr_w_two_cells(
            _("Occurrence"),
            ""
                . check_box('m_semantic', _("Distinguish between purely decorative italics/bold/gesperrt and semantic uses of them"))
                . check_box('m_unusedcss', _("Ensure that there are no unused elements in the CSS (other than the base HTML headings)"))
        )
        . tr_w_one_cell_centered("major_section", _("General Comments"))
        . tr_w_two_cells(
            sprintf(_("<a href='%s'>Did you have to return</a> the project again because the PPer failed to make requested corrections on the second submission? (If so, please explain)"), "$ppv_guidelines_url#rr"),
            comment_box('reason_returned')
        )
        . tr_w_two_cells(
            _("General comments on this project or your experience working with this PPer."),
            comment_box('general_comments')
        )
        . tr_w_one_cell_centered('major_section', _("Copies"))
        . tr_w_two_cells(
            sprintf(_("<a href='%s'>Send to</a>"), "$ppv_guidelines_url#summary"),
            ""
                . check_box('cc_ppv', _("Me"))
                . check_box('cc_pp', $project->postproofer, true) ."
                        <p class='inline_input hanging_indent'><input type='checkbox' name='foo' checked disabled>"._("PPV Summary (mailing list)") ."</p>"
        )
        . tr_w_one_cell_centered("", "<input type='submit' value='".attr_safe(_("Preview"))."'>") ."
        </table>
    </form>";
}

if ($action == SHOW_BLANK_ENTRY_FORM) {
    echo $entry_form;
} elseif ($action == HANDLE_ENTRY_FORM_SUBMISSION) {
    if ($n_form_problems > 0) {
        if ($n_form_problems == 1) {
            $message =
                _("There was a problem with a form input, as detailed below. Please fix it and re-submit.");
        } else {
            $message = sprintf(
                _("There were problems with %d form inputs, as detailed below. Please fix them and re-submit."),
                $n_form_problems);
        }
        echo "\n<p class='form_problem'>$message</p>\n";

        // Show the form as the user filled it in,
        // with embedded problem-reports.
        echo $entry_form;
        exit();
    }

    // ---------------------------------

    $project_size = $_POST["kb_size"];

    $project_basic_counter = 0;
    $project_average_counter = 0;
    $project_complex_counter = 0;
    $level_1_errors = 0;
    $level_2_errors = 0;
    $pp_evaluation = "";
    $feature_label_map = get_feature_labels(false);
    $pping_complexity = "\n"
        . "\nPPing Complexity:"
        . "\n"
        . "\n    Text File Size: $project_size KB";

    foreach ($_POST as $key => $value) {
        $feature_type = substr($key, 4);
        if (startswith($key, "bsc_") && isset($feature_label_map[$feature_type])) {
            $project_basic_counter++;
            $pping_complexity .= "\n    " . "Basic " . $feature_label_map[$feature_type];
        } elseif (startswith($key, "avg_") && isset($feature_label_map[$feature_type])) {
            $project_average_counter++;
            $pping_complexity .= "\n    " . "Average " . $feature_label_map[$feature_type];
        } elseif (startswith($key, "cpx_") && isset($feature_label_map[$feature_type])) {
            $project_complex_counter++;
            $pping_complexity .= "\n    " . "Complex " . $feature_label_map[$feature_type];
        } elseif (startswith($key, "hrd_") && isset($feature_label_map[$feature_type])) {
            $project_complex_counter++;
            $pping_complexity .= "\n    " . $feature_label_map[$feature_type];
        } elseif (startswith($key, "e1_") && !empty($value)) {
            $level_1_errors += $value;
        } elseif (startswith($key, "e2_") && !empty($value)) {
            $level_2_errors += $value;
        }
    }

    if ($project_complex_counter >= 3) {
        $pp_difficulty_level = "Difficult";
    } elseif ($project_complex_counter > 0 || $project_average_counter >= 3) {
        $pp_difficulty_level = "Average";
    } else {
        $pp_difficulty_level = "Easy";
    }

    function number_of_errors_allowed($size_per)
    {
        $project_size = $_POST["kb_size"];
        if ($project_size <= $size_per) {
            return 1;
        }

        return round($project_size / $size_per);
    }

    if ($level_2_errors == 0) {
        if ($pp_difficulty_level == "Easy") {
            if (number_of_errors_allowed(300) >= $level_1_errors) {
                $pp_evaluation = "Excellent";
            } elseif (number_of_errors_allowed(120) >= $level_1_errors) {
                $pp_evaluation = "Very Good";
            }
        } elseif ($pp_difficulty_level == "Average") {
            if (number_of_errors_allowed(200) >= $level_1_errors) {
                $pp_evaluation = "Excellent";
            } elseif (number_of_errors_allowed(80) >= $level_1_errors) {
                $pp_evaluation = "Very Good";
            }
        } elseif ($pp_difficulty_level == "Difficult") {
            if (number_of_errors_allowed(100) >= $level_1_errors) {
                $pp_evaluation = "Excellent";
            } elseif (number_of_errors_allowed(40) >= $level_1_errors) {
                $pp_evaluation = "Very Good";
            }
        }
    }
    if ($level_2_errors <= 5 && empty($pp_evaluation)) {
        if ($pp_difficulty_level == "Easy") {
            if ((number_of_errors_allowed(120) * 6) >= $level_1_errors) {
                $pp_evaluation = "Good";
            }
        } elseif ($pp_difficulty_level == "Average") {
            if ((number_of_errors_allowed(80) * 6) >= $level_1_errors) {
                $pp_evaluation = "Good";
            }
        } elseif ($pp_difficulty_level == "Difficult") {
            if ((number_of_errors_allowed(40) * 6) >= $level_1_errors) {
                $pp_evaluation = "Good";
            }
        }
    }
    if (empty($pp_evaluation)) {
        $pp_evaluation = "Fair";
    }

    $reportcard = ""
        . "\n"
        . "\n"
        . "\nPPV Summary for $project->postproofer"
        . "\n"
        . "\n    Number of books post-processed by $project->postproofer (including this one): $number_post_processed"
        . "\n"
        . "\n"
        . "\nProject Information"
        . "\n"
        . "\n    projectID: $projectid"
        . "\n    Title: $nameofwork"
        . "\n    Author: $authorsname"
        . "\n    Language: $language"
        . "\n    Proofreading Difficulty: $difficulty_level"
        . "\n    Number of pages: $pages"
        . "\n    Post-processed by: $project->postproofer"
        . "\n    Verified by: $pguser"
        . "\n    Verified on: $subdate"
        . "\n    Submitted by PP on: $pp_date"
        . "\n"
        . "\nGeneral Post-Processing Information"
        . "\n"
        . "\n    PPing Difficulty: $pp_difficulty_level"
        . "\n    Overall evaluation of PPer's work: $pp_evaluation"

        . $pping_complexity

        . "\n"
        . "\nLevel 1 Errors:"
        . "\n"
        . "\n    All Versions:"
        . report_error_counts([
            'e1_spellcheck_num' => "Spellcheck/Scanno errors",
            'e1_gutcheck_num' => "Gutcheck-type errors, e.g. punctuation, hyphen/emdash, missing/extra space, line length, illegal characters, etc.",
            'e1_jeebies_num' => "Jeebies errors (English only)",
            'e1_para_num' => "Paragraph breaks missing or incorrectly added",
            'e1_hyph_num' => "A few occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)",
            'e1_chap_num' => "Chapter and other headings inconsistently spaced, aligned, capitalized or punctuated",
            'e1_format_num' => "Formatting inconsistencies (e.g., in margins, blank lines, etc.)",
            'e1_xhtml_genother_num' => "Other minor errors (such as a minor rewrap error, misplaced entry in the TN, or minor inconsistency between the text and HTML versions) (Please explain in the Comments Field below)",
        ])
        . report_comments(
            "      ",
            'level1_general_comments',
            ""
        )
        . "\n"
        . "\n    HTML Version Only:"
        . report_error_counts([
            'e1_unused_num' => "Unused files in images folder (Thumbs.db and .DS_Store are not counted toward rating)",
            'e1_imagesize_num' => "Appropriate image size not used for thumbnail, inline and linked-to images. Image sizes should not normally exceed the limits described here, but exceptions may be made if warranted by the type of image or book (provided the PPer explains the exception).",
            'e1_blemish_num' => "Images with major blemishes, uncorrected rotation/distortion or without appropriate cropping",
            'e1_distort_num' => "Failure to enter image size appropriately via HTML attribute or CSS such that the image is distorted in HTML, epub or mobi",
            'e1_alt_num' => "Failure to use appropriate \"alt\" tags for images that have no caption and to include empty \"alt\" tags if captions exist",
            'e1_px_num' => "Use of px sizing units for items other than images and borders",
            'e1_title_num' => "&lt;title&gt; missing or incorrectly worded (Should be &lt;title&gt;Alice's Adventures in Wonderland | Project Gutenberg&lt;/title&gt;)",
            'e1_pre_num' => "Use of &lt;pre&gt; tags instead of their CSS equivalents",
            'e1_body_num' => "Failure to place &lt;html&gt;, &lt;body&gt;, &lt;head&gt;, &lt;/head&gt;&lt;/body&gt;, and &lt;/html&gt; tags each on their own line and correctly use them",
            'e1_tabl_num' => "Use of tables for things that are not tables",
            'e1_css_num' => "Used CSS other than CSS 2.1 or below (except for the dropcap \"transparent\" element and other CSS 3 REC features permitted by PGLAF)",
            'e1_xhtml_num' => "Used HTML version other than that specified in the Post-Processing FAQ",
            'e1_chapter_num' => "Failure to add &lt;div class=\"chapter\"&gt; at chapter breaks to enable proper page breaks for ereaders",
            'e1_xhtml_genhtml_num' => "Minor HTML errors in code that do not generate an HTML validation alert such as misspelling a language code (Please explain in the Comments Field below)",
        ])
        . report_comments(
            "      ",
            'level1_html_comments',
            ""
        )

        . "\n"
        . "\nLevel 2 Errors:"
        . "\n"
        . "\n    All Versions:"
        . report_error_counts([
            'e2_markup_num' => "Markup not handled (e.g., blockquotes, poetry indentation, or widespread failure to mark italics)",
            'e2_poetry_num' => "Poetry indentation does not match original",
            'e2_foot_num' => "Footnotes/footnote markers missing or incorrectly placed",
            'e2_printers_num' => "Printers' errors not addressed",
            'e2_missing_num' => "Missing page(s) or substantial sections of missing text",
            'e2_rewrap_num' => "Substantial rewrapping errors, e.g., poetry has been rewrapped or text version generally not rewrapped to required length (not exceeding 75 characters or falling below 55 characters) except where unavoidable, e.g., some tables though the aim should be 72 characters",
            'e2_hyphen_num' => "Widespread/general occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)",
            'e2_gen_num' => "Other major errors that could seriously impact the readability of the book or that represent major inconsistencies between the text and the HTML versions (Please explain in the Comments Field below)",
        ])
        . report_comments(
            "      ",
            'level2_general_comments',
            ""
        )
        . "\n"
        . "\n    HTML Version Only:"
        . report_error_counts([
            'e2_tidy_num' => "The W3C Markup Validation Service generates errors or warning messages",
            'e2_csscheck_num' => "The W3C CSS Validation Service generates errors or warning messages other than for the dropcap \"transparent\" element and other CSS 3 REC status features permitted by PGLAF",
            'e2_links_num' => "Non-working links within HTML or to images. (Either broken or link to wrong place/file)",
            'e2_file_num' => "File and folder names not in lowercase or contain spaces, images not in \"images\" folder, etc.",
            'e2_cover_num' => "Cover image has not been included and/or has not been coded for e-reader use. (The cover should meet current DP guidelines.)",
            'e2_epub_num' => "Project not presentable/useable when put through ebookmaker",
            'e2_heading_num' => "Heading elements used for things that are not headings and failure to use hierarchical headings for book, chapter and section headings (single h1, appropriate h2s and h3s etc.)",
        ])

        . "\n"
        . "\nStrongly Recommended (These don't count as errors but should be corrected):\n"
        . report_recommendations([
            's_multi' => "Enclose entire multi-part headings within the related heading tag",
            's_empty' => "Avoid using empty tags (with &amp;nbsp; entities) or &lt;br/&gt; elements for vertical spacing. e.g. &lt;p&gt;&lt;br/&gt;&lt;br/&gt;&lt;/p&gt; (or with nbsps) -- &lt;td&gt;&amp;nbsp;&lt;/td&gt; is still acceptable though",
            's_list' => "List Tags should be used for lists (e.g., a normal index)",
            's_text' => "Include all text as text, not just as images",
            's_code' => "Keep your code line lengths reasonable",
            's_tables' => "Tables should display left, right, and center justification and top and bottom align appropriately",
            's_th' => "Tables contain &lt;th&gt; elements for headings",
            's_thumbs' => "Remove unwanted system/hidden OS files, e.g. Thumbs.db or .DS_Store, from the images folder",
            's_ereader' => "E-reader version, although without major flaws, should also look as good as possible",
        ])
        . "\n"
        . "\nMildly Recommended (These don't count as errors):\n"
        . report_recommendations([
            'm_semantic' => "Distinguish between purely decorative italics/bold/gesperrt and semantic uses of them",
            'm_unusedcss' => "Ensure that there are no unused elements in the CSS (other than the base HTML headings)",
        ])

        . report_comments(
            '',
            'reason_returned',
            "Did you have to return the project again because the PPer failed to make requested corrections on the second submission? (If so, please explain)"
        )
        . report_comments(
            '',
            'general_comments',
            "General comments"
        )

        . "\n";

    echo "<p>" . _("Please check the information below to make sure everything is correct.
        To return to the form, simply use your browser's back button.") . "</p>\n";
    echo "<p>" . sprintf(_("The PPV Guidelines describe how the <a href='%s'>PPing Difficulty</a> and <a href='%s'>Overall evaluation of PPers' work</a> are calculated."), "$ppv_guidelines_url#difficulty", "$ppv_guidelines_url#allowable") . "</p>";
    echo "<pre>" . $reportcard . "</pre>";
    echo "<form action='{$code_url}/tools/post_proofers/ppv_report.php?project=$projectid&amp;send=1' name='ppvform' method='post'>
                <input type='hidden' name='reportcard' value='" . attr_safe($reportcard) . "'>
                <input type='hidden' name='pp_evaluation' value='" . attr_safe($pp_evaluation) . "'>";
    if (isset($_POST['cc_pp'])) {
        echo "<input type='hidden' name='cc_pp'>";
    }
    if (isset($_POST['cc_ppv'])) {
        echo "<input type='hidden' name='cc_ppv'>";
    }
    echo "<input type='submit' value='".attr_safe(_("Send"))."'></form>";
} elseif ($action == SEND_OUT_REPORTCARD) {
    $pper = new User($project->postproofer);
    $ppver = User::load_current();

    $reportcard = ppv_report_wrap($_POST["reportcard"]);

    // The Spanish PPer shouldn't get a French email because that's the PPVer's
    // language, so temporarily change the current locale.
    configure_gettext_for_user($pper);
    $ppbita = _("Hello %1\$s,

    This is a message that your Post-Processing Verifier, %2\$s, requested you receive from the %4\$s site.

    Thank you for your Post-Processing work on \"%3\$s\".
    
    A copy of the PPV Summary is below. If you have any questions about it, please contact your PPVer.");

    $ppbit = sprintf($ppbita, $project->postproofer, $pguser, $nameofwork, $site_name);
    $ppbit = ppv_report_wrap($ppbit);

    configure_gettext_for_user($ppver);
    $ppvbita = _("Hello %1\$s,

    This is a message that you requested you receive from the %4\$s site.

    Thank you for your Post-Processing Verification work on \"%2\$s\".
    
    A copy of the summary you submitted is below. If you see an important error, please email %3\$s.");

    $ppvbit = sprintf($ppvbita, $pguser, $nameofwork, $general_help_email_addr, $site_name);
    $ppvbit = ppv_report_wrap($ppvbit);

    if (isset($_POST['cc_pp'])) {
        $to = $pper->email;
        $subject = "$site_abbreviation PP: $nameofwork";
        $message = $ppbit . $reportcard;
        send_mail($to, $subject, $message);
    }

    if (isset($_POST['cc_ppv'])) {
        $to = $ppver->email;
        $subject = "$site_abbreviation PPV: $nameofwork";
        $message = $ppvbit . $reportcard;
        send_mail($to, $subject, $message);
    }

    $to = $ppv_reporting_email_addr;
    $subject = "PPV Summary - $project->postproofer ($_POST[pp_evaluation])";
    $message = $reportcard;
    send_mail($to, $subject, $message, "$pguser <$ppver->email>");
    printf(_("Return to <a href='%s'>the Project Page</a>"), "../../project.php?id=$projectid");
    exit();
} else {
    assert(false);
}

// Wrap a string to about 72 characters wide so it's
// suitable for displaying in <pre>-formatted HTML email.
function ppv_report_wrap($string)
{
    $string = wordwrap($string, 72);
    $string = str_replace("\n    ", "\n", $string); // Remove 4 blank spaces if they are there
    $string = str_replace("\n", "\n    ", $string); // Add 4 blank spaces to mean <pre> when rendered
    return $string;
}

function report_error_counts($errors)
{
    $result = "";
    foreach ($errors as $id => $label) {
        if ($_POST[$id]) {
            $result .= "\n      {$_POST[$id]} $label";
        }
    }
    if ($result == "") {
        $result = "\n      None";
    }
    return $result;
}

function report_recommendations($recommendations)
{
    $result = "";
    foreach ($recommendations as $id => $label) {
        if (isset($_POST[$id])) {
            $result .= "\n    $label";
        }
    }
    if ($result == "") {
        $result = "\n    None";
    }
    return $result;
}

function report_comments($base_indent, $id, $label)
{
    $comments = array_get($_POST, $id, '');
    if (empty($comments)) {
        return "";
    }

    return "\n"
        . (empty($label) ? "" : "\n$base_indent$label:\n")
        . "\n$comments"
    ;
}
