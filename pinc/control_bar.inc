<?php
function get_control_bar_texts()
{
    $texts = json_encode([
        "adjustPanel" => _("Adjust control bar position and layout"),
        "dockTop" => _("Dock control bar to top"),
        "dockLeft" => _("Dock control bar to left"),
        "dockRight" => _("Dock control bar to right"),
        "dockBot" => _("Dock control bar to bottom"),
        "controlLeft" => _("Left-align controls"),
        "controlRight" => _("Right-align controls"),
        "controlCenter" => _("Center-align controls"),
        "controlTop" => _("Top-align controls"),
        "controlBot" => _("Bottom-align controls"),
        "controlMid" => _("Middle-align controls"),
        "hideMenu" => _("Hide this menu"),
        "fitHeight" => _("Fit image to height of window"),
        "fitWidth" => _("Fit image to width of window"),
        "zoomIn" => _("Zoom in 10%"),
        "zoomOut" => _("Zoom out 10%"),
        "zoomPercent" => _("Zoom to percentage of natural width"),
        "clockRotate" => _("Rotate image clockwise 90°"),
        "anticlockRotate" => _("Rotate image anticlockwise 90°"),
    ]);

    return "var texts = $texts;";
}
