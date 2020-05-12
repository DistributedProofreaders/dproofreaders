/*global $ */
/* exported initSplit */

function initSplit(container, config) {
    // use these defaults if any config not set
    let theConfig = {splitDirection: "horizontal", splitPercent: 50, minSiz0: 50, minSiz1: 50};
    for(let key in config) {
        theConfig[key] = config[key];
    }
    // paneContainer is the id of a div which will contain three divs:
    // referred to in this function as pane1, dragBar and pane2.
    // More split views can be set up within the panes.
    // minSiz0, minSiz1 minimum allowed size of panes top/left and bottom/right
    var splitRatio = theConfig.splitPercent / 100;
    var splitPos;     // position of split
    var range;
    var minPos;
    var maxPos;
    var container = $(container);

    let pane1 = $("<div>").css({"position": "absolute"});
    let dragBar = $("<div>").css({"background-color": "darkgray", "position": "absolute"});
    let pane2 = $("<div>").css({"position": "absolute"});
    container.append(pane1, dragBar, pane2);

    var divTop;
    var divLeft;
    var height;
    var width;
    var reSize = $.Callbacks();
    var dragEnd = $.Callbacks();

    function moveSplit() {
        if (splitPos < minPos) {
            splitPos = minPos;
        }
        if (splitPos > maxPos) {
            splitPos = maxPos;
        }
        var sp6 = splitPos + 6;
        var p1Size;
        if (theConfig.splitDirection === "vertical") {
            p1Size = splitPos;
            pane1.width(splitPos);
            dragBar.offset({top: divTop, left: splitPos});
            pane2.offset({top: divTop, left: sp6});
            pane2.width(width + divLeft - sp6);
        } else {
            p1Size = splitPos - divTop;
            pane1.height(p1Size);
            dragBar.offset({top: splitPos, left: divLeft});
            pane2.height(height + divTop - sp6);
            pane2.offset({top: sp6, left: divLeft});
        }
        splitRatio = p1Size / range;
        reSize.fire();
    }

    function reLayout() {
        var base;
        height = container.height();
        width = container.width();
        var containerOffset = container.offset();
        divTop = containerOffset.top;
        divLeft = containerOffset.left;
        pane1.offset({top: divTop, left: divLeft});
        if (theConfig.splitDirection === "vertical") {
            range = width;
            base = 0;
            pane1.height(height);
            pane2.height(height);
            dragBar.height(height);
            dragBar.width(6);
            dragBar.css("cursor", "ew-resize");
        } else {
            range = height;
            base = divTop;
            pane1.width(width);
            pane2.width(width);
            dragBar.width(width);
            dragBar.css("cursor", "ns-resize");
            dragBar.height(6);
        }
        splitPos = base + (range * splitRatio);
        minPos = base + theConfig.minSiz0;
        // mouse sets top/left of dragbar
        maxPos = base + range - theConfig.minSiz1 - 6;
        moveSplit();
    }

    function windowMouseMove(event) {
        splitPos = ((theConfig.splitDirection === "vertical")
            ? event.pageX
            : event.pageY);
        moveSplit();
    }

    function windowMouseUp() {
        $(document).unbind("mousemove mouseup");
        // restore normal operation
        pane2.css("pointerEvents", "auto");
        pane1.css("pointerEvents", "auto");
        dragEnd.fire((splitRatio * 100).toFixed(0));
    }

    function dragBarMouseDown(event) {
        event.preventDefault();
        $(document).mousemove(windowMouseMove)
            .mouseup(windowMouseUp);
        // if there is an iframe it will take mousemove
        pane2.css("pointerEvents", "none");
        pane1.css("pointerEvents", "none");
    }

    dragBar.mousedown(dragBarMouseDown);

    return {
        pane1: pane1,
        pane2: pane2,
        setSplit: function (splitDirection) {
            theConfig.splitDirection = splitDirection;
            reLayout();
        },

        reLayout: reLayout,
        reSize: reSize,
        dragEnd: dragEnd,
    };
}
