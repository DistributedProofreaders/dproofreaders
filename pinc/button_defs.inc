<?php
include_once($relPath.'site_vars.php');  // $charset for javascript_safe()
include_once($relPath.'misc.inc');  // attr_safe(), javascript_safe()

define('CHANGE_LAYOUT', 'A');
define('QUIT', 'B');
define('REPORT_BAD_PAGE', 'C');
define('RETURN_PAGE', 'D');
define('REVERT_TO_LAST_SAVE', 'E');
define('REVERT_TO_ORIGINAL', 'F');
define('SAVE_AS_DONE_AND_PROOF_NEXT', 'H');
define('SAVE_AS_DONE_AND_QUIT', 'I');
define('SAVE_AS_IN_PROGRESS', 'J');
define('SPELL_CHECK', 'L');

define('PROJECT_COMMENTS', 'M');
define('REVERT_TO_LAST_SAVE_DISABLED', 'O');
define('SHOW_ALL_TEXT', 'P');
define('PREVIEW', 'S');

function echo_button($button_id, $which_interface)
{
    global $charset;
    $CRLF = '\r\n';
    if ($which_interface == 's') {
        $label = 'value';
    } elseif ($which_interface == 'a') {
        $label = 'alt';
    } else {
        die("echo_button: bad which_interface arg: '$which_interface'");
    }

    switch ($button_id) {
        case CHANGE_LAYOUT:
            $user = User::load_current();
            if ($user->profile->i_layout == 1) {
                $horvert = _("Horizontal");
                $n = "5";
            } else {
                $horvert = _("Vertical");
                $n = "4";
            }
            $attrs = [
                'accesskey' => "6",
                'name' => "button4",
                $label => attr_safe(sprintf(_("Switch to %s"), $horvert)),
                'title' => attr_safe(sprintf(_("Save and Switch to %s Layout"), $horvert)),
                'src' => "gfx/bt$n.png",
            ];
            break;

        case QUIT:
            $attrs = [
                'accesskey' => "9",
                'name' => "button3",
                $label => attr_safe(_("Stop Proofreading")),
                'title' => attr_safe(_("Stop Proofreading")),
                'onclick' => "return(confirm('"
                    . javascript_safe(_("Are you sure you want to stop proofreading?"), $charset)
                    . "'));",
                'src' => "gfx/bt1.png",
            ];
            break;

        case REPORT_BAD_PAGE:
            $attrs = [
                'accesskey' => '4',
                'name' => 'button6',
                $label => attr_safe(_("Report Bad Page")),
                'title' => attr_safe(_("Report Bad Page")),
                'src' => 'gfx/bt14.png',
            ];
            break;

        case RETURN_PAGE:
            $attrs = [
                'accesskey' => "3",
                'name' => "button7",
                $label => attr_safe(_("Return Page to Round")),
                'title' => attr_safe(_("Return Page to Round")),
                'onclick' => "return(confirm('"
                     . javascript_safe(_("This will discard all changes you have made on this page."), $charset)
                     . $CRLF . javascript_safe(_("Are you sure you want to return this page to the current round?"), $charset)
                     . "'));",
                'src' => "gfx/bt15.png",
            ];
            break;

        case REVERT_TO_LAST_SAVE:
            $attrs = [
                'name' => "button9",
                $label => attr_safe(_("Revert to Last Save")),
                'title' => attr_safe(_("Revert to Last Save")),
                'onclick' => "return(confirm('"
                    . javascript_safe(_("Are you sure you want to revert to your last save?"), $charset)
                    . "'));",
                'src' => "gfx/bt7.png",
            ];
            break;

        case REVERT_TO_ORIGINAL:
            $attrs = [
                'name' => "button8",
                $label => attr_safe(_("Revert to Original Document")),
                'title' => attr_safe(_("Revert to Original Document")),
                'src' => "gfx/bt8.png",
            ];
            break;

        case SAVE_AS_DONE_AND_PROOF_NEXT:
            $attrs = [
                'accesskey' => "8",
                'name' => "button2",
                $label => attr_safe(_("Save as 'Done' & Proofread Next Page")),
                'title' => attr_safe(_("Save as 'Done' & Proofread Next Page")),
                'onClick' => "return submitJustOnce(this);",
                'src' => "gfx/bt2.png",
                'class' => "check_button",
            ];
            break;

        case SAVE_AS_DONE_AND_QUIT:
            $attrs = [
                'accesskey' => "5",
                'name' => "button5",
                $label => attr_safe(_("Save as 'Done'")),
                'title' => attr_safe(_("Save as 'Done'")),
                'onClick' => "return submitJustOnce(this);",
                'src' => "gfx/bt13.png",
                'class' => "check_button",
            ];
            break;

        case SAVE_AS_IN_PROGRESS:
            $attrs = [
                'accesskey' => "7",
                'name' => "button1",
                $label => attr_safe(_("Save as 'In Progress'")),
                'title' => attr_safe(_("Save as 'In Progress'")),
                'onClick' => "return submitJustOnce(this);",
                'src' => "gfx/bt3.png",
                'class' => "check_button",
            ];
            break;

        case SPELL_CHECK:
            $attrs = [
                'name' => "button10",
                $label => attr_safe(_("WordCheck")),
                'title' => attr_safe(_("Run WordCheck")),
                //                'onClick' => "return submitJustOnce(this);",
                'src' => "gfx/bt16.png",
            ];
            break;
        case SHOW_ALL_TEXT:
            $attrs = [
                'name' => "button13",
                $label => attr_safe(_("Show All Text")),
                'title' => attr_safe(_("Show All Text")),
                'onclick' => "top.showNW(); return false;",
                'src' => "gfx/bt9.png",
            ];
            break;
        case PREVIEW:
            $attrs = [
                'name' => "button14",
                $label => attr_safe(_("Preview")),
                'title' => attr_safe(_("Format preview")),
                'onclick' => "event.preventDefault(); previewControl.show();",
                'src' => "gfx/bt20.png?16092302",
            ];
            break;
    }
    if ($which_interface == 's') {
        unset($attrs['src']);
        $attrs['type'] = 'submit';
        $attrs['id'] = $attrs['name'];
    } elseif ($which_interface == 'a') {
        $attrs['type'] = 'image';
        $attrs['align'] = 'top';
        $attrs['size'] = '26';
        $attrs['id'] = $attrs['name'];
    }

    echo "<INPUT\n";
    foreach ($attrs as $at_name => $at_value) {
        echo "$at_name=\"$at_value\"\n";
    }
    echo ">";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_img($id)
{
    switch ($id) {
        case PROJECT_COMMENTS:
            $attrs = [
                'src' => "gfx/bt12.png",
                'alt' => attr_safe(_("Project Comments")),
                'title' => attr_safe(_("Project Comments")),
            ];
            break;

        case REVERT_TO_LAST_SAVE_DISABLED:
            $attrs = [
                'src' => "gfx/bt7_n.png",
                'alt' => attr_safe(_("Revert to Last Save Disabled")),
                'title' => attr_safe(_("Revert to Last Save Disabled")),
            ];
            break;

        case SHOW_ALL_TEXT:
            $attrs = [
                'src' => "gfx/bt9.png",
                'alt' => attr_safe(_("Show All Text")),
                'title' => attr_safe(_("Show All Text")),
            ];
            break;
        case PREVIEW:
            $attrs = [
                'src' => "gfx/bt20.png?16092302",
                'alt' => attr_safe(_("Format preview")),
                'title' => attr_safe(_("Format preview")),
            ];
            break;
    }
    $attrs['align'] = 'top';
    $attrs['width'] = "26";
    $attrs['height'] = "26";
    $attrs['border'] = "0";

    echo "<img\n";
    foreach ($attrs as $at_name => $at_value) {
        echo "$at_name=\"$at_value\"\n";
    }
    echo ">";
}
