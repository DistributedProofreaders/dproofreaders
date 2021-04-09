<?php

// This file implements the "Launch in New Window" preference.
// This should be the only code that uses $user->profile->i_newwin
// (leaving aside code to save/restore/display/update it).

include_once($relPath.'resolution.inc');

function get_js_for_links_to_project_pages()
{
    global $i_resolutions;

    $user = User::load_current();

    $wSize = explode("x",$i_resolutions[$user->profile->i_res*1]);
    $window_width  = $wSize[0] - 20;
    $window_height = $wSize[1] - 80;

    return "
        function newProofWin(winURL)
        {
            newFeatures='toolbar={$user->profile->i_toolbar},status={$user->profile->i_statusbar},location=0,directories=0,menubar=0,scrollbars=1,resizable=1,width=$window_width,height=$window_height,top=0,left=5';
            nwWin=window.open(winURL,'_blank',newFeatures);
        }
    ";
}

function prep_for_links_to_project_pages()
{
    // Ensure that the guts of this function are only done once,
    // because don't want to define newProofWin more than once.
    static $this_func_has_been_called = FALSE;
    if ($this_func_has_been_called) return;
    $this_func_has_been_called = TRUE;

    echo "<script>" . get_js_for_links_to_project_pages() . "</script>";
}

function get_onclick_attr_for_link_to_project_page($url)
// Get the 'onclick' attribute (if any) for an <a> element
// that links to a project page.
{
    $user = User::load_current();
    if ( $user->profile->i_newwin == 1 )
    {
        $attr = "onclick=\"newProofWin('$url'); return false;\"";
    }
    else
    {
        $attr = '';
    }
    return $attr;
}

