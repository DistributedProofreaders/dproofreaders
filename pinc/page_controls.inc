<?php
function get_proofreading_interface_data_js()
{
    $font_data = [
        "faces" => get_font_styles(),
        "faceFamilies" => get_full_font_families(),
        "sizes" => array_values(get_available_proofreading_font_sizes()),
    ];

    $strings = [
        "page" => _("Page"),
        "previous" => _("Previous"),
        "next" => _("Next"),
        "previousPage" => _("Previous page"),
        "nextPage" => _("Next page"),
        "round" => _("Round"),
        "switchVert" => _("Switch to Vertical Layout"),
        "switchHoriz" => _("Switch to Horizontal Layout"),
        "selectFontFace" => _("Select Font Face"),
        "selectFontSize" => _("Select Font Size"),
        "wrap" => _("Wrap"),
        "lineWrap" => _("Line wrap"),
        "showImage" => _("Image only"),
        "showText" => _("Text only"),
        "showImageText" => _("Image & Text"),
        "selectProject" => _("Select Project"),
        "reset" => _("Select a different project"),
        "projectid" => _("Project ID"),
        "selectAPage" => _("Select a page"),
        "browserDefault" => BROWSER_DEFAULT_STR,
        "displayPageX" => _("Display Page: %s"),
        "noPages" => _("There are no pages in this project"),
        "enterID" => _("Please enter a project ID"),
        "noPageX" => _("There is no page %s in this project"),
        "layoutVertical" => _("Change to vertical layout"),
        "layoutHorizontal" => _("Change to horizontal layout"),
    ];

    global $code_url;
    $button_images = [
        "imgVSplit" => "$code_url/tools/proofers/gfx/bt4.png",
        "imgHSplit" => "$code_url/tools/proofers/gfx/bt5.png",
    ];

    $proof_int_data = json_encode([
        "font" => $font_data,
        "strings" => $strings,
        "buttonImages" => $button_images,
        "projectFile" => "$code_url/project.php",
    ]);

    return "var proofIntData = $proof_int_data;";
}
