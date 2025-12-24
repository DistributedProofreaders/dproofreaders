/* global Quill actionButton makeLabel makeCheckBox makeRadio */
/* eslint no-use-before-define: "warn" */
/* eslint camelcase: "off" */

import { makePreview } from "./show_format.js";
import { makeWordchecker } from "./word_check.js";
import { makeValidator } from "./validator.js";
import translate from "./gettext.js";

const fonts = {
    dp_sans_mono: {
        name: "DP Sans Mono",
        face: "'DP Sans Mono', monospace",
    },
    dejavu_sans_mono: {
        name: "DejaVu Sans Mono",
        face: "'DejaVu Sans Mono', monospace",
    },
    default: {
        name: translate.gettext("Browser default"),
        face: "monospace",
    },
};

function editOperations(quill) {
    return {
        // in the following "user" disables when not enabled
        surroundSelection: function (before, after) {
            let { index, length } = quill.getSelection(true);
            // move end fwd if spaces at end
            while (length > 0 && quill.getText(index + length - 1, 1) === " ") {
                length -= 1;
            }
            if (length === 0) {
                return;
            }
            // do in two parts so undo buffer does not need to hold so much
            quill.insertText(index + length, after, "user");
            quill.insertText(index, before, "user");
            quill.setSelection(index, length + before.length + after.length, "user");
        },
        transformSelection: function (func) {
            const { index, length } = quill.getSelection(true);
            const selectedText = quill.getText(index, length);
            quill.deleteText(index, length, "user");
            quill.insertText(index, func(selectedText), "user");
        },
        replaceSelection: function (text) {
            const { index, length } = quill.getSelection(true);
            // HACK to make Quill undo work correctly do not delete zero length
            // otherwise it combines inserts into one operation
            if (0 !== length) {
                quill.deleteText(index, length, "user");
            }
            quill.insertText(index, text, "user");
            quill.setSelection(index + text.length, 0, "user");
        },
        getText: function () {
            return quill.getText();
        },
    };
}

function convertPunctuation(string) {
    const conversionMap = {
        "‘": "'",
        "“": '"',
        "’": "'",
        "”": '"',
        "—": "--",
        "…": "...",
    };

    return [...string]
        .map((character) => {
            const conversion = conversionMap[character] || character;
            return conversion;
        })
        .join("");
}

function convertDiacriticalMarkup(string) {
    const above = {
        "=": "\u0304", // macron
        ":": "\u0308", // diaeresis
        ".": "\u0307", // dot
        "`": "\u0300", // grave
        "'": "\u0301", // acute
        "^": "\u0302", // circumflex
        ")": "\u0306", // breve
        "~": "\u0303", // tilde
        v: "\u030C", // caron
        "*": "\u030A", // ring
        "(": "\u0311", // inverted breve
    };

    const below = {
        "=": "\u0331", // macron
        ":": "\u0324", // diaeresis
        ".": "\u0323", // dot
        "`": "\u0316", // grave
        "'": "\u0317", // acute
        "^": "\u032D", // circumflex
        ")": "\u032E", // breve
        "~": "\u0330", // tilde
        ",": "\u0327", // cedilla
        v: "\u032C", // caron
        "*": "\u0325", // ring
        "(": "\u032F", // inverted breve
    };

    const ligatures = {
        ae: "\u00e6",
        AE: "\u00c6",
        oe: "\u0153",
        OE: "\u0152",
    };

    // if it's not a valid diacritical markup string, bail early
    if (string.length !== 4 || string[0] !== "[" || string[3] !== "]") {
        return string;
    }

    var replaceChar = ligatures[string.slice(1, 3)];
    if (replaceChar) {
        return replaceChar;
    }

    let char1 = string[1];
    let char2 = string[2];
    var code = above[char1];
    if (code) {
        replaceChar = char2 + code;
    } else {
        code = below[char2];
        if (code) {
            replaceChar = char1 + code;
        }
    }

    if (replaceChar) {
        // TODO: confirm that the normalized character is a valid one
        // for the project
        return replaceChar.normalize("NFC");
    } else {
        return string;
    }
}

