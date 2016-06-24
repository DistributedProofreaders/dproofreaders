/*global previewDemo, top, previewStyles, makePreview, window, alert, ieWarn */
/*
This file controls the user interface functions. Initially nothing is displayed because "prevdiv" has diplay:none; which means it is not displayed and the page is laid out as if it was not there.
When the preview button is pressed the function "show" changes its display style to "block" and changes the display style of the normal view (proofDiv) to "none". This is reversed when "Quit" is pressed.
The configuration screen is handled in the same way.
previewDemo, ieWarn are loaded by the function output_preview_strings() defined in preview.inc, called in preview_strings.php
*/
var previewControl;
// this is called (in proof_frame_enh.inc or text_frame_std.inc) when the preview button is pressed, the returned functions are then members of previewControl
function initPrev() {
    "use strict";
    var outerPrev = document.getElementById("id_tp_outer"); // this is a wrapper round text_preview which enables the padding on the right to appear correctly
    var prevWin = document.getElementById("text_preview");
    var txtarea = document.getElementById("text_data");
    var prevDiv = document.getElementById("prevdiv");
    var controlDiv = document.getElementById("id_controls");
    var tagon = document.getElementById("id_tags");
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
    var proofFrameSet = top.document.getElementById("proof_frames");
    var selTag;
    var viewMode = "no_tags";    // always start with this

    var tempStyle = {}; // used during configure

// these are the default values. If the user changes anything the new styles are saved in local storage and reloaded next time.
    var previewStyles = {
// the foreground and background colours for plain text, italic, bold, gesperrt, smallcaps, font change, other tags, highlighting issues and possible issues
        t: {bg: "#fffcf4", fg: "#000000"},
        i: {bg: "", fg: "#0000ff"},
        b: {bg: "", fg: "#c55a1b"},
        g: {bg: "", fg: "#8a2be2"},
        sc: {bg: "", fg: "#009700"},
        f: {bg: "", fg: "#ff0000"},
        etc: {bg: "#ffcaaf", fg: ""},
        err: {bg: "#ff0000", fg: ""},
        hlt: {bg: "#ceff09", fg: ""},
        color: true, // colour the markup or not
        fontList: ["serif", "sans-serif", "monospace", "DPCustomMono2"],
        defFont: "serif"
    };
    var old_rows; // stores the size of the bottom frame so it can be restored on exit
    var font_size;
    var preview;

    function writePreviewText() {
        preview = makePreview(txtarea.value, viewMode, previewStyles); // in preview.js
        prevWin.style.whiteSpace = (preview.ok && (viewMode === "re_wrap")) ? "normal" : "pre";
        prevWin.innerHTML = preview.txtout;
        issBox.value = preview.issues;
        possIssBox.value = preview.possIss;
        if (!preview.ok) {  // if there are any issues the tags will be shown so make the radio buttons agree
            tagon.checked = true;
            viewMode = "show_tags";
        }
    }

    function setColors(win) { // sets background and plain text colours
        win.style.backgroundColor = previewStyles.t.bg;
        win.style.color = previewStyles.t.fg;
    }

    function initSel(selector, optionList, def) { // sets up the font selector
        var i;
        var opt;
// remove all incase revisiting, last first
        for (i = selector.length - 1; i >= 0; i -= 1) {
            selector.remove(i);
        }
        if (!optionList) {
            return;
        }
        optionList.sort();  // this will change the original array
        for (i = 0; i < optionList.length; i += 1) {
            opt = document.createElement("option");
            opt.text = opt.value = optionList[i];
            if (opt.value === def) {
                opt.selected = true;
            }
            selector.add(opt);
        }
    }

// this makes a copy of the style data
// js assignment of objects just makes a reference to the old object so we need to copy each primitive value and construct new objects or arrays if necessary
    function deepCopy(dest, source) {
        var i;
        if (source && typeof source === 'object') {
            if (!dest) {
                dest = Array.isArray(source) ? [] : {};
            }
            for (i in source) {
                dest[i] = deepCopy(dest[i], source[i]);
            }
        } else {
            dest = source;
        }
        return dest;
    }

// if during development a new element is added to styles, this will retain its default value but copy the rest from stored data
    function initStyle() {
        var style0;
        if (localStorage.getItem('preview_data')) {
            style0 = JSON.parse(localStorage.preview_data);
            previewStyles = deepCopy(previewStyles, style0);
        }
    }

    function initView() {
        initSel(fontSelector, previewStyles.fontList, previewStyles.defFont);
        prevWin.style.fontFamily = previewStyles.defFont;
        enableColorCheckbox.checked = previewStyles.color;
        setColors(outerPrev);
    }

    initStyle();
    initView();

// functions for setting up the configuration screen
    function testDraw() {
        preview = makePreview(previewDemo, 'T', tempStyle);
        testDiv.innerHTML = preview.txtout;
    }

    function initPicker() { // initialise the color pickers
        var backgroundColor = tempStyle[selTag].bg;
        var foregroundColor = tempStyle[selTag].fg;
        var useDefaultBackground, useDefaultForeground;
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
        foreColor.value = useDefaultForeground ? tempStyle.t.fg : foregroundColor;
        backgroundCheckbox.checked = useDefaultBackground;
        backColor.disabled = useDefaultBackground;
        backColor.value = useDefaultBackground ? tempStyle.t.bg : backgroundColor; // make it look correct
    }

// The control buttons etc. in "controlDiv" will "wrap" according to the window width so the div height will vary.
// this function adjusts the bottom of the preview text area to fit.
    function adjHeight() {
        outerPrev.style.bottom = window.getComputedStyle(controlDiv, null).height;
    }

    function hideConfig() {
        prevDiv.style.display = "block";
        configPan.style.display = "none";
        adjHeight();
    }

    function saveStyle() {
        localStorage.preview_data = JSON.stringify(previewStyles);
    }

    return {
// this is used inside the "hover" markup to move the hover box (by adjusting its margin) so it does not disappear off the edge of the screen
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
            proofFrameSet.setAttribute("rows", "*,1");  // make the bottom frame very small
            proofDiv.style.display = "none";
            prevDiv.style.display = "block";
            font_size = parseFloat(window.getComputedStyle(txtarea, null).fontSize);
            this.reSizeText(1.0);
            writePreviewText();
            adjHeight();
        },

        hide: function () {
            proofFrameSet.setAttribute("rows", old_rows);   // restore the bottom frame
            proofDiv.style.display = "block";
            prevDiv.style.display = "none";
        },

        write: function (f) { // called when "Tags", "no Tags" or rewrap radio buttons are depressed
            viewMode = f;
            writePreviewText();
        },

        configure: function () {    // show the configuration screen
            prevDiv.style.display = "none";
            configPan.style.display = "block";
            setColors(testDiv);     // initialising so same as previewStyles
// make a copy of the styles so that if we cancel we can go back to how it was before.
            tempStyle = deepCopy(tempStyle, previewStyles);
            initSel(removeFontSelector, tempStyle.fontList);
            testDiv.style.fontFamily = tempStyle.defFont;
            testDraw();
            selTag = "t";   // always start with t (plain text) selected
            defaultTextRadio.checked = true;
            initPicker();
        },

        enableColor: function (en) {
            previewStyles.color = en;
            saveStyle();
            writePreviewText();
        },

        setColors: function (val) { // when i b ... radio checked
            selTag = val;
            initPicker();
        },

        OKConfig: function () {
            previewStyles = deepCopy(previewStyles, tempStyle);
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
            var colorValue = useDefaultForeground ? "" : foreColor.value;
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

        setBackgroundColor: function () {  // background colour or transparency changed
            var useDefaultBackground = backgroundCheckbox.checked;
            var colorValue = useDefaultBackground ? "" : backColor.value;
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

        adjustHeight: function () { // to fit control box
            adjHeight();
        },

        selectFont: function (font) {
            prevWin.style.fontFamily = font;
            previewStyles.defFont = font;
            saveStyle();
        },

        addFont: function () {
            if (fontName.value !== "") {
                tempStyle.fontList.push(fontName.value);
                initSel(removeFontSelector, tempStyle.fontList);
                testDiv.style.fontFamily = fontName.value;
                testDraw();
            }
        },

        removeFont: function () {
            tempStyle.fontList.splice(removeFontSelector.selectedIndex, 1);
            if (removeFontSelector.value === tempStyle.defFont) {
                tempStyle.defFont = tempStyle.fontList[0];
            }
            removeFontSelector.remove(removeFontSelector.selectedIndex);
        }
    };
}
