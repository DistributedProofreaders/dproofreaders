/* eslint-disable no-use-before-define, camelcase */
/* exported previewControl, initPrev */
/* global $ previewDemo, makePreview, ieWarn */
/*
This file controls the user interface functions. Initially nothing is displayed
because "prevdiv" has diplay:none; which means it is not displayed and the page
is laid out as if it was not there.
When the preview button is pressed the function "show" changes its display style
to "block" and changes the display style of the normal view (proofDiv) to "none"
This is reversed when "Quit" is pressed.
The configuration screen is handled in the same way.
The strings previewDemo and ieWarn are translated strings in header args
*/
var previewControl;

$( function() {
    "use strict";
    var i;
    var supp_set = ['charBeforeStart', 'sideNoteBlank'];
    // this is a wrapper round text_preview which enables the padding
    // on the right to appear correctly
    var outerPrev = document.getElementById("id_tp_outer");
    var prevWin = document.getElementById("text_preview");
    var txtarea = document.getElementById("text_data");
    var prevDiv = document.getElementById("prevdiv");
    var controlDiv = document.getElementById("id_controls");
    var tagon = document.getElementById("show_tags");
    var proofDiv = document.getElementById("proofdiv");
    var testDiv = document.getElementById("color_test");
    var backgroundCheckbox = document.getElementById("background_checkbox");
    var spanBackground = document.getElementById("span_background");
    var foregroundCheckbox = document.getElementById("foreground_checkbox");
    var spanForeground = document.getElementById("span_foreground");
    var foreColor = document.getElementById("id_forecol");
    var backColor = document.getElementById("id_backcol");
    var configPan = document.getElementById("id_config_panel");
    var defaultTextRadio = document.getElementById("id_default_radio");
    var enableColorCheckbox = document.getElementById("id_color_on");
    var issBox = document.getElementById("id_iss");
    var possIssBox = document.getElementById("id_poss_iss");
    var fontSelector = document.getElementById("id_font_sel");
    var fontName = document.getElementById("id_font_name");
    var removeFontSelector = document.getElementById("id_remove_sel");
    var allowUnderlineCheckbox = document.getElementById("id_underline");
    var someSupp = document.getElementById("id_some_supp");
    var proofFrameSet = top.document.getElementById("proof_frames");

    var suppCheckBox = [];

    var selTag;
    var viewMode;
    var wrapMode = false;

    var tempStyle = {}; // used during configure

    // these are the default values. If the user changes anything the new
    // styles are saved in local storage and reloaded next time.
    // the foreground and background colours for plain text, italic, bold,
    // gesperrt, smallcaps, font change, other tags, highlighting issues
    // and possible issues
    // font names are stored as fontSet properties,
    // the values have no significance
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
        fontSet: {"serif": 0, "sans-serif": 0, "monospace": 0, "DPCustomMono2": 0},
        defFont: "serif",
        suppress: {},
        initialViewMode: "no_tags"
    };
    supp_set.forEach(function (msg, i) {
        previewStyles.suppress[msg] = false;
        suppCheckBox[i] = document.getElementById(msg);
    });
    // stores the size of the bottom frame so it can be restored on exit
    var old_rows;
    var font_size;
    var preview;

    function writePreviewText() {
        // makePreview is defined in preview.js
        preview = makePreview(txtarea.value, viewMode, wrapMode, previewStyles);
        prevWin.style.whiteSpace = (
            (preview.ok && wrapMode)
                ? "normal"
                : "pre"
        );
        prevWin.innerHTML = preview.txtout;
        issBox.value = preview.issues;
        possIssBox.value = preview.possIss;
        // if there are any issues the tags will be shown
        // so make the radio buttons agree
        if (!preview.ok) {
            tagon.checked = true;
            viewMode = "show_tags";
        }
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

    function setViewColors(win) { // sets background and plain text colours
        win.style.backgroundColor = previewStyles.t.bg;
        win.style.color = previewStyles.t.fg;
    }

    // set up a font selector
    function initSelector(selector, optionSet, def) {
        var opt;
        var optionList = [];
        // remove all incase revisiting, last first
        for (i = selector.length - 1; i >= 0; i -= 1) {
            selector.remove(i);
        }
        if (!optionSet) {
            return;
        }
        Object.keys(optionSet).forEach(function (i) {
            optionList.push(i);
        });
        optionList.sort();
        for (i = 0; i < optionList.length; i += 1) {
            opt = document.createElement("option");
            opt.value = optionList[i];
            opt.text = opt.value;
            if (opt.value === def) {
                opt.selected = true;
            }
            selector.add(opt, null);
        }
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
    // This implies that if one of the default font options is deleted
    // it will re-appear next time
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

    function initView() {
        initSelector(fontSelector, previewStyles.fontSet, previewStyles.defFont);
        prevWin.style.fontFamily = previewStyles.defFont;
        enableColorCheckbox.checked = previewStyles.color;
        setViewColors(outerPrev);
        viewMode = previewStyles.initialViewMode;
        $("#" + viewMode).prop("checked", true);
    }

    initStyle();
    initView();

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
        preview = makePreview(previewDemo, 'no_tags', false, tempStyle);
        testDiv.innerHTML = preview.txtout;
    }

    function initPicker() { // initialise the color pickers
        var backgroundColor = tempStyle[selTag].bg;
        var foregroundColor = tempStyle[selTag].fg;
        var useDefaultBackground;
        var useDefaultForeground;
        if (selTag === "t") {
            spanBackground.style.visibility = "hidden";
            useDefaultBackground = false;
            spanForeground.style.visibility = "hidden";
            useDefaultForeground = false;
        } else {
            spanBackground.style.visibility = "visible";
            useDefaultBackground = ('' === backgroundColor);
            spanForeground.style.visibility = "visible";
            useDefaultForeground = ('' === foregroundColor);
        }
        foregroundCheckbox.checked = useDefaultForeground;
        foreColor.disabled = useDefaultForeground;
        foreColor.value = (
            useDefaultForeground
                ? tempStyle.t.fg
                : foregroundColor
        );
        backgroundCheckbox.checked = useDefaultBackground;
        backColor.disabled = useDefaultBackground;
        backColor.value = (
            useDefaultBackground
                ? tempStyle.t.bg
                : backgroundColor
        );
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
            var msie = document.documentMode;
            if (msie < 9) {
                alert(ieWarn);
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
            setViewColors(testDiv);  // uses previewStyles
            // make a copy of the styles so that if we cancel we can go back
            // to how it was before.
            tempStyle = deepCopy(tempStyle, previewStyles, false);
            initSelector(removeFontSelector, tempStyle.fontSet);
            testDiv.style.fontFamily = tempStyle.defFont;
            testDiv.style.fontSize = font_size.toFixed(1) + "px";
            testDraw();
            selTag = "t";   // always start with t (plain text) selected
            defaultTextRadio.checked = true;
            initPicker();
            allowUnderlineCheckbox.checked = tempStyle.allowUnderline;

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

        setTagColors: function (val) { // when i b ... radio checked
            selTag = val;
            initPicker();
        },

        OKConfig: function () {
            supp_set.forEach(function (msg, i) {
                tempStyle.suppress[msg] = suppCheckBox[i].checked;
            });
            tempStyle.allowUnderline = allowUnderlineCheckbox.checked;
            tempStyle.initialViewMode = $("#id_init_mode").val();
            previewStyles = deepCopy(previewStyles, tempStyle, false);
            saveStyle();
            initView();
            writePreviewText();
            hideConfig();
        },

        cancelConfig: function () { // don't change anything
            hideConfig();
        },

        setForegroundColor: function () {
            var useDefaultForeground = foregroundCheckbox.checked;
            var colorValue = (
                useDefaultForeground
                    ? ""
                    : foreColor.value
            );
            if (useDefaultForeground) {
                foreColor.value = tempStyle.t.fg;
            }
            foreColor.disabled = useDefaultForeground;
            tempStyle[selTag].fg = colorValue;
            if ("t" === selTag) {
                testDiv.style.color = colorValue;
            } else {
                testDraw();
            }
        },

        // called when background colour or transparency changed
        setBackgroundColor: function () {
            var useDefaultBackground = backgroundCheckbox.checked;
            var colorValue = (
                useDefaultBackground
                    ? ""
                    : backColor.value
            );
            if (useDefaultBackground) {
                backColor.value = tempStyle.t.bg;
            }
            backColor.disabled = useDefaultBackground;
            tempStyle[selTag].bg = colorValue;
            if ("t" === selTag) {
                testDiv.style.backgroundColor = colorValue;
            } else {
                testDraw();
            }
        },

        selectFont: function (font) {
            prevWin.style.fontFamily = font;
            previewStyles.defFont = font;
            saveStyle();
        },

        addFont: function () {
            if (fontName.value !== "") {
                tempStyle.fontSet[fontName.value] = 0;
                initSelector(removeFontSelector, tempStyle.fontSet);
                testDiv.style.fontFamily = fontName.value;
                testDraw();
            }
        },

        removeFont: function () {
            delete tempStyle.fontSet[removeFontSelector.value];
            if (removeFontSelector.value === tempStyle.defFont) {
                tempStyle.defFont = "serif";
            }
            removeFontSelector.remove(removeFontSelector.selectedIndex);
        }
    };
});
