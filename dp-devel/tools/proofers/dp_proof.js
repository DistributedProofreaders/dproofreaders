// start of code byCarel

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

function selBox(wBox)
{
if (wBox=='char')
{cRef.markBoxChar.focus();
cRef.markBoxChar.select();}
else if (wBox=='start')
{markRef.markBox.focus();
markRef.markBox.select();}}

function getCurSel()
{if (cnSel){curSel=docRef.selection.createRange().text;}}

function getCurCaret()
{if (cnSel){docRef.editform.text_data.caretPos=docRef.selection.createRange().duplicate();}}

// gets character code from numeric value cC
function gCC(cC)
{return String.fromCharCode(cC);}

// fancy check for selection
function chkRange()
{if (cnSel){return (!docRef.editform.text_data.createTextRange + !docRef.editform.text_data.caretPos)? false:true;}else {return false;}}

//fancy places text cT at caret position
function putCT(cT)
{
curCaret=docRef.editform.text_data.caretPos;
curCaret.text=cT;
curSel='';
curCaret='';
docRef.editform.text_data.focus();
}

// 2 - 97
mUC=new Array(0,0,gCC(161),gCC(162),gCC(163),gCC(164),
gCC(165),gCC(166),gCC(167),gCC(168),gCC(169),
gCC(170),gCC(171),gCC(172),gCC(173),gCC(174),
gCC(175),gCC(176),gCC(177),gCC(178),gCC(179),
gCC(180),gCC(181),gCC(182),gCC(183),gCC(184),
gCC(185),gCC(186),gCC(187),gCC(188),gCC(189),
gCC(190),gCC(191),gCC(192),gCC(193),gCC(194),
gCC(195),gCC(196),gCC(197),gCC(198),gCC(199),
gCC(200),gCC(201),gCC(202),gCC(203),gCC(204),
gCC(205),gCC(206),gCC(207),gCC(208),gCC(209),
gCC(210),gCC(211),gCC(212),gCC(213),gCC(214),
gCC(215),gCC(216),gCC(217),gCC(218),gCC(219),
gCC(220),gCC(221),gCC(222),gCC(223),gCC(224),
gCC(225),gCC(226),gCC(227),gCC(228),gCC(229),
gCC(230),gCC(231),gCC(232),gCC(233),gCC(234),
gCC(235),gCC(236),gCC(237),gCC(238),gCC(239),
gCC(240),gCC(241),gCC(242),gCC(243),gCC(244),
gCC(245),gCC(246),gCC(247),gCC(248),gCC(249),
gCC(250),gCC(251),gCC(252),gCC(253),gCC(254),
gCC(255),gCC(036));

mUO=new Array();
mUO[1]='blank page';
mUO[20]='p';
mUO[21]='i';
mUO[22]='b';
mUO[23]='u';
mUO[24]='caps';
mUO[25]='sup';
mUO[26]='sub';
mUO[27]='footnote';
mUO[28]='endnote';
mUO[29]='sidenote';
mUO[30]='illustration';
mUO[31]='poetry';
mUO[32]='drama';
mUO[33]='lyrics';
mUO[34]='letter';
mUO[35]='blockquote';
mUO[36]='table';
mUO[37]='formatted';
mUO[38]='formula';
mUO[39]='math';
mUO[40]='glossary';
mUO[41]='term';
mUO[42]='definition';
mUO[43]='bibliography';
mUO[44]='header';


// character selection
function iMUc(wM)
{
 if (inProof==1)
 {
cRef.markBoxChar.value=mUC[wM];
cR=chkRange();

//plain
if (!cnSel || !cR)
{selBox('char');}

//fancy
if (cR)
{cT=mUC[wM];
putCT(cT);}
 }
}

// standard tag selection
function iMU(wM)
{
if (inProof==1)
{
wTag=mUO[wM];
wOT='<'+wTag+'>';
wCT='';
if (wM > 19)
{wCT='</'+wTag+'>';}

markRef.markBox.value=wOT;
markRef.markBoxEnd.value=wCT;

cR=chkRange();

//plain
if (!cnSel || !cR)
{selBox();}

//fancy
if (curSel != '' && docRef.selection.createRange().text == curSel)
{docRef.editform.text_data.focus();
docRef.selection.createRange().text=wOT + curSel + wCT;
curCaret='';
curSel='';
docRef.editform.text_data.focus();}
else { 
if (cR && curSel=='')
{cT=wOT;
putCT(cT);}
     }
if(wM==1)
{docRef.editform.text_data.value=wOT;}
}}


// start of general interface functions
function mGR()
{
// greek character window
winURL='greek2ascii.php';
newFeatures="'toolbars=0,location=0,directories=0;status=0;menubar=0,scrollbars=1,resizable=1,width=620,height=200,top=180,left=180'";
greekWin=window.open(winURL,"gkasciiWin",newFeatures);
greekWin.focus();
}

aFnt=new Array();
aFnt[1]='Courier New';
aFnt[2]='Times New Roman';
aFnt[3]='Arial';
aFnt[4]='Lucida Sans Typewriter';
aFnt[5]='monospaced';
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
ieH=docRef.editform.text_data.offsetHeight;
ieW=docRef.editform.text_data.offsetWidth;
ieSt=1;
}}

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
}

function showNW()
{
nW=window.open();
nW.document.open();
nW.document.write('<PRE>'+unescape(docRef.editform.text_data.value)+'</PRE>');
nW.document.close()
}

function dSI(sdir)
{
// Modified from the script by Philip Serracino Inglott

targ=top.imageframe.window;
ammt =20; 		// this is the ammout to scroll in pixels
			// should be user configurable
switch (sdir) {
  case "up" :
   targ.scrollBy(0,-ammt);
   break;
  case "down" :
   targ.scrollBy(0,ammt);
   break;
  case "left" :
   targ.scrollBy(-ammt,0);
   break;
  case "right" :
   targ.scrollBy(ammt,0);
   break;
  } 
docRef.editform.text_data.focus();
return true;}

isLded2=0;