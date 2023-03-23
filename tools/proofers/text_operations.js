/* exported makeTextOps */

function makeTextOps() {
    let txtArea = null;

    function focusTrigger() {
        txtArea.focus();
        // when in wordcheck trigger actions that result from oninput
        txtArea.dispatchEvent(new Event('input'));
    }

    // apply tagOpen/tagClose to selection in textarea,
    function insertTags(tagOpen, tagClose, replace, retainSelection) {
        // txtArea can be null on entering wordcheck if no edit boxes selected
        if(!txtArea) {
            return;
        }
        var startPos = txtArea.selectionStart;
        var endPos = txtArea.selectionEnd;

        // move end fwd if spaces at end
        while ((startPos < endPos) && (txtArea.value.charAt(endPos - 1) === ' ')) {
            endPos -= 1;
        }
        var selection = (
            replace ? '' : (txtArea.value).substring(startPos, endPos));

        // When wrapping body text in markup or tags.
        // Modify the opening and closing tags and selection depending
        // on the context to make editing easier for the user.

        // If there's no selected text:
        // * Illustration markup may appear w/o a title, so remove the ': '.
        // * Formatting markup is redundant w/o any content, so don't produce it.
        if (selection === '') {
            if (tagOpen === '[Illustration: ') {
                tagOpen = '[Illustration';
            } else if ((tagOpen[0] === '<') && (tagOpen.length > 1)) {
                // do not tag empty strings but insert a single '<'
                tagOpen = '';
                tagClose = '';
            }
        }

        // Handle footnote label substitution
        if (tagOpen === '[Footnote #: ') {
            // Split the selected text on the first space in the string.
            // If the first part is a label use it in the opening tag in
            // place of '#', otherwise remove the ' #' from the opening tag.
            var label = '';
            var i = selection.indexOf(' ');
            if (i !== -1) {
                var first = selection.substr(0, i);

                // A string is a footnote label if it's a letter A-Z, or an integer > 0
                if ((/^[A-Za-z]$|^[1-9]\d*$/).test(first)) {
                    label = ' ' + first;
                    selection = selection.substr(i + 1);
                }
            }
            tagOpen = tagOpen.replace(' #', label);

            // If there's no selection, remove the label entirely.
            if (selection === '') {
                tagOpen = tagOpen.replace(': ', '');
            }
        } else if (tagOpen === '[** ' && selection) {
            tagOpen = selection + tagOpen;
        }

        var subst = tagOpen + selection + tagClose;
        txtArea.value = txtArea.value.substring(0, startPos) + subst + txtArea.value.substring(endPos);
        var curPos = startPos + subst.length;
        txtArea.setSelectionRange(retainSelection ? startPos : curPos, curPos);
        focusTrigger();
    }

    function lcCommon(str) {
        var words = str.split(' ');
        var commonLcWords = ':At:Under:Near:Upon:By:Of:In:On:For' + // prepositions
                        ':Is:Was:Are' +    // 'small' verbs
                        ':But:And:Or' +    // conjunctions
                        ':A:An:The' +      // articles
                        ':Am:Pm:Bc:Ad' +   // small caps abbreviations
                        ':De:Van:La:Le:';  // LOTE

        // Start at i=1 to avoid changing the first word (leave it Titlecased).
        // E.g. if str is "A Winter's Tale", we don't want to lowercase the "A".
        var i;
        for(i = 1; i < words.length; i += 1) {
            // If the word appears in the :-delimited list above, it should be lower case
            if (commonLcWords.indexOf(':' + words[i] + ':') !== -1) {
                words[i] = words[i].toLowerCase();
            }
        }

        return words.join(' ');
    }

    function titleCase(str) {
        str = str.toLowerCase();
        var newStr = '';
        var i;

        for (i = 0; i < str.length; i += 1) {
            // Capitalise the first letter, or anything after a space, newline or period.
            if (i === 0 || ' \n.'.indexOf(str.charAt(i - 1)) !== -1) {
                newStr += str.charAt(i).toUpperCase();
            } else {
                newStr += str.charAt(i);
            }
        }

        newStr = lcCommon(newStr);
        return newStr;
    }

    function transformText(transformType) {
        if(!txtArea) {
            return;
        }

        var startPos = txtArea.selectionStart;
        var endPos = txtArea.selectionEnd;
        var selection = (txtArea.value).substring(startPos, endPos);
        switch(transformType) {
        case 'title-case':
            selection = titleCase(selection);
            break;
        case 'upper-case':
            selection = selection.toUpperCase();
            break;
        case 'lower-case':
            selection = selection.toLowerCase();
            break;
        case 'remove_markup':
            selection = selection.replace(/<\/?([ibfg]|sc)>/gi, '');
            break;
        }
        txtArea.value = txtArea.value.substring(0, startPos) + selection + txtArea.value.substring(endPos);
        var curPos = startPos + selection.length;
        txtArea.setSelectionRange(startPos, curPos);
        focusTrigger();
    }

    function insertCharacter(wM) {
        insertTags(wM, '', true, false);
    }

    // standard tag selection
    function surroundSelection(wOT, wCT) {
        insertTags(wOT, wCT, false, true);
    }

    function setTarget(txtControl) {
        txtArea = txtControl;
    }

    function searchToEnd(str, text, searchStart) {
        return text.indexOf(str, searchStart);
    }

    function search(str, wrap = true) {
        let text = txtArea.value;
        let searchStart = txtArea.selectionEnd;

        let start = searchToEnd(str, text, searchStart);
        if(start < 0) {
            // not found
            if(!wrap || (searchStart == 0)) {
                return false;
            } else {
                // search again from start
                start = searchToEnd(str, text, 0);
            }
        }
        if(start < 0) {
            // not found
            return false;
        }
        txtArea.setSelectionRange(start, start + str.length);
        txtArea.focus();
        return true;
    }

    return {setTarget, insertCharacter, transformText, surroundSelection, search};
}