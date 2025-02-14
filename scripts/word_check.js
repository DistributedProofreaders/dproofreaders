/* global ajax hide show actionButton */
/* exported makeWordchecker */

function makeWordchecker(projectId, quill, languagesWithDictionaries, projectLanguages, editBox, numberLines, proofText, controls, controlBar, enterTextMode) {
    const wcControlSpan = document.createElement("span");
    hide(wcControlSpan);
    controls.prepend(wcControlSpan);

    const wcQuitButton = actionButton(proofText.quitWC);
    const langButton = actionButton(proofText.languages);
    wcControlSpan.append(wcQuitButton, langButton);

    const langDiv = document.createElement("div");
    langDiv.classList.add("fixed-box");
    hide(langDiv);
    const langGrid = document.createElement("div");
    langGrid.classList.add("grid");
    const langDoneButton = actionButton(proofText.ok);
    langDiv.append(langGrid, langDoneButton);
    controlBar.append(langDiv);

    const puncCharacters = ".,;:?!*/()#@%+=[]{}<>\"$|_¬¢£¥©®§°±¶·'´¸º×¦¡¿-»«¯÷¹²³¼½¾¤";

    const acceptButton = document.createElement("button");
    acceptButton.type = "button";
    acceptButton.classList.add("accept_button");
    acceptButton.innerText = proofText.accept;

    const languages = [projectLanguages[0]];

    for (const language of Object.values(languagesWithDictionaries)) {
        const cBox = document.createElement("input");
        cBox.type = "checkbox";
        if (language === projectLanguages[0]) {
            cBox.checked = true;
        }
        const label = document.createElement("label");
        label.classList.add("no_wrap");
        label.append(cBox, language);
        langGrid.append(label);
    }

    // put anywhere
    wcControlSpan.append(acceptButton);

    langButton.addEventListener("click", function () {
        show(langDiv);
        hide(controls);
        hide(acceptButton);
        numberLines();
    });

    langDoneButton.addEventListener("click", function () {
        languages.length = 0;
        const langCheckBoxes = langGrid.getElementsByTagName("input");
        for (const box of langCheckBoxes) {
            if (box.checked) {
                languages.push(box.nextSibling.textContent);
            }
        }
        hide(langDiv);
        show(controls);
        numberLines();
        hide(acceptButton);
        wordCheck();
    });

    let caretPos = 0;

    const WC_WORLD = 1;

    function splitText(text) {
        //const base = "\\w{1,2}";    // pattern for: markable base character
        //const mark = '[=:.`\'v)(~,^*]'; // pattern for: diacritical mark
        //const bracketedCharacterPattern = `\\[(?:oe|OE|${mark}${base}|${base}${mark})\\]`;
        //const charPattern = `(?:\\p{L}\\p{M}*|${bracketedCharacterPattern})`;
        const charPattern = "(?:\\p{L}\\p{M}*)";
        // This is used when splitting a text into words.
        const wordPattern = new RegExp(`${charPattern}+(?:'${charPattern}+)*`, "ug");
        //        const wordPattern = /(?:\w\p{M}*)+/gu;
        let result;
        let wordsWithOffsets = [];
        while ((result = wordPattern.exec(text)) !== null) {
            // string, start index, end index
            wordsWithOffsets.push([result[0], result.index, wordPattern.lastIndex]);
        }
        return wordsWithOffsets;
    }

    let acceptableWord = "";

    function maybeShowAcceptButton() {
        // show accept button for suggestible word when caret is in it
        const { index, length } = quill.getSelection(true);
        if (length !== 0) {
            // only if no selection
            return;
        }
        const format = quill.getFormat(index);
        if (format.underline) {
            // it is an acceptable word
            const [leaf] = quill.getLeaf(index);
            acceptableWord = leaf.text;
            const bounds = quill.getBounds(index);
            const editRect = editBox.getBoundingClientRect();
            acceptButton.style.top = `${editRect.top + bounds.top + bounds.height}px`;
            acceptButton.style.left = `${editRect.left + bounds.left}px`;
            acceptButton.style.display = "block";
        } else {
            hide(acceptButton);
        }
    }

    // on change, delay, then reload. If change again restart delay.
    // Avoid caret misplaced after reload:
    let timerID = null;
    let pageText;

    // when a change occurs submit a wordcheck request, but to avoid several
    // in quick succession, set or reset a timer on each. When it times out
    // no changes have occured for e.g. 2 seconds, submit a request.
    function triggerReload() {
        clearTimeout(timerID);
        timerID = setTimeout(wordCheck, 2000);
    }

    function showWordCheck(wcData) {
        // check that text and cursor pos. has not changed while waiting
        // for response.
        const { index: newCaretPos } = quill.getSelection(true);
        const newText = quill.getText();
        if (newCaretPos !== caretPos || newText !== pageText) {
            // another change has happened since we submitted wordcheck
            caretPos = newCaretPos;
            pageText = newText;
            triggerReload();
            return;
        }

        const badWords = wcData.bad_words;
        const wordsWithOffsets = splitText(pageText);
        quill.setText(pageText, "silent");

        function checkPunc(index, end) {
            while (index < end) {
                const char = pageText.charAt(index);
                if (puncCharacters.includes(char)) {
                    quill.formatText(index, 1, { background: "yellow" }, "silent");
                }
                index += 1;
            }
        }

        let puncIndex = 0;
        for (const [word, startOffset, endOffset] of wordsWithOffsets) {
            // look for punc between words
            checkPunc(puncIndex, startOffset);
            puncIndex = endOffset;
            // must not just check badWords[word] because object prototype
            // could have a property 'word', e.g. array has property 'values'
            // this avoids eslint error from "badWords.hasOwnProperty(word))"
            if (Object.prototype.hasOwnProperty.call(badWords, word)) {
                const attributes = badWords[word] === WC_WORLD ? { underline: true } : { strike: true };
                quill.formatText(startOffset, endOffset - startOffset, attributes, "silent");
            }
        }
        // process text after last word
        checkPunc(puncIndex, pageText.length);
        quill.setSelection(caretPos);
        maybeShowAcceptButton();
    }

    let acceptedWords = [];
    let wordChecked = false;

    async function wordCheck() {
        wordChecked = true;
        // save pageText and caretPos so we can check if they have changed
        // before redrawing the page
        pageText = quill.getText();
        ({ index: caretPos } = quill.getSelection(true));
        try {
            const wcData = await ajax("PUT", `v1/projects/${projectId}/wordcheck`, {}, { text: pageText, accepted_words: acceptedWords, languages: languages });
            showWordCheck(wcData);
        } catch (error) {
            alert(error.message);
        }
    }

    function acceptWord(event) {
        event.stopPropagation();
        acceptedWords.push(acceptableWord);
        hide(acceptButton);
        // resubmit so all same words will be unmarked
        wordCheck();
    }

    // if accept button is activated by keyboard we do not want the key
    // to propagate up to the text div
    function keyAcceptWord(event) {
        event.preventDefault();
        if (event.key === "Enter" || event.key === " ") {
            acceptWord(event);
        }
    }

    acceptButton.addEventListener("click", acceptWord);
    acceptButton.addEventListener("keydown", keyAcceptWord);

    function onChange() {
        const { index } = quill.getSelection(true);
        let [leaf, offset] = quill.getLeaf(index);
        const format = quill.getFormat(index);
        if (format.underline || format.strike) {
            // unmark it
            quill.removeFormat(index - offset, leaf.text.length, "silent");
        }
        triggerReload();
    }

    function leave() {
        pageText = quill.getText();
        quill.setText(pageText, "silent");
        quill.history.clear();
        hide(acceptButton);
        clearTimeout(timerID);
        quill.off("text-change", onChange);
        editBox.removeEventListener("click", maybeShowAcceptButton);
        editBox.removeEventListener("keyup", maybeShowAcceptButton);
        hide(wcControlSpan);
        enterTextMode();
    }

    wcQuitButton.addEventListener("click", leave);

    return {
        enter: function () {
            quill.on("text-change", onChange);
            editBox.addEventListener("click", maybeShowAcceptButton);
            editBox.addEventListener("keyup", maybeShowAcceptButton);
            quill.enable();
            show(wcControlSpan);
            wordCheck();
        },

        leave,

        initialise: function () {
            acceptedWords = [];
            wordChecked = false;
        },

        getWCState: function () {
            return [wordChecked, acceptedWords];
        },
    };
}
