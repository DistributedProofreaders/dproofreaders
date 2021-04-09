/* eslint-disable no-use-before-define, no-undef, camelcase */
/* exported insertCharacter, surroundSelection, changeFontFamily, changeFontSize, showActual, showNW, replaceAllText, doBU, transformText */
// This variable is set by initializeStuff() in dp_scroll.js
var docRef = null;

// image width
var iW = '1000';

// image actual width
var cW = '0';
// image copy for width
var imageCopy = new Image();
imageCopy.onload = loadImageSize;

// picker character selection
function insertCharacter(wM) {
    insertTags(wM, '', true, false);
}

// standard tag selection
function surroundSelection(wOT, wCT) {
    insertTags(wOT, wCT, false, true);
}

// start of general interface functions

ieW = 0;
ieH = 0;
ieSt = 0;

function setText() {
    //if (document.all && !ieSt)
    if (!ieSt) {
        ieW = docRef.editform.text_data.style.width;
        ieH = docRef.editform.text_data.style.height;
        ieSt = 1;
    }
}

function fixText() {
    docRef.editform.text_data.style.width = ieW;
    docRef.editform.text_data.style.height = ieH;
}

function changeFontFamily(font_index, font, fallback) {
    setText();
    // if the index is 0, we're to use the browser default
    if (font_index == 0) {
        font_family = fallback;
    } else {
        font_family = font + ", " + fallback;
    }
    docRef.editform.text_data.style.fontFamily = font_family;
    fixText();
}

function changeFontSize(font_size_index, font_size) {
    setText();
    // if the index is 0, we're to use the browser default
    if (font_size_index == 0) {
        font_size = null;
    }
    docRef.editform.text_data.style.fontSize = font_size;
    fixText();
}

function showIZ() {
    nP = docRef.editform.zmSize.value;
    zP = Math.round(iW * (nP / 100));
    zP = reSize(zP); // the two Zp's will be the same unless reSize doesn't
    //succeed in making the image the requested size, i.e. if the
    //requested size is too small.

    docRef.editform.zmSize.value = Math.round(100 * (zP / iW));
    return false;
}

function showActual() {
    docRef.editform.zmSize.value = cW / 10;
    return showIZ();
}

function loadImageSize() {
    if (imageCopy.complete) {
        // This needs to be fixed properly.
        // There is a varying maximum limit to image size, above which the
        // image vanishes from the proofing interface.  Don't know why, yet.
        if (imageCopy.width > 2000) {
            cW = 2000;
        } else {
            cW = imageCopy.width;
        }
    }
}

function makeImageCopy() {
    imageCopy.src = frameRef.scanimage.src;
}

function showNW() {
    nW = window.open();
    nW.document.open();
    // SENDING PAGE-TEXT TO USER
    // We're sending it in a HTML document,
    // so we entity-encode its HTML-special characters.
    nW.document.write('<PRE>' + showNW_safe(docRef.editform.text_data.value) + '</PRE>');
    nW.document.close();
}

// Entity-encode str's HTML-special characters,
// but reinstate <i> and <b> and <hr> tags,
// because we want the browser to interpret them (but nothing else) as markup.
// Also convert <sc></sc> to <spans> that do the right thing.
function showNW_safe(str) {
    return html_safe(str)
        .replace(/&lt;(\/?)(i|b|hr)&gt;/ig, '<$1$2>')
        .replace(/&lt;sc&gt;/ig, '<span style="font-variant: small-caps;">')
        .replace(/&lt;\/sc&gt;/ig, '</span>');
}

// Return a version of str that is safe to send as element-content
// in an HTML document.
// That is, make the following replacements:
//    &  ->  &amp;
//    <  ->  &lt;
//    >  ->  &gt;
// This should be equivalent to PHP's
//     htmlspecialchars($str,ENT_NOQUOTES)
function html_safe(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function replaceAllText(wM) {
    docRef.editform.text_data.value = wM;
}

function doBU() {
    if (frameRef.scanimage) {
        makeImageCopy();
        showIZ();
    }
}

// apply tagOpen/tagClose to selection in textarea,
function insertTags(tagOpen, tagClose, replace, retainSelection) {
    var txtArea;
    if(inFace < 2) {
        txtArea = docRef.editform.text_data;
    } else {
        // we're in wordcheck
        txtArea = docRef.getElementById("input_" + txtBoxID);
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
    txtArea.focus();
    $(txtArea).trigger("input");
}

function lc_common(str) {
    var words = str.split(' ');
    var common_lc_words = ':At:Under:Near:Upon:By:Of:In:On:For' + // prepositions
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
        if (common_lc_words.indexOf(':' + words[i] + ':') !== -1) {
            words[i] = words[i].toLowerCase();
        }
    }

    return words.join(' ');
}

function title_case(str) {
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

    newStr = lc_common(newStr);
    return newStr;
}

function transformText(transformType) {
    var txtArea = docRef.editform.text_data;
    var startPos = txtArea.selectionStart;
    var endPos = txtArea.selectionEnd;
    var selection = (txtArea.value).substring(startPos, endPos);
    switch(transformType) {
    case 'title-case':
        selection = title_case(selection);
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
    txtArea.focus();
}

