/*global $ texts */
/* exported makeImageWidget */

// Construct the image sizing controls.
var makeImageControl = function(content) {
    const imageCursor = "grab";
    // use plain js image so width or style.width is clear
    const image = document.createElement("img");
    image.classList.add("middle-align");
    image.style.cursor = imageCursor;
    // When the image is rotated it has width and height as if it were not
    // rotated. To make scroll work correctly, enclose it in a div with actual
    // width and height.
    const imageDiv = $("<div>", {class: "middle-align left-align"}).css({overflow: "hidden", display: "inline-block"});
    content.append(imageDiv);
    imageDiv.append(image);

    let scrollDiffX = 0;
    let scrollDiffY = 0;
    function mousemove(event) {
        content.scrollTop(scrollDiffY - event.pageY);
        content.scrollLeft(scrollDiffX - event.pageX);
    }

    function mouseup() {
        $(document).unbind("mousemove mouseup");
        image.style.cursor = imageCursor;
    }

    $(image).mousedown( function(event) {
        event.preventDefault();
        image.style.cursor = "grabbing";
        scrollDiffX = event.pageX + content.scrollLeft();
        scrollDiffY = event.pageY + content.scrollTop();
        $(document).on("mousemove", mousemove)
            .on("mouseup", mouseup);
    });

    let imageKey;
    // percent need not be an integer but is rounded for display and save
    // it will typically not be an integer after fit height or width or + or -
    let percent;
    const minPercent = 10;
    const maxPercent = 999;
    const defaultPercent = 100;
    let sine = 0;
    let cosine = 1;

    const percentInput = $("<input>", {type: 'number', value: percent, title: texts.zoomPercent});

    function setImageStyle() {
        // when this is called in 'setImage' we will not know dimensions
        // but sine=0 so do not need to.
        let offset;
        image.style.width = `${10 * percent}px`;
        image.style.height = "auto";
        if(sine != 0) {
            imageDiv.height(image.width);
            imageDiv.width(image.height);
            offset = (image.height - image.width) / 2;
        } else {
            imageDiv.height("auto");
            imageDiv.width("auto");
            offset = 0;
        }
        // image rotates about centre. Offset moves it to correct position
        image.style.transform = `matrix(${cosine}, ${-sine}, ${sine}, ${cosine}, ${offset}, ${-offset})`;
    }

    function setZoom() {
        if(percent < minPercent) {
            percent = minPercent;
        } else if(percent > maxPercent) {
            percent = maxPercent;
        }
        percentInput.val(Math.round(percent));
    }

    function setDrawSave() {
        setZoom();
        localStorage.setItem(imageKey, JSON.stringify({zoom: percent}));
        setImageStyle();
    }

    percentInput.change(function() {
        percent = parseInt(this.value);
        if(isNaN(percent)) {
            percent = defaultPercent;
        }
        setDrawSave();
    });

    const fitWidth = $("<button>", {title: texts.fitWidth}).click(function () {
        const contentWidth = `${content.width()}px`;
        if(sine == 0) {
            image.style.width = contentWidth;
        } else {
            image.style.height = contentWidth;
            image.style.width = "auto";
        }
        percent = image.width / 10;
        setDrawSave();
    })
        .append($("<i>", {class: 'fas fa-arrows-alt-h'}));

    const fitHeight = $("<button>", {title: texts.fitHeight}).click(function () {
        const contentHeight = `${content.height()}px`;
        if(sine == 0) {
            image.style.height = contentHeight;
            image.style.width = "auto";
        } else {
            image.style.width = contentHeight;
        }
        percent = image.width / 10;
        setDrawSave();
    })
        .append($("<i>", {class: 'fas fa-arrows-alt-v'}));

    const zoomIn = $("<button>", {title: texts.zoomIn}).click(function () {
        percent *= 1.1;
        setDrawSave();
    })
        .append($("<i>", {class: 'fas fa-search-plus'}));

    const zoomOut = $("<button>", {title: texts.zoomOut}).click(function () {
        percent /= 1.1;
        setDrawSave();
    })
        .append($("<i>", {class: 'fas fa-search-minus'}));

    const clockRotateInput = $("<button>", {title: texts.clockRotate})
        .append($("<i>", {class: 'fas fa-redo-alt'}))
        .click( function () {
            [sine, cosine] = [-cosine, sine];
            setImageStyle();
        });

    const anticlockRotateInput = $("<button>", {title: texts.anticlockRotate})
        .append($("<i>", {class: 'fas fa-undo-alt'}))
        .click( function () {
            [sine, cosine] = [cosine, -sine];
            setImageStyle();
        });

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
            anticlockRotateInput,
        ],
        setup: function(storageKey) {
            imageKey = storageKey + "-image";
            let imageData = JSON.parse(localStorage.getItem(imageKey));
            if(!$.isPlainObject(imageData)) {
                imageData = {zoom: defaultPercent};
            }
            percent = imageData.zoom;
            setZoom();
        },
        setImage: function(src) {
            // reset to normal orientation
            sine = 0;
            cosine = 1;
            image.src = src;
            setImageStyle();
        }
    };
};

