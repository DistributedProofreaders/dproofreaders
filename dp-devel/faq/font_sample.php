<?
$relPath='../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
new dbConnect();
$no_stats=1;
theme(_('Custom Font Comparison'),'header');


// get font variable from url, if any

if (isset($_GET['compare']) )
{
        $tfont = $_GET['compare'];
}
else
{
// if none, then determine user's current proofing font

        if ($userP['i_layout']==1)
        {
                $tfonti = $userP['v_fntf'];
                $tfont = $f_f[$tfonti];
        }
        else
        {
            $tfonti = $userP['h_fntf'];
            $tfont = $f_f[$tfonti];
        }
}


// use this font  -- Mike?


// echo text

$exp_text = "
        <H1>DPCustomMono2</H1>
        <P>"
        ._("DPCustomMono2 is a font adapted by DP's own big_bill,
        based on the suggestions and ideas of many experienced proofreaders,
        that helps proofreaders find mistakes.
        You can change the font that you use for proofing in your")
        ." <a href='$code_url/userprefs.php'>"._("preferences")."</a>. "
        ._("Here are some samples that compare DPCustomMono2 to other fonts.
        For information on installing and using the font, read the"). " <a href='$forums_url/viewtopic.php?p=31521#31521'>"._("Wiki about this")
        ."</a></P>

        <P>
        <FONT FACE='DPCustomMono2, Times New Roman, Times, serif'>"
        ._("If you already have the font installed,
        you will see this sentence in the DPCustomMono2 typeface.
        If this sentence looks normal, you can download DPCustomMono2 from")
        ." <a href='DPCustomMono2.ttf'>"._("here")."</a> "._("(right click the link, and choose &ldquo;Save
        Target As...&rdquo;).")
        ."</FONT></P>";

echo $exp_text;


$first = 1;

foreach ($f_f as $otherfont) {

        if (($otherfont != $tfont) AND ($otherfont != _("Browser Default")) AND ($otherfont != 'DPCustomMono2')) {
                if (! $first) {
                        echo " | ";
                } else {
                        $first = 0;
                }

                echo "<a href='font_sample.php?compare=$otherfont'>$otherfont</a>";

        }
}


echo " | "._("View original image")."</P>\n";

echo "<hr style='width: 546; text-align: left;'>";
echo "<P>";
echo _("On this page, the top font is")." <b>$tfont</b>, "._("and the bottom example is").
        " <b>DPCustomMono2</b>.</P>";

echo "
        <p><img border='0' src='".$tfont."_A.gif' width='415' height='285'></p>
        <p><img border='0' src='DPCustomMono2_A.gif' width='546' height='242'></p>
        <hr style='width: 546; text-align: left;'>
        <p><img border='0' src='".$tfont."_B.gif' width='414' height='198'></p>
        <p><img border='0' src='DPCustomMono2_B.gif' width='582' height='200'></p>
        <hr style='width: 546; text-align: left;'>
        <p><img border='0' src='".$tfont."_C.gif' width='402' height='174'></p>
        <p><img border='0' src='DPCustomMono2_C.gif' width='582' height='180'></p>
";

theme("", "footer");
?>
