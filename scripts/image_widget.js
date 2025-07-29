/* global makeLabel makeCheckBox */

import translate from "./gettext.js";

export function makeImageWidget(container, userSettings) {
    const content = document.createElement("div");
    content.id = "image_content";
    const grabCursor = "grab";
    // use plain js image so width or style.width is clearly differentiated
    const image = document.createElement("img");

    // When the image is rotated it has width and height as if it were not
    // rotated. To make scroll work correctly, enclose it in a div with the
    //actual width and height.
    const imageDiv = document.createElement("div");
    imageDiv.classList.add("middle-align", "center-align", "overflow-hidden");
    imageDiv.style.cursor = grabCursor;
    imageDiv.style.display = "inline-block";
    imageDiv.appendChild(image);

    content.append(imageDiv);
    let scrollDiffX = 0;
    let scrollDiffY = 0;

    function dragMove(event) {
        content.scrollTop = scrollDiffY - event.pageY;
        content.scrollLeft = scrollDiffX - event.pageX;
    }

    function pointerUp() {
        document.removeEventListener("pointermove", dragMove);
        document.removeEventListener("pointerup", pointerUp);
        imageDiv.style.cursor = grabCursor;
    }

    function dragStart(event) {
        scrollDiffX = event.pageX + content.scrollLeft;
        scrollDiffY = event.pageY + content.scrollTop;
    }

    imageDiv.addEventListener("pointerdown", function (event) {
        event.preventDefault();
        imageDiv.style.cursor = "grabbing";
        dragStart(event);
        document.addEventListener("pointermove", dragMove);
        document.addEventListener("pointerup", pointerUp);
    });

    // percent need not be an integer but is rounded for display
    // it will typically not be an integer after fit height or width or + or -
    let percent;
    const minPercent = 10;
    const maxPercent = 999;
    const defaultPercent = 100;

    // sine & cosine define the rotation:
    //  clockwise angle   sine   cosine
    //    0 degrees         0       1
    //   90 degrees        -1       0
    //  180 degrees         0      -1
    //  270 degrees         1       0
    let sine = 0;
    let cosine = 1;

    let contentWidth, contentHeight, imageWidth, imageHeight, imDivWidth, imDivHeight, vertOffset;

    let unset = true;
    function initScroll() {
        // centre horizontally
        content.scrollLeft = 0.5 * (imDivWidth - contentWidth);
        // top of image at top of window
        content.scrollTop = vertOffset;
        unset = false;
    }

    function setImageStyle() {
        // when this is called in 'setImage' we will not know the dimensions
        // of the image but it is not rotated (sine=0) so we do not need to.
        contentWidth = content.clientWidth;
        contentHeight = content.clientHeight;
        image.style.width = `${10 * percent}px`;
        image.style.height = "auto";

        let xOffset, yOffset;
        if (sine != 0) {
            // rotated 90 or 270 degrees
            imageWidth = image.height;
            imageHeight = image.width;
            yOffset = -(image.height - image.width) / 2;
        } else {
            // rotated 0 or 180 degrees
            imageWidth = image.width;
            imageHeight = image.height;
            yOffset = 0;
        }
        // center aligned so:
        xOffset = 0;
        imDivWidth = Math.max(2 * (contentWidth - 60) + imageWidth, imageWidth);
        imageDiv.style.width = `${imDivWidth}px`;
        imDivHeight = Math.max(2 * (contentHeight - 60) + imageHeight, imageHeight);
        imageDiv.style.height = `${imDivHeight}px`;
        // adjust yOffset to be able to scroll downwards
        vertOffset = Math.max(0.5 * (imDivHeight - imageHeight), 0);
        yOffset += vertOffset;
        // image rotates about centre. Offset moves it to correct position
        image.style.transform = `matrix(${cosine}, ${-sine}, ${sine}, ${cosine}, ${xOffset}, ${yOffset})`;

        if (unset) {
            initScroll();
        }
    }

    const percentInput = document.createElement("input");
    percentInput.type = "number";
    percentInput.classList.add("text_number");
    percentInput.value = percent;
    percentInput.title = translate.gettext("Zoom to percentage of 1000 pixels wide");

    function setZoom() {
        if (percent < minPercent) {
            percent = minPercent;
        } else if (percent > maxPercent) {
            percent = maxPercent;
        }
        percentInput.value = Math.round(percent);
    }

    function setDrawSave() {
        setZoom();
        setImageStyle();
        userSettings.zoomPercent = percent;
    }

    function onWheel(event) {
        // mouse wheel gives +- 120, trackpad gives small number
        if (event.ctrlKey) {
            event.preventDefault();
            const absDelta = Math.abs(event.wheelDelta);
            const ratio = (100 + Math.min(absDelta, 10)) / 100;
            if (event.wheelDelta > 0) {
                percent *= ratio;
            } else {
                percent /= ratio;
            }
            setDrawSave();
        }
    }
    imageDiv.addEventListener("wheel", onWheel);

    percentInput.addEventListener("change", function () {
        percent = parseInt(this.value);
        if (isNaN(percent)) {
            percent = defaultPercent;
        }
        setDrawSave();
    });

    function makeImageButtton(title, icon) {
        const button = document.createElement("button");
        button.type = "button";
        button.classList.add("bordered_icon_button");
        button.title = title;
        button.innerHTML = `<i class="${icon}"></i>`;
        return button;
    }

    const fitHeightButton = makeImageButtton(translate.gettext("Fit image to height of window"), "fas fa-arrows-alt-v");
    fitHeightButton.addEventListener("click", function () {
        const contentHeight = getComputedStyle(content).height;
        if (sine == 0) {
            image.style.height = contentHeight;
            image.style.width = "auto";
        } else {
            image.style.width = contentHeight;
        }
        percent = image.width / 10;
        setDrawSave();
        initScroll();
    });

    const fitWidthButton = makeImageButtton(translate.gettext("Fit image to width of window"), "fas fa-arrows-alt-h");
    fitWidthButton.addEventListener("click", function () {
        const contentWidth = getComputedStyle(content).width;
        if (sine == 0) {
            image.style.width = contentWidth;
        } else {
            image.style.height = contentWidth;
            image.style.width = "auto";
        }
        percent = image.width / 10;
        setDrawSave();
        initScroll();
    });

    const zoomInButton = makeImageButtton(translate.gettext("Zoom in 10%"), "fas fa-search-plus");
    zoomInButton.addEventListener("click", function () {
        percent *= 1.1;
        setDrawSave();
    });

    const zoomOutButton = makeImageButtton(translate.gettext("Zoom out 10%"), "fas fa-search-minus");
    zoomOutButton.addEventListener("click", function () {
        percent /= 1.1;
        setDrawSave();
    });

    const clockRotateButton = makeImageButtton(translate.gettext("Rotate image clockwise 90°"), "fas fa-redo-alt");
    clockRotateButton.addEventListener("click", function () {
        [sine, cosine] = [-cosine, sine];
        setImageStyle();
        initScroll();
    });

    const counterClockRotateButton = makeImageButtton(translate.gettext("Rotate image counterclockwise 90°"), "fas fa-undo-alt");
    counterClockRotateButton.addEventListener("click", function () {
        [sine, cosine] = [cosine, -sine];
        setImageStyle();
        initScroll();
    });

    percent = userSettings.zoomPercent ?? 100;
    setZoom();

    // touch handlers:  handle 1-finger drag and 2-finger pinch-to-zoom
    //
    const activeTouches = []; // 0 is no action, 1 is dragging, 2 is drag/scaling, 3 or more is no action

    function copyTouch({ identifier, pageX, pageY }) {
        // make a copy of the touch data structure just to be memory safe when adding to lists
        return { identifier, pageX, pageY };
    }

    function touchStart(e) {
        // cancel the pointer event -- we'll handle this
        pointerUp();

        // Get the new touches -- add them to the list
        e.preventDefault(); // tell the OS not to try to handle it
        const touches = e.changedTouches;
        for (let i = 0; i < touches.length; i++) {
            activeTouches.push(copyTouch(touches[i]));
        }
    }

    function touchMove(e) {
        // one or more of the touches have moved.  What happens depends
        // on how many touches there are
        e.preventDefault();
        const touches = e.changedTouches;
        if (activeTouches.length == 1) {
            // one touch -- drag the image
            content.scrollLeft += activeTouches[0].pageX - touches[0].pageX;
            content.scrollTop += activeTouches[0].pageY - touches[0].pageY;
            activeTouches.splice(0, 1, copyTouch(touches[0]));
        } else if (activeTouches.length == 2) {
            // two touches -- scale/drag the image
            //
            // Note that the list of changed touches may only contain one point,
            // if the other one stayed the same.  So we initialize our variables
            // based on the idea that neither has changed, and we update the ones
            // that have
            //
            let prevAx = activeTouches[0].pageX;
            let prevAy = activeTouches[0].pageY;
            let newAx = prevAx;
            let newAy = prevAy;

            let prevBx = activeTouches[1].pageX;
            let prevBy = activeTouches[1].pageY;
            let newBx = prevBx;
            let newBy = prevBy;

            // There is no guarantee that the changed touch point list is in the same order
            // each time we receive it, so we have to match up the IDs
            if (touches.length >= 1) {
                if (touches[0].identifier == activeTouches[0].identifier) {
                    newAx = touches[0].pageX;
                    newAy = touches[0].pageY;
                    activeTouches.splice(0, 1, copyTouch(touches[0]));
                } else {
                    newBx = touches[0].pageX;
                    newBy = touches[0].pageY;
                    activeTouches.splice(1, 1, copyTouch(touches[0]));
                }
            }
            if (touches.length >= 2) {
                if (touches[1].identifier == activeTouches[0].identifier) {
                    newAx = touches[1].pageX;
                    newAy = touches[1].pageY;
                    activeTouches.splice(0, 1, copyTouch(touches[1]));
                } else {
                    newBx = touches[1].pageX;
                    newBy = touches[1].pageY;
                    activeTouches.splice(1, 1, copyTouch(touches[1]));
                }
            }

            // see how the centre point between the two touch points has moved during the gesture.
            let prevCx = 0.5 * (prevAx + prevBx);
            let prevCy = 0.5 * (prevAy + prevBy);
            let newCx = 0.5 * (newAx + newBx);
            let newCy = 0.5 * (newAy + newBy);

            // Figure out where the previous touch centre point is relative to the center of the page
            // image.
            let imageRect = imageDiv.getBoundingClientRect();
            let deltaX = prevCx - imageRect.width * 0.5 - imageRect.left;
            let deltaY = prevCy - imageRect.height * 0.5 - imageRect.top;

            // see how the distance between the touchpoints has changed to figure out the scale delta
            let newDist = Math.sqrt((newAx - newBx) * (newAx - newBx) + (newAy - newBy) * (newAy - newBy));
            let prevDist = Math.sqrt((prevAx - prevBx) * (prevAx - prevBx) + (prevAy - prevBy) * (prevAy - prevBy));
            let scaleDelta = newDist / prevDist;

            // Now scroll the div to try to keep the same point on the image under the point halfway between
            // the two touch points, doing tracking and scaling simultaneously
            let newDivWidth = Math.max(2 * (contentWidth - 60) + imageWidth * scaleDelta, imageWidth * scaleDelta);
            let newDivHeight = Math.max(2 * (contentHeight - 60) + imageHeight * scaleDelta, imageHeight * scaleDelta);
            let newPointX = newDivWidth * 0.5 + deltaX * scaleDelta;
            let newPointY = newDivHeight * 0.5 + deltaY * scaleDelta;

            // apply the translation
            let contentRect = content.getBoundingClientRect();
            content.scrollLeft = newPointX - newCx + contentRect.left;
            content.scrollTop = newPointY - newCy + contentRect.top;

            // apply the scaling
            percent *= scaleDelta;
            setDrawSave();
        }
    }

    function touchEnd(e) {
        // if the user lifts one or more fingers, remove them from the touch list
        e.preventDefault();
        const touches = e.changedTouches;
        for (let i = 0; i < touches.length; i++) {
            // count backwards so list indexes don't change when there is a deletion
            for (let j = activeTouches.length - 1; j >= 0; j--) {
                if (activeTouches[j].identifier == touches[i].identifier) {
                    activeTouches.splice(j, 1);
                }
            }
        }
    }

    imageDiv.addEventListener("touchstart", touchStart);
    imageDiv.addEventListener("touchend", touchEnd);
    imageDiv.addEventListener("touchcancel", touchEnd);
    imageDiv.addEventListener("touchmove", touchMove);

    // end of touch section

    const controlBar = document.createElement("div");
    controlBar.classList.add("simple_bar", "top_settings_box");
    controlBar.append(fitHeightButton, fitWidthButton, percentInput, "%", zoomInButton, zoomOutButton, clockRotateButton, counterClockRotateButton);
    container.append(controlBar, content);

    // when split dirn. changes reset so that the following resize
    // will also do a scroll re-center
    function reset() {
        unset = true;
    }

    function initAll() {
        reset();
        setImageStyle();
    }

    image.addEventListener("load", initAll);

    return {
        setImage: function (src) {
            sine = 0;
            cosine = 1;
            image.src = src;
        },
        content,
        controlBar,
        reSize: setImageStyle,
        reset,
    };
}

export function makeProofImageWidget(container, userSettings) {
    const { setImage, content, controlBar, reSize, reset } = makeImageWidget(container, userSettings);

    const scrollWithTextBox = makeCheckBox();
    const scrollControl = makeLabel([scrollWithTextBox, translate.gettext("Scroll with Text")]);
    controlBar.append(scrollControl);

    userSettings.scrollWithText ?? (userSettings.scrollWithText = false);
    scrollWithTextBox.checked = userSettings.scrollWithText;
    scrollWithTextBox.addEventListener("change", function () {
        userSettings.scrollWithText = this.checked;
    });

    return {
        setImage: setImage,

        setScroll: function (delta) {
            if (userSettings.scrollWithText) {
                content.scrollTop += delta;
            }
        },
        reSize,
        reset,
    };
}
