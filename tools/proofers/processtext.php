<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'abort.inc');
include_once($relPath.'Project.inc');
include_once('PPage.inc');
include_once('proof_frame.inc');
include_once('text_frame_std.inc');
include_once('spellcheck_text.inc');

require_login();

/*
$_POST:
    $projectid, $proj_state,
    $imagefile, $page_state, $text_data,
    $button1, $button2, $button3, $button4, ...
    $button1_x, $button2_x, $button3_x, $button4_x, ...
*/

$projectid = get_projectID_param($_POST, 'projectid');
$proj_state = get_enumerated_param($_POST, 'proj_state', null, ProjectStates::get_states());
$imagefile = get_page_image_param($_POST, 'imagefile');
$text_data = array_get($_POST, 'text_data', '');

define('B_TEMPSAVE', 1);
define('B_SAVE_AND_DO_ANOTHER', 2);
define('B_QUIT', 3);
define('B_SWITCH_LAYOUT', 4);
define('B_SAVE_AND_QUIT', 5);
define('B_REPORT_BAD_PAGE', 6);
define('B_RETURN_PAGE_TO_ROUND', 7);
define('B_REVERT_TO_ORIGINAL', 8);
define('B_REVERT_TO_LAST_TEMPSAVE', 9);
define('B_RUN_SPELL_CHECK', 10);


// set tbutton
$tbutton = null; // default value, will result in error if not overridden below
if (isset($_POST['button1']) || isset($_POST['button1_x'])) {
    $tbutton = B_TEMPSAVE;
}
if (isset($_POST['button2']) || isset($_POST['button2_x'])) {
    $tbutton = B_SAVE_AND_DO_ANOTHER;
}
if (isset($_POST['button3']) || isset($_POST['button3_x'])) {
    $tbutton = B_QUIT;
}
if (isset($_POST['button4']) || isset($_POST['button4_x'])) {
    $tbutton = B_SWITCH_LAYOUT;
}
if (isset($_POST['button5']) || isset($_POST['button5_x'])) {
    $tbutton = B_SAVE_AND_QUIT;
}
if (isset($_POST['button6']) || isset($_POST['button6_x'])) {
    $tbutton = B_REPORT_BAD_PAGE;
}
if (isset($_POST['button7']) || isset($_POST['button7_x'])) {
    $tbutton = B_RETURN_PAGE_TO_ROUND;
}
if (isset($_POST['button8']) || isset($_POST['button8_x'])) {
    $tbutton = B_REVERT_TO_ORIGINAL;
}
if (isset($_POST['button9']) || isset($_POST['button9_x'])) {
    $tbutton = B_REVERT_TO_LAST_TEMPSAVE;
}
if (isset($_POST['button10']) || isset($_POST['button10_x'])) {
    $tbutton = B_RUN_SPELL_CHECK;
}

// Make Spelling Corrections
if (isset($_POST['spcorrect'])) {
    $tbutton = 101;
}
// Exit Spelling Corrections
if (isset($_POST['spexit'])) {
    $tbutton = 102;
}
// Save and do another from the Spellcheck page
if (isset($_POST['spsaveandnext'])) {
    $tbutton = 103;
}
// Spellcheck against another language
if (isset($_POST['rerunauxlanguage']) || isset($_POST["remove_language"])) {
    $tbutton = 104;
}

// set prefs
$user = User::load_current();
if ($user->profile->i_type == 1) {
    if (isset($_POST['fntFace'])) {
        $fntFace = get_integer_param($_POST, 'fntFace', 0, 0, null);
    }
    if (isset($_POST['fntSize'])) {
        $fntSize = get_integer_param($_POST, 'fntSize', 0, 0, null);
    }

    if ($user->profile->i_layout == 1) {
        if (isset($fntFace)) {
            $user->profile->v_fntf = $fntFace;
        }
        if (isset($fntSize)) {
            $user->profile->v_fnts = $fntSize;
        }
    } else {
        if (isset($fntFace)) {
            $user->profile->h_fntf = $fntFace;
        }
        if (isset($fntSize)) {
            $user->profile->h_fnts = $fntSize;
        }
    }

    if (isset($fntFace) || isset($fntSize)) {
        $user->profile->save();
    }
}