function makeBasicTextWidget(editBox, userSettings) {
    const quill = new Quill(editBox, {
        modules: { toolbar: false },
        history: {
            delay: 0,
            maxStack: 500,
            userOnly: true,
        },
    });

    quill.root.setAttribute("spellcheck", false);

    quill.on("text-change", (delta, oldDelta, source) => {
        if (source == "user") {
            var retain = 0;
            [...delta.ops].map((op) => {
                if (Object.hasOwn(op, "retain")) {
                    retain = op.retain;
                } else if (Object.hasOwn(op, "insert")) {
                    // check if we should attempt diacritical conversion, we only support
                    // the user typing this in so the op.insert will always end with ]
                    if ([...op.insert].reverse()[0] == "]") {
                        // find the start and end of our markup, we can treat
                        // this as basic ASCII since all of our diacritical
                        // markup is basic ASCII
                        var maybeMarkupStartIndex = Math.max(0, retain + [...op.insert].length - 4);
                        var maybeMarkup = quill.getText(maybeMarkupStartIndex, 4);
                        const converted = convertDiacriticalMarkup(maybeMarkup);
                        if (maybeMarkup != converted) {
                            quill.deleteText(maybeMarkupStartIndex, 4);
                            quill.insertText(maybeMarkupStartIndex, converted);
                            setTimeout(() => quill.setSelection(maybeMarkupStartIndex + [...converted].length, 0), 0);
                        }
                    }
                    // if not, see if we need to convert any punctuation
                    else {
                        const converted = convertPunctuation(op.insert);
                        if (op.insert != converted) {
                            quill.deleteText(retain, [...op.insert].length);
                            quill.insertText(retain, converted);
                            if ([...op.insert].length != [...converted].length) {
                                setTimeout(() => quill.setSelection(retain + [...converted].length, 0), 0);
                            }
                        }
                    }
                }
            });
        }
    });

    const qlEditor = editBox.firstChild;

    function setFontSize() {
        qlEditor.style.fontSize = userSettings.fontSize;
    }

    userSettings.fontSize ?? (userSettings.fontSize = "12pt");

    function setFontFace() {
        qlEditor.style.fontFamily = fonts[userSettings.fontId].face;
    }

    userSettings.textWrap ?? (userSettings.textWrap = false);

    function setWrap() {
        qlEditor.style.whiteSpace = userSettings.textWrap ? "pre-wrap" : "pre";
    }

    userSettings.fontId ?? (userSettings.fontId = "dp_sans_mono");

    return {
        quill,
        qlEditor,
        setText: function (text) {
            quill.setText(text);
        },
        setFontSize,
        setFontFace,
        setWrap,
    };
}

export function makeQuizTextWidget(editBox, userSettings) {
    const { quill, setText, setFontSize, setFontFace, setWrap } = makeBasicTextWidget(editBox, userSettings);
    const { surroundSelection, transformSelection, replaceSelection, getText } = editOperations(quill);

    setFontSize();
    setFontFace();
    setWrap();

    return {
        surroundSelection,
        transformSelection,
        replaceSelection,
        setText,
        getText,
    };
}

