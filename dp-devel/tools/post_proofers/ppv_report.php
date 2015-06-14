<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'Project.inc'); // validate_projectID()
include_once($relPath.'Stage.inc'); //user_can_work_in_stage()
include_once($relPath.'project_states.inc'); // get_project_status_descriptor()
include_once($relPath.'misc.inc');  // javascript_safe() array_get

header_remove("Expires");
header_remove("Cache-Control");

require_login();

$projectid = validate_projectID('project', @$_REQUEST['project']);

$theme_args['js_data'] = "
function grow_textarea(textarea_id)
{
    textarea = document.getElementById(textarea_id);
    textarea.rows = textarea.rows+2;
}

function shrink_textarea(textarea_id)
{
    textarea = document.getElementById(textarea_id);
    if (textarea.rows > 2)
    {
        textarea.rows = textarea.rows-2;
    }
}";

$theme_args['css_data'] = "
.single {
    margin:0;
    padding:0;
    text-indent:-3em;
    margin-left:3em;
}    
.single2 {
    margin:0;
    padding:0;
    text-indent:-1.25em;
    margin-left:1.25em;
}    
div.shrinker {float: right;}
div.shrinker a {
    font-size:200%; 
    font-weight: 900;
    text-decoration: none!important;
    color: #888;
    cursor: pointer;
}
input[type='text'], textarea {
     background-color: #E2F2E1;
}";

output_header(_('Post-Processing Verification Reporting'), SHOW_STATSBAR, $theme_args);


