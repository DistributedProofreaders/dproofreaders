<?php
include_once($relPath.'site_vars.php');  // $charset for javascript_safe()
include_once($relPath.'misc.inc');  // attr_safe(), javascript_safe()

define('CHANGE_LAYOUT',               'A');
define('QUIT',                        'B');
define('REPORT_BAD_PAGE',             'C');
define('RETURN_PAGE',                 'D');
define('REVERT_TO_LAST_SAVE',         'E');
define('REVERT_TO_ORIGINAL',          'F');
define('SAVE_AS_DONE_AND_PROOF_NEXT', 'H');
define('SAVE_AS_DONE_AND_QUIT',       'I');
define('SAVE_AS_IN_PROGRESS',         'J');
define('SET_IMAGE_ZOOM',              'K');
define('SET_IMAGE_ACTUAL',            'Q');
define('SPELL_CHECK',                 'L');

define('PROJECT_COMMENTS',            'M');
define('REFRESH_IMAGE',               'N');
define('REVERT_TO_LAST_SAVE_DISABLED','O');
define('SHOW_ALL_TEXT',               'P');
define('CONVERT_UTF8',                'R');
define('PREVIEW',                     'S');
define('P_CHECK',                     'T');

function echo_button( $button_id, $which_interface )
{
    global $userP;
    global $charset;
    $CRLF = '\r\n';
    if ($which_interface == 's')
    {
        $label = 'value';
    }
    else if ($which_interface == 'a')
    {
        $label = 'alt';
    }
    else
    {
        die("echo_button: bad which_interface arg: '$which_interface'");
    }

    switch ($button_id)
    {
        case CHANGE_LAYOUT:
            if ( $userP['i_layout']==1)
            {
                $horvert = _("Horizontal");
                $n = "5";
            }
            else
            {
                $horvert = _("Vertical");
                $n = "4";
            }
            $attrs = array(
                'accesskey' => "6",
                'name'      => "button4",
                $label      => attr_safe(sprintf(_("Switch to %s"), $horvert)),
                'title'     => attr_safe(sprintf(_("Save and Switch to %s Layout"), $horvert)),
                'onclick'   => "return(confirm('" 
                    . javascript_safe(_("Are you sure you want to change layout & save the current document?"), $charset)
                    . "'));",
                'src'       => "gfx/bt$n.png",
            );
            break;

        case QUIT:
            $attrs = array(
                'accesskey' => "9",
                'name'      => "button3",
                $label      => attr_safe(_("Stop Proofreading")),
                'title'     => attr_safe(_("Stop Proofreading")),
                'onclick'   => "return(confirm('"
                    . javascript_safe(_("Are you sure you want to stop proofreading?"), $charset)
                    . "'));",
                'src'       => "gfx/bt1.png",
            );
            break;

        case REPORT_BAD_PAGE:
            $attrs = array(
                'accesskey' => '4',
                'name'      => 'button6',
                $label      => attr_safe(_("Report Bad Page")),
                'title'     => attr_safe(_("Report Bad Page")),
                'src'       => 'gfx/bt14.png',
            );
            break;

        case RETURN_PAGE:
            $attrs = array(
                'accesskey' => "3",
                'name'      => "button7",
                $label      => attr_safe(_("Return Page to Round")),
                'title'     => attr_safe(_("Return Page to Round")),
                'onclick'   => "return(confirm('"
                     . javascript_safe(_("This will discard all changes you have made on this page."), $charset)
                     . $CRLF . javascript_safe(_("Are you sure you want to return this page to the current round?"), $charset)
                     . "'));",
                'src'       => "gfx/bt15.png",
            );
            break;

        case REVERT_TO_LAST_SAVE:
            $attrs = array(
                'name'      => "button9",
                $label      => attr_safe(_("Revert to Last Save")),
                'title'     => attr_safe(_("Revert to Last Save")),
                'onclick'   => "return(confirm('"
                    . javascript_safe(_("Are you sure you want to revert to your last save?"), $charset)
                    . "'));",
                'src'       => "gfx/bt7.png",
            );
            break;

        case REVERT_TO_ORIGINAL:
            $attrs = array(
                'name'      => "button8",
                $label      => attr_safe(_("Revert to Original Document")),
                'title'     => attr_safe(_("Revert to Original Document")),
                'onclick'   => "return(confirm('"
                    . javascript_safe(_("Are you sure you want to revert to the original text for this round?"), $charset)
                    . "'));",
                'src'       => "gfx/bt8.png",
            );
            break;

        case SAVE_AS_DONE_AND_PROOF_NEXT:
            $attrs = array(
                'accesskey' => "8",
                'name'      => "button2",
                $label      => attr_safe(_("Save as 'Done' & Proofread Next Page")),
                'title'     => attr_safe(_("Save as 'Done' & Proofread Next Page")),
                'src'       => "gfx/bt2.png",
            );
            break;

        case SAVE_AS_DONE_AND_QUIT:
            $attrs = array(
                'accesskey' => "5",
                'name'      => "button5",
                $label      => attr_safe(_("Save as 'Done'")),
                'title'     => attr_safe(_("Save as 'Done'")),
                'src'       => "gfx/bt13.png",
            );
            break;

        case SAVE_AS_IN_PROGRESS:
            $attrs = array(
                'accesskey' => "7",
                'name'      => "button1",
                $label      => attr_safe(_("Save as 'In Progress'")),
                'title'     => attr_safe(_("Save as 'In Progress'")),
                'src'       => "gfx/bt3.png",
            );
            break;

        case SET_IMAGE_ZOOM:
            $attrs = array(
                'name'      => "button35",
                $label      => attr_safe(_("Set Image Zoom Percent")),
                'title'     => attr_safe(_("Set Image Zoom Percent")),
                'src'       => "gfx/bt10.png",
                'onclick'   => "top.showIZ(); return false;",
            );
            break;

        case SET_IMAGE_ACTUAL:
            $attrs = array(
                'name'      => "button36",
                $label      => attr_safe(_("Zoom to Actual Size")),
                'title'     => attr_safe(_("Zoom to Actual Size")),
                'src'       => "gfx/bt18.png",
                'onclick'   => "return(top.showActual());",
            );
            break;

        case SPELL_CHECK:
            $attrs = array(
                'name'      => "button10",
                $label      => attr_safe(_("WordCheck")),
                'title'     => attr_safe(_("Run WordCheck")),
                'src'       => "gfx/bt16.png",
            );
            if ($userP['i_type']==0)
            {
                // standard interface:
                // Direct the (text-only) spellcheck doc to 'textframe'
                // (rather than 'proofframe', the statically defined target).
                $attrs['onclick']="document.getElementById('editform').target='textframe'; return 1;";
            }
            break;

        case CONVERT_UTF8:
            $attrs = array(
                'name'      => "button12",
                $label      => attr_safe(_("Convert")),
                'title'     => attr_safe(_("Convert text codes to UTF-8")),
                'src'       => "gfx/bt19.png",
            );
            break;
        case SHOW_ALL_TEXT:
            $attrs = array(
                'name'      => "button13",
                $label      => attr_safe(_("Show All Text")),
                'title'     => attr_safe(_("Show All Text")),
                'onclick'   => "top.showNW(); return false;",
                'src'       => "gfx/bt9.png",
            );
            break;
        case PREVIEW:
            $attrs = array(
                'name'      => "button14",
                $label      => attr_safe(_("Preview")),
                'title'     => attr_safe(_("Format preview")),
                'onclick'   => "previewControl.show(); return false;",
                'src'       => "gfx/bt20.png?16092302",
            );
            break;
        case P_CHECK:
            $attrs = array(
                'name'      => "button15",
                $label      => attr_safe(_("Character Check")),
                'title'     => attr_safe(_("Character Check")),
                'onclick'   => "previewControl.show(0); return false;",
                'src'       => "gfx/bt21.png",
            );
            break;
    }
    if ($which_interface == 's')
    {
        unset($attrs['src']);
        $attrs['type'] = 'submit';
        $attrs['id'] = $attrs['name'];
    }
    else if ($which_interface == 'a')
    {
        $attrs['type' ] = 'image';
        $attrs['align'] = 'top';
        $attrs['size' ] = '26';
        $attrs['id'   ] = $attrs['name'];
    }

    echo "<INPUT\n";
    foreach ($attrs as $at_name => $at_value)
    {
        echo "$at_name=\"$at_value\"\n";
    }
    echo ">";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_img( $id )
{
    switch ($id)
    {
        case PROJECT_COMMENTS:
            $attrs = array(
                'src'       => "gfx/bt12.png",
                'alt'       => attr_safe(_("Project Comments")),
                'title'     => attr_safe(_("Project Comments")),
            );
            break;

        case REFRESH_IMAGE:
            $attrs = array(
                'src'       => "gfx/bt6.png",
                'alt'       => attr_safe(_("Refresh Image")),
                'title'     => attr_safe(_("Refresh Image")),
            );
            break;

        case REVERT_TO_LAST_SAVE_DISABLED:
            $attrs = array(
                'src'       => "gfx/bt7_n.png",
                'alt'       => attr_safe(_("Revert to Last Save Disabled")),
                'title'     => attr_safe(_("Revert to Last Save Disabled")),
            );
            break;

        case SHOW_ALL_TEXT:
            $attrs = array(
                'src'       => "gfx/bt9.png",
                'alt'       => attr_safe(_("Show All Text")),
                'title'     => attr_safe(_("Show All Text")),
            );
            break;
        case PREVIEW:
            $attrs = array(
                'src'       => "gfx/bt20.png?16092302",
                'alt'       => attr_safe(_("Format preview")),
                'title'     => attr_safe(_("Format preview")),
            );
            break;
        case P_CHECK:
            $attrs = array(
                'src'       => "gfx/bt21.png",
                'alt'       => attr_safe(_("Character Check")),
                'title'     => attr_safe(_("Character Check")),
            );
            break;
    }
    $attrs['align' ] = 'top';
    $attrs['width' ] = "26";
    $attrs['height'] = "26";
    $attrs['border'] = "0";

    echo "<img\n";
    foreach( $attrs as $at_name => $at_value )
    {
        print "$at_name=\"$at_value\"\n";
    }
    echo ">";
}

// vim: sw=4 ts=4 expandtab
?>
