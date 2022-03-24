/* eslint-disable no-use-before-define, camelcase */
/* exported previewControl, initPrev */
/* global $ makePreview, fontStyles fontFamilies MathJax validateText tagNames previewStrings
previewMessages */
/*
This file controls the user interface functions. Initially nothing is displayed
because "prevdiv" has diplay:none; which means it is not displayed and the page
is laid out as if it was not there.
When the preview button is pressed the function "show" changes its display style
to "block" and changes the display style of the normal view (proofDiv) to "none"
This is reversed when "Quit" is pressed.
The configuration screen is handled in the same way.
previewStrings are translated strings in header args
*/
var previewControl;

$( function() {
    "use strict";
    var supp_set = ['charBeforeStart', 'sideNoteBlank'];
    // this is a wrapper round text_preview which enables the padding
    // on the right to appear correctly
    var outerPrev = document.getElementById("id_tp_outer");
    var prevWin = document.getElementById("text_preview");
    var txtarea = document.getElementById("text_data");
    var prevDiv = document.getElementById("prevdiv");
    var proofDiv = document.getElementById("proofdiv");
    var testDiv = document.getElementById("color_test");
    var configPan = document.getElementById("id_config_panel");
    var enableColorCheckbox = document.getElementById("id_color_on");
    var issBox = document.getElementById("id_iss");
    var possIssBox = document.getElementById("id_poss_iss");
    var allowUnderlineCheckbox = document.getElementById("id_underline");
    var allowMathPreviewCheckbox = document.getElementById("id_math_preview");
    var someSupp = document.getElementById("id_some_supp");
    var proofFrameSet = top.document.getElementById("proof_frames");

    let colorTable = $("#color_table");
    let $fontSelector = $("#id_font_sel");

    var suppCheckBox = [];

    var viewMode;
    var wrapMode = false;

    var tempStyle = {}; // used during configure

    // these are the default values. If the user changes anything the new
    // styles are saved in local storage and reloaded next time.
    // the foreground and background colours for plain text, italic, bold,
    // gesperrt, smallcaps, font change, other tags, highlighting issues
    // and possible issues.
    // An empty color string means use default color
    var previewStyles = {
        t: {bg: "#fffcf4", fg: "#000000"},
        i: {bg: "", fg: "#0000ff"},
        b: {bg: "", fg: "#c55a1b"},
        g: {bg: "", fg: "#8a2be2"},
        sc: {bg: "", fg: "#009700"},
        f: {bg: "", fg: "#ff0000"},
        u: {bg: "", fg: ""},
        etc: {bg: "#ffcaaf", fg: ""},
        err: {bg: "#ff0000", fg: ""},
        hlt: {bg: "#ceff09", fg: ""},
        color: true, // colour the markup or not
        allowUnderline: false,
        defFontIndex: 0,
        suppress: {},
        initialViewMode: "no_tags",
        allowMathPreview: false
    };
    supp_set.forEach(function (msg, i) {
        previewStyles.suppress[msg] = false;
        suppCheckBox[i] = document.getElementById(msg);
    });
    // stores the size of the bottom frame so it can be restored on exit
    var old_rows;
    var font_size;
    var preview;

    function getMessage(index) {
        return previewMessages[index];
    }

    function writePreviewText() {
        // makePreview is defined in preview.js
        preview = makePreview(txtarea.value, viewMode, wrapMode, previewStyles, getMessage);
        prevWin.style.whiteSpace = (
            (preview.ok && wrapMode)
                ? "normal"
                : "pre"
        );
        prevWin.innerHTML = preview.txtout;
        if (preview.ok && previewStyles.allowMathPreview) {
            try {
                MathJax.typeset([prevWin]);
            } catch(exception) {
                alert("MathJax error: " + exception);
            }
        }
        issBox.value = preview.issues;
        possIssBox.value = preview.possIss;
        // show the style controls if there are no issues
        $(".styleoption").toggle(preview.ok);
        // if any issues are suppressed show warning
        var warn = Object.keys(previewStyles.suppress).some(function (key) {
            return previewStyles.suppress[key];
        });
        someSupp.style.display = (
            warn
                ? "inline"
                : "none"
        );
    }

    // this makes a copy of the style data
    // js assignment of objects just makes a reference to the old object
    // so we need to copy each primitive value and construct new objects
    // or arrays.
    // Initially the style data is initialised to default values then, if saved
    // values are in local storage, they are copied to the data. If during
    // development a new element is added to styles, we need to keep it
    // but copy the rest from stored data so if keep is true then do not
    // construct new empty objects or arrays. If an element becomes obsolete
    // the destination will not then exist, so check before copying.
    // If keep is false then an exact copy is made.
    function deepCopy(dest, source, keep) {
        if (source && typeof source === 'object') {
            if (!keep) {
                dest = (
                    Array.isArray(source)
                        ? []
                        : {}
                );
            }
            if (dest) {
                Object.keys(source).forEach(function (i) {
                    dest[i] = deepCopy(dest[i], source[i], keep);
                });
            }
        } else {
            dest = source;
        }
        return dest;
    }

    function initStyle() {
        var style0;
        if (localStorage.getItem('preview_data')) {
            style0 = JSON.parse(localStorage.preview_data);
            previewStyles = deepCopy(previewStyles, style0, true);
        }
    }

    function setSelectedFont() {
        let fontIndex = $fontSelector.val();
        prevWin.style.fontFamily = fontFamilies[fontIndex];
        previewStyles.defFontIndex = fontIndex;
        saveStyle();
    }

    function setupFont() {
        Object.keys(fontStyles).forEach(function(index) {
            let fontStyle = fontStyles[index];
            let selected = (index === previewStyles.defFontIndex);
            let option = new Option(fontStyle, index, selected, selected);
            $fontSelector[0].add(option, null);
        });
        // use value from selector incase the user defined option has been
        // removed and value has changed from 1 to 0
        setSelectedFont();
    }

    $fontSelector.change( function() {
        setSelectedFont();
    });

    function initView() {
        enableColorCheckbox.checked = previewStyles.color;
        outerPrev.style.backgroundColor = previewStyles.t.bg;
        outerPrev.style.color = previewStyles.t.fg;
        viewMode = previewStyles.initialViewMode;
        $("#" + viewMode).prop("checked", true);
        // check if MathJax already loaded. Will break if load more than once
        if(previewStyles.allowMathPreview && (typeof(MathJax) === 'undefined')) {
            // allow caching which getScript() by default does not
            return $.ajax({
                dataType: "script",
                cache: true,
                url: "https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js",
            });
        }
    }

    initStyle();
    initView();
    setupFont();

    function colorChange(event) {
        tempStyle[event.data.tag][event.data.ground] = this.value;
        testDraw();
    }

    function boxChange(event) {
        const tag = event.data.tag;
        const ground = event.data.ground;
        const colorInput = event.data.colorInput;
        if(this.checked) {
            // show the color input, set and save its color
            const defaultColor = tempStyle.t[ground];
            colorInput.val(defaultColor);
            tempStyle[tag][ground] = defaultColor;
            colorInput.show();
        } else {
            // use default and hide the color input
            tempStyle[tag][ground] = "";
            colorInput.hide();
        }
        testDraw();
    }

    // select the view mode
    $("[name='viewSel']").click(function () {
        viewMode = this.id;
        writePreviewText();
    });

    $("#re_wrap").change(function () {
        wrapMode = this.checked;
        writePreviewText();
    });

    // functions for setting up the configuration screen
    function testDraw() {
        testDiv.style.backgroundColor = tempStyle.t.bg;
        testDiv.style.color = tempStyle.t.fg;
        preview = makePreview(previewStrings.previewDemo, 'no_tags', false, tempStyle, getMessage);
        testDiv.innerHTML = preview.txtout;
    }

    function initColorSelector() {
        const foreBackGround = ["fg", "bg"];
        colorTable.append($("<tr>").append("<th>", $("<th>", {colspan: '2', text: previewStrings.text}), $("<th>", {colspan: '2', text: previewStrings.background})));

        Object.keys(tagNames).forEach(function(tag) {
            let dataRow = $("<tr>");
            dataRow.append($("<td>", {text: tagNames[tag]}));
            foreBackGround.forEach(function(ground) {
                const color = tempStyle[tag][ground];
                let colorInput = $("<input>", {type: 'color'}).change({tag: tag, ground: ground}, colorChange);
                // HACK for safari, setting value: color in the constructor doesn't work
                colorInput.val(color);
                if(tag == 't') {
                    // no checkbox
                    dataRow.append("<td>");
                } else {
                    const hideColor = ("" === color);
                    const checkBox = $("<input>", {type: 'checkbox', 'checked': !hideColor}).change({tag: tag, ground: ground, colorInput: colorInput}, boxChange);
                    dataRow.append($("<td>").append(checkBox));
                    if(hideColor) {
                        colorInput.hide();
                    }
                }
                dataRow.append($("<td>").append(colorInput));
            });
            colorTable.append(dataRow);
        });
    }

    function leavePreview() {
        prevDiv.style.display = "none";
        window.removeEventListener("keydown", keyQuit, false);
    }

    function previewToProof() {
        // restore the bottom frame
        proofFrameSet.setAttribute("rows", old_rows);
        proofDiv.style.display = "block";
        leavePreview();
    }

    function keyQuit(event) {
        if (event.keyCode === 27) {
            previewToProof();
        }
    }

    function enterPreview() {
        prevDiv.style.display = "block";
        window.addEventListener("keydown", keyQuit, false);
    }

    function hideConfig() {
        enterPreview();
        configPan.style.display = "none";
        colorTable.empty();
    }

    function saveStyle() {
        localStorage.preview_data = JSON.stringify(previewStyles);
    }

    previewControl = {
        // this is used inside the "hover" markup to move the hover box
        // (by adjusting its margin) so it does not disappear
        // off the edge of the screen
        adjustMargin: function (el) {
            var hov = el.firstElementChild;
            var hovBox = hov.getBoundingClientRect();
            var container = outerPrev.getBoundingClientRect();
            if (hovBox.right >= container.right) {
                hov.style.marginLeft = -hovBox.width + "px";
            }
            if (hovBox.left <= container.left) {
                hov.style.marginLeft = "0";
            }
            if (hovBox.bottom + 17 >= container.bottom) { // 17 allows for scrollbar
                hov.style.marginTop = "-2em";
            }
        },

        reSizeText: function (ratio) {
            font_size *= ratio;
            prevWin.style.fontSize = font_size.toFixed(1) + "px";
        },

        show: function () { // called when preview is first shown
            if(!validateText()) {
                return;
            }
            var msie = document.documentMode;
            if (msie < 9) {
                alert(previewStrings.ieWarn);
                return;
            }
            old_rows = proofFrameSet.getAttribute("rows");
            // make the bottom frame very small since it is not useful
            proofFrameSet.setAttribute("rows", "*,1");
            proofDiv.style.display = "none";
            enterPreview();
            font_size = parseFloat(window.getComputedStyle(txtarea, null).fontSize);
            this.reSizeText(1.0);
            writePreviewText();
        },

        hide: previewToProof,

        configure: function () {    // show the configuration screen
            leavePreview();
            configPan.style.display = "block";
            // make a copy of the styles so that if we cancel we can go back
            // to how it was before.
            tempStyle = deepCopy(tempStyle, previewStyles, false);
            testDiv.style.fontFamily = fontFamilies[previewStyles.defFontIndex];
            testDiv.style.fontSize = font_size.toFixed(1) + "px";
            testDraw();
            initColorSelector();
            allowUnderlineCheckbox.checked = tempStyle.allowUnderline;
            allowMathPreviewCheckbox.checked = tempStyle.allowMathPreview;

            supp_set.forEach(function (msg, i) {
                suppCheckBox[i].checked = tempStyle.suppress[msg];
            });
            $("#id_init_mode").val(tempStyle.initialViewMode);
        },

        enableColor: function (en) {
            previewStyles.color = en;
            saveStyle();
            writePreviewText();
        },

        OKConfig: function () {
            supp_set.forEach(function (msg, i) {
                tempStyle.suppress[msg] = suppCheckBox[i].checked;
            });
            tempStyle.allowUnderline = allowUnderlineCheckbox.checked;
            tempStyle.allowMathPreview = allowMathPreviewCheckbox.checked;
            tempStyle.initialViewMode = $("#id_init_mode").val();
            previewStyles = deepCopy(previewStyles, tempStyle, false);
            saveStyle();
            // if loading MathJax, wait for it to finish
            $.when(initView()).done(function() {
                writePreviewText();
                hideConfig();
            });
        },

        cancelConfig: function () { // don't change anything
            hideConfig();
        },
    };
});
