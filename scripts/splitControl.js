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
var splitControl = function (container, { splitVertical = true, splitPercent = 50, dragBarSize = 6, dragBarColor = "darkgray", splitLimit = 0.1 } = {}) {
    let splitRatio = splitPercent / 100;
    // base, splitPos, range units in principal direction
    let base;
    let splitPos;
    let range;
    container.style.display = "flex";
    let pane1 = container.children[0]
    pane1.style.overflow = "auto";
    let pane2 = container.children[1];
    pane2.style.flex = "1 1 1px";
    pane2.style.overflow = "auto";

    let dragBar = document.createElement("div");
    dragBar.style.backgroundColor = dragBarColor;
    dragBar.style.flex = `0 0 ${dragBarSize}px`;
    pane1.after(dragBar);

    const onResize = new Set();
    const onDragEnd = new Set();

    function moveSplit() {
        splitRatio = Math.max(splitRatio, splitLimit);
        splitRatio = Math.min(splitRatio, 1 - splitLimit);
        splitPos = base + range * splitRatio;
        pane1.style.flex = `0 0 ${splitPos - base}px`;
        onResize.forEach(function (reSizeCallback) {
            reSizeCallback();
        });
    }

    function reLayout() {
        container.style.overflow = "hidden";
        const containerRect = container.getBoundingClientRect();
        if (splitVertical) {
            container.style.flexDirection = "row";
            range = containerRect.width;
            base = containerRect.left;
            dragBar.style.cursor = "ew-resize";
        } else {
            container.style.flexDirection = "column";
            range = containerRect.height;
            base = containerRect.top;
            dragBar.style.cursor = "ns-resize";
        }
        range -= dragBarSize;
        // mouse sets top/left of dragbar
        moveSplit();
    }

    function dragStart(event) {
        event.preventDefault();
        // need this only if there is an iframe in a pane.
        pane2.style.pointerEvents = "none";
        pane1.style.pointerEvents = "none";
    }

    function dragMove(event) {
        splitPos = splitVertical ? event.clientX : event.clientY;
        if (range > 0) {
            splitRatio = (splitPos - base) / range;
        }
        moveSplit();
    }

    function dragMoveEnd() {
        // restore normal operation
        pane2.style.pointerEvents = "auto";
        pane1.style.pointerEvents = "auto";
        onDragEnd.forEach(function (dragEndCallback) {
            dragEndCallback((splitRatio * 100).toFixed(0));
        });
    }

    function dragMouseUp() {
        document.removeEventListener("mousemove", dragMove);
        document.removeEventListener("mouseup", dragMouseUp);
        document.body.style.cursor = "default";
        dragMoveEnd();
    }

    function dragMouseDown(event) {
        // prevent cursor flicker by persisting cursor style while dragging
        document.body.style.cursor = dragBar.style.cursor;
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

    dragBar.addEventListener("mousedown", dragMouseDown);
    dragBar.addEventListener("touchstart", dragTouchStart);

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
