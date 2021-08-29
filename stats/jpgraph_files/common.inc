<?php

// Various utilities for the scripts in this directory.

include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($jpgraph_dir.'/src/jpgraph_canvas.php');
include_once($jpgraph_dir.'/src/jpgraph_pie.php');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_image_cache_filename()
// Returns a filename to pass into jpgraph's Graph constructor for caching
// purposes.
// jpgraph allows a value of "auto" to be passed which will base the image
// filename off the PHP_SELF value from Apache. This can't be used with
// gettext() support because we need to cache a per-language version of the
// graph. Instead we leverage jpgraph's GenImgName() function (which is
// ultimately used if "auto" is passed into the constructor) and prepend the
// user's language.
{
    return get_desired_language() . "-" . GenImgName();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
