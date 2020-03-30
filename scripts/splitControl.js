/*global $ */
/* exported initSplit */

function initSplit(paneContainer, verticalSplit, splitPercent, minSiz0, minSiz1) {
    // paneContainer is the id of a div containing three divs: they are the
    // chidren of paneContainer and are referred to in this function as pane1,
    // dragBar and pane2. Their styles can be defined by css. pane1 and pane2
    // generally have overflow:auto and the dragbar has a background colour.
    // jquery will give them position:relative.
    // More split views can be set up within the panes.
    // minSiz0, minSiz1 minimum allowed size of panes top/left and bottom/right
    var splitRatio = splitPercent / 100;
    var splitPos;     // position of split
    var range;
    var minPos;
    var maxPos;
    var container = $("#" + paneContainer);
    var children = container.children();
    var pane1 = $(children[0]);
    var dragBar = $(children[1]);
    var pane2 = $(children[2]);

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
        if (verticalSplit) {
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
        if (verticalSplit) {
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
        minPos = base + minSiz0;
        // mouse sets top/left of dragbar
        maxPos = base + range - minSiz1 - 6;
        moveSplit();
    }

    function windowMouseMove(event) {
        splitPos = (verticalSplit
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
    reLayout();

    return {
        setSplit: function (vertical) {
            verticalSplit = vertical;
            reLayout();
        },

        reLayout: reLayout,
        reSize: reSize,
        dragEnd: dragEnd,
    };
}
