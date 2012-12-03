// New version
// start of code by Carel

// This variable is set by initializeStuff() in dp_scroll.js
docRef=null;

// These two variables are set by
// top.menuframe.document.body.attributes['onLoad'] in ctrl_frame.php
markRef=null;
cRef=null;

// image width
iW='1000';

// image actual width
cW='0';
// image copy for width
var imageCopy = new Image();
imageCopy.onload = loadImageSize;


// dropdown character selection
function insertCharacter(wM)
{
    cRef.tCharsA.selectedIndex=0;
    cRef.tCharsE.selectedIndex=0;
    cRef.tCharsI.selectedIndex=0;
    cRef.tCharsO.selectedIndex=0;
    cRef.tCharsU.selectedIndex=0;
    cRef.tCharsM.selectedIndex=0;

    // These ones (now) only exist if $utf8_site is true.
    // This script doesn't have access to that variable,
    // so we test each individually.
    if (cRef.tCharsC) cRef.tCharsC.selectedIndex=0;
    if (cRef.tCharsD) cRef.tCharsD.selectedIndex=0;
    if (cRef.tCharsS) cRef.tCharsS.selectedIndex=0;
    if (cRef.tCharsZ) cRef.tCharsZ.selectedIndex=0;
    if (cRef.tCharsCyr) cRef.tCharsCyr.selectedIndex=0;
    if (cRef.tCharsOCyr) cRef.tCharsOCyr.selectedIndex=0;

    cRef.markBoxChar.value=String.fromCharCode(wM);
    insertTags(String.fromCharCode(wM),'','',true);
}

// standard tag selection
function surroundSelection(wOT,wCT)
{
    markRef.markBox.value=wOT;
    markRef.markBoxEnd.value=wCT;

    insertTags(wOT,wCT,'',false);
}

// start of general interface functions

ieW=0;
ieH=0;
ieSt=0;

function setText()
{
    //if (document.all && !ieSt)
    if (!ieSt)
    {
        ieW=docRef.editform.text_data.style.width;
        ieH=docRef.editform.text_data.style.height;
        ieSt=1;
    }
}

function fixText()
{
    //if (document.all)
    //{
        docRef.editform.text_data.style.width=ieW;
        docRef.editform.text_data.style.height=ieH;
    //}
}

function changeFontFamily(font_family)
{
    setText();
    if (font_family == 'Browser Default') {
        font_family = null;
    }
    docRef.editform.text_data.style.fontFamily = font_family;
    fixText();
}

function changeFontSize(font_size)
{
    setText();
    if (font_size == 'Browser Default') {
        font_size = null;
    }
    docRef.editform.text_data.style.fontSize = font_size;
    fixText();
}

function showAllText()
{
    alert(docRef.editform.text_data.value);
}

function showIZ()
{
    nP=docRef.editform.zmSize.value;
    zP=Math.round(iW*(nP/100));
    zP=reSize(zP); // the two Zp's will be the same unless reSize doesn't
    //succeed in making the image the requested size, i.e. if the
    //requested size is too small.
    
    docRef.editform.zmSize.value=Math.round(100*(zP/iW));
    return false;
}

function showActual()
{
    docRef.editform.zmSize.value = cW/10;
    return showIZ();
}

