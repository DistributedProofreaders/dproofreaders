<?php

// this script checks to see if the 'wdiff' executable is installed
// and accessible

exec("wdiff -h",$outputArray,$returnCode);

if($returnCode!=0) {
    die("\nWarning: wdiff not found on path.\nThe GNU 'wdiff' is used by a WordCheck tool\n(\"Suggestions from diff analysis\"). If you wish to use\nthat tool, you will need to install wdiff.\nhttp://www.gnu.org/software/wdiff/wdiff.html\n\n");
}

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
