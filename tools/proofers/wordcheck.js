/* exported acceptWord evaluateWordChange markBox confirmExit */
/* global testText wordCheckMessages */

// function to accept specified words in the spellcheck
// it works by finding (span) elements with IDs in the format
// word_# and when found sets the content of the span
// to be just the word thereby removing the select and button
function acceptWord(wordIDprefix,wordNumber) {
    var wordID = wordIDprefix + "_" + wordNumber;

    // Get the original word
    var input = document.getElementById("input_" + wordID);

    // Double-check that the value hadn't changed
    if(input && input.value != input.defaultValue) {
        // what? it has? disable that button & bail!
        disableAW(wordID);
        return false;
    }
    // get the original word value
    var wordOrig = input.value;

    // loop through all bad words
    var totalNumWords = document.getElementById("sptotal").value;
    for(let wordIndex = 1; wordIndex <= totalNumWords; wordIndex++) {
        // find occurrences of the word
        var wordOccurID = wordIDprefix + "_" + wordIndex;

        // check to see if this instance has been edited already
        // by comparing the before and after words
        input = document.getElementById("input_" + wordOccurID);
        if(input && input.value == input.defaultValue) {
            // get the span
            var wordSpan = document.getElementById(wordOccurID);
            // set contents to be the word itself
            wordSpan.innerHTML = wordOrig;
            wordSpan.className = "aw";
        }
    }

    // save the word in the accepted_words list
    var acceptedWordsInput = document.getElementById("accepted_words");
    if(acceptedWordsInput.value == "") {
        acceptedWordsInput.value = wordOrig;
    } else {
        acceptedWordsInput.value = acceptedWordsInput.value + " " + wordOrig;
    }

    return false;
}

function isWordChanged(wordID) {
    var input = document.getElementById("input_" + wordID);
    let wordText = input.value;
    input.style.border = testText(wordText) ? '' : '2px solid red';
    return !(input && (wordText == input.defaultValue));
}

function evaluateWordChange(wordID) {
    if (isWordChanged(wordID))
        markPageChanged();
}

// store wordID for char pickers to use
function markBox(wordID) {
    top.txtBoxID = wordID;
}

// Disable a bad word's Unflag button
function disableAW(wordID) {
    var a = document.getElementById("a_" + wordID);

    // If the value of the input field hasn't changed, don't disable
    if (!isWordChanged(wordID)) {
        // if the current and original values are the same
        // and the button has been disabled, re-enable it
        if(a && !a.href) {
            enableAW(wordID);
        }
        return false;
    }

    // If we're here, we should be disabling the button
    var button = document.getElementById("button_" + wordID);
    if(button && a && a.href) {
        button.src = "../../graphics/Book-Plus-Small-Disabled.gif";
        button.title = wordCheckMessages.disableAwLabel;
        a.removeAttribute('href');
    }

    markPageChanged();

    return false;
}

// Enable an already-disabled button
function enableAW(wordID) {
    var button = document.getElementById("button_" + wordID);
    var a = document.getElementById("a_" + wordID);
    if(button && a) {
        button.src = "../../graphics/Book-Plus-Small.gif";
        button.title = wordCheckMessages.enableAwLabel;
        a.href = "#";
    }
    return false;
}

// Confirm exit if changes have been made
function confirmExit() {
    // see if changes have been made
    var changesMade = document.getElementById("is_changed").value;
    if(changesMade == 1) {
        return confirm(wordCheckMessages.confirmExitString);
    }

    // return true (ie: confirm exit) if no changes were made
    return true;
}

// function to mark the page as changed
function markPageChanged() {
    // mark the page as having been changed
    document.getElementById("is_changed").value = 1;

    // Disable the save as done and proof next button.
    let spsaveandnext = document.getElementById("spsaveandnext");
    spsaveandnext.title = wordCheckMessages.pageChangedError;
    spsaveandnext.disabled = true;
    return false;
}
