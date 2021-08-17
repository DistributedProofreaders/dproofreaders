<?php
include_once($relPath.'misc.inc');

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
        $textHeight = 99;
        $block_height_pc = 99;
        $textTop = "0px";
        $textLeft = (100 - $user->profile->v_tframe) . "%";
    } else {
        $textWidth = 99;
        $block_width_pc = 99;
        $textHeight = $user->profile->h_tframe;
        $block_height_pc = (100 - $user->profile->h_tframe) - 1;
        $textTop = (100 - $user->profile->h_tframe) . "%";
        $textLeft = "1%";
    }

    // (This assumes that 25px is about 3% of either dimension of the proofframe;
    // i.e., that the proofframe is roughly 800 x 800px.)
    return <<<STYLES
                #controlframe {
                    position:absolute;
                    left:$textLeft;
                    top:$textTop;
                    width:$textWidth%;
                    height:$textHeight%;
                    text-align:center;
                    z-index:6;
                    overflow:auto;
                }

                #newimageframe {
                    position:absolute;
                    top: 0px;
                    width:{$block_width_pc}%;
                    height:{$block_height_pc}%;
                    z-index:2;
                    text-align:center;
                    overflow:hidden;
                    border: 1px solid black;
                }
        STYLES;
}

// -----------------------------------------------------------------------------

function ibe_echo_block()
{
    echo "<div id='newimageframe'>
    <div id='image-view'>
    </div></div>";
}
