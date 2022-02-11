<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'abort.inc');
include_once('PPage.inc');
include_once('proof_frame.inc');

require_login();

/*
$_POST:
    $projectid, $proj_state,
    $imagefile, $page_state, $text_data,
    $button1, $button2, $button3, $button4, ...
    $button1_x, $button2_x, $button3_x, $button4_x, ...
*/

$projectid = get_projectID_param($_POST, 'projectid');
$proj_state = $_POST['proj_state'];
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
if (isset($_POST['rerunauxlanguage'])) {
    $tbutton = 104;
}

// set prefs
$user = User::load_current();
if ($user->profile->i_type == 1) {
    if (isset($_POST['fntFace'])) {
        $fntFace = $_POST['fntFace'];
    }
    if (isset($_POST['fntSize'])) {
        $fntSize = $_POST['fntSize'];
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

// $_SESSION key name for storing WordCheck corrections
$page = $ppage->lpage->imagefile;
$wcTempCorrections = "WC_temp_corrections-$projectid-$page";

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
                _("Return to Round"));
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
            $aux_language = '';
            $accepted_words = [];
            $text_data = $_POST["text_data"];

            // to retain corrections across multiple language checks, we save the
            // corrections in a page-specific session variable
            // here we unset it before beginning, just in case
            unset($_SESSION[$wcTempCorrections]);

            $is_changed = 0;
            include('spellcheck.inc');
            break;

        case 101:
            // Return from spellchecker via "Submit Corrections" button.
            include_once('spellcheck_text.inc');
            [$correct_text, $corrections] = spellcheck_apply_corrections();
            $accepted_words = explode(' ', $_POST["accepted_words"]);
            $_SESSION["is_header_visible"] = $_POST["is_header_visible"];

            // the user is submitting corrections, so pull any temporary corrections
            // they made long the way for saving in WordCheck (they've already been
            // applied to the text itself)
            if (isset($_SESSION[$wcTempCorrections]) && is_array($_SESSION[$wcTempCorrections]) && count($_SESSION[$wcTempCorrections])) {
                $corrections = array_merge($_SESSION[$wcTempCorrections], $corrections);
            }
            unset($_SESSION[$wcTempCorrections]);

            // for the record, PPage (or at least LPage) should provide
            // functions for returning the round ID and the page number
            // without the mess below
            save_wordcheck_event(
                $_POST["projectid"], $ppage->lpage->round->id, $page, $pguser, $accepted_words, $corrections);

            $ppage->saveAsInProgress($correct_text, $pguser);
            echo_proof_frame($ppage);
            break;

        case 102:
            // Return from spellchecker via "Quit Spell Check" button.
            include_once('spellcheck_text.inc');
            $correct_text = spellcheck_quit();
            $accepted_words = explode(' ', $_POST["accepted_words"]);
            $_SESSION["is_header_visible"] = $_POST["is_header_visible"];

            // the user wants to quit, so clear out the temporary variable
            // storing the corrections
            unset($_SESSION[$wcTempCorrections]);

            save_wordcheck_event(
                $_POST["projectid"], $ppage->lpage->round->id, $page, $pguser, $accepted_words, []);

            $ppage->saveAsInProgress($correct_text, $pguser);
            echo_proof_frame($ppage);
            break;

        case 103:
            // Do Save as 'Done' & Proofread Next from the spellchecker interface.
            // This works by
            // 1. Quitting the current wordcheck
            // 2. Saving the current page as done
            // 3. Redirecting to the next available page
            include_once('spellcheck_text.inc');
            $correct_text = spellcheck_quit();
            $accepted_words = explode(' ', $_POST["accepted_words"]);
            $_SESSION["is_header_visible"] = $_POST["is_header_visible"];

            // 1. Quit the wordcheck interface:
            // Discard the session state holding current wordcheck corrections
            // since we don't want to submit them and subsequent page loads will
            // not need the state.
            //
            // NB: Within wordcheck, the markPageChanged() javascript function
            // disables the Save as 'Done' & Proofread Next button if the user makes
            // any corrections, so we should only get here if the user has no
            // corrections to submit, so all we're discarding is wordcheck's
            // unmodified input fields.
            unset($_SESSION[$wcTempCorrections]);

            save_wordcheck_event(
                $_POST["projectid"], $ppage->lpage->round->id, $page, $pguser, $accepted_words, []);

            // 2. Save the current page as done
            $ppage->attempt_to_save_as_done($correct_text);

            // Redirect to the next available page
            $url = $ppage->url_for_do_another_page();
            metarefresh(1, $url, _("Save as 'Done' & Proofread Next Page"), _("Page Saved."));
            break;

        case 104:
            // User wants to run the page through spellcheck for an another language
            // Apply current corrections to text (but don't save the progress)
            // and rerun through the spellcheck
            include_once('spellcheck_text.inc');
            $aux_language = $_POST["aux_language"];
            $accepted_words = explode(' ', $_POST["accepted_words"]);
            $_SESSION["is_header_visible"] = $_POST["is_header_visible"];
            [$text_data, $corrections] = spellcheck_apply_corrections();

            // save the already-made corrections in a temporary session variable
            // so we can later save or trash them based on their final decision
            if (isset($_SESSION[$wcTempCorrections]) && is_array($_SESSION[$wcTempCorrections]) && count($_SESSION[$wcTempCorrections])) {
                $corrections = array_merge($_SESSION[$wcTempCorrections], $corrections);
            }
            $_SESSION[$wcTempCorrections] = $corrections;

            $is_changed = $_POST['is_changed'];
            include('spellcheck.inc');
            break;


        default:
            die("unexpected tbutton value: '$tbutton'");
    }
} catch (ProjectPageException $exception) {
    abort($exception->getMessage());
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function switch_layout()
{
    $user = User::load_current();
    $user->profile->i_layout = $user->profile->i_layout == 1 ? 0 : 1;
    $user->profile->save();
}

function leave_proofing_interface($title)
{
    global $code_url, $projectid, $proj_state;

    slim_header($title);

    $url = "$code_url/project.php?id=$projectid&expected_state=$proj_state";

    $text = _("You will be returned to the <a href='%s' target='_top'>Project Page</a> in one second.");
    echo sprintf($text, $url);
    echo "<script><!--\n";
    echo "setTimeout(\"top.location.href='$url';\", 1000);\n";
    echo "// --></script>\n";
}
