/*global $ */
/* exported splitControl */

/*
 * Create a splitter between two <div>s within a container.
 * splitControl returns:
 * DIRECTION: an object containing VERTICAL and HORIZONTAL constants
 * setup: a function to setup the splitter
 * Arguments for setup:
 * container - ID of a <div> which contains two <div>s (herein referred
 *     to as pane1 and pane2). The splitter will be created between these.
 * config - optional dictionary that controls the div: (defaults in brackets)
 * {
 *   splitDirection: (DIRECTION.VERTICAL) DIRECTION.VERTICAL or DIRECTION.HORIZONTAL,
 *   splitPercent: (50), percentage of contaner occupied by pane1,
 *   reDraw - jQuery callback which should be fired when the container size changes.
 *       by default this is fired by $(window).resize()
 *       for a subsidiary splitter use reSize returned by the parent splitter
 *   dragBarSize: (6), the width/height of the splitterbar in pixels,
 *   dragBarColor: ("darkgray"),
 * }
 *
 * Returns:
 * setSplit(splitDirection): a function to change the splitDirection
 * reLayout(): a function to re-draw the panes, this should be called after
 *     drawing any divs surrounding the container.
 * reSize: this jquery callback is fired after relayout has been called
 *     and after moving the dragbar. It can be used as the reDraw parameter for
 *     subsidiary splitControls.
 * dragEnd: this jquery callback is fired at the end of a drag resize with
 *     a percentage parameter. It enables the split percentage to be stored so
 *     that when splitControl is used again the split ratio can be persisted.
 */
var splitControl = function() {
    const DIRECTION = {VERTICAL: 1, HORIZONTAL: 0};

    let windowResize = $.Callbacks();
    $(window).resize(function () {
        windowResize.fire();
    });

    return {
        setup: function(container, config) {
            let theConfig = {reDraw: windowResize, splitDirection: DIRECTION.VERTICAL, splitPercent: 50, dragBarSize: 6, dragBarColor: "darkgray"};
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
            container = $(container);

            let children = container.children();
            let pane1 = $(children[0]).css({"position": "absolute"});
            let pane2 = $(children[1]).css({"position": "absolute"});

            let dragBar = $("<div>").css({"background-color": theConfig.dragBarColor, "position": "absolute"});
            pane1.after(dragBar);

            // coordinates of the container
            let divTop;
            let divLeft;
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
                let splitPlusDrag = splitPos + theConfig.dragBarSize;
                if (theConfig.splitDirection === DIRECTION.VERTICAL) {
                    pane1.width(splitPos - base);
                    dragBar.offset({top: divTop, left: splitPos});
                    pane2.offset({top: divTop, left: splitPlusDrag});
                    pane2.width(width + divLeft - splitPlusDrag);
                } else {
                    pane1.height(splitPos - base);
                    dragBar.offset({top: splitPos, left: divLeft});
                    pane2.height(height + divTop - splitPlusDrag);
                    pane2.offset({top: splitPlusDrag, left: divLeft});
                }
                reSize.fire();
            }

            function reLayout() {
                height = container.height();
                width = container.width();
                let containerOffset = container.offset();
                divTop = containerOffset.top;
                divLeft = containerOffset.left;
                pane1.offset(containerOffset);
                if (theConfig.splitDirection === DIRECTION.VERTICAL) {
                    range = width;
                    base = divLeft;
                    pane1.height(height);
                    pane2.height(height);
                    dragBar.height(height);
                    dragBar.width(theConfig.dragBarSize);
                    dragBar.css("cursor", "ew-resize");
                } else {
                    range = height;
                    base = divTop;
                    pane1.width(width);
                    pane2.width(width);
                    dragBar.width(width);
                    dragBar.css("cursor", "ns-resize");
                    dragBar.height(theConfig.dragBarSize);
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
                splitPos = (theConfig.splitDirection === DIRECTION.VERTICAL) ? event.pageX : event.pageY;
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
                setSplit: function (splitDirection) {
                    theConfig.splitDirection = splitDirection;
                    reLayout();
                },

                reLayout: reLayout,
                reSize: reSize,
                dragEnd: dragEnd,
            };
        },
        DIRECTION: DIRECTION,
    };
};
