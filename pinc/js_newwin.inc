<?php

// This file implements the "Launch in New Window" preference.
// This should be the only code that uses $userP['i_newwin']
// (leaving aside code to save/restore/display/update it).

function get_onclick_attr_for_link_to_project_page($url)
// Get the 'onclick' attribute (if any) for an <a> element
// that links to a project page.
{
    global $userP;
    if ( $userP['i_newwin'] == 1 )
    {
        $attr = "target='_blank'";
    }
    else
    {
        $attr = '';
    }
    return $attr;
}

// vim: sw=4 ts=4 expandtab
?>
