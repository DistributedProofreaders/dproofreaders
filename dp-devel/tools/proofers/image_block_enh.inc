<?php

/*
The "image block" comprises (at the top level) 3 divs,
which are arranged by absolute positioning into a rectangular grid:

    +----------------------+-----------------------+   ---
    |                      |                       |    ^
    |                      | <div id="imagehorz">  |   $minor_height
    |                      |                       |    v
    +----------------------+-----------------------+   ---
    |                      |                       |    ^
    | <div id="imagevert"> | <div id="imageframe"> |   $major_height
    |                      |                       |    v
    +----------------------+-----------------------+   ---

    |<--- $minor_width --->|<---- $major_width --->|

*/

// "ibe" for "image block enhanced"

// -----------------------------------------------------------------------------

function ibe_echo_styles()
// $block_width_pc and $block_height_pc
// give the dimensions of the whole image block
// as percentages of its container box
// (which is typically the "proofframe" of the proofing interface).
{
    global $userP;

    if ($userP['i_layout']=='1') {
        $textWidth=$userP['v_tframe'];
        $block_width_pc=(100-$userP['v_tframe'])-1;
        $textHeight=99;
        $block_height_pc=99;
        $textTop="0px";
        $textLeft=(100-$userP['v_tframe'])."%";
    } else {
        $textWidth=99;
        $block_width_pc=99;
        $textHeight=$userP['h_tframe'];
        $block_height_pc=(100-$userP['h_tframe'])-1;
        $textTop=100-$userP['h_tframe']."%";
        $textLeft="1%";
    }

    $minor_width  = '25px';
    $minor_height = '25px';

    $major_width  = ($block_width_pc  - 3) . '%';
    $major_height = ($block_height_pc - 3) . '%';

    // (This assumes that 25px is about 3% of either dimension of the proofframe;
    // i.e., that the proofframe is roughly 800 x 800px.)
?>
    #controlframe {
        position:absolute;
        left:<?php echo $textLeft; ?>;
        top:<?php echo $textTop; ?>;
        width:<?php echo $textWidth; ?>%;
        height:<?php echo $textHeight; ?>%;
        clip:rect(0px, 100%, 100%, 0px);
        background-color:#CDCDC1;
        text-align:center;
        z-index:6;
        overflow:auto;
    }

    #imagehorz {
        position:absolute;
        left:<?php echo $minor_width; ?>;
        top:0px;
        width:<?php echo $major_width; ?>;
        height:<?php echo $minor_height; ?>;
        z-index:3;
    }
    #imagevert {
        position:absolute;
        left:0px;
        top:<?php echo $minor_height; ?>;
        width:<?php echo $minor_width; ?>;
        height:<?php echo $major_height; ?>;
        z-index:4;
    }
    #imageframe {
        position:absolute;
        top:<?php echo $minor_height; ?>;
        left:<?php echo $minor_width; ?>;
        width:<?php echo $major_width; ?>;
        height:<?php echo $major_height; ?>;
        clip:rect(0px, 100%, 100%, 0px);
        z-index:2;
        text-align:center;
        overflow:auto;
    }
    #imagedisplay {
        position:absolute;
        left:0px;
        top:0px;
        z-index:1;
        background-color:#EEDFCC;
    }
<?php
}

// -----------------------------------------------------------------------------

function ibe_echo_block( $img_src, $img_width )
{
?>
    <div id="imagehorz">
        <table id="tbhorz" width="100%">
            <tr>
                <td align="left">
                    <?php ibe_echo_jump_scroller( 'left', _('Move Left') ); ?>
                    &nbsp;&nbsp;&nbsp;
                    <?php ibe_echo_smooth_scroller( 'left', _('Scroll Left') ); ?>
                </td>
                <td align="right">
                    <?php ibe_echo_smooth_scroller( 'right', _('Scroll Right') ); ?>
                    &nbsp;&nbsp;&nbsp;
                    <?php ibe_echo_jump_scroller( 'right', _('Move Right') ); ?>
                </td>
            </tr>
        </table>
    </div>
    <div id="imagevert">
        <table id="tbvert" height="95%">
            <tr>
                <td valign="top">
                    <?php ibe_echo_jump_scroller( 'up', _('Move Up') ); ?>
                    <p>
                    <?php ibe_echo_smooth_scroller( 'up', _('Scroll Up') ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td valign="bottom">
                    <?php ibe_echo_smooth_scroller( 'down', _('Scroll Down') ); ?>
                    <p>
                    <?php ibe_echo_jump_scroller( 'down', _('Move Down') ); ?>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <div id="imageframe">
        <div id="imagedisplay">
            <a href="JavaScript: //">
            <img name="scanimage" id="scanimage" title="" alt="" src="<?php echo $img_src; ?>" border="0" width="<?php echo $img_width; ?>">
            </a>
        </div>
    </div>
<?php
}

function ibe_echo_jump_scroller( $direction, $label )
{
    $img_src = "gfx/a1_$direction.png";
    echo "<a href='JavaScript:scrollImage(\"$direction\")'>";
    echo "<img src='$img_src' width='11' height='11' alt='$label' title='$label' border='0'>";
    echo "</a>";
}

function ibe_echo_smooth_scroller( $direction, $label )
{
    $img_src = "gfx/a2_$direction.png";
    echo "<a href='JavaScript: //' onmouseover='scrollOver(\"$direction\")' onmouseout='stopOver()'>";
    echo "<img src='$img_src' width='11' height='11' alt='$label' title='$label' border='0'>";
    echo "</a>";
}

// -----------------------------------------------------------------------------

function ibe_echo_script()
{
?>
    <script language="JavaScript" type="text/javascript">
    <!--
        function scrollImage(sDir) {top.scrollImage(sDir);}
        function scrollOver(sDir)  {top.scrollOver(sDir);}
        function stopOver()        {top.stopOver();}
    // -->
    </script>
<?php
    // This assumes that the document at the top of the frame-tree
    // (currently, the document generated by tools/proofers/proof.php)
    // references tools/proofers/dp_scroll.js, which defines (among other things)
    // the functions scrollImage(), scrollOver, and stopOver().
}

// vim: sw=4 ts=4 expandtab
?>
