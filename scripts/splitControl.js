/*global $ */
/* exported splitControl */

/*
 * Create a splitter between two <div>s within a container.
 * splitControl returns:
 * Arguments for splitControl:
 * container - ID of a <div> which contains two <div>s (herein referred
 *     to as pane1 and pane2). The splitter will be created between these.
 * config - optional dictionary that controls the div: (defaults in brackets)
 * {
 *   splitVertical: (true) true or false,
 *   splitPercent: (50), percentage of contaner occupied by pane1,
 *   reDraw - jQuery callback which should be fired when the container size changes.
 *       by default this is fired by $(window).resize()
 *       for a subsidiary splitter use reSize returned by the parent splitter
 *   dragBarSize: (6), the width/height of the splitterbar in pixels,
 * }
 *
 * Returns:
 * setSplit(splitVertical): a function to change the splitDirection
 * reLayout(): a function to re-draw the panes, this should be called after
 *     drawing any divs surrounding the container.
 * reSize: this jquery callback is fired after relayout has been called
 *     and after moving the dragbar. It can be used as the reDraw parameter for
 *     subsidiary splitControls.
 * dragEnd: this jquery callback is fired at the end of a drag resize with
 *     a percentage parameter. It enables the split percentage to be stored so
 *     that when splitControl is used again the split ratio can be persisted.
 */
var splitControl = function(container, config) {

    let windowResize = $.Callbacks();
    $(window).resize(function () {
        windowResize.fire();
    });

    let theConfig = {reDraw: windowResize, splitVertical: true, splitPercent: 50, dragBarSize: 6};
    for(let key in config) {
        theConfig[key] = config[key];
    }
    let splitRatio = theConfig.splitPercent / 100;
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

    // Drag bar with a row of dashes in the centre
    let dragBar = $("<div>", {class: 'drag-bar'}).css({flex: `0 0 ${theConfig.dragBarSize}px`})
        .append($("<div>", {class: 'drag-bar-marker'}).css({'border-width': `${theConfig.dragBarSize / 3}px`, margin: `${theConfig.dragBarSize / 6}px`}));

    pane1.after(dragBar);

    // coordinates of the container
    let height;
    let width;

    let reSize = $.Callbacks();
    let dragEnd = $.Callbacks();

    function moveSplit() {
        if (splitPos < minPos) {
            splitPos = minPos;
        }
        if (splitPos > maxPos) {
            splitPos = maxPos;
        }
        pane1.css({flex: `0 0 ${splitPos - base}px`});
        reSize.fire();
    }

    function reLayout() {
        container.css('overflow', 'hidden');
        height = container.height();
        width = container.width();
        let containerOffset = container.offset();
        let divTop = containerOffset.top;
        let divLeft = containerOffset.left;
        if (theConfig.splitVertical) {
            container.css({flexDirection: 'row'});
            range = width;
            base = divLeft;
            dragBar.css({cursor: 'ew-resize', 'flex-direction': 'column'});
        } else {
            container.css({flexDirection: 'column'});
            range = height;
            base = divTop;
            dragBar.css({cursor: 'ns-resize', 'flex-direction': 'row'});
        }
        range -= theConfig.dragBarSize;
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
        splitPos = (theConfig.splitVertical) ? event.pageX : event.pageY;
        moveSplit();
    }

    function dragMoveEnd() {
        // restore normal operation
        pane2.css("pointerEvents", "auto");
        pane1.css("pointerEvents", "auto");
        if(range > 0) {
            splitRatio = (splitPos - base) / range;
            dragEnd.fire((splitRatio * 100).toFixed(0));
        }
    }

    function dragMouseUp() {
        $(document).unbind("mousemove mouseup");
        dragMoveEnd();
    }

    function dragMouseDown(event) {
        dragStart(event);
        $(document).on("mousemove", dragMove)
            .on("mouseup", dragMouseUp);
    }

    function dragTouchMove(event) {
        dragMove(event.touches[0]);
    }

    function dragTouchEnd() {
        $(document).unbind("touchmove touchend");
        dragMoveEnd();
    }

    function dragTouchStart(event) {
        dragStart(event);
        $(document).on("touchmove", dragTouchMove)
            .on("touchend", dragTouchEnd);
    }

    dragBar.on("mousedown", dragMouseDown);
    dragBar.on("touchstart", dragTouchStart);
    theConfig.reDraw.add(reLayout);

    return {
        setSplit: function (splitVertical) {
            theConfig.splitVertical = splitVertical;
            reLayout();
        },

        reLayout: reLayout,
        reSize: reSize,
        dragEnd: dragEnd,
    };
};
