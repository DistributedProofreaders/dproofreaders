<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'slim_header.inc');
include_once('PPage.inc');

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

// BUTTON CODE

switch( $tbutton )
{
    case B_TEMPSAVE:
        $ppage->saveAsInProgress($text_data,$pguser);
        include('proof_frame.inc');
        break;

    case B_SWITCH_LAYOUT:
        $ppage->saveAsInProgress($text_data,$pguser);
        switch_layout();
        include('proof_frame.inc');
        break;

    case B_REVERT_TO_ORIGINAL:
        $ppage->saveAsInProgress($text_data,$pguser);
        $ppage->revertToOriginal();
        include('proof_frame.inc');
        break;

    case B_REVERT_TO_LAST_TEMPSAVE:
        $ppage->revertToSaved();
        include('proof_frame.inc');
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
        include('spellcheck.inc');
        break;

    case 101:
        // Return from spellchecker via "Submit Corrections" button.
        include_once('spellcheck_text.inc');
        $correct_text = spellcheck_apply_corrections();
        $ppage->saveAsInProgress(addslashes($correct_text),$pguser);
        include('proof_frame.inc');
        break;

    case 102:
        // Return from spellchecker via "Quit Spell Check" button.
        include_once('spellcheck_text.inc');
        $correct_text = spellcheck_quit();
        $ppage->saveAsInProgress(addslashes($correct_text),$pguser);
        include('proof_frame.inc');
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
