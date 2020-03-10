#!/usr/bin/env php
<?php
$relPath="../pinc/";
include_once($relPath."CharSuites.inc");

foreach(CharSuites::get_all() as $charsuite)
{
    echo "Validating charsuite $charsuite->name...\n";
    $nonnormalized_codepoints = $charsuite->get_nonnormalized_codepoints();
    if($nonnormalized_codepoints)
    {
        echo "ERROR: codepoints are not normalized:\n";
        foreach($nonnormalized_codepoints as $orig => $norm)
        {
            echo sprintf("    %s normalized is %s\n", $orig, $norm);
        }
        exit(1);
    }
}
