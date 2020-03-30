/*global $ initSplit */

var mainSplit;

// this initiates the split using direction and ratio from local storage
// saves these if changed, and works the split control buttons
$(function () {
    function setSplitControls(vSplit) {
        if (vSplit) {
            $("#h-switch").show();
            $("#v-switch").hide();
        } else {
            $("#h-switch").hide();
            $("#v-switch").show();
        }
    }

    function startSplit() {
        // vSplit is true or false
        var vSplit = localStorage.getItem("text_image_split");
        if(vSplit) {
            vSplit = JSON.parse(vSplit);
        } else {
            vSplit = false;
        }
        var splitPercent = localStorage.getItem("split_percent");
        if(!splitPercent) {
            splitPercent = 50;
        }
        mainSplit = initSplit("pane_container", vSplit, splitPercent, 50, 50);
        setSplitControls(vSplit);
    }

    startSplit();

    $(window).resize(mainSplit.reLayout);

    function changeSplit(vSplit) {
        mainSplit.setSplit(vSplit);
        setSplitControls(vSplit);
        localStorage.setItem("text_image_split", JSON.stringify(vSplit));
    }

    $("#v-switch").click(function () {
        changeSplit(true);
    });

    $("#h-switch").click(function () {
        changeSplit(false);
    });

    mainSplit.dragEnd.add(function (percent) {
        localStorage.setItem("split_percent", percent);
    });

});
