<?
//
//
// The Default Template
//
//
//

$classic_grey['template_name'] = "Basic Template";
$classic_grey['template_unix_name'] = "basic_template";
$classic_grey['template_description'] = "A basic template.  What else is there to say?";
$classic_grey['template_creator'] = "Who are you?";

$classic_grey['body_bgcolor'] = "#ffffff";
$classic_grey['body_text'] = "#000000";
$classic_grey['body_link'] = "#0000ff";
$classic_grey['body_vlink'] = "#0000ff";
$classic_grey['body_alink'] = "#0000ff";

$classic_grey['image_logo'] = "/graphics/logo.gif";
$classic_grey['image_curve_right'] = "/graphics/r_curve.gif";
$classic_grey['image_curve_left'] = "/graphics/l_curve.gif";
$classic_grey['image_gold_star'] = "/graphics/gold_star.gif";
$classic_grey['image_silver_star'] = "/graphics/silver_star.gif";
$classic_grey['image_bronze_star'] = "/graphics/bronze_star.gif";
$classic_grey['image_donate'] = "/graphics/donate.gif";

$classic_grey['color_logobar_bg'] = "#ffffff";
$classic_grey['color_headerbar_bg'] = "#999999";
$classic_grey['color_headerbar_font'] = "#000000";
$classic_grey['color_mainbody_bg'] = "#ffffff";
$classic_grey['color_mainbody_font'] = "#000000";
$classic_grey['color_navbar_bg'] = "#cccccc";
$classic_grey['color_navbar_font'] = "#000000";
$classic_grey['color_copyright_bg'] = "#cccccc";
$classic_grey['color_copyright_font'] = "#000000";

$classic_grey['font_headerbar'] = "verdana, helvetica, sans-serif";
$classic_grey['font_mainbody'] = "verdana, helvetica, sans-serif";
$classic_grey['font_navbar'] = "verdana, helvetica, sans-serif";
$classic_grey['font_copyright'] = "verdana, helvetica, sans-serif";

$classic_grey['tabs'] = array(
    // These values are used by pinc/tabs.inc to
    // make a list appear as tabs.
    // Images named 'tabs_left.png', 'tabs_left_on.png',
    // 'tabs_bg.png', 'tabs_right.png.png', 'tabs_right_on.png'
    // in the graphics-folder will be used.
    'background' => '#ffffff',
    'background-position' => 'bottom',
    'padding-left' => '5px',
    //
    // If you provide a key 'use_default_graphics' with a TRUE value,
    // a default tab-layout will be used. You can still specify
    // a background-color using a 'background'-key. If you don't,
    // the $theme['color_navbar_bg']-value will be used.
    // 'use_default_graphics' => true,
);
?>