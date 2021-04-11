/*global $ */
/* exported makeImageControlPane */

// Construct the image sizing controls.
var imageControl = function(imageElement) {
    const imagePercentID = "image_percent";
    let percent = localStorage.getItem(imagePercentID);
    if(!percent) {
        percent = 100;
    }

    let percentInput = $("<input>", {type: 'number', min: '1', max: '999', value: percent});

    let setZoom = function () {
        imageElement.width(10 * percent);
        imageElement.height("auto");
    };

    function zoomSave() {
        localStorage.setItem(imagePercentID, percent);
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
        zoomSave();
    });

    function setPercent() {
        percent = Math.round(percent);
        percentInput.val(percent);
        zoomSave();
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

    let fitWidth = $("<input>", {type: 'button', value: '↔'}).click(function () {
        imageElement.width('100%');
        unPersist();
    });

    let fitHeight = $("<input>", {type: 'button', value: '↕'}).click(function () {
        imageElement.height('100%');
        imageElement.width("auto");
        unPersist();
    });

    let zoomIn = $("<input>", {type: 'button', value: '+'}).click(function () {
        percent *= 1.1;
        setPercent();
        setZoom();
    });

    let zoomOut = $("<input>", {type: 'button', value: '-'}).click(function () {
        percent *= 0.909;
        setPercent();
        setZoom();
    });

    setZoom();
    return [
        fitHeight,
        fitWidth,
        $("<span>", {class: "nowrap"}).append(percentInput, "% "),
        zoomIn,
        zoomOut
    ];
};

function makeControlPane() {
    // these define the position of the control bar and controls in it
    let point = "N";
    let begMidEnd = "M";

    let contentPane = $("<div>", {class: 'overflow-auto'}).css({flex: 'auto'});
    let container = $("<div>").css({display: 'flex', height: "100%"});
    container.append(contentPane);

    let controlPane = $("<div>", {class: 'page-interface control-panel'});
    // control2 contains the controls, control1 & control3 adjust the layout
    let control1 = $("<div>");
    let control2 = $("<div>").css({flex: '0 1 auto'});
    let control3 = $("<div>");
    controlPane.append(control1, control2, control3);

    let menu = $("<div>", {class: "nav-menu"});

    let menuButton = $("<input>", {type: 'button', value: '⌘'}).click(function () {
        menu.show();
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
        controlPane.detach();
        container.prepend(controlPane);
    }

    function controlLast() {
        controlPane.detach();
        container.append(controlPane);
    }

    function controlHoriz() {
        container.css({flexDirection: 'column'});
        // need control2 here or doesn't find anything after adding controls
        $(".condiv", control2).css({display: "inline", padding: "0 0.1em"});
        controlPane.css({"text-align": "", "flex-direction": "row"});
    }

    function controlVert() {
        container.css({flexDirection: 'row'});
        $(".condiv", control2).css({display: "block", padding: "0.1em 0"});
        controlPane.css({"text-align": "center", "flex-direction": "column"});
    }

    let leftButton = $("<input>", {type: 'button', class: 'navbutton', value: '⇦'});
    let centreButton = $("<input>", {type: 'button', class: 'navbutton', value: '|'});
    let rightButton = $("<input>", {type: 'button', class: 'navbutton', value: '⇨'});
    let topButton = $("<input>", {type: 'button', class: 'navbutton', value: '⇧'});
    let midButton = $("<input>", {type: 'button', class: 'navbutton', value: '−'});
    let botButton = $("<input>", {type: 'button', class: 'navbutton', value: '⇩'});

    let westButton = $("<input>", {type: 'button', class: 'navbutton', value: '◁'});
    let northButton = $("<input>", {type: 'button', class: 'navbutton', value: '△'});
    let southButton = $("<input>", {type: 'button', class: 'navbutton', value: '▽'});
    let eastButton = $("<input>", {type: 'button', class: 'navbutton', value: '▷'});

    let hideButton = $("<input>", {type: 'button', class: 'navbutton', value: '×'});

    menu.append(navBox);
    control1.append(menuButton, menu);

    function navigate() {
        $(".navbutton").detach();
        switch(point) {
        case "N":
            controlFirst();
            controlHoriz();
            controlPane.css({borderWidth: "0 0 1px 0"});
            menu.css({margin: "0"});
            fillNavBox([leftButton, centreButton, rightButton, westButton, hideButton, eastButton, "", southButton, ""]);
            break;
        case "W":
            controlFirst();
            controlVert();
            controlPane.css({borderWidth: "0 1px 0 0"});
            menu.css({margin: "0 0 0 6em"});
            fillNavBox([topButton, northButton, "", midButton, hideButton, eastButton, botButton, southButton, ""]);
            break;
        case "E":
            controlLast();
            controlVert();
            controlPane.css({borderWidth: "0 0 0 1px"});
            menu.css({margin: "0 0 0 -8em"});
            fillNavBox(["", northButton, topButton, westButton, hideButton, midButton, "", southButton, botButton]);
            break;
        case "S":
            controlLast();
            controlHoriz();
            controlPane.css({borderWidth: "1px 0 0 0"});
            menu.css({margin: "-8em 0 0 0"});
            fillNavBox(["", northButton, "", westButton, hideButton, eastButton, leftButton, centreButton, rightButton]);
            break;
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

    function addControls(controls) {
        // put each control in a div
        controls.forEach(function(control) {
            control2.append($("<div>").addClass("condiv")
                .append(control));
        });
        // set up the condivs to be block or inline
        navigate();
    }
    // initialise from point NESW and Begin Mid End
    navigate();
    navStyle();

    return {
        container: container,
        contentPane: contentPane,
        addControls: addControls
    };
}

function makeImageControlPane(imageElement) {
    let controlPane = makeControlPane();
    controlPane.addControls(imageControl(imageElement));
    controlPane.contentPane.addClass("center-align").append(imageElement);
    return controlPane.container;
}
