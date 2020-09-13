<?php
include_once($relPath.'misc.inc');

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

function ibe_get_styles()
// $block_width_pc and $block_height_pc
// give the dimensions of the whole image block
// as percentages of its container box
// (which is typically the "proofframe" of the proofing interface).
{
    $user = User::load_current();

    if ($user->profile->i_layout == '1') {
        $textWidth = $user->profile->v_tframe;
        $block_width_pc = (100 - $user->profile->v_tframe) - 1;
        $textHeight=99;
        $block_height_pc=99;
        $textTop="0px";
        $textLeft = (100 - $user->profile->v_tframe) . "%";
    } else {
        $textWidth=99;
        $block_width_pc=99;
        $textHeight = $user->profile->h_tframe;
        $block_height_pc = (100 - $user->profile->h_tframe) - 1;
        $textTop = (100 - $user->profile->h_tframe) . "%";
        $textLeft="1%";
    }

    $minor_width  = '25px';
    $minor_height = '25px';

    $major_width  = ($block_width_pc  - 3) . '%';
    $major_height = ($block_height_pc - 3) . '%';

    // (This assumes that 25px is about 3% of either dimension of the proofframe;
    // i.e., that the proofframe is roughly 800 x 800px.)
    return <<<STYLES
    #controlframe {
        position:absolute;
        left:$textLeft;
        top:$textTop;
        width:$textWidth%;
        height:$textHeight%;
        /* clip:rect(0px, 100%, 100%, 0px); -- broken in MS Edge */
        text-align:center;
        z-index:6;
        overflow:auto;
    }

    #imagehorz {
        position:absolute;
        left:$minor_width;
        top:0px;
        width:$major_width;
        height:$minor_height;
        z-index:3;
    }
    #imagevert {
        position:absolute;
        left:0px;
        top:$minor_height;
        width:$minor_width;
        height:$major_height;
        z-index:4;
    }
    #imageframe {
        position:absolute;
        top:$minor_height;
        left:$minor_width;
        width:$major_width;
        height:$major_height;
        /* clip:rect(0px, 100%, 100%, 0px); -- broken in MS Edge */
        z-index:2;
        text-align:center;
        overflow:auto;
    }
    #imagedisplay {
        position:absolute;
        left:0px;
        top:0px;
        z-index:1;
    }
STYLES;
}

// -----------------------------------------------------------------------------

function ibe_echo_block( $img_src, $img_width )
{
?>
    <div id="imagehorz">
        <table id="tbhorz" width="100%">
            <tr>
                <td class="left-align">
                    <?php ibe_echo_jump_scroller( 'left', _('Move Left') ); ?>
                    &nbsp;&nbsp;&nbsp;
                    <?php ibe_echo_smooth_scroller( 'left', _('Scroll Left') ); ?>
                </td>
                <td class="right-align">
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
                <td class="top-align">
                    <?php ibe_echo_jump_scroller( 'up', _('Move Up') ); ?>
                    <p>
                    <?php ibe_echo_smooth_scroller( 'up', _('Scroll Up') ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td class="bottom-align">
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
    $label = attr_safe($label);
    echo "<a href='JavaScript:scrollImage(\"$direction\")'>";
    echo "<img src='$img_src' width='11' height='11' alt='$label' title='$label' border='0'>";
    echo "</a>";
}

function ibe_echo_smooth_scroller( $direction, $label )
{
    $img_src = "gfx/a2_$direction.png";
    $label = attr_safe($label);
    echo "<a href='JavaScript: //' onmouseover='scrollOver(\"$direction\")' onmouseout='stopOver()'>";
    echo "<img src='$img_src' width='11' height='11' alt='$label' title='$label' border='0'>";
    echo "</a>";
}

// -----------------------------------------------------------------------------

function ibe_get_js()
{
    return "
        function scrollImage(sDir) {top.scrollImage(sDir);}
        function scrollOver(sDir)  {top.scrollOver(sDir);}
        function stopOver()        {top.stopOver();}
    ";
    // This assumes that the document at the top of the frame-tree
    // (currently, the document generated by tools/proofers/proof.php)
    // references tools/proofers/dp_scroll.js, which defines (among other things)
    // the functions scrollImage(), scrollOver, and stopOver().
}

// vim: sw=4 ts=4 expandtab
