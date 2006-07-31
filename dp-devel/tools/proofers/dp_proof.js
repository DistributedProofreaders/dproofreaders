// New version
// start of code by Carel

// These four variables set from initializeStuff() in dp_scroll.js
docRef=null;
markRef=null;
cRef=null;
// true=fancy : false=plain
cnSel=null;

//store fancy data
curSel='';
curCaret='';

// image width
iW='1000';

// image actual width
cW='0';
// image copy for width
var imageCopy = new Image();
imageCopy.onload = loadImageSize;

function getCurSel()
{if (cnSel){curSel=docRef.selection.createRange().text;}}

function getCurCaret()
{if (cnSel){docRef.editform.text_data.caretPos=docRef.selection.createRange().duplicate();}}

// dropdown character selection
function new_iMUc(wM)
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
function new_iMU(wOT,wCT)
{
markRef.markBox.value=wOT;
markRef.markBoxEnd.value=wCT;

insertTags(wOT,wCT,'',false);
}

// start of general interface functions

// Font Face Selection values
aFnt=new Array();
aFnt[1]='Courier New';
aFnt[2]='Times New Roman';
aFnt[3]='Arial';
aFnt[4]='Lucida Sans Typewriter';
aFnt[5]='monospace';
aFnt[6]='DPCustomMono2';
aFnt[7]='Courier';	// re-added per Task 400

// Font Size Selection values
bFnt=new Array();
bFnt[1]='8';
bFnt[2]='9';
bFnt[3]='10';
bFnt[4]='11';
bFnt[5]='12';
bFnt[6]='13';
bFnt[7]='14';
bFnt[8]='15';
bFnt[9]='16';
bFnt[10]='18';
bFnt[11]='20';

ieW=0;
ieH=0;
ieL=0;
ieT=0;
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

function chFFace(fF)
{if(parseInt(fF)){setText();docRef.editform.text_data.style.fontFamily=aFnt[fF];
fixText();}}

function chFSize(fS)
{if(parseInt(fS)){setText();docRef.editform.text_data.style.fontSize=bFnt[fS]+'pt';
fixText();}}

function showAllText()
{alert(docRef.editform.text_data.value);}

function showIZ()
{
nP=docRef.editform.zmSize.value;
zP=Math.round(iW*(nP/100));
reSize(zP)
docRef.editform.zmSize.value=nP;
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

// standard tag selection
function iMUO(wM)
{
	// no longer used for anything but [Blank Page]
	docRef.editform.text_data.value='[Blank Page]';
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
function insertTags(tagOpen, tagClose, sampleText, replace) {
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

function isDigit(num) {
        if (num.length>1){return false;}
        var string="1234567890";
        if (string.indexOf(num)!=-1){return true;}
        return false;
        }

function isLetter(chr) {
        if (chr.length>1){return false;}
        var string="abcdefghijklmnopqrstuvxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if (string.indexOf(chr)!=-1){return true;}
        return false;
        }

function processText(tagOpen,tagClose,innerText)
{
    // Any special processing on the contents of the selection is performed here

    if (tagOpen == '[Footnote #: ')
    {
        if (innerText.charAt(1) == ' ' && (isDigit(innerText.charAt(0)) || isLetter(innerText.charAt(0))))
				{
            tagOpen = '[Footnote ' + innerText.charAt(0) + ': ';
            innerText = innerText.substr(2);
        }
    }

    return [tagOpen, tagClose, innerText];
}



// ----------

function transformText(transformType) {
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

for ( var i = 0, l = str.length; i < l; i++ )
{
  newStr += ( i == 0 || str.charAt( i - 1 ) ==  ' ' || str.charAt( i - 1 ) ==  '\n' 
      ||str.charAt( i - 1 ) ==  '.')?
  str.charAt( i ).toUpperCase():
  str.charAt( i );
}

newStr = lc_common(newStr);
return newStr;
}

function lc_common(str)
{
  str_array = str.split(' ');
  common_lc_words = new Array('And','Of','The','In','On','De','Van','Am','Pm','Bc','Ad','A','An','At','By','For','La','Le');

  for(i=0;i<str_array.length;i++)
  {
    var cur_word = str_array[i];
    for(n=0;n<common_lc_words.length;n++) 
    {
      if (common_lc_words[n]==cur_word)
      {
        str_array[i] = cur_word.toLowerCase();
      }
    }
  }
  // Make the first letter uppercase.
  str_array[0] = str_array[0].substr(0,1).toUpperCase() + str_array[0].substr(1,str_array[0].length);
  str = str_array.join(' ');
  return str;
}

