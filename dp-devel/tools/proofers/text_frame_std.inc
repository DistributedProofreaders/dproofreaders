<?php
include_once($relPath.'dp_main.inc');
include_once($relPath.'button_defs.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc');

function echo_text_frame_std( $ppage )
{
    global $pguser;

    slim_header( _("Text Frame"),TRUE,FALSE);
    ?>
    </head>
    <body onload="top.initializeStuff(0)">
    <center>
    <form name="editform" id="editform" method="post" action="processtext.php" target="proofframe">
        <?php

        $ppage->echo_proofing_textarea();

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
        echo _("View:")." <a href=\"$comments_url\" title=\"". attr_safe(_("View Project Comments in New Window"))."\" target=\"viewcomments\">"._("Project Comments")."</a> ";

        $image_url = $ppage->url_for_image();
        echo "| <a href=\"$image_url\" title=\"". attr_safe(_("View Image in New Window"))."\" target=\"lg_image\">"._("Image")."</a> ";

        echo "<br>\n";

        echo _("Image Resize:");
        ?>
        <input title="<?php echo attr_safe(_("Zoom Out 25%")); ?>" id="zoomout_button" type="button" value="-25%" onclick="top.reSizeRelative(0.75)?true:this.disabled=true;">
        <input title="<?php echo attr_safe(_("Zoom In 25%")); ?>" type="button" value="+25%" onclick="top.reSizeRelative(1.25); document.getElementById('zoomout_button').disabled=false;">
        <input title="<?php echo attr_safe(_("Zoom to Original Size")); ?>" type="button" value="<?php echo attr_safe(_("Original")); ?>" onclick="top.reSizeRelative(-1); document.getElementById('zoomout_button').disabled=false;">

    </form>
    </center>
    </body>
    </html>
    <?php
}
// vim: sw=4 ts=4 expandtab
?>
