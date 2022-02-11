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
    // now only sets inFace to indicate if in wordcheck (2) or not (0 or 1)
    frameRef = top.proofframe.document;
    isLded = 1;
    inProof = 1;
    inFace = wFace;
    docRef = top.proofframe.document;
}
inProof = 0;
isLded = 0;
inFace = 0;
