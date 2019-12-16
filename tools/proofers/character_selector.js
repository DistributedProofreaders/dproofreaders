/*global $ projectID mruTitle */

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
        top.focusText();
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
    if(!Array.isArray(mru)) {
        // this should only happen during development changes
        mru = [];
    }

    window.addEventListener('beforeunload', function() {
        localStorage.setItem(storageKey, JSON.stringify(mru));
    });

    const mruColumns = 12;
    const mruMax = 2 * mruColumns;

    function drawRow(mruRow) {
        // it is not necessary to escape the character or title
        var row = $('<div />').addClass('table-row');
        mruRow.forEach(function(element) {
            row.append($('<div />')
                .addClass('table-cell')
                .append($('<button />', {type: "button", title: element.title})
                    .addClass('picker')
                    .text(element.character)
                )
            );
        });
        return row;
    }

    function drawMru() {
        var mruUpper = mru.slice(0, mruColumns);
        var mruLower = mru.slice(mruColumns);
        $(".mru")
            .html(drawRow(mruUpper))
            .append(drawRow(mruLower));
    }

    function addMru(char, titletext) {
        var index = mru.findIndex(function (element) {
            return element.character === char;
        });
        if(index >= 0) {
            // already in array, update time
            mru[index].time = Date.now();
        } else {
            // add new element and check length
            if(mruMax < mru.unshift({character: char, time: Date.now(), title: titletext})) {
                // remove oldest element
                let oldTime = Number.MAX_VALUE;
                let oldIndex;
                mru.forEach(function(element, index) {
                    let elTime = element.time;
                    if(elTime < oldTime) {
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
    $("#selector_row").prepend($('<button />', {type: "button", id: 'mru', title: mruTitle})
        .addClass('selector_button')
        .text('\u{1f550}')
    );
    $("#char-selector").append($('<div />')
        .addClass('mru key-block')
    );

    // draw the picker buttons
    drawMru();

    // find the code of the first selector button
    var initialCode = $("#selector_row > button")[0].id;
    enableBoard(initialCode);

    $("#selector_row")
        .on("click", ".selector_button", charSelector, function () {
            enableBoard(this.id);
        });

    // attach handlers to key-block so will respond to dynamically created mru buttons
    $(".key-block", charSelector)
        .on("click", ".picker", function () {
            var char = this.textContent;
            top.insertCharacter(char);
            // do this also for mru buttons so time will get updated
            addMru(char, this.title);
        })
        .on("mouseover", ".picker", function () {
            largeChar.value = this.textContent;
        });
});
