/* global Quill makePreview makeWordchecker makeValidator actionButton makeLabel makeCheckBox makeRadio */
/* exported makeTextWidget */
/* exported makeProofTextWidget */
/* exported makeQuizTextWidget */
/* eslint no-use-before-define: "warn" */

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

function makeBasicTextWidget(editBox, userSettings, widgetText) {
    const quill = new Quill(editBox, {
        modules: { toolbar: false },
        history: {
            delay: 0,
            maxStack: 500,
            userOnly: true,
        },
    });

    quill.root.setAttribute("spellcheck", false);

    const qlEditor = editBox.firstChild;

    function setFontSize() {
        qlEditor.style.fontSize = userSettings.fontSize;
    }

    userSettings.fontSize ?? (userSettings.fontSize = "12pt");

    function setFontFace() {
        qlEditor.style.fontFamily = widgetText.fonts[userSettings.fontId].face;
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

function makeQuizTextWidget(editBox, userSettings, widgetText) {
    const { quill, setText, setFontSize, setFontFace, setWrap } = makeBasicTextWidget(editBox, userSettings, widgetText);
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

function makeTextWidget(container, userSettings, widgetText) {
    const controlBar = document.createElement("div");
    controlBar.classList.add("simple_bar", "top_settings_box");

    const settingsDlg = document.createElement("dialog");
    container.append(settingsDlg);

    const controlRow = document.createElement("p");

    const extraSettings = document.createElement("div");

    const doneButton = actionButton(widgetText.done);

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
    } = makeBasicTextWidget(editBox, userSettings, widgetText);

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
    for (const fontId of Object.keys(widgetText.fonts)) {
        fontFaceSelector.add(new Option(widgetText.fonts[fontId].name, fontId));
    }

    fontFaceSelector.addEventListener("change", function () {
        userSettings.fontId = this.value;
        setFontFace();
        numberLines();
    });

    const fontControl = makeLabel([widgetText.font + ": ", fontFaceSelector]);

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

    const fontSizeControl = makeLabel([widgetText.size + ": ", fontSizeSelector]);

    function setWrap() {
        basicSetWrap();
        numberLines();
    }

    const wrapCheck = makeCheckBox();
    wrapCheck.addEventListener("change", function () {
        userSettings.textWrap = this.checked;
        setWrap();
    });
    const wrapControl = makeLabel([wrapCheck, widgetText.wrap]);

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
    const settingsButton = actionButton(widgetText.settings);
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

function makeProofTextWidget(container, projectId, userSettings, languagesWithDictionaries, projectLanguages, proofText, widgetText) {
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

    const Embed = Quill.import("blots/embed");
    class DFormula extends Embed {
        static blotName = "dformula";
        static className = "ql-dformula";
        static tagName = "SPAN";

        static create(value) {
            // @ts-expect-error
            if (window.katex == null) {
                throw new Error("Formula module requires KaTeX.");
            }
            const node = super.create(value);
            if (typeof value === "string") {
                // @ts-expect-error
                window.katex.render(value, node, {
                    throwOnError: false,
                    errorColor: "#f00",
                    displayMode: true,
                    // output: "mathml",
                });
                node.setAttribute("data-value", value);
            }
            return node;
        }

        static value(domNode) {
            return domNode.getAttribute("data-value");
        }

        html() {
            const { dformula } = this.value();
            return `<span>${dformula}</span>`;
        }
    }

    Quill.register(DFormula);

    const { setup, reLayout, setText, quill, editBox, controlBar, setParaSpacing, qlEditor, extraSettings, onDoneSettings } = makeTextWidget(
        container,
        userSettings,
        widgetText,
    );

    const lineSpacer = document.createElement("input");
    lineSpacer.type = "range";
    lineSpacer.classList.add("va_middle");
    lineSpacer.min = "1.5";
    lineSpacer.max = "3";
    lineSpacer.step = "0.01";
    lineSpacer.title = proofText.adjustLineSpace;
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

    const wordChecker = makeWordchecker(
        projectId,
        quill,
        languagesWithDictionaries,
        projectLanguages,
        editBox,
        proofText,
        extraSettings,
        onDoneSettings,
        scrollListeners,
    );

    const statSpan = document.createElement("span");

    // userSettings.formatting ??= {}; // needs chrome 85, FF 79, Safari 14
    userSettings.formatting ?? (userSettings.formatting = {});
    const formatter = makePreview(userSettings.formatting, proofText, quill, extraSettings, statSpan);

    const textOnlyRadio = makeRadio("viewMode");
    textOnlyRadio.checked = true;
    textOnlyRadio.addEventListener("click", function () {
        leave();
        leave = leaveText;
    });
    const textOnlyControl = makeLabel([textOnlyRadio, proofText.textOnly]);

    const wordCheckRadio = makeRadio("viewMode");
    wordCheckRadio.addEventListener("click", function () {
        leave();
        wordChecker.enter();
        leave = wordChecker.leave;
    });
    const wordCheckControl = makeLabel([wordCheckRadio, proofText.wordCheck]);

    const formatPreviewRadio = makeRadio("viewMode");
    formatPreviewRadio.addEventListener("click", function () {
        leave();
        formatter.enter();
        leave = formatter.leave;
    });
    const formatPreviewControl = makeLabel([formatPreviewRadio, proofText.formatPreview]);

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
