<?
$relPath='../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
new dbConnect();
$no_stats=1;
theme(_('Custom Font Comparison'),'header');

// if they're not logged in use arial

$tfont = 'Arial';

// determine user's current proofing font, if any

if ($userP['i_layout']==1)
{
        $tfonti = $userP['v_fntf'];
        $tfont = $f_f[$tfonti];
}  else if ((count($userP) > 0) and ($userP['i_layout']==0)) {
        $tfonti = $userP['h_fntf'];
        $tfont = $f_f[$tfonti];
}

$cfont = $tfont;


// get font variable from url, if any

if (isset($_GET['compare']) )
{
        $tfont = $_GET['compare'];
}


// use this font, unless it's DPCM2

if ($tfont != 'DPCustomMono2') {
	echo "<FONT FACE='$tfont'>";
} else {
	echo "<FONT FACE='Times New Roman, Times, serif'>";
}

if ($cfont != 'DPCustomMono2') {
    	$DPCM = 0;
} else {
	$DPCM = 1;
}



// echo text



$exp_text = "<H1><font face='DPCustomMono2'>DPCustomMono2</font>";

if (($tfont != _("Browser Default")) AND 
	($tfont != 'DPCustomMono2') AND
	($tfont != _("Monospaced"))) {

$exp_text .= " vs. $tfont";
}


$exp_text .=
	"</H1>
        <P>"
        ._("DPCustomMono2 is a font adapted by DP's own big_bill,
        based on the suggestions and ideas of many experienced proofreaders,
        that helps proofreaders find mistakes.
        You can change the font that you use for proofing in your")
        ." <a href='$code_url/userprefs.php'>"._("preferences")."</a>. "
        ._("Here are some samples that compare DPCustomMono2 to other fonts.
        For information on installing and using the font, read the"). 
	" <a href='$forums_url/viewtopic.php?p=31521#31521'>".
	_("DPWiki post")
        ."</a></P>";

echo $exp_text;

$exp_text ="
        <P>
        <FONT FACE='DPCustomMono2, $tfont'>";

if ($DPCM) {

$exp_text .= _("You currently have DPCustomMono2 selected as your default proofing font.")." ";

} 

$exp_text .= 
	_("If you already have the font installed,
        you will see this paragraph in the DPCustomMono2 typeface.
        If this paragraph's font doesn't look radically different 
	to that of the paragraph above, you can download DPCustomMono2 from")
        ." <a href='DPCustomMono2.ttf'>"._("here")."</a> "._("(right click the link, and choose &ldquo;Save
        Target As...&rdquo;). After you have installed the font please refresh this page 
	to make sure DPCustomMono2 is installed correctly. 
	") ;

if ($DPCM) {

$exp_text .= _("If DpCustomMono2 is displayed correctly in this paragraph, 
	then please browse through the gallery of font comparisons below 
	to remind yourself why it's so useful.")." ";

} 


if (($tfont == _("Browser Default")) OR ($tfont == _("Monospaced"))) {
$exp_text .= _("Since you have the non-specific font type of $tfont selected, we don't have any 
		specific comparison images to show you; but we enocurage you to browse through the gallery of
		comparisons to specific fonts from the links below, to see them juxtaposed with DPCustomMono2.");

$exp_text .= "</FONT></P>";

echo $exp_text;


$first = 1;

foreach ($f_f as $otherfont) {

        if (($otherfont != $tfont) AND 
		($otherfont != _("Browser Default")) AND 
		($otherfont != 'DPCustomMono2') AND
		($otherfont != _("Monospaced"))) {
                if (! $first) {
                        echo " | ";
                } else {
                        $first = 0;
                }

                echo "<font face='$otherfont'><a href='font_sample.php?compare=$otherfont'>$otherfont</a></font>";

        }
}


echo " | <a href='images/Original.gif'>"._("View original image")."</a></P><br><br>\n";


if (($tfont != _("Browser Default")) AND 
	($tfont != 'DPCustomMono2') AND
	($tfont != _("Monospaced"))) {

	echo "<hr style='width: 546; text-align: left;'>";
	echo "<P>";
	echo _("On this page, the top font is")." <b>$tfont</b>, "._("and the bottom example is").
        	" <b>DPCustomMono2</b>.</P>";

	echo "
	        <p><img border='0' src='images/".$tfont."_A.gif'></p>
        	<p><img border='0' src='images/DPCustomMono2_A.gif' width='546' height='242'></p>
        	<hr style='width: 546; text-align: left;'>
	        <p><img border='0' src='images/".$tfont."_B.gif'></p>
	        <p><img border='0' src='images/DPCustomMono2_B.gif' width='582' height='200'></p>
	        <hr style='width: 546; text-align: left;'>
	        <p><img border='0' src='images/".$tfont."_C.gif'></p>
	        <p><img border='0' src='images/DPCustomMono2_C.gif' width='582' height='180'></p>
	";
}

theme("", "footer");
?>