export function makeTextWidget(container, userSettings) {
    const controlBar = document.createElement("div");
    controlBar.classList.add("simple_bar", "top_settings_box");

    const settingsDlg = document.createElement("dialog");
    container.append(settingsDlg);

    const controlRow = document.createElement("p");

    const extraSettings = document.createElement("div");

    const doneButton = actionButton(translate.gettext("Done"));

    const editBox = document.createElement("div");
    editBox.classList.add("stretch-box", "overflow-hidden");

    const numberText = document.createElement("div");
    numberText.classList.add("stretch-box", "row_flex");

    const numberColumn = document.createElement("div");
    numberColumn.classList.add("fixed-box");
    numberColumn.id = "number_col";

    numberText.append(numberColumn, editBox);

    const {
        quill,
        qlEditor,
        setText: basicSetText,
        setFontSize: basicSetFontSize,
        setFontFace,
        setWrap: basicSetWrap,
    } = makeBasicTextWidget(editBox, userSettings);

    const lineElements = qlEditor.children;
    function numberLines() {
        numberColumn.innerHTML = "";
        let lineNumber = 1;
        for (const child of lineElements) {
            const para = child.getBoundingClientRect();
            const pnumb = document.createElement("p");
            numberColumn.append(pnumb);
            pnumb.textContent = lineNumber;
            pnumb.classList.add("numb_para");
            pnumb.style.top = `${para.top}px`;
            lineNumber += 1;
        }
    }

    qlEditor.addEventListener("scroll", function () {
        numberLines();
    });

    const onDoneSettings = new Set();

    doneButton.addEventListener("click", function () {
        for (const func of onDoneSettings) {
            func();
        }
        settingsDlg.close();
    });

    // for polytonic greek
    qlEditor.style.padding = "0 0 0 0.6em";

    function setParaSpacing(lineHeight) {
        qlEditor.style.lineHeight = lineHeight;
        numberColumn.style.lineHeight = lineHeight;
        numberLines();
    }

    const lineHeight = userSettings.lineHeight ?? 1.6;
    setParaSpacing(lineHeight);

    const fontFaceSelector = document.createElement("select");
    for (const fontId of Object.keys(fonts)) {
        fontFaceSelector.add(new Option(fonts[fontId].name, fontId));
    }

    fontFaceSelector.addEventListener("change", function () {
        userSettings.fontId = this.value;
        setFontFace();
        numberLines();
    });

    const fontControl = makeLabel([translate.gettext("Font") + ": ", fontFaceSelector]);

    const fontSizeSelector = document.createElement("select");
    const fontSizes = [10, 12, 14, 17, 20, 24, 30, 36, 44];
    fontSizes.forEach(function (fontSize) {
        fontSizeSelector.add(new Option(`${fontSize}pt`, `${fontSize}pt`));
    });

    function setFontSize() {
        basicSetFontSize();
        numberColumn.style.fontSize = userSettings.fontSize;
        numberLines();
    }

    fontSizeSelector.addEventListener("change", function () {
        userSettings.fontSize = fontSizeSelector.value;
        setFontSize();
    });

    const fontSizeControl = makeLabel([translate.gettext("Size") + ": ", fontSizeSelector]);

    function setWrap() {
        basicSetWrap();
        numberLines();
    }

    const wrapCheck = makeCheckBox();
    wrapCheck.addEventListener("change", function () {
        userSettings.textWrap = this.checked;
        setWrap();
    });
    const wrapControl = makeLabel([wrapCheck, translate.gettext("Wrap")]);

    controlRow.append(fontControl, fontSizeControl, wrapControl);

    function setText(text) {
        basicSetText(text);
        numberLines();
    }

    fontSizeSelector.value = userSettings.fontSize;
    setFontSize();

    fontFaceSelector.value = userSettings.fontId;
    setFontFace();

    wrapCheck.checked = userSettings.textWrap;
    setWrap();

    const onSettings = new Set();
    const settingsButton = actionButton(translate.gettext("Settings"));
    settingsButton.addEventListener("click", function () {
        for (const func of onSettings) {
            func();
        }
        settingsDlg.showModal();
    });

    settingsDlg.append(controlRow, extraSettings, doneButton);
    controlBar.append(settingsButton);

    container.append(controlBar, numberText);

    return {
        setup: function (splitVertical) {
            if (container.contains(controlBar)) {
                container.removeChild(controlBar);
            }
            if (splitVertical) {
                container.prepend(controlBar);
                // top right bottom left
                controlBar.style.borderWidth = "0 0 1px 0";
            } else {
                container.append(controlBar);
                controlBar.style.borderWidth = "1px 0 0 0";
            }
        },

        reLayout: function () {
            numberLines();
        },

        setText,
        quill,
        editBox,
        controlBar,
        setParaSpacing,
        qlEditor,
        extraSettings,
        onDoneSettings,
    };
}