function makeControlDiv(container, content, controls, onChange) {
    let barKey;
    let compassPoint;
    let begMidEnd;

    function saveLocation() {
        const barData = {location: compassPoint + begMidEnd};
        localStorage.setItem(barKey, JSON.stringify(barData));
    }

    content.addClass('overflow-auto').css({flex: 'auto'});
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
            if(menu.is(":hidden")) {
                // find the position of the menu button and set position of
                // menu relative to it
                const menuWidth = menu.outerWidth();
                let buttonRect = menuButton[0].getBoundingClientRect();
                switch(compassPoint) {
                case "N":
                    menu.css({top: buttonRect.top, left: buttonRect.right});
                    break;
                case "W":
                    menu.css({top: buttonRect.top, left: buttonRect.right});
                    break;
                case "S":
                    menu.css({top: buttonRect.bottom - menuWidth, left: buttonRect.right});
                    break;
                default: // E
                    menu.css({top: buttonRect.top, left: buttonRect.left - menuWidth});
                    break;
                }
            }
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
        // this could be done more simply using prepend
        // but on Safari the tools disappear sometimes.
        content.detach();
        controlBar.detach();
        container.append(controlBar);
        container.append(content);
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
    control1.append($("<div>", {class: "condiv center-align"}).append(menuButton), menu);

    function setCompassPoint() {
        $(".navbutton", navBox).detach();
        switch(compassPoint) {
        case "N":
            controlFirst();
            controlHoriz();
            controlBar.css({borderWidth: "0 0 1px 0"});
            fillNavBox([leftButton, centerButton, rightButton, westButton, hideButton, eastButton, "", southButton, ""]);
            break;
        case "W":
            controlFirst();
            controlVert();
            controlBar.css({borderWidth: "0 1px 0 0"});
            fillNavBox([topButton, northButton, "", midButton, hideButton, eastButton, botButton, southButton, ""]);
            break;
        case "E":
            controlLast();
            controlVert();
            controlBar.css({borderWidth: "0 0 0 1px"});
            fillNavBox(["", northButton, topButton, westButton, hideButton, midButton, "", southButton, botButton]);
            break;
        case "S":
            controlLast();
            controlHoriz();
            controlBar.css({borderWidth: "1px 0 0 0"});
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

function makeImageWidget(container, align = "C") {
    const alignment = {
        L: "left",
        C: "center",
        R: "right"
    };
    const content = $("<div>").css("text-align", alignment[align]);
    const imageControl = makeImageControl(content);
    const controlDiv = makeControlDiv(container, content, imageControl.controls);

    return {
        setup: function (storageKey) {
            const imageWidgetKey = storageKey + "-imagewidget";
            imageControl.setup(imageWidgetKey);
            controlDiv.setupControls(imageWidgetKey);
        },

        setImage: function (src) {
            imageControl.setImage(src);
            // reset scroll to top left
            content
                .scrollTop(0)
                .scrollLeft(0);
        }
    };
}
