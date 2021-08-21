/*global $ texts */
/* exported makeImageWidget */

// Construct the image sizing controls.
var makeImageControl = function(imageElement) {
    let imageKey;
    let percent;
    const percentInput = $("<input>", {type: 'number', min: '1', max: '999', value: percent, title: texts.zoomPercent});

    function setZoom() {
        imageElement.width(10 * percent);
        imageElement.height("auto");
    }

    function saveZoom() {
        localStorage.setItem(imageKey, JSON.stringify({zoom: percent}));
    }

    percentInput.change(function() {
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
        setZoom();
        saveZoom();
    });

    function setPercent() {
        percent = Math.round(percent);
        percentInput.val(percent);
        saveZoom();
    }

    function unPersist() {
        // reset width and height so that fitting does not persist
        const width = imageElement.width();
        imageElement.width(width);
        imageElement.height("auto");
        // assume 100% means 1000px wide
        percent = width / 10;
        setPercent();
    }

    const fitWidth = $("<button>", {title: texts.fitWidth}).click(function () {
        imageElement.width('100%');
        unPersist();
    })
        .append($("<i>", {class: 'fas fa-arrows-alt-h'}));

    const fitHeight = $("<button>", {title: texts.fitHeight}).click(function () {
        imageElement.height(imageElement.parent().height());
        imageElement.width("auto");
        unPersist();
    })
        .append($("<i>", {class: 'fas fa-arrows-alt-v'}));

    const zoomIn = $("<button>", {title: texts.zoomIn}).click(function () {
        percent *= 1.1;
        setPercent();
        setZoom();
    })
        .append($("<i>", {class: 'fas fa-search-plus'}));

    const zoomOut = $("<button>", {title: texts.zoomOut}).click(function () {
        percent *= 0.909;
        setPercent();
        setZoom();
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
            zoomOut
        ],
        setup: function(storageKey) {
            imageKey = storageKey + "-image";
            let imageData = JSON.parse(localStorage.getItem(imageKey));
            if(!$.isPlainObject(imageData)) {
                imageData = {zoom: 100};
            }
            percent = imageData.zoom;
            percentInput.val(percent);
            setZoom();
        },
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

function makeImageWidget(container, align = "C") {
    const alignment = {
        L: "left",
        C: "center",
        R: "right"
    };
    const imageElement = $("<img>", {class: "middle-align"}).css("cursor", "grab");
    const imageControl = makeImageControl(imageElement);
    const controlDiv = makeControlDiv(container, imageControl.controls);

    controlDiv.content.css("text-align", alignment[align]).append(imageElement);

    let scrollDiffX = 0;
    let scrollDiffY = 0;
    function mousemove(event) {
        controlDiv.content.scrollTop(scrollDiffY - event.pageY);
        controlDiv.content.scrollLeft(scrollDiffX - event.pageX);
    }

    function mouseup() {
        $(document).unbind("mousemove mouseup");
        imageElement.css("cursor", "grab");
    }

    imageElement.mousedown( function(event) {
        event.preventDefault();
        imageElement.css("cursor", "grabbing");
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
            imageElement.attr("src", src);
            controlDiv.content
                .scrollTop(0)
                .scrollLeft(0);
        }
    };
}
