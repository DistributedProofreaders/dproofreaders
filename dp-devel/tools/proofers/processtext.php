<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'slim_header.inc');
include_once('PPage.inc');
include_once('proof_frame.inc');
include_once('text_frame_std.inc');

/*
$_POST:
    $projectid, $proj_state,
    $imagefile, $page_state, $text_data,
    $button1, $button2, $button3, $button4, ...
    $button1_x, $button2_x, $button3_x, $button4_x, ...
*/

$text_data = isset($text_data) ? $text_data : '';

define('B_TEMPSAVE',                1);
define('B_SAVE_AND_DO_ANOTHER',     2);
define('B_QUIT',                    3);
define('B_SWITCH_LAYOUT',           4);
define('B_SAVE_AND_QUIT',           5);
define('B_REPORT_BAD_PAGE',         6);
define('B_RETURN_PAGE_TO_ROUND',    7);
define('B_REVERT_TO_ORIGINAL',      8);
define('B_REVERT_TO_LAST_TEMPSAVE', 9);
define('B_RUN_SPELL_CHECK',         10);
define('B_RUN_COMMON_ERRORS_CHECK', 11);


// set tbutton
  if (isset($button1) || isset($button1_x)) {$tbutton=B_TEMPSAVE;}
  if (isset($button2) || isset($button2_x)) {$tbutton=B_SAVE_AND_DO_ANOTHER;}
  if (isset($button3) || isset($button3_x)) {$tbutton=B_QUIT;}
  if (isset($button4) || isset($button4_x)) {$tbutton=B_SWITCH_LAYOUT;}
  if (isset($button5) || isset($button5_x)) {$tbutton=B_SAVE_AND_QUIT;}
  if (isset($button6) || isset($button6_x)) {$tbutton=B_REPORT_BAD_PAGE;}
  if (isset($button7) || isset($button7_x)) {$tbutton=B_RETURN_PAGE_TO_ROUND;}
  if (isset($button8) || isset($button8_x)) {$tbutton=B_REVERT_TO_ORIGINAL;}
  if (isset($button9) || isset($button9_x)) {$tbutton=B_REVERT_TO_LAST_TEMPSAVE;}
  if (isset($button10) || isset($button10_x)) {$tbutton=B_RUN_SPELL_CHECK;}
  if (isset($button11) || isset($button11_x)) {$tbutton=B_RUN_COMMON_ERRORS_CHECK;}

  if (isset($spcorrect)) {$tbutton=101;} // Make Spelling Corrections
  if (isset($spexit)) {$tbutton=102;} // Exit Spelling Corrections
  if (isset($rerunauxlanguage)) {$tbutton=103;} // Spellcheck against another language
  if (isset($errcorrect)) {$tbutton=111;} // Make Spelling Corrections
  if (isset($errexit)) {$tbutton=112;} // Exit Spelling Corrections

// set prefs
  if ($userP['i_type']==1)
  {
    $isChg=0;
      if ($userP['i_layout']==1)
      {
        if (isset($fntFace) && $userP['v_fntf']!=$fntFace) {$userP['v_fntf']=$fntFace;$isChg=1;}
        if (isset($fntSize) && $userP['v_fnts']!=$fntSize) {$userP['v_fnts']=$fntSize;$isChg=1;}
        if (isset($zmSize) && $userP['v_zoom']!=$zmSize) {$userP['v_zoom']=$zmSize;$isChg=1;}
      }
      else
      {
        if (isset($fntFace) && $userP['h_fntf']!=$fntFace) {$userP['h_fntf']=$fntFace;$isChg=1;}
        if (isset($fntSize) && $userP['h_fnts']!=$fntSize) {$userP['h_fnts']=$fntSize;$isChg=1;}
        if (isset($zmSize) && $userP['h_zoom']!=$zmSize) {$userP['h_zoom']=$zmSize;$isChg=1;}
      }
    $userP['prefschanged']=$isChg;
    dpsession_set_preferences_temp( $userP );
  }

// If the user simply wants to leave the proofing interface,
// then it doesn't matter what state the project or page is in.
// So handle that case before we do any continuity/permission checks.
if ($tbutton == B_QUIT)
{
    leave_proofing_interface( _("Stop Proofreading"), '' );
    exit;
}


$ppage = get_requested_PPage( $_POST );

// $_SESSION key name for storing WordCheck corrections
$projectid=$_POST["projectid"];
$page=$ppage->lpage->imagefile;
$wcTempCorrections = "WC_temp_corrections-$projectid-$page";

// BUTTON CODE

