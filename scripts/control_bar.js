/*global $ texts */
/* exported makeImageWidget */

// Construct the image sizing controls.
var makeImageControl = function(canvas, align, reSize) {
    let imageKey;
    let scale = 1;
    let imageWidth, imageHeight;
    const image = document.createElement("img");
    let ctx = canvas.getContext("2d");

    const percentInput = $("<input>", {type: 'number', min: '1', max: '999', value: 100, title: texts.zoomPercent});

    let sine = 0, cosine = 1;

    let scrollbarWidth = false;
    var getScrollbarWidth = function() {
        if (scrollbarWidth === false) {
            let testDiv = document.createElement('div');
            testDiv.innerHTML = '<div style="width:50px;height:5px;position:absolute; overflow:scroll;"><div style="width:100px;height:5px;"></div></div>';
            let innerDiv = testDiv.firstChild;
            document.body.appendChild(testDiv);
            scrollbarWidth = innerDiv.offsetWidth - innerDiv.clientWidth;
            document.body.removeChild(testDiv);
        }
        return scrollbarWidth;
    };

    // If we always make the canvas fit image then when reducing the image size
    // rapidly with spinner the canvas does not get cleared even when
    // redrawing with setTimer(0).
    // So, make canvas dimension fit image dimension only if it is larger
    // than the pane. A scroll bar will then appear. Otherwise make canvas
    // dimension fill pane. Canvas now always clears.
    // This introduces a new problem: if the image is smaller than the pane
    // and the pane size decreases it is possible to scroll the image out
    // of view. So redraw when pane size changes.
    // This would be easy if we could use ResizeObserver(). In the meantime
    // use a resize callback which gets fired when pane size changes.
    function drawImage() {
        getScrollbarWidth();
        // clearRect acts through transform
        ctx.resetTransform();
        ctx.clearRect( 0, 0, canvas.width, canvas.height);
        let scaleWidth = imageWidth * scale;
        let scaleHeight = imageHeight * scale;
        let rotatedWidth, rotatedHeight;
        if(cosine != 0) {
            // 0 or 180
            rotatedWidth = scaleWidth;
            rotatedHeight = scaleHeight;
        } else {
            // +- 90
            rotatedWidth = scaleHeight;
            rotatedHeight = scaleWidth;
        }
        // we have to calculate what will happen if scrollbars appear
        let holder = canvas.parentNode;
        let paneWidth = holder.offsetWidth;
        let paneHeight = holder.offsetHeight;
        if(rotatedHeight > paneHeight) {
            paneWidth = holder.offsetWidth - scrollbarWidth;
        }
        let underWidth = paneWidth - rotatedWidth;
        if(underWidth < 0) {
            // image is wider than pane
            paneHeight = holder.offsetHeight - scrollbarWidth;
            canvas.width = rotatedWidth;
        } else {
            canvas.width = paneWidth;
        }
        if(rotatedHeight > paneHeight) {
            paneWidth = holder.offsetWidth - scrollbarWidth;
            canvas.height = rotatedHeight;
        } else {
            canvas.height = paneHeight;
        }

        // rotation is about point (0,0); offset so image is in canvas area
        let xOff = 0;
        let yOff = 0;
        if(cosine == -1) {
            // 180 deg
            xOff = scaleWidth;
            yOff = scaleHeight;
        } else if(sine == 1) {
            // 90 deg
            yOff = scaleWidth;
        } else if(sine == -1) {
            // -90 deg
            xOff = scaleHeight;
        }

        let scaleCosine = cosine * scale;
        let scaleSine = sine * scale;

        // image origin in canvas
        let dx = 0;
        if(underWidth > 0) {
            if(align == "C") {
                dx = underWidth / 2;
            } else if (align == "R") {
                dx = underWidth;
            }
        }

        ctx.setTransform(scaleCosine, -scaleSine, scaleSine, scaleCosine, xOff + dx, yOff);
        ctx.drawImage(image, 0, 0);
    }

    if(reSize) {
        reSize.add(drawImage);
    }

    function clearCanvas() {
        ctx.resetTransform();
        ctx.clearRect( 0, 0, canvas.width, canvas.height);
    }

    image.onload = function() {
        clearCanvas();
        imageWidth = image.width;
        imageHeight = image.height;
        drawImage();
    };

    function saveZoom(percent) {
        localStorage.setItem(imageKey, JSON.stringify({zoom: percent}));
    }

    percentInput.change(function() {
        let percent;
        const value = parseInt(this.value);
        if(isNaN(value)) {
            percent = 100;
        } else if(value < 10) {
            percent = 10;
        } else if(value > 999) {
            percent = 999;
        } else {
            percent = value;
        }
        // in case above has changed it
        this.value = percent;
        scale = percent / 100;
        drawImage();
        saveZoom(percent);
    });

    function setPercent() {
        let percent = Math.round(100 * scale);
        percentInput.val(percent);
        saveZoom(percent);
    }

    let rotatedImageWidth;
    let rotatedImageHeight;
    function rotationCompensate() {
        if(sine == 0) {
            rotatedImageWidth = imageWidth;
            rotatedImageHeight = imageHeight;
        } else {
            rotatedImageWidth = imageHeight;
            rotatedImageHeight = imageWidth;
        }
    }


    const fitWidth = $("<button>", {title: texts.fitWidth}).click(function () {
        let holder = canvas.parentNode;
        rotationCompensate();
        scale = holder.offsetWidth / rotatedImageWidth;
        // does fitting rotatedImageWidth cause a vertical scrollbar?
        if(scale * rotatedImageHeight > holder.offsetHeight) {
            // there would be a vertical scrollbar, fit inside
            scale = (holder.offsetWidth - scrollbarWidth) / rotatedImageWidth;
            // rotatedImageHeight will be less so might not need a vertical scrollbar
            if(scale * rotatedImageHeight < holder.offsetHeight) {
                // best option is to fit rotatedImageHeight
                scale = holder.offsetHeight / rotatedImageHeight;
            }
        }
        setPercent();
        drawImage();
    })
        .append($("<i>", {class: 'fas fa-arrows-alt-h'}));

    const fitHeight = $("<button>", {title: texts.fitHeight}).click(function () {
        let holder = canvas.parentNode;
        rotationCompensate();
        scale = holder.offsetHeight / rotatedImageHeight;
        // does fitting rotatedImageHeight cause a horizontal scrollbar?
        if(scale * rotatedImageWidth > holder.offsetWidth) {
            // there would be a vertical scrollbar, fit inside
            scale = (holder.offsetHeight - scrollbarWidth) / rotatedImageHeight;
            // rotatedImageWidth will be less so might not need a horizontal scrollbar
            if(scale * rotatedImageWidth < holder.offsetWidth) {
                // best option is to fit rotatedImageWidth
                scale = holder.offsetWidth / rotatedImageWidth;
            }
        }
        setPercent();
        drawImage();
    })
        .append($("<i>", {class: 'fas fa-arrows-alt-v'}));

    let clockRotateInput = $("<button>", {title: texts.clockRotate})
        .append($("<i>", {class: 'fas fa-redo-alt'}))
        .click( function () {
            let newCos = sine;
            sine = -cosine;
            cosine = newCos;
            drawImage();
        });

    let anticlockRotateInput = $("<button>", {title: texts.anticlockRotate})
        .append($("<i>", {class: 'fas fa-undo-alt'}))
        .click( function () {
            let newCos = -sine;
            sine = cosine;
            cosine = newCos;
            drawImage();
        });

    const zoomIn = $("<button>", {title: texts.zoomIn}).click(function () {
        scale *= 1.1;
        setPercent();
        drawImage();
    })
        .append($("<i>", {class: 'fas fa-search-plus'}));

    const zoomOut = $("<button>", {title: texts.zoomOut}).click(function () {
        scale /= 1.1;
        setPercent();
        drawImage();
    })
        .append($("<i>", {class: 'fas fa-search-minus'}));

    return {
        controls: [
            fitHeight,
            fitWidth,
            " ",
            $("<span>", {class: "nowrap"}).append(percentInput, "%"),
            " ",
            zoomIn,
            zoomOut,
            clockRotateInput,
            anticlockRotateInput
        ],
        setup: function(storageKey) {
            imageKey = storageKey + "-image";
            let imageData = JSON.parse(localStorage.getItem(imageKey));
            if(!$.isPlainObject(imageData)) {
                imageData = {zoom: 100};
            }
            let percent = imageData.zoom;
            percentInput.val(percent);
            scale = percent / 100;
            drawImage();
        },
        setImage: function(src) {
            // reset to normal orientation
            sine = 0;
            cosine = 1;
            image.src = src;
        }
    };
};

