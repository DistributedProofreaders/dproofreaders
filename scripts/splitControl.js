/*global $ */
/* exported initSplit */

function initSplit(container, reDraw, config) {
    // use these defaults if any config not set
    let theConfig = {splitDirection: "horizontal", splitPercent: 50, minSizePane1: 50, minSizePane2: 50};
    for(let key in config) {
        theConfig[key] = config[key];
    }
    // container is the id of a div which contains two divs:
    // referred to in this function as pane1 and pane2.
    // the dragBar is created between pane1 and pane2
    // More split views can be set up within the panes.
    // reDraw is a jquery callback which is fired when the container changes size.
    // minSizePane1, minSizePane2 minimum allowed size of panes top/left and bottom/right
    var splitRatio = theConfig.splitPercent / 100;
    var splitPos;     // position of split
    var range;
    var minPos;
    var maxPos;
    container = $(container);

    let children = container.children();
    let pane1 = $(children[0]).css({"position": "absolute"});
    let pane2 = $(children[1]).css({"position": "absolute"});

    let dragBar = $("<div>").css({"background-color": "darkgray", "position": "absolute"});
    pane1.after(dragBar);

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
            p1Size = splitPos - divLeft;
            pane1.width(p1Size);
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
            base = divLeft;
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
        minPos = base + theConfig.minSizePane1;
        // mouse sets top/left of dragbar
        maxPos = base + range - theConfig.minSizePane2 - 6;
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
    reDraw.add(reLayout);

    return {
        setSplit: function (splitDirection) {
            theConfig.splitDirection = splitDirection;
            reLayout();
        },

        reLayout: reLayout,
        reSize: reSize,
        dragEnd: dragEnd,
    };
}
