// start of code byCarel
docRef=null;
markRef=null;
// true=fancy : false=plain
cnSel=null;

//store fancy data
curSel='';
curCaret='';

//back up
bU='';
bR='';
iW='1000';

function selBox()
{markRef.markBox.focus();
markRef.markBox.select();}

function getCurSel()
{if (cnSel){curSel=docRef.selection.createRange().text;}}

function getCurCaret()
{if (cnSel){docRef.editform.text_data.caretPos=docRef.selection.createRange().duplicate();}}

// gets character code from numeric value cC
function gCC(cC)
{return String.fromCharCode(cC);}

mUO=new Array();
mUO[0]='';
mUO[1]='[Illustration: ';
mUO[2]='[Footnote: ';
mUO[3]='[Sidenote: ';
mUO[4]='       *       *       *       *       *';
mUO[5]='/*';
mUO[6]='[Blank Page]';
mUO[7]='<i>';

mUC=new Array();
mUC[0]='*';mUC[1]=']';mUC[2]=']';mUC[3]=']';mUC[4]='';mUC[5]='*/';mUC[6]='';mUC[7]='</i>';
mUC[8]=gCC(163);mUC[9]=gCC(161);mUC[10]=gCC(191);mUC[11]=gCC(169);mUC[12]=gCC(196);mUC[13]=gCC(228);
mUC[14]=gCC(214);mUC[15]=gCC(246);mUC[16]=gCC(220);mUC[17]=gCC(252);mUC[18]=gCC(223);mUC[19]=gCC(199);
mUC[20]=gCC(231);mUC[21]=gCC(201);mUC[22]=gCC(233);mUC[23]=gCC(202);mUC[24]=gCC(234);mUC[25]=gCC(200);
mUC[26]=gCC(232);mUC[27]=gCC(192);mUC[28]=gCC(224);mUC[29]=gCC(193);mUC[30]=gCC(225);mUC[31]=gCC(210);
mUC[32]=gCC(242);mUC[33]=gCC(243);mUC[34]=gCC(209);mUC[35]=gCC(241);mUC[36]=gCC(204);mUC[37]=gCC(236);
mUC[38]=gCC(205);mUC[39]=gCC(237);mUC[40]=gCC(198);mUC[41]=gCC(230);

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

// opening tag selection
function iMU(wM)
{
if (inProof==1)
{
markRef.markBox.value=mUO[wM];
cR=chkRange();

//plain
if (!cnSel || !cR)
{selBox();}

//fancy
if (curSel != '' && docRef.selection.createRange().text == curSel)
{docRef.editform.text_data.focus();
docRef.selection.createRange().text=mUO[wM] + curSel + mUC[wM];
curCaret='';
curSel='';
docRef.editform.text_data.focus();}
else { 
if (cR && curSel=='')
{cT=mUO[wM];
putCT(cT);}
     }
if(wM==6)
{docRef.editform.text_data.value=mUO[6];}
}}

// closing or single tag selection
function iMUe(wM)
{
if (inProof==1)
{
markRef.markBox.value=mUC[wM];
cR=chkRange();

//plain
if (!cnSel || !cR)
{selBox();}

//fancy
if (cR)
{cT=mUC[wM];
putCT(cT);}
}}

function mNA()
{
// additional character window
winURL='morenonascii.php';
newFeatures="'toolbars=0,location=0,directories=0;status=0;menubar=0,scrollbars=1,resizable=1,width=460,height=240,top=200,left=200'";
asciiWin=window.open(winURL,"nonasciiWin",newFeatures);
asciiWin.focus();
}

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

function doBU()
{chFFace(docRef.editform.fntFace.selectedIndex);
chFSize(docRef.editform.fntSize.selectedIndex);
if (top.imageframe.docRef.scanimage) {showIZ();}
}


function showNW()
{
nW=window.open();
nW.document.open();
nW.document.write('<PRE>'+unescape(docRef.editform.text_data.value)+'</PRE>');
nW.document.close()
}

//check for common errors

edM='';
//edit stage
edS=0;

//edit text
edT='';

//postion in text
edP=0;

//second half of edit
eH2='';
inE=0;

function cFCE()
{
tF=docRef.editform.text_data;
//make edit master
if (inE == 1)
{//show text for edit
edD=edM.substring(0,(cP+2));
eH2=edM.substring(cP+2);
tF.value=edD;
inE=2;}
else {
if (inE==2) {edM=tF.value+eH2;}

if (edS==0)
{
//make backup
bR=escape(tF.value);
edM=tF.value;
//write explanation in window
tF.value='Check for Common Errors \r\n\r\nPlease read the help file for more information on this feature.\r\n\r\nClick the Check for Common Errors button to Start.\r\nClick the Undo Revert button to Exit.';
edS++;
}
else if (edS==1)
{edT='\r\nDouble Space Check....\r\n'+edT;
tF.value=edT;
dCC('  ');}
else if(edS==2)
{edT='\r\nStarting Double Single Quotes Check....\r\n'+edT;
tF.value=edT;
dCC('\'\'');}
else if (edS==3)
{edT='\r\nStarting Check for Spacing before Hyphens and Em-Dashes....\r\n'+edT;
tF.value=edT;
dCC(' -');}
else if (edS==4)
{edT='\r\nStarting Check for Spacing after Hyphens and Em-Dashes....\r\n'+edT;
tF.value=edT;
dCC('- ');}
else if (edS==5){
//clean up
edT='\r\nPlease Click Check for Common Errors to Load Error Checked Document.\r\n\r\n\r\nCheck for Common Errors Complete!\r\n'+edT;
tF.value=edT;
edS++;
}
else {tF.value=edM;edS=0;edT='';}
}
tF.focus();
}
showErr='\r\nClick Check for Common Errors to show error at the bottom of the text area.\r\n';
showErr+='When the error has been corrected, click Check for Common Errors again.\r\n';

cAl=new Array();
cAl[1]='\r\nDouble Space Found!'+showErr;
cAl[2]='\r\nDouble Single Quotes Found!'+showErr;
cAl[3]='\r\nSpace before Hyphen or Em-Dash Found!'+showErr;
cAl[4]='\r\nSpace after Hyphen or Em-Dash Found!'+showErr;
cAl[5]='';
cAl[6]='';

cntErr='\r\nClick Check for Common Errors to continue.\r\n';
cAd=new Array();
cAd[1]=cntErr+'\r\nDouble Space Check Complete.';
cAd[2]=cntErr+'\r\nDouble Single Quotes Check Complete.';
cAd[3]=cntErr+'\r\nCheck for Spacing before Hyphens and Em-Dashes Complete.';
cAd[4]=cntErr+'\r\nCheck for Spacing after Hyphens and Em-Dashes Complete.';
cAd[5]='';
cAd[6]='';

function dCC(cChar)
{
tF=docRef.editform.text_data;
dS=cChar;
dL=edM.length-1;
cP=edM.indexOf(dS,edP);
if (cP != -1)
{inE=1;
edT=cAl[edS]+edT;
tF.value=edT;
//set new positions
edP=cP+1;
}
else {
edT=cAd[edS]+edT;
tF.value=edT;
edP=0;
edS++;
inE=0;
}}

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