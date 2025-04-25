/* global makeLabel makeCheckBox */
/* exported makeImageWidget */
/* exported makeProofImageWidget*/

function makeImageWidget(container, userSettings, widgetText) {
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

    let contentWidth, contentHeight, imageWidth, imDivWidth, vertOffset;

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

        let imageHeight, xOffset, yOffset, imDivHeight;
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
    percentInput.title = widgetText.zoomPercent;

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
        initScroll();
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
        initScroll();
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
        initScroll();
    });

    const counterClockRotateButton = makeImageButtton(widgetText.counterclockRotate, "fas fa-undo-alt");
    counterClockRotateButton.addEventListener("click", function () {
        [sine, cosine] = [cosine, -sine];
        setImageStyle();
        initScroll();
    });

    percent = userSettings.zoomPercent ?? 100;
    setZoom();

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

function makeProofImageWidget(container, userSettings, widgetText, proofText) {
    const { setImage, content, controlBar, reSize, reset } = makeImageWidget(container, userSettings, widgetText);

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
        reSize,
        reset,
    };
}
