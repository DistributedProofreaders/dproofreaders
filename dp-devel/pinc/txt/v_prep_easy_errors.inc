<?PHP
/*
  easy paragraph level replaces
  (will work on whole doc too, but does not include <p></p> related error handling)
  replace arrays written in long form to assist matching b=bad g=good (p=paragraph)
  un-indexed, so simply add a routine where you need it.

  example use to correct all listed errors:
    $text=str_replace($bpTxt,$gpTxt,$text);
*/

$bpTxt=array();
$gpTxt=array();

// replace tabs
$bpTxt[]='\t';
$gpTxt[]='  ';

$bpTxt[]='[tab]';
$gpTxt[]='  ';

// restore tags uploaders may insert
$bpTxt[]='&ltb&gt';
$gpTxt[]='<b>';

$bpTxt[]='&lt/b&gt';
$gpTxt[]='</b>';

$bpTxt[]='&lti&gt';
$gpTxt[]='<i>';

$bpTxt[]='&lti&gt';
$gpTxt[]='</i>';

// remove space at line-feed
$bpTxt[]='<lb> ';
$gpTxt[]='<lb>';

$bpTxt[]=' <lb>';
$gpTxt[]='<lb>';

// double single quotes
$bpTxt[]="''";
$gpTxt[]='"';

// 1 = I
$bpTxt[]=' 1 ';
$gpTxt[]=' I ';

$bpTxt[]='<lb>1 ';
$gpTxt[]='<lb>I ';

$bpTxt[]=' 1<lb>';
$gpTxt[]=' I<lb>';

$bpTxt[]='<p>1 ';
$gpTxt[]='<p>I ';

// spaced quote (erred in favor of the punctuation)
$bpTxt[]=', "';
$gpTxt[]=',"';

$bpTxt[]='. " ';
$gpTxt[]='." ';

$bpTxt[]='! " ';
$gpTxt[]='!" ';

$bpTxt[]='? " ';
$gpTxt[]='?" ';

// spaced punctuation
$bpTxt[]=' ,';
$gpTxt[]=',';

$bpTxt[]=' .';
$gpTxt[]='.';

$bpTxt[]=' ?';
$gpTxt[]='?';

$bpTxt[]=' :';
$gpTxt[]=':';

$bpTxt[]=' ;';
$gpTxt[]=';';

$bpTxt[]='[ ';
$gpTxt[]='[';

$bpTxt[]=' ]';
$gpTxt[]=']';

$bpTxt[]='{ ';
$gpTxt[]='{';

$bpTxt[]=' }';
$gpTxt[]='}';

$bpTxt[]=' !';
$gpTxt[]='!';

$bpTxt[]='( ';
$gpTxt[]='(';

$bpTxt[]=' )';
$gpTxt[]=')';

// 'period pause'
$bpTxt[]='...';
$gpTxt[]=' ... ';

$bpTxt[]=' ... .';
$gpTxt[]='....';

// contraction spacing
$bpTxt[]=" n't";
$gpTxt[]="n't";

$bpTxt[]=" 'll";
$gpTxt[]="'ll";

$bpTxt[]=" 've";
$gpTxt[]="'ve";

$bpTxt[]=" 're";
$gpTxt[]="'re";

// remove extra <lb> after <p>
$bpTxt[]='<p><lb>';
$gpTxt[]='<p>';

// spaced hyphen/emdash
$bpTxt[]=' -';
$gpTxt[]='-';

$bpTxt[]='- ';
$gpTxt[]='-';

$bpTxt[]='- -';
$gpTxt[]='--';

$bpTxt[]='-<lb>-';
$gpTxt[]='--<lb>';
?>