export function makeProofTextWidget(container, projectId, userSettings, languagesWithDictionaries, projectLanguages) {
    const Parchment = Quill.import("parchment");
    const config = { scope: Parchment.Scope.INLINE };

    const qTitle = new Parchment.Attributor("title", "title", config);
    Quill.register(qTitle);

    config.whiteList = ["italic"];
    const qfontStyle = new Parchment.StyleAttributor("fontStyle", "font-style", config);
    Quill.register(qfontStyle);

    const qfontWeight = new Parchment.StyleAttributor("fontWeight", "font-weight", config);
    Quill.register(qfontWeight);

    const qfontVariant = new Parchment.StyleAttributor("fontVariant", "font-variant", config);
    Quill.register(qfontVariant);

    const qTextTransform = new Parchment.StyleAttributor("textTransform", "text-transform", config);
    Quill.register(qTextTransform);

    const qLetterSpacing = new Parchment.StyleAttributor("letterSpacing", "letter-spacing", config);
    Quill.register(qLetterSpacing);

    const qMarginRight = new Parchment.StyleAttributor("marginRight", "margin-right", config);
    Quill.register(qMarginRight);

    const qTextDecoration = new Parchment.StyleAttributor("textDecoration", "text-decoration", config);
    Quill.register(qTextDecoration);

    config.whiteList = ["none"];
    const qHide = new Parchment.StyleAttributor("display", "display", config);
    Quill.register(qHide);

    const { setup, reLayout, setText, quill, editBox, controlBar, setParaSpacing, qlEditor, extraSettings, onDoneSettings } = makeTextWidget(
        container,
        userSettings,
    );

    const lineSpacer = document.createElement("input");
    lineSpacer.type = "range";
    lineSpacer.classList.add("middle-align");
    lineSpacer.min = "1.5";
    lineSpacer.max = "3";
    lineSpacer.step = "0.01";
    lineSpacer.title = translate.gettext("Adjust the line spacing");
    lineSpacer.addEventListener("input", (event) => {
        const lineHeight = event.target.value;
        setParaSpacing(lineHeight);
        userSettings.lineHeight = lineHeight;
    });

    const lineHeight = userSettings.lineHeight ?? 1.6;
    setParaSpacing(lineHeight);
    lineSpacer.value = lineHeight;

    let oldScroll = qlEditor.scrollTop;
    const scrollListeners = new Set();
    // send difference in scroll position
    qlEditor.addEventListener("scroll", function () {
        const newScroll = qlEditor.scrollTop;
        const deltaScroll = newScroll - oldScroll;
        oldScroll = newScroll;
        scrollListeners.forEach(function (scrollCallBack) {
            scrollCallBack(deltaScroll);
        });
    });

    let leaveText = function () {};
    let leave = leaveText;

    const wordChecker = makeWordchecker(projectId, quill, languagesWithDictionaries, projectLanguages, editBox, extraSettings, onDoneSettings, scrollListeners);

    const statSpan = document.createElement("span");

    // userSettings.formatting ??= {}; // needs chrome 85, FF 79, Safari 14
    userSettings.formatting ?? (userSettings.formatting = {});
    const formatter = makePreview(userSettings.formatting, quill, extraSettings, statSpan);

    const textOnlyRadio = makeRadio("viewMode");
    textOnlyRadio.checked = true;
    textOnlyRadio.addEventListener("click", function () {
        leave();
        leave = leaveText;
    });
    const textOnlyControl = makeLabel([textOnlyRadio, translate.gettext("Text Only")]);

    const wordCheckRadio = makeRadio("viewMode");
    wordCheckRadio.addEventListener("click", function () {
        leave();
        wordChecker.enter();
        leave = wordChecker.leave;
    });
    const wordCheckControl = makeLabel([wordCheckRadio, translate.gettext("WordCheck")]);

    const formatPreviewRadio = makeRadio("viewMode");
    formatPreviewRadio.addEventListener("click", function () {
        leave();
        formatter.enter();
        leave = formatter.leave;
    });
    const formatPreviewControl = makeLabel([formatPreviewRadio, translate.gettext("Format Preview")]);

    controlBar.prepend(textOnlyControl, wordCheckControl, formatPreviewControl);
    controlBar.append(lineSpacer, statSpan);

    const validator = makeValidator(projectId, quill);

    function showValidate() {
        validator.enter();
    }

    function toTextMode() {
        leave();
        leave = leaveText;
        textOnlyRadio.checked = true;
    }

    const { surroundSelection, transformSelection, replaceSelection, getText } = editOperations(quill);

    return {
        setup,
        reLayout,
        scrollListeners,
        getWCState: wordChecker.getWCState,
        setText,
        getText,
        initWordCheck: wordChecker.initialise,
        showValidate,
        toTextMode,
        surroundSelection,
        transformSelection,
        replaceSelection,
    };
}
