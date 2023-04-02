/* global proofText */
/* exported validateView */

function validateView(data, textDiv, replaceText, fixHead) {
    return new Promise( function (resolve) {
        const markArray = data.mark_array;
        const GOOD_TEXT = 0;
        const BAD_TEXT = 1;

        let valDiv = document.createElement("pre");
        const validateControlSpan = document.createElement("span");

        function leaveValidatView() {
            valDiv.remove();
            validateControlSpan.remove();
            resolve();
        }

        const quitValidButton = document.createElement("input");
        quitValidButton.type = 'button';
        quitValidButton.value = proofText.quitText;
        quitValidButton.addEventListener("click", leaveValidatView);

        const removeInvalidButton = document.createElement("input");
        removeInvalidButton.type = 'button';
        removeInvalidButton.value = proofText.removeChars;
        removeInvalidButton.addEventListener("click", function() {
            let goodText = "";
            markArray.forEach(function([textPiece, type]) {
                if(type == GOOD_TEXT) {
                    goodText += textPiece;
                }
            });
            replaceText(goodText);
            leaveValidatView();
        });

        validateControlSpan.innerHTML = proofText.invalidChars;
        validateControlSpan.append(quitValidButton, removeInvalidButton);
        fixHead.append(validateControlSpan);

        markArray.forEach(function([textPiece, type]) {
            let textElement;
            if(type == GOOD_TEXT) {
                textElement = textPiece;
            } else if(type == BAD_TEXT) {
                textElement = document.createElement("span");
                textElement.classList.add('bad-char');
                textElement.append(textPiece);
            }
            valDiv.append(textElement);
        });
        textDiv.append(valDiv);
    });
}
