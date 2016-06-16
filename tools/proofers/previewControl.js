/*global
    previewMessages, previewDemo, top, style1, makePreview, window
*/
var previewControl;
function initPrev() {
    "use strict";
    var outerPrev = document.getElementById("id_tp_outer");
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
    var tRadio = document.getElementById("id_tradio");
    var enableColorCheckbox = document.getElementById("id_color_on");
    var issBox = document.getElementById("id_iss");
    var possIssBox = document.getElementById("id_poss_iss");
    var fontSelector = document.getElementById("id_font_sel");
    var fontName = document.getElementById("id_font_name");
    var removeFontSelector = document.getElementById("id_remove_sel");
    var proofFrameSet = top.document.getElementById("proof_frames");
    var selTag;
    var func = "NT";    // always start with this

    var tempStyle = {};
    var style1 = {
        t: {bg: "#fffcf4", fg: "#000000"},
        i: {bg: "", fg: "#0000ff"},
        b: {bg: "", fg: "#c55a1b"},
        g: {bg: "", fg: "#8a2be2"},
        sc: {bg: "", fg: "#009700"},
        f: {bg: "", fg: "#ff0000"},
        etc: {bg: "#ffcaaf", fg: ""},
        err: {bg: "#ff0000", fg: ""},
        hlt: {bg: "#ceff09", fg: ""},
        color: true,
        fontList: ["serif", "sans-serif", "monospace", "DPCustomMono2"],
        defFont: "serif"
    };
    var old_rows;
    var font_size;
    var preview;

    function write1() {
        preview = makePreview(txtarea.value, func, style1, previewMessages);
        prevWin.style.whiteSpace = (preview.ok && (func === "RW")) ? "normal" : "pre";
        prevWin.innerHTML = preview.txtout;
        issBox.value = preview.issues;
        possIssBox.value = preview.possIss;
        if (!preview.ok) {
            tagon.checked = true;
            func = "T";
        }
    }

    function setColors(win) {
        win.style.backgroundColor = style1.t.bg;
        win.style.color = style1.t.fg;
    }

    function initSel(selector, optionList, def) {
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

// so old data compatible keep all of dest, overwrite with source
    function deepCopy(dest, source, keep) {
        var i;
        if (source && typeof source === 'object') {
            if (!dest || !keep) {
                dest = Array.isArray(source) ? [] : {};
            }
            for (i in source) {
                dest[i] = deepCopy(dest[i], source[i], keep);
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
            style1 = deepCopy(style1, style0, true);
        }
    }

    function initView() {
        initSel(fontSelector, style1.fontList, style1.defFont);
        prevWin.style.fontFamily = style1.defFont;
        enableColorCheckbox.checked = style1.color;
        setColors(outerPrev);
    }

    initStyle();
    initView();

    function testDraw() {
        preview = makePreview(previewDemo, 'T', tempStyle, previewMessages);
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

    function adjHeight() {
        outerPrev.style.bottom = window.getComputedStyle(controlDiv, null).height;
    }

    function hideConfig() {
        prevDiv.style.display = "block";
        configPan.style.display = "none";
        adjHeight();
    }

    function saveStyle() {
        localStorage.preview_data = JSON.stringify(style1);
    }

    return {
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

        show: function () {
            old_rows = proofFrameSet.getAttribute("rows");
            proofFrameSet.setAttribute("rows", "*,1");
            proofDiv.style.display = "none";
            prevDiv.style.display = "block";
            font_size = parseFloat(window.getComputedStyle(txtarea, null).fontSize);
            this.reSizeText(1.0);
            write1();
            adjHeight();
        },

        hide: function () {
            proofFrameSet.setAttribute("rows", old_rows);
            proofDiv.style.display = "block";
            prevDiv.style.display = "none";
        },

        write: function (f) {
            func = f;
            write1();
        },

        configure: function () {
            prevDiv.style.display = "none";
            configPan.style.display = "block";
            setColors(testDiv);     // initialising so same as style1
            tempStyle = deepCopy(tempStyle, style1, false);
            initSel(removeFontSelector, tempStyle.fontList);
            testDiv.style.fontFamily = tempStyle.defFont;
            testDraw();
            selTag = "t";   // always start with t selected
            tRadio.checked = true;
            initPicker();
        },

        enableColor: function (en) {
            style1.color = en;
            saveStyle();
            write1();
        },

        setColors: function (val) { // when i b ... radio checked
            selTag = val;
            initPicker();
        },

        OKConfig: function () {
            style1 = deepCopy(style1, tempStyle, false);
            saveStyle();
            initView();
            write1();
            hideConfig();
        },

        cancelConfig: function () {
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
            style1.defFont = font;
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
