/* eslint-disable no-undef */
/* exported scrollImage, scrollOver, stopOver, reSize, reSizeRelative, focusText, initializeStuff */
imgX = 0;
imgY = 0;
frameRef = null; // used by dp_proof.js
imgWin = null;
imgblock = null;
imgWinstyle = null;
imgstyle = null;
imgWinX = null;
imgWinY = null;
imgviewX = null;
imgviewY = null;
imgMaxX = null;
imgMaxY = null;
bPX = (document.layers || window.opera) ? "" : "px";
scrollTime = 0;
scrollAmount = 10;
scrollMaxX = 100;
scrollMaxY = 100;
imgMinSize = 20; //minimum size the image can be zoomed down to

function setMaxScrolls() {
    if (imgWinX && imgviewX && (imgviewX >= imgWinX)) {
        imgMaxX = (imgviewX - imgWinX) * -1;
    } else {
        imgMaxX = 0;
    }
    if (imgWinY && imgviewY && (imgviewY >= imgWinY)) {
        imgMaxY = (imgviewY - imgWinY) * -1;
    } else {
        imgMaxY = 0;
    }
    if (scrollMaxX > -imgMaxX) {
        scrollMaxX = -imgMaxX;
    }
    if (scrollMaxY > -imgMaxY) {
        scrollMaxY = -imgMaxY;
    }
}

function setWinWidth() {
    if (imgWin.clientWidth) {
        imgWinX = imgWin.clientWidth;
    } else if (imgWin.offsetWidth) {
        imgWinX = imgWin.offsetWidth;
    } else if (imgWintyle.clip.width) {
        imgWinX = imgWinstyle.clip.width;
    } else {
        imgWinX = 0;
    }
}
function setWinHeight() {
    if (imgWin.offsetHeight) {
        imgWinY = imgWin.offsetHeight;
    } else if (imgWinstyle.clip.height) {
        imgWinY = imgWinstyle.clip.height;
    } else {
        imgWinY = 0;
    }
}

function setViewWidth() {
    if (frameRef.getElementById('scanimage').offsetWidth) {
        imgviewX = frameRef.getElementById('scanimage').offsetWidth;
    } else if (imgblock.clientWidth) {
        imgviewX = imgblock.clientWidth;
    } else if (imgblock.offsetWidth) {
        imgviewX = imgblock.offsetWidth;
    } else if (imgstyle.clip.width) {
        imgviewX = imgstyle.clip.width;
    } else {
        imgviewX = 0;
    }
}

function setViewHeight() {
    if (imgblock.offsetHeight) {
        imgviewY = imgblock.offsetHeight;
    } else if (imgstyle.clip.height) {
        imgviewY = imgstyle.clip.height;
    } else {
        imgviewY = 0;
    }
}

function setScrollWidths() {
    setViewHeight();
    setViewWidth();
    setMaxScrolls();
}

function getNSLayer(layroot, layname) {

    for (i = 0;i < layroot.layers.length;i++) {
        curLay = layroot.layers[i];
        if (curLay.name == layname) {
            return curLay;
        } else {
            if (curLay.document.layers.length > 0) {
                curLay = getNSLayer(curLay.document, layname);
                if (curLay != null) {
                    return curLay;
                }
            }
        }
    }
    return null;
}

function setLayer() {
    if (frameRef.getElementById) {
        imgblock = frameRef.getElementById('imagedisplay');
        imgWin = frameRef.getElementById('imageframe');
    } else if (imgblock.all) {
        imgblock = frameRef.all['imagedisplay'];
        imgWin = frameRef.all['imageframe'];
    } else if (imgblock.layers) {
        imgWin = getNSLayer(frameRef, 'imageframe');
    } else {
        imgblock = null;imgWin = null;
    }
    if(imgblock && imgWin) {
        imgstyle = imgblock.style ? imgblock.style : imgblock;
        imgWinstyle = imgWin.style ? imgWin.style : imgWin;
        setWinHeight();
        setWinWidth();
        setScrollWidths();
        imgstyle.top = 0 + bPX;
        imgstyle.left = 0 + bPX;
    }
}

// ------------------------------------------------
// The following functions are the "exported" ones.

