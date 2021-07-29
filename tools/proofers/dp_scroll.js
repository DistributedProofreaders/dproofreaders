/* eslint-disable no-undef */
/* exported focusText, initializeStuff */
frameRef = null; // used by dp_proof.js

// ------------------------------------------------
// The following functions are the "exported" ones.

function focusText(noScroll = false) {
    if (isLded && inProof) {
        docRef.editform.text_data.focus({preventScroll: noScroll});
    }
}

function initializeStuff(wFace) {
    frameRef = top.proofframe.document;
    isLded = 1;
    inProof = 1;
    inFace = wFace;
    if(wFace == 1) {
        // enhanced interface, non-spellcheck
        docRef = top.proofframe.document;
    } else if (wFace == 0) {
        // standard interface, non-spellcheck
        docRef = top.proofframe.textframe.document;
    } else if (wFace == 2) {
        // enhanced interface, spellcheck
        docRef = top.proofframe.document;
    } else if (wFace == 3) {
        // standard interface, spellcheck
        docRef = top.proofframe.textframe.document;
    }
}
inProof = 0; // used by dp_proof.js
isLded = 0;
inFace = 0;
