/*global $ texts */
/* exported makeImageWidget */

// Construct the image sizing controls.
var imageControl = function(imageElement, storageKey) {
    let imageKey = storageKey + "-image";
    let imageData = JSON.parse(localStorage.getItem(imageKey));
    if(!$.isPlainObject(imageData)) {
        imageData = {zoom: 100};
    }
    let percent = imageData.zoom;

    let percentInput = $("<input>", {type: 'number', min: '1', max: '999', value: percent, title: texts.zoomPercent});

    let setZoom = function () {
        imageElement.css({"vertical-align": "middle"});
        imageElement.width(10 * percent);
        imageElement.height("auto");
    };

    function saveZoom() {
        imageData.zoom = percent;
        localStorage.setItem(imageKey, JSON.stringify(imageData));
    }

    percentInput.change(function() {
        let value = parseInt(this.value);
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
        let width = imageElement.width();
        imageElement.width(width);
        imageElement.height("auto");
        // assume 100% means 1000px wide
        percent = width / 10;
        setPercent();
    }

    let fitWidth = $("<button>", {title: texts.fitWidth}).click(function () {
        imageElement.width('100%');
        unPersist();
    })
        .append($("<i>", {class: 'fas fa-arrows-alt-h'}));

    let fitHeight = $("<button>", {title: texts.fitHeight}).click(function () {
        imageElement.height('100%');
        imageElement.width("auto");
        unPersist();
    })
        .append($("<i>", {class: 'fas fa-arrows-alt-v'}));

    let zoomIn = $("<button>", {title: texts.zoomIn}).click(function () {
        percent *= 1.1;
        setPercent();
        setZoom();
    })
        .append($("<i>", {class: 'fas fa-search-plus'}));

    let zoomOut = $("<button>", {title: texts.zoomOut}).click(function () {
        percent *= 0.909;
        setPercent();
        setZoom();
    })
        .append($("<i>", {class: 'fas fa-search-minus'}));

    setZoom();
    return [
        fitHeight,
        fitWidth,
        " ",
        $("<span>", {class: "nowrap"}).append(percentInput, "%"),
        " ",
        zoomIn,
        zoomOut
    ];
};

function makeControlDiv(container, controls, storageKey, onChange) {
    let barKey = storageKey + "-bar";
    let barData = JSON.parse(localStorage.getItem(barKey));

    // location is two letters, 1st position of bar: North East South West,
    // 2nd controls within it Begin Middle End
    if(!$.isPlainObject(barData)) {
        barData = {location: "NM"};
    }
    let point = barData.location[0];
    let begMidEnd = barData.location[1];

    function saveLocation() {
        barData.location = point + begMidEnd;
        localStorage.setItem(barKey, JSON.stringify(barData));
    }

    let content = $("<div>", {class: 'overflow-auto'}).css({flex: 'auto'});
    container.css({display: 'flex', height: "100%"});
    container.append(content);

    let controlBar = $("<div>", {class: 'page-interface control-panel'});
    // control2 contains the controls, control1 & control3 adjust the layout
    let control1 = $("<div>");
    let control2 = $("<div>").css({flex: '0 1 auto'});
    let control3 = $("<div>");
    controlBar.append(control1, control2, control3);

    let menu = $("<div>", {class: "nav-menu"});

    let menuButton = $("<button>", {title: texts.adjustPanel})
        .append($("<i>", {class: 'fas fa-cog'}))
        .click(function () {
            menu.toggle();
        });

    // build navBox
    let navBox = $("<table>");
    let navCell = [];
    let cellIndex = 0;
    for(let rowIndex = 0; rowIndex < 3; rowIndex++) {
        let row = $("<tr>");
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

    let leftButton = $("<button>", {class: 'navbutton', title: texts.controlLeft})
        .append($("<i>", {class: 'fas fa-caret-left'}));
    let centreButton = $("<button>", {class: 'navbutton', title: texts.controlMid}).append('|');
    let rightButton = $("<button>", {class: 'navbutton', title: texts.controlRight})
        .append($("<i>", {class: 'fas fa-caret-right'}));
    let topButton = $("<button>", {class: 'navbutton', title: texts.controlTop})
        .append($("<i>", {class: 'fas fa-caret-up'}));
    let midButton = $("<button>", {class: 'navbutton', title: texts.controlMid}).append('−');
    let botButton = $("<button>", {class: 'navbutton', title: texts.controlBot})
        .append($("<i>", {class: 'fas fa-caret-down'}));

    let westButton = $("<button>", {class: 'navbutton', title: texts.dockLeft})
        .append($("<i>", {class: 'fas fa-arrow-left'}));
    let northButton = $("<button>", {class: 'navbutton', title: texts.dockTop})
        .append($("<i>", {class: 'fas fa-arrow-up'}));
    let southButton = $("<button>", {class: 'navbutton', title: texts.dockBot})
        .append($("<i>", {class: 'fas fa-arrow-down'}));
    let eastButton = $("<button>", {class: 'navbutton', title: texts.dockRight})
        .append($("<i>", {class: 'fas fa-arrow-right'}));

    let hideButton = $("<button>", {class: 'navbutton', title: texts.hideMenu}).append('×');

    menu.append(navBox);
    let menuHolder = $("<div>").css({position: "relative"})
        .append(menu);
    control1.append($("<div>", {class: "condiv"}).append(menuButton), menuHolder);

    function navigate() {
        $(".navbutton", navBox).detach();
        switch(point) {
        case "N":
            controlFirst();
            controlHoriz();
            controlBar.css({borderWidth: "0 0 1px 0"});
            menu.css({top: "0.21em", left: "", right: "", bottom: ""});
            fillNavBox([leftButton, centreButton, rightButton, westButton, hideButton, eastButton, "", southButton, ""]);
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
            fillNavBox(["", northButton, "", westButton, hideButton, eastButton, leftButton, centreButton, rightButton]);
            break;
        }
        if(onChange) {
            onChange.fire();
        }
    }

    function navStyle() {
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
        point = newP;
        saveLocation();
        navigate();
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
        navStyle();
        menu.hide();
    }

    leftButton.click(() => {
        newBME('B');
    });

    centreButton.click(() => {
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

    navigate();
    navStyle();

    return {
        content: content
    };
}

function makeImageWidget(container, storageKey) {
    let imageElement = $("<img>");
    let controls = imageControl(imageElement, storageKey);
    let controlDiv = makeControlDiv(container, controls, storageKey);
    controlDiv.content.addClass("center-align").append(imageElement);
    return {
        setImage: function (src) {
            imageElement.attr("src", src);
            controlDiv.content
                .scrollTop(0)
                .scrollLeft(0);
        }
    };
}