function makeControlDiv(container, controls, onChange) {
    let barKey;
    let compassPoint;
    let begMidEnd;

    function saveLocation() {
        const barData = {location: compassPoint + begMidEnd};
        localStorage.setItem(barKey, JSON.stringify(barData));
    }

    const content = $("<div>", {class: 'overflow-auto'}).css({flex: 'auto'});
    container.css({display: 'flex', height: "100%"});
    container.append(content);

    const controlBar = $("<div>", {class: 'page-interface control-bar'});
    // control2 contains the controls, control1 & control3 adjust the layout
    const control1 = $("<div>", {class: 'left-align'});
    const control2 = $("<div>").css({flex: '0 1 auto'});
    const control3 = $("<div>");
    controlBar.append(control1, control2, control3);

    const menu = $("<div>", {class: "control-bar-menu"});

    const menuButton = $("<button>", {title: texts.adjustPanel})
        .append($("<i>", {class: 'fas fa-cog'}))
        .click(function () {
            menu.toggle();
        });

    // build navBox
    const navBox = $("<table>");
    const navCell = [];
    let cellIndex = 0;
    for(let rowIndex = 0; rowIndex < 3; rowIndex++) {
        const row = $("<tr>");
        navBox.append(row);
        for(let columnIndex = 0; columnIndex < 3; columnIndex++) {
            navCell[cellIndex] = $("<td>");
            row.append(navCell[cellIndex]);
            cellIndex += 1;
        }
    }

    function fillNavBox(cells) {
        for(let cellIndex = 0; cellIndex < 9; cellIndex++) {
            navCell[cellIndex].append(cells[cellIndex]);
        }
    }

    function controlFirst() {
        controlBar.detach();
        container.prepend(controlBar);
    }

    function controlLast() {
        controlBar.detach();
        container.append(controlBar);
    }

    function controlHoriz() {
        container.css({flexDirection: 'column'});
        // controlBar so only our's if there are others
        $(".condiv", controlBar).css({display: "inline", padding: "0 0.1em"});
        controlBar.css({"text-align": "", "flex-direction": "row"});
    }

    function controlVert() {
        container.css({flexDirection: 'row'});
        $(".condiv", controlBar).css({display: "block", padding: "0.1em 0"});
        controlBar.css({"text-align": "center", "flex-direction": "column"});
    }

    const leftButton = $("<button>", {class: 'navbutton', title: texts.controlLeft})
        .append($("<i>", {class: 'fas fa-caret-left'}));
    const centerButton = $("<button>", {class: 'navbutton', title: texts.controlCenter}).append('|');
    const rightButton = $("<button>", {class: 'navbutton', title: texts.controlRight})
        .append($("<i>", {class: 'fas fa-caret-right'}));
    const topButton = $("<button>", {class: 'navbutton', title: texts.controlTop})
        .append($("<i>", {class: 'fas fa-caret-up'}));
    const midButton = $("<button>", {class: 'navbutton', title: texts.controlMid}).append('−');
    const botButton = $("<button>", {class: 'navbutton', title: texts.controlBot})
        .append($("<i>", {class: 'fas fa-caret-down'}));

    const westButton = $("<button>", {class: 'navbutton', title: texts.dockLeft})
        .append($("<i>", {class: 'fas fa-arrow-left'}));
    const northButton = $("<button>", {class: 'navbutton', title: texts.dockTop})
        .append($("<i>", {class: 'fas fa-arrow-up'}));
    const southButton = $("<button>", {class: 'navbutton', title: texts.dockBot})
        .append($("<i>", {class: 'fas fa-arrow-down'}));
    const eastButton = $("<button>", {class: 'navbutton', title: texts.dockRight})
        .append($("<i>", {class: 'fas fa-arrow-right'}));

    const hideButton = $("<button>", {class: 'navbutton', title: texts.hideMenu}).append('×');

    menu.append(navBox);
    const menuHolder = $("<div>").css({position: "relative"})
        .append(menu);
    control1.append($("<div>", {class: "condiv center-align"}).append(menuButton), menuHolder);

    function setCompassPoint() {
        $(".navbutton", navBox).detach();
        switch(compassPoint) {
        case "N":
            controlFirst();
            controlHoriz();
            controlBar.css({borderWidth: "0 0 1px 0"});
            menu.css({top: "0.21em", left: "", right: "", bottom: ""});
            fillNavBox([leftButton, centerButton, rightButton, westButton, hideButton, eastButton, "", southButton, ""]);
            break;
        case "W":
            controlFirst();
            controlVert();
            controlBar.css({borderWidth: "0 1px 0 0"});
            menu.css({top: "", left: "3em", right: "", bottom: ""});
            fillNavBox([topButton, northButton, "", midButton, hideButton, eastButton, botButton, southButton, ""]);
            break;
        case "E":
            controlLast();
            controlVert();
            controlBar.css({borderWidth: "0 0 0 1px"});
            menu.css({top: "", left: "", right: "3em", bottom: ""});
            fillNavBox(["", northButton, topButton, westButton, hideButton, midButton, "", southButton, botButton]);
            break;
        case "S":
            controlLast();
            controlHoriz();
            controlBar.css({borderWidth: "1px 0 0 0"});
            menu.css({top: "", left: "", right: "", bottom: "2em"});
            fillNavBox(["", northButton, "", westButton, hideButton, eastButton, leftButton, centerButton, rightButton]);
            break;
        }
        if(onChange) {
            onChange.fire();
        }
    }

    function setBegMidEnd() {
        switch(begMidEnd) {
        case "B":
            control1.css({flex: '0 0 auto'});
            control3.css({flex: '1 0 auto'});
            break;
        case "M":
            control1.css({flex: '1 0 auto'});
            control3.css({flex: '1 0 auto'});
            break;
        case "E":
            control1.css({flex: '1 0 auto'});
            control3.css({flex: '0 0 auto'});
            break;
        }
    }

    hideButton.click(() => {
        menu.hide();
    });

    function newPoint(newP) {
        compassPoint = newP;
        saveLocation();
        setCompassPoint();
        menu.hide();
    }

    northButton.click(() => {
        newPoint("N");
    });

    southButton.click(() =>{
        newPoint("S");
    });

    westButton.click(() => {
        newPoint("W");
    });

    eastButton.click(() => {
        newPoint("E");
    });

    function newBME(newBME) {
        begMidEnd = newBME;
        saveLocation();
        setBegMidEnd();
        menu.hide();
    }

    leftButton.click(() => {
        newBME('B');
    });

    centerButton.click(() => {
        newBME('M');
    });

    rightButton.click(() => {
        newBME('E');
    });

    topButton.click(() => {
        newBME('B');
    });

    midButton.click(() => {
        newBME('M');
    });

    botButton.click(() => {
        newBME('E');
    });

    controls.forEach(function(control) {
        control2.append($("<div>").addClass("condiv")
            .append(control));
    });

    return {
        content: content,

        setupControls: function (storageKey) {
            barKey = storageKey + "-bar";
            let barData = JSON.parse(localStorage.getItem(barKey));
            if(!$.isPlainObject(barData)) {
                barData = {location: "NM"};
            }
            // location is two letters:
            // 1st compass point of bar: North East South West,
            // 2nd controls position within it: Begin Middle End
            compassPoint = barData.location[0];
            begMidEnd = barData.location[1];
            setCompassPoint();
            setBegMidEnd();
        },
    };
}

