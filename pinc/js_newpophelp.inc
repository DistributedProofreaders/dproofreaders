<?php
include_once($relPath.'resolution.inc');

function get_newHelpWin_javascript($popHelpDir) {
    global $i_resolutions;

    $user = User::load_current();

    $wSize=explode("x",$i_resolutions[$user->profile->i_res]);
    $top=($wSize[1]-300)/2;
    $left=($wSize[0]-400)/2;

    return "
    function newHelpWin(wFile)
        {
        var newFeatures='toolbar=0,status=0,location=0,directories=0,menubar=0,scrollbars=1,resizable=1,width=400,height=300,top=$top,left=$left';
        window.open('$popHelpDir'+wFile,'popHelp',newFeatures);
    }
    ";
}

