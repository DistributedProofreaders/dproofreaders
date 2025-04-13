/* global makeLabel makeCheckBox */
/* exported makeImageWidget */
/* exported makeProofImageWidget*/

function makeImageWidget(container, userSettings, widgetText) {
    const content = document.createElement("div");
    const left = document.createElement("div");
    const centre = document.createElement("div");
    const right = document.createElement("div");
    content.append(left, centre, right);
    content.classList.add("row_flex");
    left.style.width = "0px";
    centre.classList.add("overflow-auto");

    content.id = "image_content";
    content.classList.add("stretch-box", "center-align");
    const grabCursor = "grab";
    // use plain js image so width or style.width is clearly differentiated
    const image = document.createElement("img");
    image.classList.add("middle-align");

    // When the image is rotated it has width and height as if it were not
    // rotated. To make scroll work correctly, enclose it in a div with the
    //actual width and height.
    const imageDiv = document.createElement("div");
    imageDiv.classList.add("middle-align", "left-align", "overflow-hidden");
    imageDiv.style.cursor = grabCursor;
    imageDiv.style.display = "inline-block";
    imageDiv.appendChild(image);

    centre.append(imageDiv);
    let scrollDiffX = 0;
    let scrollDiffY = 0;
    let pageX0;
    let leftWidth;

    function dragMove(event) {
        centre.scrollTop = scrollDiffY - event.pageY;
//        let reqScroll = scrollDiffX - event.pageX;
//        centre.scrollLeft = reqScroll;

        // if drag right then scroll to right then increase left div width
        let rightDrag = event.pageX - pageX0;
//        console.log(rightDrag, centre.scrollLeft);
        if(rightDrag > 0) {
            let scrollLeft = lScroll0 - rightDrag;
            if (scrollLeft > 0) {
                centre.scrollLeft = scrollLeft;
            } else {
                console.log(leftWidth, scrollLeft);
                left.style.flex =  `0 0 ${leftWidth - scrollLeft}px`;
            }
        } else {
            // drag left: reduce left div to 0 then scroll
            let newLeftWidth = leftWidth + rightDrag;
            if (newLeftWidth > 0) {
                left.style.flex =  `0 0 ${newLeftWidth}px`;
            } else {
                centre.scrollLeft = -newLeftWidth;
            }
        }
//        centre.scrollLeft = scrollDiffX - event.pageX;//(scrollDiffX - event.pageX);
/*        if(reqScroll < 0) {
            console.log(reqScroll);
            left.style.width = `${-reqScroll}px`;
        }*/
    }

    function mouseUp() {
        document.removeEventListener("mousemove", dragMove);
        document.removeEventListener("mouseup", mouseUp);
        imageDiv.style.cursor = grabCursor;
    }

    function dragStart(event) {
        pageX0 = event.pageX;
        leftWidth = left.clientWidth;
        lScroll0 = centre.scrollLeft;
//        console.log(leftWidth);
        scrollDiffX = event.pageX + centre.scrollLeft;
        scrollDiffY = event.pageY + centre.scrollTop;
    }

    imageDiv.addEventListener("mousedown", function (event) {
        event.preventDefault();
        imageDiv.style.cursor = "grabbing";
        dragStart(event);
        document.addEventListener("mousemove", dragMove);
        document.addEventListener("mouseup", mouseUp);
    });

    function dragTouchMove(event) {
        dragMove(event.touches[0]);
    }

    function dragTouchEnd() {
        document.removeEventListener("touchmove", dragTouchMove);
        document.removeEventListener("touchend", dragTouchEnd);
    }

    imageDiv.addEventListener("touchstart", function (event) {
        event.preventDefault();
        dragStart(event.touches[0]);
        document.addEventListener("touchmove", dragTouchMove);
        document.addEventListener("touchend", dragTouchEnd);
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

    function setImageStyle() {
        // when this is called in 'setImage' we will not know the dimensions
        // of the image but it is not rotated (sine=0) so we do not need to.
        let offset;
        image.style.width = `${10 * percent}px`;
        image.style.height = "auto";
        if (sine != 0) {
            // rotated 90 or 270 degrees
            imageDiv.style.height = `${image.width}px`;
            imageDiv.style.width = `${image.height}px`;
            offset = (image.height - image.width) / 2;
        } else {
            // rotated 0 or 180 degrees
            imageDiv.style.height = "auto";
            imageDiv.style.width = "auto";
            offset = 0;
        }
        // image rotates about centre. Offset moves it to correct position
        image.style.transform = `matrix(${cosine}, ${-sine}, ${sine}, ${cosine}, ${offset}, ${-offset})`;
    }

    const percentInput = document.createElement("input");
    percentInput.type = "number";
    percentInput.classList.add("text_number");
    percentInput.value = percent;
    percentInput.title = widgetText.zoomPercent;

    function setZoom() {
        if (percent < minPercent) {
            percent = minPercent;
        } else if (percent > maxPercent) {
            percent = maxPercent;
        }
        percentInput.value = Math.round(percent);
        setImageStyle();
    }

    function setDrawSave() {
        setZoom();
        userSettings.zoomPercent = percent;
    }

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

    const fitHeightButton = makeImageButtton(widgetText.fitHeight, "fas fa-arrows-alt-v");
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
    });

    const fitWidthButton = makeImageButtton(widgetText.fitWidth, "fas fa-arrows-alt-h");
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
    });

    const zoomInButton = makeImageButtton(widgetText.zoomIn, "fas fa-search-plus");
    zoomInButton.addEventListener("click", function () {
        percent *= 1.1;
        setDrawSave();
    });

    const zoomOutButton = makeImageButtton(widgetText.zoomOut, "fas fa-search-minus");
    zoomOutButton.addEventListener("click", function () {
        percent /= 1.1;
        setDrawSave();
    });

    const clockRotateButton = makeImageButtton(widgetText.clockRotate, "fas fa-redo-alt");
    clockRotateButton.addEventListener("click", function () {
        [sine, cosine] = [-cosine, sine];
        setImageStyle();
    });

    const counterClockRotateButton = makeImageButtton(widgetText.counterclockRotate, "fas fa-undo-alt");
    counterClockRotateButton.addEventListener("click", function () {
        [sine, cosine] = [cosine, -sine];
        setImageStyle();
    });

    percent = userSettings.zoomPercent ?? 100;
    setZoom();

    const controlBar = document.createElement("div");
    controlBar.classList.add("simple_bar", "top_settings_box");
    controlBar.append(fitHeightButton, fitWidthButton, percentInput, "%", zoomInButton, zoomOutButton, clockRotateButton, counterClockRotateButton);
    container.append(controlBar, content);

    return {
        setImage: function (src) {
            sine = 0;
            cosine = 1;
            image.src = src;
            setImageStyle();
            // reset scroll to top left
            content.scrollTop = 0;
            content.scrollLeft = 0;
        },
        content,
        controlBar,
    };
}

function makeProofImageWidget(container, userSettings, widgetText, proofText) {
    const { setImage, content, controlBar } = makeImageWidget(container, userSettings, widgetText);

    const scrollWithTextBox = makeCheckBox();
    const scrollControl = makeLabel([scrollWithTextBox, proofText.scrollWithText]);
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
    };
}
