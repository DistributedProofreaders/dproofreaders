/* global $ XRegExp proofText ajax */
/* exported makeValidator */

function makeValidator(iFace) {

    let validCharRegex = null;
    // this matches any character: non-mark codepoint followed by 0 or more marks
    const charMatch = XRegExp("\\PM\\pM*", "Ag");

    function htmlSafe(str) {
        // Return a version of str that is safe to send as element-content
        // in an HTML document.
        // That is, make the following replacements:
        //    &  ->  &amp;
        //    <  ->  &lt;
        //    >  ->  &gt;
        // This should be equivalent to PHP's
        //     htmlspecialchars($str,ENT_NOQUOTES)
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function showInvalidText(text) {

        function charReplacer(match) {
            if(validCharRegex.test(match)) {
                return match;
            }
            return "<span class='bad-char'>" + match + "</span>";
        }

        function charRemover(match) {
            if(validCharRegex.test(match)) {
                return match;
            }
            return "";
        }

        iFace.hideProof();

        let markedText = htmlSafe(text).replace(charMatch, charReplacer);
        let validDiv = $("<pre>", {class: 'validator'});
        validDiv.append(markedText);
        iFace.textDiv.append(validDiv);
        let quitValidButton = $("<input>", {type: 'button', value: proofText.quitText});
        let removeInvalidButton = $("<input>", {type: 'button', value: proofText.removeChars});

        quitValidButton.click(function() {
            $(".validator").remove();
            iFace.showProof();
        });

        removeInvalidButton.click( function () {
            $(".validator").remove();
            iFace.textWidget.setText(text.replace(charMatch, charRemover));
            iFace.showProof();
        });
        const validControlSpan = $("<span>", {class: 'validator'});
        validControlSpan.append(proofText.invalidChars, quitValidButton, removeInvalidButton);
        iFace.fixHead.append(validControlSpan);
    }


    function makeRegex(codepoints) {
        function removeUplus(text) {
            return text.slice(2);
        }

        let alternatives = [];
        for (let codepoint of codepoints) {
            if(codepoint.includes('-')) {
                // we have a character range
                let components = codepoint.split('-');
                alternatives.push(`[\\u{${removeUplus(components[0])}}-\\u{${removeUplus(components[1])}}]`);
            } else if(codepoint.includes('>')) {
                // we have a combined character
                let combination = '';
                for (let component of codepoint.split('>')) {
                    combination += `\\u{${removeUplus(component)}}`;
                }
                alternatives.push(combination);
            } else {
                // just a regular unicode character
                alternatives.push(`\\u{${removeUplus(codepoint)}}`);
            }
        }
        return new RegExp(`^(?:${alternatives.join('|')})$`, "u");
    }

    function getCharRegex() {
        return new Promise(function(resolve, reject) {
            if(validCharRegex) {
                resolve();
            } else {
                ajax("GET", `v1/projects/${iFace.projectId}/codepoints`)
                    .then(function (data) {
                        validCharRegex = makeRegex(data.codepoints);
                        resolve();
                    }, function (data) {
                        alert(data);
                        reject();
                    });
            }
        });
    }

    function testText(text) {
        charMatch.lastIndex = 0;
        let result;
        while(null != (result = charMatch.exec(text))) {
            if(!validCharRegex.test(result[0])) {
                return false;
            }
        }
        return true;
    }

    return function() {
        let text = iFace.textWidget.getText();
        text = text.normalize("NFC");
        iFace.textWidget.setText(text);
        return new Promise(function(resolve, reject) {
            getCharRegex()
                .then(function () {
                    if(testText(text)) {
                        resolve(text);
                    } else {
                        showInvalidText(text);
                        reject();
                    }
                });
        });
    };
}

