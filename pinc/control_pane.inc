<?php
function get_control_pane_texts()
{
    $texts = json_encode([
        "adjustPanel" => _("Adjust control bar position and layout"),
        "dockTop" => _("Dock control bar to top"),
        "dockLeft" => _("Dock control bar to left"),
        "dockRight" => _("Dock control bar to right"),
        "dockBot" => _("Dock control bar to bottom"),
        "controlLeft" => _("Put controls at the left of the bar"),
        "controlRight" => _("Put controls at the right of the bar"),
        "controlMid" => _("Put controls at the middle of the bar"),
        "controlTop" => _("Put controls at the top of the bar"),
        "controlBot" => _("Put controls at the bottom of the bar"),
        "hideMenu" => _("Hide this menu"),
        "fitHeight" => _("Fit image to height of pane"),
        "fitWidth" => _("Fit image to width of pane"),
        "zoomIn" => _("Zoom in 10%"),
        "zoomOut" => _("Zoom out 10%"),
        "zoomPercent" => _("Zoom to percentage of 1000 pixels wide"),
    ]);

    return "var texts = $texts;";
}
