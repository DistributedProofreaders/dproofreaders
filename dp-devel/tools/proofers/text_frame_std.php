<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'http_headers.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'button_defs.inc');
include_once($relPath.'slim_header.inc');
include_once('PPage.inc');

// This script is invoked only for the standard interface now.
assert($userP['i_type'] == 0);

$ppage = get_requested_PPage($_GET);

slim_header("Text Frame",TRUE,FALSE);
?>
</head>
<body onload="top.initializeStuff(0)">
<center>
<form name="editform" method="post" action="processtext.php" target="proofframe">
<script language="JavaScript" type="text/javascript">
<!--
    function resize(factor)
    {
        if (factor == -1)
        {
            parent.imageframe.document.scanimage.width=1000;
        }
        else
        {
            currentsize=parent.imageframe.document.scanimage.width;
            newsize=currentsize*factor;
            parent.imageframe.document.scanimage.width=newsize;
        }
    }
//-->
</script>
<?
    $ppage->echo_proofing_textarea( FALSE );

    echo "<br>\n";

    $ppage->echo_hidden_fields();

    echo_button(SAVE_AS_IN_PROGRESS,'s');
    echo_button(SAVE_AS_DONE_AND_PROOF_NEXT,'s');
    echo_button(SAVE_AS_DONE_AND_QUIT,'s');
    echo_button(QUIT,'s');

    echo "<br>\n";

    echo_button(CHANGE_LAYOUT,'s');
    echo_button(SHOW_ALL_TEXT,'s');
    echo_button(RETURN_PAGE,'s');

    if ( $ppage->can_be_marked_bad_by($pguser) )
    {
        echo_button(REPORT_BAD_PAGE,'s');
    }

    echo "<br>\n";

    echo_button(SPELL_CHECK,'s');

    echo "<br>\n";

    $ppage->echo_info();

    echo "&nbsp;";

    $comments_url = $ppage->url_for_project_comments();
    echo _("View:")." <a href=\"$comments_url\" title=\"". _("View Project Comments in New Window")."\" target=\"viewcomments\">"._("Project Comments")."</a> ";

    $image_url = $ppage->url_for_image();
    echo "| <a href=\"$image_url\" title=\"". _("View Image in New Window")."\" target=\"lg_image\">"._("Image")."</a> ";

    echo "<br>\n";

    echo _("Image Resize:");
?>
<input title="<? echo _("Zoom Out 25%"); ?>" type="button" value="-25%"  onclick="resize(0.75)">
<input title="<? echo _("Zoom In 25%"); ?>" type="button" value="+25%" onclick="resize(1.25)">
<input title="<? echo _("Zoom to Original Size"); ?>" type="button" value="Original" onclick="resize(-1)">

</form>
</center>
</body>
</html>
<?
// vim: sw=4 ts=4 expandtab
?>
