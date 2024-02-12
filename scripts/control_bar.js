/*global $ texts */
/* exported makeImageWidget */

function makeControlDiv(container, content, controls) {
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

    let controlBar = document.createElement("div");
    controlBar.classList.add('page-interface', 'control-bar');
    // control2 contains the controls, control1 & control3 adjust the layout
    let control1 = document.createElement("div");
    control1.classList.add('left-align');
    let control2 = document.createElement("div");
    control2.style.flex = '0 1 auto';
    let control3 = document.createElement("div");
    controlBar.append(control1, control2, control3);

    const menu = document.createElement("div");
    menu.classList.add("control-bar-menu");
    // set this style explicitly or the event listener does not see it the first time
    menu.style.display = 'none';

    const menuButton = document.createElement("button");
    menuButton.title = texts.adjustPanel;
    menuButton.innerHTML = "<i class='fas fa-cog'></i>";
    menuButton.addEventListener('click', function () {
        if(menu.style.display == 'none') {
            // find the position of the menu button and set position of
            // menu relative to it
            let buttonRect = menuButton.getBoundingClientRect();
            let containerRect = container[0].getBoundingClientRect();
            switch(compassPoint) {
            case "N":
            case "W":
                menu.style.top = `${buttonRect.top}px`;
                menu.style.bottom = 'auto';
                menu.style.left = `${buttonRect.right}px`;
                menu.style.right = 'auto';
                break;
            case "S":
                menu.style.top = 'auto';
                menu.style.bottom = `${containerRect.bottom - buttonRect.bottom}px`;
                menu.style.left = `${buttonRect.right}px`;
                menu.style.right = 'auto';
                break;
            default: // E
                menu.style.top = `${buttonRect.top}px`;
                menu.style.bottom = 'auto';
                menu.style.left = 'auto';
                menu.style.right = `${containerRect.right - buttonRect.left}px`;
                break;
            }
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    });

    // build navBox
    const navBox = document.createElement('table');
    const navCell = [];
    let cellIndex = 0;
    for(let rowIndex = 0; rowIndex < 3; rowIndex++) {
        const row = document.createElement("tr");
        navBox.append(row);
        for(let columnIndex = 0; columnIndex < 3; columnIndex++) {
            navCell[cellIndex] = document.createElement("td");
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
        controlBar.remove();
        container.append(controlBar);
        container.append(content);
    }

    function controlLast() {
        controlBar.remove();
        container.append(controlBar);
    }

    const controlDivs = [];

    function controlHoriz() {
        container.css({flexDirection: 'column'});
        controlDivs.forEach(function (controlDiv) {
            controlDiv.style.display = 'inline';
            controlDiv.style.padding = '0 0.1em';
        });
        controlBar.style.textAlign = 'unset';
        controlBar.style.flexDirection = 'row';

    }

    function controlVert() {
        container.css({flexDirection: 'row'});
        controlDivs.forEach(function (controlDiv) {
            controlDiv.style.display = 'block';
            controlDiv.style.padding = '0.1em 0';
        });
        controlBar.style.textAlign = 'center';
        controlBar.style.flexDirection = 'column';
    }

    const leftButton = document.createElement("button");
    leftButton.classList.add('navbutton');
    leftButton.title = texts.controlLeft;
    leftButton.innerHTML = "<i class='fas fa-caret-left'></i>";

    const centerButton = document.createElement("button");
    centerButton.classList.add('navbutton');
    centerButton.title = texts.controlCenter;
    centerButton.innerText = '|';

    const rightButton = document.createElement("button");
    rightButton.classList.add('navbutton');
    rightButton.title = texts.controlRight;
    rightButton.innerHTML = "<i class='fas fa-caret-right'></i>";

    const topButton = document.createElement("button");
    topButton.classList.add('navbutton');
    topButton.title = texts.controlTop;
    topButton.innerHTML = "<i class='fas fa-caret-up'></i>";

    const midButton = document.createElement("button");
    midButton.classList.add('navbutton');
    midButton.title = texts.controlMid;
    midButton.innerText = '−';

    const botButton = document.createElement("button");
    botButton.classList.add('navbutton');
    botButton.title = texts.controlBot;
    botButton.innerHTML = "<i class='fas fa-caret-down'></i>";

    const westButton = document.createElement("button");
    westButton.classList.add('navbutton');
    westButton.title = texts.dockLeft;
    westButton.innerHTML = "<i class='fas fa-arrow-left'></i>";

    const northButton = document.createElement("button");
    northButton.classList.add('navbutton');
    northButton.title = texts.dockTop;
    northButton.innerHTML = "<i class='fas fa-arrow-up'></i>";

    const southButton = document.createElement("button");
    southButton.classList.add('navbutton');
    southButton.title = texts.dockBot;
    southButton.innerHTML = "<i class='fas fa-arrow-down'></i>";

    const eastButton = document.createElement("button");
    eastButton.classList.add('navbutton');
    eastButton.title = texts.dockRight;
    eastButton.innerHTML = "<i class='fas fa-arrow-right'></i>";

    const hideButton = document.createElement("button");
    hideButton.classList.add('navbutton');
    hideButton.title = texts.hideMenu;
    hideButton.innerText = '×';

    menu.append(navBox);
    const menuDiv = document.createElement("div");
    controlDivs.push(menuDiv);
    menuDiv.classList.add('center-align');
    menuDiv.append(menuButton);
    control1.append(menuDiv, menu);

    function setCompassPoint() {
        $(".navbutton", navBox).detach();
        switch(compassPoint) {
        case "N":
            controlFirst();
            controlHoriz();
            controlBar.style.borderWidth = '0 0 1px 0';
            fillNavBox([leftButton, centerButton, rightButton, westButton, hideButton, eastButton, "", southButton, ""]);
            break;
        case "W":
            controlFirst();
            controlVert();
            controlBar.style.borderWidth = '0 1px 0 0';
            fillNavBox([topButton, northButton, "", midButton, hideButton, eastButton, botButton, southButton, ""]);
            break;
        case "E":
            controlLast();
            controlVert();
            controlBar.style.borderWidth = '0 0 0 1px';
            fillNavBox(["", northButton, topButton, westButton, hideButton, midButton, "", southButton, botButton]);
            break;
        case "S":
            controlLast();
            controlHoriz();
            controlBar.style.borderWidth = '1px 0 0 0';
            fillNavBox(["", northButton, "", westButton, hideButton, eastButton, leftButton, centerButton, rightButton]);
            break;
        }
    }

    function setBegMidEnd() {
        switch(begMidEnd) {
        case "B":
            control1.style.flex = '0 0 auto';
            control3.style.flex = '1 0 auto';
            break;
        case "M":
            control1.style.flex = '1 0 auto';
            control3.style.flex = '1 0 auto';
            break;
        case "E":
            control1.style.flex = '1 0 auto';
            control3.style.flex = '0 0 auto';
            break;
        }
    }

    hideButton.addEventListener('click', () => {
        menu.style.display = 'none';
    });

    let onChange = new Set();
    function newPoint(newP) {
        compassPoint = newP;
        saveLocation();
        setCompassPoint();
        onChange.forEach(function (onChangeCallback) {
            onChangeCallback();
        });
        menu.style.display = 'none';
    }

    northButton.addEventListener('click', () => {
        newPoint("N");
    });

    southButton.addEventListener('click', () =>{
        newPoint("S");
    });

    westButton.addEventListener('click', () => {
        newPoint("W");
    });

    eastButton.addEventListener('click', () => {
        newPoint("E");
    });

    function newBME(newBME) {
        begMidEnd = newBME;
        saveLocation();
        setBegMidEnd();
        menu.style.display = 'none';
    }

    leftButton.addEventListener('click', () => {
        newBME('B');
    });

    centerButton.addEventListener('click', () => {
        newBME('M');
    });

    rightButton.addEventListener('click', () => {
        newBME('E');
    });

    topButton.addEventListener('click', () => {
        newBME('B');
    });

    midButton.addEventListener('click', () => {
        newBME('M');
    });

    botButton.addEventListener('click', () => {
        newBME('E');
    });

    let controlDiv;
    controls.forEach(function(control) {
        controlDiv = document.createElement("div");
        controlDiv.append(control[0]);
        controlDivs.push(controlDiv);
        control2.append(controlDiv);
    });

    return {
        setupControls: function (storageKey) {
            barKey = storageKey + "-bar";
            let barData = JSON.parse(localStorage.getItem(barKey));
            if (!barData || typeof barData.location !== 'string') {
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
        onChange: onChange,
    };
}

function makeImageWidget(container, align = "C") {
    const alignment = {
        L: "left",
        C: "center",
        R: "right"
    };
    const content = document.createElement("div");
    content.style.textAlign = alignment[align];

    const imageCursor = "grab";
    // use plain js image so width or style.width is clearly differentiated
    const image = document.createElement("img");
    image.classList.add("middle-align");
    image.style.cursor = imageCursor;
    // When the image is rotated it has width and height as if it were not
    // rotated. To make scroll work correctly, enclose it in a div with the
    //actual width and height.
    const imageDiv = document.createElement("div");
    imageDiv.classList.add("middle-align", "left-align");
    imageDiv.style.overflow = "hidden";
    imageDiv.style.display = "inline-block";
    content.append(imageDiv);
    imageDiv.appendChild(image);

    let scrollDiffX = 0;
    let scrollDiffY = 0;
    function mousemove(event) {
        content.scrollTop = scrollDiffY - event.pageY;
        content.scrollLeft = scrollDiffX - event.pageX;
    }

    function mouseup() {
        document.removeEventListener("mousemove", mousemove);
        document.removeEventListener("mouseup", mouseup);
        image.style.cursor = imageCursor;
    }

    image.addEventListener("mousedown", function(event) {
        event.preventDefault();

        // so image can be moved with arrow keys
        content.focus();
        image.style.cursor = "grabbing";
        scrollDiffX = event.pageX + content.scrollLeft;
        scrollDiffY = event.pageY + content.scrollTop;
        document.addEventListener("mousemove", mousemove);
        document.addEventListener("mouseup", mouseup);
    });

    let imageKey;
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

    const percentInput = $("<input>", {type: 'number', value: percent, title: texts.zoomPercent});

    function setImageStyle() {
        // when this is called in 'setImage' we will not know the dimensions
        // of the image but it is not rotated (sine=0) so we do not need to.
        let offset;
        image.style.width = `${10 * percent}px`;
        image.style.height = "auto";
        if(sine != 0) {
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

    function setZoom() {
        if(percent < minPercent) {
            percent = minPercent;
        } else if(percent > maxPercent) {
            percent = maxPercent;
        }
        percentInput.val(Math.round(percent));
        setImageStyle();
    }

    function setDrawSave() {
        setZoom();
        localStorage.setItem(imageKey, JSON.stringify({zoom: percent}));
    }

    percentInput.change(function() {
        percent = parseInt(this.value);
        if(isNaN(percent)) {
            percent = defaultPercent;
        }
        setDrawSave();
    });

    const fitWidth = $("<button>", {title: texts.fitWidth}).click(function () {
        const contentWidth = getComputedStyle(content).width;
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
        const contentHeight = getComputedStyle(content).height;
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

    const counterclockRotateInput = $("<button>", {title: texts.counterclockRotate})
        .append($("<i>", {class: 'fas fa-undo-alt'}))
        .click( function () {
            [sine, cosine] = [cosine, -sine];
            setImageStyle();
        });

    const controls = [
        fitHeight,
        fitWidth,
        " ",
        $("<span>", {class: "nowrap"}).append(percentInput, "%"),
        " ",
        zoomIn,
        zoomOut,
        " ",
        clockRotateInput,
        counterclockRotateInput,
    ];
    const controlDiv = makeControlDiv(container, $(content), controls);

    return {
        setup: function (storageKey) {
            const imageWidgetKey = storageKey + "-imagewidget";

            imageKey = imageWidgetKey + "-image";
            let imageData = JSON.parse(localStorage.getItem(imageKey));
            if (!imageData || typeof imageData.zoom !== 'number') {
                imageData = {zoom: defaultPercent};
            }
            percent = imageData.zoom;
            setZoom();
            controlDiv.setupControls(imageWidgetKey);
        },

        setImage: function (src) {
            sine = 0;
            cosine = 1;
            image.src = src;
            setImageStyle();
            // reset scroll to top left
            content.scrollTop = 0;
            content.scrollLeft = 0;
        }
    };
}
