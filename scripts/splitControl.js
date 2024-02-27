/*global $ */
/* exported splitControl */

/*
 * Create a splitter between two <div>s within a container.
 * Arguments for splitControl:
 * container - a <div> which contains two <div>s (herein referred
 * to as pane1 and pane2). The splitter will be created between these.
 * config - optional object that controls the div: (defaults in brackets)
 * {
 *   splitVertical: (true) true or false,
 *   splitPercent: (50), percentage of container occupied by pane1,
 *   dragBarSize: (6), the width/height of the splitterbar in pixels,
 *   dragBarColor: ("darkgray"),
 * }
 *
 * Returns:
 * setSplit(vertical): a function to change the splitDirection.
 * reLayout(): a function to re-draw the panes, this should be called after
 *     drawing any divs surrounding the container. If this splitter is
*      inside another splitter it should be called by onResize of the
 *     parent splitter. If this is the top level splitter it should be
 *     called when the window is resized.
 * onResize: a set of functions which are called when relayout has been called
 *     and after moving the dragbar. It can be used to reLayout subsidiary
 *     splitControls.
 * onDragEnd: a set of functions which are called at the end of a drag resize with
 *     a percentage parameter. It enables the split percentage to be stored so
 *     that when splitControl is used again the split ratio can be persisted.
 */
var splitControl = function(container, {splitVertical = true, splitPercent = 50, dragBarSize = 6, dragBarColor = "darkgray"} = {}) {
    let splitRatio = splitPercent / 100;
    // base, splitPos, range, minPos, maxPos units in principal direction
    let base;
    let splitPos;
    let range;
    let minPos;
    let maxPos;
    container = $(container).css({display: 'flex'});
    let children = container.children();
    let pane1 = $(children[0]).css({overflow: 'auto'});
    let pane2 = $(children[1]).css({flex: '1 1 1px', overflow: 'auto'});

    let dragBar = $("<div>").css({"background-color": dragBarColor, flex: `0 0 ${dragBarSize}px`});
    pane1.after(dragBar);

    // coordinates of the container
    let height;
    let width;

    let onResize = new Set();
    let onDragEnd = new Set();

    function moveSplit() {
        if (splitPos < minPos) {
            splitPos = minPos;
        }
        if (splitPos > maxPos) {
            splitPos = maxPos;
        }
        pane1.css({flex: `0 0 ${splitPos - base}px`});
        onResize.forEach(function (reSizeCallback) {
            reSizeCallback();
        });
    }

    function reLayout() {
        container.css('overflow', 'hidden');
        height = container.height();
        width = container.width();
        let containerOffset = container.offset();
        let divTop = containerOffset.top;
        let divLeft = containerOffset.left;
        if (splitVertical) {
            container.css({flexDirection: 'row'});
            range = width;
            base = divLeft;
            dragBar.css("cursor", "ew-resize");
        } else {
            container.css({flexDirection: 'column'});
            range = height;
            base = divTop;
            dragBar.css("cursor", "ns-resize");
        }
        range -= dragBarSize;
        minPos = base;
        if(range < 0) {
            range = 0;
        }
        splitPos = base + (range * splitRatio);
        // mouse sets top/left of dragbar
        maxPos = base + range;
        moveSplit();
    }

    function dragStart(event) {
        event.preventDefault();
        // need this only if there is an iframe in a pane.
        pane2.css("pointerEvents", "none");
        pane1.css("pointerEvents", "none");
    }

    function dragMove(event) {
        splitPos = (splitVertical) ? event.pageX : event.pageY;
        moveSplit();
    }

    function dragMoveEnd() {
        // restore normal operation
        pane2.css("pointerEvents", "auto");
        pane1.css("pointerEvents", "auto");
        if(range > 0) {
            splitRatio = (splitPos - base) / range;
            onDragEnd.forEach(function (dragEndCallback) {
                dragEndCallback((splitRatio * 100).toFixed(0));
            });
        }
    }

    function dragMouseUp() {
        document.removeEventListener("mousemove", dragMove);
        document.removeEventListener("mouseup", dragMouseUp);
        document.body.style.cursor = "default";
        dragMoveEnd();
    }

    function dragMouseDown(event) {
        // prevent cursor flicker by persisting cursor style while dragging
        document.body.style.cursor = dragBar.css("cursor");
        dragStart(event);
        document.addEventListener("mousemove", dragMove);
        document.addEventListener("mouseup", dragMouseUp);
    }

    function dragTouchMove(event) {
        dragMove(event.touches[0]);
    }

    function dragTouchEnd() {
        document.removeEventListener("touchmove", dragTouchMove);
        document.removeEventListener("touchend", dragTouchEnd);
        dragMoveEnd();
    }

    function dragTouchStart(event) {
        dragStart(event);
        document.addEventListener("touchmove", dragTouchMove);
        document.addEventListener("touchend", dragTouchEnd);
    }

    dragBar.on("mousedown", dragMouseDown);
    dragBar.on("touchstart", dragTouchStart);

    return {
        setSplit: function (vertical) {
            splitVertical = vertical;
        },
        setSplitPercent: function (percent) {
            splitRatio = percent / 100;
        },
        reLayout: reLayout,
        onResize: onResize,
        onDragEnd: onDragEnd,
    };
};
