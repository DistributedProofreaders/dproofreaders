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
                        wordObject.innerHTML="";
                        freqObject.innerHTML="";
                        if(auxObject) auxObject.innerHTML="";
                    } else if(wordObject.innerHTML == "") {
                        eval("wordObject.innerHTML=cutoffArray_word_" + instance + "[cutoff];");
                        eval("freqObject.innerHTML=cutoffArray_freq_" + instance + "[cutoff];");
                        if(auxObject) eval("auxObject.innerHTML=cutoffArray_aux_" + instance + "[cutoff];");
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
    $jsArrayString="";
    echo '<table class="freq">';
    echo '<tr><th>' . _('Frequency') . '</th><th>' . _('Word') . '</th>';
    if(is_array($auxData))
        echo "<th>$auxTitle</th>";
    echo '</tr>';

    // we'll do it in a table so project managers can copy-paste
    // the values list into the accept textarea
    // words printed
    echo "<tr>";
    $jsArrayString.=_printTableColumn($initialFreq,$cutoffOptions,$wordCount,"freq",$instance);
    $jsArrayString.=_printTableColumn($initialFreq,$cutoffOptions,$wordCount,"word",$instance);
    if(is_array($auxData))
        $jsArrayString.=_printTableColumn($initialFreq,$cutoffOptions,$wordCount,"aux",$instance,$auxData);
    echo "</tr>";

    echo '</table>';

    // print out javascript array
    echo "<script language='javascript'>\n";
    echo $jsArrayString;
    echo "</script>\n";
    
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
    $returnString="var cutoffArray_${prefix}_${instance}=new Array();\n";
    $freqCutoff = 100000;
    $class="";
    $printWords=1;
    if($prefix=="freq" || $prefix=="aux") $class=" class='num'";
    echo "<td$class><hr>";
    echo "<span id='${prefix}_${freqCutoff}_${instance}'>";
    $nextCutoff = array_pop($cutoffOptions);
    foreach( $wordCount as $word => $freq ) {
        if($freq < $nextCutoff) {
            $prevCutoff = $nextCutoff;
            while($freq < $nextCutoff && count($cutoffOptions))
                $nextCutoff=array_pop($cutoffOptions);
            $freqCutoff=$nextCutoff;

            if($printWords) echo $jsString;
            $returnString.="cutoffArray_${prefix}_${instance}[${prevCutoff}]=\"$jsString\";\n";
            $jsString="";

            echo "</span>\n";

            // hide any span sections past our initialFreq
            if($freqCutoff < $initialFreq) $printWords=0;
            echo "<span id='{$prefix}_${freqCutoff}_${instance}'>";
        }

        // output either $freq or $word based on $prefix
        if($prefix=="freq") $jsString.="$freq<br>";
        elseif($prefix=="word") $jsString.=rtrim($word) . "<br>";
        elseif($prefix="aux") $jsString.=$auxData[$word] . "<br>";
    }
    $returnString.="cutoffArray_${prefix}_${instance}[${nextCutoff}]=\"$jsString\";\n";
    if($printWords) echo $jsString;
    echo "</span>\n";
    echo "</td>\n";

    return $returnString;
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
span.mono {
    font-family: DPCustomMono2,monospace;
}
</style>
<?
}

function echo_page_header($title,$projectid) {
    echo "<html>\n";
    echo "<head><title>$title</title></head>\n";
    echo "<body>\n";
    echo "<h1>$title</h1>";

    $project_text = sprintf(_("Project: %s"),get_project_name($projectid));
    echo "<h2>$project_text</h2>\n";
}

function get_project_name($projectid) {
    $res = mysql_query("SELECT * FROM projects WHERE projectid = '$projectid'");
    $ar = mysql_fetch_array($res);
    return $ar['nameofwork'];
}

function echo_page_instruction_text($wordListType) {
    if( $wordListType == "good") {
        $page_instructions_string = _("If you find any words in the results list that should not be flagged in the WordCheck interface, add them to the project's Good Words List. The most accurate way to do so probably is to copy the words from the candidate list and paste them into the Good Words input box on the Edit Project page (taking care not to overwrite any words that are already in the box).");
    } elseif( $wordListType == "bad" ) {
        $page_instructions_string = _("If you find any words in the results list that should be flagged in the WordCheck interface for extra attention by proofers, add them to the project's Bad Words List. The most accurate way to do so probably is to copy the words from the candidate list and paste them into the Bad Words input box on the Edit Project page (taking care not to overwrite any words that are already in the box).");
    } else {
        assert(FALSE);
    }

    echo "<p>$page_instructions_string</p>\n";
}

function echo_page_instruction_reiteration_text($wordListType) {
    if( $wordListType == "good") {
        $reiteration_text = _("Once again, these are only <i>candidates</i> for this project's Good Words List. Based on your analysis of the results and your knowledge of this particular project, you as a PM must decide which, if any, of the words will actually be added to the project's Good Words List.");
    } elseif( $wordListType == "bad" ) {
        $reiteration_text = _("Once again, these are only <i>candidates</i> for this project's Bad Words List. Based on your analysis of the results and your knowledge of this particular project, you as a PM must decide which, if any, of the words will actually be added to the project's Bad Words List.");
    } else {
        assert(FALSE);
    }

    echo "<p>$reiteration_text</p>\n";
}

function echo_download_text($projectid,$format) {
    $download_text = "";

    if($format == "html")
        $download_text = sprintf(_("You can <a href='%s'>download</a> a copy of the full candidate word list with frequencies for offline analysis."),"?projectid=$projectid&amp;format=file");

    $download_text .= " ";
    $download_text .= _("When you paste data into the input box on the Edit Project page, the system will remove frequency counts for you if they follow the word and are separated from the word by at least one space.");
    echo "<p>$download_text</p>\n";
}

function echo_cutoff_text($initialFreq,$cutoffOptions) {
    $cutoff_text = sprintf(_("Words that appear fewer than <b><span id='current_cutoff'>%d</span></b> times are not shown. Other cutoff options are available: %s"),$initialFreq,get_cutoff_string($cutoffOptions));
    echo "<p>$cutoff_text</p>\n";
}

function echo_wordcheck_faq_text() {
    global $code_url;
    $faq_text = sprintf(_("See also the %s for more information on the WordCheck system."),"<a href='$code_url/faq/wordcheck-faq.php'>" . _("WordCheck FAQ") . "</a>");
    echo "<p>$faq_text</p>\n";
}

function echo_any_warnings_errors($messages) {
    if ( count($messages) > 0 )
    {
        echo "<p>\n";
        echo _("The following warnings/errors were raised:") . "<br>\n";
        foreach ( $messages as $message )
            echo "$message<br>\n";
        echo "</p>\n";
    }
}

function echo_page_footer($page_gen_time=null) {
    if($page_gen_time != null) {
        $time_generate_string=sprintf(_("Time to generate this data: %.2f seconds"),$page_gen_time);
        echo "<p>$time_generate_string</p>";
    }

    echo "</body>\n";
    echo "</html>\n";
}

// vim: sw=4 ts=4 expandtab
?>
