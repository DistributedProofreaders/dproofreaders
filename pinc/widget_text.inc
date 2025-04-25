<?php

class Font
{
    public $name;
    public $face;
    public function __construct($name, $face)
    {
        $this->name = $name;
        $this->face = $face;
    }
}

$fonts = [
    "dp_sans_mono" => new Font('DP Sans Mono', "'DP Sans Mono', monospace"),
    "dejavu_sans_mono" => new Font('DejaVu Sans Mono', "'DejaVu Sans Mono', monospace"),
    "default" => new Font(_("Browser default"), "monospace"),
];

// need JSON_HEX_APOS flag because dp sans mono has a single quote
$widget_text = json_encode([
    "fonts" => $fonts,
    "settings" => _("Settings"),
    "done" => _("Done"),
    "font" => _("Font"),
    "size" => _("Size"),
    "wrap" => _("Wrap"),
    "fitHeight" => _("Fit image to height of window"),
    "fitWidth" => _("Fit image to width of window"),
    "zoomIn" => _("Zoom in 10%"),
    "zoomOut" => _("Zoom out 10%"),
    "zoomPercent" => _("Zoom to percentage of 1000 pixels wide"),
    "clockRotate" => _("Rotate image clockwise 90°"),
    "counterclockRotate" => _("Rotate image counterclockwise 90°"),
    "splitVertical" => _("Change to vertical layout"),
    "splitHorizontal" => _("Change to horizontal layout"),
    "showImageOnly" => _("Show Image only"),
    'showTextOnly' => _("Show Text only"),
    'showImageText' => _("Show Image & Text"),
    "selectAProject" => _("Select a different project"),
    "previous" => _("Previous"),
    'next' => _("Next"),
    "page" => _("Page"),
    "round" => _("Round"),
    "selectProject" => _("Select Project"),
    "projectid" => _("Project ID"),
    "enterID" => _("Please enter a project ID"),
    "browsePages" => _("Browse pages"),
    "projectPage" => _("Project Page"),
    "displayPageX" => _("Display Page: %s"),
    "noPageX" => _("There is no page %s in this project"),

], JSON_HEX_APOS);
