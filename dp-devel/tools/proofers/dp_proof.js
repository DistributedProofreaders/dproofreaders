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
cRef.tCharsC.selectedIndex=0;
cRef.tCharsD.selectedIndex=0;
cRef.tCharsS.selectedIndex=0;
cRef.tCharsZ.selectedIndex=0;
cRef.tCharsCyr.selectedIndex=0;
cRef.tCharsOCyr.selectedIndex=0;

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

function mGR()
{
	// greek character window
	winURL='greek2ascii.php';
	newFeatures="'toolbars=0,location=0,directories=0;status=0;menubar=0,scrollbars=1,resizable=1,width=640,height=210,top=180,left=180'";
	greekWin=window.open(winURL,"gkasciiWin",newFeatures);
	greekWin.focus();
}

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
{
    return html_safe(str)
	.replace(/&lt;(\/?)(i|b|hr)&gt;/ig, '<$1$2>')
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
// copied and adapted from phpBB
function insertTags(tagOpen, tagClose, sampleText, replace) {
	var txtarea = docRef.editform.text_data;
	// IE
	if(docRef.selection  && !is_gecko) {
		var theSelection = docRef.selection.createRange().text;
		if(!theSelection) { theSelection=sampleText;}
		if(tagOpen=='<sc>') { theSelection=title_case(theSelection);}
		if(replace) { theSelection=''; }
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
		if(tagOpen=='<sc>') { myText=title_case(myText);}
		if(replace) { myText=''; }
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
		if(tagOpen=='<sc>') { text=title_case(text);}
		if(replace) { text=''; }
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
  newStr += ( i == 0 || str.charAt( i - 1 ) ==  ' ' )?
  str.charAt( i ).toUpperCase():
  str.charAt( i );
}

return newStr;
}