function makeImageWidget(container, align = "C", reSize = null) {
    // if we are inside a splitter then reSize will fire when pane size
    // changes.Otherwise fire on window reSize. It will also fire when the
    // control bar changes position.
    if(!reSize) {
        reSize = $.Callbacks();
        $(window).resize(function () {
            reSize.fire();
        });
    }

    const canvas = document.createElement("canvas");
    // this avoids the extra space for descender for inline element
    canvas.classList.add("middle-align");
    canvas.style.cursor = "grab";
    const imageControl = makeImageControl(canvas, align, reSize);

    const controlDiv = makeControlDiv(container, imageControl.controls, reSize);

    controlDiv.content.append(canvas);

    let scrollDiffX = 0;
    let scrollDiffY = 0;
    function mousemove(event) {
        controlDiv.content.scrollTop(scrollDiffY - event.pageY);
        controlDiv.content.scrollLeft(scrollDiffX - event.pageX);
    }

    function mouseup() {
        $(document).unbind("mousemove mouseup");
        canvas.style.cursor = "grab";
    }

    $(canvas).mousedown( function(event) {
        event.preventDefault();
        canvas.style.cursor = "grabbing";
        scrollDiffX = event.pageX + controlDiv.content.scrollLeft();
        scrollDiffY = event.pageY + controlDiv.content.scrollTop();
        $(document).on("mousemove", mousemove)
            .on("mouseup", mouseup);
    });

    return {
        setup: function (storageKey) {
            const imageWidgetKey = storageKey + "-imagewidget";
            imageControl.setup(imageWidgetKey);
            controlDiv.setupControls(imageWidgetKey);
        },

        setImage: function (src) {
            imageControl.setImage(src);
            // reset scroll to top left
            controlDiv.content
                .scrollTop(0)
                .scrollLeft(0);
        }
    };
}
