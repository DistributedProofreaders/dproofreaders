<?PHP

// "ibe" for "image block enhanced"

// -----------------------------------------------------------------------------

function ibe_echo_styles( $block_width_pc, $block_height_pc )
{
?>
    A:link {
        color:#000000;
        text-decoration : none; 
    }
    A:visited {
        color:#000000;
        text-decoration : none; 
    }
    A:hover {
        color:#003300;
        font-weight: bold;
        text-decoration : none; 
    }
    A:active {
        color:#000033;
        font-weight: bold;
        text-decoration : none; 
    }
    #imagehorz {
        position:absolute;
        left:25px;
        top:0px;
        width:<? echo ($block_width_pc-3)."%"; ?>;
        height:25px;
        z-index:3;
    }
    #imagevert {
        position:absolute;
        left:0px;
        top:25px;
        width:25px;
        height:<? echo ($block_height_pc-3)."%"; ?>;
        z-index:4;
    }
    #imageframe {
        position:absolute;
        top:25px;
        left:25px;
        width:<? echo ($block_width_pc-3)."%"; ?>;
        height:<? echo ($block_height_pc-3)."%"; ?>;
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
<?
}

// -----------------------------------------------------------------------------

function ibe_echo_block( $img_src, $img_width )
{
?>
    <div id="imagehorz">
        <table id="tbhorz" width="100%">
            <tr>
                <td align="left">
                    <a href="JavaScript:scrollImage('left')">
                        <img src="gfx/a1_left.png" width="11" height="11" alt="Move Left" title="Move Left" border="0">
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="JavaScript: //" onmouseover="scrollOver('left')" onmouseout="stopOver()">
                        <img src="gfx/a2_left.png" width="11" height="11" alt="Scroll Left" title="Scroll Left" border="0">
                    </a>
                </td>
                <td align="right">
                    <a href="JavaScript: //" onmouseover="scrollOver('right')" onmouseout="stopOver()">
                        <img src="gfx/a2_right.png" width="11" height="11" alt="Scroll Right" title="Scroll Right" border="0">
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    <a href="JavaScript:scrollImage('right')">
                        <img src="gfx/a1_right.png" width="11" height="11" alt="Move Right" title="Move Right" border="0">
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <div id="imagevert">
        <table id="tbvert" height="95%">
            <tr>
                <td valign="top">
                    <a href="JavaScript:scrollImage('up')">
                        <img src="gfx/a1_up.png" width="11" height="11" alt="Move Up" title="Move Up" border="0">
                    </a>
                    <p>
                    <a href="JavaScript: //" onmouseover="scrollOver('up')" onmouseout="stopOver()">
                        <img src="gfx/a2_up.png" width="11" height="11" alt="Scroll Up" title="Scroll Up" border="0">
                    </a>
                    </p>
                </td>
            </tr>
            <tr>
                <td valign="bottom">
                    <a href="JavaScript: //" onmouseover="scrollOver('down')" onmouseout="stopOver()">
                        <img src="gfx/a2_down.png" width="11" height="11" alt="Scroll Down" title="Scroll Down" border="0">
                    </a>
                    <p>
                    <a href="JavaScript:scrollImage('down')">
                        <img src="gfx/a1_down.png" width="11" height="11" alt="Move Down" title="Move Down" border="0">
                    </a>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <div id="imageframe">
        <div id="imagedisplay">
            <a href="JavaScript: //">
            <img name="scanimage" id="scanimage" title="" alt="" src="<? echo $img_src; ?>" border="0" width="<? echo $img_width; ?>">
            </a>
        </div>
    </div>
<?
}

// vim: sw=4 ts=4 expandtab
?>
