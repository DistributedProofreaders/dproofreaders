<?

// get_cutoff_string
// Arguments:
//   cutoffOptions - list of javascript cutoff options (eg: array(1,2,3,4,5))
//
// Returns a string with javascript links to effect showing or hiding parts of
// the word list
function get_cutoff_string($cutoffOptions) {
    $cutoffString = "";
    foreach($cutoffOptions as $cutoff)
        $cutoffString .= "<a href='#' onClick='return setCutoff($cutoff);'>$cutoff</a> | ";
    $cutoffString = preg_replace("/ \| $/","",$cutoffString);
    return $cutoffString;
}

// echo_cutoff_script
// Arguments:
//   cutoffOptions - list of javascript cutoff options (eg: array(1,2,3,4,5))
//   instances     - number of word frequency tables to support
//                   (required for hiding/showing items of the correct table)
//
// Outputs the javascript used to show/hide parts of the word frequency tables
function echo_cutoff_script($cutoffOptions,$instances) {
    $cutoffJSArray = "";
    foreach($cutoffOptions as $cutoff)
        $cutoffJSArray .= "$cutoff,";
    $cutoffJSArray = preg_replace("/,$/","",$cutoffJSArray);
?>
    <script language="javascript">
    function setCutoff(newCutoff) {
        var cutoffArray = new Array(<?=$cutoffJSArray;?>);
        var instances = <?=$instances;?>;
        for(cutoffIndex in cutoffArray) {
            cutoff = cutoffArray[cutoffIndex];
            for(instance = instances; instance>=0; instance--) {
                var wordObject = document.getElementById("word_" + cutoff + "_" + instance);
                var freqObject = document.getElementById("freq_" + cutoff + "_" + instance);
                if(wordObject) {
                    if(cutoff <= newCutoff) { wordObject.style.display="none";
                        freqObject.style.display="none";
                    } else {
                        wordObject.style.display="";
                        freqObject.style.display="";
                    }
                }
            }
        }
        document.getElementById("current_cutoff").innerHTML=newCutoff;
        return false;
    }
</script>
<? } // end of echo_cutoff_script


// printTableFrequencies
// Arguments:
//   initialFreq   - initial cutoff frequency, anything after this is hidden
//   cutoffOptions - list of javascript cutoff options (eg: array(1,2,3,4,5))
//   wordCount     - a table containing the word/frequency pairs already
//                   sorted and ready for display
//   instance      - number uniquely identifying this instance, must be less
//                   than the $instances passed into echo_cutoff_script
//
// Outputs the word frequency table
function printTableFrequencies($initialFreq,$cutoffOptions,$wordCount,$instance) {
    echo '<table>';
    echo '<tr><th>' . _('Frequency') . '</th><th>' . _('Word') . '</th></tr>';

    // we'll do it in a table so project managers can copy-paste
    // the values list into the accept textarea
    // words printed
    echo "<tr>";
    _printTableColumn($initialFreq,$cutoffOptions,$wordCount,"freq",$instance);
    _printTableColumn($initialFreq,$cutoffOptions,$wordCount,"word",$instance);
    echo "</tr>";

    echo '</table>';
}

// _printTableColumn -- internal function used by printTableFrequencies
// Arguments:
//   initialFreq   - initial cutoff frequency, anything after this is hidden
//   cutoffOptions - list of javascript cutoff options (eg: array(1,2,3,4,5))
//   wordCount     - a table containing the word/frequency pairs already
//                   sorted and ready for display
//   prefix        - column specifier (either 'word' or 'freq')
//   instance      - number uniquely identifying this instance, must be less
//                   than the $instances passed into echo_cutoff_script
//
// Outputs the word frequency table
function _printTableColumn($initialFreq,$cutoffOptions,$wordCount,$prefix,$instance) {
    $freqCutoff = null;
    array_push($cutoffOptions,100000);
    echo "<td style='font-family: DPCustomMono2,monospace;'><hr>";
    foreach( $wordCount as $word => $freq ) {
        // hide any span sections past our initialFreq
        if($freq < $initialFreq) { $style=" style='display: none;'"; }
        else { $style=""; }

        // handle the case of our first span
        if($freqCutoff == null) {
            $freqCutoff=array_pop($cutoffOptions);
            echo "<span id='${prefix}_${freqCutoff}_${instance}'$style>";
        }
        // handle all other cases
        elseif($freq <= $cutoffOptions[count($cutoffOptions)-1]) {
            $freqCutoff=array_pop($cutoffOptions);
            echo "</span>\n";
            echo "<span id='{$prefix}_${freqCutoff}_${instance}'$style>";
        }

        // output either $freq or $word based on $prefix
        if($prefix=="freq") echo "$freq<br>";
        else echo "$word<br>";
    }
    echo "</span>\n";
    echo "</td>\n";
}

// vim: sw=4 ts=4 expandtab
?>