function scrollImage(sDir) {
    if (imgstyle) {
        curTop = parseInt(imgstyle.top);
        curLeft = parseInt(imgstyle.left);

        if (sDir == 'up') {
            newTop = curTop + scrollMaxY;
            if (newTop < 0) {
                imgstyle.top = newTop + bPX;
            } else {
                imgstyle.top = 0 + bPX;
            }
        } else if (sDir == 'down') {
            newTop = curTop - scrollMaxY;
            if (newTop > imgMaxY) {
                imgstyle.top = newTop + bPX;
            } else {
                imgstyle.top = imgMaxY + bPX;
            }
        } else if (sDir == 'right') {
            newLeft = curLeft - scrollMaxX;
            if (newLeft > imgMaxX) {
                imgstyle.left = newLeft + bPX;
            } else {
                imgstyle.left = imgMaxX + bPX;
            }
        } else if (sDir == 'left') {
            newLeft = curLeft + scrollMaxX;
            if (newLeft < 0) {
                imgstyle.left = newLeft + bPX;
            } else {
                imgstyle.left = 0 + bPX;
            }
        }
    }
}


function scrollOver(sDir) {
    if (imgstyle && isLded == 1) {
        curTop = parseInt(imgstyle.top);
        curLeft = parseInt(imgstyle.left);
        if (scrollTime) {
            clearTimeout(scrollTime);
        }
        if (sDir == 'up') {
            newTop = curTop + scrollAmount;
            if (newTop < 0) {
                imgstyle.top = newTop + bPX;
            } else {
                imgstyle.top = 0 + bPX;
            }
        } else if (sDir == 'down') {
            newTop = curTop - scrollAmount;
            if (newTop > imgMaxY) {
                imgstyle.top = newTop + bPX;
            } else {
                imgstyle.top = imgMaxY + bPX;
            }
        } else if (sDir == 'right') {
            newLeft = curLeft - scrollAmount;
            if (newLeft > imgMaxX) {
                imgstyle.left = newLeft + bPX;
            } else {
                imgstyle.left = imgMaxX + bPX;
            }
        } else if (sDir == 'left') {
            newLeft = curLeft + scrollAmount;
            if (newLeft < 0) {
                imgstyle.left = newLeft + bPX;
            } else {
                imgstyle.left = 0 + bPX;
            }
        }
        scrollTime = setTimeout("scrollOver('" + sDir + "')", 20);
    }
}

function stopOver() {
    clearTimeout(scrollTime);
    scrollTime = 0;
}

function reSize(newsize) {
    if (newsize < imgMinSize) {
        newsize = imgMinSize;
    }
    if (frameRef.scanimage) {
        frameRef.scanimage.width = newsize;
        setScrollWidths();
        imgstyle.top = 0 + bPX;
        imgstyle.left = 0 + bPX;
    }
    return newsize; //which allows the caller to take appropriate action
    //if the new size was not the same as what was asked for
}

function reSizeRelative(factor) {
    if (frameRef.scanimage) {
        imageObject = frameRef.scanimage;
    } else if(top.proofframe.imageframe.document.scanimage) {
        imageObject = top.proofframe.imageframe.document.scanimage;
    } else {
        return true; //if you can't get an imageObject, return without
        //doing anything else. (This is probably an error.)
    }
    if (factor == -1) {
        imageObject.width = 1000;
    } else if (imageObject.width * factor > imgMinSize) {
        imageObject.width = imageObject.width * factor;
    } else {
        imageObject.width = imgMinSize;
        //and disable the button or make the textbox match, as above.
    }
    if (frameRef.scanimage) {
        setScrollWidths();
        imgstyle.top = 0 + bPX;
        imgstyle.left = 0 + bPX;
    }
    if (imageObject.width == imgMinSize) {
        return false; //signalling the caller to disable the button...
    }
    return true;
}

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
        setLayer();
        doBU();
    } else if (wFace == 0) {
        // standard interface, non-spellcheck
        docRef = top.proofframe.textframe.document;
    } else if (wFace == 2) {
        // enhanced interface, spellcheck
        docRef = top.proofframe.document;
        setLayer();
    } else if (wFace == 3) {
        // standard interface, spellcheck
        docRef = top.proofframe.textframe.document;
    }


}
inProof = 0; // used by dp_proof.js
isLded = 0;
inFace = 0;
