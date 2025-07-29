/*global makeCheckBox makeLabel */
/* eslint no-unused-vars: "warn" */

import { analyse, getILTags } from "./analyse_format.js";
import translate from "./gettext.js";

// these are the default values. If the user changes anything the new
// styles are saved in local storage and reloaded next time.
// the foreground and background colours for plain text, italic, bold,
// gesperrt, smallcaps, font change, other tags, highlighting issues
// and possible issues.
// An empty color string means use default color
export const defaultStyles = {
    t: { bg: "#fffcf4", fg: "#000000" },
    i: { bg: "", fg: "#0000ff" },
    b: { bg: "", fg: "#c55a1b" },
    g: { bg: "", fg: "#8a2be2" },
    sc: { bg: "", fg: "#009700" },
    f: { bg: "", fg: "#ff0000" },
    u: { bg: "", fg: "" },
    etc: { bg: "#ffcaaf", fg: "" },
    err: { bg: "#ff0000", fg: "" },
    hlt: { bg: "#ceff09", fg: "" },
    blockquote: { bg: "#fecafe", fg: "" },
    nowrap: { bg: "#d1fcff", fg: "" },
    color: true, // colour the markup or not
    allowUnderline: false,
    defFontIndex: 0,
    suppress: {},
    initialViewMode: "no_tags",
    allowMathPreview: false,
};

