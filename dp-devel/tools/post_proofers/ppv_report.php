<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'Project.inc'); // validate_projectID()
include_once($relPath.'Stage.inc'); //user_can_work_in_stage()
include_once($relPath.'project_states.inc'); // get_project_status_descriptor()
include_once($relPath.'misc.inc');  // javascript_safe() array_get() startswith()

header_remove("Expires");
header_remove("Cache-Control");

require_login();

$projectid = validate_projectID('project', @$_REQUEST['project']);

define('SHOW_BLANK_ENTRY_FORM',        'SHOW_BLANK_ENTRY_FORM');
define('HANDLE_ENTRY_FORM_SUBMISSION', 'HANDLE_ENTRY_FORM_SUBMISSION');
define('SEND_OUT_REPORTCARD',          'SEND_OUT_REPORTCARD');

if (isset($_GET['confirm']))
    $action = HANDLE_ENTRY_FORM_SUBMISSION;
else if (isset($_GET['send']))
    $action = SEND_OUT_REPORTCARD;
else
    $action = SHOW_BLANK_ENTRY_FORM;

// -------------------------------------

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

if ($action == SHOW_BLANK_ENTRY_FORM)
{
    $i4 = "                ";
    $i5 = $i4 . "    ";
    $i6 = $i5 . "    ";

    function tr_w_one_cell_centered($bgcolor, $content)
    {
        global $i4, $i5;
        return ""
            . "\n$i4<tr>"
            . "\n$i5<td colspan='2' style='text-align: center; font-weight: bold; background: $bgcolor;'>$content</td>"
            . "\n$i4</tr>";
    }

    function tr_w_one_cell($content)
    {
        global $i4, $i5;
        return ""
            . "\n$i4<tr>"
            . "\n$i5<td colspan='2'>"
            . $content
            . "\n$i5</td>"
            . "\n$i4</tr>";
    }

    function tr_w_two_cells($left_content, $right_content)
    {
        global $i4, $i5;
        return ""
            . "\n$i4<tr>"
            . "\n$i5<td style='background-color: #CCCCCC; width: 40%;'><b>$left_content</b></td>"
            . "\n$i5<td>"
            . $right_content
            . "\n$i5</td>"
            . "\n$i4</tr>";
    }

    // ------------------------

    function some_sig_combo($some_id, $some_label, $sig_id, $sig_label, $final_label)
    {
        global $i6;
        return ""
            . "\n$i6"
            . "<p class='single2'>"
            . _checkbox($some_id, $some_label)
            . "&nbsp;&nbsp;"
            . _checkbox($sig_id, $sig_label)
            . " &mdash; "
            . $final_label
            . "</p>";
    }

    function some_num_combo($some_id, $some_label, $num_id)
    {
        global $i6;
        return ""
            . "\n$i6"
            . "<p class='single2'>"
            . _checkbox($some_id, $some_label)
            . ": "
            . _textbox($num_id, _("(Number of)"), array('use_a_label_element'=>TRUE, 'put_label_on_left'=>TRUE))
            . "</p>";
    }

    function check_box($id, $label, $checked=FALSE)
    {
        global $i6;
        return ""
            . "\n$i6"
            . "<p class='single2'>"
            . _checkbox($id, $label, $checked)
            . "</p>";
    }

    function _checkbox($id, $label, $checked=FALSE)
    {
        $checked_attr = ($checked ? ' checked': '');
        return "<input type='checkbox' name='$id' id='$id'$checked_attr><label for='$id'>$label</label>";
    }

    function number_box($id, $label, $options=array())
    {
        global $i6;
        return ""
            . "\n$i6"
            . "<p class='single'>"
            . _textbox($id, $label, $options)
            . "</p>";
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

    function comment_box($id)
    {
        return ""
            . "<textarea rows='4' cols='67' name='$id' id='$id' wrap='hard'></textarea>"
            . "<br />"
            . "<div class='shrinker'>"
            . "<a onclick='grow_textarea(\"$id\")'>+</a>"
            . "&nbsp;"
            . "<a onclick='shrink_textarea(\"$id\")'>&minus;</a>"
            . "</div>";
    }

    // ---------------------------------

    echo "<br />
          <form action='{$code_url}/tools/post_proofers/ppv_report.php?project=$projectid&amp;confirm=1' name='ppvform' method='post'>
          <table border='1' id='report_card' style='width: 95%'>
"
        . tr_w_one_cell_centered($theme['color_logobar_bg'], _("Project Information"))
        . tr_w_two_cells(
            _("Project ID"),
            "<input type='hidden' name='projectid' value='$projectid'>$projectid"
        )
        . tr_w_two_cells(
            _("Name of Work"),
            $nameofwork
        )
        . tr_w_two_cells(
            _("Author's Name"),
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

        . tr_w_one_cell_centered($theme['color_logobar_bg'], _("General Information"))
        . tr_w_one_cell_centered("#e0e8dd", _("Difficulty Details"))
        . tr_w_two_cells(
            "File Information",
            number_box('kb_size', _("Text File Size in kb (Please do not insert commas. For example, you should input 1450 instead of 1,450 and, if you use commas as decimal marks, 1450.5 instead of 1450,5)"), array('size'=>5))
        )
        . tr_w_two_cells(
            _("Present in the text"),
            ""
                . some_sig_combo('some_poetry',   _("Some"),  'sig_poetry', _("Significant Amount"), _("Poetry (other than straight poetry)"))
                . some_sig_combo('some_block',    _("Some"),  'sig_block',  _("Significant Amount"), _("Blockquotes"))
                . some_sig_combo('some_foot',     _("Some"),  'sig_foot',   _("Significant Amount"), _("Footnotes"))
                . some_sig_combo('some_side',     _("Some"),  'sig_side',   _("Significant Amount"), _("Sidenotes"))
                . some_sig_combo('some_ads',      _("Some"),  'sig_ads',    _("Significant Amount"), _("Advertisements"))
                . some_sig_combo('some_tables',   _("Some"),  'sig_tables', _("Significant Amount"), _("Tables"))
                . some_sig_combo('some_drama',    _("Some"),  'sig_drama',  _("Significant Amount"), _("Drama"))
                . some_sig_combo('some_index',    _("Small"), 'sig_index',  _("Significant Size"),   _("Index"))
                . some_num_combo('some_illos', _("Illustrations (other than minor decorations or logos)"), 'num_illos')
                . check_box('sig_illos',     _("Illustrations requiring advanced preparation and/or difficult placement"))
                . check_box('sig_multilang', _("Multiple Languages") . " <a href='#languages'>*</a>")
                . check_box('sig_englifh',   _("Englifh"))
                . check_box('sig_music',     _("Musical Notation and Files"))
                . check_box('sig_math',      _("Extensive mathematical/chemical notation"))
        )
        . tr_w_two_cells(
            "",
            "
                <a id='languages'>*</a><b> "._("How to define multiple languages:")."</b><br />
                <ul>
                    <li>"._("If the book is English on one page and Latin on the facing page, it counts as multiple languages.")."</li>
                    <li>"._("If the author is travelling and repeatedly reports conversations in the foreign language of the country, it counts as multiple languages.")."</li>
                    <li>"._("If extensive (several long paragraphs or more) quotations in a language other than the base language are present, it counts as multiple languages.")."</li>
                    <li>"._("If the Frenchman in the novel says \"Zut!\" a lot, it does NOT count as multiple languages.")."</li>
                </ul>"
        )
        . tr_w_one_cell_centered("#99ff99", _("ERRORS"))
        . tr_w_one_cell(
            "
                <div style='margin-left:5%;margin-right:5%;'>
                    <p>"._("Errors such as failure to grasp the italics guidelines are counted as one error, not one error each time italics are wrongly handled. Errors such as he/be errors are each counted as individual errors (i.e., 3 \"he\" instead of \"be\" count as 3 errors).")."</p>
                    <p>"._("If the PPer is asked to resubmit a corrected file, then any errors not corrected or new errors introduced are added to the total number of errors for rating purposes.")."</p>
                </div>"
        )
        . tr_w_one_cell_centered("#99ff99", _("LEVEL 1 (Minor Errors)"))
        . tr_w_one_cell_centered("#e0e8dd", _("All Versions"))
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e1_spellcheck_num',     _("Spellcheck/Scanno errors"))
                . number_box('e1_gutcheck_num',       _("Gutcheck-type errors, e.g. punctuation, hyphen/emdash, missing/extra space, line length, illegal characters, etc."))
                . number_box('e1_jeebies_num',        _("Jeebies errors (English only)"))
                . number_box('e1_para_num',           _("Paragraph breaks missing or incorrectly added"))
                . number_box('e1_hyph_num',           _("A few occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)"))
                . number_box('e1_chap_num',           _("Chapter and other headings inconsistently spaced, aligned, capitalized or punctuated"))
                . number_box('e1_format_num',         _("Formatting inconsistencies (e.g., in margins, blank lines, etc.)"))
                . number_box('e1_xhtml_genother_num', _("Other minor errors (such as a minor rewrap error, misplaced entry in the TN, or minor inconsistency between the text and HTML versions) (Please explain in the Comments Field)"))
        )
        . tr_w_one_cell_centered("#e0e8dd", _("HTML Version Only"))
        . tr_w_one_cell_centered("#e0e8dd", _("Images"))
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e1_unused_num',    _("Unused files in images folder (Thumbs.db is not counted toward rating)"))
                . number_box('e1_imagesize_num', _("Appropriate image size not used for thumbnail, inline and linked-to images. Image sizes should not normally exceed the limits described <a href='http://www.pgdp.net/wiki/Guide_to_Image_Processing#Image_Display_Dimensions:_Considerations'>here</a>, but exceptions may be made if warranted by the type of image or book (provided the PPer explains the exception)."))
                . number_box('e1_blemish_num',   _("Images with major blemishes, uncorrected rotation/distortion or without appropriate cropping"))
                . number_box('e1_distort_num',   _("Failure to enter image size appropriately via HTML attribute or CSS such that the image is distorted in HTML, epub or mobi"))
                . number_box('e1_alt_num',       _("Failure to use appropriate \"alt\" tags for images that have no caption and to include empty \"alt\" tags if captions exist"))
        )
        . tr_w_one_cell_centered("#e0e8dd", _("HTML Code"))
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e1_px_num',            _("Use of px sizing units for items other than images"))
                . number_box('e1_title_num',         _("&lt;title&gt; missing or incorrectly worded (Should be &lt;title&gt;The Project Gutenberg eBook of Alice's Adventures in Wonderland, by Lewis Carroll&lt;/title&gt; or &lt;title&gt;Alice's Adventures in Wonderland, by Lewis Carroll&mdash;A Project Gutenberg eBook&lt;/title&gt;)"))
                . number_box('e1_pre_num',           _("Use of &lt;pre&gt; tags instead of their CSS equivalents"))
                . number_box('e1_body_num',          _("Failure to place &lt;html&gt;, &lt;body&gt;, &lt;head&gt;, &lt;/head&gt;&lt;/body&gt;, and &lt;/html&gt; tags each on their own line and correctly use them"))
                . number_box('e1_tabl_num',          _("Use of tables for things that are not tables"))
                . number_box('e1_css_num',           _("Used CSS other than CSS 2.1 or below (except for the dropcap \"transparent\" element)"))
                . number_box('e1_xhtml_num',         _("Used HTML version other than XHTML 1.0 Strict or 1.1"))
                . number_box('e1_chapter_num',       _("Failure to add &lt;div class=\"chapter\"&gt; at chapter breaks to enable proper page breaks for ereaders"))
                . number_box('e1_xhtml_genhtml_num', _("Minor HTML errors in code that do not generate an HTML validation alert such as misspelling a language code (Please explain in the Comments Field)"))
        )
        . tr_w_one_cell_centered("#99ff99", _("LEVEL 2 (Major Errors)"))
        . tr_w_one_cell_centered("#e0e8dd", _("All Versions"))
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e2_markup_num',   _("Markup not handled (e.g. blockquotes, poetry indentation, or widespread failure to mark italics)"))
                . number_box('e2_poetry_num',   _("Poetry indentation does not match original"))
                . number_box('e2_foot_num',     _("Footnotes/footnote markers missing or incorrectly placed"))
                . number_box('e2_printers_num', _("Printers' errors not addressed") . " <a href='#print'>**</a>")
                . number_box('e2_missing_num',  _("Missing page(s) or substantial sections of missing text"))
                . number_box('e2_rewrap_num',   _("Substantial rewrapping errors, e.g., poetry has been rewrapped or text version generally not rewrapped to required length (not exceeding 75 characters or falling below 55 characters) except where unavoidable, e.g., some tables though the aim should be 72 characters"))
                . number_box('e2_hyphen_num',   _("Widespread/general occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)"))
                . number_box('e2_gen_num',      _("Other major errors that could seriously impact the readability of the book or that represent major inconsistencies between the text and the HTML versions (Please explain in the Comments Field)"))
        )
        . tr_w_two_cells(
            "",
            "
                <p><a id='print'>**</a><b> "._("Printers' Errors and Transcriber's Note")."</b>:
                <p>"._("Obvious printers' errors should be addressed in one, or a combination, of the following ways:")."</p>
                <ul>
                    <li>"._("Correct silently and state in the Transcriber's Note that all such errors have been corrected silently.")."</li>
                    <li>"._("Correct all such errors and note them in Transcriber's Note")."</li>
                    <li>"._("Leave uncorrected and state in the Transcriber's Note that at all such errors were left uncorrected.")."</li>
                </ul>
                <p>"._("\"Not addressing printers' errors\" means that all, or a large percentage, of printers' errors have been left uncorrected and not noted. If just one or two have been missed, and the rest addressed, then those missed would instead be counted as the relevant type of error (spellcheck, gutcheck, etc.). Anything that could make a reader think an error has been made in the transcription should be mentioned in the Transcriber's Note.")."</p>"
        )
        . tr_w_one_cell_centered("#e0e8dd", _("HTML Version Only"))
        . tr_w_two_cells(
            _("Approximate number of errors <br>(Please enter only numbers)"),
            ""
                . number_box('e2_tidy_num',     _("The W3C Markup Validation Service generates errors or warning messages (Please enter number of errors)"))
                . number_box('e2_csscheck_num', _("The W3C CSS Validation Service generates errors or warning messages other than for the dropcap \"transparent\" element (Please enter number of errors)"))
                . number_box('e2_links_num',    _("Non-working links within HTML or to images. (Either broken or link to wrong place/file)"))
                . number_box('e2_file_num',     _("File and folder names not in lowercase or contain spaces, images not in \"images\" folder, etc."))
                . number_box('e2_cover_num',    _("Cover image has not been included and/or has not been coded for e-reader use. (For example, the cover should be 600x800px or at least 500px wide and no more than 800px high and should be called cover.jpg. Also, if the cover is newly created, it must meet <a href='http://www.pgdp.net/wiki/PP_guide_to_cover_pages#DP_policy'>current DP guidelines</a>.)"))
                . number_box('e2_epub_num',     _("Project not presentable/useable when put through epubmaker") . " <a href='#ereader'>***</a>")
                . number_box('e2_heading_num',  _("Heading elements used for things that are not headings and failure to use hierarchical headings for book, chapter and section headings (single h1, appropriate h2s and h3s etc.)"))
        )
        . tr_w_two_cells(
            "",
            "
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
                </ul>"
        )
        . tr_w_one_cell_centered("#99ff99", _("STRONGLY RECOMMENDED<br />(Failure to follow these guidelines will not be tabulated as errors, but the PPer should be counselled to correct any problems)"))
        . tr_w_two_cells(
            _("Occurrence"),
            ""
                . check_box('s_multi',   _("Enclose entire multi-part headings within the related heading tag"))
                . check_box('s_empty',   _("Avoid using empty tags (with &amp;nbsp; entities) or &lt;br /&gt; elements for vertical spacing. e.g. &lt;p&gt;&lt;br /&gt;&lt;br /&gt;&lt;/p&gt; (or with nbsps) -- &lt;td&gt;&amp;nbsp;&lt;/td&gt; is still acceptable though"))
                . check_box('s_list',    _("List Tags should be used for lists (e.g., a normal index)"))
                . check_box('s_text',    _("Include all text as text, not just as images"))
                . check_box('s_code',    _("Keep your code line lengths reasonable"))
                . check_box('s_tables',  _("Tables should display left, right, and center justification and top and bottom align appropriately"))
                . check_box('s_th',      _("Tables contain &lt;th&gt; elements for headings"))
                . check_box('s_thumbs',  _("Remove thumbs.db file from the images folder"))
                . check_box('s_ereader', _("E-reader version, although without major flaws, should also look as good as possible"))
        )
        . tr_w_one_cell_centered("#99ff99", _("MILDLY RECOMMENDED<br />(Failure to follow these guidelines will not be tabulated as errors, and any corrections are solely at the discretion of the PPVer and PPer)"))
        . tr_w_two_cells(
            _("Occurrence"),
            ""
                . check_box('m_semantic',  _("Distinguish between purely decorative italics/bold/gesperrt and semantic uses of them"))
                . check_box('m_space',     _("Include space before the slash in self-closing tags (e.g. &lt;br /&gt;)"))
                . check_box('m_unusedcss', _("Ensure that there are no unused elements in the CSS (other than the base HTML headings)"))
        )
        . tr_w_one_cell_centered("#99ff99", _("COMMENTS"))
        . tr_w_two_cells(
            _("Did you have to return the project again because the PPer failed to make requested corrections on the second submission? (If so, please explain)"),
            comment_box('reason_returned')
        )
        . tr_w_two_cells(
            _("General comments on this project or your experience working with this PPer."),
            comment_box('general_comments')
        )
        . tr_w_one_cell_centered($theme['color_logobar_bg'], _("Copies"))
        . tr_w_two_cells(
            _("Send to"),
            ""
                . check_box('cc_ppv', _("Me"))
                . check_box('cc_pp', $project->postproofer, TRUE) ."
                        <p class='single2'><input type='checkbox' name='foo' checked disabled>"._("PPV Summary (mailing list)") ."</p>"
        )
        . tr_w_one_cell_centered("#ffffff", "<input type='submit' value='".attr_safe(_("Submit"))."'>") ."
        </table>
    </form>";
}
else if ($action == HANDLE_ENTRY_FORM_SUBMISSION)
{

    // ---------------------------------
    // Validate the form input.

    function report_form_problem($message)
    {
        echo "\n<p class='form_problem'>$message</p>";
        exit();
    }

    $project_size = $_POST["kb_size"];
    if ((isset($_POST["some_poetry"]) && isset($_POST["sig_poetry"])) || (isset($_POST["some_block"]) && isset($_POST["sig_block"]))
            || (isset($_POST["some_foot"]) && isset($_POST["sig_foot"])) || (isset($_POST["some_side"]) && isset($_POST["sig_side"]))
            || (isset($_POST["some_ads"]) && isset($_POST["sig_ads"])) || (isset($_POST["some_tables"]) && isset($_POST["sig_tables"]))
            || (isset($_POST["some_index"]) && isset($_POST["sig_index"])) || (isset($_POST["some_drama"]) && isset($_POST["sig_drama"]))) {
        report_form_problem(
            _("You selected both \"Some\" and \"Significant Amount\" for an item.
            Please go back, fix this, and resubmit the form.")
        );
    } else if (strpos($project_size, ',') !== false) {
        report_form_problem(
            _("The file size should not contain commas.")
        );
    } else if ($project_size == "" || $project_size == 0) {
        report_form_problem(
            _("Please enter a file size that is greater than 0.")
        );
    } else if ($project_size > 3000) {
        report_form_problem(
            _("You put in a file size greater than 3000 KB.
            Please make sure that you have the file size in kilobytes, not bytes.")
        );
    } else if (isset($_POST["some_illos"]) && !isset($_POST["num_illos"])) {
        report_form_problem(
            _("You selected there were illustrations but didn't specify how many.
            Please go back and specify how many illustrations there were")
        );
    } else if (isset($_POST["some_illos"]) && (!is_numeric($_POST["num_illos"]) || $_POST["num_illos"] == 0)) {
        report_form_problem(
            _("Please input a non-0 number for how many illustrations were in the book.")
        );
    } else if (!empty($_POST["num_illos"]) && !isset($_POST["some_illos"])) {
        report_form_problem(
            sprintf(_("You put that there were %s illustrations but didn't check the box for illustrations.
            Please go back and select the checkbox for 'Illustrations (other than minor decorations or logos)'."), $_POST["num_illos"])
        );
    }

    foreach($_POST as $key => $value) {
        if (startswith($key, "e1_") && !empty($value)) {
            if (!is_numeric($value)) {
                report_form_problem(
                    _("Please input a number for all Level 1 error fields.
                    Not all fields must be completed, but all data input in the error fields must be numeric.")
                );
            }
        } else if (startswith($key, "e2_") && !empty($value)) {
            if (!is_numeric($value)) {
                report_form_problem(
                    _("Please input a number for all Level 2 error fields.
                    Not all fields must be completed, but all data input in the error fields must be numeric.")
                );
            }
        }
    }

    // ---------------------------------

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
        if (startswith($key, "sig_") && isset($mapped_array[$key])) {
            $project_significant_counter++;
            $pping_complexity .= "\n    " . $mapped_array[$key];
        } else if (startswith($key, "some_") && isset($mapped_array[$key])) {
            $project_average_counter++;
            $pping_complexity .= "\n    " . $mapped_array[$key];
        } else if ($key === "some_illos") {
            if ($_POST["num_illos"] >= 40) {
                $project_significant_counter++;
            } else if ($_POST["num_illos"] > 5) {
                $project_average_counter++;
            }
            $pping_complexity .= "\n    " . $_POST["num_illos"] . " Illustrations (other than minor decorations or logos)";
        } else if (startswith($key, "e1_") && !empty($value)) {
            $level_1_errors += $value;
        } else if (startswith($key, "e2_") && !empty($value)) {
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

    function number_of_errors_allowed($size_per) {
        $project_size = $_POST["kb_size"];
        if ($project_size <= $size_per)
            return 1;

        return floor($project_size / $size_per);
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
    $reportcard .= report_error_counts(array(
        'e1_spellcheck_num'     => "Spellcheck/Scanno errors",
        'e1_gutcheck_num'       => "Gutcheck-type errors, e.g. punctuation, hyphen/emdash, missing/extra space, line length, illegal characters, etc.",    
        'e1_jeebies_num'        => "Jeebies errors (English only)",  
        'e1_para_num'           => "Paragraph breaks missing or incorrectly added",
        'e1_hyph_num'           => "A few occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)",
        'e1_chap_num'           => "Chapter and other headings inconsistently spaced, aligned, capitalized or punctuated",
        'e1_format_num'         => "Formatting inconsistencies (e.g., in margins, blank lines, etc.)",
        'e1_xhtml_genother_num' => "Other minor errors (such as a minor rewrap error, misplaced entry in the TN, or minor inconsistency between the text and HTML versions) (Please explain in the Comments Field)",
    ));
    $reportcard .= "\n\n    HTML Version Only:";
    $reportcard .= report_error_counts(array(
        'e1_unused_num'         => "Unused files in images folder (Thumbs.db is not counted toward rating)",
        'e1_imagesize_num'      => "Appropriate image size not used for thumbnail, inline and linked-to images. Image sizes should not normally exceed the limits described here, but exceptions may be made if warranted by the type of image or book (provided the PPer explains the exception).",
        'e1_blemish_num'        => "Images with major blemishes, uncorrected rotation/distortion or without appropriate cropping",
        'e1_distort_num'        => "Failure to enter image size appropriately via HTML attribute or CSS such that the image is distorted in HTML, epub or mobi",
        'e1_alt_num'            => "Failure to use appropriate \"alt\" tags for images that have no caption and to include empty \"alt\" tags if captions exist",
        'e1_px_num'             => "Use of px sizing units for items other than images",
        'e1_title_num'          => "&lt;title&gt; missing or incorrectly worded (Should be &lt;title&gt;The Project Gutenberg eBook of Alice's Adventures in Wonderland, by Lewis Carroll&lt;title&gt; or &lt;title&gt;Alice's Adventures in Wonderland, by Lewis Carroll&mdash;A Project Gutenberg eBook&lt;title&gt;)",
        'e1_pre_num'            => "Use of &lt;pre&gt; tags instead of their CSS equivalents",
        'e1_body_num'           => "Failure to place &lt;html&gt;, &lt;body&gt;, &lt;head&gt;, &lt;/head&gt;&lt;/body&gt;, and &lt;/html&gt; tags each on their own line and correctly use them",
        'e1_tabl_num'           => "Use of tables for things that are not tables",
        'e1_css_num'            => "Used CSS other than CSS 2.1 or below (except for the dropcap \"transparent\" element)",
        'e1_xhtml_num'          => "Used HTML version other than XHTML 1.0 Strict or 1.1",
        'e1_chapter_num'        => "Failure to add &lt;div class=\"chapter\"&gt; at chapter breaks to enable proper page breaks for ereaders",
        'e1_xhtml_genhtml_num'  => "Minor HTML errors in code that do not generate an HTML validation alert such as misspelling a language code (Please explain in the Comments Field)",
    ));

    $reportcard .= "\n\n  Level 2 Errors:";
    $reportcard .= "\n\n    All Versions:";
    $reportcard .= report_error_counts(array(
        'e2_markup_num'     => "Markup not handled (e.g., blockquotes, poetry indentation, or widespread failure to mark italics)",
        'e2_poetry_num'     => "Poetry indentation does not match original",    
        'e2_foot_num'       => "Footnotes/footnote markers missing or incorrectly placed",  
        'e2_printers_num'   => "Printers' errors not addressed",
        'e2_missing_num'    => "Missing page(s) or substantial sections of missing text",
        'e2_rewrap_num'     => "Substantial rewrapping errors, e.g., poetry has been rewrapped or text version generally not rewrapped to required length (not exceeding 75 characters or falling below 55 characters) except where unavoidable, e.g., some tables though the aim should be 72 characters",
        'e2_hyphen_num'     => "Widespread/general occurrences of hyphenated/non-hyphenated, spelling and punctuation variants and other inconsistencies not addressed (may be addressed by note in the TN)",
        'e2_gen_num'        => "Other major errors that could seriously impact the readability of the book or that represent major inconsistencies between the text and the HTML versions (Please explain in the Comments Field)",
    ));
    $reportcard .= "\n\n    HTML Version Only:";
    $reportcard .= report_error_counts(array(
        'e2_tidy_num'       => "The W3C Markup Validation Service generates errors or warning messages (Please enter number of errors)",
        'e2_csscheck_num'   => "The W3C CSS Validation Service generates errors or warning messages other than for the dropcap \"transparent\" element (Please enter number of errors)",
        'e2_links_num'      => "Non-working links within HTML or to images. (Either broken or link to wrong place/file)",
        'e2_file_num'       => "File and folder names not in lowercase or contain spaces, images not in \"images\" folder, etc.",
        'e2_cover_num'      => "Cover image has not been included and/or has not been coded for e-reader use. (For example, the cover should be 600x800px or at least 500px wide and no more than 800px high and should be called cover.jpg. Also, if the cover is newly created, it must meet current DP guidelines.)",
        'e2_epub_num'       => "Project not presentable/useable when put through epubmaker",
        'e2_heading_num'    => "Heading elements used for things that are not headings and failure to use hierarchical headings for book, chapter and section headings (single h1, appropriate h2s and h3s etc.)",
    ));

    $reportcard .= "\n\n  Strongly Recommended (These don't count as errors but should be corrected):";
    $reportcard .= report_recommendations(array(
        's_multi'     => "Enclose entire multi-part headings within the related heading tag",
        's_empty'     => "Avoid using empty tags (with &amp;nbsp; entities) or &lt;br /&gt; elements for vertical spacing. e.g. &lt;p&gt;&lt;br /&gt;&lt;br /&gt;&lt;/p&gt; (or with nbsps) -- &lt;td&gt;&amp;nbsp;&lt;/td&gt; is still acceptable though",
        's_list'      => "List Tags should be used for lists (e.g., a normal index)",
        's_text'      => "Include all text as text, not just as images",
        's_code'      => "Keep your code line lengths reasonable",
        's_tables'    => "Tables should display left, right, and center justification and top and bottom align appropriately",
        's_th'        => "Tables contain &lt;th&gt; elements for headings",
        's_thumbs'    => "Remove thumbs.db file from the images folder",
        's_ereader'   => "E-reader version, although without major flaws, should also look as good as possible",
    ));
    $reportcard .= "\n\n  Mildly Recommended (These don't count as errors):";
    $reportcard .= report_recommendations(array(
        'm_semantic'  => "Distinguish between purely decorative italics/bold/gesperrt and semantic uses of them",
        'm_space'     => "Include space before the slash in self-closing tags (e.g. &lt;br /&gt;)",
        'm_unusedcss' => "Ensure that there are no unused elements in the CSS (other than the base HTML headings)",
    ));

    $reportcard .= report_comments(array(
        'general_comments' => "General comments",
        'reason_returned'  => "Did you have to return the project again because the PPer failed to make requested corrections on the second submission? (If so, please explain)",
    ));
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
}
else if ($action == SEND_OUT_REPORTCARD)
{
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
}
else
{
    assert(FALSE);
}

function report_error_counts($errors)
{
    $result = "";
    foreach ( $errors as $id => $label )
    {
        if ($_POST[$id]) $result .= "\n      {$_POST[$id]} $label";
    }
    if ($result == "") $result = "\n      None";
    return $result;
}

function report_recommendations($recommendations)
{
    $result = "";
    foreach ( $recommendations as $id => $label )
    {
        if (isset($_POST[$id])) $result .= "\n    $label";
    }
    if ($result == "") $result = "\n    None";
    return $result;
}

function report_comments($comments)
{
    $result = "";
    foreach ($comments as $id => $label)
    {
        if (!empty($_POST[$id]))
            $result .= "\n\n  $label:\n    {$_POST[$id]}";
    }
    return $result;
}

// vim: sw=4 ts=4 expandtab
