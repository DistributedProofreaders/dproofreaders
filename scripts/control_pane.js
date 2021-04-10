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

    let sin = 0;
    let cosine = 1;
    function transform() {
        // when image is rotated, scroll does not account for any points
        // with x or y < 0. Rotate about centre. Then if +- 90 deg.
        // translate by half difference in height and width.
        let xOffset = 0, yOffset = 0;
        if(sin != 0) {
            let offset = (imageElement.height() - imageElement.width()) / 2;
            if(offset > 0) {
                xOffset = offset;
            } else {
                yOffset = -offset;
            }
        }
        imageElement.css({transform: `matrix(${cosine}, ${sin}, ${-sin}, ${cosine}, ${xOffset}, ${yOffset})`});
    }

    function zoomSave() {
        // have to recalculate transform after changing size.
        transform();
        localStorage.setItem(imagePercentID, percent);
    }

    percentInput.change(function() {
        percent = this.value;
        setZoom();
        zoomSave();
    });

    function setPercent() {
        // assume 100% means 1000px wide
        let width = imageElement.width();
        // reset width and height so that fitting does not persist
        imageElement.width(width);
        imageElement.height("auto");
        percent = Math.round(width / 10);
        percentInput.val(percent);
        zoomSave();
    }

    let fitWidth = $("<input>", {type: 'button', value: '↔'}).click(function () {
        imageElement.width('100%');
        imageElement.height("auto");
        setPercent();
    });

    let fitHeight = $("<input>", {type: 'button', value: '↕'}).click(function () {
        imageElement.height('100%');
        imageElement.width("auto");
        setPercent();
    });


    let clockRotateInput = $("<input>", {type: 'button', value: '↻'}).click( function () {
        let temp = sin;
        sin = cosine;
        cosine = -temp;
        transform();
    });

    let anticlockRotateInput = $("<input>", {type: 'button', value: '↺'}).click( function () {
        let temp = sin;
        sin = -cosine;
        cosine = temp;
        transform();
    });

    setZoom();
    return [
        $("<span>", {class: "nowrap"}).append(percentInput, "% "),
        fitWidth,
        fitHeight,
        clockRotateInput,
        anticlockRotateInput
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

    let navBox = $("<div>").css({display: "grid",
        "grid-template-areas": "'nw n ne' 'w c e' 'sw s se'",
        "grid-template-rows": "1fr 1fr 1fr",
        "grid-template-columns": "1fr 1fr 1fr"
    });

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

    let westButton = $("<input>", {type: 'button', class: 'navbutton', value: '◁', style: "grid-area: w"});
    let northButton = $("<input>", {type: 'button', class: 'navbutton', value: '△', style: "grid-area: n"});
    let southButton = $("<input>", {type: 'button', class: 'navbutton', value: '▽', style: "grid-area: s"});
    let eastButton = $("<input>", {type: 'button', class: 'navbutton', value: '▷', style: "grid-area: e"});

    let hideButton = $("<input>", {type: 'button', value: '×', style: "grid-area: c"});

    navBox.append(hideButton);
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
            navBox.append(leftButton, centreButton, rightButton, westButton, eastButton, southButton);
            leftButton.css({"grid-area": "nw"});
            centreButton.css({"grid-area": "n"});
            rightButton.css({"grid-area": "ne"});
            break;
        case "W":
            controlFirst();
            controlVert();
            controlPane.css({borderWidth: "0 1px 0 0"});
            menu.css({margin: "0 0 0 6em"});
            navBox.append(topButton, midButton, botButton, northButton, eastButton, southButton);
            topButton.css({"grid-area": "nw"});
            midButton.css({"grid-area": "w"});
            botButton.css({"grid-area": "sw"});
            break;
        case "E":
            controlLast();
            controlVert();
            controlPane.css({borderWidth: "0 0 0 1px"});
            menu.css({margin: "0 0 0 -8em"});
            navBox.append(topButton, midButton, botButton, northButton, westButton, southButton);
            topButton.css({"grid-area": "ne"});
            midButton.css({"grid-area": "e"});
            botButton.css({"grid-area": "se"});
            break;
        case "S":
            controlLast();
            controlHoriz();
            controlPane.css({borderWidth: "1px 0 0 0"});
            menu.css({margin: "-8em 0 0 0"});
            navBox.append(leftButton, centreButton, rightButton, northButton, westButton, eastButton);
            leftButton.css({"grid-area": "sw"});
            centreButton.css({"grid-area": "s"});
            rightButton.css({"grid-area": "se"});
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