// To make PPVer collaboration easier, allow any PPVer to fill in the summary.
// (The link is still only shown to the PPVer with the project checked-out.)
// All summaries are sent to the PPVers' list, signed by the person filling
// out the summary, so a mischievous PPVer couldn't get away with anything, anyway.
if (!user_can_work_in_stage($pguser, 'PPV')) {
    echo _("You're not recorded as a Post-Processing Verifier.
            If you feel this is an error, please contact a Site Administrator.");
    exit();
}

$project = mysql_fetch_object(mysql_query("SELECT nameofwork, authorsname, language, difficulty, n_pages, postproofer
                                           FROM projects WHERE projectid = '$projectid'"));

$nameofwork = $project->nameofwork;
$authorsname = $project->authorsname;
$language = $project->language;
$difficulty_level = $project->difficulty;
$pages = $project->n_pages;
$subdate = date('jS \o\f F, Y');

// number of books post-processed by this PPer (including this one).
$psd = get_project_status_descriptor('PPd');
$result = mysql_query("
    SELECT COUNT(*) AS num_post_processed
    FROM projects
    WHERE $psd->state_selector
      AND postproofer = '$project->postproofer'
");
$number_post_processed = mysql_result($result, 0, "num_post_processed");
mysql_free_result($result);

// Compute the date of PP upload. We must take into account cases when 
// the project is being sent back to the PPer, and also when a PPer 
// takes back a project from the PPV pool before a PPV has picked 
// up the project.
//
// So, the date we are looking for is the latest transition to PPV.avail
// before the earliest transition from PPV.avail to PPV.checked out...

$pp_date = "";

// earliest transition from PPV.avail to PPV.checked out
$result = mysql_query("SELECT timestamp FROM project_events
    WHERE projectid = '$projectid'
      AND event_type = 'transition'
      AND details1 = '" . PROJ_POST_SECOND_AVAILABLE . "'
      AND details2 = '" . PROJ_POST_SECOND_CHECKED_OUT . "'
    ORDER BY timestamp ASC
    LIMIT 1");
if (mysql_num_rows($result) != 0) 
{
    $earliest_in_ppv = mysql_result($result, 0);
    mysql_free_result($result);

    // latest transition from PP.checked out to PPV.avail
    $result = mysql_query("SELECT timestamp FROM project_events
        WHERE projectid = '$projectid'
          AND event_type = 'transition'
          AND details1 = '" . PROJ_POST_FIRST_CHECKED_OUT . "'
          AND details2 = '" . PROJ_POST_SECOND_AVAILABLE . "'
          AND timestamp < $earliest_in_ppv
        ORDER BY timestamp DESC
        LIMIT 1");
    if (mysql_num_rows($result) != 0) 
    {
        $pp_date = date("d-M-Y", mysql_result($result, 0));
    }
}
mysql_free_result($result);

if (isset($_GET['confirm'])) {
    function number_of_errors_allowed($size_per) {
        $project_size = $_POST["kb_size"];
        if ($project_size <= $size_per)
            return 1;

        return floor($project_size / $size_per);
    }

    $project_size = $_POST["kb_size"];
    if ((isset($_POST["some_poetry"]) && isset($_POST["sig_poetry"])) || (isset($_POST["some_block"]) && isset($_POST["sig_block"]))
            || (isset($_POST["some_foot"]) && isset($_POST["sig_foot"])) || (isset($_POST["some_side"]) && isset($_POST["sig_side"]))
            || (isset($_POST["some_ads"]) && isset($_POST["sig_ads"])) || (isset($_POST["some_tables"]) && isset($_POST["sig_tables"]))
            || (isset($_POST["some_index"]) && isset($_POST["sig_index"])) || (isset($_POST["some_drama"]) && isset($_POST["sig_drama"]))) {
        echo _("You selected both \"Some\" and \"Significant Amount\" for an item.
            Please go back, fix this, and resubmit the form.");
        exit();
    } else if (strpos($project_size, ',') !== false) {
        echo _("The file size should not contain commas.");
        exit();
    } else if ($project_size == "" || $project_size == 0) {
        echo _("Please enter a file size that is greater than 0.");
        exit();
    } else if ($project_size > 3000) {
        echo _("You put in a file size greater than 3000 KB.
            Please make sure that you have the file size in kilobytes, not bytes.");
        exit();
    } else if (isset($_POST["some_illos"]) && !isset($_POST["num_illos"])) {
        echo _("You selected there were illustrations but didn't specify how many.
            Please go back and specify how many illustrations there were");
        exit();
    } else if (isset($_POST["some_illos"]) && (!is_numeric($_POST["num_illos"]) || $_POST["num_illos"] == 0)) {
        echo _("Please input a non-0 number for how many illustrations were in the book.");
        exit();
    } else if (!empty($_POST["num_illos"]) && !isset($_POST["some_illos"])) {
        echo sprintf(_("You put that there were %s illustrations but didn't check the box for illustrations.
            Please go back and select the checkbox for 'Illustrations (other than minor decorations or logos)'."), $_POST["num_illos"]);
        exit();
    }

    // Wrap any long input from textareas.
    $_POST['reason_returned'] = wordwrap($_POST['reason_returned'], 78, "\n    ");
    $_POST['general_comments'] = wordwrap($_POST['general_comments'], 78, "\n    ");

    $project_significant_counter = 0;
    $project_average_counter = 0;
    $level_1_errors = 0;
    $level_2_errors = 0;
    $pp_evaluation = "";
    $pping_complexity = "\n\n  PPing Complexity:\n
        Text File Size: $project_size KB";
    $mapped_array = array("sig_poetry" => "Significant Amount of Poetry", "some_poetry" => "Some Poetry",
        "sig_block" => "Significant Amount of Blockquotes", "some_block" => "Some Blockquotes",
        "sig_foot" => "Significant Amount of Footnotes", "some_foot" => "Some Footnotes",
        "sig_side" => "Significant Amount of Sidenotes", "some_side" => "Some Sidenotes",
        "sig_ads" => "Significant Amount of Ads", "some_ads" => "Some Ads",
        "sig_tables" => "Significant Amount of Tables", "some_tables" => "Some Tables",
        "sig_drama" => "Significant Amount of Drama", "some_drama" => "Some Drama",
        "sig_index" => "Significant Size of Index", "some_index" => "Small Index",
        "sig_illos" => "Illustrations requiring advanced preparation and/or difficult placement",
        "sig_multilang" => "Multiple Languages", "sig_englifh" => "Englifh",
        "sig_music" => "Musical Notation and Files", "sig_math" => "Extensive mathematical/chemical notation");

    foreach($_POST as $key => $value) {
        if (substr($key, 0, 4) === "sig_" && isset($mapped_array[$key])) {
            $project_significant_counter++;
            $pping_complexity .= "\n    " . $mapped_array[$key];
        } else if (substr($key, 0, 5) === "some_" && isset($mapped_array[$key])) {
            $project_average_counter++;
            $pping_complexity .= "\n    " . $mapped_array[$key];
        } else if ($key === "some_illos") {
            if ($_POST["num_illos"] >= 40) {
                $project_significant_counter++;
            } else if ($_POST["num_illos"] > 5) {
                $project_average_counter++;
            }
            $pping_complexity .= "\n    " . $_POST["num_illos"] . " Illustrations (other than minor decorations or logos)";
        } else if (substr($key, 0, 3) === "e1_" && !empty($value)) {
            if (!is_numeric($value)) {
                echo _("Please input a number for all Level 1 error fields.
                    Not all fields must be completed, but all data input in the error fields must be numeric.");
                exit();
            }
            $level_1_errors += $value;
        } else if (substr($key, 0, 3) === "e2_" && !empty($value)) {
            if (!is_numeric($value)) {
                echo _("Please input a number for all Level 2 error fields.
                    Not all fields must be completed, but all data input in the error fields must be numeric.");
                exit();
            }
            $level_2_errors += $value;
        }
    }

    if ($project_significant_counter >= 4) {
        $pp_difficulty_level = "Difficult";
    } else if ($project_significant_counter > 0 || $project_average_counter >= 3) {
        $pp_difficulty_level = "Average";
    } else {
        $pp_difficulty_level = "Easy";
    }

    if ($level_2_errors == 0) {
        if ($pp_difficulty_level == "Easy") {
            if (number_of_errors_allowed(300) >= $level_1_errors) {
                $pp_evaluation = "Excellent";
            } else if (number_of_errors_allowed(150) >= $level_1_errors) {
                $pp_evaluation = "Very Good";
            }
        } else if ($pp_difficulty_level == "Average") {
            if (number_of_errors_allowed(200) >= $level_1_errors) {
                $pp_evaluation = "Excellent";
            } else if (number_of_errors_allowed(100) >= $level_1_errors) {
                $pp_evaluation = "Very Good";
            }
        } else if ($pp_difficulty_level == "Difficult") {
            if (number_of_errors_allowed(100) >= $level_1_errors) {
                $pp_evaluation = "Excellent";
            } else if (number_of_errors_allowed(50) >= $level_1_errors) {
                $pp_evaluation = "Very Good";
            }
        }
    } if ($level_2_errors <= 5 && empty($pp_evaluation)) {
        if ($pp_difficulty_level == "Easy") {
            if ((number_of_errors_allowed(150) * 6) >= $level_1_errors) {
                $pp_evaluation = "Good";
            }
        } else if ($pp_difficulty_level == "Average") {
            if ((number_of_errors_allowed(100) * 6) >= $level_1_errors) {
                $pp_evaluation = "Good";
            }
        } else if ($pp_difficulty_level == "Difficult") {
            if ((number_of_errors_allowed(50) * 6) >= $level_1_errors) {
                $pp_evaluation = "Good";
            }
        }
    } if (empty($pp_evaluation)) {
        $pp_evaluation = "Fair";
    }

    $reportcard = "\n\n
    PPV Summary for $project->postproofer

        Number of books post-processed by $project->postproofer (including this one): $number_post_processed


    Project Information

        projectID: $projectid
        Title: $nameofwork
        Author: $authorsname
        Language: $language
        Proofreading Difficulty: $difficulty_level
        Number of pages: $pages
        Post-processed by: $project->postproofer
        Verified by: $pguser
        Verified on: $subdate
        Submitted by PP on: $pp_date

    General Post-Processing Information

        PPing Difficulty: $pp_difficulty_level
        Overall evaluation of PPer's work: $pp_evaluation";

    $reportcard .= $pping_complexity;

    $reportcard .= "\n\n  Level 1 Errors:";
    $reportcard .= "\n\n    All Versions:";
    if (!$_POST['e1_spellcheck_num'] && !$_POST['e1_gutcheck_num'] && !$_POST['e1_jeebies_num'] && !$_POST['e1_para_num']
            && !$_POST['e1_hyph_num'] && !$_POST['e1_chap_num'] && !$_POST['e1_format_num'] && !$_POST['e1_xhtml_genother_num']) {
        $reportcard .= "\n      None";
    } else {
        if ($_POST['e1_spellcheck_num'])     $reportcard .= "\n      $_POST[e1_spellcheck_num] Spellcheck/Scanno errors";
        if ($_POST['e1_gutcheck_num'])       $reportcard .= "\n      $_POST[e1_gutcheck_num] Gutcheck-type errors, e.g. punctuation, hyphen/emdash, missing/extra space, line length, illegal characters, etc.";    
        if ($_POST['e1_jeebies_num'])        $reportcard .= "\n      $_POST[e1_jeebies_num] Jeebies errors (English only)";  
        if ($_POST['e1_para_num'])           $reportcard .= "\n      $_POST[e1_para_num] Paragraph breaks missing or incorrectly added";
        if ($_POST['e1_hyph_num'])           $reportcard .= "\n      $_POST[e1_hyph_num] A few occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)";
        if ($_POST['e1_chap_num'])           $reportcard .= "\n      $_POST[e1_chap_num] Chapter and other headings inconsistently spaced, aligned, capitalized or punctuated";
        if ($_POST['e1_format_num'])         $reportcard .= "\n      $_POST[e1_format_num] Formatting inconsistencies (e.g., in margins, blank lines, etc.)";
        if ($_POST['e1_xhtml_genother_num']) $reportcard .= "\n      $_POST[e1_xhtml_genother_num] Other minor errors (such as a minor rewrap error, misplaced entry in the TN, or minor inconsistency between the text and HTML versions) (Please explain in the Comments Field)";
    }
    $reportcard .= "\n\n    HTML Version Only:";
    if (!$_POST['e1_unused_num'] && !$_POST['e1_imagesize_num'] && !$_POST['e1_blemish_num'] && !$_POST['e1_distort_num']
            && !$_POST['e1_alt_num'] && !$_POST['e1_px_num'] && !$_POST['e1_title_num'] && !$_POST['e1_pre_num'] && !$_POST['e1_body_num']
            && !$_POST['e1_css_num'] && !$_POST['e1_xhtml_num'] && !$_POST['e1_chapter_num'] && !$_POST['e1_xhtml_genhtml_num']
            && !$_POST['e1_tabl_num']) {
        $reportcard .= "\n      None";
    }
    if ($_POST['e1_unused_num'])         $reportcard .= "\n      $_POST[e1_unused_num] Unused files in images folder (Thumbs.db is not counted toward rating)";
    if ($_POST['e1_imagesize_num'])      $reportcard .= "\n      $_POST[e1_imagesize_num] Appropriate image size not used for thumbnail, inline and linked-to images. Image sizes should not normally exceed the limits described here, but exceptions may be made if warranted by the type of image or book (provided the PPer explains the exception).";
    if ($_POST['e1_blemish_num'])        $reportcard .= "\n      $_POST[e1_blemish_num] Images with major blemishes, uncorrected rotation/distortion or without appropriate cropping";
    if ($_POST['e1_distort_num'])        $reportcard .= "\n      $_POST[e1_distort_num] Failure to enter image size appropriately via HTML attribute or CSS such that the image is distorted in HTML, epub or mobi";
    if ($_POST['e1_alt_num'])            $reportcard .= "\n      $_POST[e1_alt_num] Failure to use appropriate \"alt\" tags for images that have no caption and to include empty \"alt\" tags if captions exist";
    if ($_POST['e1_px_num'])             $reportcard .= "\n      $_POST[e1_px_num] Use of px sizing units for items other than images";
    if ($_POST['e1_title_num'])          $reportcard .= "\n      $_POST[e1_title_num] &lt;title&gt; missing or incorrectly worded (Should be &lt;title&gt;The Project Gutenberg eBook of Alice's Adventures in Wonderland, by Lewis Carroll&lt;title&gt; or &lt;title&gt;Alice's Adventures in Wonderland, by Lewis Carroll&mdash;A Project Gutenberg eBook&lt;title&gt;)";
    if ($_POST['e1_pre_num'])            $reportcard .= "\n      $_POST[e1_pre_num] Use of &lt;pre&gt; tags instead of their CSS equivalents";
    if ($_POST['e1_body_num'])           $reportcard .= "\n      $_POST[e1_body_num] Failure to place &lt;html&gt;, &lt;body&gt;, &lt;head&gt;, &lt;/head&gt;&lt;/body&gt;, and &lt;/html&gt; tags each on their own line and correctly use them";
    if ($_POST['e1_tabl_num'])           $reportcard .= "\n      $_POST[e1_tabl_num] Use of tables for things that are not tables";
    if ($_POST['e1_css_num'])            $reportcard .= "\n      $_POST[e1_css_num] Used CSS other than CSS 2.1 or below (except for the dropcap \"transparent\" element)";
    if ($_POST['e1_xhtml_num'])          $reportcard .= "\n      $_POST[e1_xhtml_num] Used HTML version other than XHTML 1.0 Strict or 1.1";
    if ($_POST['e1_chapter_num'])        $reportcard .= "\n      $_POST[e1_chapter_num] Failure to add &lt;div class=\"chapter\"&gt; at chapter breaks to enable proper page breaks for ereaders";
    if ($_POST['e1_xhtml_genhtml_num'])  $reportcard .= "\n      $_POST[e1_xhtml_genhtml_num] Minor HTML errors in code that do not generate an HTML validation alert such as misspelling a language code (Please explain in the Comments Field)";

    $reportcard .= "\n\n  Level 2 Errors:";
    $reportcard .= "\n\n    All Versions:";
    if (!$_POST['e2_markup_num'] && !$_POST['e2_poetry_num'] && !$_POST['e2_foot_num'] && !$_POST['e2_printers_num']
            && !$_POST['e2_missing_num'] && !$_POST['e2_rewrap_num'] && !$_POST['e2_hyphen_num'] && !$_POST['e2_gen_num']) {
        $reportcard .= "\n      None";
    } else {
        if ($_POST['e2_markup_num'])     $reportcard .= "\n      $_POST[e2_markup_num] Markup not handled (e.g., blockquotes, poetry indentation, or widespread failure to mark italics)";
        if ($_POST['e2_poetry_num'])     $reportcard .= "\n      $_POST[e2_poetry_num] Poetry indentation does not match original";    
        if ($_POST['e2_foot_num'])       $reportcard .= "\n      $_POST[e2_foot_num] Footnotes/footnote markers missing or incorrectly placed";  
        if ($_POST['e2_printers_num'])   $reportcard .= "\n      $_POST[e2_printers_num] Printers' errors not addressed";
        if ($_POST['e2_missing_num'])    $reportcard .= "\n      $_POST[e2_missing_num] Missing page(s) or substantial sections of missing text";
        if ($_POST['e2_rewrap_num'])     $reportcard .= "\n      $_POST[e2_rewrap_num] Substantial rewrapping errors, e.g., poetry has been rewrapped or text version generally not rewrapped to required length (not exceeding 75 characters or falling below 55 characters) except where unavoidable, e.g., some tables though the aim should be 72 characters";
        if ($_POST['e2_hyphen_num'])     $reportcard .= "\n      $_POST[e2_hyphen_num] Widespread/general occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)";
        if ($_POST['e2_gen_num'])        $reportcard .= "\n      $_POST[e2_gen_num] Other major errors that could seriously impact the readability of the book or that represent major inconsistencies between the text and the HTML versions (Please explain in the Comments Field)";
    }
    $reportcard .= "\n\n    HTML Version Only:";
    if (!$_POST['e2_tidy_num'] && !$_POST['e2_csscheck_num'] && !$_POST['e2_links_num'] && !$_POST['e2_file_num']
            && !$_POST['e2_cover_num'] && !$_POST['e2_epub_num'] && !$_POST['e2_heading_num']) {
        $reportcard .= "\n      None";
    } else {
        if ($_POST['e2_tidy_num'])       $reportcard .= "\n      $_POST[e2_tidy_num] The W3C Markup Validation Service generates errors or warning messages (Please enter number of errors)";
        if ($_POST['e2_csscheck_num'])   $reportcard .= "\n      $_POST[e2_csscheck_num] The W3C CSS Validation Service generates errors or warning messages other than for the dropcap \"transparent\" element (Please enter number of errors)";
        if ($_POST['e2_links_num'])      $reportcard .= "\n      $_POST[e2_links_num] Non-working links within HTML or to images. (Either broken or link to wrong place/file)";
        if ($_POST['e2_file_num'])       $reportcard .= "\n      $_POST[e2_file_num] File and folder names not in lowercase or contain spaces, images not in \"images\" folder, etc.";
        if ($_POST['e2_cover_num'])      $reportcard .= "\n      $_POST[e2_cover_num] Cover image has not been included and/or has not been coded for e-reader use. (For example, the cover should be 600x800px or at least 500px wide and no more than 800px high and should be called cover.jpg. Also, if the cover is newly created, it must meet current DP guidelines.)";
        if ($_POST['e2_epub_num'])       $reportcard .= "\n      $_POST[e2_epub_num] Project not presentable/useable when put through epubmaker";
        if ($_POST['e2_heading_num'])    $reportcard .= "\n      $_POST[e2_heading_num] Heading elements used for things that are not headings and failure to use hierarchical headings for book, chapter and section headings (single h1, appropriate h2s and h3s etc.)";
    }

    $reportcard .= "\n\n  Strongly Recommended (These don't count as errors but should be corrected):";
    if (!isset($_POST['s_multi']) && !isset($_POST['s_empty']) && !isset($_POST['s_list']) && !isset($_POST['s_text'])
            && !isset($_POST['s_code']) && !isset($_POST['s_tables']) && !isset($_POST['s_th']) && !isset($_POST['s_thumbs'])
            && !isset($_POST["s_ereader"])) {
        $reportcard .= "\n    None";
    } else {
        if (isset($_POST['s_multi']))     $reportcard .= "\n    Enclose entire multi-part headings within the related heading tag";
        if (isset($_POST['s_empty']))     $reportcard .= "\n    Avoid using empty tags (with &amp;nbsp; entities) or &lt;br /&gt; elements for vertical spacing. e.g. &lt;p&gt;&lt;br /&gt;&lt;br /&gt;&lt;/p&gt; (or with nbsps) -- &lt;td&gt;&amp;nbsp;&lt;/td&gt; is still acceptable though";
        if (isset($_POST['s_list']))      $reportcard .= "\n    List Tags should be used for lists (e.g., a normal index)";
        if (isset($_POST['s_text']))      $reportcard .= "\n    Include all text as text, not just as images";
        if (isset($_POST['s_code']))      $reportcard .= "\n    Keep your code line lengths reasonable";
        if (isset($_POST['s_tables']))    $reportcard .= "\n    Tables should display left, right, and center justification and top and bottom align appropriately";
        if (isset($_POST['s_th']))        $reportcard .= "\n    Tables contain &lt;th&gt; elements for headings";
        if (isset($_POST['s_thumbs']))    $reportcard .= "\n    Remove thumbs.db file from the images folder";
        if (isset($_POST['s_ereader']))   $reportcard .= "\n    E-reader version, although without major flaws, should also look as good as possible";
    }
    $reportcard .= "\n\n  Mildly Recommended (These don't count as errors):";
    if (!isset($_POST['m_semantic']) && !isset($_POST['m_space']) && !isset($_POST['m_unusedcss'])) {
        $reportcard .= "\n    None";
    } else {
        if (isset($_POST['m_semantic']))  $reportcard .= "\n    Distinguish between purely decorative italics/bold/gesperrt and semantic uses of them";
        if (isset($_POST['m_space']))     $reportcard .= "\n    Include space before the slash in self-closing tags (e.g. &lt;br /&gt;)";
        if (isset($_POST['m_unusedcss'])) $reportcard .= "\n    Ensure that there are no unused elements in the CSS (other than the base HTML headings)";
    }

    if(!empty($_POST['general_comments']))
        $reportcard .= "\n\n  General comments:  \n    $_POST[general_comments]";
    if(!empty($_POST['reason_returned']))
        $reportcard .=  "\n\n  Did you have to return the project again because the PPer failed to make requested corrections on the second submission? (If so, please explain): \n    $_POST[reason_returned]";
    $reportcard .= "\n\n" . $site_signoff;

    if (get_magic_quotes_gpc())
        $reportcard = stripslashes($reportcard);

    echo _("Please check the information below to make sure everything is correct.
        To return to the form, simply use your browser's back button.") . "<br />\n";
    echo "<pre>" . $reportcard . "</pre>";
    echo "<form action='{$code_url}/tools/post_proofers/ppv_report.php?project=$projectid&amp;send=1' name='ppvform' method='post'>
                <input type='hidden' name='reportcard' value='" . htmlspecialchars($reportcard, ENT_QUOTES) . "'/>
                <input type='hidden' name='pp_evaluation' value='" . htmlspecialchars($pp_evaluation, ENT_QUOTES) . "'/>";
    if (isset($_POST['cc_pp']))
        echo "<input type='hidden' name='cc_pp'/>";
    if (isset($_POST['cc_ppv']))
        echo "<input type='hidden' name='cc_ppv'/>";
    echo "<input type='submit' value='".attr_safe(_("Send"))."'></form>";
} else if (isset($_GET['send'])) {
    $pper = mysql_fetch_object(mysql_query("SELECT email, u_intlang FROM users WHERE username = '$project->postproofer'"));
    $ppver = mysql_fetch_object(mysql_query("SELECT email, u_intlang FROM users WHERE username = '$pguser'"));
    if (get_magic_quotes_gpc())
        $reportcard = stripslashes($_POST["reportcard"]);

    // The Spanish PPer shouldn't get a French email because that's the PPVer's
    // language, so temporarily change the current locale.
    setlocale(LC_ALL,$pper->u_intlang);
    $ppbita = _("Hello %1\$s,

    This is a message that your Post-Processing Verifier, %2\$s,
    requested you receive from the %4\$s site.

    Thank you for your Post-Processing work on \"%3\$s\".
    A copy of the PPV Summary is below. If you have any questions about it, please contact your PPVer.");
    $ppbit = sprintf($ppbita, $project->postproofer, $pguser, $nameofwork, $site_name);

    setlocale(LC_ALL,$ppver->u_intlang);
    $ppvbita = _("Hello %1\$s,

    This is a message that you requested you receive from the %4\$s
    site.

    Thank you for your Post-Processing Verification work on \"%2\$s\".
    A copy of the summary you submitted is below. If you see an important error,
    please email %3\$s.");

    $ppvbit = sprintf($ppvbita, $pguser, $nameofwork, $general_help_email_addr, $site_name);
    if (isset($_POST['cc_pp'])) {
        $to = $pper->email;
        $subject = "$site_abbreviation PP: $nameofwork";
        $message = $ppbit . $reportcard;
        maybe_mail($to, $subject, $message);
    }

    if (isset($_POST['cc_ppv'])) {
        $to = $ppver->email;
        $subject = "$site_abbreviation PPV: $nameofwork";
        $message = $ppvbit . $reportcard;
        maybe_mail($to, $subject, $message);
    }

    $to = $ppv_reporting_email_addr;
    $subject = "PPV Summary - $project->postproofer ($_POST[pp_evaluation])";
    $message = $reportcard;
    maybe_mail($to, $subject, $message, array("From: $pguser <$ppver->email>"));
    printf(_("Return to <a href='%s'>the Project Page</a>"), "../../project.php?id=$projectid");
    exit();
} else {
    function textarea_size_control($id)
    {
        return "<br /><div class='shrinker'><a onclick='grow_textarea(\"$id\")'>+</a>&nbsp;<a onclick='shrink_textarea(\"$id\")'>&minus;</a></div>";
    }

    echo "<br />
          <form action='{$code_url}/tools/post_proofers/ppv_report.php?project=$projectid&amp;confirm=1' name='ppvform' method='post'>
          <table border='1' id='report_card' style='width: 95%'>

                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>"._("Project Information")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Project ID")."</b></td>
                    <td><input type='hidden' name='projectid' value='$projectid'>$projectid</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Name of Work")."</b></td>
                    <td>$nameofwork</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Author's Name")."</b></td>
                    <td>$authorsname</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Language")."</b></td>
                    <td>$language</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Difficulty")."</b></td>
                    <td>$difficulty_level</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Pages")."</b></td>
                    <td>$pages</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Post-Processed by")."</b></td>
                    <td>$project->postproofer<br>
                    " . sprintf(_("Number of books post-processed by %1\$s (including this one): %2\$d"),
                            $project->postproofer, $number_post_processed) . "</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Submitted by PP on")."</b></td>
                    <td>$pp_date</td>
                </tr>

                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>"._("General Information")."</td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #e0e8dd;'>"._("Difficulty Details")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>File Information</b></td>
                        <td><p class='single'>"._textbox('kb_size', _("Text File Size in kb (Please do not insert commas. For example, you should input 1450 instead of 1,450 and, if you use commas as decimal marks, 1450.5 instead of 1450,5)"), array('size'=>5)) ."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Present in the text")."</b></td>
                    <td>
                        "._checkbox('some_poetry',   _("Some"))  ."&nbsp;&nbsp;"._checkbox('sig_poetry', _("Significant Amount")) ." &mdash; "._("Poetry (other than straight poetry)")."<br />
                        "._checkbox('some_block',    _("Some"))  ."&nbsp;&nbsp;"._checkbox('sig_block',  _("Significant Amount")) ." &mdash; "._("Blockquotes")."<br />
                        "._checkbox('some_foot',     _("Some"))  ."&nbsp;&nbsp;"._checkbox('sig_foot',   _("Significant Amount")) ." &mdash; "._("Footnotes")."<br />
                        "._checkbox('some_side',     _("Some"))  ."&nbsp;&nbsp;"._checkbox('sig_side',   _("Significant Amount")) ." &mdash; "._("Sidenotes")."<br />
                        "._checkbox('some_ads',      _("Some"))  ."&nbsp;&nbsp;"._checkbox('sig_ads',    _("Significant Amount")) ." &mdash; "._("Advertisements")."<br />
                        "._checkbox('some_tables',   _("Some"))  ."&nbsp;&nbsp;"._checkbox('sig_tables', _("Significant Amount")) ." &mdash; "._("Tables")."<br />
                        "._checkbox('some_drama',    _("Some"))  ."&nbsp;&nbsp;"._checkbox('sig_drama',  _("Significant Amount")) ." &mdash; "._("Drama")."<br />
                        "._checkbox('some_index',    _("Small")) ."&nbsp;&nbsp;"._checkbox('sig_index',  _("Significant Size"))   ." &mdash; "._("Index")."<br />
                        "._checkbox('some_illos',    _("Illustrations (other than minor decorations or logos):")) ." "
                            ._textbox('num_illos', _("(Number of)"), array('use_a_label_element'=>TRUE, 'put_label_on_left'=>TRUE)) ."<br />
                        "._checkbox('sig_illos',     _("Illustrations requiring advanced preparation and/or difficult placement")) ."<br />
                        "._checkbox('sig_multilang', _("Multiple Languages")) ." <a href='#languages'>*</a><br />
                        "._checkbox('sig_englifh',   _("Englifh")) ."<br />
                        "._checkbox('sig_music',     _("Musical Notation and Files")) ."<br />
                        "._checkbox('sig_math',      _("Extensive mathematical/chemical notation")) ."<br />
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'></td>
                    <td>
                        <a id='languages'>*</a><b> "._("How to define multiple languages:")."</b><br />
                        <ul>
                            <li>"._("If the book is English on one page and Latin on the facing page, it counts as multiple languages.")."</li>
                            <li>"._("If the author is travelling and repeatedly reports conversations in the foreign language of the country, it counts as multiple languages.")."</li>
                            <li>"._("If extensive (several long paragraphs or more) quotations in a language other than the base language are present, it counts as multiple languages.")."</li>
                            <li>"._("If the Frenchman in the novel says \"Zut!\" a lot, it does NOT count as multiple languages.")."</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #99ff99;'>"._("ERRORS")."</td>
                </tr>
                <tr>
                    <td colspan='2'><div style='margin-left:5%;margin-right:5%;'>
                        <p>"._("Errors such as failure to grasp the italics guidelines are counted as one error, not one error each time italics are wrongly handled. Errors such as he/be errors are each counted as individual errors (i.e., 3 \"he\" instead of \"be\" count as 3 errors).")."</p>
                        <p>"._("If the PPer is asked to resubmit a corrected file, then any errors not corrected or new errors introduced are added to the total number of errors for rating purposes.")."</p></div>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #99ff99;'>"._("LEVEL 1 (Minor Errors)")."</td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #e0e8dd;'>"._("All Versions")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Approximate number of errors <br>(Please enter only numbers)")."</b></td>
                    <td>
                        <p class='single'>"._textbox('e1_spellcheck_num',     _("Spellcheck/Scanno errors")) ."</p>
                        <p class='single'>"._textbox('e1_gutcheck_num',       _("Gutcheck-type errors, e.g. punctuation, hyphen/emdash, missing/extra space, line length, illegal characters, etc.")) ."</p>
                        <p class='single'>"._textbox('e1_jeebies_num',        _("Jeebies errors (English only)")) ."</p>
                        <p class='single'>"._textbox('e1_para_num',           _("Paragraph breaks missing or incorrectly added")) ."</p>
                        <p class='single'>"._textbox('e1_hyph_num',           _("A few occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)")) ."</p>
                        <p class='single'>"._textbox('e1_chap_num',           _("Chapter and other headings inconsistently spaced, aligned, capitalized or punctuated")) ."</p>
                        <p class='single'>"._textbox('e1_format_num',         _("Formatting inconsistencies (e.g., in margins, blank lines, etc.)")) ."</p>
                        <p class='single'>"._textbox('e1_xhtml_genother_num', _("Other minor errors (such as a minor rewrap error, misplaced entry in the TN, or minor inconsistency between the text and HTML versions) (Please explain in the Comments Field)")) ."</p>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #e0e8dd;'>"._("HTML Version Only")."</td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #e0e8dd;'>"._("Images")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Approximate number of errors <br>(Please enter only numbers)")."</b></td>
                    <td>
                        <p class='single'>"._textbox('e1_unused_num',    _("Unused files in images folder (Thumbs.db is not counted toward rating)")) ."</p>
                        <p class='single'>"._textbox('e1_imagesize_num', _("Appropriate image size not used for thumbnail, inline and linked-to images. Image sizes should not normally exceed the limits described <a href='http://www.pgdp.net/wiki/Guide_to_Image_Processing#Image_Display_Dimensions:_Considerations'>here</a>, but exceptions may be made if warranted by the type of image or book (provided the PPer explains the exception).")) ."</p>
                        <p class='single'>"._textbox('e1_blemish_num',   _("Images with major blemishes, uncorrected rotation/distortion or without appropriate cropping")) ."</p>
                        <p class='single'>"._textbox('e1_distort_num',   _("Failure to enter image size appropriately via HTML attribute or CSS such that the image is distorted in HTML, epub or mobi")) ."</p>
                        <p class='single'>"._textbox('e1_alt_num',       _("Failure to use appropriate \"alt\" tags for images that have no caption and to include empty \"alt\" tags if captions exist")) ."</p>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #e0e8dd;'>"._("HTML Code")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Approximate number of errors <br>(Please enter only numbers)")."</b></td>
                    <td>
                        <p class='single'>"._textbox('e1_px_num',            _("Use of px sizing units for items other than images")) ."</p>
                        <p class='single'>"._textbox('e1_title_num',         _("&lt;title&gt; missing or incorrectly worded (Should be &lt;title&gt;The Project Gutenberg eBook of Alice's Adventures in Wonderland, by Lewis Carroll&lt;/title&gt; or &lt;title&gt;Alice's Adventures in Wonderland, by Lewis Carroll&mdash;A Project Gutenberg eBook&lt;/title&gt;)")) ."</p>
                        <p class='single'>"._textbox('e1_pre_num',           _("Use of &lt;pre&gt; tags instead of their CSS equivalents")) ."</p>
                        <p class='single'>"._textbox('e1_body_num',          _("Failure to place &lt;html&gt;, &lt;body&gt;, &lt;head&gt;, &lt;/head&gt;&lt;/body&gt;, and &lt;/html&gt; tags each on their own line and correctly use them")) ."</p>
                        <p class='single'>"._textbox('e1_tabl_num',          _("Use of tables for things that are not tables")) ."</p>
                        <p class='single'>"._textbox('e1_css_num',           _("Used CSS other than CSS 2.1 or below (except for the dropcap \"transparent\" element)")) ."</p>
                        <p class='single'>"._textbox('e1_xhtml_num',         _("Used HTML version other than XHTML 1.0 Strict or 1.1")) ."</p>
                        <p class='single'>"._textbox('e1_chapter_num',       _("Failure to add &lt;div class=\"chapter\"&gt; at chapter breaks to enable proper page breaks for ereaders")) ."</p>
                        <p class='single'>"._textbox('e1_xhtml_genhtml_num', _("Minor HTML errors in code that do not generate an HTML validation alert such as misspelling a language code (Please explain in the Comments Field)")) ."</p>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #99ff99;'>"._("LEVEL 2 (Major Errors)")."</td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #e0e8dd;'>"._("All Versions")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Approximate number of errors <br>(Please enter only numbers)")."</b></td>
                    <td>
                        <p class='single'>"._textbox('e2_markup_num',   _("Markup not handled (e.g. blockquotes, poetry indentation, or widespread failure to mark italics)")) ."</p>
                        <p class='single'>"._textbox('e2_poetry_num',   _("Poetry indentation does not match original")) ."</p>
                        <p class='single'>"._textbox('e2_foot_num',     _("Footnotes/footnote markers missing or incorrectly placed")) ."</p>
                        <p class='single'>"._textbox('e2_printers_num', _("Printers' errors not addressed")) ." <a href='#print'>**</a></p>
                        <p class='single'>"._textbox('e2_missing_num',  _("Missing page(s) or substantial sections of missing text")) ."</p>
                        <p class='single'>"._textbox('e2_rewrap_num',   _("Substantial rewrapping errors, e.g., poetry has been rewrapped or text version generally not rewrapped to required length (not exceeding 75 characters or falling below 55 characters) except where unavoidable, e.g., some tables though the aim should be 72 characters")) ."</p>
                        <p class='single'>"._textbox('e2_hyphen_num',   _("Widespread/general occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)")) ."</p>
                        <p class='single'>"._textbox('e2_gen_num',      _("Other major errors that could seriously impact the readability of the book or that represent major inconsistencies between the text and the HTML versions (Please explain in the Comments Field)")) ."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'></td>
                    <td>
                        <p><a id='print'>**</a><b> "._("Printers' Errors and Transcriber's Note")."</b>:
                        <p>"._("Obvious printers' errors should be addressed in one, or a combination, of the following ways:")."</p>
                        <ul>
                            <li>"._("Correct silently and state in the Transcriber's Note that all such errors have been corrected silently.")."</li>
                            <li>"._("Correct all such errors and note them in Transcriber's Note")."</li>
                            <li>"._("Leave uncorrected and state in the Transcriber's Note that at all such errors were left uncorrected.")."</li>
                        </ul>
                        <p>"._("\"Not addressing printers' errors\" means that all, or a large percentage, of printers' errors have been left uncorrected and not noted. If just one or two have been missed, and the rest addressed, then those missed would instead be counted as the relevant type of error (spellcheck, gutcheck, etc.). Anything that could make a reader think an error has been made in the transcription should be mentioned in the Transcriber's Note.")."</p>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #e0e8dd;'>"._("HTML Version Only")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Approximate number of errors <br>(Please enter only numbers)")."</b></td>
                    <td>
                        <p class='single'>"._textbox('e2_tidy_num',     _("The W3C Markup Validation Service generates errors or warning messages (Please enter number of errors)")) ."</p>
                        <p class='single'>"._textbox('e2_csscheck_num', _("The W3C CSS Validation Service generates errors or warning messages other than for the dropcap \"transparent\" element (Please enter number of errors)")) ."</p>
                        <p class='single'>"._textbox('e2_links_num',    _("Non-working links within HTML or to images. (Either broken or link to wrong place/file)")) ."</p>
                        <p class='single'>"._textbox('e2_file_num',     _("File and folder names not in lowercase or contain spaces, images not in \"images\" folder, etc.")) ."</p>
                        <p class='single'>"._textbox('e2_cover_num',    _("Cover image has not been included and/or has not been coded for e-reader use. (For example, the cover should be 600x800px or at least 500px wide and no more than 800px high and should be called cover.jpg. Also, if the cover is newly created, it must meet <a href='http://www.pgdp.net/wiki/PP_guide_to_cover_pages#DP_policy'>current DP guidelines</a>.)")) ."</p>
                        <p class='single'>"._textbox('e2_epub_num',     _("Project not presentable/useable when put through epubmaker")) ." <a href='#ereader'>***</a></p>
                        <p class='single'>"._textbox('e2_heading_num',  _("Heading elements used for things that are not headings and failure to use hierarchical headings for book, chapter and section headings (single h1, appropriate h2s and h3s etc.)")) ."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'></td>
                    <td>
                        <p><a id='ereader'><b>*** "._("Checking E-reader Versions")."</b></a></p>
                        <p>"._("It doesn't take long to look through the pages of the epub and mobi versions using the <a href='http://www.pgdp.net/wiki/Easy_Epub/Viewing#I_don.27t_have_an_e-reader.21'>suggested emulators</a>. Here are some problem areas to look for:")."</p>
                        <p><b>"._("Front and End of Book")."</b></p>
                        <ul>
                            <li>"._("TOC")."</li>
                            <li>"._("Title page layout")."</li>
                        </ul>
                        <p><b>"._("Body of Book")."</b></p>
                        <ul>
                            <li>"._("Horizontal rules")."</li>
                            <li>"._("Obscured sections within the book such that text covers other text or blank areas occur where text should be")."</li>
                            <li>"._("Poetry")."</li>
                            <li>"._("Dropcaps")."</li>
                            <li>"._("If hovers were used in the HTML, all important \"hovered\" information should be present and readable in a non-hovered way within the e-reader version. Also Transcriber's Notes referring to hovers should be hidden in the e-reader version.")."</li>
                            <li>"._("Headings")."</li>
                            <li>"._("Blockquotes")."</li>
                            <li>"._("Page numbers (if present)")."</li>
                            <li>"._("Sidenotes")."</li>
                            <li>"._("Margins")."</li>
                            <li>"._("Tables")."</li>
                            <li>"._("Illustrations")."</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #99ff99;'>"._("STRONGLY RECOMMENDED<br />(Failure to follow these guidelines will not be tabulated as errors, but the PPer should be counselled to correct any problems)")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Occurrence")."</b></td>
                    <td>
                        <p class='single2'>"._checkbox('s_multi', _("Enclose entire multi-part headings within the related heading tag")) ."</p>
                        <p class='single2'>"._checkbox('s_empty', _("Avoid using empty tags (with &amp;nbsp; entities) or &lt;br /&gt; elements for vertical spacing. e.g. &lt;p&gt;&lt;br /&gt;&lt;br /&gt;&lt;/p&gt; (or with nbsps) -- &lt;td&gt;&amp;nbsp;&lt;/td&gt; is still acceptable though")) ."</p>
                        <p class='single2'>"._checkbox('s_list', _("List Tags should be used for lists (e.g., a normal index)")) ."</p>
                        <p class='single2'>"._checkbox('s_text', _("Include all text as text, not just as images")) ."</p>
                        <p class='single2'>"._checkbox('s_code', _("Keep your code line lengths reasonable")) ."</p>
                        <p class='single2'>"._checkbox('s_tables', _("Tables should display left, right, and center justification and top and bottom align appropriately")) ."</p>
                        <p class='single2'>"._checkbox('s_th', _("Tables contain &lt;th&gt; elements for headings")) ."</p>
                        <p class='single2'>"._checkbox('s_thumbs', _("Remove thumbs.db file from the images folder")) ."</p>
                        <p class='single2'>"._checkbox('s_ereader', _("E-reader version, although without major flaws, should also look as good as possible")) ."</p>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #99ff99;'>"._("MILDLY RECOMMENDED<br />(Failure to follow these guidelines will not be tabulated as errors, and any corrections are solely at the discretion of the PPVer and PPer)")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Occurrence")."</b></td>
                    <td>
                        <p class='single2'>"._checkbox('m_semantic', _("Distinguish between purely decorative italics/bold/gesperrt and semantic uses of them")) ."</p>
                        <p class='single2'>"._checkbox('m_space', _("Include space before the slash in self-closing tags (e.g. &lt;br /&gt;)")) ."</p>
                        <p class='single2'>"._checkbox('m_unusedcss', _("Ensure that there are no unused elements in the CSS (other than the base HTML headings)")) ."</p>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: #99ff99;'>"._("COMMENTS")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'>
                        <b>"._("Did you have to return the project again because the PPer failed to make requested corrections on the second submission? (If so, please explain)")."</b>
                    </td>
                    <td><textarea rows='4' cols='67' name='reason_returned' id='reason_returned' wrap='hard'></textarea>".textarea_size_control('reason_returned')."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>" . _("General comments on this project or your experience working with this PPer."). "</b></td>
                    <td><textarea rows='4' cols='67' name='general_comments' id='general_comments' wrap='hard'></textarea>".textarea_size_control('general_comments')."</td>
                </tr>

                <tr>
                    <td colspan='2' style='text-align: center; font-weight: bold; background: $theme[color_logobar_bg];'>"._("Copies")."</td>
                </tr>
                <tr>
                    <td style='background-color: #CCCCCC; width: 40%;'><b>"._("Send to")."</b></td>
                    <td>"._checkbox('cc_ppv', _("Me")) ."<br />
                            ". _checkbox('cc_pp', $project->postproofer, TRUE) ."<br />
                            <input type='checkbox' name='foo' checked disabled>"._("PPV Summary (mailing list)")."
                    </td>
                </tr>
                <tr><td colspan='2' style='text-align: center'>
                    <input type='submit' value='".attr_safe(_("Submit"))."'></td></tr>
        </table>
    </form>";
}

function _checkbox($id, $label, $checked=FALSE)
{
    $checked_attr = ($checked ? ' checked': '');
    return "<input type='checkbox' name='$id' id='$id'$checked_attr><label for='$id'>$label</label>";
}

function _textbox($id, $label, $options=array())
{
    $size = array_get($options, 'size', 3);
    $use_a_label_element = array_get($options, 'use_a_label_element', FALSE);
    $put_label_on_left = array_get($options, 'put_label_on_left', FALSE);

    $input_element = "<input type='text' size='$size' name='$id' id='$id'>";

    if ($use_a_label_element)
    {
        $label_thing = "<label for='$id'>$label</label>";
        $connector = " ";
    }
    else
    {
        $label_thing = $label;
        $connector = "&nbsp;&nbsp;";
    }

    if ($put_label_on_left)
    {
        $result = $label_thing . $connector . $input_element;
    }
    else
    {
        $result = $input_element . $connector . $label_thing;
    }
    return $result;
}

// vim: sw=4 ts=4 expandtab