export function makePreview(formatting, quill, extraSettings, statSpan) {
    const colorMarkupCheck = makeCheckBox();
    const colorMarkupControl = makeLabel([colorMarkupCheck, translate.gettext("Color markup")]);

    const hideTagsCheck = makeCheckBox();
    const hideTagsControl = makeLabel([hideTagsCheck, translate.gettext("Hide tags")]);

    const allowUnderlineCheck = makeCheckBox();
    const allowUnderlineControl = makeLabel([allowUnderlineCheck, translate.gettext("Allow <u> for underline")]);

    const allowMathCheck = makeCheckBox();
    const allowMathControl = makeLabel([allowMathCheck, translate.gettext("Preview Math")]);

    const optGrid = document.createElement("div");
    optGrid.classList.add("grid2col");
    optGrid.append(colorMarkupControl, hideTagsControl, allowMathControl, allowUnderlineControl);

    const possIssBox = document.createElement("input");
    possIssBox.type = "text";
    possIssBox.size = "1";
    possIssBox.readOnly = true;

    formatting.colors ??
        (formatting.colors = {
            err: { background: "#ff0000", color: "" },
            hlt: { background: "#ceff09", color: "" },
            i: { background: "", color: "#0000ff" },
            b: { background: "", color: "#c55a1b" },
            g: { background: "", color: "#8a2be2" },
            sc: { background: "", color: "#009700" },
            f: { background: "", color: "#ff0000" },
            "#": { background: "#fecafe", color: "" },
            "*": { background: "#d1fcff", color: "" },
            etc: { background: "#ffcaaf", color: "" },
        });

    formatting.allowUnderline ?? (formatting.allowUnderline = false);
    formatting.colorMarkup ?? (formatting.colorMarkup = true);
    formatting.hideTags ?? (formatting.hideTags = true);
    formatting.allowMathPreview ?? (formatting.allowMathPreview = false);
    const colors = formatting.colors;

    const formatStyles = {
        i: { fontStyle: "italic" },
        b: { fontWeight: "bold" },
        g: { letterSpacing: "0.25em", marginRight: "-0.25em" },
        sc: { fontVariant: "small-caps" },
        f: { fontStyle: "normal" },
        u: { textDecoration: "underline" },
    };

    // the way html treats small cap text is different to the dp convention
    // so if sc-marked text is all upper-case transform to lower
    function checkAllCap(scString) {
        // remove tags such as <i> within the string so that all
        // uppercase string is correctly identified
        scString = scString.replace(/<\/?.{1,2}>/g, "");
        return scString === scString.toUpperCase();
    }

    function showInlineStyle(text) {
        const ILTags = getILTags(formatting);
        const reStartTag = new RegExp(`<(${ILTags})>`, "g");
        let result;
        while ((result = reStartTag.exec(text)) !== null) {
            const start = result.index;
            // find the matching closing tag
            const tagStyle = result[1];
            const closeTag = `</${tagStyle}>`;
            const end = text.indexOf(closeTag, start);
            const attributes = {};
            Object.assign(attributes, formatStyles[tagStyle]);
            if ("sc" === tagStyle) {
                if (checkAllCap(text.slice(start, end))) {
                    attributes.textTransform = "lowercase";
                }
            }
            if (formatting.colorMarkup) {
                Object.assign(attributes, colors[tagStyle]);
            }
            quill.formatText(start, end - start + closeTag.length, attributes, "silent");
        }
        if (formatting.hideTags) {
            const reAnyTag = new RegExp(`</?(?:${ILTags})>`, "g");
            while ((result = reAnyTag.exec(text)) !== null) {
                quill.formatText(result.index, result[0].length, "display", "none", "silent");
            }
        }
    }

    function showOolStyle(text) {
        if (!formatting.colorMarkup) {
            return;
        }
        // out-of-line tags can be nested
        let nestLevel = 0;
        const reOolStart = /\/([#*])/g;
        const reOolAny = /(\/)[#*]|[#*]\//g;
        let result;
        while ((result = reOolStart.exec(text)) !== null) {
            // find following open or close tags
            const blockStart = result.index;
            reOolAny.lastIndex = blockStart + 2;
            let anyTagResult;
            while ((anyTagResult = reOolAny.exec(text)) !== null) {
                if (anyTagResult[1]) {
                    // open tag
                    nestLevel += 1;
                } else {
                    // closing tag
                    if (nestLevel === 0) {
                        // found the corresponding tag
                        // mark the range
                        quill.formatText(blockStart, anyTagResult.index - blockStart + 2, colors[result[1]], "silent");
                        break;
                    } else {
                        nestLevel -= 1;
                    }
                }
            }
        }
    }

    const reSubSuper = /(_|\^)\{.+?\}/g;
    // ^. ideally not [^x] or [x^]
    const reSingleSuper = /\^[^\]{]/g;

    function showEtc(text) {
        // thought break
        if (formatting.colorMarkup) {
            let index = 0;
            while ((index = text.indexOf("<tb>", index)) >= 0) {
                quill.formatText(index, 4, colors["etc"], "silent");
                index += 4;
            }
        }

        function showSubSuper(startHere, text) {
            let result;
            const attributes = {};
            if (formatting.colorMarkup) {
                Object.assign(attributes, colors["etc"]);
            }
            reSubSuper.lastIndex = startHere;
            while ((result = reSubSuper.exec(text)) !== null) {
                attributes.script = result[1] === "_" ? "sub" : "super";
                const start = result.index;
                const length = result[0].length;
                quill.formatText(start, length, attributes, "silent");
                if (formatting.hideTags) {
                    quill.formatText(start, 2, "display", "none", "silent");
                    quill.formatText(start + length - 1, 1, "display", "none", "silent");
                }
            }
            // single char superscript
            attributes.script = "super";
            reSingleSuper.lastIndex = startHere;
            while ((result = reSingleSuper.exec(text)) !== null) {
                const start = result.index;
                quill.formatText(start, 2, attributes, "silent");
                if (formatting.hideTags) {
                    quill.formatText(start, 1, "display", "none", "silent");
                }
            }
        }

        // processes the text by showSubSuper but in math mode only outside math markup
        if (!formatting.allowMathPreview) {
            showSubSuper(0, text);
        } else {
            // find whole math strings \[ ... \] or \( ... \)
            let txtOut = "";
            let mathRegex = /\\\[[^]*?\\\]|\\\([^]*?\\\)/g;
            let result;
            let startIndex = 0;
            while ((result = mathRegex.exec(text)) !== null) {
                // process from beginning or end of previous math to start of math
                showSubSuper(startIndex, text.slice(0, result.index));
                startIndex = mathRegex.lastIndex;
                let formula = result[0];
                const index = result.index;
                // display or inline style
                const formulaStyle = "[" === formula.charAt(1) ? "dformula" : "formula";
                // remove start and end tags
                formula = formula.slice(2, -2);
                // replace first character and hide the rest
                const hideLength = mathRegex.lastIndex - index - 1;
                quill.deleteText(index, 1);
                quill.insertEmbed(index, formulaStyle, formula);
                quill.formatText(index + 1, hideLength, "display", "none", "silent");
            }
            // no more found, process to end
            showSubSuper(startIndex, text);
            return txtOut;
        }
    }

    // Attempt to make an approximate representation of formatted text.
    // Remove proofers' notes.
    // Treat illustration, footnote, sidenote like ordinary text.
    // Re-wrap except for no-wrap markup.
    // First-line indent paragraphs except for continuation at start of page.
    // Extra indent for each block-quote markup.
    // Centre and embolden headings and sub-headings, except for a
    // sub-heading in block-quote or no-wrap. (subsequent sub-headings will
    // be centred).
    // Mark thought breaks by a horizontal line.

    // use quill.getContents to get formatting markup, process it. remove
    // single newlines to rewrap.
    // if this is implemented quill text will change so have to use a new
    // getText function to use if saving from here

    function reWrap(txt) {
        let mode = "para";
        const ops = [];
        const lines = txt.split("\n");
        for (const line of lines) {
            switch (mode) {
                case "para":
                    if (line === "/*") {
                        mode = "nowrap";
                        ops.push({ insert: "\n\n" });
                    } else {
                        ops.push({ insert: line + " " });
                    }
                    break;
                case "nowrap":
                    if (line === "*/") {
                        ops.push({ insert: "\n" });
                        mode = "para";
                    } else {
                        ops.push({ insert: line + "\n" });
                    }
                    break;
            }
        }
        quill.setContents({ ops: ops }, "silent");
    }

    let analysis;
    let ok;

    function showStyle() {
        const noNoteText = analysis.text;
        let reWrapMode = false;
        if (ok && reWrapMode) {
            reWrap(noNoteText);
            return;
        }

        quill.setText(noNoteText, "silent");
        if (ok) {
            showOolStyle(noNoteText);
            showInlineStyle(noNoteText);
            showEtc(noNoteText);
        }
        // mark issues after style so don't get hidden
        for (const issue of analysis.issues) {
            const attributes = {};
            attributes.title = issue.text;
            Object.assign(attributes, issue.type === 0 ? colors.hlt : colors.err);
            quill.formatText(issue.start, issue.len, attributes, "silent");
        }

        // then insert notes from end
        const noteFormat = {
            background: "white",
            color: "black",
            fontStyle: "normal",
            fontWeight: "normal",
            letterSpacing: "normal",
            marginRight: 0,
            fontVariant: "normal",
        };
        for (const note of analysis.noteArray) {
            quill.insertText(note.start, note.text, noteFormat, "silent");
        }
    }

    let pageText;
    function markFormat() {
        analysis = analyse(pageText, formatting);
        // so insert notes from end
        analysis.noteArray.reverse();
        let issArray = analysis.issues;
        let nIssues = 0;
        let possIss = 0;
        issArray.forEach(function (issue) {
            if (issue.type === 1) {
                nIssues += 1;
            } else {
                possIss += 1;
            }
        });
        // ok true if no errors which would cause showstyle() or reWrap() to fail
        ok = nIssues === 0;
        possIssBox.value = possIss;
        showStyle();
    }

    colorMarkupCheck.addEventListener("change", function () {
        formatting.colorMarkup = this.checked;
        showStyle();
    });
    colorMarkupCheck.checked = formatting.colorMarkup;

    hideTagsCheck.addEventListener("change", function () {
        formatting.hideTags = this.checked;
        showStyle();
    });
    hideTagsCheck.checked = formatting.hideTags;

    allowMathCheck.addEventListener("change", function () {
        formatting.allowMathPreview = this.checked;
        markFormat();
    });
    allowMathCheck.checked = formatting.allowMathPreview;

    allowUnderlineCheck.addEventListener("change", function () {
        formatting.allowUnderline = this.checked;
        markFormat();
    });
    allowUnderlineCheck.checked = formatting.allowUnderline;

    function leave() {
        // restore text with no marking.
        quill.setText(pageText, "silent");
        // it should be possible to suspend history while in preview
        // since text is unchanged by using "silent" but doesn't work
        quill.history.clear();
        extraSettings.replaceChildren();
        statSpan.replaceChildren();
        quill.enable();
    }

    return {
        enter: function () {
            quill.enable(false);
            // save text so can restore when leave formatting mode
            pageText = quill.getText();
            extraSettings.append(optGrid);
            statSpan.append("poss. iss: ", possIssBox);
            markFormat();
        },
        leave,
    };
}