function loadImageSize()
{
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

function makeImageCopy()
{
    imageCopy.src = frameRef.scanimage.src;
}

function showNW()
{
    nW=window.open();
    nW.document.open();
    // SENDING PAGE-TEXT TO USER
    // We're sending it in a HTML document,
    // so we entity-encode its HTML-special characters.
    nW.document.write('<PRE>'+showNW_safe(docRef.editform.text_data.value)+'</PRE>');
    nW.document.close()
}

function showNW_safe(str)
// Entity-encode str's HTML-special characters,
// but reinstate <i> and <b> and <hr> tags,
// because we want the browser to interpret them (but nothing else) as markup.
// Also convert <sc></sc> to <spans> that do the right thing.
{
    return html_safe(str)
        .replace(/&lt;(\/?)(i|b|hr)&gt;/ig, '<$1$2>')
        .replace(/&lt;sc&gt;/ig, '<span style="font-variant: small-caps;">')
        .replace(/&lt;\/sc&gt;/ig, '</span>')
}

function html_safe(str)
// Return a version of str that is safe to send as element-content
// in an HTML document.
// That is, make the following replacements:
//    &  ->  &amp;
//    <  ->  &lt;
//    >  ->  &gt;
// This should be equivalent to PHP's
//     htmlspecialchars($str,ENT_NOQUOTES)
{
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
}

function replaceAllText(wM)
{
    docRef.editform.text_data.value=wM;
}

function doBU()
{
    if (frameRef.scanimage) {
        makeImageCopy();
        showIZ();
    }
}

// Following is taken from Wikipedia's wikibits.js:

var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var is_gecko = ((clientPC.indexOf('gecko')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('khtml') == -1) && (clientPC.indexOf('netscape/7.0')==-1));
var is_safari = ((clientPC.indexOf('AppleWebKit')!=-1) && (clientPC.indexOf('spoofer')==-1));

// apply tagOpen/tagClose to selection in textarea,
// use sampleText instead of selection if there is none
function insertTags(tagOpen, tagClose, sampleText, replace)
{
    var txtarea = docRef.editform.text_data;
    // IE
    if(docRef.selection  && !is_gecko) {
        var theSelection = docRef.selection.createRange().text;
        if(!theSelection) { theSelection=sampleText;}
        if(replace) { theSelection=''; }
        proc = processText(tagOpen,tagClose,theSelection);
        tagOpen = proc[0];
        tagClose = proc[1];
        theSelection = proc[2];
        txtarea.focus();
        if(theSelection.charAt(theSelection.length - 1) == " "){// exclude ending space char, if any
            theSelection = theSelection.substring(0, theSelection.length - 1);
            docRef.selection.createRange().text = tagOpen + theSelection + tagClose + " ";
        } else {
            docRef.selection.createRange().text = tagOpen + theSelection + tagClose;
        }

    // Mozilla
    } else if(txtarea.selectionStart || txtarea.selectionStart == '0') {
        var startPos = txtarea.selectionStart;
        var endPos = txtarea.selectionEnd;
        var scrollTop=txtarea.scrollTop;
        var myText = (txtarea.value).substring(startPos, endPos);
        if(!myText) { myText=sampleText;}
        if(replace) { myText=''; }
        proc = processText(tagOpen,tagClose,myText);
        tagOpen = proc[0];
        tagClose = proc[1];
        myText = proc[2];

        if(myText.charAt(myText.length - 1) == " "){ // exclude ending space char, if any
            subst = tagOpen + myText.substring(0, (myText.length - 1)) + tagClose + " ";
        } else {
            subst = tagOpen + myText + tagClose;
        }
        txtarea.value = txtarea.value.substring(0, startPos) + subst +
            txtarea.value.substring(endPos, txtarea.value.length);
        txtarea.focus();

        var cPos=startPos+(tagOpen.length+myText.length+tagClose.length);
        txtarea.selectionStart=cPos;
        txtarea.selectionEnd=cPos;
        txtarea.scrollTop=scrollTop;

    // All others
    } else {
        var copy_alertText=alertText;
        var re1=new RegExp("\\$1","g");
        var re2=new RegExp("\\$2","g");
        copy_alertText=copy_alertText.replace(re1,sampleText);
        copy_alertText=copy_alertText.replace(re2,tagOpen+sampleText+tagClose);
        var text;
        if (sampleText) {
            text=prompt(copy_alertText);
        } else {
            text="";
        }
        if(!text) { text=sampleText;}
        if(replace) { text=''; }
        proc = processText(tagOpen,tagClose,text);
        tagOpen = proc[0];
        tagClose = proc[1];
        text = proc[2];
        text=tagOpen+text+tagClose;
        docRef.infoform.infobox.value=text;
        // in Safari this causes scrolling
        if(!is_safari) {
            txtarea.focus();
        }
        noOverwrite=true;
    }
    // reposition cursor if possible
    if (txtarea.createTextRange) txtarea.caretPos = docRef.selection.createRange().duplicate();
}

// ----------

function isDigit(num)
{
    return num.length == 1 && "1234567890".indexOf(num) != -1;
}

function isLetter(chr)
{
    return chr.length == 1 && "abcdefghijklmnopqrstuvwxyz".indexOf(chr.toLowerCase()) != -1;
}

// Used when wrapping body text in markup or tags.
// Modify the opening and closing tags and body text depending
// on the context to make editing easier for the user.
// Return updated tags and body.
function processText(tagOpen, tagClose, bodyText)
{

    // If there's no selected text:
    // * Illustration markup may appear w/o a title, so remove the ': '.
    // * Formatting markup is redundant w/o any content, so don't produce it.
    if (bodyText == '') {
        if (tagOpen == '[Illustration: ') {
            tagOpen = '[Illustration';
        }
        
        if (tagOpen[0] == '<') {
            tagOpen = '';
            tagClose = '';
        }
    }
    
    // Handle footnote index substitution
    if (tagOpen == '[Footnote #: ') {
        // Split the selected text on the first space in the string.
        // If the first part is a letter or a number then replace the # with
        // it, otherwise wrap the selected text in the tags as normally.
        i = innerText.indexOf(' ');
        if (i != -1) {
            indexText = bodyText.substr(0, i)
            if (isLetter(indexText) || parseInt(indexText) == indexText) {
                tagOpen = tagOpen.replace('#', indexText);
                bodyText = bodyText.substr(i+1);
        }
        }
    }

    return [tagOpen, tagClose, bodyText];
}


// ----------

function transformText(transformType)
{
    var txtarea = docRef.editform.text_data;
    // There's really no point to this, it just
    // avoids some unpleasant problems later:
    var tagOpen = '';
    var tagClose = '';
    // IE
    if(docRef.selection  && !is_gecko) {
        var theSelection = docRef.selection.createRange().text;
        if(!theSelection) { theSelection=sampleText;}
        if(transformType=='title-case') { theSelection=title_case(theSelection);}
        if(transformType=='upper-case') { theSelection=theSelection.toUpperCase();}
        if(transformType=='lower-case') { theSelection=theSelection.toLowerCase();}
        if(transformType=='remove_markup') { theSelection=theSelection.replace(/<\/?([ibfg]|sc)>/gi,'');}
        txtarea.focus();
        if(theSelection.charAt(theSelection.length - 1) == " "){// exclude ending space char, if any
            theSelection = theSelection.substring(0, theSelection.length - 1);
            docRef.selection.createRange().text = tagOpen + theSelection + tagClose + " ";
        } else {
            docRef.selection.createRange().text = tagOpen + theSelection + tagClose;
        }

    // Mozilla
    } else if(txtarea.selectionStart || txtarea.selectionStart == '0') {
        var startPos = txtarea.selectionStart;
        var endPos = txtarea.selectionEnd;
        var scrollTop=txtarea.scrollTop;
        var myText = (txtarea.value).substring(startPos, endPos);
        if(!myText) { myText=sampleText;}
        if(transformType=='title-case') { myText=title_case(myText);}
        if(transformType=='upper-case') { myText=myText.toUpperCase();}
        if(transformType=='lower-case') { myText=myText.toLowerCase();}
        if(transformType=='remove_markup') { myText=myText.replace(/<\/?([ibfg]|sc)>/gi,'');}
        if(myText.charAt(myText.length - 1) == " "){ // exclude ending space char, if any
            subst = tagOpen + myText.substring(0, (myText.length - 1)) + tagClose + " ";
        } else {
            subst = tagOpen + myText + tagClose;
        }
        txtarea.value = txtarea.value.substring(0, startPos) + subst +
        txtarea.value.substring(endPos, txtarea.value.length);
        txtarea.focus();

        var cPos=startPos+(tagOpen.length+myText.length+tagClose.length);
        txtarea.selectionStart=cPos;
        txtarea.selectionEnd=cPos;
        txtarea.scrollTop=scrollTop;

    // All others
    } else {
        var copy_alertText=alertText;
        var re1=new RegExp("\\$1","g");
        var re2=new RegExp("\\$2","g");
        copy_alertText=copy_alertText.replace(re1,sampleText);
        copy_alertText=copy_alertText.replace(re2,tagOpen+sampleText+tagClose);
        var text;
        if (sampleText) {
            text=prompt(copy_alertText);
        } else {
            text="";
        }
        if(!text) { text=sampleText;}
        if(transformType=='title-case') { text=title_case(text);}
        if(transformType=='upper-case') { text=text.toUpperCase();}
        if(transformType=='lower-case') { text=text.toLowerCase();}
        if(transformType=='remove_markup') { text=text.replace(/<\/?([ibfg]|sc)>/gi,'');}
        text=tagOpen+text+tagClose;
        docRef.infoform.infobox.value=text;
        // in Safari this causes scrolling
        if(!is_safari) {
            txtarea.focus();
        }
        noOverwrite=true;
    }
    // reposition cursor if possible
    if (txtarea.createTextRange) txtarea.caretPos = docRef.selection.createRange().duplicate();
}

function title_case(str)
{
    str    = str.toLowerCase(),
    newStr = '';

    for (i = 0; i < str.length; i++)
    {
        // Capitalise the first letter, or anything after a space, newline or period.
        if (i == 0 || ' \n.'.indexOf(str[i - 1]) != -1) {
            newStr += str[i].toUpperCase();
        } else {
            newStr += str[i];
        }
    }

    newStr = lc_common(newStr);
    return newStr;
}

function lc_common(str)
{
    words = str.split(' ');
    common_lc_words = ':And:Of:The:In:On:De:Van:Am:Pm:Bc:Ad:A:An:At:By:For:La:Le:';

    for(i = 0; i < words.length; i++)
    {
        // If the word appears in the :-delimited list above, it should be lower case
        if (common_lc_words.indexOf(':' + words[i] + ':') != -1)
        {
            words[i] = words[i].toLowerCase();
        }
    }
    // Make the first letter uppercase.
    words[0] = words[0][0].toUpperCase() + words[0].substr(1);
    return words.join(' ');
}

// vim: sw=4 ts=4 expandtab
