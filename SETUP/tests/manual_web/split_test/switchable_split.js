/* global $ splitControl */
$(function () {
    let splitter = splitControl();

    function appendControlButton(controlDiv, theSplit, initialDirection) {
        let vSwitchButton = $("<input>", {type: 'button', value: 'Vertical Split'});
        let hSwitchButton = $("<input>", {type: 'button', value: 'Horizontal Split'});

        function setSplitControls(splitDirection) {
            if (splitDirection === splitter.DIRECTION.VERTICAL) {
                hSwitchButton.show();
                vSwitchButton.hide();
            } else {
                hSwitchButton.hide();
                vSwitchButton.show();
            }
        }

        function changeSplit(splitDirection) {
            theSplit.setSplit(splitDirection);
            setSplitControls(splitDirection);
        }

        vSwitchButton.click(function () {
            changeSplit(splitter.DIRECTION.VERTICAL);
        });

        hSwitchButton.click(function () {
            changeSplit(splitter.DIRECTION.HORIZONTAL);
        });

        $(controlDiv).append(vSwitchButton, hSwitchButton);
        setSplitControls(initialDirection);
    }

    function appendIndicator(controlDiv, theSplit) {
        let indicator = $("<input>", {readonly: 'true'});
        $(controlDiv).append(indicator);
        theSplit.dragEnd.add(function (percent) {
            indicator.val(percent);
        });
    }

    let initialSplitDirection = splitter.DIRECTION.HORIZONTAL;
    let mainSplit = splitter.setup("#container", {splitDirection: initialSplitDirection});
    appendControlButton("#control-div", mainSplit, initialSplitDirection);
    appendIndicator("#control-div", mainSplit);
    mainSplit.reLayout();
});
