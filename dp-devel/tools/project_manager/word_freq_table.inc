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
                var auxObject = document.getElementById("aux_" + cutoff + "_" + instance);
                if(wordObject) {
                    if(cutoff < newCutoff) {
                        wordObject.style.display="none";
                        freqObject.style.display="none";
                        if(auxObject) auxObject.style.display="none";
                    } else {
                        wordObject.style.display="";
                        freqObject.style.display="";
                        if(auxObject) auxObject.style.display="";
                    }
                }
            }
        }
        document.getElementById("current_cutoff").innerHTML=newCutoff;
        return false;
    }
</script>
<? } // end of echo_cutoff_script

// given the list of cutoff options and the word count
// make sure the intial cutoff shows something
function getInitialCutoff($idealCutoff,$cutoffOptions,$wordCount) {
    // validate the idealCutoff is in $cutoffOptions
    if(!in_array($idealCutoff,$cutoffOptions))
        $idealCutoff=$cutoffOptions[count($cutoffOptions)-1];

    $nextCutoff = $idealCutoff;

    // find the biggest freq
    list($word,$freq) = each($wordCount);

    // if the idealCutoff is smaller than the biggest freq
    // there's nothing to do so continue
    if($idealCutoff < $freq) return $idealCutoff;

    // find the smallest cutoff possible that will show at least
    // one result
    foreach($cutoffOptions as $cutoff) {
        if($cutoff > $freq) break;
        $nextCutoff = $cutoff;
    }

    return $nextCutoff;
}

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
function printTableFrequencies($initialFreq,$cutoffOptions,$wordCount,$instance,$auxData=null,$auxTitle="") {
    echo '<table class="freq">';
    echo '<tr><th>' . _('Frequency') . '</th><th>' . _('Word') . '</th>';
    if(is_array($auxData))
        echo "<th>$auxTitle</th>";
    echo '</tr>';

    // we'll do it in a table so project managers can copy-paste
    // the values list into the accept textarea
    // words printed
    echo "<tr>";
    _printTableColumn($initialFreq,$cutoffOptions,$wordCount,"freq",$instance);
    _printTableColumn($initialFreq,$cutoffOptions,$wordCount,"word",$instance);
    if(is_array($auxData))
        _printTableColumn($initialFreq,$cutoffOptions,$wordCount,"aux",$instance,$auxData);
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
function _printTableColumn($initialFreq,$cutoffOptions,$wordCount,$prefix,$instance,$auxData=null) {
    $freqCutoff = 100000;
    $class="";
    if($prefix=="freq" || $prefix=="aux") $class=" class='num'";
    echo "<td$class><hr>";
    echo "<span id='${prefix}_${freqCutoff}_${instance}'>";
    $nextCutoff = array_pop($cutoffOptions);
    foreach( $wordCount as $word => $freq ) {
        if($freq < $nextCutoff) {
            while($freq < $nextCutoff && count($cutoffOptions))
                $nextCutoff=array_pop($cutoffOptions);
            $freqCutoff=$nextCutoff;

            // hide any span sections past our initialFreq
            if($freqCutoff < $initialFreq) { $style=" style='display: none;'"; }
            else { $style=""; }

            echo "</span>\n";
            echo "<span id='{$prefix}_${freqCutoff}_${instance}'$style>";
        }

        // output either $freq or $word based on $prefix
        if($prefix=="freq") echo "$freq<br>";
        elseif($prefix=="word") echo "$word<br>";
        elseif($prefix="aux") echo $auxData[$word] . "<br>";
    }
    echo "</span>\n";
    echo "</td>\n";
}

// arrays with numeric and string keys don't multisort correctly
// appending a space to the end of the numeric keys forces them
// to strings and multisort works correctly
function prep_numeric_keys_for_multisort(&$assocArray) {
    if(!is_array($assocArray)) return;
    foreach($assocArray as $key => $val) {
        if(!is_string($key)) {
            unset($assocArray[$key]);
            $assocArray["$key "]=$val;
        }
    }
}

function echo_word_freq_style() {
?>
<style type="text/css">
table.freq td, table.freq th {
    padding-right: 0.5em;
}
table.freq td {
    font-family: DPCustomMono2,monospace;
}
table.freq td.num {
    text-align: right;
}
</style>
<?
}

function echo_download_text($projectid) {
    echo "<p>You can <a href='?projectid=$projectid&amp;format=text'>download</a> a copy of the full candidate word list with frequencies for offline analysis. If you leave the frequency counts in the form found in the downloaded file (i.e., word - #), you can leave the counts in when pasting your final word list into the input box on the Edit Project page since the system will remove them.</p>";
}

function echo_cutoff_text($initialFreq,$cutoffOptions) {
    $cutoffString = get_cutoff_string($cutoffOptions);
    echo "<p>Words that appear fewer than <b><span id='current_cutoff'>$initialFreq</span></b> times are not shown. Other cutoff options are available: $cutoffString.</p>";
}


// vim: sw=4 ts=4 expandtab
?>