// If the user simply wants to leave the proofing interface,
// then it doesn't matter what state the project or page is in.
// So handle that case before we do any continuity/permission checks.
if ($tbutton == B_QUIT) {
    leave_proofing_interface(_("Stop Proofreading"));
    exit;
}

try {
    $ppage = get_requested_PPage($_POST);
} catch (ProjectException | ProjectPageException $exception) {
    abort($exception->getMessage());
}

$page = $ppage->lpage->imagefile;

// BUTTON CODE

try {
    switch ($tbutton) {
        case B_TEMPSAVE:
            $ppage->saveAsInProgress($text_data, $pguser);
            echo_proof_frame($ppage);
            break;

        case B_SWITCH_LAYOUT:
            $ppage->saveAsInProgress($text_data, $pguser);
            switch_layout();
            echo_proof_frame($ppage);
            break;

        case B_REVERT_TO_ORIGINAL:
            $ppage->saveAsInProgress($text_data, $pguser);
            $ppage->revertToOriginal();
            echo_proof_frame($ppage);
            break;

        case B_REVERT_TO_LAST_TEMPSAVE:
            $ppage->revertToSaved();
            echo_proof_frame($ppage);
            break;

        case B_SAVE_AND_DO_ANOTHER:
            $ppage->attempt_to_save_as_done($text_data);
            $url = $ppage->url_for_do_another_page();
            metarefresh(1, $url, _("Save as 'Done' & Proofread Next Page"), _("Page Saved."));
            break;

        case B_SAVE_AND_QUIT:
            $ppage->attempt_to_save_as_done($text_data);
            leave_proofing_interface(_("Save as 'Done'"));
            break;

        case B_RETURN_PAGE_TO_ROUND:
            $ppage->returnToRound($pguser);
            leave_proofing_interface(
                _("Return to Round")
            );
            break;

        case B_REPORT_BAD_PAGE:
            include('report_bad_page.php');
            break;

        case B_RUN_SPELL_CHECK:
            if (! is_dir($aspell_temp_dir)) {
                mkdir($aspell_temp_dir);
            }
            // save what we have so far, just in case the spellchecker barfs
            $ppage->saveAsInProgress($text_data, $pguser);
            $languages = get_project_languages($projectid);
            $accepted_words = [];
            $text_data = $_POST["text_data"];

            $is_changed = 0;

            slim_header(_("WordCheck"), get_wordcheck_page_header_args($user, $ppage));
            output_wordcheck_interface($user, $ppage, $text_data, $is_changed, $accepted_words, $languages);
            break;

        case 101:
            // Return from spellchecker via "Submit Corrections" button.
            [$correct_text, $corrections] = spellcheck_apply_corrections();
            $accepted_words = explode(' ', $_POST["accepted_words"]);
            $_SESSION["is_header_visible"] = get_integer_param($_POST, 'is_header_visible', 0, 0, 1);

            // for the record, PPage (or at least LPage) should provide
            // functions for returning the round ID and the page number
            // without the mess below
            save_wordcheck_event(
                $projectid,
                $ppage->lpage->round->id,
                $page,
                $pguser,
                $accepted_words
            );

            $ppage->saveAsInProgress($correct_text, $pguser);
            leave_spellcheck_mode($ppage);
            break;

        case 102:
            // Return from spellchecker via "Quit Spell Check" button.
            $correct_text = spellcheck_quit();
            $accepted_words = explode(' ', $_POST["accepted_words"]);
            $_SESSION["is_header_visible"] = get_integer_param($_POST, 'is_header_visible', 0, 0, 1);

            save_wordcheck_event(
                $projectid,
                $ppage->lpage->round->id,
                $page,
                $pguser,
                $accepted_words
            );

            $ppage->saveAsInProgress($correct_text, $pguser);
            leave_spellcheck_mode($ppage);
            break;

        case 103:
            // Do Save as 'Done' & Proofread Next from the spellchecker interface.
            // This works by
            // 1. Quitting the current wordcheck
            // 2. Saving the current page as done
            // 3. Redirecting to the next available page
            $correct_text = spellcheck_quit();
            $accepted_words = explode(' ', $_POST["accepted_words"]);
            $_SESSION["is_header_visible"] = get_integer_param($_POST, 'is_header_visible', 0, 0, 1);

            save_wordcheck_event(
                $projectid,
                $ppage->lpage->round->id,
                $page,
                $pguser,
                $accepted_words
            );

            // 2. Save the current page as done
            $ppage->attempt_to_save_as_done($correct_text);

            // Redirect to the next available page
            $url = $ppage->url_for_do_another_page();
            // Note: Using Wordcheck in the standard interface changes the default
            // target for links from 'proofframe' to 'textframe' which is why we
            // have to do these gymnastics instead of using metarefresh().
            $title = _("Save as 'Done' & Proofread Next Page");
            $body = _("Page Saved.");
            slim_header($title);
            echo "<script><!--\n";
            echo "setTimeout(\"top.proofframe.location.href='$url';\", 1000);\n";
            echo "// --></script>\n";
            echo $body;
            break;

        case 104:
            // User wants to run the page through spellcheck for another language
            // Apply current corrections to text (but don't save the progress)
            // and rerun through the spellcheck
            $languages = $_POST["languages"] ?? [];
            if ($_POST["aux_language"] ?? null) {
                $languages[] = $_POST["aux_language"];
            }
            if (isset($_POST["remove_language"])) {
                $languages = array_diff($languages, array_keys($_POST["remove_language"] ?? []));
            }
            $accepted_words = explode(' ', $_POST["accepted_words"]);
            $_SESSION["is_header_visible"] = get_integer_param($_POST, 'is_header_visible', 0, 0, 1);
            [$text_data, $corrections] = spellcheck_apply_corrections();

            $is_changed = get_integer_param($_POST, 'is_changed', 0, 0, 1);

            slim_header(_("WordCheck"), get_wordcheck_page_header_args($user, $ppage));
            output_wordcheck_interface($user, $ppage, $text_data, $is_changed, $accepted_words, $languages);
            break;


        default:
            die("unexpected tbutton value: '$tbutton'");
    }
} catch (ProjectPageException | ProjectPageStateException | PageNotOwnedException $exception) {
    abort($exception->getMessage());
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function switch_layout(): void
{
    $user = User::load_current();
    $user->profile->i_layout = $user->profile->i_layout == 1 ? 0 : 1;
    $user->profile->save();
}

function leave_spellcheck_mode(PPage $ppage): void
{
    $user = User::load_current();

    // The user has requested a return from spellcheck mode.
    // The response that we send will replace the frame/document
    // containing the spellcheck form.

    if ($user->profile->i_type == 0) {
        // standard interface:
        // The spellcheck document (containing text only) is in 'textframe'.
        // So the response we generate will go into 'textframe'.
        // So generate just the (normal-mode) text frame of the std interface.
        echo_text_frame_std($ppage);
    } else {
        // enhanced interface:
        // The spellcheck document (containing image+text) is in 'proofframe'.
        // So the response we generate will go into 'proofframe'.
        // So generate the (normal-mode) image-and-text doc of the enh interface.
        echo_proof_frame($ppage);
    }
}

function leave_proofing_interface(string $title): void
{
    global $code_url, $projectid, $proj_state;

    slim_header($title);

    // HTML requires HTML-safe version, whereas script doesn't want escaped ampersands
    $query_param = "expected_state=$proj_state";
    $safe_url = project_page_link_url($projectid, [$query_param]);
    $raw_url = "$code_url/project.php?id=$projectid&$query_param";
    $text = _("You will be returned to the <a href='%s' target='_top'>project page</a> in one second.");
    echo sprintf($text, $safe_url);
    echo "<script><!--\n";
    echo "setTimeout(\"top.location.href='$raw_url';\", 1000);\n";
    echo "// --></script>\n";
}