switch( $tbutton )
{
    case B_TEMPSAVE:
        $ppage->saveAsInProgress($text_data,$pguser);
        echo_proof_frame($ppage);
        break;

    case B_SWITCH_LAYOUT:
        $ppage->saveAsInProgress($text_data,$pguser);
        switch_layout();
        echo_proof_frame($ppage);
        break;

    case B_REVERT_TO_ORIGINAL:
        $ppage->saveAsInProgress($text_data,$pguser);
        $ppage->revertToOriginal();
        echo_proof_frame($ppage);
        break;

    case B_REVERT_TO_LAST_TEMPSAVE:
        $ppage->revertToSaved();
        echo_proof_frame($ppage);
        break;

    case B_SAVE_AND_DO_ANOTHER:
        $ppage->saveAsDone($text_data,$pguser);
        $url = $ppage->url_for_do_another_page();
        metarefresh(1,$url,_("Save as 'Done' & Proof Next"),_("Page saved."));
        break;

    case B_SAVE_AND_QUIT:
        $ppage->saveAsDone($text_data,$pguser);
        leave_proofing_interface(
            _("Save as 'Done'"), _("Page Saved.") );
        break;

    case B_RETURN_PAGE_TO_ROUND:
        $ppage->returnToRound($pguser);
        leave_proofing_interface(
            _("Return to Round"), _("Page Returned to Round.") );
        break;

    case B_REPORT_BAD_PAGE:
        include('report_bad_page.php');
        break;

    case B_RUN_COMMON_ERRORS_CHECK:
        //  include('errcheck.inc');
        break;

    case B_RUN_SPELL_CHECK:
        if ( ! is_dir($aspell_temp_dir) ) { mkdir($aspell_temp_dir); }
        // save what we have so far, just in case the spellchecker barfs
        $ppage->saveAsInProgress($text_data,$pguser);
        $aux_language = '';
        $accepted_words=array();
        $text_data=stripslashes($_POST["text_data"]);

        // to retain corrections across multiple language checks, we save the
        // corrections in a page-specific session variable
        // here we unset it before beginning, just in case
        unset($_SESSION[$wcTempCorrections]);

        include('spellcheck.inc');
        break;

    case 101:
        // Return from spellchecker via "Submit Corrections" button.
        include_once('spellcheck_text.inc');
        list($correct_text,$corrections) = spellcheck_apply_corrections();
        $accepted_words = explode(' ',stripslashes($_POST["accepted_words"]));
        $_SESSION["is_header_visible"] = $_POST["is_header_visible"];

        // the user is submitting corrections, so pull any temporary corrections
        // they made long the way for saving in WordCheck (they've already been
        // applied to the text itself)
        if(isset($_SESSION[$wcTempCorrections]) && is_array($_SESSION[$wcTempCorrections]) && count($_SESSION[$wcTempCorrections]))
            $corrections = array_merge($_SESSION[$wcTempCorrections], $corrections);
        unset($_SESSION[$wcTempCorrections]);

        // for the record, PPage (or at least LPage) should provide
        // functions for returning the round ID and the page number
        // without the mess below
        save_wordcheck_event(
            $_POST["projectid"],$ppage->lpage->round->id,$page,$pguser,$accepted_words,$corrections);

        $ppage->saveAsInProgress(addslashes($correct_text),$pguser);
        leave_spellcheck_mode($ppage);
        break;

    case 102:
        // Return from spellchecker via "Quit Spell Check" button.
        include_once('spellcheck_text.inc');
        $correct_text = spellcheck_quit();
        $accepted_words = explode(' ',stripslashes($_POST["accepted_words"]));
        $_SESSION["is_header_visible"] = $_POST["is_header_visible"];

        // the user wants to quit, so clear out the temporary variable
        // storing the corrections
        unset($_SESSION[$wcTempCorrections]);

        save_wordcheck_event(
            $_POST["projectid"],$ppage->lpage->round->id,$page,$pguser,$accepted_words,array());

        $ppage->saveAsInProgress(addslashes($correct_text),$pguser);
        leave_spellcheck_mode($ppage);
        break;

    case 103:
        // User wants to run the page through spellcheck for an another language
        // Apply current corrections to text (but don't save the progress)
        // and rerun through the spellcheck
        include_once('spellcheck_text.inc');
        $aux_language = $_POST["aux_language"];
        $accepted_words = explode(' ',stripslashes($_POST["accepted_words"]));
        $_SESSION["is_header_visible"] = $_POST["is_header_visible"];
        list($text_data,$corrections) = spellcheck_apply_corrections();

        // save the already-made corrections in a temporary session variable
        // so we can later save or trash them based on their final decision
        if(isset($_SESSION[$wcTempCorrections]) && is_array($_SESSION[$wcTempCorrections]) && count($_SESSION[$wcTempCorrections]))
            $corrections = array_merge($_SESSION[$wcTempCorrections], $corrections);
        $_SESSION[$wcTempCorrections] = $corrections;

        include('spellcheck.inc');
        break;


    default:
        die( "unexpected tbutton value: '$tbutton'" );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function switch_layout()
{
    global $userP;
    $userP['i_layout'] = $userP['i_layout']==1 ? 0 : 1;
    $userP['prefschanged'] = 1;
    dpsession_set_preferences_temp( $userP );
}

function leave_spellcheck_mode( $ppage )
{
    global $userP;

    // The user has requested a return from spellcheck mode.
    // The response that we send will replace the frame/document
    // containing the spellcheck form.

    if ( $userP['i_type'] == 0 )
    {
        // standard interface:
        // The spellcheck document (containing text only) is in 'textframe'.
        // So the response we generate will go into 'textframe'.
        // So generate just the (normal-mode) text frame of the std interface.
        echo_text_frame_std($ppage);
    }
    else
    {
        // enhanced interface:
        // The spellcheck document (containing image+text) is in 'proofframe'.
        // So the response we generate will go into 'proofframe'.
        // So generate the (normal-mode) image-and-text doc of the enh interface.
        echo_proof_frame($ppage);
    }
}

function leave_proofing_interface( $title, $body )
{
    global $code_url, $projectid, $proj_state;

    slim_header( $title );

    $url = "$code_url/project.php?id=$projectid&amp;expected_state=$proj_state";

//    $text = _("Please click here to return to Project Page.");
//    echo "<a href='$url' target='_top'>$text</a>";

    $text =  _("You will be returned to the <a href='%s' target='_top'>Project Page</a> in one second.");
    echo sprintf($text, $url);
    echo "<script language='JavaScript'><!--\n";
    echo "setTimeout(\"top.location.href='$url';\", 1000);\n";
    echo "// --></script>\n";

    echo "</body>";
    echo "</html>";
}

// vim: sw=4 ts=4 expandtab
?>
