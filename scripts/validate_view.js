/* global $ proofText */
/* exported validateView */

function validateView(data, textDiv, replaceText, fixHead) {
    return new Promise( function (resolve) {
        const markArray = data.mark_array;
        const GOOD_TEXT = 0;
        const BAD_TEXT = 1;

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

        let valDiv = $("<pre>", {class: 'validator'});
        textDiv.append(valDiv);

        function leaveValidatView() {
            $(".validator").remove();
            resolve();
        }

        const quitValidButton = document.createElement("input");
        quitValidButton.type = 'button';
        quitValidButton.value = proofText.quitText;

        quitValidButton.addEventListener("click", function() {
            leaveValidatView();
        });

        const removeInvalidButton = $("<input>", {type: 'button', value: proofText.removeChars});

        removeInvalidButton.click( function () {
            let goodText = "";
            markArray.forEach(function([textPiece, type]) {
                if(type == GOOD_TEXT) {
                    goodText += textPiece;
                }
            });
            replaceText(goodText);
            leaveValidatView();
        });

        const validateControlSpan = $("<span>", {class: 'validator'});
        validateControlSpan.append(proofText.invalidChars, quitValidButton, removeInvalidButton);
        fixHead.append(validateControlSpan);

        let markedText = "";
        markArray.forEach(function([textPiece, type]) {
            if(type == GOOD_TEXT) {
                markedText += htmlSafe(textPiece);
            } else if(type == BAD_TEXT) {
                markedText += "<span class='bad-char'>" + textPiece + "</span>";
            }
        });
        valDiv.html(markedText);
    });
}
