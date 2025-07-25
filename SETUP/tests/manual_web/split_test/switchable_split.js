/* global $ */
import { splitControl } from "../../../../scripts/splitControl.js";

window.addEventListener("DOMContentLoaded", function () {
    function appendControlButton(controlDiv, theSplit, splitVert) {
        let vSwitchButton = $("<input>", { type: "button", value: "Switch to Vertical Split" });
        let hSwitchButton = $("<input>", { type: "button", value: "Switch to Horizontal Split" });

        function setSplitControls(splitVertical) {
            if (splitVertical) {
                hSwitchButton.show();
                vSwitchButton.hide();
            } else {
                hSwitchButton.hide();
                vSwitchButton.show();
            }
        }

        function changeSplit(splitVertical) {
            theSplit.setSplit(splitVertical);
            theSplit.reLayout();
            setSplitControls(splitVertical);
        }

        vSwitchButton.click(function () {
            changeSplit(true);
        });

        hSwitchButton.click(function () {
            changeSplit(false);
        });

        $(controlDiv).append(vSwitchButton, hSwitchButton);
        setSplitControls(splitVert);
    }

    function appendIndicator(controlDiv, theSplit) {
        let indicator = $("<input>", { readonly: "true" });
        $(controlDiv).append(indicator);
        theSplit.onDragEnd.add(function (percent) {
            indicator.val(percent);
        });
    }

    let initialSplitVertical = false;
    let mainSplit = splitControl(document.getElementById("container"), { splitVertical: initialSplitVertical });
    window.addEventListener("resize", mainSplit.reLayout);
    appendControlButton("#control-div", mainSplit, initialSplitVertical);
    appendIndicator("#control-div", mainSplit);
    mainSplit.reLayout();
});
