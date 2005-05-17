<?php

include_once($relPath.'theme.inc');

/* This file contains one function: echo_stylesheet_for_tabs() .
 *
 * It echoes a style sheet for creating a tabbed pane at a page.
 * At creation only used by userprefs.php,
 * but the design should make it re-usable at any page.
 *
 * The $theme-array may contain a key 'tabs' which should
 * map to an array with the keys 'background', 'background-position',
 * and 'padding-left'.
 * If $theme['tabs']['use_default_graphics'] is TRUE, a default
 * tab-stylesheet will be used.
 */
 
// This stylesheet originated from http://www.alistapart.com/articles/slidingdoors/
// Quote http://www.alistapart.com/copyright/:
/*
 *  A List Apart gives away CSS styles, JavaScript, PHP and Perl scripts,
 *  XHTML markup techniques, and other bits of "source code" for the use
 *  of its readers. You may freely copy, paste, and modify any of this
 *  code for use on your own web projects large or small, commercial or
 *  non-commercial. You need not ask permission to do so. You need not
 *  credit ALA or its authors. It is our gift to you.
 *
 *  ...
 *
 *  Images, including those used in tutorials (example), cannot be
 *  reproduced outside this magazine, except by the author or designer
 *  who created them.
 *
 *  You may adapt elements of our CSS in your own projects, but the
 *  unique combination of images, colors, sizes, typography, and
 *  positioning ("the design") is copyright 2003 A List Apart Magazine
 *  and Happy Cog Studios and may not be reproduced.
 */
// I've tweaked the css a little, mainly by removing/changing font-directives
// -- martinag

function echo_stylesheet_for_tabs() {
  global $theme, $theme_name, $code_url;

  $image_path = "$code_url/pinc/templates/$theme_name/graphics";
  $tabs = $theme['tabs'];

  if (isset($tabs['use_default_graphics']) && $tabs['use_default_graphics']) {
    $image_path = "$code_url/pinc/templates/default_graphics";
    $tabs['padding-left'] = '4px';
    $tabs['background-position'] = 'bottom';
    if ($tabs['background'] == null)
      $tabs['background'] = $theme['color_navbar_bg'];
  }

  ?>

<style type="text/css"><!--
   
/* style sheet to create nice tabs from a list */
   
body {
  margin:0;
  padding:0;
}
   
#tabs {
  float:left;
  width:100%;
  background:<?=$tabs['background']?> url("<?=$image_path?>/tabs_bg.png") repeat-x <?=$tabs['background-position']?>;
  font-size:80%;
  line-height:normal;
}
   
#tabs ul {
  margin:0;
  padding:10px 10px 0;
  list-style:none;
}
   
#tabs li {
  float:left;
  background:url("<?=$image_path?>/tabs_left.png") no-repeat left top;
  margin:0;
  padding:0 0 0 <?=$tabs['padding-left']?>;
}
   
#tabs a {
  float:left;
  display:block;
  background:url("<?=$image_path?>/tabs_right.png") no-repeat right top;
  padding:5px 15px 4px 6px;
  text-decoration:none;
  font-weight:bold;
  color:#765;
}

/* IE5-Mac has problems when a floated element gets an
   auto-width block-level element inserted into it */
/* Commented Backslash Hack
   hides rule from IE5-Mac \*/
#tabs a {float:none;}
/* End IE5-Mac hack */
   
#tabs a:hover {
  color:#333;
}
   
#tabs #current {
  background-image:url("<?=$image_path?>/tabs_left_on.png");
}
   
#tabs #current a {
  background-image:url("<?=$image_path?>/tabs_right_on.png");
  color:#333;
  padding-bottom:5px;
}
   
//--></style>

  <?php

} // end function echo_stylesheet_for_tabs()

?>
