/*global $ projectID mruTitle mruAbbrev */

$(function () {
    "use strict";

    var charSelector = $("#char-selector");
    var largeChar = document.getElementById("large_char");

    function enableBoard(newCode) {
        // hide the visible key block
        $(".show", charSelector).removeClass("show");
        // show the new one
        $("." + newCode, charSelector).addClass("show");
        // mark the new selected tab
        $(".selected-tab", charSelector).removeClass("selected-tab");
        $("#" + newCode, charSelector).addClass("selected-tab");
        largeChar.value = ""; // remove old character
        top.focusText(true);
    }

    // The mru (most recently used) list stores a set of characters
    // with time of last use and title.
    // on page load get data and draw html.
    // on each char: if already in the set update time
    // else add it to start of array
    // if overflowing find oldest and remove it
    // redraw if changed
    // on page unload, save data

    var mru;
    var storageKey = "mru_" + projectID;
    var mruString = localStorage.getItem(storageKey);
    if (mruString) {
        mru = JSON.parse(mruString);
    }
    if (!Array.isArray(mru)) {
        // this should only happen during development changes
        mru = [];
    }

    var pickerChars = {};
    $(".picker").each(function () {
        pickerChars[$(this).text()] = true;
    });

    // filter out invalid characters
    mru = mru.filter(function (mruCharacter) {
        return pickerChars[mruCharacter.character] === true;
    });

    window.addEventListener("beforeunload", function () {
        localStorage.setItem(storageKey, JSON.stringify(mru));
    });

    const mruColumns = 12;
    const mruMax = 2 * mruColumns;

    function setAlign(element, title) {
        // to ensure the accents for Greek capital letters are visible, add a text indent.
        // see also similar code in pinc/CharacterSelector.inc
        if (title.startsWith("GREEK CAPITAL") && (title.includes("OXIA") || title.includes("VARIA"))) {
            element.style.textIndent = "0.35em";
        } else {
            element.style.textIndent = "0";
        }
    }

    function drawRow(mruRow) {
        // it is not necessary to escape the character or title
        var row = $("<div />").addClass("table-row");
        mruRow.forEach(function (element) {
            let mruButton = $("<button />", { type: "button", title: element.title });
            mruButton.addClass("picker").text(element.character);
            setAlign(mruButton[0], element.title);

            row.append($("<div />").addClass("table-cell").append(mruButton));
        });
        return row;
    }

    function drawMru() {
        var mruUpper = mru.slice(0, mruColumns);
        var mruLower = mru.slice(mruColumns);
        $(".mru_code") // selects the MRU block of buttons
            .html(drawRow(mruUpper))
            .append(drawRow(mruLower));
    }

    function addMru(char, titletext) {
        // is the character in MRU array?
        var index = mru.findIndex(function (element) {
            return element.character === char;
        });

        if (index >= 0) {
            // already in array, update time
            mru[index].time = Date.now();
        } else {
            // add new element and check length
            if (mruMax < mru.unshift({ character: char, time: Date.now(), title: titletext })) {
                // remove oldest element
                let oldTime = Number.MAX_VALUE;
                let oldIndex;
                mru.forEach(function (element, index) {
                    let elTime = element.time;
                    if (elTime < oldTime) {
                        oldTime = elTime;
                        oldIndex = index;
                    }
                });
                mru.splice(oldIndex, 1);
            }
            drawMru();
        }
    }

    // Draw the MRU selector button and empty block
    // this duplicates the html code defined in CharacterSelector.inc
    $("#selector_row").prepend(
        $("<button />", {
            type: "button",
            id: "mru_code",
            title: mruTitle,
        })
            .addClass("selector_button")
            .text(mruAbbrev),
    );
    $("#char-selector").append($("<div />").addClass("mru_code key-block"));

    // draw the picker buttons
    drawMru();

    // find the code of the first selector button
    var initialCode = $("#selector_row > button")[0].id;
    enableBoard(initialCode);

    $("#selector_row").on("click", ".selector_button", charSelector, function () {
        enableBoard(this.id);
    });

    // attach handlers to key-block so will respond to dynamically created MRU buttons
    $(".key-block", charSelector)
        .on("click", ".picker", function () {
            var char = this.textContent;
            top.insertCharacter(char);
            // do this also for MRU buttons so time will get updated
            addMru(char, this.title);
        })
        .on("mouseover", ".picker", function () {
            largeChar.value = this.textContent;
            setAlign(largeChar, this.title);
        });
});
