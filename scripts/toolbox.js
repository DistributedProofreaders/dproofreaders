/* exported constructToolBox */
/* eslint no-use-before-define: "off" */

function constructToolBox(textWidget, pickerSets, roundType, proofSettings, projectId) {
    const midPickerBox = document.getElementById("mid_picker_box");
    const showPickerButton = document.getElementById("show_picker_button");
    const pickerDiv = document.getElementById("picker_box");
    const largeChar = document.getElementById("large_char");
    const toolboxText = JSON.parse(pickerDiv.dataset.toolbox_text);

    function hidePicker() {
        midPickerBox.style.display = "none";
        showPickerButton.style.display = "";
    }

    document.getElementById("hide_picker_button").addEventListener("click", function () {
        hidePicker();
        proofSettings.showPicker = false;
    });

    function showPicker() {
        midPickerBox.style.display = "flex";
        showPickerButton.style.display = "none";
    }

    showPickerButton.addEventListener("click", function () {
        showPicker();
        proofSettings.showPicker = true;
    });

    function setAlign(element, title) {
        // to ensure the accents for Greek capital letters are visible, add a text indent.
        if (title.startsWith("GREEK CAPITAL") && (title.includes("OXIA") || title.includes("VARIA"))) {
            element.style.textIndent = "0.35em";
        } else {
            element.style.textIndent = "0";
        }
    }

    function drawRow(charSet) {
        const charRow = document.createElement("div");
        for (const [character, title] of charSet) {
            const pickerButton = document.createElement("button");
            pickerButton.classList.add("picker");
            if (null == character) {
                pickerButton.classList.add("invisible");
            } else {
                pickerButton.textContent = character;
                setAlign(pickerButton, title);
                pickerButton.title = title;
            }
            pickerButton.addEventListener("click", function () {
                textWidget.replaceSelection(character);
                addMruChar(character, title);
            });
            pickerButton.addEventListener("mouseover", function () {
                largeChar.value = character;
                setAlign(largeChar, title);
            });
            charRow.appendChild(pickerButton);
        }
        return charRow;
    }

    function drawKeyBlock(keyBlock, rows) {
        // remove any children (only needed in MRU)
        // replaceChildren() needs FF78
        keyBlock.innerHTML = "";
        for (const row of rows) {
            keyBlock.appendChild(drawRow(row));
        }
    }

    const selectorRow = document.createElement("div");
    pickerDiv.appendChild(selectorRow);

    let selectedKeyBlock = null;
    let selectedSelectorButton = null;

    function setSelection(keyBlock, selectorButton) {
        if (selectedKeyBlock) {
            selectedKeyBlock.classList.remove("show");
        }
        if (selectedSelectorButton) {
            selectedSelectorButton.classList.remove("selected-tab");
        }
        selectedKeyBlock = keyBlock;
        selectedSelectorButton = selectorButton;
        keyBlock.classList.add("show");
        selectorButton.classList.add("selected-tab");
        largeChar.value = ""; // remove old character
    }

    function drawSubset(prefix, subSet) {
        const selectorButton = document.createElement("button");
        selectorButton.textContent = subSet.name;
        selectorButton.classList.add("selector_button");
        selectorButton.title = prefix + subSet.title;

        const keyBlock = document.createElement("div");
        keyBlock.classList.add("key-block");
        pickerDiv.appendChild(keyBlock);
        drawKeyBlock(keyBlock, subSet.rows);

        function selector() {
            setSelection(keyBlock, selectorButton);
        }

        selectorButton.addEventListener("click", selector);
        selectorRow.appendChild(selectorButton);
        // return keyBlock to redraw MRU
        return keyBlock;
    }

    let mruChars = [];
    const mruColumns = 12;
    const mruMax = 2 * mruColumns;
    const projMax = 10;

    function makeMruCodepoints() {
        const chars = [];
        for (const mruChar of mruChars) {
            chars.push([mruChar.char, mruChar.title]);
        }
        return [chars.slice(0, mruColumns), chars.slice(mruColumns)];
    }

    // each element is an object with time property
    function removeOldest(elements) {
        let oldTime = Number.MAX_VALUE;
        let oldIndex;
        elements.forEach(function (element, index) {
            const elTime = element.time;
            if (elTime < oldTime) {
                oldTime = elTime;
                oldIndex = index;
            }
        });
        elements.splice(oldIndex, 1);
    }

    // each mruProject has properties projectId, mruChars, time
    // is this project already in the set?
    proofSettings.mruProjects ?? (proofSettings.mruProjects = []);
    const mruProjects = proofSettings.mruProjects;
    const project = mruProjects.find(function (project) {
        return project.projectId === projectId;
    });
    if (project) {
        // already in array, update time
        project.time = Date.now();
        mruChars = project.mruChars;
    } else {
        // add new element and check length
        mruChars = [];
        if (projMax < mruProjects.push({ projectId: projectId, time: Date.now(), mruChars: mruChars })) {
            removeOldest(mruProjects);
        }
    }

    let mruSubset = { name: toolboxText.mruAbbrev, title: toolboxText.mruTitle, rows: makeMruCodepoints() };
    const mruKeyBlock = drawSubset("", mruSubset);

    for (const pickerSet of pickerSets) {
        const subSets = pickerSet.subsets;
        const prefix = pickerSet.name == "" ? "" : `${pickerSet.name}: `;
        for (const subSet of subSets) {
            drawSubset(prefix, subSet);
        }
    }

    // each mruChar has properties char, time, title
    function addMruChar(char, titletext) {
        // is the character in MRU array?
        const mruChar = mruChars.find(function (mruChar) {
            return mruChar.char === char;
        });

        if (mruChar) {
            // already in array, update time
            mruChar.time = Date.now();
        } else {
            // add new mruChar at begining and check length
            if (mruMax < mruChars.unshift({ char: char, title: titletext, time: Date.now() })) {
                removeOldest(mruChars);
            }
            drawKeyBlock(mruKeyBlock, makeMruCodepoints());
        }
    }

    proofSettings.showPicker ?? (proofSettings.showPicker = true);
    if (!proofSettings.showPicker) {
        hidePicker();
    }

    function titleCase(str) {
        function lcCommon(str) {
            const words = str.split(" ");
            const commonLcWords =
                ":At:Under:Near:Upon:By:Of:In:On:For" + // prepositions
                ":Is:Was:Are" + // 'small' verbs
                ":But:And:Or" + // conjunctions
                ":A:An:The" + // articles
                ":Am:Pm:Bc:Ad" + // small caps abbreviations
                ":De:Van:La:Le:"; // LOTE

            // Start at i=1 to avoid changing the first word (leave it Titlecased).
            // E.g. if str is "A Winter's Tale", we don't want to lowercase the "A".
            for (let i = 1; i < words.length; i += 1) {
                // If the word appears in the :-delimited list above, it should be lower case
                if (commonLcWords.indexOf(":" + words[i] + ":") !== -1) {
                    words[i] = words[i].toLowerCase();
                }
            }
            return words.join(" ");
        }

        str = str.toLowerCase();
        let newStr = "";

        for (let i = 0; i < str.length; i += 1) {
            // Capitalise the first letter, or anything after a space, newline or period.
            if (i === 0 || " \n.".indexOf(str.charAt(i - 1)) !== -1) {
                newStr += str.charAt(i).toUpperCase();
            } else {
                newStr += str.charAt(i);
            }
        }

        newStr = lcCommon(newStr);
        return newStr;
    }

    function markFootnote(selection) {
        // Handle footnote label substitution
        // Split the selected text on the first space in the string.
        // If the first part is a label use it in the opening tag
        // followed by :, otherwise just :.
        let label = "";
        if (selection !== "") {
            let i = selection.indexOf(" ");
            if (i !== -1) {
                let first = selection.substr(0, i);

                // A string is a footnote label if it's a letter A-Z, or an integer > 0
                if (/^[A-Za-z]$|^[1-9]\d*$/.test(first)) {
                    selection = selection.substr(i + 1);
                    label = ` ${first}`;
                }
            }
            label += ": ";
        }
        return `[Footnote${label}${selection}]`;
    }

    function markIllustration(text) {
        let startTag = text === "" ? "[Illustration" : "[Illustration: ";
        return startTag + text + "]";
    }

    function makeNote(text) {
        // if no selection put in [** ], else sel[** sel]
        return `${text}[** ${text}]`;
    }

    function surround(id, before, after) {
        document.getElementById(id).addEventListener("click", function () {
            textWidget.surroundSelection(before, after);
        });
    }

    function transform(id, func) {
        document.getElementById(id).addEventListener("click", function () {
            textWidget.transformSelection(func);
        });
    }

    surround("italic", "<i>", "</i>");
    surround("bold", "<b>", "</b>");
    surround("small_caps", "<sc>", "</sc>");
    surround("gesperrt", "<g>", "</g>");
    surround("antiqua", "<f>", "</f>");

    transform("remove_markup", (text) => text.replace(/<\/?([ibfg]|sc)>/gi, ""));
    transform("upper_case", (text) => text.toUpperCase());
    transform("title_case", titleCase);
    transform("lower_case", (text) => text.toLowerCase());
    surround("greek", "[Greek: ", "]");
    surround("sidenote", "[Sidenote: ", "]");
    transform("illustration", markIllustration);
    transform("note", makeNote);
    surround("brackets", "[", "]");
    surround("braces", "{", "}");
    transform("footnote", markFootnote);
    surround("slash_star", "/*\n", "\n*/");
    surround("slash_number", "/#\n", "\n#/");
    transform("thought_break", () => "\n<tb>\n");
    document.getElementById("blank_page").addEventListener("click", function () {
        textWidget.setText("[Blank Page]");
    });

    const toolStyle = roundType === "formatting" ? "inline" : "none";
    for (const element of document.getElementsByClassName("format")) {
        element.style.display = toolStyle;
    }
}
