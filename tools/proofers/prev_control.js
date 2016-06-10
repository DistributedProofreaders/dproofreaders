/*global
    previewMessages, previewDemo, top, style1, doPrev, window
*/
var PrevControl;
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
    var bkBox = document.getElementById("id_bkbox");
    var spanBkBox = document.getElementById("id_span_bkbox");
    var frBox = document.getElementById("id_frbox");
    var spanFrBox = document.getElementById("id_span_frbox");
    var foreColor = document.getElementById("id_forecol");
    var backColor = document.getElementById("id_backcol");
    var configPan = document.getElementById("id_config_panel");
    var tRadio = document.getElementById("id_tradio");
    var encolBox = document.getElementById("id_colon");
    var issBox = document.getElementById("id_iss");
    var possIssBox = document.getElementById("id_poss_iss");
    var fontSelector = document.getElementById("id_font_sel");
    var fontName = document.getElementById("id_font_name");
    var fontSel1 = document.getElementById("id_fsel1");
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
        preview = doPrev(txtarea.value, func, style1, previewMessages);
        prevWin.style.whiteSpace = (preview.ok && (func === "RW")) ? "normal" : "pre";
        prevWin.innerHTML = preview.txtout;
        issBox.value = preview.issues;
        possIssBox.value = preview.possIss;
        if (!preview.ok) {
            tagon.checked = true;
            func = "T";
        }
    }

    function setWinCol(win) {
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
        encolBox.checked = style1.color;
        setWinCol(outerPrev);
    }

    initStyle();
    initView();

    function testDraw() {
        preview = doPrev(previewDemo, 'T', tempStyle, previewMessages);
        testDiv.innerHTML = preview.txtout;
    }

    function initPicker() { // initialise the color pickers
        var bcol = tempStyle[selTag].bg;
        var fcol = tempStyle[selTag].fg;
        var bkDef, frDef;
        if (selTag === "t") {
            spanBkBox.style.visibility = "hidden";
            bkDef = false;
            spanFrBox.style.visibility = "hidden";
            frDef = false;
        } else {
            spanBkBox.style.visibility = "visible";
            bkDef = ('' === bcol);
            spanFrBox.style.visibility = "visible";
            frDef = ('' === fcol);
        }
        frBox.checked = frDef;
        foreColor.disabled = frDef;
        foreColor.value = frDef ? tempStyle.t.fg : fcol;
        bkBox.checked = bkDef;
        backColor.disabled = bkDef;
        backColor.value = bkDef ? tempStyle.t.bg : bcol; // make it look correct
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
            var conBox = outerPrev.getBoundingClientRect();
            if (hovBox.right >= conBox.right) {
                hov.style.marginLeft = -hovBox.width + "px";
            }
            if (hovBox.left <= conBox.left) {
                hov.style.marginLeft = "0";
            }
            if (hovBox.bottom + 17 >= conBox.bottom) { // 17 allows for scrollbar
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
            setWinCol(testDiv);     // initialising so same as style1
            tempStyle = deepCopy(tempStyle, style1, false);
            initSel(fontSel1, tempStyle.fontList);
            testDiv.style.fontFamily = tempStyle.defFont;
            testDraw();
            selTag = "t";   // always start with t selected
            tRadio.checked = true;
            initPicker();
        },

        enableCol: function (en) {
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

        setFCol: function () {  // foreground colour changed
            var frDef = frBox.checked;
            var colval = frDef ? "" : foreColor.value;
            if (frDef) {
                foreColor.value = tempStyle.t.fg;
            }
            foreColor.disabled = frDef;
            tempStyle[selTag].fg = colval;
            if ("t" === selTag) {
                testDiv.style.color = colval;
            } else {
                testDraw();
            }
        },

        setBCol: function () {  // background colour or transparency changed
            var bkDef = bkBox.checked;
            var colval = bkDef ? "" : backColor.value;
            if (bkDef) {
                backColor.value = tempStyle.t.bg;
            }
            backColor.disabled = bkDef;
            tempStyle[selTag].bg = colval;
            if ("t" === selTag) {
                testDiv.style.backgroundColor = colval;
            } else {
                testDraw();
            }
        },

        adjustHeight: function () { // to fit control box
            adjHeight();
        },

        selFont: function (font) {
            prevWin.style.fontFamily = font;
            style1.defFont = font;
            saveStyle();
        },

        addFont: function () {
            if (fontName.value !== "") {
                tempStyle.fontList.push(fontName.value);
                initSel(fontSel1, tempStyle.fontList);
                testDiv.style.fontFamily = fontName.value;
                testDraw();
            }
        },

        remFont: function () {
            tempStyle.fontList.splice(fontSel1.selectedIndex, 1);
            if (fontSel1.value === tempStyle.defFont) {
                tempStyle.defFont = tempStyle.fontList[0];
            }
            fontSel1.remove(fontSel1.selectedIndex);
        }
    };
}
